<?php

namespace hacklabr\dashboard;

function get_cor_by_name( $name = 'NORMAL' ) {
    $cor = '';
    switch ($name) {
        case 'NORMAL';
            $cor = 'normal';
            break;
        case 'ATENÇÃO';
            $cor = 'atencao';
            break;
        case 'PERIGO';
            $cor = 'perigo';
            break;
    }
    return $cor;
}

$paged = isset($_GET['paginacao']) ? intval($_GET['paginacao']) : 1;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 6;
$get_riscos = get_riscos_by_status( 'draft', $paged, $limit );

$situacao_ativa_post = get_posts([
    'post_type' => 'situacao_atual',
    'posts_per_page' => -1,
    'orderby'        => 'date',
    'order'          => 'ASC',
    'meta_query' => [
        [
            'key' => 'is_active',
            'value' => true,
            'compare' => '='
        ]
    ]
]);

$pod_ativo = pods('situacao_atual', $situacao_ativa_post[0]->ID);

?>
<div id="dashboardInicio" class="dashboard-content">
    <div class="dashboard-content-home">
        <header class="dashboard-content-header">
            <h1>Olá, <?=wp_get_current_user()->display_name?></h1>
            <p>Lorem ipsum dolor sit amet. Curabitur ornare enim justo, at tristique.</p>
        </header>

        <div class="dashboard-content-section">
            <header class="dashboard-content-section-header">
                <h2>Situação Atual</h2>
                <a class="button" href="<?=get_dashboard_url( 'situacao_atual' )?>">
                    <span>Editar</span>
                    <iconify-icon icon="bi:chevron-right"></iconify-icon>
                </a>
            </header>
            <div class="dashboard-content-section-body">
                <div class="header-cards">
                    <div class="card">
                        <header class="<?=get_cor_by_name( $pod_ativo->field( 'tipo_de_alerta' ) )?>">
                            <figure>
                                <img src="<?=$pod_ativo->field( 'icone.guid' )?>">
                            </figure>
                            <div>
                                <p> <strong><?=$pod_ativo->field( 'tipo_de_alerta' )?></strong> </p>
                                <p><?=$pod_ativo->field( 'descricao' )?></p>
                            </div>
                        </header>
                        <article>
                            <?php if( wp_is_mobile() ) : ?>
                                <p>
                                    RJ:
                                    <strong>ESTÁGIO <?=$pod_ativo->field( 'estagio' )?></strong>
                                    <br>
                                    <strong><?=$pod_ativo->field( 'temperatura' )?>º</strong>
                                    <span class="is-separator">・</span>
                                    <?=$pod_ativo->field( 'clima' )?>
                                </p>
                            <?php else : ?>
                                <p>
                                    Rio de Janeiro:
                                    <strong>ESTÁGIO <?=$pod_ativo->field( 'estagio' )?></strong>
                                    <span class="is-separator">|</span>
                                    <strong><?=$pod_ativo->field( 'temperatura' )?>º</strong>
                                    <span class="is-separator">・</span>
                                    <?=$pod_ativo->field( 'clima' )?>
                                </p>
                                <p>Última atualização: <?=date( 'H:m', strtotime( $pod_ativo->field( 'data_e_horario' ) ) )?>
                                    <span class="is-separator">・</span> <?=date( 'd/m/y', strtotime( $pod_ativo->field( 'data_e_horario' ) ) )?>
                                </p>
                            <?php endif; ?>
                        </article>
                    </div>
                    <div class="card-group">
                        <div class="card">
                            <div class="is-counter">
                                <h3><?=$get_riscos[ 'total_posts' ]?></h3>
                                <p>Novos Relatos</p>
                            </div>
                        </div>
                        <div class="card">
                            <div class="is-counter">
                                <h3>0</h3>
                                <p>Estágio do COR</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="dashboard-content-section">
            <div class="dashboard-content-section-body">
                <hr>
            </div>
        </div>

        <div class="dashboard-content-section">

            <div class="dashboard-content-section-header">
                <h2>Relatos de Risco Aguardando Avaliação <span>( <?=$get_riscos[ 'total_posts' ]?> )</span> </h2>
                <a class="button" href="<?=get_dashboard_url( 'riscos' )?>">
                    <span>Gerenciar</span>
                    <iconify-icon icon="bi:chevron-right"></iconify-icon>
                </a>
            </div>

            <div class="dashboard-content-section-body dashboard-content-riscos">
                <div class="dashboard-content-tabs tabs">
                    <div id="riscosAprovacao" class="tabs__panels" style=" display: block !important; ">
                        <?php echo get_template_part('template-parts/dashboard/ui/skeleton' ); ?>
                        <div class="tabs__panel__content dashboard-content-cards " style="display: block; padding-top: 0">
                            <?php if( $get_riscos[ 'posts' ]->have_posts() ) :
                                while( $get_riscos[ 'posts' ]->have_posts() ) :
                                    $get_riscos[ 'posts' ]->the_post();
                                    $pod = pods( 'risco', get_the_ID() ); ?>
                                    <article class="post-card is-draft" style="display: none;">
                                        <main class="post-card__content">
                                            <div class="post-card__term">
                                                <?php
                                                $get_terms = get_the_terms( get_the_ID(), 'situacao_de_risco' );
                                                if( !empty( $get_terms ) && !is_wp_error( $get_terms ) ) {
                                                    risco_badge_category( $get_terms[0]->slug, $get_terms[0]->name );
                                                } else {
                                                    risco_badge_category( 'sem-categoria', 'SEM' );
                                                }
                                                //$timezone = new DateTimeZone('UTC');
                                                ?>
                                            </div>
                                            <div class="post-card__risco-meta"><?=date( 'H:i | d/m/Y', strtotime( $pod->field('data_e_horario') ) )?></div>
                                            <h3 class="post-card__title">
                                                <span><?=$pod->field( 'endereco' )?></span>
                                            </h3>
                                            <div class="post-card__excerpt-wrapped">
                                                <p class="text-excerpt">
                                                    <?php dashboard_excerpt( wp_unslash($pod->field( 'descricao' )) ); ?>
                                                </p>
                                            </div>
                                            <div class="post-card__see-more">
                                                <div></div>
                                                <div>
                                                    <a class="is-aprovacao button" href="<?=get_dashboard_url( 'editar-risco', [ 'post_id' => get_the_ID() ] )?>">
                                                        Avaliar
                                                        <iconify-icon icon="bi:chevron-right"></iconify-icon>
                                                    </a>
                                                </div>
                                            </div>
                                        </main>
                                    </article>
                                <?php endwhile;

                            else : ?>
                                <div class="message-response">
                                    <span class="tabs__panel-message">Nenhum risco foi publicado ainda.</span>
                                </div>
                            <?php endif; ?>

                        </div>
                        <?php if( $get_riscos[ 'pagination' ] ) : ?>
                            <div class="dashboard-content-pagination">
                                <ol>
                                    <li>
                                        <a href="<?php echo ($paged === 1 ) ? '#' : get_dashboard_url( 'inicio', [ 'paginacao' => ($paged-1) ] ); ?>" class="button is-previous <?php echo ($paged === 1 ) ? 'is-disabled' : ''; ?>">
                                            <iconify-icon icon="bi:chevron-left"></iconify-icon>
                                        </a>
                                    </li>
                                    <?php
                                    for( $i = 1; $i <= $get_riscos[ 'pagination_total' ]; $i++ ) : ?>
                                    <li>
                                        <a href="<?=get_dashboard_url( 'inicio', [ 'paginacao' => ($i) ] )?>" class="button <?=( $i === $paged ) ? 'is-active' : '' ?>">
                                            <span><?=$i?></span>
                                        </a>
                                    </li>
                                    <?php endfor; ?>
                                    <li>
                                        <a href="<?php echo ($paged < $get_riscos[ 'pagination_total' ] ) ? get_dashboard_url( 'inicio', [ 'paginacao' => ($paged+1) ] ) : '#'; ?>" class="button is-next <?php echo ($paged === $get_riscos[ 'pagination_total' ] ) ? 'is-disabled' : ''; ?>">
                                            <iconify-icon icon="bi:chevron-right"></iconify-icon>
                                        </a>
                                    </li>
                                </ol>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

