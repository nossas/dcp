<?php

/**
 * Template part for Map Legend
 * Estrutura da legenda dos ícones do mapa
 */
?>

<aside class="dcp-map-legend" aria-label="<?= __('Legenda', 'hacklabr') ?>">
    <ul class="dcp-map-legend__list dcp-map-legend--desktop dcp-map-legend__list--apoio apoio-only">
        <li class="dcp-map-legend__item">
            <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/locais-seguros.svg" alt="<?= __('Locais seguros', 'hacklabr') ?>">
            <span class="dcp-map-legend__item--seguros"><?= __('Serviços públicos', 'hacklabr') ?></span>
        </li>

        <li class="dcp-map-legend__item">
            <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/cacambas.svg" alt="<?= __('Caçambas', 'hacklabr') ?>">
            <span class="dcp-map-legend__item--cacambas"><?= __('Caçambas', 'hacklabr') ?></span>
        </li>

        <li class="dcp-map-legend__item">
            <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/iniciativas-locais.svg" alt="<?= __('Iniciativas locais', 'hacklabr') ?>">
            <span class="dcp-map-legend__item--iniciativas"><?= __('Iniciativas locais', 'hacklabr') ?></span>
        </li>
    </ul>

    <ul class="dcp-map-legend__list">
        <li class="dcp-map-legend__item dcp-map-legend--desktop dcp-map-legend__alagamento dcp-map-legend__alagamento-nivel1">
            <button type="button" aria-label="<?= __('Exibir/ocultar zona de risco muito baixo de alagamento', 'hacklabr') ?>">
                <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/alagamento-nivel-1.svg" alt="<?= __('Risco de alagamento: Muito baixo', 'hacklabr') ?>">
                <span class="dcp-map-legend__item--alagamento-nivel1"><?= __('Risco de alagamento: Muito baixo', 'hacklabr') ?></span>
            </button>
        </li>

        <li class="dcp-map-legend__item dcp-map-legend--desktop dcp-map-legend__alagamento dcp-map-legend__alagamento-nivel2">
            <button type="button" aria-label="<?= __('Exibir/ocultar zona de risco baixo de alagamento', 'hacklabr') ?>">
                <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/alagamento-nivel-2.svg" alt="<?= __('Risco de alagamento: Baixo', 'hacklabr') ?>">
                <span class="dcp-map-legend__item--alagamento-nivel2"><?= __('Risco de alagamento: Baixo', 'hacklabr') ?></span>
            </button>
        </li>

        <li class="dcp-map-legend__item dcp-map-legend--desktop dcp-map-legend__alagamento dcp-map-legend__alagamento-nivel3">
            <button type="button" aria-label="<?= __('Exibir/ocultar zona de risco médio de alagamento', 'hacklabr') ?>">
                <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/alagamento-nivel-3.svg" alt="<?= __('Risco de alagamento: Médio', 'hacklabr') ?>">
                <span class="dcp-map-legend__item--alagamento-nivel3"><?= __('Risco de alagamento: Médio', 'hacklabr') ?></span>
            </button>
        </li>
        <li class="dcp-map-legend__item dcp-map-legend--desktop dcp-map-legend__alagamento dcp-map-legend__alagamento-nivel4">
            <button type="button" aria-label="<?= __('Exibir/ocultar zona de risco alto de alagamento', 'hacklabr') ?>">
                <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/alagamento-nivel-4.svg" alt="<?= __('Risco de alagamento: Alto', 'hacklabr') ?>">
                <span class="dcp-map-legend__item--alagamento-nivel4"><?= __('Risco de alagamento: Alto', 'hacklabr') ?></span>
            </button>
        </li>
        <li class="dcp-map-legend__item dcp-map-legend--desktop dcp-map-legend__alagamento dcp-map-legend__alagamento-nivel5">
            <button type="button" aria-label="<?= __('Exibir/ocultar zona de risco muito alto de alagamento', 'hacklabr') ?>">
                <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/alagamento-nivel-5.svg" alt="<?= __('Risco de alagamento: Muito alto', 'hacklabr') ?>">
                <span class="dcp-map-legend__item--alagamento-nivel5"><?= __('Risco de alagamento: Muito alto', 'hacklabr') ?></span>
            </button>
        </li>

        <li class="dcp-map-legend__item dcp-map-legend--mobile">
            <button class="dcp-map-legend__mobile-btn" type="button" tabindex="0" aria-label="<?= __('Abrir legenda', 'hacklabr') ?>" @click="$refs.legendModal.showModal()">
                <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/mobile-legend.svg" alt="<?= __('Legenda do mapa', 'hacklabr') ?>">
            </button>
        </li>
        <li class="dcp-map-legend__item dcp-map-legend__item--dicas-toggle">
            <button type="button" tabindex="0" aria-label="<?= __('Abrir dicas do mapa', 'hacklabr') ?>" @click="$refs.dicasModal.showModal()">
                <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/dicas.svg" alt="<?= __('Dicas', 'hacklabr') ?>">
            </button>
        </li>
    </ul>

    <ul class="dcp-map-legend__list dcp-map-legend--desktop">
        <li class="dcp-map-legend__item dcp-map-legend__item--layers-toggle">
            <button type="button" tabindex="0" @click="$refs.legendModal.showModal()">
                <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/legenda-camadas.svg" alt="" aria-hidden="true"> <?= __('Legendas', 'hacklabr') ?>
            </button>
        </li>
    </ul>
