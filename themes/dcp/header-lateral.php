<!DOCTYPE html>
<html <?php language_attributes(); ?>>

<head>
    <meta charset="<?php bloginfo('charset'); ?>" />
    <meta name="viewport" content="width=device-width, initial-scale=1, user-scalable=no, maximum-scale=1.0">
    <meta property="og:image" content="<?= get_template_directory_uri() ?>/assets/images/cover-website.png">
    <?php wp_head() ?>
    <title><?= is_front_page() ? get_bloginfo('name') : wp_title() ?></title>
    <link rel="icon" href="<?= get_site_icon_url() ?>" />
</head>

<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>

    <div class="pre-header">
        <div class="container container--wide">
            <div class="pre-header__content">
                <div class="main-header__social-content">
                    <?= the_social_networks_menu(false); ?>
                </div>
                <div class="acessibilidade">
                    <a href="#"><iconify-icon icon="material-symbols-light:contrast"></iconify-icon></a>
                    <a href="#"><iconify-icon icon="mdi:format-font-size-increase"></iconify-icon></a>
                    <a href="#"><iconify-icon icon="mdi:format-font-size-decrease"></iconify-icon></a>
                    <a href="#"><iconify-icon icon="bi:volume-down-fill"></iconify-icon></a>
                    <a href="#"><iconify-icon icon="fa:print"></iconify-icon></a>
                </div>
            </div>
        </div>
    </div>

    <header x-data="{ menuOpen: false, searchOpen: false }" class="main-header main-header-lateral" :class="{ 'main-header-lateral--menu-open': menuOpen, 'main-header-lateral--search-open': searchOpen }">
        <div class="container container--wide">
            <div class="main-header-lateral__content">
                <button type="button" class="main-header__toggle-menu main-header-lateral__toggle-menu" aria-label="<?= __('Toggle menu visibility', 'hacklabr') ?>" @click="menuOpen = !menuOpen">
                    <source media="(max-width: 768px)" srcset="<?= get_template_directory_uri() ?>/assets/images/DCP_Logo_Escura_4.svg">
                    <img src="<?= get_template_directory_uri() ?>/assets/images/toggle.svg" width="200" alt="<?= esc_attr(get_bloginfo('name')) ?>">
                </button>

                <div class="main-header-lateral__logo">
                    <a href="<?php echo esc_url(home_url('/')); ?>" class="custom-logo-link" rel="home">
                        <picture>
                            <source media="(max-width: 768px)" srcset="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/DCP_Logo_Escura_4.svg">
                            <img src="<?php echo esc_url(get_template_directory_uri()); ?>/assets/images/DCP_Logo_Escura_4.svg" alt="<?php echo esc_attr(get_bloginfo('name')); ?>" class="custom-logo">
                        </picture>
                    </a>
                </div>

                <div class="main-header-lateral__grid">
                    <div class="main-header-lateral__desktop-content">
                        <?= wp_nav_menu(['theme_location' => 'main-menu', 'container' => 'nav', 'menu_class' => 'menu', 'container_class' => 'main-header-lateral__menu-desktop']) ?>
                    </div>

                    <div class="main-header-lateral__login">
                        <a href="#"><button class="main-header-lateral__login-access"><?= __('Login') ?></button></a>
                    </div>
                </div>

                <div class="main-header-lateral__search">
                    <?php get_search_form(); ?>
                    <button type="button" class="main-header__toggle-search main-header-lateral__toggle-search" aria-label="<?= __('Toggle search form visibility', 'hacklabr') ?>" @click="searchOpen = !searchOpen">
                        <iconify-icon icon="fa-solid:search"></iconify-icon>
                    </button>
                </div>

                <?php do_action('hacklabr/header/menus-end'); ?>
            </div>
        </div>
        </div>

        <div class="main-header-lateral__mobile-content">
            <?= wp_nav_menu(['theme_location' => 'main-menu', 'container' => 'nav', 'menu_class' => 'menu', 'container_class' => 'main-header-lateral__menu-mobile']) ?>
        </div>
    </header>

    <div class="sub-header">
        <div class="sub-header__container">
            <div class="sub-header__content-left">
                <div class="sub-header__icon">
                    <div class="warning"> <?= __('Atenção') ?> </div>
                </div>
                <div class="sub-header__content">
                    <div class="advertment"> <?= __('Alagamento em algumas áreas do Jacarezinho. Evite locais de risco.') ?> </div>
                </div>
            </div>

            <div class="sub-header__content-right">
                <div class="sub-header__read-more">
                    <a href="#"> <?= __('Saiba mais') ?> </a>
                </div>

                <div class="sub-header__close">
                    <a href="#"> <?= __('X') ?> </a>
                </div>
            </div>
        </div>
    </div>

    <div id="app">
