<?php

namespace hacklabr\dashboard;

    $tipo_acao = get_query_var('tipo_acao' );
    if( empty( $tipo_acao ) ) $tipo_acao = 'sugestoes';

    $paged = isset($_GET['paginacao']) ? intval($_GET['paginacao']) : 1;
    $limit = isset($_GET['limit']) ? intval($_GET['limit']) : 6;

    $sectios_tabs = [
        'sugestoes' => [
            'name' => 'Sugestões',
            'link' => '',
            'icon' => 'lightbulb-fill',
            'tipo_acao' => 'sugestoes',
            'post_status' => 'draft',
            'notification' => false
        ],
        'agendadas' => [
            'name' => 'Agendadas',
            'link' => '',
            'icon' => 'calendar3',
            'tipo_acao' => 'agendadas',
            'post_status' => 'publish',
            'notification' => true
        ],
        'realizadas' => [
            'name' => 'Realizadas',
            'link' => '',
            'icon' => 'check-square-fill',
            'tipo_acao' => 'realizadas',
            'post_status' => 'private',
            'notification' => false
        ],
        'arquivadas' => [
            'name' => 'Arquivadas',
            'link' => '',
            'icon' => 'x-octagon-fill',
            'tipo_acao' => 'arquivadas',
            'post_status' => 'pending',
            'notification' => false
        ],
        'relatos-compartilhados' => [
            'name' => 'Ações Relatadas',
            'link' => '',
            'icon' => 'file-earmark-richtext-fill',
            'tipo_acao' => 'relatos-compartilhados',
            'post_status' => 'publish',
            'notification' => true
        ]
    ];

    if( $tipo_acao === 'relatos-compartilhados' ) {
        $get_acoes = get_relatos( $paged, $limit );
    } else {
        $get_acoes = get_acoes_by_status( $sectios_tabs[ $tipo_acao ][ 'post_status' ], $paged, $limit );
    }

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
                <a href="<?=get_dashboard_url( 'adicionar-relato' )?>" class="button relato">
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
                <?php
                    get_template_part('template-parts/dashboard/ui/skeleton' );

                    if( wp_is_mobile() ) {
                        get_template_part('template-parts/dashboard/ui/skeleton' );
                        get_template_part('template-parts/dashboard/ui/skeleton' );
                    }

                    if( $get_acoes[ 'posts' ]->have_posts() ) :
                        while( $get_acoes[ 'posts' ]->have_posts() ) {
                            $get_acoes[ 'posts' ]->the_post();
                            get_template_part('template-parts/dashboard/ui/loop-post-card-' . $tipo_acao );
                        }
                    else : ?>
                    <div class="message-response">
                        <span class="tabs__panel-message">Nenhuma ação foi registrada ainda.</span>
                    </div>
                <?php endif;  ?>
            </div>
            <?php if( $get_acoes[ 'pagination' ] ) : ?>
                <div class="dashboard-content-pagination">
                    <ol>
                        <li>
                            <a href="<?php echo ($paged === 1 ) ? '#' : get_dashboard_url( 'acoes', [ 'paginacao' => ($paged-1), 'tipo_acao' => $tipo_acao ] ); ?>" class="button is-previous <?php echo ($paged === 1 ) ? 'is-disabled' : ''; ?>">
                                <iconify-icon icon="bi:chevron-left"></iconify-icon>
                            </a>
                        </li>
                        <?php
                        for( $i = 1; $i <= $get_acoes[ 'pagination_total' ]; $i++ ) : ?>
                            <li>
                                <a href="<?=get_dashboard_url( 'acoes', [ 'paginacao' => ($i), 'tipo_acao' => $tipo_acao ] )?>" class="button <?=( $i === $paged ) ? 'is-active' : '' ?>">
                                    <span><?=$i?></span>
                                </a>
                            </li>
                        <?php endfor; ?>
                        <li>
                            <a href="<?php echo ($paged < $get_acoes[ 'pagination_total' ] ) ? get_dashboard_url( 'acoes', [ 'paginacao' => ($paged+1), 'tipo_acao' => $tipo_acao ] ) : '#'; ?>" class="button is-next <?php echo ($paged === $get_acoes[ 'pagination_total' ] ) ? 'is-disabled' : ''; ?>">
                                <iconify-icon icon="bi:chevron-right"></iconify-icon>
                            </a>
                        </li>
                    </ol>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

