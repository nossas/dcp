<?php

namespace hacklabr\dashboard;

$pod = pods( 'acao', get_the_ID() );

?>
<article class="post-card is-realizadas" style="display: none;">
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
            <span>Mutirão de limpeza no foco de lixo na Rua Primeira de Souza</span>
        </h3>
        <div class="post-card__excerpt-wrapped">
            <p class="text-excerpt">
                Descrição da ação sugerida. Lorem ipsum dolor sit amet, consectetur adipiscing elit.
                <a class="read-more" href="#/">Ver mais</a>
                <span class="read-more-full">Etiam eget magna. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam eget magna. Lorem ipsum dolor sit amet. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam eget magna. Lorem ipsum dolor sit amet, consectetur adipiscing elit. Etiam eget magna. Lorem ipsum dolor sit amet.</span>
            </p>
        </div>
        <ul class="post-card__list-infos">
            <li>
                <i><iconify-icon icon="bi:calendar3"></iconify-icon></i>
                <span>Dia: 10/06/25, 10:00</span>
            </li>
            <li>
                <i><iconify-icon icon="bi:geo-alt-fill"></iconify-icon></i>
                <span>Endereço: Rua Primeira Souza, nº 194, em frente à farmácia</span>
            </li>
        </ul>
    </main>
</article>
