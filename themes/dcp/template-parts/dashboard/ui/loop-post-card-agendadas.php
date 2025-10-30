<?php

namespace hacklabr\dashboard;

$pod = pods( 'acao', get_the_ID() );

?>
<article class="post-card is-agendadas" style="display: none;">
    <section class="post-card__content">
        <div class="post-card__term">
            <div>
                <?php
                    $get_terms = get_the_terms( get_the_ID(), 'tipo_acao' );
                    if( !empty( $get_terms ) && !is_wp_error( $get_terms ) ) {
                        risco_badge_category( $get_terms[0]->slug, $get_terms[0]->name, 'post-card__taxonomia term-' . $get_terms[0]->slug );
                    } else {
                        risco_badge_category( 'sem-categoria', 'SEM' );
                    }
                ?>
            </div>
        </div>
        <h3 class="post-card__title">
            <span><?=$pod->field( 'titulo' )?></span>
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
        <div class="post-card__see-more term-<?=$get_terms[0]->slug?>">
            <div>
                <?php if( !empty( $pod->field( 'total_participantes' ) ) ) : ?>
                    <a class="is-download button" href="<?=admin_url( 'admin-ajax.php?action=download_participantes_acao&post_id=' . get_the_ID() )?>" target="_blank">
                        <iconify-icon icon="bi:download"></iconify-icon>
                        Lista de participantes <span>(<?=$pod->field( 'total_participantes' )?>)</span>
                    </a>
                <?php endif; ?>
            </div>
            <div>
                <a class="is-arquivados button" href="<?=get_dashboard_url( 'editar-acao' )?>/?post_id=<?=get_the_ID()?>">
                    <iconify-icon icon="bi:pencil-square"></iconify-icon>
                    Editar
                </a>
            </div>
        </div>
    </section>
</article>
