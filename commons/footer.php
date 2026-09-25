<?php  defined('BASEPATH') OR exit('No direct script access allowed'); ?>

<div class="container">
    <?php if($transparansi) $this->load->view($folder_themes .'/partials/apbdesa', $transparansi) ?>
</div>

<?php 
    $social_media = [
        'facebook' => [
            'color' => 'bg-blue-600',
            'icon' => 'fa-facebook-f'
        ],
        'twitter' => [
            'color' => 'bg-blue-400',
            'icon' => 'fa-twitter'
        ],
        'instagram' => [
            'color' => 'bg-pink-500',
            'icon' => 'fa-instagram'
        ],
        'telegram' => [
            'color' => 'bg-blue-500',
            'icon' => 'fa-telegram'
        ],
        'whatsapp' => [
            'color' => 'bg-green-500',
            'icon' => 'fa-whatsapp'
        ],
        'youtube' => [
            'color' => 'bg-red-500',
            'icon' => 'fa-youtube'
        ]
    ];
?>

<?php foreach($sosmed as $social) : ?>
    <?php if($social['link']) : ?>  
        <?php $social_media[strtolower($social['nama'])]['link'] = $social['link']; ?>
    <?php endif ?>
<?php endforeach ?>

<?php $this->load->view($folder_themes .'/commons/back_to_top') ?>

<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>
<?php
  $tampilkan_wa = theme_config('tampilkan_wa', '1') !== '0';
  $nomor_wa = identitas('telepon') ?: identitas('nomor_operator');
  $nama_desa = NAMA_DESA;
?>

<?php if ($tampilkan_wa && $nomor_wa) : ?>
  <?php $pesan_wa = urlencode('Halo, saya ingin mendapatkan informasi mengenai ' . $nama_desa . '.'); ?>
  <a href="https://api.whatsapp.com/send?phone=<?= format_telpon($nomor_wa) ?>&text=<?= $pesan_wa ?>"
     target="_blank"
     rel="noopener noreferrer"
     class="lt-wa-btn"
     title="Chat WhatsApp <?= $nama_desa ?>">
    <i class="fab fa-whatsapp"></i>
  </a>
<?php endif; ?>

<footer class="container mx-auto lg:px-5 px-3 pt-5 footer">
    <div class="lt-footer-bg text-white py-8 px-5 rounded-t-xl text-center">
        <div class="lt-footer-logo-wrap">
            <img src="<?= gambar_desa($desa['logo'] ?? null) ?>" alt="Logo <?= NAMA_DESA ?>" class="lt-footer-logo">
        </div>
        <h3 class="text-lg font-bold mb-1"><?= NAMA_DESA ?></h3>
        <?php if (identitas('alamat_kantor')) : ?>
            <p class="text-sm text-zinc-300 mb-1"><?= identitas('alamat_kantor') ?></p>
        <?php endif; ?>
        <?php if (identitas('telepon') || identitas('email_desa')) : ?>
            <p class="text-sm text-zinc-300 mb-4">
                <?= identitas('telepon') ? identitas('telepon') : '' ?>
                <?= (identitas('telepon') && identitas('email_desa')) ? ' &bull; ' : '' ?>
                <?= identitas('email_desa') ? identitas('email_desa') : '' ?>
            </p>
        <?php else : ?>
            <div class="mb-4"></div>
        <?php endif; ?>

        <ul class="flex justify-center gap-2 mb-5">
            <?php foreach($social_media as $sm) : ?>
                <?php if($link = $sm['link']) : ?>
                <li class="inline-block"><a href="<?= $link ?>" class="inline-flex items-center justify-center <?= $sm['color'] ?> h-9 w-9 rounded-full" target="_blank" rel="noopener"><i class="fab fa-lg <?= $sm['icon'] ?>"></i></a></li>
                <?php endif ?>
            <?php endforeach ?>
        </ul>

        <?php if (setting('tte')): ?>
            <div class="flex justify-center mb-4">
                <img src="<?=asset('assets/images/bsre.png?v', false); ?>" alt="Bsre" class="img-responsive" style="width: 185px;" />
            </div>
        <?php endif ?>

        <div class="border-t border-zinc-600 pt-4 text-xs text-zinc-400 space-y-1">
            <p>Hak cipta situs &copy; <?= date('Y') ?> - <?= NAMA_DESA ?></p>
            <p>
                <a href="https://www.beitsolution.id" class="underline decoration-pink-500 underline-offset-1 decoration-2" target="_blank" rel="noopener">Lentera <?= THEME_VERSION ?></a>
                <?php if (file_exists('mitra')): ?>
                    - Hosting didukung <a href="https://my.idcloudhost.com/aff.php?aff=3172" rel="noopener noreferrer" target="_blank">
                    <img src="<?= base_url('/assets/images/Logo-IDcloudhost.png')?>" class="h-4 inline-block" alt="Logo-IDCloudHost" title="Logo-IDCloudHost"></a>
                <?php endif; ?>
            </p>
        </div>
    </div>
</footer>