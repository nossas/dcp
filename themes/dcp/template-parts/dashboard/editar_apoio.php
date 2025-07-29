<?php

namespace hacklabr\dashboard;

$post_id = isset($_GET['id']) ? intval($_GET['id']) : 0;
$post_obj = get_post($post_id);

if (!$post_obj || $post_obj->post_type !== 'apoio') {
    wp_redirect(home_url('/apoio'));
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && current_user_can('edit_post', $post_id)) {

    if (isset($_POST['salvar_apoio'])) {
        wp_update_post([
            'ID'           => $post_id,
            'post_title'   => sanitize_text_field($_POST['apoio_nome'] ?? ''),
            'post_content' => sanitize_textarea_field($_POST['apoio_descricao'] ?? ''),
        ]);

        update_post_meta($post_id, 'horario_de_atendimento', sanitize_text_field($_POST['apoio_horario_atendimento'] ?? ''));
        update_post_meta($post_id, 'endereco', sanitize_text_field($_POST['apoio_endereco'] ?? ''));

        if (!empty($_FILES['apoio_capa']['tmp_name'])) {
            require_once ABSPATH . 'wp-admin/includes/image.php';
            require_once ABSPATH . 'wp-admin/includes/file.php';
            require_once ABSPATH . 'wp-admin/includes/media.php';

            $attachment_id = media_handle_upload('apoio_capa', $post_id);
            if (!is_wp_error($attachment_id)) {
                set_post_thumbnail($post_id, $attachment_id);
            }
        }

        $post_obj = get_post($post_id);
    }

    if (!empty($_POST['arquivar_apoio']) && $_POST['arquivar_apoio'] === '1') {
        update_post_meta($post_id, 'apoio_arquivado', '1');
        wp_redirect(home_url('/dashboard/apoio?tipo=arquivados'));
        exit;
    }
}

