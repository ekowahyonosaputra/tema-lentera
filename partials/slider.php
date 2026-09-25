<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<section class="sliderx lt-slider w-full relative group transition-all duration-300 overflow-hidden">
  <div class="owl-carousel rounded-2xl lt-slider-frame z-10 relative w-full">
    <?php foreach($slider_gambar['gambar'] as $data) : ?>
    <?php $img = $slider_gambar['lokasi'] . 'sedang_' . $data['gambar']; ?>
    <?php if(is_file($img)) : ?>
    <figure class="lt-slider-frame w-full lt-slider-item">
      <img src="<?= base_url($img) ?>" alt="<?= $data['judul'] ?>"
        class="max-w-full w-full lt-slider-frame object-cover lt-slider-img">

        <?php if($slider_gambar['sumber'] != 3) : ?>
          <div class="lt-slider-caption lt-anim-fade-up">
            <span class="lt-slider-tag">Berita</span>
            <a href="<?= site_url('artikel/'.buat_slug($data)) ?>" class="lt-slider-title"><?= $data['judul'] ?></a>
          </div>
        <?php endif ?>
    </figure>
    <?php endif ?>
    <?php endforeach ?>
  </div>
  <div class="slider-nav">
    <span
      class="slider-nav-prev lt-slider-nav-btn opacity-0 group-hover:opacity-100 absolute top-1/2 left-4 transform -translate-y-1/2 z-[99]"
      title="Sebelumnya"><i class="fas fa-chevron-left"></i></span>
    <span
      class="slider-nav-next lt-slider-nav-btn opacity-0 group-hover:opacity-100 absolute top-1/2 right-4 transform -translate-y-1/2 z-[99]"
      title="Selanjutnya"><i class="fas fa-chevron-right"></i></span>
  </div>
</section>

<script>
window.addEventListener('load', function () {
  if (typeof jQuery === 'undefined') { return; }
  jQuery('.lt-slider .owl-carousel').on('translate.owl.carousel', function () {
    jQuery(this).find('.lt-anim-fade-up').css('animation', 'none');
  });
  jQuery('.lt-slider .owl-carousel').on('translated.owl.carousel', function (e) {
    var active = jQuery(this).find('.owl-item.active .lt-anim-fade-up');
    active.css('animation', '');
  });
});
</script>
