<?php

namespace hacklabr\dashboard;

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
                <a href="<?=get_dashboard_url( 'adicionar-pagina-relato' )?>" class="button relato">
                    <iconify-icon icon="bi:file-earmark-richtext-fill"></iconify-icon>
                    <span>Criar página relato</span>
                </a>
            </div>
        </header>
        <div class="dashboard-content-tabs tabs">
            <div class="tabs__header">
                <a href="<?=get_dashboard_url( 'acoes', [ 'tipo_acao' => 'sugestoes' ] )?>" class="is-active">SUGESTÕES <span class="total"></span> </a>
                <a href="<?=get_dashboard_url( 'acoes', [ 'tipo_acao' => 'agendadas' ] )?>">AGENDADAS <span class="total"></span></a>
                <a href="<?=get_dashboard_url( 'acoes', [ 'tipo_acao' => 'realizadas' ] )?>">REALIZADAS <span class="total"></span></a>
                <a href="<?=get_dashboard_url( 'acoes', [ 'tipo_acao' => 'arquivadas' ] )?>">ARQUIVADAS <span class="total"></span></a>
                <a href="<?=get_dashboard_url( 'acoes', [ 'tipo_acao' => 'relatos_compartilhados' ] )?>">RELATOS COMPARTILHADOS <span class="total"></span></a>
            </div>

            <div class="dashboard-content-cards">
                <?php echo get_template_part('template-parts/dashboard/ui/skeleton' ); ?>

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
                                <a class="is-arquivados button" href="">Avaliar</a>
                            </div>
                        </div>
                    </main>
                </article>

                <article class="post-card is-agendadas" style="display: none;">
                    <main class="post-card__content">
                        <div class="post-card__term">
                            <div>
                                <span class="post-card__taxonomia term-reparos"><iconify-icon icon="bi:hammer"></iconify-icon>Reparos</span>
                            </div>
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
                                <i><iconify-icon icon="bi:calendar3"></iconify-icon></i>
                                <span>Dia: 10/06/25, 10:00</span>
                            </li>
                            <li>
                                <i><iconify-icon icon="bi:geo-alt-fill"></iconify-icon></i>
                                <span>Endereço: Rua Primeira Souza, nº 194, em frente à farmácia</span>
                            </li>
                        </ul>

                        <div class="post-card__see-more term-reparos">
                            <div></div>
                            <div>
                                <a class="is-arquivados button" href="">
                                    <iconify-icon icon="bi:pencil-square"></iconify-icon>
                                    Editar
                                </a>
                            </div>
                        </div>
                    </main>
                </article>

                <article class="post-card is-realizadas" style="display: none;">
                    <main class="post-card__content">

                        <div class="post-card__term">
                            <div>
                                <span class="post-card__taxonomia term-limpeza"><iconify-icon icon="bi:bucket-fill"></iconify-icon>Limpeza</span>
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

                <article class="post-card is-arquivadas" style="display: none;">
                    <main class="post-card__content">

                        <div class="post-card__term">
                            <div>
                                <span class="post-card__taxonomia term-cultural"><iconify-icon icon="bi:mic-fill"></iconify-icon>Cultural</span>
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
                                <a class="is-arquivados button" href="">Reavaliar</a>
                            </div>
                        </div>
                    </main>
                </article>

                <article class="post-card is-relatos" style="display: none;">
                    <main class="post-card__content">
                        <div class="post-card__cover">

                        </div>
                        <div class="post-card__term">
                            <div>
                                <span class="post-card__taxonomia term-reparos"><iconify-icon icon="bi:hammer"></iconify-icon> Tag</span>
                            </div>
                            <div class="post-card__risco-meta">10/06/2025</div>
                        </div>

                        <h3 class="post-card__title">
                            <span>Mutirão de limpeza na rua X</span>
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

                        <div class="post-card__see-more">
                            <div>
                                <span>Publicada</span>
                            </div>
                            <div>
                                <a class="is-arquivados button" href="">
                                    <iconify-icon icon="bi:pencil-square"></iconify-icon>
                                    Editar
                                </a>
                            </div>
                        </div>
                    </main>
                </article>


                <!--
                    <div class="message-response" style="display: none;">
                        <span class="tabs__panel-message">Nenhuma ação foi registrada ainda.</span>
                    </div>
                -->
            </div>


        </div>
    </div>
</div>

