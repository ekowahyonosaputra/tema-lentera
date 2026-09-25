<?php
/**
 * Lentera Theme — License Verification Engine v4
 *
 * Endpoint resmi:
 * GET https://desakami.my.id/api/verify_lisensi.php
 *     ?api_key=sk_live_xxx
 *     &lisensi=LNT-XXXX-XXXX-XXXX-XXXX
 *     &domain=opensid.beitsolution.id
 *     &nomor_seri=XXXX-XXXX-XXXX-XXXX
 */
defined('BASEPATH') || exit('No direct script access allowed');

const LNT_API_KEY      = 'sk_live_e1573a1b1bb7c4d9f07a208cb5912f15';
const LNT_API_HOST     = 'desakami.my.id';
const LNT_API_PATH     = '/api/verify_lisensi.php';
const LNT_CACHE_HOURS  = 24;        // jam — cek ulang tiap hari
const LNT_GRACE_DAYS   = 7;         // hari grace jika server down

/** Nomor seri unik per instalasi (deterministik, aman ditampilkan publik) */
function lntr_seri(): string
{
    $hash = strtoupper(substr(md5('lentera_' . ($_SERVER['HTTP_HOST'] ?? '') . '_' . get_app_key()), 0, 16));
    return implode('-', str_split($hash, 4));
}

/**
 * Panggil API verifikasi dengan parameter yang sesuai dokumentasi resmi.
 * Strategi: CURLOPT_RESOLVE (same-server fix) → cURL biasa → file_get_contents
 */
function lntr_api_call(array $params): ?array
{
    $host = LNT_API_HOST;
    $qs   = http_build_query($params);
    $log  = DESAPATH . '.lntr.log';

    $attempts = [
        // 1. cURL GET + RESOLVE ke 127.0.0.1 (same-server fix)
        ['url' => "http://{$host}" . LNT_API_PATH . "?{$qs}",  'resolve' => true,  'method' => 'GET'],
        ['url' => "https://{$host}" . LNT_API_PATH . "?{$qs}", 'resolve' => true,  'method' => 'GET'],
        // 2. cURL GET biasa (beda server / IP eksternal)
        ['url' => "https://{$host}" . LNT_API_PATH . "?{$qs}", 'resolve' => false, 'method' => 'GET'],
        ['url' => "http://{$host}" . LNT_API_PATH . "?{$qs}",  'resolve' => false, 'method' => 'GET'],
        // 3. file_get_contents (hosting tanpa cURL)
        ['url' => "https://{$host}" . LNT_API_PATH . "?{$qs}", 'resolve' => false, 'method' => 'FGC'],
    ];

    foreach ($attempts as $i => $a) {
        $raw = null;

        if ($a['method'] === 'FGC') {
            $ctx = stream_context_create([
                'http' => ['timeout' => 15, 'follow_location' => 1, 'ignore_errors' => true,
                           'user_agent' => 'LenteraTheme/' . THEME_VERSION],
                'ssl'  => ['verify_peer' => false, 'verify_peer_name' => false],
            ]);
            $raw = @file_get_contents($a['url'], false, $ctx);
        } elseif (function_exists('curl_init')) {
            $opts = [
                CURLOPT_URL            => $a['url'],
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_TIMEOUT        => 20,
                CURLOPT_CONNECTTIMEOUT => 15,
                CURLOPT_SSL_VERIFYPEER => false,
                CURLOPT_SSL_VERIFYHOST => 0,
                CURLOPT_FOLLOWLOCATION => true,
                CURLOPT_IPRESOLVE      => CURL_IPRESOLVE_V4,
                CURLOPT_USERAGENT      => 'LenteraTheme/' . THEME_VERSION,
                CURLOPT_HTTPHEADER     => ['Accept: application/json'],
            ];
            if ($a['resolve']) {
                // Bypass DNS — paksa langsung ke localhost (same-server fix)
                $opts[CURLOPT_RESOLVE] = ["{$host}:80:127.0.0.1", "{$host}:443:127.0.0.1"];
            }
            $ch   = curl_init();
            curl_setopt_array($ch, $opts);
            $raw  = curl_exec($ch);
            $cerr = curl_error($ch);
            $code = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            if ($raw === false || !empty($cerr) || $code < 200 || $code >= 300) {
                @file_put_contents($log, date('[Y-m-d H:i:s] ') . "#$i FAIL code={$code} err={$cerr}\n", FILE_APPEND);
                continue;
            }
        }

        if ($raw === null || $raw === false) {
            @file_put_contents($log, date('[Y-m-d H:i:s] ') . "#$i no-response\n", FILE_APPEND);
            continue;
        }

        @file_put_contents($log, date('[Y-m-d H:i:s] ') . "#$i OK raw=" . substr($raw, 0, 150) . "\n", FILE_APPEND);

        $data = json_decode($raw, true);
        if (!is_array($data)) {
            @file_put_contents($log, date('[Y-m-d H:i:s] ') . "#$i JSON parse fail\n", FILE_APPEND);
            continue;
        }

        return lntr_normalize($data);
    }

    return null;
}

