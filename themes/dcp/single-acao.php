<?php get_header(); ?>

<main id="primary" class="site-main container container--wide">
    <?php if (have_posts()) : while (have_posts()) : the_post();
            $pods = pods('acao', get_the_ID());
    ?>
            <section class="acao-grid">
                <?php if (function_exists('bcn_display')): ?>
                    <nav class="bread-acao-mob" aria-label="Breadcrumb">
                        <?php bcn_display(); ?>
                    </nav>
                <?php endif; ?>

                <div class="acao-thumb">
                    <?php if (has_post_thumbnail()) : ?>
                        <div class="acao-thumb-wrapper">
                            <?php the_post_thumbnail('large'); ?>
                        </div>
                    <?php else: ?>
                        <div class="acao-thumb-wrapper no-thumb" style="background-color: #eee; width: 100%; height: 300px;">
                            <!-- Espaço reservado sem imagem -->
                        </div>
                    <?php endif; ?>
                </div>

                <div class="acao-conteudo">
                    <?php if (function_exists('bcn_display')): ?>
                        <nav class="bread-acao" aria-label="Breadcrumb">
                            <?php bcn_display(); ?>
                        </nav>
                    <?php endif; ?>
                    <h1 class="acao-titulo"><?= esc_html($pods->field('titulo')); ?></h1>

                    <div class="acao-categorias">
                        <?php
                        $terms = get_the_terms(get_the_ID(), 'tipo_acao');
                        if ($terms && !is_wp_error($terms)) {
                            foreach ($terms as $term) {
                                $slug = sanitize_title($term->slug);
                                echo '<span class="acao-categoria acao-categoria--' . esc_attr($slug) . '">' . esc_html($term->name) . '</span>';
                            }
                        }
                        ?>
                    </div>

                    <div class="acao-descricao">
                        <?php echo nl2br($pods->field('descricao')); ?>
                    </div>

                    <ul class="acao-info">
                        <li>
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/wrapper.svg" alt="Ícone de endereço" style="width: 1em; vertical-align: middle; margin-right: 0.5em;">
                            Endereço: <?php echo esc_html($pods->field('endereco')); ?>
                        </li>
                        <li>
                            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/pin.svg" alt="Ícone de horário" style="width: 1em; vertical-align: middle; margin-right: 0.5em;">
                            Dia: <?= date('d/m/Y, H:i', strtotime($pods->field('data_e_horario'))); ?>
                        </li>
                    </ul>

                    <section class="formulario-participar">
                    <h2>Participe:</h2>
                    <p class="form-descricao">
                        Aenean egestas ultricies nibh, at tempus purus fringilla in. Curabitur ornare enim justo, at tristique.
                    </p>

                    <form
                        id="formParticiparAcao"
                        class="form-participar"
                        action="javascript:void(0);"
                        method="post"
                        data-action="<?php bloginfo('url'); ?>/wp-admin/admin-ajax.php"
                        novalidate
                    >
                        <p>Nome <span class="required">*</span></p>
                        <input type="text" name="nome_completo" placeholder="Digite seu nome" required />
                        <div class="msg-erro">Preencha este campo para continuar</div>

                        <p>Telefone (WhatsApp) <span class="required">*</span></p>
                        <input type="text" class="phone" name="telefone" placeholder="(xx) xxxxx-xxxx" required />
                        <div class="erro-telefone" style="display:none;">Telefone inválido</div>

                        <p>E-mail (opcional)</p>
                        <input type="email" name="email" placeholder="exemplo@email.com" />

                        <label class="checkbox-aceite">
                        <input type="checkbox" name="aceite_termos" required />
                        <?php _e('Aceito receber confirmações e informações sobre esta ação via e-mail e WhatsApp.', 'dcp'); ?><span class="required">*</span>
                        </label>

                        <label class="checkbox-aceite checkbox-aceite-whatsapp">
                        <input type="checkbox" name="aceite_whatsapp" value="ACEITE" required />
                        <?php _e('Quero receber o link para entrar no grupo da Defesa Climática Popular no WhatsApp.', 'dcp'); ?><span class="required">*</span>
                        </label>

                        <input type="hidden" name="post_id" value="<?= get_the_ID(); ?>" />
                        <input type="hidden" name="action" value="form_participar_acao" />

                        <div style="display: flex; justify-content: space-between">
                        <div></div>
                        <button type="submit" class="botao-confirmar">
                            <iconify-icon icon="bi:check2"></iconify-icon>
                            <span>Confirmar participação</span>
                        </button>
                        </div>

                        <div id="cf7-snackbar-telefone" class="snackbar modal" style="display:none;">
                        <span class="modal-message">
                            Telefone inválido, use o formato com DDD, por exemplo: (21) 99999-8888
                        </span>
                        <button type="button" class="modal-close">&times;</button>
                        </div>

                        <div id="cf7-snackbar-error" class="snackbar modal" style="display:none;">
                        <span class="modal-message">
                            Você precisa preencher os campos obrigatórios para confirmar sua participação.
                        </span>
                        <button type="button" class="modal-close">&times;</button>
                        </div>

                        <div id="cf7-snackbar-success" class="snackbar modal" style="display:none;">
                        <span class="modal-message">Participação confirmada com sucesso!</span>
                        <button type="button" class="modal-close">&times;</button>
                        </div>
                    </form>
                    </section>

                </div>
            </section>

        <?php endwhile;
    else : ?>
        <p>Nenhuma ação encontrada.</p>
    <?php endif; ?>

    <hr class="is-separator">
    <?php get_template_part('template-parts/content/related-posts-acao'); ?>
    <p style="padding: 100px 0;">
        Ficou com alguma dúvida? Fale com a gente para saber mais sobre o projeto ou como participar.
        <strong style="text-decoration: underline;">Entre em contato</strong>
    </p>
</main>

<?php get_footer(); ?>
