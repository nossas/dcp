<section id="form-register-risk" class="risk-form">
    <div class="form-layout">

        <div class="form-image">
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/wrapper71.svg" alt="Pessoa com megafone">
        </div>

        <!-- Conteúdo do formulário -->
        <div class="form-steps">

            <!-- Barra de progresso -->
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

            <!-- Formulário -->
            <form id="multiStepForm" class="multistepform" method="post" enctype="multipart/form-data">
                <div class="multistepform__1 step active" data-step="1">
                    <div class="multistepform__header">
                        <h2 class="multistepform__title"><?php _e('Onde isso está acontecendo?'); ?></h2>
                        <span class="multistepform__excerpt"><?php _e('Você pode digitar o endereço ou clicar no mapa para marcar o local.'); ?></span>
                    </div>
                    <div class="multistepform__fields">
                        <span class="multistepform__label"><?php _e('Localização'); ?></span>
                        <input type="text" name="cep" placeholder="<?php _e('Digite o local ou endereço aqui'); ?>" required>
                        <span class="or"><?php _e('ou'); ?></span>
                        <a href="#" role="button" class="multistepform__button-map multistepform__button map-button">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true">
                                <path d="M4.66425 0.165227...Z" fill="#281414" />
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

                <!-- Etapa 2 -->
                <div class="step" data-step="2">
                    <h2><?php _e('Etapa 2 – Tipo de Risco'); ?></h2>
                    <label><input type="checkbox" name="tipo_risco[]" value="Alagamento"> <?php _e('Alagamento'); ?></label>
                    <label><input type="checkbox" name="tipo_risco[]" value="Deslizamento"> <?php _e('Deslizamento'); ?></label>
                    <label><input type="checkbox" name="tipo_risco[]" value="Incêndio"> <?php _e('Incêndio'); ?></label>
                    <label><input type="checkbox" name="tipo_risco[]" value="Outros"> <?php _e('Outros'); ?></label>
                    <textarea name="descricao_risco" placeholder="<?php _e('Descreva o risco...'); ?>" required></textarea>
                    <div class="multistepform__buttons">
                        <a href="#" role="button" class="multistepform__button multistepform__button-next"><?php _e('Continuar'); ?></a>
                    </div>
                </div>

                <!-- Etapa 3 -->
                <div class="step" data-step="3">
                    <h2><?php _e('Etapa 3 – Mídia'); ?></h2>
                    <input type="file" name="arquivo">
                    <label><input type="checkbox" name="nao_tem_midia" value="1"> <?php _e('Não tenho mídia agora'); ?></label>
                    <div class="multistepform__buttons">
                        <a href="#" role="button" class="multistepform__button multistepform__button-next"><?php _e('Continuar'); ?></a>
                    </div>
                </div>

                <!-- Etapa 4 -->
                <div class="step" data-step="4">
                    <h2><?php _e('Etapa 4 – Seus dados'); ?></h2>
                    <input type="text" name="nome" placeholder="<?php _e('Seu nome'); ?>" required>
                    <input type="tel" name="telefone" placeholder="<?php _e('Telefone'); ?>" required>
                    <input type="email" name="email" placeholder="<?php _e('E-mail'); ?>" required>
                    <label><input type="radio" name="whatsapp" value="sim" required> <?php _e('Sim, autorizo contato via WhatsApp'); ?></label>
                    <label><input type="radio" name="whatsapp" value="nao"> <?php _e('Não autorizo'); ?></label>
                    <div class="multistepform__buttons">
                        <a href="#" role="button" class="multistepform__button multistepform__button-next"><?php _e('Continuar'); ?></a>
                    </div>
                </div>

                <!-- Etapa 5 -->
                <div class="step" data-step="5">
                    <h2><?php _e('Etapa 5 – Confirmação'); ?></h2>
                    <p><?php _e('Revise suas informações e clique em enviar para finalizar.'); ?></p>
                    <div class="multistepform__buttons">
                        <a href="#" role="button" class="multistepform__button prev"><?php _e('Editar'); ?></a>
                        <button type="submit" class="multistepform__button submit-button"><?php _e('Enviar'); ?></button>
                    </div>
                </div>

                <!-- Etapa 6 – Sucesso -->
                <div class="step" data-step="6">
                    <h2><?php _e('Risco enviado com sucesso!'); ?></h2>
                    <p><?php _e('Deseja adicionar outro?'); ?></p>
                    <div class="multistepform__buttons">
                        <a href="/register-risk" class="multistepform__button"><?php _e('Registrar outro'); ?></a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</section>
