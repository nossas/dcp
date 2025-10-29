<?php

/**
 * Template part for Map Legend
 * Estrutura da legenda dos ícones do mapa
 */
?>

<aside class="dcp-map-legend" aria-label="<?= __('Legenda', 'hacklabr') ?>">
    <ul class="dcp-map-legend__list dcp-map-legend--desktop risco-only">
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

    <ul class="dcp-map-legend__list dcp-map-legend--desktop apoio-only">
        <li class="dcp-map-legend__item">
            <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/locais-seguros.svg" alt="<?= __('Locais seguros', 'hacklabr') ?>">
            <span class="dcp-map-legend__item--seguros"><?= __('Locais seguros', 'hacklabr') ?></span>
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
        <li class="dcp-map-legend__item dcp-map-legend--desktop dcp-map-legend__alagamento-nivel5 risco-only">
            <button type="button" aria-label="<?= __('Exibir/ocultar zona de risco alto de alagamento', 'hacklabr') ?>">
                <img class="icon-alagamento-nivel5"
                    src="<?= get_stylesheet_directory_uri() ?>/assets/images/button-alagamento-on.svg"
                    alt="<?= __('Zonas de risco alto de alagamento', 'hacklabr') ?>">
                <span class="dcp-map-legend__item--alagamento-nivel5"><?= __('Zonas de risco alto de alagamento', 'hacklabr') ?></span>
            </button>
        </li>
        <li class="dcp-map-legend__item dcp-map-legend--desktop dcp-map-legend__alagamento-nivel4 risco-only">
            <button type="button" aria-label="<?= __('Exibir/ocultar zona de risco moderado de alagamento', 'hacklabr') ?>">
                <img class="icon-alagamento-nivel4"
                    src="<?= get_stylesheet_directory_uri() ?>/assets/images/button-alagamento-nivel4-off.svg"
                    alt="<?= __('Zonas de risco moderado de alagamento', 'hacklabr') ?>">
                <span class="dcp-map-legend__item--alagamento-nivel4"><?= __('Zonas de risco moderado de alagamento', 'hacklabr') ?></span>
            </button>
        </li>
        <li class="dcp-map-legend__item dcp-map-legend--mobile">
            <button class=" dcp-map-legend__mobile-btn" type="button" aria-label="<?= __('Abrir legenda', 'hacklabr') ?>" @click="$refs.legendModal.showModal()">
                <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/mobile-legend.svg" alt="<?= __('Legenda do mapa', 'hacklabr') ?>">
            </button>
        </li>
        <li class="dcp-map-legend__item">
            <button type="button" aria-label="<?= __('Abrir dicas do mapa', 'hacklabr') ?>" @click="$refs.dicasModal.showModal()">
                <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/dicas.svg" alt="<?= __('Dicas', 'hacklabr') ?>">
            </button>
        </li>
    </ul>
</aside>

