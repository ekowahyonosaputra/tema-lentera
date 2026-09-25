<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<?php
  $title = (!empty($judul_kategori))? $judul_kategori: 'Artikel Terkini';
  $slug = 'terkini';
  if(is_array($title)){
    $slug = $title['slug'];
    $title = $title['kategori'];
  }
?>
<div class="container mx-auto lg:px-5 px-3 my-5 text-gray-600">
  <main class="w-full overflow-hidden space-y-5">
    <!-- Tampilkan slider hanya di halaman awal. Tidak tampil pada daftar artikel di halaman kategori atau halaman selanjutnya serta halaman hasil pencarian -->
    <?php if(empty($cari AND count($slider_gambar ?? []) > 0) AND $this->uri->segment(2) != 'kategori' AND ($this->uri->segment(2) !== 'index' AND $this->uri->segment(1) !== 'index')) : ?>
      <?php $this->load->view($folder_themes .'/partials/slider') ?>
    <?php endif; ?>

    <!-- Transparansi Anggaran — paling atas setelah slider -->
    <?php
      try {
          $this->load->view("{$folder_themes}/widgets/transparansi_anggaran");
      } catch (\Throwable $e) {
          log_message('error', 'Widget transparansi_anggaran di beranda gagal: ' . $e->getMessage());
      }
    ?>

    <!-- Menu Pintasan Layanan — tepat di atas foto aparatur -->
    <?php
      try {
          echo '<div class="shadow rounded-lg bg-white overflow-hidden p-3">';
          $this->load->view("{$folder_themes}/widgets/menu_layanan");
          echo '</div>';
      } catch (\Throwable $e) {
          log_message('error', 'Widget menu_layanan di beranda gagal: ' . $e->getMessage());
      }
    ?>

    <!-- Aparatur Desa/Nagari -->
    <?php
      try {
          $widget_aparatur = null;
          if (!empty($w_cos)) {
              foreach ($w_cos as $w) {
                  if (($w['isi'] ?? null) === 'aparatur_desa.php') {
                      $widget_aparatur = $w;
                      break;
                  }
              }
          }
          if ($widget_aparatur) {
              $judul_widget = str_replace('Desa', ucwords($this->setting->sebutan_desa), strip_tags($widget_aparatur['judul']));
              echo '<div class="shadow rounded-lg bg-white overflow-hidden">';
              $this->load->view("{$folder_themes}/widgets/aparatur_desa", ['judul_widget' => $judul_widget]);
              echo '</div>';
          }
      } catch (\Throwable $e) {
          log_message('error', 'Widget aparatur_desa di beranda gagal: ' . $e->getMessage());
      }
    ?>

    <!-- Statistik Desa/Nagari -->
    <?php
      try {
          echo '<div class="shadow rounded-lg overflow-hidden">';
          $this->load->view("{$folder_themes}/widgets/statistik_nagari");
          echo '</div>';
      } catch (\Throwable $e) {
          log_message('error', 'Widget statistik_nagari di beranda gagal: ' . $e->getMessage());
      }
    ?>

    <!-- Judul Kategori / Artikel Terkini -->
    <div class="flex justify-between items-center w-full">
      <h3 class="text-h4 text-primary-200"><?= $title ?></h3>
      <a href="<?= site_url('arsip') ?>" class="text-sm hover:text-primary-100">Indeks <i class="fas fa-chevron-right ml-1"></i></a>
    </div>

    <?php if(empty($cari AND count($slider_gambar ?? []) > 0) AND $this->uri->segment(2) != 'kategori' AND ($this->uri->segment(2) !== 'index' AND $this->uri->segment(1) !== 'index')) : ?>
      <?php $this->load->view($folder_themes .'/partials/headline') ?>
    <?php endif; ?>

    <?php if($artikel) : ?>
      <div class="lt-article-grid">
        <?php foreach($artikel as $post) : ?>
          <?php $data['post'] = $post ?>
          <?php $this->load->view($folder_themes .'/partials/article_list', $data) ?>
        <?php endforeach ?>
      </div>
      <?php $data['paging_page'] = $paging_page ?>
      <div class="pagination space-y-1 flex-wrap w-full">
        <?php $this->load->view($folder_themes .'/commons/paging', $data) ?>
      </div>
      <?php else : ?>
        <?php $data['title'] = $title ?>
        <?php $this->load->view($folder_themes .'/partials/empty_article', $data) ?>
    <?php endif ?>

    <!-- Widget Login Layanan Mandiri -->
    <?php if ($this->setting->layanan_mandiri == 1) : ?>
    <?php
      try {
        $this->load->view("{$folder_themes}/widgets/layanan_mandiri");
      } catch (\Throwable $e) {
        log_message('error', 'Widget layanan_mandiri gagal: ' . $e->getMessage());
      }
    ?>
    <?php endif; ?>
  </main>
</div>