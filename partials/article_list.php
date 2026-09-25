<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<?php $url = site_url('artikel/' . buat_slug($post)) ?>
<?php $abstract = potong_teks(strip_tags($post['isi']), 300) ?>
<?php $image = ($post['gambar'] && is_file(LOKASI_FOTO_ARTIKEL . 'sedang_' . $post['gambar'])) ?
  AmbilFotoArtikel($post['gambar'], 'sedang') :
  gambar_desa($desa['logo']);
?>

<div class="lt-article-card">
  <a href="<?= $url ?>" class="lt-article-card-img">
    <img src="<?= $image ?>" alt="<?= $post['judul'] ?>" loading="lazy">
  </a>
  <div class="lt-article-card-body">
    <a href="<?= $url ?>" class="lt-article-card-title"><?= potong_teks($post['judul'], 70) ?><?= strlen($post['judul']) > 70 ? '...' : '' ?></a>
    <ul class="lt-article-card-meta">
      <li><i class="fas fa-calendar-alt mr-1"></i> <?= tgl_indo($post['tgl_upload']) ?></li>
      <?php if ($post['kategori']) : ?>
        <li><i class="fas fa-bookmark mr-1"></i> <?= $post['kategori'] ?></li>
      <?php endif ?>
    </ul>
  </div>
</div>