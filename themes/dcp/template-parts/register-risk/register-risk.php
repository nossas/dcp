<section id="form-register-risk" class="risk-form">
    <div class="form-layout">

        <div class="form-image">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/wrapper71.svg" alt="Pessoa com megafone">
        </div>

        <div class="form-steps">
            <div class="form-steps__container">
                <div class="form-steps__container-header">
                    <h3 class="form-steps__container-title"><?= __('Adicionar risco') ?></h3>
                    <div class="multistepform__pipe"> | </div>
                    <span class="form-steps__stage" id="formStepsStage">1. Localização</span>
                </div>
                <div class="form-steps__progress-bar">
                    <div class="step-circle"><span>1</span></div>
                    <div class="line"></div>
                    <div class="step-circle"><span>2</span></div>
                    <div class="line"></div>
                    <div class="step-circle"><span>3</span></div>
                    <div class="line"></div>
                    <div class="step-circle"><span>4</span></div>
                    <div class="line"></div>
                    <div class="step-circle"><span>5</span></div>
                </div>
            </div>

            <form id="multiStepForm" class="multistepform" method="post" enctype="multipart/form-data">
                <div class="multistepform__1 step active" data-step="1">
                    <div class="multistepform__header">
                        <h2 class="multistepform__title"><?php _e('Onde isso está acontecendo?'); ?></h2>
                        <span class="multistepform__excerpt"><?php _e('Você pode digitar o endereço ou clicar no mapa para marcar o local.'); ?></span>
                    </div>
                    <div class="multistepform__fields">
                        <div class="multistepform__input">
                            <span class="multistepform__label"><?php _e('Localização'); ?></span>
                            <input type="text" name="endereco" placeholder="<?php _e('Digite o local ou endereço aqui'); ?>">
                        </div>
                        <!--
                          <!--   <span class="or"><?php _e('ou'); ?></span>
                            <a href="#" role="button" class="multistepform__button-map multistepform__button map-button">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                    <path d="M9 0.000976562C10.7902 0.000976562 12.5076 0.712645 13.7734 1.97852C15.0392 3.24427 15.7499 4.96094 15.75 6.75098C15.75 11.6042 9 18.001 9 18.001C8.9337 17.938 2.25 11.5802 2.25 6.75098C2.25013 4.96094 2.96178 3.24427 4.22754 1.97852C5.4933 0.712759 7.20996 0.00110478 9 0.000976562ZM9 3.37598C8.10507 3.3761 7.24708 3.73241 6.61426 4.36523C5.98144 4.99806 5.62513 5.85605 5.625 6.75098C5.625 7.64608 5.98132 8.50476 6.61426 9.1377C7.24708 9.77051 8.10507 10.1258 9 10.126C9.89511 10.126 10.7538 9.77063 11.3867 9.1377C12.0197 8.50476 12.375 7.64608 12.375 6.75098C12.3749 5.85605 12.0195 4.99805 11.3867 4.36523C10.7538 3.7323 9.89511 3.37598 9 3.37598Z" fill="#281414"/>
                                </svg>
                                <?php _e('Marcar no mapa'); ?>
                            </a> -->
                        -->
                    </div>
                    <div class="multistepform__buttons">
                        <a href="/reportar-riscos" role="button" class="multistepform__button back-to-map">
                            <?php _e('Voltar'); ?>
                        </a>

                        <a href="#" role="button" class="multistepform__button multistepform__button-next">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon-next" viewBox="0 0 18 18" width="18" height="18" aria-hidden="true">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M5.22776 1.85276C5.28001 1.80037 5.34208 1.75881 5.41042 1.73045C5.47876 1.7021 5.55202 1.6875 5.62601 1.6875C5.69999 1.6875 5.77325 1.7021 5.84159 1.73045C5.90993 1.75881 5.972 1.80037 6.02426 1.85276L12.7743 8.60275C12.8266 8.65501 12.8682 8.71708 12.8966 8.78542C12.9249 8.85376 12.9395 8.92702 12.9395 9.001C12.9395 9.07499 12.9249 9.14825 12.8966 9.21659C12.8682 9.28493 12.8266 9.347 12.7743 9.39925L6.02426 16.1493C5.91863 16.2549 5.77538 16.3142 5.62601 16.3142C5.47663 16.3142 5.33338 16.2549 5.22776 16.1493C5.12213 16.0436 5.06279 15.9004 5.06279 15.751C5.06279 15.6016 5.12213 15.4584 5.22776 15.3528L11.5806 9.001L5.22776 2.64926C5.17537 2.597 5.13381 2.53493 5.10545 2.46659C5.0771 2.39826 5.0625 2.32499 5.0625 2.25101C5.0625 2.17702 5.0771 2.10376 5.10545 2.03542C5.13381 1.96708 5.17537 1.90501 5.22776 1.85276V1.85276Z" fill="currentColor" />
                            </svg>
                            <?php _e('Continuar'); ?>
                        </a>
                    </div>
                </div>

                <div class="multistepform__2 step" data-step="2">
                    <div class="multistepform__header">
                        <h2 class="multistepform__title"><?php _e('O que está acontecendo?'); ?></h2>
                        <span class="multistepform__excerpt"><?php _e('Escolha uma opção e, se puder, descreva o que está acontecendo.'); ?></span>
                    </div>

                    <div class="multistepform__checkbox-group">
                        <?php
                        $terms = get_terms([
                            'taxonomy' => 'situacao_de_risco',
                            'hide_empty' => false,
                            'parent' => 0,
                        ]);

                        if (!is_wp_error($terms) && !empty($terms)) {
                            foreach ($terms as $term) {
                        ?>
                                <label class="multistepform__checkbox-option">
                                    <input type="radio" name="situacao_de_risco" value="<?php echo esc_attr($term->slug); ?>">
                                    <span class="multistepform__checkbox-label"><?php echo esc_html($term->name); ?></span>
                                </label>
                        <?php
                            }
                        }
                        ?>
                    </div>

                    <div class="multistepform__fields">
                        <div class="multistepform__input">
                            <span class="multistepform__label"><?php _e('Descrição'); ?></span>
                            <textarea name="descricao" placeholder="<?php _e('Descreva o risco...'); ?>"></textarea>
                        </div>
                    </div>

                    <div class="multistepform__buttons">
                        <a href="#" role="button" class="multistepform__button back-to-map prev">
                            <?php _e('Voltar'); ?>
                        </a>

                        <a href="#" role="button" class="multistepform__button multistepform__button-next">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon-next" viewBox="0 0 18 18" width="18" height="18" aria-hidden="true">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M5.22776 1.85276C5.28001 1.80037 5.34208 1.75881 5.41042 1.73045C5.47876 1.7021 5.55202 1.6875 5.62601 1.6875C5.69999 1.6875 5.77325 1.7021 5.84159 1.73045C5.90993 1.75881 5.972 1.80037 6.02426 1.85276L12.7743 8.60275C12.8266 8.65501 12.8682 8.71708 12.8966 8.78542C12.9249 8.85376 12.9395 8.92702 12.9395 9.001C12.9395 9.07499 12.9249 9.14825 12.8966 9.21659C12.8682 9.28493 12.8266 9.347 12.7743 9.39925L6.02426 16.1493C5.91863 16.2549 5.77538 16.3142 5.62601 16.3142C5.47663 16.3142 5.33338 16.2549 5.22776 16.1493C5.12213 16.0436 5.06279 15.9004 5.06279 15.751C5.06279 15.6016 5.12213 15.4584 5.22776 15.3528L11.5806 9.001L5.22776 2.64926C5.17537 2.597 5.13381 2.53493 5.10545 2.46659C5.0771 2.39826 5.0625 2.32499 5.0625 2.25101C5.0625 2.17702 5.0771 2.10376 5.10545 2.03542C5.13381 1.96708 5.17537 1.90501 5.22776 1.85276V1.85276Z" fill="currentColor" />
                            </svg>
                            <?php _e('Continuar'); ?>
                        </a>
                    </div>
                </div>

                <div class="multistepform__3 step" data-step="3">
                    <div class="multistepform__header">
                        <h2 class="multistepform__title"><?php _e('Gostaria de enviar uma foto ou vídeo?'); ?></h2>
                        <span class="multistepform__excerpt"><?php _e('Isso pode ajudar a entender melhor a situação, mas não é obrigatório.'); ?></span>
                    </div>
