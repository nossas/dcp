<?php

namespace hacklabr\dashboard;

$post_id = $_GET['id'] ?? null;

if (!$post_id) {
    echo "<p>Post não encontrado.</p>";
    return;
}

$post = get_post($post_id);

if (!$post || $post->post_type !== 'apoio' || !has_term('quem-acionar', 'tipo_apoio', $post)) {
    echo "<p>Post inválido ou não é do tipo 'quem acionar'.</p>";
    return;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    wp_update_post([
        'ID' => $post_id,
        'post_title' => sanitize_text_field($_POST['apoio_nome']),
        'post_content' => sanitize_textarea_field($_POST['apoio_descricao']),
    ]);

    update_post_meta($post_id, 'data_e_horario', sanitize_text_field($_POST['apoio_horario_atendimento']));
    update_post_meta($post_id, 'endereco', sanitize_text_field($_POST['apoio_endereco']));
    update_post_meta($post_id, 'site', sanitize_text_field($_POST['apoio_site']));
    update_post_meta($post_id, 'observacoes', sanitize_textarea_field($_POST['apoio_observacoes']));

    wp_safe_redirect(add_query_arg('salvo', 'true', $_SERVER['REQUEST_URI']));
    exit;
}

$data_horario = get_post_meta($post_id, 'data_e_horario', true);
$endereco = get_post_meta($post_id, 'endereco', true);
$site = get_post_meta($post_id, 'site', true);
$observacoes = get_post_meta($post_id, 'observacoes', true);
?>

<div class="apoio-card__container">
    <div class="apoio-card__header">
        <h1 class="apoio-card__title"><?= __('Quem acionar') ?></h1>
    </div>

    <form class="apoio-card__form" method="post">
        <div class="apoio-card__field">
            <label class="apoio-card__label"><?= __('Nome') ?></label>
            <div class="apoio-card__field-wrap">
                <input type="text" name="apoio_nome" class="apoio-card__input"
                    value="<?= esc_attr(wp_strip_all_tags($post->post_title)) ?>" />
                <div class="apoio-card__hint">
                    <span class="apoio-card__hint-icon">?</span>
                    <p class="apoio-card__hint-text"><?= __('Nome do local ou entidade que pode ser acionada em situação de risco.') ?></p>
                </div>
            </div>
        </div>

        <div class="apoio-card__field">
            <label class="apoio-card__label"><?= __('Subtítulo') ?></label>
            <div class="apoio-card__field-wrap">
                <textarea name="apoio_descricao" class="apoio-card__textarea"><?= esc_textarea(wp_strip_all_tags($post->post_content)) ?></textarea>
                <div class="apoio-card__hint">
                    <span class="apoio-card__hint-icon">?</span>
                    <p class="apoio-card__hint-text"><?= __('Descrição curta com detalhes complementares ou orientações específicas.') ?></p>
                </div>
            </div>
        </div>

        <div class="apoio-card__field apoio-card__field--split">
            <div class="apoio-card__field-horario">
                <label class="apoio-card__label"><?= __('Horário de funcionamento') ?></label>
                <div class="apoio-card__field-wrap">
                    <input type="text" name="apoio_horario_atendimento" class="apoio-card__input"
                        value="<?= esc_attr(wp_strip_all_tags($data_horario)) ?>" />
                    <div class="apoio-card__hint">
                        <span class="apoio-card__hint-icon">?</span>
                        <p class="apoio-card__hint-text"><?= __('Exemplo: Segunda a sexta, das 8h às 17h. Inclua dias e horários de atendimento.') ?></p>
                    </div>
                </div>
            </div>
        </div>

        <div class="apoio-card__field">
            <label class="apoio-card__label"><?= __('Endereço') ?></label>
            <div class="apoio-card__field-wrap">
                <input type="text" name="apoio_endereco" class="apoio-card__input"
                    value="<?= esc_attr(wp_strip_all_tags($endereco)) ?>" />
                <div class="apoio-card__hint">
                    <span class="apoio-card__hint-icon">?</span>
                    <p class="apoio-card__hint-text"><?= __('Digite o endereço completo ou ponto de referência do local de apoio.') ?></p>
                </div>
            </div>
        </div>

        <div class="apoio-card__field">
            <label class="apoio-card__label"><?= __('Website') ?></label>
            <div class="apoio-card__field-wrap">
                <input type="text" name="apoio_site" class="apoio-card__input"
                    value="<?= esc_attr(wp_strip_all_tags($site)) ?>" />
                <div class="apoio-card__hint">
                    <span class="apoio-card__hint-icon">?</span>
                    <p class="apoio-card__hint-text"><?= __('Link para o site institucional ou rede social com mais informações.') ?></p>
                </div>
            </div>
        </div>

        <div class="apoio-card__field">
            <label class="apoio-card__label"><?= __('Informações adicionais') ?></label>
            <div class="apoio-card__field-wrap">
                <textarea name="apoio_observacoes" class="apoio-card__textarea"><?= esc_textarea(wp_strip_all_tags($observacoes)) ?></textarea>
                <div class="apoio-card__hint">
                    <span class="apoio-card__hint-icon">?</span>
                    <p class="apoio-card__hint-text"><?= __('Use este campo para observações gerais, como recomendações ou avisos.') ?></p>
                </div>
            </div>
        </div>

        <div class="apoio-card__actions">
            <a href="<?= esc_url(get_dashboard_url('dashboard')) ?>" class="apoio-card__btn apoio__btn-cancelar">
                <?= __('Voltar') ?>
            </a>

            <button type="button" class="apoio-card__btn apoio-card__btn--danger apoio__btn-arquivar"
                id="btn-excluir" data-id="<?= esc_attr($post_id) ?>">
                <?= __('Excluir') ?>
            </button>

            <button id="abrir-modal-apoio" type="button" class="apoio-card__btn apoio__btn-salvar multistepform__button apoio-card__btn--save">
                <?= __('Salvar alterações') ?>
            </button>
        </div>
    </form>

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
</div>
