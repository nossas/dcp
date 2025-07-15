<?php

namespace hacklabr\dashboard;

$pod = pods( 'relato', get_the_ID() );

?>
<article class="post-card is-relatos" style="display: none;">
    <main class="post-card__content">
        <div class="post-card__cover">
            <?php $get_attachments = get_attached_media('', get_the_ID() );
            if( !empty( $get_attachments ) ) :
                foreach ( get_attached_media('', get_the_ID() ) as $attachment ) : ?>
                    <img class="is-load-now" data-media-src="<?=$attachment->guid?>" />
                <?php endforeach;
            else : ?>
                <div class="slider-thumb-empty">
                    Nenhuma Mídia adicionada ainda.
                </div>
            <?php endif; ?>
        </div>
        <div class="post-card__term">
            <div>
                <?php
                    $get_terms = get_the_terms( get_the_ID(), 'tipo_relato' );
                    if( !empty( $get_terms ) && !is_wp_error( $get_terms ) ) {
                        risco_badge_category( $get_terms[0]->slug, $get_terms[0]->name, 'post-card__taxonomia term-' . $get_terms[0]->slug );
                    } else {
                        risco_badge_category( 'sem-categoria', 'NENHUMA CATEGORIA ADICIONADA' );
                    }
                ?>
            </div>
            <div class="post-card__risco-meta"><?=$pod->field( 'data' )?></div>
        </div>
        <h3 class="post-card__title">
            <span><?=get_the_title()?></span>
        </h3>
        <div class="post-card__excerpt-wrapped">
            <p class="text-excerpt">
                <?php dashboard_excerpt( $pod->field( 'descricao' ) ); ?>
            </p>
        </div>
        <ul class="post-card__list-infos">
            <li>
                <i><iconify-icon icon="bi:calendar3"></iconify-icon></i>
                <span>Dia: <?=date( 'd/m/Y, H:i', strtotime( $pod->field( 'data_e_horario' ) ) )?></span>
            </li>
            <?php if( !empty( $pod->field( 'endereco' ) ) ) : ?>
                <li>
                    <i><iconify-icon icon="bi:geo-alt-fill"></iconify-icon></i>
                    <span>Endereço: <?=$pod->field( 'endereco' )?></span>
                </li>
            <?php endif; ?>
        </ul>
        <div class="post-card__see-more">
            <div>
                <span>Publicada</span>
            </div>
            <div>
                <a class="is-arquivados button" href="<?=get_dashboard_url( 'editar-relato' )?>?post_id=<?=get_the_ID()?>">
                    <iconify-icon icon="bi:pencil-square"></iconify-icon>
                    Editar
                </a>
            </div>
        </div>
    </main>
</article>
