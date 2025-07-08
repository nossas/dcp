<?php

namespace hacklabr\dashboard;

// ID do post
$post_id = $_GET['id'] ?? null;

if (!$post_id) {
    echo "<p>Post não encontrado.</p>";
    return;
}

$post = get_post($post_id);

// Garante que é um post do tipo apoio com o termo correto
if (!$post || $post->post_type !== 'apoio' || !has_term('cacambas', 'tipo_apoio', $post)) {
    echo "<p>Post inválido ou não é do tipo 'caçambas'.</p>";
    return;
}

// Processamento do formulário
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    wp_update_post([
        'ID' => $post_id,
        'post_title'   => sanitize_text_field($_POST['apoio_nome']),
        'post_content' => sanitize_textarea_field($_POST['apoio_descricao']),
    ]);

    update_post_meta($post_id, 'data_e_horario', sanitize_text_field($_POST['apoio_horario_atendimento']));
    update_post_meta($post_id, 'endereco', sanitize_text_field($_POST['apoio_endereco']));

    // Redireciona para evitar reenvio e sair da execução
    wp_safe_redirect(add_query_arg('salvo', 'true', $_SERVER['REQUEST_URI']));
    exit;
}

// Metadados para preencher o formulário
$data_horario = get_post_meta($post_id, 'data_e_horario', true);
$endereco = get_post_meta($post_id, 'endereco', true);

?>

<!-- HTML começa aqui -->
<div class="apoio-card__container">

    <div class="apoio-card__header">
        <div class="apoio-card__breadcrumb">
            <a href="<?= esc_url(home_url('/dashboard/apoio')) ?>" class="apoio-card__breadcrumb-link">
                <?= __('Apoio') ?>
            </a>
            <span class="apoio-card__breadcrumb-separator">›</span>
            <span class="apoio-card__breadcrumb-current"><?= __('Editar caçambas') ?></span>
        </div>
        <h1 class="apoio-card__title"><?= __('Editar caçambas') ?></h1>
    </div>

    <form method="post" class="apoio-card__form">

        <!-- Nome -->
        <div class="apoio-card__field">
            <label class="apoio-card__label"><?= __('Nome') ?></label>
            <div class="apoio-card__field-wrap">
                <input type="text" name="apoio_nome" class="apoio-card__input"
                    value="<?= esc_attr(wp_strip_all_tags($post->post_title)) ?>" />
                <div class="apoio-card__hint">
                    <span class="apoio-card__hint-icon">?</span>
                    <p class="apoio-card__hint-text"><?= __('Nome da empresa ou serviço de caçambas.') ?></p>
                </div>
            </div>
        </div>

        <!-- Subtítulo -->
        <div class="apoio-card__field">
            <label class="apoio-card__label"><?= __('Subtítulo') ?></label>
            <div class="apoio-card__field-wrap">
                <textarea name="apoio_descricao" class="apoio-card__textarea"><?= esc_textarea(wp_strip_all_tags($post->post_content)) ?></textarea>
                <div class="apoio-card__hint">
                    <span class="apoio-card__hint-icon">?</span>
                    <p class="apoio-card__hint-text"><?= __('Descrição complementar, como informações de serviço ou contatos.') ?></p>
                </div>
            </div>
        </div>

        <!-- Horário de atendimento -->
        <div class="apoio-card__field apoio-card__field--split">
            <div class="apoio-card__field-horario">
                <label class="apoio-card__label"><?= __('Horário de atendimento') ?></label>
                <div class="apoio-card__field-wrap">
                    <input type="text" name="apoio_horario_atendimento" class="apoio-card__input"
                        value="<?= esc_attr(wp_strip_all_tags($data_horario)) ?>" />
                    <div class="apoio-card__hint">
                        <span class="apoio-card__hint-icon">?</span>
                        <p class="apoio-card__hint-text"><?= __('Exemplo: Segunda a sábado, das 7h às 18h.') ?></p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Endereço -->
        <div class="apoio-card__field">
            <label class="apoio-card__label"><?= __('Endereço') ?></label>
            <div class="apoio-card__field-wrap">
                <input type="text" name="apoio_endereco" class="apoio-card__input"
                    value="<?= esc_attr(wp_strip_all_tags($endereco)) ?>" />
                <div class="apoio-card__hint">
                    <span class="apoio-card__hint-icon">?</span>
                    <p class="apoio-card__hint-text"><?= __('Endereço completo do serviço de caçambas.') ?></p>
                </div>
            </div>
        </div>

        <!-- Ações -->
        <div class="apoio-card__actions">
            <a href="<?= esc_url(home_url('dashboard/apoio')); ?>" class="apoio__btn-cancelar">
                <?= __('Voltar') ?>
            </a>

            <button id="abrir-modal-apoio" type="button" class="apoio__btn-salvar multistepform__button multistepform__button-submit">
                <?= __('Salvar alterações') ?>
            </button>
        </div>

        <!-- Modal -->
        <div id="modal-confirmar-salvar" class="modal-confirmar-salvar is-hidden" role="dialog" aria-modal="true">
            <div class="modal-confirmar-salvar__overlay"></div>
            <div class="modal-confirmar-salvar__box">
                <button class="modal-confirmar-salvar__close" aria-label="Fechar modal">&times;</button>
                <h2 class="modal-confirmar-salvar__title"><?= __('Salvar alterações?') ?></h2>
                <p class="modal-confirmar-salvar__desc">
                    <?= __('As mudanças feitas serão aplicadas imediatamente. Você ainda poderá editá-las depois, se necessário.') ?>
                </p>
                <div class="modal-confirmar-salvar__actions">
                    <button type="button" class="modal-confirmar-salvar__btn voltar">
                        <?= __('Voltar') ?>
                    </button>
                    <button type="button" class="modal-confirmar-salvar__btn salvar">
                        <?= __('Salvar alterações') ?>
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>
