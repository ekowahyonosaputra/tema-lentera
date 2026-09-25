<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>
<?php $bg_header = $latar_website; ?>

<div class="lt-header-compact" x-data="{menuOpen: false}">
  <div class="lt-topbar">
    <span id="lt-topbar-tanggal-c"></span>
    <span class="lt-topbar-sep">&bull;</span>
    <span id="lt-topbar-jam-c"></span>
  </div>

  <div class="lt-header-compact-bar">
    <div class="container mx-auto lg:px-5 px-3 flex items-center justify-between">
      <a href="<?= site_url() ?>" class="lt-header-compact-brand">
        <img src="<?= gambar_desa($desa['logo'] ?? null) ?>" alt="Logo <?= NAMA_DESA ?>">
        <span><?= NAMA_DESA ?></span>
      </a>
      <button type="button" class="lg:hidden lt-header-compact-btn" @click="menuOpen = !menuOpen" aria-label="Menu">
        <i class="fas fa-bars"></i>
      </button>
    </div>
  </div>

  <?php $this->load->view($folder_themes .'/commons/main_menu') ?>
  <?php $this->load->view($folder_themes .'/commons/mobile_menu') ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
  var hari  = ['Minggu','Senin','Selasa','Rabu','Kamis',"Jum'at",'Sabtu'];
  var bulan = ['Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
  var elTgl = document.getElementById('lt-topbar-tanggal-c');
  var elJam = document.getElementById('lt-topbar-jam-c');
  function dua(n) { return n<10?'0'+n:n; }
  function tick() {
    var now = new Date();
    if(elTgl) elTgl.textContent = hari[now.getDay()]+', '+now.getDate()+' '+bulan[now.getMonth()]+' '+now.getFullYear();
    if(elJam) elJam.textContent = dua(now.getHours())+':'+dua(now.getMinutes())+':'+dua(now.getSeconds())+' WIB';
  }
  tick(); setInterval(tick, 1000);
});
</script>

<nav class="lt-bottomnav">
  <a href="<?= site_url() ?>" class="lt-bottomnav-item"><i class="fas fa-home"></i><span>Beranda</span></a>
  <a href="<?= site_url('pemerintah') ?>" class="lt-bottomnav-item"><i class="fas fa-users"></i><span>Perangkat</span></a>
  <a href="<?= site_url('lapak') ?>" class="lt-bottomnav-item"><i class="fas fa-store"></i><span>Lapak</span></a>
  <a href="<?= site_url('pembangunan') ?>" class="lt-bottomnav-item"><i class="fas fa-hard-hat"></i><span>Bangunan</span></a>
  <a href="<?= site_url('layanan-mandiri') ?>" class="lt-bottomnav-item"><i class="fas fa-user-circle"></i><span>Mandiri</span></a>
</nav>