/** Normalisasi berbagai format respons API */
function lntr_normalize(array $d): array
{
    if (isset($d['data']) && is_array($d['data'])) {
        $d = array_merge($d['data'], $d);
        unset($d['data']);
    }
    $s     = strtolower((string) ($d['status'] ?? ''));
    $valid = !empty($d['valid']) || in_array($s, ['active', 'success', 'ok', 'valid', '1'], true);
    return [
        'valid'         => (bool) $valid,
        'reason'        => $d['reason']     ?? ($valid ? 'active' : 'invalid'),
        'pesan'         => $d['message']    ?? $d['pesan']    ?? $d['msg'] ?? '',
        'nama_pemegang' => $d['name']       ?? $d['nama']     ?? $d['holder'] ?? '',
        'expired_at'    => $d['expired_at'] ?? $d['expires']  ?? null,
        'raw_status'    => $s,
    ];
}

/** Cek lisensi — dengan cache 24 jam dan grace period 7 hari */
function lntr_cek(): array
{
    $kode   = trim(theme_config('kode_aktivasi', ''));
    $domain = strtolower($_SERVER['HTTP_HOST'] ?? 'localhost');
    $cfile  = DESAPATH . '.lntr_cache';
    $now    = time();

    if (empty($kode)) {
        return ['valid' => false, 'reason' => 'empty'];
    }

    // Baca cache
    $cached = null;
    if (is_file($cfile)) {
        $c = json_decode(@file_get_contents($cfile) ?: '', true);
        if (is_array($c) && ($c['lisensi'] ?? '') === $kode && ($c['domain'] ?? '') === $domain) {
            $cached = $c;
        }
    }

    // Cache masih segar
    if ($cached && ($now - ($cached['ts'] ?? 0)) < (LNT_CACHE_HOURS * 3600)) {
        return $cached;
    }

    // Reset log lama
    @unlink(DESAPATH . '.lntr.log');

    // Panggil API dengan parameter resmi
    $result = lntr_api_call([
        'api_key'    => LNT_API_KEY,
        'lisensi'    => $kode,
        'domain'     => $domain,
        'nomor_seri' => lntr_seri(),          // ← parameter resmi sesuai dokumentasi
    ]);

    if ($result === null) {
        // Grace period jika sebelumnya valid
        if ($cached && !empty($cached['valid']) && ($now - ($cached['ts'] ?? 0)) < (LNT_GRACE_DAYS * 86400)) {
            return array_merge($cached, ['grace' => true]);
        }
        return [
            'valid'  => false,
            'reason' => 'api_error',
            '_log'   => @file_get_contents(DESAPATH . '.lntr.log') ?: '',
        ];
    }

    // Simpan cache
    $save = array_merge($result, ['ts' => $now, 'lisensi' => $kode, 'domain' => $domain]);
    @file_put_contents($cfile, json_encode($save));

    return $save;
}

// ── Eksekusi ──────────────────────────────────────────────────────────────
$_lntr = lntr_cek();

if (!empty($_lntr['valid'])) {
    return; // ✅ Lisensi valid — lanjutkan render tema
}

// ── Halaman Aktivasi ──────────────────────────────────────────────────────
$_seri   = lntr_seri();
$_reason = $_lntr['reason'] ?? '';
$_pesan  = $_lntr['pesan']  ?? '';
$_domain = strtolower($_SERVER['HTTP_HOST'] ?? '');
$_log    = $_lntr['_log'] ?? @file_get_contents(DESAPATH . '.lntr.log') ?: '';

