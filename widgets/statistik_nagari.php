<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>
<?php
try {
    $config_id = (int) identitas('id');

    // Coba lewat model dulu (otomatis terfilter per desa)
    $jml_laki      = (int) \App\Models\PendudukHidup::where('sex', 1)->count();
    $jml_perempuan = (int) \App\Models\PendudukHidup::where('sex', 2)->count();

    // Jalur cadangan 1: query langsung ke tweb_penduduk dengan filter config_id manual
    if ($jml_laki === 0 && $jml_perempuan === 0) {
        $jml_laki      = (int) $this->db->where('config_id', $config_id)->where('sex', 1)->count_all_results('tweb_penduduk');
        $jml_perempuan = (int) $this->db->where('config_id', $config_id)->where('sex', 2)->count_all_results('tweb_penduduk');
    }

    // Jalur cadangan 2: tanpa filter config_id sama sekali (kalau ada ketidaksesuaian data lama)
    if ($jml_laki === 0 && $jml_perempuan === 0) {
        $jml_laki      = (int) $this->db->where('sex', 1)->count_all_results('tweb_penduduk');
        $jml_perempuan = (int) $this->db->where('sex', 2)->count_all_results('tweb_penduduk');
    }

    $total_baris_tweb = (int) $this->db->count_all_results('tweb_penduduk');
} catch (\Throwable $e) {
    $jml_laki      = 0;
    $jml_perempuan = 0;
    $total_baris_tweb = -1;
    log_message('error', 'Widget statistik_nagari gagal: ' . $e->getMessage());
}

$jml_total = $jml_laki + $jml_perempuan;
$sebutan   = ucwords($this->setting->sebutan_desa);
?>
<!-- lentera:debug: config_id=<?= $config_id ?? 'null' ?> total_tweb_penduduk=<?= $total_baris_tweb ?> laki=<?= $jml_laki ?> perempuan=<?= $jml_perempuan ?> -->
<!-- lentera:statistik_nagari:v3-no-scopeConfigId -->
<div class="lt-statnagari">
  <div class="lt-statnagari-title">
    <span class="lt-statnagari-title-1">Statistik</span>
    <span class="lt-statnagari-title-2"><?= $sebutan ?></span>
  </div>

  <div class="lt-statnagari-gender">
    <div class="lt-statnagari-gender-item">
      <div class="lt-statnagari-avatar lt-statnagari-avatar-l"><i class="fas fa-male"></i></div>
      <div>
        <strong><?= ribuan($jml_laki) ?></strong>
        <span>laki-laki</span>
      </div>
    </div>
    <div class="lt-statnagari-gender-item">
      <div class="lt-statnagari-avatar lt-statnagari-avatar-p"><i class="fas fa-female"></i></div>
      <div>
        <strong><?= ribuan($jml_perempuan) ?></strong>
        <span>perempuan</span>
      </div>
    </div>
    <div class="lt-statnagari-total">
      total <strong><?= ribuan($jml_total) ?></strong> penduduk
    </div>
  </div>

  <div class="lt-statnagari-kategori">
    <a href="<?= site_url('data-wilayah') ?>" class="lt-statnagari-badge" style="--badge-color:#9b6bb3">
      <i class="fas fa-map-marker-alt"></i>
      <span>Data Wilayah</span>
    </a>
    <a href="<?= site_url('data-statistik/pendidikan-dalam-kk') ?>" class="lt-statnagari-badge" style="--badge-color:#16a36a">
      <i class="fas fa-graduation-cap"></i>
      <span>Data Pendidikan</span>
    </a>
    <a href="<?= site_url('data-statistik/pekerjaan') ?>" class="lt-statnagari-badge" style="--badge-color:#e08a2e">
      <i class="fas fa-tools"></i>
      <span>Data Pekerjaan</span>
    </a>
    <a href="<?= site_url('data-statistik/rentang-umur') ?>" class="lt-statnagari-badge" style="--badge-color:#2f6f95">
      <i class="fas fa-users"></i>
      <span>Data Usia</span>
    </a>
  </div>
</div>
