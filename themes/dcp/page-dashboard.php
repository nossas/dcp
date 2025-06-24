<?php

/**
 * Template Name: Dashboard
 * Description: Template específico para o dashboard
 */

namespace hacklabr\dashboard;

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= get_dashboard_title() ?> | <?= get_bloginfo('name') ?></title>
    <?php wp_head(); ?>
</head>
<body <?php body_class(); ?>>
    <?php wp_body_open(); ?>
    <div class="dashboard">
        <header class="dashboard__header">
            <div class="dashboard__header__logo">
                <?php if ( has_custom_logo() ): ?>
                    <?php the_custom_logo(); ?>
                <?php else: ?>
                    <a href="<?= get_dashboard_url() ?>">
                        <img src="<?= get_template_directory_uri() ?>/assets/images/logo.png" width="200" alt="<?= get_bloginfo( 'name' ) ?>">
                    </a>
                <?php endif; ?>
            </div>
            <nav class="dashboard__header__navigation">
                <ul>
                    <li>
                        <a href="#/conta/">Conta</a>
                    </li>
                    <li>
                        <a href="#/conta/">Ajuda</a>
                    </li>
                    <li>
                        <button class="button">
                            <span>Sair</span>
                        </button>
                    </li>
                </ul>
            </nav>
        </header>
        <div class="dashboard__body">
            <aside class="dashboard__sidebar">
                <nav>
                    <ul>
                        <li class="<?= is_dashboard('inicio') ? 'dashboard-current' : '' ?>">
                            <a href="<?= get_dashboard_url('inicio') ?>">
                                <iconify-icon icon="bi:house-fill"></iconify-icon>
                                <span>início</span>
                            </a>
                        </li>
                        <?php // TODO : REFACTORY is-current ?>
                        <li class="<?= ( is_dashboard('riscos') || is_dashboard('riscos-adicionar') || is_dashboard('riscos-single')) ? 'dashboard-current' : '' ?>">
                            <a href="<?= get_dashboard_url('riscos') ?>">
                                <iconify-icon icon="bi:geo-alt-fill"></iconify-icon>
                                <span>Riscos</span>
                            </a>
                        </li>
                        <li class="<?= is_dashboard('situacao_atual') ? 'dashboard-current' : '' ?>">
                            <a href="<?= get_dashboard_url('situacao_atual') ?>">
                                <iconify-icon icon="bi:exclamation-triangle-fill"></iconify-icon>
                                <span>Situação Atual</span>
                            </a>
                        </li>
                        <li class="<?= is_dashboard('acoes') ? 'dashboard-current' : '' ?>">
                            <a href="<?= get_dashboard_url('acoes') ?>">
                                <iconify-icon icon="bi:calendar2-week-fill"></iconify-icon>
                                <span>Ações</span>
                            </a>
                        </li>
                        <li class="<?= is_dashboard('apoio') ? 'dashboard-current' : '' ?>">
                            <a href="<?= get_dashboard_url('apoio') ?>">
                                <iconify-icon icon="bi:people-fill"></iconify-icon>
                                <span>Apoio</span>
                            </a>
                        </li>
                        <li class="<?= is_dashboard('indicadores') ? 'dashboard-current' : '' ?>">
                            <a href="<?= get_dashboard_url('indicadores') ?>">
                                <iconify-icon icon="bi:graph-up"></iconify-icon>
                                <span>Indicadores</span>
                            </a>
                        </li>
                    </ul>
                </nav>
            </aside>
            <main class="dashboard__main">
                <?php get_dashboard_content(); ?>
            </main>
        </div>
    </div>
    <?php wp_footer(); ?>
</body>
</html>
