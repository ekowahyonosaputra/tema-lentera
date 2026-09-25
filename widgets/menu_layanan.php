<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>
<!-- lentera:menu_layanan:v2-fixed -->
<?php
$daftar_layanan = [
    ['icon' => 'fa-chart-bar',      'warna' => '#2f4f5f', 'judul' => 'Data Wilayah',    'sub' => 'Populasi Per Wilayah',          'url' => site_url('data-wilayah')],
    ['icon' => 'fa-store',          'warna' => '#7e6ba8', 'judul' => 'Lapak Nagari',    'sub' => 'Hasil Olahan Warga',            'url' => site_url('lapak')],
    ['icon' => 'fa-tint',           'warna' => '#e2a06b', 'judul' => 'Golongan Darah',  'sub' => 'Data Statistik',                'url' => site_url('data-statistik/golongan-darah')],
    ['icon' => 'fa-desktop',        'warna' => '#5fa893', 'judul' => 'Rekam Kehadiran', 'sub' => 'di Kantor Nagari',              'url' => site_url('kehadiran')],
    ['icon' => 'fa-hard-hat',       'warna' => '#e0a23a', 'judul' => 'Pembangunan',     'sub' => 'Dokumentasi Kegiatan',          'url' => site_url('pembangunan')],
    ['icon' => 'fa-folder',         'warna' => '#e0934a', 'judul' => 'Produk Hukum',    'sub' => 'Peraturan di Nagari',           'url' => site_url('peraturan-desa')],
    ['icon' => 'fa-vote-yea',       'warna' => '#7b9bc2', 'judul' => 'DPT',             'sub' => 'Calon Pemilih',                 'url' => site_url('data-dpt')],
    ['icon' => 'fa-bullhorn',       'warna' => '#c0584f', 'judul' => 'Lapor',           'sub' => 'Pengaduan Warga',               'url' => site_url('pengaduan')],
    ['icon' => 'fa-images',         'warna' => '#6b7a8f', 'judul' => 'Galeri',          'sub' => 'Album Foto',                    'url' => site_url('galeri')],
    ['icon' => 'fa-globe-asia',     'warna' => '#4a9b6e', 'judul' => 'Status SDGs',     'sub' => 'Sustainable Development Goals', 'url' => site_url('status-sdgs')],
    ['icon' => 'fa-map-marked-alt', 'warna' => '#c0584f', 'judul' => 'Peta',            'sub' => 'Wilayah Nagari',                'url' => site_url('peta')],
    ['icon' => 'fa-gift',           'warna' => '#4aa3c4', 'judul' => 'Bantuan',         'sub' => 'Penerima Manfaat',              'url' => site_url('data-statistik/bantuan-penduduk')],
];
?>
<div class="lt-shortcut-grid">
  <?php foreach ($daftar_layanan as $layanan) : ?>
    <a href="<?= $layanan['url'] ?>" class="lt-shortcut-item">
      <span class="lt-shortcut-icon" style="background: <?= $layanan['warna'] ?>">
        <i class="fas <?= $layanan['icon'] ?>"></i>
      </span>
      <span class="lt-shortcut-text">
        <strong><?= $layanan['judul'] ?></strong>
        <span><?= $layanan['sub'] ?></span>
      </span>
    </a>
  <?php endforeach; ?>
</div>