?>
<div id="dashboardApoioSingle" class="dashboard-content">
    <div class="dashboard-content-single">
        <?php if (isset($_GET['sucesso'])): ?>
            <div class="notice-success">Alterações salvas com sucesso.</div>
        <?php endif; ?>

        <div class="apoio__container-edit">

            <nav class="breadcrumb-dashboard">
                <a href="#">Apoio</a>
                <span class="breadcrumb-dashboard__separator"> > </span>
                <a href="#">Locais Seguros</a>
                <span class="breadcrumb-dashboard__separator"> > </span>
                <span class="breadcrumb-dashboard__current">Editar Local seguro</span>
            </nav>

            <?php
            $taxonomia = 'tipo_apoio';
            $termos = get_the_terms($post_id, $taxonomia);
            $nome_termo = $termos && !is_wp_error($termos) ? $termos[0]->name : 'Tipo de apoio';
            ?>
            <h2 class="apoio__header--edit-title"><?= esc_html($nome_termo); ?></h2>

            <div class="apoio-card" data-id="<?= esc_attr($post_obj->ID); ?>">
                <form method="post" enctype="multipart/form-data" class="apoio-card__form">
                    <input type="hidden" name="post_id" value="<?= esc_attr($post_obj->ID); ?>" />
                    <input type="hidden" name="salvar_apoio" value="1" />
                    <input type="hidden" name="arquivar_apoio" id="arquivar_apoio" value="0">


                    <div class="apoio-card__field">
                        <span class="input-icon-apoio">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                <path d="M17.4397 2.18343C17.5449 2.28887 17.6039 2.43168 17.6039 2.58056C17.6039 2.72944 17.5449 2.87224 17.4397 2.97768L16.2664 4.15218L14.0164 1.90218L15.1897 0.727681C15.2952 0.622228 15.4383 0.562988 15.5874 0.562988C15.7366 0.562988 15.8796 0.622228 15.9851 0.727681L17.4397 2.18231V2.18343ZM15.471 4.94643L13.221 2.69643L5.55638 10.3622C5.49446 10.4241 5.44785 10.4996 5.42025 10.5827L4.51463 13.2984C4.4982 13.3479 4.49587 13.401 4.50789 13.4518C4.51991 13.5026 4.54581 13.549 4.5827 13.5859C4.61958 13.6227 4.666 13.6486 4.71676 13.6607C4.76751 13.6727 4.82062 13.6704 4.87012 13.6539L7.58588 12.7483C7.66886 12.721 7.74435 12.6748 7.80638 12.6133L15.471 4.94643Z" fill="#605E5D" />
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M1.125 15.1884C1.125 15.636 1.30279 16.0652 1.61926 16.3817C1.93573 16.6981 2.36495 16.8759 2.8125 16.8759H15.1875C15.6351 16.8759 16.0643 16.6981 16.3807 16.3817C16.6972 16.0652 16.875 15.636 16.875 15.1884V8.43843C16.875 8.28925 16.8157 8.14617 16.7102 8.04068C16.6048 7.93519 16.4617 7.87593 16.3125 7.87593C16.1633 7.87593 16.0202 7.93519 15.9148 8.04068C15.8093 8.14617 15.75 8.28925 15.75 8.43843V15.1884C15.75 15.3376 15.6907 15.4807 15.5852 15.5862C15.4798 15.6917 15.3367 15.7509 15.1875 15.7509H2.8125C2.66332 15.7509 2.52024 15.6917 2.41475 15.5862C2.30926 15.4807 2.25 15.3376 2.25 15.1884V2.81343C2.25 2.66425 2.30926 2.52117 2.41475 2.41568C2.52024 2.31019 2.66332 2.25093 2.8125 2.25093H10.125C10.2742 2.25093 10.4173 2.19167 10.5227 2.08618C10.6282 1.98069 10.6875 1.83761 10.6875 1.68843C10.6875 1.53925 10.6282 1.39617 10.5227 1.29068C10.4173 1.18519 10.2742 1.12593 10.125 1.12593H2.8125C2.36495 1.12593 1.93573 1.30372 1.61926 1.62019C1.30279 1.93666 1.125 2.36588 1.125 2.81343V15.1884Z" fill="#605E5D" />
                            </svg>
                        </span>
                        <label for="apoio_nome">Nome</label>
                        <div class="apoio-card__field-wrap">
                            <input type="text" name="apoio_nome" id="apoio_nome" value="<?= esc_attr($post_obj->post_title); ?>" />
                            <div class="apoio-card__hint">
                                <span class="apoio-card__hint-icon">?</span>
                                <p class="apoio-card__hint-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus ullamcorper.</p>
                            </div>
                        </div>
                    </div>

                    <div class="apoio-card__field">
                        <span class="input-icon-apoio">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                <path d="M17.4397 2.18343C17.5449 2.28887 17.6039 2.43168 17.6039 2.58056C17.6039 2.72944 17.5449 2.87224 17.4397 2.97768L16.2664 4.15218L14.0164 1.90218L15.1897 0.727681C15.2952 0.622228 15.4383 0.562988 15.5874 0.562988C15.7366 0.562988 15.8796 0.622228 15.9851 0.727681L17.4397 2.18231V2.18343ZM15.471 4.94643L13.221 2.69643L5.55638 10.3622C5.49446 10.4241 5.44785 10.4996 5.42025 10.5827L4.51463 13.2984C4.4982 13.3479 4.49587 13.401 4.50789 13.4518C4.51991 13.5026 4.54581 13.549 4.5827 13.5859C4.61958 13.6227 4.666 13.6486 4.71676 13.6607C4.76751 13.6727 4.82062 13.6704 4.87012 13.6539L7.58588 12.7483C7.66886 12.721 7.74435 12.6748 7.80638 12.6133L15.471 4.94643Z" fill="#605E5D" />
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M1.125 15.1884C1.125 15.636 1.30279 16.0652 1.61926 16.3817C1.93573 16.6981 2.36495 16.8759 2.8125 16.8759H15.1875C15.6351 16.8759 16.0643 16.6981 16.3807 16.3817C16.6972 16.0652 16.875 15.636 16.875 15.1884V8.43843C16.875 8.28925 16.8157 8.14617 16.7102 8.04068C16.6048 7.93519 16.4617 7.87593 16.3125 7.87593C16.1633 7.87593 16.0202 7.93519 15.9148 8.04068C15.8093 8.14617 15.75 8.28925 15.75 8.43843V15.1884C15.75 15.3376 15.6907 15.4807 15.5852 15.5862C15.4798 15.6917 15.3367 15.7509 15.1875 15.7509H2.8125C2.66332 15.7509 2.52024 15.6917 2.41475 15.5862C2.30926 15.4807 2.25 15.3376 2.25 15.1884V2.81343C2.25 2.66425 2.30926 2.52117 2.41475 2.41568C2.52024 2.31019 2.66332 2.25093 2.8125 2.25093H10.125C10.2742 2.25093 10.4173 2.19167 10.5227 2.08618C10.6282 1.98069 10.6875 1.83761 10.6875 1.68843C10.6875 1.53925 10.6282 1.39617 10.5227 1.29068C10.4173 1.18519 10.2742 1.12593 10.125 1.12593H2.8125C2.36495 1.12593 1.93573 1.30372 1.61926 1.62019C1.30279 1.93666 1.125 2.36588 1.125 2.81343V15.1884Z" fill="#605E5D" />
                            </svg>
                        </span>
                        <label for="apoio_descricao">Descrição</label>
                        <div class="apoio-card__field-wrap">
                            <textarea name="apoio_descricao" id="apoio_descricao"><?= esc_textarea($post_obj->post_content); ?></textarea>
                            <div class="apoio-card__hint">
                                <span class="apoio-card__hint-icon">?</span>
                                <p class="apoio-card__hint-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit. Vestibulum erat in commodo.</p>
                            </div>
                        </div>
                    </div>

                    <div class="apoio-card__field">
                        <span class="input-icon-apoio">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                <path d="M17.4397 2.18343C17.5449 2.28887 17.6039 2.43168 17.6039 2.58056C17.6039 2.72944 17.5449 2.87224 17.4397 2.97768L16.2664 4.15218L14.0164 1.90218L15.1897 0.727681C15.2952 0.622228 15.4383 0.562988 15.5874 0.562988C15.7366 0.562988 15.8796 0.622228 15.9851 0.727681L17.4397 2.18231V2.18343ZM15.471 4.94643L13.221 2.69643L5.55638 10.3622C5.49446 10.4241 5.44785 10.4996 5.42025 10.5827L4.51463 13.2984C4.4982 13.3479 4.49587 13.401 4.50789 13.4518C4.51991 13.5026 4.54581 13.549 4.5827 13.5859C4.61958 13.6227 4.666 13.6486 4.71676 13.6607C4.76751 13.6727 4.82062 13.6704 4.87012 13.6539L7.58588 12.7483C7.66886 12.721 7.74435 12.6748 7.80638 12.6133L15.471 4.94643Z" fill="#605E5D" />
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M1.125 15.1884C1.125 15.636 1.30279 16.0652 1.61926 16.3817C1.93573 16.6981 2.36495 16.8759 2.8125 16.8759H15.1875C15.6351 16.8759 16.0643 16.6981 16.3807 16.3817C16.6972 16.0652 16.875 15.636 16.875 15.1884V8.43843C16.875 8.28925 16.8157 8.14617 16.7102 8.04068C16.6048 7.93519 16.4617 7.87593 16.3125 7.87593C16.1633 7.87593 16.0202 7.93519 15.9148 8.04068C15.8093 8.14617 15.75 8.28925 15.75 8.43843V15.1884C15.75 15.3376 15.6907 15.4807 15.5852 15.5862C15.4798 15.6917 15.3367 15.7509 15.1875 15.7509H2.8125C2.66332 15.7509 2.52024 15.6917 2.41475 15.5862C2.30926 15.4807 2.25 15.3376 2.25 15.1884V2.81343C2.25 2.66425 2.30926 2.52117 2.41475 2.41568C2.52024 2.31019 2.66332 2.25093 2.8125 2.25093H10.125C10.2742 2.25093 10.4173 2.19167 10.5227 2.08618C10.6282 1.98069 10.6875 1.83761 10.6875 1.68843C10.6875 1.53925 10.6282 1.39617 10.5227 1.29068C10.4173 1.18519 10.2742 1.12593 10.125 1.12593H2.8125C2.36495 1.12593 1.93573 1.30372 1.61926 1.62019C1.30279 1.93666 1.125 2.36588 1.125 2.81343V15.1884Z" fill="#605E5D" />
                            </svg>
                        </span>
                        <label for="apoio_horario_atendimento">Horário de atendimento</label>
                        <div class="apoio-card__field-wrap">
                            <input type="text" name="apoio_horario_atendimento" id="apoio_horario_atendimento" value="<?= esc_attr(get_post_meta($post_id, 'horario_de_atendimento', true)); ?>" />
                            <div class="apoio-card__hint">
                                <span class="apoio-card__hint-icon">?</span>
                                <p class="apoio-card__hint-text">Exemplo: Segunda a sexta, das 8h às 17h</p>
                            </div>
                        </div>
                    </div>

                    <div class="apoio-card__field">
                        <span class="input-icon-apoio">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                <path d="M17.4397 2.18343C17.5449 2.28887 17.6039 2.43168 17.6039 2.58056C17.6039 2.72944 17.5449 2.87224 17.4397 2.97768L16.2664 4.15218L14.0164 1.90218L15.1897 0.727681C15.2952 0.622228 15.4383 0.562988 15.5874 0.562988C15.7366 0.562988 15.8796 0.622228 15.9851 0.727681L17.4397 2.18231V2.18343ZM15.471 4.94643L13.221 2.69643L5.55638 10.3622C5.49446 10.4241 5.44785 10.4996 5.42025 10.5827L4.51463 13.2984C4.4982 13.3479 4.49587 13.401 4.50789 13.4518C4.51991 13.5026 4.54581 13.549 4.5827 13.5859C4.61958 13.6227 4.666 13.6486 4.71676 13.6607C4.76751 13.6727 4.82062 13.6704 4.87012 13.6539L7.58588 12.7483C7.66886 12.721 7.74435 12.6748 7.80638 12.6133L15.471 4.94643Z" fill="#605E5D" />
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M1.125 15.1884C1.125 15.636 1.30279 16.0652 1.61926 16.3817C1.93573 16.6981 2.36495 16.8759 2.8125 16.8759H15.1875C15.6351 16.8759 16.0643 16.6981 16.3807 16.3817C16.6972 16.0652 16.875 15.636 16.875 15.1884V8.43843C16.875 8.28925 16.8157 8.14617 16.7102 8.04068C16.6048 7.93519 16.4617 7.87593 16.3125 7.87593C16.1633 7.87593 16.0202 7.93519 15.9148 8.04068C15.8093 8.14617 15.75 8.28925 15.75 8.43843V15.1884C15.75 15.3376 15.6907 15.4807 15.5852 15.5862C15.4798 15.6917 15.3367 15.7509 15.1875 15.7509H2.8125C2.66332 15.7509 2.52024 15.6917 2.41475 15.5862C2.30926 15.4807 2.25 15.3376 2.25 15.1884V2.81343C2.25 2.66425 2.30926 2.52117 2.41475 2.41568C2.52024 2.31019 2.66332 2.25093 2.8125 2.25093H10.125C10.2742 2.25093 10.4173 2.19167 10.5227 2.08618C10.6282 1.98069 10.6875 1.83761 10.6875 1.68843C10.6875 1.53925 10.6282 1.39617 10.5227 1.29068C10.4173 1.18519 10.2742 1.12593 10.125 1.12593H2.8125C2.36495 1.12593 1.93573 1.30372 1.61926 1.62019C1.30279 1.93666 1.125 2.36588 1.125 2.81343V15.1884Z" fill="#605E5D" />
                            </svg>
                        </span>
                        <label for="apoio_endereco">Endereço</label>
                        <div class="apoio-card__field-wrap">
                            <input type="text" name="apoio_endereco" id="apoio_endereco" value="<?= esc_attr(get_post_meta($post_id, 'endereco', true)); ?>" />
                            <div class="apoio-card__hint">
                                <span class="apoio-card__hint-icon">?</span>
                                <p class="apoio-card__hint-text">Lorem ipsum dolor sit amet, consectetur adipiscing elit.</p>
                            </div>
                        </div>
                    </div>

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
                        <button type="submit" name="arquivar_apoio" class="apoio__btn-arquivar">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="19" viewBox="0 0 18 19" fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M15.5868 3.14377C15.6391 3.19602 15.6807 3.25809 15.7091 3.32643C15.7374 3.39477 15.752 3.46803 15.752 3.54202C15.752 3.61601 15.7374 3.68927 15.7091 3.75761C15.6807 3.82595 15.6391 3.88802 15.5868 3.94027L3.21176 16.3153C3.10613 16.4209 2.96288 16.4802 2.81351 16.4802C2.66413 16.4802 2.52088 16.4209 2.41526 16.3153C2.30963 16.2096 2.2503 16.0664 2.2503 15.917C2.2503 15.7676 2.30963 15.6244 2.41526 15.5188L14.7903 3.14377C14.8425 3.09139 14.9046 3.04983 14.9729 3.02147C15.0413 2.99311 15.1145 2.97852 15.1885 2.97852C15.2625 2.97852 15.3358 2.99311 15.4041 3.02147C15.4724 3.04983 15.5345 3.09139 15.5868 3.14377Z" fill="#B83D13" />
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M2.41526 3.14377C2.36287 3.19602 2.32131 3.25809 2.29295 3.32643C2.2646 3.39477 2.25 3.46803 2.25 3.54202C2.25 3.61601 2.2646 3.68927 2.29295 3.75761C2.32131 3.82595 2.36287 3.88802 2.41526 3.94027L14.7903 16.3153C14.8959 16.4209 15.0391 16.4802 15.1885 16.4802C15.3379 16.4802 15.4811 16.4209 15.5868 16.3153C15.6924 16.2096 15.7517 16.0664 15.7517 15.917C15.7517 15.7676 15.6924 15.6244 15.5868 15.5188L3.21176 3.14377C3.1595 3.09139 3.09743 3.04983 3.02909 3.02147C2.96076 2.99311 2.88749 2.97852 2.81351 2.97852C2.73952 2.97852 2.66626 2.99311 2.59792 3.02147C2.52958 3.04983 2.46751 3.09139 2.41526 3.14377Z" fill="#B83D13" />
                            </svg>
                            <?= __('Arquivar') ?>
                        </button>
                        <button id="abrir-modal-apoio" type="button" class="apoio__btn-salvar multistepform__button multistepform__button-submit">
                            <svg xmlns="http://www.w3.org/2000/svg" width="19" height="19" viewBox="0 0 19 19" fill="none">
                                <path d="M16.0865 4.83127C16.1388 4.88352 16.1804 4.94559 16.2088 5.01393C16.2371 5.08227 16.2517 5.15553 16.2517 5.22952C16.2517 5.30351 16.2371 5.37677 16.2088 5.44511C16.1804 5.51345 16.1388 5.57552 16.0865 5.62777L8.21146 13.5028C8.15921 13.5552 8.09714 13.5967 8.0288 13.6251C7.96046 13.6534 7.8872 13.668 7.81321 13.668C7.73922 13.668 7.66596 13.6534 7.59762 13.6251C7.52928 13.5967 7.46721 13.5552 7.41496 13.5028L3.47746 9.56527C3.37184 9.45965 3.3125 9.31639 3.3125 9.16702C3.3125 9.01765 3.37184 8.87439 3.47746 8.76877C3.58308 8.66315 3.72634 8.60381 3.87571 8.60381C4.02508 8.60381 4.16834 8.66315 4.27396 8.76877L7.81321 12.3091L15.29 4.83127C15.3422 4.77889 15.4043 4.73733 15.4726 4.70897C15.541 4.68061 15.6142 4.66602 15.6882 4.66602C15.7622 4.66602 15.8355 4.68061 15.9038 4.70897C15.9721 4.73733 16.0342 4.77889 16.0865 4.83127V4.83127Z" fill="#F9F3EA" />
                            </svg>
                            <?= __('Salvar Alterações') ?>
                        </button>
                    </div>
                </form>
            </div>

            <div id="modal-confirmar-salvar" class="modal-confirmar-salvar is-hidden" role="dialog" aria-modal="true">
                <div class="modal-confirmar-salvar__overlay"></div>
                <div class="modal-confirmar-salvar__box">
                    <button class="modal-confirmar-salvar__close" aria-label="Fechar modal">&times;</button>
                    <h2 class="modal-confirmar-salvar__title">Salvar alterações?</h2>
                    <p class="modal-confirmar-salvar__desc">
                        <?= __('As mudanças feitas serão aplicadas imediatamente. Você ainda poderá editá-las depois, se necessário.') ?>
                    </p>
                    <div class="modal-confirmar-salvar__actions">
                        <button type="button" class="modal-confirmar-salvar__btn voltar">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                <path fill-rule="evenodd" clip-rule="evenodd" d="M12.7743 1.85276C12.8266 1.90501 12.8682 1.96708 12.8966 2.03542C12.9249 2.10376 12.9395 2.17702 12.9395 2.25101C12.9395 2.32499 12.9249 2.39826 12.8966 2.46659C12.8682 2.53493 12.8266 2.597 12.7743 2.64926L6.42138 9.001L12.7743 15.3528C12.8799 15.4584 12.9392 15.6016 12.9392 15.751C12.9392 15.9004 12.8799 16.0436 12.7743 16.1493C12.6686 16.2549 12.5254 16.3142 12.376 16.3142C12.2266 16.3142 12.0834 16.2549 11.9778 16.1493L5.22776 9.39925C5.17537 9.347 5.13381 9.28493 5.10545 9.21659C5.0771 9.14825 5.0625 9.07499 5.0625 9.001C5.0625 8.92702 5.0771 8.85376 5.10545 8.78542C5.13381 8.71708 5.17537 8.65501 5.22776 8.60275L11.9778 1.85276C12.03 1.80037 12.0921 1.75881 12.1604 1.73045C12.2288 1.7021 12.302 1.6875 12.376 1.6875C12.45 1.6875 12.5233 1.7021 12.5916 1.73045C12.6599 1.75881 12.722 1.80037 12.7743 1.85276V1.85276Z" fill="#281414" />
                            </svg>
                            <?= __('Voltar') ?>
                        </button>
                        <button type="button" class="modal-confirmar-salvar__btn salvar">
                            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                                <path d="M15.5865 4.10276C15.6388 4.15501 15.6804 4.21708 15.7088 4.28542C15.7371 4.35376 15.7517 4.42702 15.7517 4.50101C15.7517 4.57499 15.7371 4.64826 15.7088 4.71659C15.6804 4.78493 15.6388 4.847 15.5865 4.89926L7.71146 12.7743C7.65921 12.8266 7.59714 12.8682 7.5288 12.8966C7.46046 12.9249 7.3872 12.9395 7.31321 12.9395C7.23922 12.9395 7.16596 12.9249 7.09762 12.8966C7.02928 12.8682 6.96721 12.8266 6.91496 12.7743L2.97746 8.83676C2.87184 8.73113 2.8125 8.58788 2.8125 8.43851C2.8125 8.28913 2.87184 8.14588 2.97746 8.04026C3.08308 7.93463 3.22634 7.87529 3.37571 7.87529C3.52508 7.87529 3.66834 7.93463 3.77396 8.04026L7.31321 11.5806L14.79 4.10276C14.8422 4.05037 14.9043 4.00881 14.9726 3.98045C15.041 3.9521 15.1142 3.9375 15.1882 3.9375C15.2622 3.9375 15.3355 3.9521 15.4038 3.98045C15.4721 4.00881 15.5342 4.05037 15.5865 4.10276V4.10276Z" fill="#F9F3EA" />
                            </svg>
                            <?= __('Salvar alterações') ?>
                        </button>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