<dialog class="dcp-map-dicas__modal" x-ref="dicasModal">
    <article>
        <header>
            <div class="dcp-map-dicas__title">
                <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/icon-tip.svg" alt="">
                <h5><?= __('Dicas para usar o mapa', 'hacklabr') ?></h5>
            </div>

            <button type="button" class="dcp-map-dicas__modal-close" aria-label="<?= __('Fechar dicas', 'hacklabr') ?>" @click="$refs.dicasModal.close()">
                <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/close-modal.svg" alt="">
            </button>
        </header>
        <main>
            <ul class="dcp-map-dicas__list">
                <div class="dcp-map-dicas__list--zonas">
                    <li class="dcp-map-dicas__item  dcp-map-dicas__item-zonas dcp-map-dicas__item--alagamento">
                        <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/icon-alagamento.svg" alt="">
                        <span><?= __('Zonas de risco alto de alagamento', 'hacklabr') ?></span>
                    </li>
                    <li class="dcp-map-dicas__item  dcp-map-dicas__item-zonas dcp-map-dicas__item--alagamento">
                        <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/icon-alagamento-nivel4.svg" alt="">
                        <span><?= __('Zonas de risco moderado de alagamento', 'hacklabr') ?></span>
                    </li>
                    <!--
                    <li class="dcp-map-dicas__item dcp-map-dicas__item-zonas dcp-map-dicas__item--lixo">
                        <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/icon-lixo.svg" alt="">
                        <span><?= __('Zonas de acúmulo de lixo', 'hacklabr') ?></span>
                    </li>
                    -->
                </div>

                <div class="dcp-map-dicas__list--first">
                    <li class="dcp-map-dicas__item">
                        <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/lupa-mapa.svg" alt="">

                        <div class="dcp-map-dicas__text">
                            <h5><?= __('Buscar localização:', 'hacklabr') ?></h5>
                            <span><?= __('Digite o endereço para localizar', 'hacklabr') ?></span>
                        </div>
                    </li>
                    <li class="dcp-map-dicas__item">
                        <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/aqui-modal.svg" alt="">

                        <div class="dcp-map-dicas__text">
                            <h5><?= __('Informar risco:', 'hacklabr') ?></h5>
                            <span><?= __('Clique e envie um relato', 'hacklabr') ?></span>
                        </div>
                    </li>
                    </li>
                    <li class="dcp-map-dicas__item">
                        <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/defender.svg" alt="">

                        <div class="dcp-map-dicas__text">
                            <h5><?= __('O que fazer:', 'hacklabr') ?></h5>
                            <span><?= __('Veja dicas e contatos úteis', 'hacklabr') ?></span>
                        </div>
                    </li>
                </div>

                <div class="dcp-map-dicas__list--second">
                    <li class="dcp-map-dicas__item">
                        <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/maismenos.svg" alt="">

                        <div class="dcp-map-dicas__text">
                            <h5><?= __('Zoom:', 'hacklabr') ?></h5>
                            <span><?= __('Aproximar ou afastar o mapa', 'hacklabr') ?></span>
                        </div>
                    </li>
                    <li class="dcp-map-dicas__item">
                        <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/alvo.svg" alt="">

                        <div class="dcp-map-dicas__text">
                            <h5><?= __('Buscar Localização:', 'hacklabr') ?></h5>
                            <span><?= __('Vá direto para a sua localização atual.', 'hacklabr') ?></span>
                        </div>
                    </li>
                    </li>
                    <li class="dcp-map-dicas__item">
                        <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/dicas2.svg" alt="">
                        <div class="dcp-map-dicas__text">
                            <h5><?= __('Ajuda sobre o mapa:', 'hacklabr') ?></h5>
                            <span><?= __('Volte a este card quando quiser!', 'hacklabr') ?></span>
                        </div>
                    </li>
                </div>
            </ul>
        </main>
    </article>
</dialog>

<aside class="dcp-map-legend__mobile">
    <dialog class="dcp-map-legend__modal" x-ref="legendModal">
        <article>
            <header>
                <button type="button" class="dcp-map-legend__modal-close" aria-label="<?= __('Fechar legenda', 'hacklabr') ?>" @click="$refs.legendModal.close()">
                    <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/close-modal.svg" alt="">
                </button>
                <h5><img src="<?= get_stylesheet_directory_uri() ?>/assets/images/legenda.svg" alt=""><?= __('Legenda', 'hacklabr') ?></h5>
            </header>

            <main>
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
                        <span class="dcp-map-legend__item--seguros"><?= __('Locais seguros', 'hacklabr') ?></span>
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
            </main>
        </article>
    </dialog>
</aside>

<footer class="dcp-map-footer">
    <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/metodologia-icon.svg" alt="<?= __('Metodologia', 'hacklabr') ?>" class="dcp-map-footer__icon">
    <p class="dcp-map-footer__text">
        <?= __('As zonas demarcadas não mostram a situação em tempo real. Entenda nossa', 'hacklabr') ?>
        <a href="/sobre/metodologia" class="dcp-map-footer__link"><?= __('metodologia', 'hacklabr') ?></a>
    </p>
</footer>
