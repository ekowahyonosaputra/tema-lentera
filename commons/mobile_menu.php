<?php defined('BASEPATH') || exit('No direct script access allowed'); ?>

<nav
  class="lg:hidden block"
  role="navigation">

  <!-- Latar belakang gelap saat drawer terbuka -->
  <div
    x-show="menuOpen"
    x-transition.opacity
    @click="menuOpen = false"
    class="lt-drawer-backdrop"
    style="display: none;"></div>

  <!-- Drawer/sidebar menu dari kiri -->
  <aside
    x-show="menuOpen"
    x-transition:enter="lt-drawer-enter"
    x-transition:leave="lt-drawer-leave"
    class="lt-drawer"
    style="display: none;"
    @click.outside="menuOpen = false">

    <div class="lt-drawer-header">
      <span>Menu</span>
      <button type="button" @click="menuOpen = false" class="lt-drawer-close" aria-label="Tutup menu">
        <i class="fas fa-times"></i>
      </button>
    </div>

    <ul class="lt-drawer-list">
      <?php $menu_atas = menu_tema() ?>
      <?php if ($menu_atas) : ?>
        <?php foreach ($menu_atas as $menu) : ?>
          <?php $has_dropdown = count($menu['childrens'] ?? []) > 0 ?>
          <li class="block relative" <?php $has_dropdown && print 'x-data="{dropdownMain: false}"' ?>>

            <?php $menu_link = $has_dropdown ? '#!' : $menu['link_url'] ?>

            <a href="<?= $menu_link ?>"
              class="lt-drawer-link"
              @click="dropdownMain = !dropdownMain">
              <span><?= $menu['nama'] ?></span>

              <?php if ($has_dropdown) : ?>
                <i class="fas fa-chevron-down text-xs ml-1 inline-block transition duration-300"
                  :class="{'transform rotate-180': dropdownMain}"></i>
              <?php endif ?>
            </a>

            <?php if ($has_dropdown) : ?>
              <ul
                class="lt-drawer-sublist"
                :class="{'opacity-0 invisible z-[-10] scale-y-75 h-0': !dropdownMain, 'opacity-100 visible z-30 scale-y-100 h-auto': dropdownMain}"
                x-transition.opacity>

                <?php foreach ($menu['childrens'] as $childrens) : ?>
                  <?php $has_dropdown2 = count($childrens['childrens'] ?? []) > 0 ?>

                  <li <?php $has_dropdown2 && print 'x-data="{dropdownSub: false}"' ?>>
                    <?php $menu_link2 = $has_dropdown2 ? '#!' : $childrens['link_url'] ?>
                    <a href="<?= $menu_link2 ?>" class="lt-drawer-link lt-drawer-link-2"
                      @click="dropdownSub = !dropdownSub">
                      <span><?= $childrens['nama'] ?></span>
                      <?php if ($has_dropdown2) : ?>
                        <i class="fas fa-chevron-down text-xs ml-1 inline-block transition duration-300"
                          :class="{'transform rotate-180': dropdownSub}"></i>
                      <?php endif ?>
                    </a>

                    <?php if ($has_dropdown2) : ?>
                      <ul
                        class="lt-drawer-sublist"
                        :class="{'opacity-0 invisible z-[-10] scale-y-75 h-0': !dropdownSub, 'opacity-100 visible z-30 scale-y-100 h-auto': dropdownSub}"
                        x-transition.opacity>
                        <?php foreach ($childrens['childrens'] as $children) : ?>
                          <li @click="dropdownSub = false">
                            <a href="<?= $children['link_url'] ?>" class="lt-drawer-link lt-drawer-link-3">
                              <span><?= $children['nama'] ?></span>
                            </a>
                          </li>
                        <?php endforeach ?>
                      </ul>
                    <?php endif ?>

                  </li>

                <?php endforeach ?>

              </ul>
            <?php endif ?>
          </li>
        <?php endforeach ?>
      <?php endif ?>
    </ul>
  </aside>
</nav>
