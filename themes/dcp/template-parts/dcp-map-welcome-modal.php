<div
    x-data="welcomeModal"
    x-init="init()"
    x-show="isOpen"
    x-transition:enter="transition ease-out duration-300"
    x-transition:enter-start="opacity-0"
    x-transition:enter-end="opacity-100"
    x-transition:leave="transition ease-in duration-200"
    x-transition:leave-start="opacity-100"
    x-transition:leave-end="opacity-0"
    class="welcome-modal-overlay"
    style="display: none;">
    <div class="dcp-map-welcome-modal" @click.away="closeModal()">

        <div class="dcp-map-welcome-modal__step-content" x-show="step === 1">
            <div class="dcp-map-welcome-modal__image-container">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/modal-page-map/image1.svg" alt="Ilustração de boas-vindas" class="dcp-map-welcome-modal__image">
            </div>
            <div class="dcp-map-welcome-modal__text-container">
                <h3 class="dcp-map-welcome-modal__title"><?= _e('Bem-vindo(a) ao Mapeamento da Defesa Climática Popular!', 'dcp'); ?></h3>
                <p class="dcp-map-welcome-modal__text"><?= _e('Explore as camadas de risco de alagamento e os pontos de apoio no território.', 'dcp'); ?></p>
                <div class="dcp-map-welcome-modal__tags">
                    <ul class="dcp-map-legend__list">
                        <li class="dcp-map-legend__item dcp-map-legend--desktop dcp-map-legend__alagamento-nivel1">
                            <button type="button" aria-label="<?= __('Exibir/ocultar zona de risco muito baixo de alagamento', 'hacklabr') ?>">
                                <span class="dcp-map-legend__item--alagamento-nivel1"><?= __('Risco de alagamento: Muito baixo', 'hacklabr') ?></span>
                            </button>
                        </li>

                        <li class="dcp-map-legend__item dcp-map-legend--desktop dcp-map-legend__alagamento-nivel2">
                            <button type="button" aria-label="<?= __('Exibir/ocultar zona de risco baixo de alagamento', 'hacklabr') ?>">
                                <span class="dcp-map-legend__item--alagamento-nivel2"><?= __('Risco de alagamento: Baixo', 'hacklabr') ?></span>
                            </button>
                        </li>

                        <li class="dcp-map-legend__item dcp-map-legend--desktop dcp-map-legend__alagamento-nivel3">
                            <button type="button" aria-label="<?= __('Exibir/ocultar zona de risco médio de alagamento', 'hacklabr') ?>">
                                <span class="dcp-map-legend__item--alagamento-nivel3"><?= __('Risco de alagamento: Médio', 'hacklabr') ?></span>
                            </button>
                        </li>
                        <li class="dcp-map-legend__item dcp-map-legend--desktop dcp-map-legend__alagamento-nivel4">
                            <button type="button" aria-label="<?= __('Exibir/ocultar zona de risco alto de alagamento', 'hacklabr') ?>">
                                <span class="dcp-map-legend__item--alagamento-nivel4"><?= __('Risco de alagamento: Alto', 'hacklabr') ?></span>
                            </button>
                        </li>
                        <li class="dcp-map-legend__item dcp-map-legend--desktop dcp-map-legend__alagamento-nivel5">
                            <button type="button" aria-label="<?= __('Exibir/ocultar zona de risco muito alto de alagamento', 'hacklabr') ?>">
                                <span class="dcp-map-legend__item--alagamento-nivel5"><?= __('Risco de alagamento: Muito alto', 'hacklabr') ?></span>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>

        <div class="dcp-map-welcome-modal__step-content padding" x-show="step === 2">
            <div class="dcp-map-welcome-modal__image-container">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/modal-page-map/image2.png" alt="Ilustração de boas-vindas" class="dcp-map-welcome-modal__image dcp-map-welcome-modal__image--step2">
            </div>
            <div class="dcp-map-welcome-modal__text-container">
                <h3 class="dcp-map-welcome-modal__title bold-position"><?= _e('O que você pode fazer aqui?', 'dcp'); ?></h3>
                <p class="dcp-map-welcome-modal__text"><?= _e('Este mapeamento é uma ferramenta para agir coletivamente. Você pode:', 'dcp'); ?></p>
                <div class="dcp-map-welcome-modal__list">
                    <div class="dcp-map-welcome-modal__item">
                        <div class="dcp-map-welcolme-modal__item--container">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/modal-page-map/lupa.svg" alt="lupa" class="dcp-map-welcome-modal__item-icon">
                            <div class="dcp-map-welcome-modal__item-text-container">
                                <h4 class="dcp-map-welcome-modal__item-title"><?= _e('Buscar um local', 'dcp'); ?></h4>
                                <span class="dcp-map-welcome-modal__item-text"><?= _e('Veja o que já foi mapeado na sua rua ou em outras regiões.', 'dcp'); ?></span>
                            </div>
                        </div>
                        <div class="dcp-map-welcolme-modal__item--container">
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/modal-page-map/apoio.svg" alt="apoio" class="dcp-map-welcome-modal__item-icon">
                            <div class="dcp-map-welcome-modal__item-text-container">
                                <h4 class="dcp-map-welcome-modal__item-title dcp-map-welcome-modal__item-title-apoio"><?= _e('Ver pontos de apoio', 'dcp'); ?></h4>
                                <span class="dcp-map-welcome-modal__item-text"><?= _e('Acesse no mapa os locais que oferecem apoio, como serviços públicos e iniciativas comunitárias.', 'dcp'); ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="dcp-map-welcome-modal__step-content padding" x-show="step === 3">
            <div class="dcp-map-welcome-modal__image-container">
                <img src="<?php echo get_template_directory_uri(); ?>/assets/images/modal-page-map/image3.svg" alt="Ilustração de boas-vindas" class="dcp-map-welcome-modal__image">
            </div>
            <div class="dcp-map-welcome-modal__text-container">
                <h3 class="dcp-map-welcome-modal__title bold-position"><?= _e('Ajude a manter o mapa vivo!', 'dcp'); ?></h3>
                <div class="dcp-map-welcome-modal__list">
                    <div class="dcp-map-welcome-modal__item dcp-map-welcome-modal__item-2 ">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/modal-page-map/icon1.svg" alt="exclamacao" class="dcp-map-welcome-modal__item-icon icon">
                        <span class="dcp-map-welcome-modal__item-text"><?= _e('Em ', 'dcp'); ?> <strong><?= _e('"O que fazer"', 'dcp'); ?></strong> <?= _e(' você encontra orientações e contatos úteis.', 'dcp'); ?></span>
                    </div>
                    <div class="dcp-map-welcome-modal__item dcp-map-welcome-modal__item-2">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/modal-page-map/icon2.svg" alt="interrogacao" class="dcp-map-welcome-modal__item-icon icon">
                        <span class="dcp-map-welcome-modal__item-text"><?= _e('Clique no ', 'dcp'); ?> <strong><?= _e('"?"', 'dcp'); ?></strong> <?= _e(' na barra à esquerda para ver como usar o mapa.', 'dcp'); ?></span>
                    </div>
                    <div class="dcp-map-welcome-modal__item dcp-map-welcome-modal__item-2">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/modal-page-map/icon3.svg" alt="faq" class="dcp-map-welcome-modal__item-icon icon">
                        <span class="dcp-map-welcome-modal__item-text"><?= _e('Em caso de emergência, ligue para a Defesa Civil: ', 'dcp'); ?> <strong><?= _e('199.', 'dcp'); ?></strong></span>
                    </div>
                </div>
            </div>
        </div>

        <div class="dcp-map-welcome-modal__pagination">
            <span class="dcp-map-welcome-modal__dot" :class="{ 'dcp-map-welcome-modal__dot--active': step === 1 }"></span>
            <span class="dcp-map-welcome-modal__dot" :class="{ 'dcp-map-welcome-modal__dot--active': step === 2 }"></span>
            <span class="dcp-map-welcome-modal__dot" :class="{ 'dcp-map-welcome-modal__dot--active': step === 3 }"></span>
        </div>

        <div class="dcp-map-welcome-modal__actions">
            <button type="button" class="dcp-map-welcome-modal__button dcp-map-welcome-modal__button--skip" @click="closeModal()"><?php echo _e('Pular', 'dcp'); ?></button>
            <button
                type="button"
                class="dcp-map-welcome-modal__button dcp-map-welcome-modal__button--next"
                :class="{ 'dcp-map-welcome-modal__button--last-step': step === 3 }"
                @click="nextStep()">
                <span x-text="step < 3 ? 'Próximo' : 'Vamos lá!'"></span>
                <img
                    src="<?php echo get_template_directory_uri(); ?>/assets/images/modal-page-map/arrow-right.svg"
                    alt="Avançar"
                    class="dcp-map-welcome-modal__arrow-icon"
                    x-show="step < 3">
            </button>
        </div>
    </div>
</div>
