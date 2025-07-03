<?php

namespace hacklabr\dashboard;

?>
<article class="post-card is-sugestao" style="display: none;">
    <main class="post-card__content">
        <div class="post-card__term">
            <div>
                <span class="post-card__taxonomia term-reparos"><iconify-icon icon="bi:hammer"></iconify-icon>Reparos</span>
                <span class="post-card__taxonomia term-limpeza"><iconify-icon icon="bi:bucket-fill"></iconify-icon>Limpeza</span>
                <span class="post-card__taxonomia term-cultural"><iconify-icon icon="bi:mic-fill"></iconify-icon>Cultural</span>
                <!--
                <span class="post-card__taxonomia term-educacao">Educação</span>
                <span class="post-card__taxonomia term-solidariedade">Solidariedade</span>
                <span class="post-card__taxonomia term-incidencia">Incidencia</span>
                -->
                <?php //risco_badge_category( 'sem-categoria', 'NENHUMA CATEGORIA ADICIONADA' ); ?>
            </div>
            <div class="post-card__risco-meta">10/06/2025</div>
        </div>
        <h3 class="post-card__title">
            <span>Endereço sugerido</span>
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
                <a class="is-arquivados button" href="<?=get_dashboard_url( 'editar-acao' )?>/?post_id=1092">Avaliar</a>
            </div>
        </div>
    </main>
</article>
