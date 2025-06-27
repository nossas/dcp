<?php

namespace hacklabr\dashboard;

$post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$post_obj = get_post($post_id);

if (!$post_obj || $post_obj->post_type !== 'apoio') {
    wp_redirect(home_url('/apoio'));
    exit;
}

setup_postdata($post_obj);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['salvar_apoio'])) {
    if (current_user_can('edit_post', $post_id)) {
        wp_update_post([
            'ID' => $post_id,
            'post_title' => sanitize_text_field($_POST['apoio_nome']),
            'post_content' => sanitize_textarea_field($_POST['apoio_descricao']),
        ]);

        update_post_meta($post_id, 'dias_funcionamento', sanitize_text_field($_POST['apoio_dias']));
        update_post_meta($post_id, 'horario_de_atendimento', sanitize_text_field($_POST['apoio_horario']));
        update_post_meta($post_id, 'endereco', sanitize_text_field($_POST['apoio_endereco']));

        if (!empty($_FILES['apoio_capa']['tmp_name'])) {
            require_once(ABSPATH . 'wp-admin/includes/image.php');
            require_once(ABSPATH . 'wp-admin/includes/file.php');
            require_once(ABSPATH . 'wp-admin/includes/media.php');

            $attachment_id = media_handle_upload('apoio_capa', $post_id);
            if (!is_wp_error($attachment_id)) {
                set_post_thumbnail($post_id, $attachment_id);
            }
        }

        if (!headers_sent()) {
            ob_clean();
            wp_redirect(add_query_arg(['id' => $post_id, 'sucesso' => '1'], get_permalink()));
            exit;
        }
    }
}

?>

<?php if (isset($_GET['sucesso'])): ?>
    <div class="notice-success">Alterações salvas com sucesso.</div>
<?php endif; ?>

