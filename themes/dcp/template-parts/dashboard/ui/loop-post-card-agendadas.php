<?php

namespace hacklabr\dashboard;

$pod = pods( 'acao', get_the_ID() );

?>
<article class="post-card is-agendadas" style="display: none;">
    <main class="post-card__content">
        <div class="post-card__term">
            <div>
                <?php
                    $get_terms = get_the_terms( get_the_ID(), 'tipo_acao' );
                    if( !empty( $get_terms ) && !is_wp_error( $get_terms ) ) {
                        risco_badge_category( $get_terms[0]->slug, $get_terms[0]->name, 'post-card__taxonomia term-' . $get_terms[0]->slug );
                    } else {
                        risco_badge_category( 'sem-categoria', 'NENHUMA CATEGORIA ADICIONADA' );
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
                <pre style="display: none">000 - CADASTROS : faça parte da rede!</pre>
            </p>
        </div>
        <ul class="post-card__list-infos">
            <li>
                <i><iconify-icon icon="bi:calendar3"></iconify-icon></i>
                <span>Dia: <?=$pod->field( 'data' )?>, <?=$pod->field( 'horario' )?></span>
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
                <div>
                    <a class="is-download button" href="<?=get_dashboard_url( 'editar-acao' )?>/?post_id=<?=get_the_ID()?>">
                        <iconify-icon icon="bi:download"></iconify-icon>
                        Lista de participantes
                    </a>
                </div>
            </div>
            <div>
                <a class="is-arquivados button" href="<?=get_dashboard_url( 'editar-acao' )?>/?post_id=<?=get_the_ID()?>">
                    <iconify-icon icon="bi:pencil-square"></iconify-icon>
                    Editar
                </a>
            </div>
        </div>
    </main>
</article>
