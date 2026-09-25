<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<!-- lentera:apbdesa:v3-modal-detail -->

<?php
// Hitung total per kategori utama
$kategori_utama = [];
foreach ($data_widget as $key => $subdatas) {
    $label     = $subdatas['laporan'] ?? '';
    $anggaran  = 0;
    $realisasi = 0;
    foreach ($subdatas as $k => $subdata) {
        if (!is_array($subdata) || $k === 'laporan') { continue; }
        $anggaran  += (float) ($subdata['anggaran']  ?? 0);
        $realisasi += (float) ($subdata['realisasi'] ?? 0);
    }
    $persen = $anggaran > 0 ? min(round(($realisasi / $anggaran) * 100, 2), 100) : 0;
    $kategori_utama[] = [
        'key'       => $key,
        'label'     => $label,
        'anggaran'  => $anggaran,
        'realisasi' => $realisasi,
        'persen'    => $persen,
    ];
}
?>

<!-- Banner utama: panel biru + 3 kolom donut -->
<div class="lt-apbd-wrap">
  <!-- Panel kiri -->
  <div class="lt-apbd-side">
    <div class="lt-apbd-side-deco"></div>
    <div class="lt-apbd-side-content">
      <p class="lt-apbd-side-kecil">TRANSPARANSI</p>
      <p class="lt-apbd-side-besar">ANGGARAN</p>
      <p class="lt-apbd-side-tahun"><?= $tahun ?? date('Y') ?></p>
      <button type="button" class="lt-apbd-side-btn" onclick="document.getElementById('lt-modal-apbd').classList.remove('lt-modal-hide')">
        <i class="fas fa-file-alt mr-1"></i> Lihat Detail
      </button>
    </div>
  </div>

  <!-- Kolom donut -->
  <div class="lt-apbd-cols">
    <?php foreach ($kategori_utama as $kat) : ?>
    <div class="lt-apbd-col">
      <div class="lt-apbd-col-head"><?= strtoupper($kat['label']) ?></div>
      <div class="lt-apbd-col-body">
        <div class="lt-apbd-col-text">
          <p class="lt-apbd-label">Anggaran</p>
          <p class="lt-apbd-value"><?= rupiah24($kat['anggaran']) ?></p>
          <p class="lt-apbd-label">Realisasi</p>
          <p class="lt-apbd-value"><?= rupiah24($kat['realisasi']) ?></p>
        </div>
        <div class="lt-apbd-ring" style="background: conic-gradient(#1a6fa8 <?= (float)$kat['persen'] ?>%, #dde8f5 0);">
          <span><?= rtrim(rtrim(number_format($kat['persen'], 2, ',', ''), '0'), ',') ?>%</span>
        </div>
      </div>
    </div>
    <?php endforeach; ?>
  </div>
</div>

<!-- ====== MODAL DETAIL ====== -->
<div id="lt-modal-apbd" class="lt-modal-hide lt-modal-overlay" onclick="if(event.target===this)this.classList.add('lt-modal-hide')">
  <div class="lt-modal-box" onclick="event.stopPropagation()">

    <!-- Header modal -->
    <div class="lt-modal-header">
      <span>TRANSPARANSI ANGGARAN</span>
      <button type="button" class="lt-modal-close" onclick="document.getElementById('lt-modal-apbd').classList.add('lt-modal-hide')" aria-label="Tutup">✕</button>
    </div>

    <!-- Isi modal: detail per kategori -->
    <div class="lt-modal-body">
      <?php foreach ($data_widget as $subdatas) : ?>

        <?php
          // Hitung total kategori ini untuk ring utama
          $kat_anggaran  = 0;
          $kat_realisasi = 0;
          foreach ($subdatas as $k => $sd) {
              if (!is_array($sd) || $k === 'laporan') { continue; }
              $kat_anggaran  += (float)($sd['anggaran'] ?? 0);
              $kat_realisasi += (float)($sd['realisasi'] ?? 0);
          }
          $kat_persen = $kat_anggaran > 0 ? min(round(($kat_realisasi/$kat_anggaran)*100,2),100) : 0;
        ?>

        <!-- Judul kategori dengan aksen kuning -->
        <div class="lt-modal-cat-title">
          <span class="lt-modal-cat-bar"></span>
          <?= $subdatas['laporan'] ?>
        </div>

        <!-- Sub-item per pos -->
        <?php foreach ($subdatas as $key => $subdata) : ?>
          <?php if (!is_array($subdata) || $key === 'laporan') { continue; } ?>
          <?php if ($subdata['judul'] === null || ($subdata['anggaran'] == 0 && $subdata['realisasi'] == 0)) { continue; } ?>

          <?php $sp = $subdata['anggaran'] > 0 ? min(round(($subdata['realisasi']/$subdata['anggaran'])*100,2),100) : 0; ?>

          <div class="lt-modal-item">
            <div class="lt-modal-item-head"><?= $subdata['judul'] ?></div>
            <div class="lt-modal-item-body">
              <div class="lt-modal-item-text">
                <p class="lt-apbd-label">Anggaran</p>
                <p class="lt-apbd-value"><?= rupiah24($subdata['anggaran']) ?></p>
                <p class="lt-apbd-label">Realisasi</p>
                <p class="lt-apbd-value"><?= rupiah24($subdata['realisasi']) ?></p>
              </div>
              <div class="lt-apbd-ring-sm" style="background: conic-gradient(#1a6fa8 <?= (float)$sp ?>%, #dde8f5 0);">
                <span><?= rtrim(rtrim(number_format($sp, 2, ',', ''), '0'), ',') ?>%</span>
              </div>
            </div>
          </div>

        <?php endforeach; ?>
      <?php endforeach; ?>
    </div><!-- /lt-modal-body -->
  </div>
</div>
