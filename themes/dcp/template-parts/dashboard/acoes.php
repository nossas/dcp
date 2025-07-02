<?php

namespace hacklabr\dashboard;

$tipo_acao = get_query_var('tipo_acao' );
if( empty( $tipo_acao ) ) $tipo_acao = 'sugestoes';

$sectios_tabs = [
    [
        'name' => 'Sugestões',
        'link' => '',
        'icon' => 'lightbulb-fill',
        'tipo_acao' => 'sugestoes',
        'notification' => false
    ],
    [
        'name' => 'Agendadas',
        'link' => '',
        'icon' => 'calendar3',
        'tipo_acao' => 'agendadas',
        'notification' => true
    ],
    [
        'name' => 'Realizadas',
        'link' => '',
        'icon' => 'check-square-fill',
        'tipo_acao' => 'realizadas',
        'notification' => false
    ],
    [
        'name' => 'Arquivadas',
        'link' => '',
        'icon' => 'x-octagon-fill',
        'tipo_acao' => 'arquivadas',
        'notification' => false
    ],
    [
        'name' => 'Relatos compartilhados',
        'link' => '',
        'icon' => 'file-earmark-richtext-fill',
        'tipo_acao' => 'relatos-compartilhados',
        'notification' => true
    ]
];

?>
<div id="dashboardAcoes" class="dashboard-content">
    <div class="dashboard-content-acoes">
        <header class="dashboard-content-header">
            <h1>AÇÕES</h1>
            <div class="is-actions">
                <a href="<?=get_dashboard_url( 'adicionar-acao' )?>" class="button acao">
                    <iconify-icon icon="bi:plus-lg"></iconify-icon>
                    <span>Criar ação</span>
                </a>
                <a href="<?=get_dashboard_url( 'adicionar-pagina-relato' )?>" class="button relato">
                    <iconify-icon icon="bi:file-earmark-richtext-fill"></iconify-icon>
                    <span>Criar página relato</span>
                </a>
            </div>
        </header>
        <div class="dashboard-content-tabs tabs">
            <div class="tabs__header">
                <?php foreach ( $sectios_tabs as $tab ) : ?>
                    <a href="<?=get_dashboard_url( 'acoes', [ 'tipo_acao' => $tab[ 'tipo_acao' ] ] )?>"
                       class="<?=( $tipo_acao == $tab[ 'tipo_acao' ] ) ? 'is-active' : ''?> <?=( $tab[ 'notification' ] ) ? 'is-notification' : ''?>">
                        <iconify-icon icon="bi:<?=$tab[ 'icon' ]?>"></iconify-icon>
                        <?=$tab[ 'name' ]?>
                        <span class="total"></span>
                    </a>
                <?php endforeach; ?>
            </div>

            <div class="dashboard-content-cards">
                <?php get_template_part('template-parts/dashboard/ui/skeleton' ); ?>

                <?php for( $i = 0; $i < 9; $i++ ) : ?>
                    <?php get_template_part('template-parts/dashboard/ui/loop-post-card-' . $tipo_acao ); ?>
                <?php endfor; ?>
                <!--
                    <dißv class="message-response" style="display: none;">
                        <span class="tabs__panel-message">Nenhuma ação foi registrada ainda.</span>
                    </div>
                -->
            </div>
        </div>
    </div>
</div>

