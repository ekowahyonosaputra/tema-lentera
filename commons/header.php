<?php  defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
  $bg_header   = $latar_website;
  $motto       = theme_config('motto_nagari', '');
  $event_judul = theme_config('event_judul', '');
  $event_tgl   = theme_config('event_tanggal', '');
  $warna_tema    = theme_config('warna_tema', 'hijau');
  $logo_berputar = theme_config('logo_berputar', '0') === '1';

  // Map nama warna ke kode hex
  $palet_warna = [
    'hijau'   => '#0F6E56',
    'biru'    => '#185fa5',
    'merah'   => '#b91c1c',
    'ungu'    => '#6d28d9',
    'cokelat' => '#854f0b',
  ];
  $warna_hex = $palet_warna[$warna_tema] ?? '#0F6E56';
?>

<!--- Override CSS warna tema dinamis --->
<style>
:root {
  --primary-base-color: <?= $warna_hex ?>;
  --primary-darken-color: color-mix(in srgb, <?= $warna_hex ?> 80%, #000);
}
.lt-main-nav,
.lt-header-compact-bar {
  background: linear-gradient(to right, color-mix(in srgb, <?= $warna_hex ?> 85%, #000), <?= $warna_hex ?>) !important;
}
/* Overlay hero: semi-transparan agar gambar latar terlihat namun teks tetap terbaca */
.lt-hero-overlay {
  background: linear-gradient(
    120deg,
    rgba(0, 0, 0, 0.55) 0%,
    color-mix(in srgb, <?= $warna_hex ?> 60%, transparent) 60%,
    color-mix(in srgb, <?= $warna_hex ?> 40%, transparent) 100%
  ) !important;
  backdrop-filter: brightness(0.85);
}
.lt-anggaran-side {
  background: linear-gradient(135deg, color-mix(in srgb, <?= $warna_hex ?> 90%, #000), color-mix(in srgb, <?= $warna_hex ?> 70%, #007)) !important;
}
.lt-footer-bg {
  background: linear-gradient(135deg, color-mix(in srgb, <?= $warna_hex ?> 60%, #000), color-mix(in srgb, <?= $warna_hex ?> 75%, #000)) !important;
}
.lt-topbar {
  background: color-mix(in srgb, <?= $warna_hex ?> 40%, #000) !important;
}
</style>

<div class="container md:px-4 lg:px-5" x-data="{menuOpen: false}">
  <div class="lt-topbar">
    <span id="lt-topbar-tanggal"></span>
    <span class="lt-topbar-sep">&bull;</span>
    <span id="lt-topbar-jam"></span>
  </div>

  <header style="background-image: url(<?= $bg_header ?>);" class="bg-center bg-cover bg-no-repeat relative text-white lt-hero">
    <div class="absolute inset-0 lt-hero-overlay"></div>

    <button
      type="button"
      class="lg:hidden lt-hero-menu-btn"
      @click="menuOpen = !menuOpen"
      aria-label="Buka menu">
      <i class="fas fa-bars"></i>
    </button>

    <?php $this->load->view($folder_themes .'/commons/category_menu') ?>

    <section class="relative z-10 px-4 lg:px-8 py-6 lt-hero-inner">
      <div class="lt-hero-identity">
        <a href="<?= site_url() ?>" class="lt-hero-brand">
          <img src="<?= gambar_desa($desa['logo']) ?>" alt="Logo <?= ucfirst($this->setting->sebutan_desa).' '.ucwords($desa['nama_desa']) ?>" class="<?= $logo_berputar ? 'lt-logo-spin' : '' ?>">
          <div>
            <span class="lt-hero-title"><?= NAMA_DESA ?></span>
            <p class="lt-hero-sub">
              <?= ucfirst($this->setting->sebutan_kecamatan_singkat) ?> <?= ucwords($desa['nama_kecamatan']) ?>,
              <?= ucfirst($this->setting->sebutan_kabupaten_singkat) ?> <?= ucwords($desa['nama_kabupaten']) ?><br>
              Provinsi <?= ucwords($desa['nama_propinsi']) ?>
            </p>
            <?php if ($motto) : ?>
              <p class="lt-hero-motto">Motto <?= ucfirst($this->setting->sebutan_desa) ?> : <?= strtoupper($motto) ?></p>
            <?php endif; ?>
          </div>
        </a>
      </div>

      <?php if ($event_judul && $event_tgl) : ?>
        <div class="lt-hero-countdown" data-target="<?= str_replace(' ', 'T', $event_tgl) ?>">
          <p class="lt-hero-countdown-label">Perayaan</p>
          <p class="lt-hero-countdown-title"><?= $event_judul ?></p>
          <div class="lt-hero-countdown-box">
            <div><strong data-unit="hari">0</strong><span>HARI</span></div>
            <div><strong data-unit="jam">0</strong><span>JAM</span></div>
            <div><strong data-unit="menit">0</strong><span>MENIT</span></div>
            <div><strong data-unit="detik">0</strong><span>DETIK</span></div>
          </div>
          <p class="lt-hero-countdown-date"><?= formatTanggal($event_tgl) ?></p>
        </div>
      <?php endif; ?>
    </section>

    <?php if($teks_berjalan) : ?>
      <div class="lt-info-ticker">
        <span class="lt-info-ticker-tag"><i class="fas fa-bullhorn mr-2"></i>Info</span>
        <marquee onmouseover="this.stop();" onmouseout="this.start();" class="block divide-x-4 relative lt-info-ticker-text">
          <?php foreach($teks_berjalan as $marquee) : ?>
            <span class="px-3">
              <?= $marquee['teks'] ?>
              <?php if(trim($marquee['tautan']) && $marquee['judul_tautan']) : ?>
              <a href="<?= $marquee['tautan'] ?>" class="hover:text-link"><?= $marquee['judul_tautan']?></a>
              <?php endif ?>
            </span>
          <?php endforeach ?>
        </marquee>
        <span class="lt-info-ticker-icons">
          <?php if($this->setting->layanan_mandiri == 1) : ?>
            <a href="<?= site_url('layanan-mandiri') ?>" class="lt-info-icon-btn lt-info-icon-green" title="Layanan Mandiri"><i class="fas fa-user"></i></a>
          <?php endif ?>
          <a href="<?= site_url('pengaduan') ?>" class="lt-info-icon-btn lt-info-icon-red" title="Lapor / Pengaduan"><i class="fas fa-list"></i></a>
          <a href="<?= site_url('siteman') ?>" class="lt-info-icon-btn lt-info-icon-green" title="Login Admin"><i class="fas fa-lock"></i></a>
        </span>
      </div>
    <?php endif ?>
  </header>
  <?php $this->load->view($folder_themes .'/commons/main_menu') ?>
  <?php $this->load->view($folder_themes .'/commons/mobile_menu') ?>
</div>

<nav class="lt-bottomnav">
  <a href="<?= site_url() ?>" class="lt-bottomnav-item">
    <i class="fas fa-home"></i>
    <span>Beranda</span>
  </a>
  <a href="<?= site_url('pemerintah') ?>" class="lt-bottomnav-item">
    <i class="fas fa-users"></i>
    <span>Perangkat</span>
  </a>
  <a href="<?= site_url('lapak') ?>" class="lt-bottomnav-item">
    <i class="fas fa-store"></i>
    <span>Lapak</span>
  </a>
  <a href="<?= site_url('pembangunan') ?>" class="lt-bottomnav-item">
    <i class="fas fa-hard-hat"></i>
    <span>Bangunan</span>
  </a>
  <a href="<?= site_url('layanan-mandiri') ?>" class="lt-bottomnav-item">
    <i class="fas fa-user-circle"></i>
    <span>Mandiri</span>
  </a>
</nav>

<script>
document.addEventListener('DOMContentLoaded', function () {
  var hari  = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', "Jum'at", 'Sabtu'];
  var bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
  var elTgl = document.getElementById('lt-topbar-tanggal');
  var elJam = document.getElementById('lt-topbar-jam');

  function dua(n) { return n < 10 ? '0' + n : n; }

  function tickJam() {
    if (!elTgl || !elJam) { return; }
    var now = new Date();
    elTgl.textContent = hari[now.getDay()] + ', ' + now.getDate() + ' ' + bulan[now.getMonth()] + ' ' + now.getFullYear();
    elJam.textContent = dua(now.getHours()) + ':' + dua(now.getMinutes()) + ':' + dua(now.getSeconds()) + ' WIB';
  }

  tickJam();
  setInterval(tickJam, 1000);
});
</script>

<?php if ($event_judul && $event_tgl) : ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
  var box = document.querySelector('.lt-hero-countdown');
  if (!box) { return; }
  var target = new Date(box.getAttribute('data-target')).getTime();

  function tick() {
    var now = new Date().getTime();
    var sisa = target - now;
    if (sisa < 0) { sisa = 0; }

    var hari   = Math.floor(sisa / (1000 * 60 * 60 * 24));
    var jam    = Math.floor((sisa % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
    var menit  = Math.floor((sisa % (1000 * 60 * 60)) / (1000 * 60));
    var detik  = Math.floor((sisa % (1000 * 60)) / 1000);

    box.querySelector('[data-unit="hari"]').textContent  = hari;
    box.querySelector('[data-unit="jam"]').textContent   = jam;
    box.querySelector('[data-unit="menit"]').textContent = menit;
    box.querySelector('[data-unit="detik"]').textContent = detik;
  }

  tick();
  setInterval(tick, 1000);
});
</script>
<?php endif; ?>

