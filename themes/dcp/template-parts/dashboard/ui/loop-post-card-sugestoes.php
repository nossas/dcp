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
            <div class="post-card__risco-meta"><?=$pod->field( 'data' )?></div>
        </div>
        <h3 class="post-card__title">
            <span>Endereço sugerido</span>
        </h3>
        <div class="post-card__excerpt-wrapped">
            <p class="text-excerpt">
                <?=$pod->field( 'endereco' )?>
                <?php if( !empty( $pod->field( 'descricao' ) ) ) : ?>
                    <a class="read-more" href="#/">Ver mais</a>
                    <span class="read-more-full"><?=$pod->field( 'descricao' )?></span>
                <?php endif; ?>
            </p>
        </div>
        <ul class="post-card__list-infos">
            <li>
                <i><iconify-icon icon="bi:person-fill"></iconify-icon></i>
                <span>Nome: João de souza</span>
            </li>
            <li>
                <i><iconify-icon icon="bi:telephone-fill"></iconify-icon></i>
                <span>Telefone: (21) 99851-2135</span>
            </li>
        </ul>
        <div class="post-card__see-more">
            <div></div>
            <div>
                <a class="is-arquivados button" href="<?=get_dashboard_url( 'editar-acao' )?>/?post_id=<?=get_the_ID()?>">Avaliar</a>
            </div>
        </div>
    </main>
</article>
