<?php  defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div id="lt-loading-screen" style="position:fixed;inset:0;background:#fff;z-index:9999;display:flex;align-items:center;justify-content:center;">
  <img src="<?= gambar_desa($desa['logo'] ?? null) ?>" alt="<?= NAMA_DESA ?>" class="lt-loading-logo">
</div>
<script>
  window.addEventListener('load', function () {
    var el = document.getElementById('lt-loading-screen');
    if (el) {
      el.style.transition = 'opacity 0.4s ease';
      el.style.opacity = '0';
      setTimeout(function () { el.style.display = 'none'; }, 450);
    }
  });
</script>