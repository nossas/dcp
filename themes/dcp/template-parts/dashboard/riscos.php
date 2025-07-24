<?php

namespace hacklabr\dashboard;

$paged = isset($_GET['paginacao']) ? intval($_GET['paginacao']) : 1;
$limit = isset($_GET['limit']) ? intval($_GET['limit']) : 6;
$get_all_riscos = get_dashboard_riscos( $paged, $limit );

?>
<div id="dashboardRiscos" class="dashboard-content">
    <header class="dashboard-content-header">
        <h1>RISCOS MAPEADOS</h1>
        <a href="<?=get_dashboard_url( 'adicionar-risco' )?>" class="button">
            <iconify-icon icon="bi:plus-lg"></iconify-icon>
            <span>Adicionar Risco</span>
        </a>
    </header>
    <div class="dashboard-content-tabs tabs">
        <div class="tabs__header">
            <a href="#aprovacao" class="is-active is-notification">AGUARDANDO APROVAÇÃO <span class="total"> (<?=$get_all_riscos['riscosAprovacao']['total_posts']?>) </span> </a>
            <a href="#publicados">PUBLICADOS <span class="total"> (<?=$get_all_riscos['riscosPublicados']['total_posts']?>) </span></a>
            <a href="#arquivados">ARQUIVADOS <span class="total"> (<?=$get_all_riscos['riscosArquivados']['total_posts']?>) </span></a>
        </div>
        <?php foreach ( $get_all_riscos as $panel_id => $value ) {

                //$is_active = $value['is_active'] ? 'is-active' : '';
                $is_active = ''; ?>

                    <div id="<?=$panel_id?>" class="tabs__panels <?=$is_active?>" <?=($is_active) ? 'style="display: block;"' : 'style="display: none;"'?> >
                        <?php
                            get_template_part('template-parts/dashboard/ui/skeleton' );

                            if( wp_is_mobile() ) {
                                get_template_part('template-parts/dashboard/ui/skeleton' );
                                get_template_part('template-parts/dashboard/ui/skeleton' );
                            }
                        ?>
                        <div class="tabs__panel__content dashboard-content-cards">
                            <?php
                            if( $value[ 'riscos' ]->have_posts() ) :
                                while( $value[ 'riscos' ]->have_posts() ) :
                                    $value[ 'riscos' ]->the_post();
                                    $pod = pods( 'risco', get_the_ID() );
                                    $post_status = get_post_status(); ?>

                                    <article class="post-card is-<?=$post_status?>" style="display: none;">
                                        <main class="post-card__content">

                                            <div class="post-card__term">
                                                <?php
                                                    $get_terms = get_the_terms( get_the_ID(), 'situacao_de_risco' );
                                                    if( !empty( $get_terms ) && !is_wp_error( $get_terms ) ) {
                                                        risco_badge_category( $get_terms[0]->slug, $get_terms[0]->name );
                                                    } else {
                                                        risco_badge_category( 'sem-categoria', 'SEM' );
                                                    }
                                                ?>
                                            </div>
                                            <div class="post-card__risco-meta"><?=wp_date( 'H:i | d/m/Y', strtotime( $pod->field('data_e_horario') ))?></div>

                                            <h3 class="post-card__title">
                                                <span><?=$pod->field( 'endereco' )?></span>
                                            </h3>

                                            <div class="post-card__excerpt-wrapped">
                                                <p class="text-excerpt">
                                                    <?php dashboard_excerpt( $pod->field( 'descricao' ) ); ?>
                                                </p>
                                            </div>

                                            <?php if( $panel_id == 'riscosPublicados' ) : ?>
                                                <div class="post-card__assets is-slider-thumb">
                                                <?php $get_attachments = get_attached_media('', get_the_ID() );
                                                if( !empty( $get_attachments ) ) :
                                                    foreach ( get_attached_media('', get_the_ID() ) as $attachment ) : ?>
                                                    <div class="slider-thumb-item">
                                                        <?php if( $attachment->post_mime_type == 'image/jpeg' || $attachment->post_mime_type == 'image/png' ) : ?>
                                                        <img class="is-load-now" data-media-src="<?=$attachment->guid?>" />
                                                        <?php endif; ?>

                                                        <?php if( $attachment->post_mime_type == 'video/mp4' ) : ?>
                                                        <video class="" poster="" playsinline controls>
                                                            <source class="is-load-now" data-media-src="<?=$attachment->guid?>" type="video/mp4">
                                                        </video>
                                                        <?php endif; ?>
                                                    </div>
                                                <?php endforeach;

                                                else : ?>
                                                    <div class="slider-thumb-empty">
                                                        Nenhuma Mídia adicionada ainda.
                                                    </div>

                                                <?php endif; ?>
                                                </div>
                                            <?php endif; ?>

                                            <div class="post-card__see-more">

                                                <div></div>
                                                <div>
                                                    <?php if( $panel_id == 'riscosAprovacao' ) : ?>
                                                        <a class="is-aprovacao button" href="<?=get_dashboard_url( 'editar-risco' )?>/?post_id=<?=get_the_ID()?>">
                                                            Avaliar
                                                            <iconify-icon icon="bi:chevron-right"></iconify-icon>
                                                        </a>
                                                    <?php  endif; ?>

                                                    <?php if( $panel_id == 'riscosPublicados' ) : ?>
                                                        <a class="is-publicados button" href="<?=get_dashboard_url( 'editar-risco' )?>/?post_id=<?=get_the_ID()?>">
                                                            <iconify-icon icon="bi:pencil-square"></iconify-icon>
                                                            Editar
                                                        </a>
                                                    <?php endif; ?>

                                                    <?php if( $panel_id == 'riscosArquivados' ) : ?>
                                                        <a class="is-arquivados button" href="<?=get_dashboard_url( 'editar-risco' )?>/?post_id=<?=get_the_ID()?>">
                                                            Reavaliar
                                                            <iconify-icon icon="bi:chevron-right"></iconify-icon>
                                                        </a>
                                                    <?php endif; ?>
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
                        <?php if( $value[ 'pagination' ] ) : ?>
                            <div class="dashboard-content-pagination">
                                <ol>
                                    <li>
                                        <a href="<?php echo ($paged === 1 ) ? '#' : get_dashboard_url( 'riscos', [ 'paginacao' => ($paged-1) ] ); ?>" class="button is-previous <?php echo ($paged === 1 ) ? 'is-disabled' : ''; ?>">
                                            <iconify-icon icon="bi:chevron-left"></iconify-icon>
                                        </a>
                                    </li>
                                    <?php
                                    for( $i = 1; $i <= $value[ 'pagination_total' ]; $i++ ) : ?>
                                        <li>
                                            <a href="<?=get_dashboard_url( 'riscos', [ 'paginacao' => ($i) ] )?>" class="button <?=( $i === $paged ) ? 'is-active' : '' ?>">
                                                <span><?=$i?></span>
                                            </a>
                                        </li>
                                    <?php endfor; ?>
                                    <li>
                                        <a href="<?php echo ($paged < $value[ 'pagination_total' ] ) ? get_dashboard_url( 'riscos', [ 'paginacao' => ($paged+1) ] ) : '#'; ?>" class="button is-next <?php echo ($paged === $value[ 'pagination_total' ] ) ? 'is-disabled' : ''; ?>">
                                            <iconify-icon icon="bi:chevron-right"></iconify-icon>
                                        </a>
                                    </li>
                                </ol>
                            </div>
                        <?php endif; ?>
                    </div>
                <?php
            }
        ?>
    </div>
</div>

