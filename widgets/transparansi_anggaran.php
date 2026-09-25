<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>
<!-- lentera:transparansi_anggaran:v1 -->
<?php
try {
    $this->load->model('keuangan_grafik_model');
    $transparansi = $this->keuangan_grafik_model->grafik_keuangan_tema();
    $kategori_anggaran = [];

    if (!empty($transparansi['data_widget'])) {
        foreach ($transparansi['data_widget'] as $subdatas) {
            $total_anggaran  = 0;
            $total_realisasi = 0;
            $judul           = $subdatas['laporan'] ?? '';

            foreach ($subdatas as $key => $subdata) {
                if ($key === 'laporan' || !is_array($subdata)) {
                    continue;
                }
                $total_anggaran  += (float) ($subdata['anggaran'] ?? 0);
                $total_realisasi += (float) ($subdata['realisasi'] ?? 0);
            }

            $persen = $total_anggaran > 0 ? round(($total_realisasi / $total_anggaran) * 100, 2) : 0;

            $kategori_anggaran[] = [
                'judul'     => $judul,
                'anggaran'  => $total_anggaran,
                'realisasi' => $total_realisasi,
                'persen'    => $persen,
            ];
        }
    }
} catch (\Throwable $e) {
    $kategori_anggaran = [];
    log_message('error', 'Widget transparansi_anggaran gagal: ' . $e->getMessage());
}
?>

<?php if (!empty($kategori_anggaran)) : ?>
<div class="lt-anggaran">
  <div class="lt-anggaran-side">
    <div class="lt-anggaran-side-deco"></div>
    <div class="lt-anggaran-side-content">
      <p class="lt-anggaran-side-label">TRANSPARANSI</p>
      <p class="lt-anggaran-side-title">ANGGARAN</p>
      <a href="<?= site_url('load_apbdes') ?>" target="_blank" class="lt-anggaran-side-btn">Lihat Detail</a>
    </div>
  </div>

  <div class="lt-anggaran-cols">
    <?php foreach ($kategori_anggaran as $kat) : ?>
      <div class="lt-anggaran-col">
        <div class="lt-anggaran-col-head"><?= strtoupper(str_replace(['Laporan', 'Realisasi'], '', $kat['judul'])) ?></div>
        <div class="lt-anggaran-col-body">
          <div class="lt-anggaran-col-text">
            <p class="lt-anggaran-label">Anggaran</p>
            <p class="lt-anggaran-value">Rp <?= number_format($kat['anggaran'], 0, ',', '.') ?></p>
            <p class="lt-anggaran-label">Realisasi</p>
            <p class="lt-anggaran-value">Rp <?= number_format($kat['realisasi'], 0, ',', '.') ?></p>
          </div>
          <div class="lt-anggaran-ring" style="background: conic-gradient(#1a6fa8 <?= min((float)$kat['persen'], 100) ?>%, #e2e2e2 0);">
            <span><?= rtrim(rtrim(number_format($kat['persen'], 2, ',', ''), '0'), ',') ?>%</span>
          </div>
        </div>
      </div>
    <?php endforeach; ?>
  </div>
</div>
<?php endif; ?>
