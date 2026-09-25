<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>
<!-- lentera:layanan_mandiri:v1 -->
<div class="lt-mandiri-wrap">

  <!-- Kolom kiri: ilustrasi + judul -->
  <div class="lt-mandiri-left">
    <div class="lt-mandiri-icon">
      <svg viewBox="0 0 80 80" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <rect x="8" y="18" width="50" height="38" rx="4" fill="#4f8ef7" opacity="0.15"/>
        <rect x="12" y="14" width="50" height="38" rx="4" fill="#4f8ef7" opacity="0.25"/>
        <rect x="16" y="10" width="50" height="38" rx="4" fill="#fff" stroke="#4f8ef7" stroke-width="2"/>
        <rect x="24" y="20" width="28" height="3" rx="1.5" fill="#4f8ef7" opacity="0.6"/>
        <rect x="24" y="27" width="20" height="3" rx="1.5" fill="#4f8ef7" opacity="0.4"/>
        <rect x="24" y="34" width="24" height="3" rx="1.5" fill="#4f8ef7" opacity="0.4"/>
        <rect x="24" y="41" width="16" height="3" rx="1.5" fill="#4f8ef7" opacity="0.3"/>
        <circle cx="62" cy="58" r="12" fill="#4f8ef7"/>
        <path d="M57 58l3.5 3.5L67 54" stroke="#fff" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"/>
      </svg>
    </div>
    <div class="lt-mandiri-title">
      <span>LAYANAN</span>
      <span>MANDIRI</span>
    </div>
    <p class="lt-mandiri-sub"><?= ucwords($this->setting->sebutan_desa) ?> Digital</p>
  </div>

  <!-- Kolom tengah: logo + tombol login -->
  <div class="lt-mandiri-center">
    <img
      src="<?= gambar_desa($desa['logo'] ?? null) ?>"
      alt="Logo <?= NAMA_DESA ?>"
      class="lt-mandiri-logo"
    >
    <p class="lt-mandiri-desa"><?= NAMA_DESA ?></p>
    <a href="<?= site_url('layanan-mandiri/masuk') ?>" class="lt-mandiri-btn">
      <i class="fas fa-lock mr-2"></i> Login Layanan Mandiri
    </a>
  </div>

  <!-- Kolom kanan: info PIN + ilustrasi -->
  <div class="lt-mandiri-right">
    <div class="lt-mandiri-bg-foto" style="background-image: url('<?= gambar_desa($desa['kantor_desa'] ?? null, true) ?>')"></div>
    <div class="lt-mandiri-right-overlay"></div>
    <div class="lt-mandiri-bubble">
      <i class="fas fa-info-circle mr-1"></i>
      Hubungi Pemerintah <?= ucwords($this->setting->sebutan_desa) ?> untuk mendapatkan PIN
    </div>
    <div class="lt-mandiri-right-icon">
      <svg viewBox="0 0 60 80" fill="none" xmlns="http://www.w3.org/2000/svg" aria-hidden="true">
        <circle cx="30" cy="16" r="10" fill="#f5c28a"/>
        <path d="M14 60c0-8.8 7.2-16 16-16s16 7.2 16 16" fill="#6d7af7" opacity="0.85"/>
        <rect x="22" y="32" width="16" height="20" rx="4" fill="#6d7af7" opacity="0.85"/>
        <rect x="26" y="52" width="4" height="10" rx="2" fill="#6d7af7" opacity="0.7"/>
        <rect x="34" y="52" width="4" height="10" rx="2" fill="#6d7af7" opacity="0.7"/>
        <rect x="26" y="70" width="14" height="4" rx="2" fill="#6d7af7" opacity="0.5"/>
        <rect x="36" y="36" width="14" height="2.5" rx="1.25" fill="#f5c28a" opacity="0.8"/>
        <rect x="38" y="41" width="10" height="2.5" rx="1.25" fill="#f5c28a" opacity="0.6"/>
      </svg>
    </div>
  </div>

</div>
