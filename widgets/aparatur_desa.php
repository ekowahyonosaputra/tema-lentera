<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<div class="box">
  <div class="box-header">
    <h3 class="box-title">
      <i class="fas fa-user  mr-1"></i><?= $judul_widget ?>
    </h3>
  </div>
  <div class="box-body">
    <div class="owl-carousel lt-aparatur" data-itemsnumber="5">
      <?php foreach ($aparatur_desa['daftar_perangkat'] as $data) : ?>
        <div class="lt-aparatur-card">
          <div class="lt-aparatur-photo">
            <img src="<?= $data['foto'] ?>" alt="<?= $data['nama'] ?>" class="object-cover object-center bg-gray-300">
          </div>
          <?php if ($this->web_widget_model->get_setting('aparatur_desa', 'overlay') == true) : ?>
            <div class="lt-aparatur-info">
              <span class="lt-aparatur-nama"><?= $data['nama'] ?></span>
              <span class="lt-aparatur-jabatan"><?= $data['jabatan'] ?></span>
              <?php if ($data['pamong_niap']) : ?>
                <span class="lt-aparatur-niap"><?= $this->setting->sebutan_nip_desa ?> : <?= $data['pamong_niap'] ?></span>
              <?php endif ?>
              <?php if ($data['kehadiran'] == 1) : ?>
                <?php if ($data['status_kehadiran'] == 'hadir') : ?>
                  <span class="lt-aparatur-badge lt-aparatur-badge-hadir">Hadir</span>
                <?php endif ?>
                <?php if ($data['tanggal'] == date('Y-m-d') && $data['status_kehadiran'] != 'hadir') : ?>
                  <span class="lt-aparatur-badge lt-aparatur-badge-tidak"><?= ucwords($data['status_kehadiran']); ?></span>
                <?php endif ?>
                <?php if ($data['tanggal'] != date('Y-m-d')) : ?>
                  <span class="lt-aparatur-badge lt-aparatur-badge-tidak">Belum Rekam Kehadiran</span>
                <?php endif ?>
              <?php endif ?>
            </div>
          <?php endif ?>
        </div>
      <?php endforeach ?>
    </div>
  </div>
</div>

<script>
/* Reinit carousel aparatur dengan konfigurasi responsif setelah global script (50ms delay) */
jQuery(document).ready(function ($) {
  setTimeout(function () {
    var $el = $('.lt-aparatur');
    if (!$el.length) { return; }
    if ($el.data('owl.carousel')) {
      $el.trigger('destroy.owl.carousel').removeClass('owl-loaded owl-drag');
      $el.find('.owl-stage-outer').children().unwrap();
    }
    $el.owlCarousel({
      loop: true,
      autoplay: true,
      autoplayHoverPause: true,
      margin: 10,
      dots: false,
      nav: false,
      responsive: {
        0:   { items: 2 },
        480: { items: 3 },
        768: { items: 4 },
        992: { items: 5 }
      }
    });
  }, 100);
});
</script>