<div class="apoio__container-edit">
    <div class="apoio__header--edit">
        <h2 class="apoio__header--edit-title">
            <?php
            $taxonomia = 'tipo_apoio';
            $termos = get_the_terms(get_the_ID(), $taxonomia);
            $nome_termo = $termos && !is_wp_error($termos) ? $termos[0]->name : 'Tipo de apoio';
            ?>
            <h2 class="apoio__header--edit-title"><?= esc_html($nome_termo); ?></h2>
        </h2>
    </div>

    <div class="apoio-card" data-id="<?= esc_attr($post_obj->ID); ?>">
        <form method="post" enctype="multipart/form-data" class="apoio-card__form">
            <input type="hidden" name="post_id" value="<?= esc_attr($post_obj->ID); ?>" />

            <!-- Nome -->
            <div class="apoio-card__field">
                <label for="apoio_nome">Nome</label>
                <div class="apoio-card__field-wrap">
                    <input type="text" name="apoio_nome" id="apoio_nome" value="<?= esc_attr($post_obj->post_title); ?>" />
                    <div class="apoio-card__hint">
                        <span class="apoio-card__hint-icon">?</span>
                        <p class="apoio-card__hint-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus ullamcorper.</p>
                    </div>
                </div>
            </div>

            <!-- Descrição -->
            <div class="apoio-card__field">
                <label for="apoio_descricao">Descrição</label>
                <div class="apoio-card__field-wrap">
                    <textarea name="apoio_descricao" id="apoio_descricao"><?= esc_textarea($post_obj->post_content); ?></textarea>
                    <div class="apoio-card__hint">
                        <span class="apoio-card__hint-icon">?</span>
                        <p class="apoio-card__hint-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum erat in commodo.</p>
                    </div>
                </div>
            </div>

            <!-- Dias -->
            <div class="apoio-card__field">
                <label for="apoio_dias">Dias de funcionamento</label>
                <div class="apoio-card__field-wrap">
                    <input type="text" name="apoio_dias" id="apoio_dias" value="<?= esc_attr(get_post_meta($post_id, 'dias_funcionamento', true)); ?>" />
                    <div class="apoio-card__hint">
                        <span class="apoio-card__hint-icon">?</span>
                        <p class="apoio-card__hint-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    </div>
                </div>
            </div>

            <!-- Horário -->
            <div class="apoio-card__field">
                <label for="apoio_horario">Horário de funcionamento</label>
                <div class="apoio-card__field-wrap">
                    <input type="time" name="apoio_horario" id="apoio_horario" value="<?= esc_attr(get_post_meta($post_id, 'horario_de_atendimento', true)); ?>" />
                    <div class="apoio-card__hint">
                        <span class="apoio-card__hint-icon">?</span>
                        <p class="apoio-card__hint-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    </div>
                </div>
            </div>

            <!-- Endereço -->
            <div class="apoio-card__field">
                <label for="apoio_endereco">Endereço</label>
                <div class="apoio-card__field-wrap">
                    <input type="text" name="apoio_endereco" id="apoio_endereco" value="<?= esc_attr(get_post_meta($post_id, 'endereco', true)); ?>" />
                    <div class="apoio-card__hint">
                        <span class="apoio-card__hint-icon">?</span>
                        <p class="apoio-card__hint-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                    </div>
                </div>
            </div>

            <!-- Foto -->
            <div class="apoio-card__field">
                <label for="apoio_capa">Foto de capa</label>
                <div class="apoio-card__field-wrap">
                    <div class="apoio-card__field-wrap-img">
                        <?php
                        $imagem_id = get_post_thumbnail_id($post_id);
                        $imagem_url = wp_get_attachment_image_url($imagem_id, 'medium');
                        ?>
                        <?php if ($imagem_url): ?>
                            <img src="<?= esc_url($imagem_url); ?>" alt="Imagem atual" style="max-width: 100%; border-radius: 8px;" />
                        <?php endif; ?>
                        <input type="file" name="apoio_capa" id="apoio_capa" accept="image/*" />
                    </div>
                    <div class="apoio-card__hint">
                        <span class="apoio-card__hint-icon">?</span>
                        <p class="apoio-card__hint-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum erat in commodo.</p>
                    </div>
                </div>
            </div>

            <div class="apoio-card__actions">
                <a href="<?= esc_url(home_url('dashboard/apoio')); ?>" class="apoio__btn-cancelar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="19" viewBox="0 0 18 19" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M12.7743 2.58127C12.8266 2.63352 12.8682 2.6956 12.8966 2.76393C12.9249 2.83227 12.9395 2.90553 12.9395 2.97952C12.9395 3.05351 12.9249 3.12677 12.8966 3.19511C12.8682 3.26345 12.8266 3.32552 12.7743 3.37777L6.42138 9.72952L12.7743 16.0813C12.8799 16.1869 12.9392 16.3301 12.9392 16.4795C12.9392 16.6289 12.8799 16.7721 12.7743 16.8778C12.6686 16.9834 12.5254 17.0427 12.376 17.0427C12.2266 17.0427 12.0834 16.9834 11.9778 16.8778L5.22776 10.1278C5.17537 10.0755 5.13381 10.0134 5.10545 9.94511C5.0771 9.87677 5.0625 9.80351 5.0625 9.72952C5.0625 9.65553 5.0771 9.58227 5.10545 9.51393C5.13381 9.44559 5.17537 9.38352 5.22776 9.33127L11.9778 2.58127C12.03 2.52889 12.0921 2.48733 12.1604 2.45897C12.2288 2.43061 12.302 2.41602 12.376 2.41602C12.45 2.41602 12.5233 2.43061 12.5916 2.45897C12.6599 2.48733 12.722 2.52889 12.7743 2.58127V2.58127Z" fill="#281414" />
                    </svg>
                    <?= __('Voltar') ?>
                </a>
                <a href="#" class="apoio__btn-arquivar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="19" viewBox="0 0 18 19" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M15.5868 3.14377C15.6391 3.19602 15.6807 3.25809 15.7091 3.32643C15.7374 3.39477 15.752 3.46803 15.752 3.54202C15.752 3.61601 15.7374 3.68927 15.7091 3.75761C15.6807 3.82595 15.6391 3.88802 15.5868 3.94027L3.21176 16.3153C3.10613 16.4209 2.96288 16.4802 2.81351 16.4802C2.66413 16.4802 2.52088 16.4209 2.41526 16.3153C2.30963 16.2096 2.2503 16.0664 2.2503 15.917C2.2503 15.7676 2.30963 15.6244 2.41526 15.5188L14.7903 3.14377C14.8425 3.09139 14.9046 3.04983 14.9729 3.02147C15.0413 2.99311 15.1145 2.97852 15.1885 2.97852C15.2625 2.97852 15.3358 2.99311 15.4041 3.02147C15.4724 3.04983 15.5345 3.09139 15.5868 3.14377Z" fill="#B83D13" />
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M2.41526 3.14377C2.36287 3.19602 2.32131 3.25809 2.29295 3.32643C2.2646 3.39477 2.25 3.46803 2.25 3.54202C2.25 3.61601 2.2646 3.68927 2.29295 3.75761C2.32131 3.82595 2.36287 3.88802 2.41526 3.94027L14.7903 16.3153C14.8959 16.4209 15.0391 16.4802 15.1885 16.4802C15.3379 16.4802 15.4811 16.4209 15.5868 16.3153C15.6924 16.2096 15.7517 16.0664 15.7517 15.917C15.7517 15.7676 15.6924 15.6244 15.5868 15.5188L3.21176 3.14377C3.1595 3.09139 3.09743 3.04983 3.02909 3.02147C2.96076 2.99311 2.88749 2.97852 2.81351 2.97852C2.73952 2.97852 2.66626 2.99311 2.59792 3.02147C2.52958 3.04983 2.46751 3.09139 2.41526 3.14377Z" fill="#B83D13" />
                    </svg>
                    <?= __('Arquivar') ?>
                </a>
                <button type="submit" name="salvar_apoio" class="apoio__btn-salvar multistepform__button multistepform__button-submit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 19 19" fill="none">
                        <path d="M16.0865 4.83127C16.1388 4.88352 16.1804 4.94559 16.2088 5.01393C16.2371 5.08227 16.2517 5.15553 16.2517 5.22952C16.2517 5.30351 16.2371 5.37677 16.2088 5.44511C16.1804 5.51345 16.1388 5.57552 16.0865 5.62777L8.21146 13.5028C8.15921 13.5552 8.09714 13.5967 8.0288 13.6251C7.96046 13.6534 7.8872 13.668 7.81321 13.668C7.73922 13.668 7.66596 13.6534 7.59762 13.6251C7.52928 13.5967 7.46721 13.5552 7.41496 13.5028L3.47746 9.56527C3.37184 9.45965 3.3125 9.31639 3.3125 9.16702C3.3125 9.01765 3.37184 8.87439 3.47746 8.76877C3.58308 8.66315 3.72634 8.60381 3.87571 8.60381C4.02508 8.60381 4.16834 8.66315 4.27396 8.76877L7.81321 12.3091L15.29 4.83127C15.3422 4.77889 15.4043 4.73733 15.4726 4.70897C15.541 4.68061 15.6142 4.66602 15.6882 4.66602C15.7622 4.66602 15.8355 4.68061 15.9038 4.70897C15.9721 4.73733 16.0342 4.77889 16.0865 4.83127V4.83127Z" fill="#F9F3EA" />
                    </svg>
                    <?= __('Salvar Alterações') ?>
                </button>
            </div>
        </form>
    </div>
</div>