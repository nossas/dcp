<?php
/**
 * Template Name: Dashboard
 * Description: Template específico para o dashboard
 */

namespace hacklabr\dashboard;

    //TODO : REFACTORY
    ob_start();

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['arquivar_apoio'])) {
        $post_id = intval($_POST['post_id']);
        if (current_user_can('edit_post', $post_id)) {
            update_post_meta($post_id, 'apoio_arquivado', '1');
            wp_redirect(home_url('/dashboard/apoio?tipo=arquivados'));
            exit;
        }
    }
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?=get_dashboard_title() ?> | <?= get_bloginfo( 'name' )?></title>
    <?php wp_head(); ?>
</head>
<body <?php body_class( 'loading' ); ?>>
    <?php wp_body_open(); ?>
    <div class="dashboard">
        <header class="dashboard__header">
            <div class="dashboard__header__logo">
                <a href="<?= get_dashboard_url() ?>">
                    <?php if ( wp_is_mobile() ) : ?>
                        <img src="<?= get_template_directory_uri() ?>/assets/images/logo-defesa-climatica-popular-icon.svg" alt="<?= get_bloginfo( 'name' ) ?>">
                    <?php else : ?>
                        <img src="<?= get_template_directory_uri() ?>/assets/images/logo-defesa-climatica-popular-full.svg" alt="<?= get_bloginfo( 'name' ) ?>">
                    <?php endif; ?>
                </a>
            </div>
            <nav class="dashboard__header__navigation">
                <?php if( !wp_is_mobile() ) : ?>
                    <ul>
                        <li>
                            <a href="<?= get_dashboard_url('conta') ?>">Conta</a>
                        </li>
                        <li>
                            <a href="<?= get_dashboard_url('ajuda') ?>">Ajuda</a>
                        </li>
                        <li>
                            <a href="<?= get_dashboard_url('sair') ?>">
                                <button class="button">
                                    <span>Sair</span>
                                </button>
                            </a>
                        </li>
                    </ul>
                <?php endif; ?>

                <i class="loading-global">
                    <img src="<?= get_template_directory_uri() ?>/assets/images/loading.gif">
                </i>

            </nav>

        </header>
        <div class="dashboard__body">
            <a id="btnOpenMenuMobile" class="button">
                <span></span>
                <span></span>
                <span></span>
            </a>
            <aside id="dashboardSidebar" class="dashboard__sidebar">
                <?php if ( wp_is_mobile() ) : ?>
                    <img class="logo-icon" src="<?= get_template_directory_uri() ?>/assets/images/logo-defesa-climatica-popular-icon-negative.svg" alt="<?= get_bloginfo( 'name' ) ?>">
                <?php endif; ?>

                <nav>
                    <ul>
                        <li class="<?= is_dashboard('inicio') ? 'dashboard-current' : '' ?>">
                            <a href="<?= get_dashboard_url('inicio') ?>">
                                <iconify-icon icon="bi:house-fill"></iconify-icon>
                                <span>Início</span>
                            </a>
                        </li>
                        <?php // TODO : REFACTORY is-current ?>
                        <li class="<?= ( is_dashboard('riscos') || is_dashboard('adicionar-risco') || is_dashboard('editar-risco')) ? 'dashboard-current' : '' ?>">
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
                        <?php // TODO : REFACTORY is-current ?>
                        <li class="<?= ( is_dashboard('acoes') || is_dashboard('adicionar-acao') || is_dashboard('editar-acao') || is_dashboard('adicionar-relato') ) ? 'dashboard-current' : '' ?>">
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

                        <?php if( wp_is_mobile() ) : ?>

                            <li class="">
                                <a href="<?= get_dashboard_url('conta') ?>">
                                    <iconify-icon icon="bi:person-circle"></iconify-icon>
                                    <span>Conta</span>
                                </a>
                            </li>

                            <li class="">
                                <a href="<?= get_dashboard_url('ajuda') ?>">
                                    <iconify-icon icon="bi:question-circle"></iconify-icon>
                                    <span>Ajuda</span>
                                </a>
                            </li>
                        <?php endif; ?>

                    </ul>
                </nav>
                <?php if( wp_is_mobile() ) : ?>
                <a class="button is-logout" href="<?= get_dashboard_url('sair') ?>">
                    <span>Sair</span>
                </a>
                <?php endif; ?>
            </aside>
            <main class="dashboard__main">
                <?php get_dashboard_content(); ?>
            </main>
        </div>
    </div>
    <?php wp_footer(); ?>

    <div id="mainProgressBar">
        <div id="mainProgressContainer">
            <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
        </div>
    </div>

    <style>
        pre {
            background-color: #000;
            font-size: 12px;
            color: #00FF00;
            text-align: left;
            padding: 5px;
            border-radius: 10px;
        }
    </style>

</body>
<?php
ob_end_flush();
?>
</html>
