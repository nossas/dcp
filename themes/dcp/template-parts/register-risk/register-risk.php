<section id="form-register-risk" class="risk-form">
    <div class="form-layout">

        <div class="form-image">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/wrapper71.svg" alt="Pessoa com megafone">
        </div>

        <div class="form-steps">

            <div class="progress-bar">
                <div class="step-circle" data-step="1">1</div>
                <div class="line"></div>
                <div class="step-circle" data-step="2">2</div>
                <div class="line"></div>
                <div class="step-circle" data-step="3">3</div>
                <div class="line"></div>
                <div class="step-circle" data-step="4">4</div>
                <div class="line"></div>
                <div class="step-circle" data-step="5">5</div>
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
                            <input type="text" name="cep" placeholder="<?php _e('Digite o local ou endereço aqui'); ?>" required>
                        </div>
                        <span class="or"><?php _e('ou'); ?></span>
                        <a href="#" role="button" class="multistepform__button-map multistepform__button map-button">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                <path d="M4.66425 0.165227C4.71658 0.113025 4.77869 0.0716501 4.84703 0.0434669C4.91536 0.0152837 4.98858 0.000845444 5.0625 0.00097746H12.9375C13.0867 0.00097746 13.2298 0.0602407 13.3352 0.16573C13.4407 0.271219 13.5 0.414293 13.5 0.563477C13.5 1.32848 13.1153 1.88423 12.7733 2.22735C12.6315 2.36798 12.492 2.47935 12.375 2.5626V7.54748L12.4628 7.60148C12.6911 7.74435 12.9983 7.95473 13.3076 8.22585C13.905 8.74785 14.625 9.59385 14.625 10.6885C14.625 10.8377 14.5657 10.9807 14.4602 11.0862C14.3548 11.1917 14.2117 11.251 14.0625 11.251H9.5625V16.3135C9.5625 16.624 9.3105 18.001 9 18.001C8.6895 18.001 8.4375 16.624 8.4375 16.3135V11.251H3.9375C3.78832 11.251 3.64524 11.1917 3.53975 11.0862C3.43426 10.9807 3.375 10.8377 3.375 10.6885C3.375 9.59385 4.095 8.74785 4.69125 8.22585C4.98229 7.97314 5.29471 7.74616 5.625 7.54748V2.5626C5.48313 2.46216 5.34992 2.35001 5.22675 2.22735C4.88475 1.88423 4.5 1.32735 4.5 0.563477C4.49987 0.489559 4.51431 0.41634 4.54249 0.348006C4.57067 0.279671 4.61205 0.217561 4.66425 0.165227V0.165227Z" fill="#281414" />
                            </svg>
                            <?php _e('Marcar no mapa'); ?>
                        </a>
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
                        <label class="multistepform__checkbox-option">
                            <input type="checkbox" name="tipo_risco[]" value="Alagamento">
                            <span class="multistepform__checkbox-label">Alagamento</span>
                        </label>
                        <label class="multistepform__checkbox-option">
                            <input type="checkbox" name="tipo_risco[]" value="Lixo">
                            <span class="multistepform__checkbox-label">Lixo</span>
                        </label>
                        <label class="multistepform__checkbox-option">
                            <input type="checkbox" name="tipo_risco[]" value="Outros">
                            <span class="multistepform__checkbox-label">Outros</span>
                        </label>
                    </div>

                    <div class="multistepform__fields">
                        <div class="multistepform__input">
                            <span class="multistepform__label"><?php _e('Descrição'); ?></span>
                            <textarea name="descricao_risco" placeholder="<?php _e('Descreva o risco...'); ?>" required></textarea>
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

                    <div class="multistepform__upload-area">
                        <label class="multistepform__upload-button">
                            <input type="file" name="arquivo" hidden>
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                <path d="M0.5625 11.1385C0.711684 11.1385 0.854758 11.1978 0.960248 11.3033C1.06574 11.4087 1.125 11.5518 1.125 11.701V14.5135C1.125 14.8119 1.24353 15.098 1.4545 15.309C1.66548 15.52 1.95163 15.6385 2.25 15.6385H15.75C16.0484 15.6385 16.3345 15.52 16.5455 15.309C16.7565 15.098 16.875 14.8119 16.875 14.5135V11.701C16.875 11.5518 16.9343 11.4087 17.0398 11.3033C17.1452 11.1978 17.2883 11.1385 17.4375 11.1385C17.5867 11.1385 17.7298 11.1978 17.8352 11.3033C17.9407 11.4087 18 11.5518 18 11.701V14.5135C18 15.1102 17.7629 15.6825 17.341 16.1045C16.919 16.5265 16.3467 16.7635 15.75 16.7635H2.25C1.65326 16.7635 1.08097 16.5265 0.65901 16.1045C0.237053 15.6825 0 15.1102 0 14.5135V11.701C0 11.5518 0.0592632 11.4087 0.164752 11.3033C0.270242 11.1978 0.413316 11.1385 0.5625 11.1385Z" fill="#F9F3EA" />
                                <path d="M8.60175 1.29026C8.654 1.23787 8.71607 1.19631 8.78441 1.16795C8.85275 1.1396 8.92601 1.125 9 1.125C9.07399 1.125 9.14725 1.1396 9.21559 1.16795C9.28393 1.19631 9.346 1.23787 9.39825 1.29026L12.7733 4.66526C12.8789 4.77088 12.9382 4.91413 12.9382 5.06351C12.9382 5.21288 12.8789 5.35613 12.7733 5.46176C12.6676 5.56738 12.5244 5.62672 12.375 5.62672C12.2256 5.62672 12.0824 5.56738 11.9767 5.46176L9.5625 3.04638V12.9385C9.5625 13.0877 9.50324 13.2308 9.39775 13.3363C9.29226 13.4417 9.14918 13.501 9 13.501C8.85082 13.501 8.70774 13.4417 8.60225 13.3363C8.49676 13.2308 8.4375 13.0877 8.4375 12.9385V3.04638L6.02325 5.46176C5.97095 5.51405 5.90886 5.55554 5.84053 5.58384C5.7722 5.61215 5.69896 5.62672 5.625 5.62672C5.55104 5.62672 5.4778 5.61215 5.40947 5.58384C5.34114 5.55554 5.27905 5.51405 5.22675 5.46176C5.17445 5.40946 5.13297 5.34737 5.10466 5.27904C5.07636 5.2107 5.06179 5.13747 5.06179 5.06351C5.06179 4.98954 5.07636 4.91631 5.10466 4.84797C5.13297 4.77964 5.17445 4.71755 5.22675 4.66526L8.60175 1.29026Z" fill="#F9F3EA" />
                            </svg>
                            <?php _e('Selecionar foto ou vídeo'); ?>
                        </label>

                        <div class="multistepform__upload-or"><?php _e('ou'); ?></div>

                        <label class="multistepform__no-media-button">
                            <input type="checkbox" name="nao_tem_midia" value="1" hidden>
                            <?php _e('Não tenho agora'); ?>
                        </label>
                    </div>

                    <div class="multistepform__buttons">
                        <a href="/reportar-riscos" role="button" class="multistepform__button back-to-map prev">
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
                            <input type="text" name="nome" placeholder="<?php _e('Digite seu nome'); ?>" required>
                            <span class="multistepform__label"><?php _e('Telefone (Whatsapp)'); ?></span>
                            <input type="tel" name="telefone" placeholder="<?php _e('(xx) xxxxx-xxxx'); ?>" required>
                        </div>
                    </div>

                    <label class="multistepform__accept"><input type="radio" name="whatsapp" value="sim" required> <?php _e('Autorizo o contato via WhatsApp. Meus dados serão usados apenas para esse fim e não serão compartilhados.'); ?></label>

                    <div class="multistepform__buttons">
                        <a href="/reportar-riscos" role="button" class="multistepform__button back-to-map prev">
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
                                <span class="multistepform__review-location"><?= __('Rua Conselheiro Corrêa') ?></span>
                            </div>

                            <div class="multistepform__pipe"> | </div>

                            <div class="multistepform__risk">
                                <div class="multistepform__label"><?php _e('Tipo de risco:'); ?></div>
                                <span class="multistepform__review-tag">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17" fill="none">
                                        <path d="M0.0358596 3.81591C0.0852132 3.69287 0.181411 3.59447 0.303301 3.54234C0.425192 3.4902 0.562797 3.48861 0.68586 3.53791L2.44286 4.24091C2.80043 4.38391 3.19929 4.38391 3.55686 4.24091L4.57086 3.83491C5.1669 3.59649 5.83182 3.59649 6.42786 3.83491L7.44286 4.24091C7.80043 4.38391 8.19929 4.38391 8.55686 4.24091L9.57086 3.83491C10.1669 3.59649 10.8318 3.59649 11.4279 3.83491L12.4429 4.24091C12.8004 4.38391 13.1993 4.38391 13.5569 4.24091L15.3139 3.53791C15.3751 3.51188 15.4409 3.49833 15.5074 3.49805C15.574 3.49778 15.6399 3.51078 15.7013 3.53631C15.7628 3.56184 15.8185 3.59937 15.8653 3.64671C15.912 3.69405 15.9488 3.75025 15.9736 3.81201C15.9984 3.87376 16.0105 3.93984 16.0094 4.00637C16.0083 4.0729 15.994 4.13853 15.9672 4.19943C15.9404 4.26033 15.9017 4.31527 15.8534 4.36103C15.8051 4.40679 15.7481 4.44245 15.6859 4.46591L13.9279 5.16891C13.3318 5.40734 12.6669 5.40734 12.0709 5.16891L11.0569 4.76291C10.6993 4.61991 10.3004 4.61991 9.94286 4.76291L8.92786 5.16891C8.33182 5.40734 7.6669 5.40734 7.07086 5.16891L6.05686 4.76291C5.69929 4.61991 5.30043 4.61991 4.94286 4.76291L3.92786 5.16891C3.33182 5.40734 2.6669 5.40734 2.07086 5.16891L0.31386 4.46591C0.190818 4.41656 0.092413 4.32036 0.0402813 4.19847C-0.0118504 4.07658 -0.0134408 3.93898 0.0358596 3.81591ZM0.0358596 6.81591C0.0852132 6.69287 0.181411 6.59447 0.303301 6.54234C0.425192 6.4902 0.562797 6.48861 0.68586 6.53791L2.44286 7.24091C2.80043 7.38391 3.19929 7.38391 3.55686 7.24091L4.57086 6.83491C5.1669 6.59649 5.83182 6.59649 6.42786 6.83491L7.44286 7.24091C7.80043 7.38391 8.19929 7.38391 8.55686 7.24091L9.57086 6.83491C10.1669 6.59649 10.8318 6.59649 11.4279 6.83491L12.4429 7.24091C12.8004 7.38391 13.1993 7.38391 13.5569 7.24091L15.3139 6.53791C15.3751 6.51188 15.4409 6.49833 15.5074 6.49805C15.574 6.49778 15.6399 6.51078 15.7013 6.53631C15.7628 6.56184 15.8185 6.59937 15.8653 6.64671C15.912 6.69405 15.9488 6.75025 15.9736 6.81201C15.9984 6.87376 16.0105 6.93984 16.0094 7.00637C16.0083 7.0729 15.994 7.13853 15.9672 7.19943C15.9404 7.26033 15.9017 7.31527 15.8534 7.36103C15.8051 7.40679 15.7481 7.44245 15.6859 7.46591L13.9279 8.16891C13.3318 8.40734 12.6669 8.40734 12.0709 8.16891L11.0569 7.76291C10.6993 7.61991 10.3004 7.61991 9.94286 7.76291L8.92786 8.16891C8.33182 8.40734 7.6669 8.40734 7.07086 8.16891L6.05686 7.76291C5.69929 7.61991 5.30043 7.61991 4.94286 7.76291L3.92786 8.16891C3.33182 8.40734 2.6669 8.40734 2.07086 8.16891L0.31386 7.46591C0.190818 7.41656 0.092413 7.32036 0.0402813 7.19847C-0.0118504 7.07658 -0.0134408 6.93898 0.0358596 6.81591ZM0.0358596 9.81591C0.0852132 9.69287 0.181411 9.59447 0.303301 9.54234C0.425192 9.4902 0.562797 9.48861 0.68586 9.53791L2.44286 10.2409C2.80043 10.3839 3.19929 10.3839 3.55686 10.2409L4.57086 9.83491C5.1669 9.59649 5.83182 9.59649 6.42786 9.83491L7.44286 10.2409C7.80043 10.3839 8.19929 10.3839 8.55686 10.2409L9.57086 9.83491C10.1669 9.59649 10.8318 9.59649 11.4279 9.83491L12.4429 10.2409C12.8004 10.3839 13.1993 10.3839 13.5569 10.2409L15.3139 9.53791C15.3751 9.51188 15.4409 9.49833 15.5074 9.49805C15.574 9.49778 15.6399 9.51078 15.7013 9.53631C15.7628 9.56184 15.8185 9.59937 15.8653 9.64671C15.912 9.69405 15.9488 9.75025 15.9736 9.81201C15.9984 9.87376 16.0105 9.93984 16.0094 10.0064C16.0083 10.0729 15.994 10.1385 15.9672 10.1994C15.9404 10.2603 15.9017 10.3153 15.8534 10.361C15.8051 10.4068 15.7481 10.4424 15.6859 10.4659L13.9279 11.1689C13.3318 11.4073 12.6669 11.4073 12.0709 11.1689L11.0569 10.7629C10.6993 10.6199 10.3004 10.6199 9.94286 10.7629L8.92786 11.1689C8.33182 11.4073 7.6669 11.4073 7.07086 11.1689L6.05686 10.7629C5.69929 10.6199 5.30043 10.6199 4.94286 10.7629L3.92786 11.1689C3.33182 11.4073 2.6669 11.4073 2.07086 11.1689L0.31386 10.4659C0.190818 10.4166 0.092413 10.3204 0.0402813 10.1985C-0.0118504 10.0766 -0.0134408 9.93898 0.0358596 9.81591ZM0.0358596 12.8159C0.0852132 12.6929 0.181411 12.5945 0.303301 12.5423C0.425192 12.4902 0.562797 12.4886 0.68586 12.5379L2.44286 13.2409C2.80043 13.3839 3.19929 13.3839 3.55686 13.2409L4.57086 12.8349C5.1669 12.5965 5.83182 12.5965 6.42786 12.8349L7.44286 13.2409C7.80043 13.3839 8.19929 13.3839 8.55686 13.2409L9.57086 12.8349C10.1669 12.5965 10.8318 12.5965 11.4279 12.8349L12.4429 13.2409C12.8004 13.3839 13.1993 13.3839 13.5569 13.2409L15.3139 12.5379C15.3751 12.5119 15.4409 12.4983 15.5074 12.4981C15.574 12.4978 15.6399 12.5108 15.7013 12.5363C15.7628 12.5618 15.8185 12.5994 15.8653 12.6467C15.912 12.6941 15.9488 12.7502 15.9736 12.812C15.9984 12.8738 16.0105 12.9398 16.0094 13.0064C16.0083 13.0729 15.994 13.1385 15.9672 13.1994C15.9404 13.2603 15.9017 13.3153 15.8534 13.361C15.8051 13.4068 15.7481 13.4424 15.6859 13.4659L13.9279 14.1689C13.3318 14.4073 12.6669 14.4073 12.0709 14.1689L11.0569 13.7629C10.6993 13.6199 10.3004 13.6199 9.94286 13.7629L8.92786 14.1689C8.33182 14.4073 7.6669 14.4073 7.07086 14.1689L6.05686 13.7629C5.69929 13.6199 5.30043 13.6199 4.94286 13.7629L3.92786 14.1689C3.33182 14.4073 2.6669 14.4073 2.07086 14.1689L0.31386 13.4659C0.190818 13.4166 0.092413 13.3204 0.0402813 13.1985C-0.0118504 13.0766 -0.0134408 12.939 0.0358596 12.8159Z" fill="#F9F3EA" />
                                    </svg>
                                    <?php _e('Alagamento'); ?>
                                </span>
                            </div>
                        </div>

                        <div class="multistepform__description">
                            <div class="multistepform__label"><?php _e('Descrição:'); ?></div>
                            <span class="multistepform__review-description">
                                <?php _e('Alagamento na Rua Conselheiro Corrêa, próximo ao número 123. A água está acumulada há mais de 3 dias, dificultando a passagem de pedestres e veículos.'); ?>
                            </span>
                        </div>

                        <div class="multistepform__review-media">
                            <div class="multistepform__label"><?php _e('Fotos/vídeos:'); ?></div>
                            <div class="multistepform__carousel">
                                <div class="multistepform__carousel-item">
                                    Aqui vão as mídias
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="multistepform__buttons">
                        <a href="#" role="button" class="multistepform__button multistepform__button-edit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="19" viewBox="0 0 18 19" fill="none">
                                <path d="M17.4397 2.87435C17.5449 2.97979 17.6039 3.12259 17.6039 3.27147C17.6039 3.42035 17.5449 3.56316 17.4397 3.6686L16.2664 4.8431L14.0164 2.5931L15.1897 1.4186C15.2952 1.31315 15.4383 1.25391 15.5874 1.25391C15.7366 1.25391 15.8796 1.31315 15.9851 1.4186L17.4397 2.87322V2.87435ZM15.471 5.63735L13.221 3.38735L5.55638 11.0531C5.49446 11.115 5.44785 11.1905 5.42025 11.2736L4.51463 13.9893C4.4982 14.0389 4.49587 14.092 4.50789 14.1427C4.51991 14.1935 4.54581 14.2399 4.5827 14.2768C4.61958 14.3137 4.666 14.3396 4.71676 14.3516C4.76751 14.3636 4.82062 14.3613 4.87012 14.3448L7.58588 13.4392C7.66886 13.412 7.74435 13.3657 7.80638 13.3042L15.471 5.63735Z" fill="#B83D13" />
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M1.125 15.8793C1.125 16.3269 1.30279 16.7561 1.61926 17.0726C1.93573 17.3891 2.36495 17.5668 2.8125 17.5668H15.1875C15.6351 17.5668 16.0643 17.3891 16.3807 17.0726C16.6972 16.7561 16.875 16.3269 16.875 15.8793V9.12935C16.875 8.98016 16.8157 8.83709 16.7102 8.7316C16.6048 8.62611 16.4617 8.56685 16.3125 8.56685C16.1633 8.56685 16.0202 8.62611 15.9148 8.7316C15.8093 8.83709 15.75 8.98016 15.75 9.12935V15.8793C15.75 16.0285 15.6907 16.1716 15.5852 16.2771C15.4798 16.3826 15.3367 16.4418 15.1875 16.4418H2.8125C2.66332 16.4418 2.52024 16.3826 2.41475 16.2771C2.30926 16.1716 2.25 16.0285 2.25 15.8793V3.50435C2.25 3.35516 2.30926 3.21209 2.41475 3.1066C2.52024 3.00111 2.66332 2.94185 2.8125 2.94185H10.125C10.2742 2.94185 10.4173 2.88259 10.5227 2.7771C10.6282 2.67161 10.6875 2.52853 10.6875 2.37935C10.6875 2.23016 10.6282 2.08709 10.5227 1.9816C10.4173 1.87611 10.2742 1.81685 10.125 1.81685H2.8125C2.36495 1.81685 1.93573 1.99464 1.61926 2.31111C1.30279 2.62757 1.125 3.0568 1.125 3.50435V15.8793Z" fill="#B83D13" />
                            </svg>
                            <?php _e('Editar'); ?>
                        </a>

                        <a href="#" role="button" class="multistepform__button multistepform__button-next multistepform__button-submit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="19" viewBox="0 0 18 19" fill="none">
                                <path d="M15.5865 4.79416C15.6388 4.84641 15.6804 4.90849 15.7088 4.97682C15.7371 5.04516 15.7517 5.11842 15.7517 5.19241C15.7517 5.2664 15.7371 5.33966 15.7088 5.408C15.6804 5.47634 15.6388 5.53841 15.5865 5.59066L7.71146 13.4657C7.65921 13.518 7.59714 13.5596 7.5288 13.588C7.46046 13.6163 7.3872 13.6309 7.31321 13.6309C7.23922 13.6309 7.16596 13.6163 7.09762 13.588C7.02928 13.5596 6.96721 13.518 6.91496 13.4657L2.97746 9.52816C2.87184 9.42254 2.8125 9.27928 2.8125 9.12991C2.8125 8.98054 2.87184 8.83728 2.97746 8.73166C3.08308 8.62604 3.22634 8.5667 3.37571 8.5667C3.52508 8.5667 3.66834 8.62604 3.77396 8.73166L7.31321 12.272L14.79 4.79416C14.8422 4.74178 14.9043 4.70022 14.9726 4.67186C15.041 4.6435 15.1142 4.62891 15.1882 4.62891C15.2622 4.62891 15.3355 4.6435 15.4038 4.67186C15.4721 4.70022 15.5342 4.74178 15.5865 4.79416V4.79416Z" fill="#F9F3EA" />
                            </svg>
                            <?php _e('Enviar'); ?>
                        </a>
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
