<?php

/**
 * Template part for Map Legend
 * Estrutura da legenda dos ícones do mapa
 */
?>

<aside class="dcp-map-legend-risco">
    <ul class="dcp-map-legend-risco__list">
        <li class="dcp-map-legend-risco__item">
            <img class="icon-alagamento-nivel5"
                src="<?= get_stylesheet_directory_uri() ?>/assets/images/button-alagamento-on.svg"
                alt="<?= __('Zonas de risco alto de alagamento', 'hacklabr') ?>">
            <span class="dcp-map-legend-risco__item--alagamento"><?= __('Zonas de risco alto de alagamento', 'hacklabr') ?></span>
        </li>
        <li class="dcp-map-legend-risco__item">
            <img class="icon-alagamento-nivel4"
                src="<?= get_stylesheet_directory_uri() ?>/assets/images/button-alagamento-nivel4-off.svg"
                alt="<?= __('Zonas de risco moderado de alagamento', 'hacklabr') ?>">
            <span class="dcp-map-legend-risco__item--alagamento-nivel4"><?= __('Zonas de risco moderado de alagamento', 'hacklabr') ?></span>
        </li>
        <li class="dcp-map-legend-risco__item">
            <img class="icon-lixo"
                src="<?= get_stylesheet_directory_uri() ?>/assets/images/button-lixo-on.svg"
                alt="<?= __('Zonas de acúmulo de lixo', 'hacklabr') ?>">
            <span class="dcp-map-legend-risco__item--lixo"><?= __('Zonas de acúmulo de lixo', 'hacklabr') ?></span>
        </li>
    </ul>
</aside>

<aside class="dcp-map-legend-apoio">
    <ul class="dcp-map-legend-apoio__list">

        <li class="dcp-map-legend-apoio__item">
            <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/locais-seguros.svg" alt="<?= __('Locais seguros', 'hacklabr') ?>">
            <span class="dcp-map-legend-apoio__item--safe"><?= __('Locais seguros', 'hacklabr') ?></span>
        </li>

        <li class="dcp-map-legend-apoio__item">
            <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/cacambas.svg" alt="<?= __('Caçambas', 'hacklabr') ?>">
            <span class="dcp-map-legend-apoio__item--cacambas"><?= __('Caçambas', 'hacklabr') ?></span>
        </li>

        <li class="dcp-map-legend-apoio__item">
            <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/iniciativas-locais.svg" alt="<?= __('Iniciativas locais', 'hacklabr') ?>">
            <span class="dcp-map-legend-apoio__item--initiates"><?= __('Iniciativas locais', 'hacklabr') ?></span>
        </li>


    </ul>
</aside>

<ul>
    <li class="dcp-map-legend-apoio__item dcp-map-legend-apoio__item--dicas">
        <button type="button" aria-label="<?= __('Abrir dicas do mapa', 'hacklabr') ?>" @click="$refs.dicasModal.showModal()">
            <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/dicas.svg" alt="<?= __('Dicas', 'hacklabr') ?>">
        </button>
    </li>
