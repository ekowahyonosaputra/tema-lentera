<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<!DOCTYPE html>
<html lang="en">
<head>
  <?php $this->load->view($folder_themes . '/commons/meta') ?>
  <?php $this->load->view($folder_themes . '/commons/source_css') ?>
</head>
<body class="font-primary bg-gray-100">

  <?php $this->load->view($folder_themes . '/commons/loading_screen') ?>
  <?php $this->load->view($folder_themes . '/commons/header_compact') ?>

  <div class="container mx-auto lg:px-5 px-3 flex flex-col lg:flex-row my-5 gap-3 lg:gap-5 justify-between text-gray-600">
    <main class="w-full space-y-1 bg-white rounded-lg px-4 py-2 lg:py-4 lg:px-5 shadow">
      <?php if($tampil): ?>
      <?php
        // Normalkan path untuk backward compat OpenSID lama
        $halaman_statis = str_replace('web/halaman_statis/lapak', 'lapak/index', $halaman_statis);
        $halaman_statis = str_replace('home/idm', 'idm/index', $halaman_statis);

        // Coba load dari folder tema dulu; kalau tidak ada, fallback ke CI views (inti OpenSID)
        $tema_partial = VIEWPATH . $folder_themes . '/partials/' . $halaman_statis . '.php';
        if (is_file($tema_partial)):
      ?>
          <?php $this->load->view("{$folder_themes}/partials/{$halaman_statis}"); ?>
      <?php elseif(IS_PREMIUM && !preg_match("/halaman_statis/i", $halaman_statis)): ?>
          <?php $this->load->view("{$folder_themes}/partials/{$halaman_statis}"); ?>
      <?php else: ?>
          <?php $this->load->view($halaman_statis); ?>
      <?php endif; ?>
      <?php else : ?>
        <?php theme_view('partials/not_found'); ?>
      <?php endif ?>
    </main>
  </div>

  <?php $this->load->view($folder_themes .'/commons/footer') ?>
  <?php $this->load->view($folder_themes . '/commons/source_js') ?>
  <?php $this->load->view($folder_themes . '/commons/license') ?>
  <script src="<?= theme_asset("js/script.min.js?" . THEME_VERSION) ?>"></script>

</body>
</html>