?><!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width,initial-scale=1">
<title>Aktivasi Lentera — <?= NAMA_DESA ?></title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
body{font-family:'Helvetica Neue',Helvetica,Arial,sans-serif;background:#f0f4f8;min-height:100vh;display:flex;align-items:center;justify-content:center;padding:20px}
.card{background:#fff;border-radius:18px;box-shadow:0 10px 40px rgba(0,0,0,.12);max-width:580px;width:100%;overflow:hidden}
.top{background:linear-gradient(135deg,#0d3a2a 0%,#0a5a3a 100%);color:#fff;padding:28px 32px;text-align:center}
.top h1{font-size:21px;font-weight:800;margin-bottom:4px}
.top small{font-size:12px;opacity:.8}
.body{padding:24px 28px;display:flex;flex-direction:column;gap:14px}
.alert{padding:13px 16px;border-radius:10px;font-size:13px;line-height:1.6;display:flex;gap:10px}
.warn{background:#fff8e1;border:1px solid #f5c842;color:#7a5a00}
.err{background:#fff0f0;border:1px solid #f5a0a0;color:#7a0000}
.ok{background:#e8f5e9;border:1px solid #a5d6a7;color:#1b5e20}
.seri-box{background:#f8f9fa;border:1px solid #dee2e6;border-radius:10px;padding:14px 16px}
.seri-box label{display:block;font-size:10px;font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:#888;margin-bottom:4px}
.seri{font-size:20px;font-weight:800;color:#0d3a2a;letter-spacing:.12em;font-family:monospace}
.hint{font-size:11px;color:#777;margin-top:5px}
.steps{font-size:13px;color:#374151;line-height:1.8}
.steps ol{padding-left:18px}
.btn{display:block;text-align:center;background:#25c26e;color:#fff;font-size:15px;font-weight:700;padding:13px;border-radius:10px;text-decoration:none;box-shadow:0 4px 12px rgba(37,194,110,.3)}
.btn:hover{background:#20b060;color:#fff;text-decoration:none}
.sub{font-size:12px;color:#888;text-align:center}
.sub a{color:#0d6e56}
details{border:1px solid #dee2e6;border-radius:8px;padding:10px 12px;font-size:11px;color:#555}
summary{cursor:pointer;font-weight:700;color:#374151;user-select:none}
table{width:100%;border-collapse:collapse;margin-top:8px}
td{padding:3px 6px;border-bottom:1px solid #eee;font-size:11px}
td:first-child{font-weight:600;width:120px;color:#374151}
pre{font-size:10px;white-space:pre-wrap;word-break:break-all;margin-top:8px;padding:8px;background:#fff;border:1px solid #e0e0e0;border-radius:6px;max-height:150px;overflow-y:auto;color:#c0392b}
.foot{text-align:center;font-size:10px;color:#ccc;padding:12px;border-top:1px solid #f0f0f0}
</style>
</head>
<body>
<div class="card">
  <div class="top">
    <h1>🔑 Aktivasi Tema Lentera</h1>
    <small><?= NAMA_DESA ?> · <?= htmlspecialchars($_domain) ?></small>
  </div>
  <div class="body">

    <?php if ($_reason === 'empty'): ?>
    <div class="alert warn">
      ⚠️&nbsp; <span><strong>Kode Aktivasi belum diisi.</strong><br>
      Masukkan kode di: Admin → Pengaturan → Tema → ⚙️ Lentera → <strong>Kode Aktivasi</strong>.</span>
    </div>

    <?php elseif ($_reason === 'api_error'): ?>
    <div class="alert err">
      🌐&nbsp; <span>
        <strong>Tidak dapat terhubung ke server verifikasi.</strong><br>
        Pastikan server desakami.my.id dapat diakses, atau hubungi tim Lentera dengan menyertakan informasi diagnostik di bawah.
      </span>
    </div>

    <?php else: ?>
    <div class="alert err">
      ❌&nbsp; <span><strong>Kode Aktivasi tidak valid.</strong>
      <?= htmlspecialchars($_pesan) ?></span>
    </div>
    <?php endif; ?>

    <div class="seri-box">
      <label>Nomor Seri Instalasi Anda</label>
      <div class="seri"><?= htmlspecialchars($_seri) ?></div>
      <div class="hint">Gunakan nomor seri ini saat membeli atau mengklaim lisensi di desakami.my.id</div>
    </div>

    <div class="steps">
      <strong>Cara Aktivasi:</strong>
      <ol>
        <li>Kunjungi <strong>desakami.my.id</strong> dan beli lisensi Tema Lentera</li>
        <li>Masukkan <strong>Nomor Seri</strong> di atas saat pembelian</li>
        <li>Kode Aktivasi dikirim ke email Anda</li>
        <li>Masukkan kode di <strong>Admin → Pengaturan → Tema → ⚙️ Lentera → Kode Aktivasi</strong></li>
        <li>Simpan pengaturan — halaman akan langsung aktif</li>
      </ol>
    </div>

    <a href="https://desakami.my.id" target="_blank" rel="noopener" class="btn">
      🛒 Beli / Aktifkan Lisensi di desakami.my.id
    </a>

    <p class="sub">Sudah punya kode? <a href="<?= site_url('siteman') ?>">Login Admin</a> dan masukkan di Pengaturan Tema.</p>

    <details>
      <summary>🔧 Informasi Diagnostik (untuk support)</summary>
      <table>
        <tr><td>Domain</td><td><?= htmlspecialchars($_domain) ?></td></tr>
        <tr><td>Nomor Seri</td><td><?= htmlspecialchars($_seri) ?></td></tr>
        <tr><td>Endpoint</td><td>https://<?= LNT_API_HOST . LNT_API_PATH ?></td></tr>
        <tr><td>cURL</td><td><?= function_exists('curl_init') ? '✅ Ada' : '❌ Tidak ada' ?></td></tr>
        <tr><td>allow_url_fopen</td><td><?= ini_get('allow_url_fopen') ? '✅ Aktif' : '❌ Nonaktif' ?></td></tr>
        <tr><td>OpenSSL</td><td><?= extension_loaded('openssl') ? '✅ Ada' : '❌ Tidak ada' ?></td></tr>
        <tr><td>PHP</td><td><?= PHP_VERSION ?></td></tr>
      </table>
      <?php if ($_log): ?>
      <pre><?= htmlspecialchars(substr($_log, -1200)) ?></pre>
      <?php endif; ?>
    </details>
  </div>
  <div class="foot">Tema Lentera v<?= THEME_VERSION ?></div>
</div>
</body>
</html>
<?php exit;
