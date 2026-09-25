<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<nav role="navigation" aria-label="navigation" class="breadcrumb mb-4">
  <ol class="flex text-sm gap-2 text-gray-500">
    <li><a href="<?= site_url() ?>" class="hover:text-primary-100">Beranda</a></li>
    <li>&rsaquo;</li>
    <li class="text-gray-700 font-medium">Lapak <?= ucwords($this->setting->sebutan_desa) ?></li>
  </ol>
</nav>

<div class="flex items-center gap-3 mb-5">
  <div class="w-10 h-10 rounded-full bg-primary-100 flex items-center justify-center text-white flex-shrink-0">
    <i class="fas fa-store"></i>
  </div>
  <h1 class="text-xl font-bold text-gray-800">Lapak <?= ucwords($this->setting->sebutan_desa) ?></h1>
</div>

<!-- Filter pencarian -->
<form method="get" class="lt-lapak-filter">
  <select name="id_kategori" class="lt-lapak-select">
    <option value="">Semua Kategori</option>
    <?php foreach ($kategori as $k) : ?>
      <option value="<?= $k->id ?>" <?= selected($id_kategori, $k->id) ?>><?= $k->kategori ?></option>
    <?php endforeach; ?>
  </select>
  <div class="lt-lapak-search-wrap">
    <i class="fas fa-search lt-lapak-search-ico"></i>
    <input type="text" name="keyword" maxlength="50" class="lt-lapak-search-input" value="<?= $keyword ?>" placeholder="Cari produk...">
  </div>
  <button type="submit" class="lt-lapak-btn-cari"><i class="fas fa-search mr-1"></i> Cari</button>
  <?php if ($keyword) : ?>
    <a href="<?= site_url('lapak') ?>" class="lt-lapak-btn-reset">Reset</a>
  <?php endif; ?>
</form>

<?php if ($produk) : ?>
  <div class="lt-lapak-grid">
    <?php foreach ($produk as $pro) : ?>
      <?php $foto = json_decode($pro->foto); ?>
      <div class="lt-lapak-card">

        <!-- Foto produk -->
        <div class="lt-lapak-foto">
          <?php if ($pro->foto) : ?>
            <div class="owl-carousel lt-lapak-carousel">
              <?php for ($i = 0; $i < $this->setting->banyak_foto_tiap_produk; $i++) : ?>
                <?php if (isset($foto[$i]) && is_file(LOKASI_PRODUK . $foto[$i])) : ?>
                  <img src="<?= base_url(LOKASI_PRODUK . $foto[$i]) ?>" alt="Foto <?= $pro->nama ?>">
                <?php endif; ?>
              <?php endfor; ?>
            </div>
          <?php else : ?>
            <img src="<?= asset('images/404-image-not-found.jpg') ?>" alt="Foto Produk">
          <?php endif; ?>
        </div>

        <!-- Info produk -->
        <div class="lt-lapak-body">
          <p class="lt-lapak-nama"><?= $pro->nama ?></p>

          <?php $potongan = ($pro->tipe_potongan == 1) ? ($pro->harga * ($pro->potongan / 100)) : $pro->potongan; ?>
          <?php if ($pro->potongan != 0) : ?>
            <p class="lt-lapak-harga-coret"><?= rupiah($pro->harga) ?></p>
          <?php endif; ?>

          <p class="lt-lapak-harga">
            <?= rupiah($pro->harga - $potongan) ?>
            <span>/ <?= $pro->satuan ?></span>
          </p>

          <p class="lt-lapak-desc"><?= mb_substr($pro->deskripsi, 0, 80) ?><?= strlen($pro->deskripsi) > 80 ? '...' : '' ?></p>

          <p class="lt-lapak-pelapak">
            <i class="fas fa-store mr-1 text-primary-100"></i>
            <?= $pro->pelapak ?? 'Admin' ?>
          </p>

          <!-- Tombol aksi -->
          <div class="lt-lapak-aksi">
            <?php if ($pro->telepon) : ?>
              <?php $pesan = strReplaceArrayRecursive(['[nama_produk]' => $pro->nama, '[link_web]' => base_url('lapak'), '<br />' => '%0A'], nl2br($this->setting->pesan_singkat_wa)); ?>
              <a href="https://api.whatsapp.com/send?phone=<?= format_telpon($pro->telepon) ?>&text=<?= $pesan ?>" rel="noopener noreferrer" target="_blank" class="lt-lapak-btn-wa">
                <i class="fab fa-whatsapp mr-1"></i> Beli
              </a>
            <?php endif; ?>
            <?php if ($pro->lat) : ?>
              <button type="button" class="lt-lapak-btn-map" data-bs-toggle="modal" data-bs-target="#modalLokasi"
                data-lat="<?= $pro->lat ?>" data-lng="<?= $pro->lng ?>" data-zoom="<?= $pro->zoom ?>"
                data-nama="<?= $pro->pelapak ?>" data-detail="<?= htmlspecialchars($pro->nama) ?>">
                <i class="fas fa-map-marker-alt mr-1"></i> Lokasi
              </button>
            <?php endif; ?>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>

  <?php $p_data['paging_page'] = 'lapak'; ?>
  <?php $this->load->view($folder_themes . '/commons/paging', $p_data) ?>

  <!-- Modal peta -->
  <div class="modal fade" id="modalLokasi" tabindex="-1" aria-modal="true" role="dialog">
    <div class="modal-dialog modal-lg">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title">Lokasi Penjual</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>
        <div class="modal-body">
          <div id="map-lapak" style="height:350px;width:100%;border-radius:8px;"></div>
        </div>
      </div>
    </div>
  </div>

  <script>
  $(document).ready(function () {
    var mapLapak = null;
    document.getElementById('modalLokasi') && document.getElementById('modalLokasi').addEventListener('shown.bs.modal', function (e) {
      var btn    = $(e.relatedTarget);
      var lat    = btn.data('lat');
      var lng    = btn.data('lng');
      var zoom   = btn.data('zoom') || 15;
      var nama   = btn.data('nama');
      var detail = btn.data('detail');

      if (mapLapak) { mapLapak.remove(); }
      mapLapak = L.map('map-lapak').setView([lat, lng], zoom);
      getBaseLayers(mapLapak, '<?= setting('mapbox_key') ?>', '<?= setting('jenis_peta') ?>');
      L.marker([lat, lng]).addTo(mapLapak).bindPopup('<strong>' + nama + '</strong><br>' + detail).openPopup();
    });
  });
  </script>

<?php else : ?>
  <div class="text-center py-10 text-gray-400">
    <i class="fas fa-store text-4xl mb-3 block"></i>
    <p>Belum ada produk yang tersedia.</p>
  </div>
<?php endif; ?>