_
                    <div class="multistepform__upload-area">
                        <label class="multistepform__upload-button">
                            <input type="file" name="media_files[]" id="inputMidias" accept="image/*,video/*" multiple hidden>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                <path d="M0.5625 11.1385C0.711684 11.1385 0.854758 11.1978 0.960248 11.3033C1.06574 11.4087 1.125 11.5518 1.125 11.701V14.5135C1.125 14.8119 1.24353 15.098 1.4545 15.309C1.66548 15.52 1.95163 15.6385 2.25 15.6385H15.75C16.0484 15.6385 16.3345 15.52 16.5455 15.309C16.7565 15.098 16.875 14.8119 16.875 14.5135V11.701C16.875 11.5518 16.9343 11.4087 17.0398 11.3033C17.1452 11.1978 17.2883 11.1385 17.4375 11.1385C17.5867 11.1385 17.7298 11.1978 17.8352 11.3033C17.9407 11.4087 18 11.5518 18 11.701V14.5135C18 15.1102 17.7629 15.6825 17.341 16.1045C16.919 16.5265 16.3467 16.7635 15.75 16.7635H2.25C1.65326 16.7635 1.08097 16.5265 0.65901 16.1045C0.237053 15.6825 0 15.1102 0 14.5135V11.701C0 11.5518 0.0592632 11.4087 0.164752 11.3033C0.270242 11.1978 0.413316 11.1385 0.5625 11.1385Z" fill="#F9F3EA" />
                                <path d="M8.60175 1.29026C8.654 1.23787 8.71607 1.19631 8.78441 1.16795C8.85275 1.1396 8.92601 1.125 9 1.125C9.07399 1.125 9.14725 1.1396 9.21559 1.16795C9.28393 1.19631 9.346 1.23787 9.39825 1.29026L12.7733 4.66526C12.8789 4.77088 12.9382 4.91413 12.9382 5.06351C12.9382 5.21288 12.8789 5.35613 12.7733 5.46176C12.6676 5.56738 12.5244 5.62672 12.375 5.62672C12.2256 5.62672 12.0824 5.56738 11.9767 5.46176L9.5625 3.04638V12.9385C9.5625 13.0877 9.50324 13.2308 9.39775 13.3363C9.29226 13.4417 9.14918 13.501 9 13.501C8.85082 13.501 8.70774 13.4417 8.60225 13.3363C8.49676 13.2308 8.4375 13.0877 8.4375 12.9385V3.04638L6.02325 5.46176C5.97095 5.51405 5.90886 5.55554 5.84053 5.58384C5.7722 5.61215 5.69896 5.62672 5.625 5.62672C5.55104 5.62672 5.4778 5.61215 5.40947 5.58384C5.34114 5.55554 5.27905 5.51405 5.22675 5.46176C5.17445 5.40946 5.13297 5.34737 5.10466 5.27904C5.07636 5.2107 5.06179 5.13747 5.06179 5.06351C5.06179 4.98954 5.07636 4.91631 5.10466 4.84797C5.13297 4.77964 5.17445 4.71755 5.22675 4.66526L8.60175 1.29026Z" fill="#F9F3EA" />
                            </svg>
                            <?php _e('Selecionar foto ou vídeo'); ?>
                        </label>
                        <div id="mediaPreview" class="multistepform__upload-preview"></div>

                        <div class="multistepform__upload-or"><?php _e('ou'); ?></div>

                        <label class="multistepform__no-media-button">
                            <input type="checkbox" name="nao_tem_midia" value="1" hidden>
                            <?php _e('Não tenho agora'); ?>
                        </label>
                    </div>

                    <div class="multistepform__buttons">
                        <a href="#" role="button" class="multistepform__button back-to-map prev">
                            <?php _e('Voltar'); ?>
                        </a>

                        <a href="#" role="button" class="multistepform__button multistepform__button-next">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon-next" viewBox="0 0 18 18" width="18" height="18" aria-hidden="true">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M5.22776 1.85276C5.28001 1.80037 5.34208 1.75881 5.41042 1.73045C5.47876 1.7021 5.55202 1.6875 5.62601 1.6875C5.69999 1.6875 5.77325 1.7021 5.84159 1.73045C5.90993 1.75881 5.972 1.80037 6.02426 1.85276L12.7743 8.60275C12.8266 8.65501 12.8682 8.71708 12.8966 8.78542C12.9249 8.85376 12.9395 8.92702 12.9395 9.001C12.9395 9.07499 12.9249 9.14825 12.8966 9.21659C12.8682 9.28493 12.8266 9.347 12.7743 9.39925L6.02426 16.1493C5.91863 16.2549 5.77538 16.3142 5.62601 16.3142C5.47663 16.3142 5.33338 16.2549 5.22776 16.1493C5.12213 16.0436 5.06279 15.9004 5.06279 15.751C5.06279 15.6016 5.12213 15.4584 5.22776 15.3528L11.5806 9.001L5.22776 2.64926C5.17537 2.597 5.13381 2.53493 5.10545 2.46659C5.0771 2.39826 5.0625 2.32499 5.0625 2.25101C5.0625 2.17702 5.0771 2.10376 5.10545 2.03542C5.13381 1.96708 5.17537 1.90501 5.22776 1.85276V1.85276Z" fill="currentColor" />
                            </svg>
                            <?php _e('Continuar'); ?>
                        </a>
                    </div>
                </div>

                <div class="multistepform__4 step" data-step="4">
                    <div class="multistepform__header">
                        <h2 class="multistepform__title"><?php _e('Por fim, informe o seu nome e telefone'); ?></h2>
                        <span class="multistepform__excerpt"><?php _e('Se precisarmos de mais detalhes, podemos te chamar pelo WhatsApp. Seu contato não será compartilhado.'); ?></span>
                    </div>
                    <div class="multistepform__fields">
                        <div class="multistepform__input">
                            <span class="multistepform__label"><?php _e('Nome'); ?></span>
                            <input type="text" name="nome_completo" placeholder="<?php _e('Digite seu nome'); ?>">
                            <span class="multistepform__label multistepform__label-phone"><?php _e('Telefone (Whatsapp)'); ?></span>
                            <input type="tel" name="telefone" placeholder="<?php _e('(xx) xxxxx-xxxx'); ?>">
                            <label for="campo-email" class="multistepform__label multistepform__label-email"><?php _e('Email'); ?></label>
                            <input id="campo-email" type="email" name="email" placeholder="<?php _e('exemplo@exemplo.com.br'); ?>">
                        </div>
                    </div>

                    <label class="multistepform__accept"><input type="checkbox" name="autoriza_contato" value="sim"> <?php _e('Autorizo o contato via WhatsApp. Meus dados serão usados apenas para esse fim e não serão compartilhados.'); ?></label>

                    <div class="multistepform__buttons">
                        <a href="#" role="button" class="multistepform__button back-to-map prev">
                            <?php _e('Voltar'); ?>
                        </a>

                        <a href="#" role="button" class="multistepform__button multistepform__button-next">
                            <svg xmlns="http://www.w3.org/2000/svg" class="icon-next" viewBox="0 0 18 18" width="18" height="18" aria-hidden="true">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M5.22776 1.85276C5.28001 1.80037 5.34208 1.75881 5.41042 1.73045C5.47876 1.7021 5.55202 1.6875 5.62601 1.6875C5.69999 1.6875 5.77325 1.7021 5.84159 1.73045C5.90993 1.75881 5.972 1.80037 6.02426 1.85276L12.7743 8.60275C12.8266 8.65501 12.8682 8.71708 12.8966 8.78542C12.9249 8.85376 12.9395 8.92702 12.9395 9.001C12.9395 9.07499 12.9249 9.14825 12.8966 9.21659C12.8682 9.28493 12.8266 9.347 12.7743 9.39925L6.02426 16.1493C5.91863 16.2549 5.77538 16.3142 5.62601 16.3142C5.47663 16.3142 5.33338 16.2549 5.22776 16.1493C5.12213 16.0436 5.06279 15.9004 5.06279 15.751C5.06279 15.6016 5.12213 15.4584 5.22776 15.3528L11.5806 9.001L5.22776 2.64926C5.17537 2.597 5.13381 2.53493 5.10545 2.46659C5.0771 2.39826 5.0625 2.32499 5.0625 2.25101C5.0625 2.17702 5.0771 2.10376 5.10545 2.03542C5.13381 1.96708 5.17537 1.90501 5.22776 1.85276V1.85276Z" fill="currentColor" />
                            </svg>
                            <?php _e('Continuar'); ?>
                        </a>
                    </div>
                </div>

                <div class="multistepform__5 step" data-step="5">
                    <div class="multistepform__header">
                        <h2 class="multistepform__title"><?php _e('Confira se está tudo correto:'); ?></h2>
                    </div>

                    <div class="multistepform__review">
                        <div class="multistepform__review-item">
                            <div class="multistepform__location">
                                <div class="multistepform__label"><?php _e('Localização:'); ?></div>
                                <span class="multistepform__review-location" id="reviewEndereco"></span>
                            </div>

                            <div class="multistepform__pipe"> | </div>

                            <div class="multistepform__risk">
                                <div class="multistepform__label"><?php _e('Tipo de risco:'); ?></div>
                                <span class="multistepform__review-tag" id="reviewTipoRisco">
                                    <span id="reviewTipoRiscoTexto" class="multistepform__review-icon"></span>
                                </span>
                            </div>
                        </div>

                        <div class="multistepform__description">
                            <div class="multistepform__label"><?php _e('Descrição:'); ?></div>
                            <span class="multistepform__review-description" id="reviewDescricao">
                            </span>
                        </div>
                     <div class="multistepform__review-media">
                        <div class="multistepform__label"><?php _e('Fotos/vídeos:'); ?></div>
                        <div class="multistepform__carousel swiper" id="reviewMidias">
                            <?php get_template_part('template-parts/splide') ?>
                            <p></p>
                        </div>
                        </div>
                    </div>
                        <div class="multistepform__accept-terms">
                            <img src="/caminho/para/information.svg" alt="Informação" class="icon-info" />
                            “Ao enviar este relato, você concorda com o uso das informações e mídias no site da Defesa Climática Popular e em outras ações do projeto, sem exibição dos seus dados pessoais.”
                        </div>
                        <div class="multistepform__buttons">

                            <a href="#" id="editarResumo" role="button" class="multistepform__button multistepform__button-edit">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="19" viewBox="0 0 18 19" fill="none">
                                    <path d="M17.4397 2.87435C17.5449 2.97979 17.6039 3.12259 17.6039 3.27147C17.6039 3.42035 17.5449 3.56316 17.4397 3.6686L16.2664 4.8431L14.0164 2.5931L15.1897 1.4186C15.2952 1.31315 15.4383 1.25391 15.5874 1.25391C15.7366 1.25391 15.8796 1.31315 15.9851 1.4186L17.4397 2.87322V2.87435ZM15.471 5.63735L13.221 3.38735L5.55638 11.0531C5.49446 11.115 5.44785 11.1905 5.42025 11.2736L4.51463 13.9893C4.4982 14.0389 4.49587 14.092 4.50789 14.1427C4.51991 14.1935 4.54581 14.2399 4.5827 14.2768C4.61958 14.3137 4.666 14.3396 4.71676 14.3516C4.76751 14.3636 4.82062 14.3613 4.87012 14.3448L7.58588 13.4392C7.66886 13.412 7.74435 13.3657 7.80638 13.3042L15.471 5.63735Z" fill="#B83D13" />
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M1.125 15.8793C1.125 16.3269 1.30279 16.7561 1.61926 17.0726C1.93573 17.3891 2.36495 17.5668 2.8125 17.5668H15.1875C15.6351 17.5668 16.0643 17.3891 16.3807 17.0726C16.6972 16.7561 16.875 16.3269 16.875 15.8793V9.12935C16.875 8.98016 16.8157 8.83709 16.7102 8.7316C16.6048 8.62611 16.4617 8.56685 16.3125 8.56685C16.1633 8.56685 16.0202 8.62611 15.9148 8.7316C15.8093 8.83709 15.75 8.98016 15.75 9.12935V15.8793C15.75 16.0285 15.6907 16.1716 15.5852 16.2771C15.4798 16.3826 15.3367 16.4418 15.1875 16.4418H2.8125C2.66332 16.4418 2.52024 16.3826 2.41475 16.2771C2.30926 16.1716 2.25 16.0285 2.25 15.8793V3.50435C2.25 3.35516 2.30926 3.21209 2.41475 3.1066C2.52024 3.00111 2.66332 2.94185 2.8125 2.94185H10.125C10.2742 2.94185 10.4173 2.88259 10.5227 2.7771C10.6282 2.67161 10.6875 2.52853 10.6875 2.37935C10.6875 2.23016 10.6282 2.08709 10.5227 1.9816C10.4173 1.87611 10.2742 1.81685 10.125 1.81685H2.8125C2.36495 1.81685 1.93573 1.99464 1.61926 2.31111C1.30279 2.62757 1.125 3.0568 1.125 3.50435V15.8793Z" fill="#B83D13" />
                                </svg>
                                <?php _e('Editar'); ?>
                            </a>

                            <button type="submit" id="enviarResumo" class="multistepform__button multistepform__button-next multistepform__button-submit">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="19" viewBox="0 0 18 19" fill="none">
                                    <path d="M15.5865 4.79416C15.6388 4.84641 15.6804 4.90849 15.7088 4.97682C15.7371 5.04516 15.7517 5.11842 15.7517 5.19241C15.7517 5.2664 15.7371 5.33966 15.7088 5.408C15.6804 5.47634 15.6388 5.53841 15.5865 5.59066L7.71146 13.4657C7.65921 13.518 7.59714 13.5596 7.5288 13.588C7.46046 13.6163 7.3872 13.6309 7.31321 13.6309C7.23922 13.6309 7.16596 13.6163 7.09762 13.588C7.02928 13.5596 6.96721 13.518 6.91496 13.4657L2.97746 9.52816C2.87184 9.42254 2.8125 9.27928 2.8125 9.12991C2.8125 8.98054 2.87184 8.83728 2.97746 8.73166C3.08308 8.62604 3.22634 8.5667 3.37571 8.5667C3.52508 8.5667 3.66834 8.62604 3.77396 8.73166L7.31321 12.272L14.79 4.79416C14.8422 4.74178 14.9043 4.70022 14.9726 4.67186C15.041 4.6435 15.1142 4.62891 15.1882 4.62891C15.2622 4.62891 15.3355 4.6435 15.4038 4.67186C15.4721 4.70022 15.5342 4.74178 15.5865 4.79416V4.79416Z" fill="#F9F3EA" />
                                </svg>
                                <?php _e('Enviar'); ?>

                            </button>
                        </div>

                </div>


                <div class="multistepform__6 step" data-step="6">
                    <div class="multistepform__header multistepform__header-success">
                        <h2 class="multistepform__title multistepform__title-success"><?php _e('Informações enviadas com sucesso!'); ?></h2>
                        <p><?php _e('Obrigado por compartilhar! Seu relato pode ajudar outras pessoas a se protegerem.'); ?></p>
                        <p><?php _e('Ele vai passar por uma verificação rápida e, assim que for aprovado, será publicado no mapa. Você receberá uma notificação no WhatsApp quando isso acontecer.') ?></p>
                    </div>
                    <div class="multistepform__buttons multistepform__buttons-success">
                        <a href="/reportar-riscos" class="multistepform__button multistepform__button-more-risk">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                <path d="M9 18.002C9 18.002 15.75 11.6052 15.75 6.75195C15.75 4.96174 15.0388 3.24485 13.773 1.97898C12.5071 0.713112 10.7902 0.00195312 9 0.00195312C7.20979 0.00195312 5.4929 0.713112 4.22703 1.97898C2.96116 3.24485 2.25 4.96174 2.25 6.75195C2.25 11.6052 9 18.002 9 18.002ZM9 10.127C8.10489 10.127 7.24645 9.77137 6.61351 9.13844C5.98058 8.5055 5.625 7.64706 5.625 6.75195C5.625 5.85685 5.98058 4.9984 6.61351 4.36547C7.24645 3.73253 8.10489 3.37695 9 3.37695C9.89511 3.37695 10.7536 3.73253 11.3865 4.36547C12.0194 4.9984 12.375 5.85685 12.375 6.75195C12.375 7.64706 12.0194 8.5055 11.3865 9.13844C10.7536 9.77137 9.89511 10.127 9 10.127Z" fill="#F9F3EA" />
                            </svg>
                            <?php _e('Adicionar outro risco'); ?>
                        </a>
                        <a href="/mapa" class="multistepform__button multistepform__button-backmapa"><?php _e('Voltar para o mapa'); ?></a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