</ul>

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
            <ul class="dcp-map-dicas__list">

                <ul class="dcp-map-dicas__list--zonas">
                    <li class="dcp-map-dicas__item  dcp-map-dicas__item-zonas dcp-map-dicas__item--alagamento">
                        <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/icon-alagamento.svg" alt="icone de alagamento">
                        <span><?= __('Zonas de risco alto de alagamento', 'hacklabr') ?></span>
                    </li>
                    <li class="dcp-map-dicas__item  dcp-map-dicas__item-zonas dcp-map-dicas__item--alagamento">
                        <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/icon-alagamento-nivel4.svg" alt="icone de alagamento">
                        <span><?= __('Zonas de risco moderado de alagamento', 'hacklabr') ?></span>
                    </li>
                    <li class="dcp-map-dicas__item dcp-map-dicas__item-zonas dcp-map-dicas__item--lixo">
                        <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/icon-lixo.svg" alt="icone de lixo">
                        <span><?= __('Zonas de acúmulo de lixo', 'hacklabr') ?></span>
                    </li>
                </ul>

                <div class="dcp-map-dicas__list--first">
                    <li class="dcp-map-dicas__item">
                        <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/lupa-mapa.svg" alt="ícone de lupa">

                        <div class="dcp-map-dicas__text">
                            <h5><?= __('Buscar localização:', 'hacklabr') ?></h5>
                            <span><?= __('Digite o endereço para localizar', 'hacklabr') ?></span>
                        </div>
                    </li>
                    <li class="dcp-map-dicas__item">
                        <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/aqui-modal.svg" alt="icone de localização">

                        <div class="dcp-map-dicas__text">
                            <h5><?= __('Informar risco:', 'hacklabr') ?></h5>
                            <span><?= __('Clique e envie um relato', 'hacklabr') ?></span>
                        </div>
                    </li>
                    </li>
                    <li class="dcp-map-dicas__item">
                        <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/defender.svg" alt="icone de alerta">

                        <div class="dcp-map-dicas__text">
                            <h5><?= __('O que fazer:', 'hacklabr') ?></h5>
                            <span><?= __('Veja dicas e contatos úteis', 'hacklabr') ?></span>
                        </div>
                    </li>
                </div>

                <div class="dcp-map-dicas__list--second">
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
                </div>

            </ul>
        </section>
    </article>
</dialog>

<aside class="dcp-map-legend-apoio__mobile">
    <button class="dcp-map-legend-apoio__mobile-btn" type="button" aria-label="<?= __('Abrir legenda', 'hacklabr') ?>" @click="$refs.legendModal.showModal()">
        <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/mobile-legend.svg" alt="<?= __('Legenda do mapa', 'hacklabr') ?>">
    </button>

    <dialog class="dcp-map-legend-apoio__modal" x-ref="legendModal">
        <article>
            <header>
                <button type="button" class="dcp-map-legend-apoio__modal-close" aria-label="<?= __('Fechar legenda', 'hacklabr') ?>" @click="$refs.legendModal.close()">
                    <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/close-modal.svg" alt="fechar modal">
                </button>
                <h5><img src="<?= get_stylesheet_directory_uri() ?>/assets/images/legenda.svg" alt="legenda"><?= __('Legenda', 'hacklabr') ?></h5>
            </header>
            <section>
                <ul class="dcp-map-legend-apoio__list">
                    <li class="dcp-map-legend-apoio__item">
                        <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/locais-seguros.svg" alt="<?= __('Locais seguros', 'hacklabr') ?>">
                        <span class="dcp-map-legend-apoio__item--safe"><?= __('Locais seguros', 'hacklabr') ?></span>
                    </li>
                    <li class="dcp-map-legend-apoio__item">
                        <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/cacambas.svg" alt="<?= __('Caçambas', 'hacklabr') ?>">
                        <span class="dcp-map-legend-apoio__item--cacambas"><?= __('Caçambas', 'hacklabr') ?></span>
                    </li>
                    <li class="dcp-map-legend-apoio__item">
                        <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/iniciativas-locais.svg" alt="<?= __('Iniciativas locais', 'hacklabr') ?>">
                        <span class="dcp-map-legend-apoio__item--initiates"><?= __('Iniciativas locais', 'hacklabr') ?></span>
                    </li>
                </ul>
            </section>
        </article>
    </dialog>
</aside>

<div class="dcp-map-footer" role="none">
    <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/metodologia-icon.svg" alt="<?= __('Metodologia', 'hacklabr') ?>" class="dcp-map-footer__icon">
    <p class="dcp-map-footer__text">
        <?= __('As zonas demarcadas não mostram a situação em tempo real. Entenda nossa', 'hacklabr') ?>
        <a href="/sobre/metodologia" class="dcp-map-footer__link"><?= __('metodologia', 'hacklabr') ?></a>
    </p>
</div>