</aside>

<dialog class="dcp-map-dicas__modal" x-ref="dicasModal">
    <article>
        <header>
            <div class="dcp-map-dicas__title">
                <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/icon-tip.svg" alt="icone de dica">
                <h5><?= __('Dicas para usar o mapa', 'hacklabr') ?></h5>
            </div>

            <button type="button" class="dcp-map-dicas__modal-close" aria-label="<?= __('Fechar dicas', 'hacklabr') ?>" @click="$refs.dicasModal.close()">
                <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/close-modal.svg" alt="icone de fechar modal">
            </button>
        </header>
        <section>
            <div class="dcp-map-dicas__list">
                <div class="dcp-map-dicas__list--first">
                    <ul>
                        <li class="dcp-map-dicas__item">
                            <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/lupa-mapa.svg" alt="ícone de lupa">

                            <div class="dcp-map-dicas__text">
                                <h5><?= __('Buscar localização:', 'hacklabr') ?></h5>
                                <span><?= __('Digite o endereço para localizar', 'hacklabr') ?></span>
                            </div>
                        </li>
                    </ul>
                    </li>
                    <ul>
                        <li class="dcp-map-dicas__item">
                            <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/defender.svg" alt="icone de alerta">

                            <div class="dcp-map-dicas__text">
                                <h5><?= __('O que fazer:', 'hacklabr') ?></h5>
                                <span><?= __('Veja dicas e contatos úteis', 'hacklabr') ?></span>
                            </div>
                        </li>
                    </ul>
                </div>

                <div class="dcp-map-dicas__list--second">
                    <ul>
                        <li class="dcp-map-dicas__item">
                            <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/maismenos.svg" alt="mais e menos">

                            <div class="dcp-map-dicas__text">
                                <h5><?= __('Zoom:', 'hacklabr') ?></h5>
                                <span><?= __('Aproximar ou afastar o mapa', 'hacklabr') ?></span>
                            </div>
                        </li>
                        <li class="dcp-map-dicas__item">
                            <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/alvo.svg" alt="alvo">

                            <div class="dcp-map-dicas__text">
                                <h5><?= __('Buscar Localização:', 'hacklabr') ?></h5>
                                <span><?= __('Vá direto para a sua localização atual.', 'hacklabr') ?></span>
                            </div>
                        </li>
                        </li>
                        <li class="dcp-map-dicas__item">
                            <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/dicas2.svg" alt="dicas">
                            <div class="dcp-map-dicas__text">
                                <h5><?= __('Ajuda sobre o mapa:', 'hacklabr') ?></h5>
                                <span><?= __('Volte a este card quando quiser!', 'hacklabr') ?></span>
                            </div>
                        </li>
                    </ul>
                </div>
                </ul>
        </section>
    </article>
</dialog>

