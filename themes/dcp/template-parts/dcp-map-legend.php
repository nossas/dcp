<?php

/**
 * Template part for Map Legend
 * Estrutura da legenda dos ícones do mapa
 */
?>

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

<aside class="dcp-map-legend-apoio__mobile">
    <button class="dcp-map-legend-apoio__mobile-btn" type="button" aria-label="<?= __('Abrir legenda', 'hacklabr') ?>" @click="$refs.legendModal.showModal()">
        <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/mobile-legend.svg" alt="<?= __('Legenda do mapa', 'hacklabr') ?>">
    </button>

    <dialog class="dcp-map-legend-apoio__modal" x-ref="legendModal">
        <article>
            <header>
                <button type="button" class="dcp-map-legend-apoio__modal-close" aria-label="<?= __('Fechar legenda', 'hacklabr') ?>" @click="$refs.legendModal.close()">
                   <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/close-modal.svg" alt="">
                </button>
                <h5><img src="<?= get_stylesheet_directory_uri() ?>/assets/images/legenda.svg" alt=""><?= __('Legenda', 'hacklabr') ?></h5>
            </header>
            <main>
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
            </main>
        </article>
    </dialog>
</aside>
