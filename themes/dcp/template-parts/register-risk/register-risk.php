<section id="form-register-risk" class="risk-form">
    <div class="form-layout">

        <!-- Imagem lateral -->
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
                        <h2 class="multistepform__title"><?= __('Onde isso está acontecendo?') ?></h2>
                        <span class="multistepform__excerpt"><?= __('Você pode digitar o endereço ou clicar no mapa para marcar o local.') ?></span>
                    </div>
                    <input type="text" name="cep" placeholder="CEP" required>
                    <div class="multistepform__buttons">
                        <button type="button" class="multistepform__button next">Próxima</button>
                    </div>
                </div>

                <!-- Etapa 2 -->
                <div class="step" data-step="2">
                    <h2>Etapa 2 – Tipo de Risco</h2>
                    <label><input type="checkbox" name="tipo_risco[]" value="Alagamento"> Alagamento</label>
                    <label><input type="checkbox" name="tipo_risco[]" value="Deslizamento"> Deslizamento</label>
                    <label><input type="checkbox" name="tipo_risco[]" value="Incêndio"> Incêndio</label>
                    <label><input type="checkbox" name="tipo_risco[]" value="Outros"> Outros</label>
                    <textarea name="descricao_risco" placeholder="Descreva o risco..." required></textarea>
                    <div class="buttons">
                        <button type="button" class="prev">Voltar</button>
                        <button type="button" class="next">Próxima</button>
                    </div>
                </div>

                <!-- Etapa 3 -->
                <div class="step" data-step="3">
                    <h2>Etapa 3 – Mídia</h2>
                    <input type="file" name="arquivo">
                    <label><input type="checkbox" name="nao_tem_midia" value="1"> Não tenho mídia agora</label>
                    <div class="buttons">
                        <button type="button" class="prev">Voltar</button>
                        <button type="button" class="next">Próxima</button>
                    </div>
                </div>

                <!-- Etapa 4 -->
                <div class="step" data-step="4">
                    <h2>Etapa 4 – Seus dados</h2>
                    <input type="text" name="nome" placeholder="Seu nome" required>
                    <input type="tel" name="telefone" placeholder="Telefone" required>
                    <input type="email" name="email" placeholder="E-mail" required>
                    <label><input type="radio" name="whatsapp" value="sim" required> Sim, autorizo contato via WhatsApp</label>
                    <label><input type="radio" name="whatsapp" value="nao"> Não autorizo</label>
                    <div class="buttons">
                        <button type="button" class="prev">Voltar</button>
                        <button type="button" class="next">Próxima</button>
                    </div>
                </div>

                <!-- Etapa 5 -->
                <div class="step" data-step="5">
                    <h2>Etapa 5 – Confirmação</h2>
                    <p>Revise suas informações e clique em enviar para finalizar.</p>
                    <div class="buttons">
                        <button type="button" class="prev">Editar</button>
                        <button type="submit">Enviar</button>
                    </div>
                </div>

                <!-- Etapa 6 – Sucesso -->
                <div class="step" data-step="6">
                    <h2>Risco enviado com sucesso!</h2>
                    <p>Deseja adicionar outro?</p>
                    <div class="buttons">
                        <a href="/register-risk" class="button">Registrar outro</a>
                        <a href="/mapa" class="button">Voltar ao mapa</a>
                    </div>
                </div>
            </form>
            </form>
        </div>
    </div>
</section>