<aside class="dcp-map-legend__mobile">
    <dialog class="dcp-map-legend__modal" x-ref="legendModal">
        <article>
            <header>
                <button type="button" class="dcp-map-legend__modal-close" aria-label="<?= __('Fechar legenda', 'hacklabr') ?>" @click="$refs.legendModal.close()">
                    <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/close-modal.svg" alt="fechar modal">
                </button>
                <h5><img src="<?= get_stylesheet_directory_uri() ?>/assets/images/legenda.svg" alt="legenda"><?= __('Legenda', 'hacklabr') ?></h5>
            </header>


            <section>
                <ul class="dcp-map-legend__list dcp-map-legend__list--list-mobile">
                    <li class="dcp-map-legend__item dcp-map-legend--desktop dcp-map-legend__alagamento dcp-map-legend__alagamento-nivel1 ">
                        <button type="button" aria-label="<?= __('Exibir/ocultar zona de risco muito baixo de alagamento', 'hacklabr') ?>">
                            <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/alagamento-nivel-1.svg" alt="<?= __('Risco de alagamento: Muito baixo', 'hacklabr') ?>">
                            <span class="dcp-map-legend__item--alagamento-nivel1"><?= __('Risco de alagamento: Muito baixo', 'hacklabr') ?></span>
                        </button>
                    </li>

                    <li class="dcp-map-legend__item dcp-map-legend--desktop dcp-map-legend__alagamento dcp-map-legend__alagamento-nivel2 ">
                        <button type="button" aria-label="<?= __('Exibir/ocultar zona de risco baixo de alagamento', 'hacklabr') ?>">
                            <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/alagamento-nivel-2.svg" alt="<?= __('Risco de alagamento: Baixo', 'hacklabr') ?>">
                            <span class="dcp-map-legend__item--alagamento-nivel2"><?= __('Risco de alagamento: Baixo', 'hacklabr') ?></span>
                        </button>
                    </li>

                    <li class="dcp-map-legend__item dcp-map-legend--desktop dcp-map-legend__alagamento dcp-map-legend__alagamento-nivel3 ">
                        <button type="button" aria-label="<?= __('Exibir/ocultar zona de risco médio de alagamento', 'hacklabr') ?>">
                            <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/alagamento-nivel-3.svg" alt="<?= __('Risco de alagamento: Médio', 'hacklabr') ?>">
                            <span class="dcp-map-legend__item--alagamento-nivel3"><?= __('Risco de alagamento: Médio', 'hacklabr') ?></span>
                        </button>
                    </li>
                    <li class="dcp-map-legend__item dcp-map-legend--desktop dcp-map-legend__alagamento dcp-map-legend__alagamento-nivel4 ">
                        <button type="button" aria-label="<?= __('Exibir/ocultar zona de risco alto de alagamento', 'hacklabr') ?>">
                            <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/alagamento-nivel-4.svg" alt="<?= __('Risco de alagamento: Alto', 'hacklabr') ?>">
                            <span class="dcp-map-legend__item--alagamento-nivel4"><?= __('Risco de alagamento: Alto', 'hacklabr') ?></span>
                        </button>
                    </li>
                    <li class="dcp-map-legend__item dcp-map-legend--desktop dcp-map-legend__alagamento dcp-map-legend__alagamento-nivel5 ">
                        <button type="button" aria-label="<?= __('Exibir/ocultar zona de risco muito alto de alagamento', 'hacklabr') ?>">
                            <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/alagamento-nivel-5.svg" alt="<?= __('Risco de alagamento: Muito alto', 'hacklabr') ?>">
                            <span class="dcp-map-legend__item--alagamento-nivel5"><?= __('Risco de alagamento: Muito alto', 'hacklabr') ?></span>
                        </button>
                    </li>
                </ul>

                <ul class="dcp-map-legend__list risco-only">
                    <li class="dcp-map-legend__item">
                        <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/legend-risco-alagamento.svg" alt="<?= __('Alagamento', 'hacklabr') ?>">
                        <span class="dcp-map-legend__item--alagamento"><?= __('Alagamento', 'hacklabr') ?></span>
                    </li>
                    <li class="dcp-map-legend__item">
                        <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/legend-risco-lixo.svg" alt="<?= __('Lixo', 'hacklabr') ?>">
                        <span class="dcp-map-legend__item--lixo"><?= __('Lixo', 'hacklabr') ?></span>
                    </li>
                    <li class="dcp-map-legend__item">
                        <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/legend-risco-outros.svg" alt="<?= __('Outros riscos', 'hacklabr') ?>">
                        <span class="dcp-map-legend__item--outros"><?= __('Outros riscos', 'hacklabr') ?></span>
                    </li>
                </ul>

                <ul class="dcp-map-legend__list apoio-only">
                    <li class="dcp-map-legend__item">
                        <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/locais-seguros.svg" alt="<?= __('Locais seguros', 'hacklabr') ?>">
                        <span class="dcp-map-legend__item--seguros"><?= __('Serviços Públicos', 'hacklabr') ?></span>
                    </li>
                    <li class="dcp-map-legend__item">
                        <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/cacambas.svg" alt="<?= __('Caçambas', 'hacklabr') ?>">
                        <span class="dcp-map-legend__item--cacambas"><?= __('Caçambas', 'hacklabr') ?></span>
                    </li>
                    <li class="dcp-map-legend__item">
                        <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/iniciativas-locais.svg" alt="<?= __('Iniciativas locais', 'hacklabr') ?>">
                        <span class="dcp-map-legend__item--iniciativas"><?= __('Iniciativas locais', 'hacklabr') ?></span>
                    </li>
                </ul>
            </section>
        </article>
    </dialog>
</aside>

<div class="dcp-map-footer">
    <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/metodologia-icon.svg" alt="<?= __('Metodologia', 'hacklabr') ?>" class="dcp-map-footer__icon">
    <p class="dcp-map-footer__text">
        <?= __('As zonas demarcadas não mostram a situação em tempo real. Entenda nossa', 'hacklabr') ?>
        <a href="/sobre/metodologia" class="dcp-map-footer__link"><?= __('metodologia', 'hacklabr') ?></a>
    </p>
</div>
