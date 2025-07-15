<?php

namespace hacklabr\dashboard;

    $pod = pods( 'acao', get_the_ID() );

?>
<article class="post-card is-sugestao" style="display: none;">
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
            <div class="post-card__risco-meta"><?=date( 'd/m/Y', strtotime( $pod->field( 'data_e_horario' ) ) )?></div>
        </div>
        <h3 class="post-card__title">
            <span><?=$pod->field( 'endereco' )?></span>
        </h3>
        <div class="post-card__excerpt-wrapped">
            <p class="text-excerpt">
                <?php dashboard_excerpt( $pod->field( 'descricao' ) ); ?>
            </p>
        </div>
        <ul class="post-card__list-infos">
            <?php if( !empty( $pod->field( 'nome_completo' ) ) ) : ?>
            <li>
                <i><iconify-icon icon="bi:person-fill"></iconify-icon></i>
                <span>Nome: <?=$pod->field( 'nome_completo' )?></span>
            </li>
            <?php endif; ?>
            <?php if( !empty( $pod->field( 'telefone' ) ) ) : ?>
                <li>
                    <i><iconify-icon icon="bi:telephone-fill"></iconify-icon></i>
                    <span>Telefone: <?=$pod->field( 'telefone' )?></span>
                </li>
            <?php else : ?>
                <li>
                    <i><iconify-icon icon="bi:envelope-fill"></iconify-icon></i>
                    <span>E-mail: <?=$pod->field( 'email' )?></span>
                </li>
            <?php endif; ?>
        </ul>
        <div class="post-card__see-more">
            <div></div>
            <div>
                <a class="is-arquivados button" href="<?=get_dashboard_url( 'editar-acao' )?>/?post_id=<?=get_the_ID()?>">Avaliar</a>
            </div>
        </div>
    </main>
</article>
