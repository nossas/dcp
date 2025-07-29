<?php

namespace hacklabr\dashboard;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'])) {
    $post_id = intval($_POST['post_id']);
    $pod_to_save = \pods('recomendacao', $post_id);

    $recomendacoes = ['recomendacao_1', 'recomendacao_2', 'recomendacao_3'];
    $icones = ['icone_1', 'icone_2', 'icone_3'];

    foreach ($recomendacoes as $index => $reco_field) {
        $icone_field = $icones[$index];
        $novo_texto = sanitize_text_field($_POST[$reco_field] ?? '');
        $novo_icone_id = intval($_POST[$icone_field] ?? 0);

        $pod_to_save->save($reco_field, $novo_texto);
        $pod_to_save->save($icone_field, $novo_icone_id);
    }

    if (!headers_sent() && isset($_POST['redirect_to'])) {
        //wp_safe_redirect(esc_url_raw($_POST['redirect_to']));
        //wp_safe_redirect($_POST['redirect_to']);
        //exit;
    }
}

$post_slug = $_GET['situacao'] ?? '';
$post_obj = get_page_by_path(sanitize_title($post_slug), OBJECT, 'recomendacao');
if (!$post_obj) {
    return;
}

$pod = \pods('recomendacao', $post_obj->ID);

$recomendacoes = ['recomendacao_1', 'recomendacao_2', 'recomendacao_3'];
$icones = ['icone_1', 'icone_2', 'icone_3'];

$opcoes_icones = get_posts([
    'post_type' => 'attachment',
    'post_mime_type' => 'image/svg+xml',
    'posts_per_page' => -1,
]);

function render_svg_preview($id)
{
    $path = get_attached_file($id);
    return file_exists($path) ? file_get_contents($path) : '';
}
?>

<div class="situacao-atual__container editar-recomendacao">

    <nav class="breadcrumb-dashboard">
        <a href="#">Situação atual</a>
        <span class="breadcrumb-dashboard__separator"> > </span>
        <span class="breadcrumb-dashboard__current">Editar recomendações</span>
    </nav>

    <h2 class="editar-recomendacao__card-title-big">
        <?= esc_html($post_obj->post_title) ?>
    </h2>

    <form method="post" action="">
        <h2 class="editar-recomendacao__card-title-small">
            <?= esc_html($post_obj->post_title) ?>
        </h2>

        <input type="hidden" name="post_id" value="<?= esc_attr($post_obj->ID) ?>">
        <input type="hidden" name="redirect_to" value="<?=get_dashboard_url('situacao_atual')?>">

        <?php for ($i = 0; $i < 3; $i++):
            $reco_field = $recomendacoes[$i];
            $icone_field = $icones[$i];
            $texto = $pod->display($reco_field);
            $icone_atual_id = $pod->field("{$icone_field}.ID");
        ?>

            <div class="situacao-atual__card-text">
                <div class="situacao-atual__card-text-icon">
                    <label class="situacao-atual__label" for="recomendacao"><?= __('Ícones') ?></label>
                    <div class="situacao-atual__icon" id="preview-icon-<?= $i ?>">
                        <?= $icone_atual_id ? render_svg_preview($icone_atual_id) : ''; ?>
                    </div>

                    <select name="<?= $icone_field ?>" class="situacao-atual__icon-select" data-preview-id="preview-icon-<?= $i ?>">
                        <option value=""><?= __('Escolha um ícone') ?></option>
                        <?php foreach ($opcoes_icones as $icon): ?>
                            <option value="<?= $icon->ID ?>" <?= selected($icone_atual_id, $icon->ID) ?>>
                                <?= esc_html($icon->post_title) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="situacao-atual__textarea-wrapper">
                    <label class="situacao-atual__label" for="recomendacao"><?= __('Recomendações') ?></label>
                    <textarea name="<?= $reco_field ?>" rows="2" class="situacao-atual__textarea"><?= esc_textarea($texto) ?></textarea>

                    <span class="input-icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                            <path d="M17.4397 2.18343C17.5449 2.28887 17.6039 2.43168 17.6039 2.58056C17.6039 2.72944 17.5449 2.87224 17.4397 2.97768L16.2664 4.15218L14.0164 1.90218L15.1897 0.727681C15.2952 0.622228 15.4383 0.562988 15.5874 0.562988C15.7366 0.562988 15.8796 0.622228 15.9851 0.727681L17.4397 2.18231V2.18343ZM15.471 4.94643L13.221 2.69643L5.55638 10.3622C5.49446 10.4241 5.44785 10.4996 5.42025 10.5827L4.51463 13.2984C4.4982 13.3479 4.49587 13.401 4.50789 13.4518C4.51991 13.5026 4.54581 13.549 4.5827 13.5859C4.61958 13.6227 4.666 13.6486 4.71676 13.6607C4.76751 13.6727 4.82062 13.6704 4.87012 13.6539L7.58588 12.7483C7.66886 12.721 7.74435 12.6748 7.80638 12.6133L15.471 4.94643Z" fill="#605E5D" />
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M1.125 15.1884C1.125 15.636 1.30279 16.0652 1.61926 16.3817C1.93573 16.6981 2.36495 16.8759 2.8125 16.8759H15.1875C15.6351 16.8759 16.0643 16.6981 16.3807 16.3817C16.6972 16.0652 16.875 15.636 16.875 15.1884V8.43843C16.875 8.28925 16.8157 8.14617 16.7102 8.04068C16.6048 7.93519 16.4617 7.87593 16.3125 7.87593C16.1633 7.87593 16.0202 7.93519 15.9148 8.04068C15.8093 8.14617 15.75 8.28925 15.75 8.43843V15.1884C15.75 15.3376 15.6907 15.4807 15.5852 15.5862C15.4798 15.6917 15.3367 15.7509 15.1875 15.7509H2.8125C2.66332 15.7509 2.52024 15.6917 2.41475 15.5862C2.30926 15.4807 2.25 15.3376 2.25 15.1884V2.81343C2.25 2.66425 2.30926 2.52117 2.41475 2.41568C2.52024 2.31019 2.66332 2.25093 2.8125 2.25093H10.125C10.2742 2.25093 10.4173 2.19167 10.5227 2.08618C10.6282 1.98069 10.6875 1.83761 10.6875 1.68843C10.6875 1.53925 10.6282 1.39617 10.5227 1.29068C10.4173 1.18519 10.2742 1.12593 10.125 1.12593H2.8125C2.36495 1.12593 1.93573 1.30372 1.61926 1.62019C1.30279 1.93666 1.125 2.36588 1.125 2.81343V15.1884Z" fill="#605E5D" />
                        </svg>
                    </span>
                </div>

            </div>

        <?php endfor; ?>

        <div class="situacao-atual__buttons">
            <a href="<?= get_dashboard_url('situacao_atual') ?>" class="multistepform__button back">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12.7743 1.85276C12.8266 1.90501 12.8682 1.96708 12.8966 2.03542C12.9249 2.10376 12.9395 2.17702 12.9395 2.25101C12.9395 2.32499 12.9249 2.39826 12.8966 2.46659C12.8682 2.53493 12.8266 2.597 12.7743 2.64926L6.42138 9.001L12.7743 15.3528C12.8799 15.4584 12.9392 15.6016 12.9392 15.751C12.9392 15.9004 12.8799 16.0436 12.7743 16.1493C12.6686 16.2549 12.5254 16.3142 12.376 16.3142C12.2266 16.3142 12.0834 16.2549 11.9778 16.1493L5.22776 9.39925C5.17537 9.347 5.13381 9.28493 5.10545 9.21659C5.0771 9.14825 5.0625 9.07499 5.0625 9.001C5.0625 8.92702 5.0771 8.85376 5.10545 8.78542C5.13381 8.71708 5.17537 8.65501 5.22776 8.60275L11.9778 1.85276C12.03 1.80037 12.0921 1.75881 12.1604 1.73045C12.2288 1.7021 12.302 1.6875 12.376 1.6875C12.45 1.6875 12.5233 1.7021 12.5916 1.73045C12.6599 1.75881 12.722 1.80037 12.7743 1.85276V1.85276Z" fill="#281414" />
                </svg>
                <?= __('Voltar') ?>
            </a>
            <button type="button" id="abrir-modal" class="multistepform__button multistepform__button-submit">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                    <path d="M15.5865 4.10276C15.6388 4.15501 15.6804 4.21708 15.7088 4.28542C15.7371 4.35376 15.7517 4.42702 15.7517 4.50101C15.7517 4.57499 15.7371 4.64826 15.7088 4.71659C15.6804 4.78493 15.6388 4.847 15.5865 4.89926L7.71146 12.7743C7.65921 12.8266 7.59714 12.8682 7.5288 12.8966C7.46046 12.9249 7.3872 12.9395 7.31321 12.9395C7.23922 12.9395 7.16596 12.9249 7.09762 12.8966C7.02928 12.8682 6.96721 12.8266 6.91496 12.7743L2.97746 8.83676C2.87184 8.73113 2.8125 8.58788 2.8125 8.43851C2.8125 8.28913 2.87184 8.14588 2.97746 8.04026C3.08308 7.93463 3.22634 7.87529 3.37571 7.87529C3.52508 7.87529 3.66834 7.93463 3.77396 8.04026L7.31321 11.5806L14.79 4.10276C14.8422 4.05037 14.9043 4.00881 14.9726 3.98045C15.041 3.9521 15.1142 3.9375 15.1882 3.9375C15.2622 3.9375 15.3355 3.9521 15.4038 3.98045C15.4721 4.00881 15.5342 4.05037 15.5865 4.10276V4.10276Z" fill="#F9F3EA" />
                </svg>
                <?= __('Salvar alterações') ?>
            </button>
        </div>
    </form>

    <div id="modal-confirmacao" class="modal-confirmacao">
        <div class="modal-content">
            <button class="modal-close" id="fechar-modal" aria-label="Fechar">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                    <path d="M20.251 3C20.3495 3 20.4471 3.01896 20.5381 3.05664C20.6292 3.09444 20.7126 3.14992 20.7822 3.21973C20.8521 3.2894 20.9075 3.37275 20.9453 3.46387C20.983 3.55488 21.0019 3.65246 21.002 3.75098C21.002 3.84947 20.983 3.94709 20.9453 4.03809C20.9075 4.1292 20.8521 4.21256 20.7822 4.28223L13.0635 12.001L20.7822 19.7197C20.923 19.8605 21.0019 20.0518 21.002 20.251C21.002 20.4501 20.9231 20.6414 20.7822 20.7822C20.6414 20.9231 20.4501 21.002 20.251 21.002C20.0518 21.0019 19.8605 20.923 19.7197 20.7822L12.001 13.0635L4.28223 20.7822C4.1414 20.9231 3.95014 21.002 3.75098 21.002C3.55184 21.0019 3.36054 20.923 3.21973 20.7822C3.07895 20.6414 3 20.4501 3 20.251C3.00002 20.0518 3.07892 19.8605 3.21973 19.7197L10.9385 12.001L3.21973 4.28223C3.14992 4.21257 3.09444 4.12917 3.05664 4.03809C3.01896 3.94711 3 3.84945 3 3.75098C3.00001 3.65248 3.01894 3.55486 3.05664 3.46387C3.09445 3.37275 3.14988 3.28939 3.21973 3.21973C3.28939 3.14988 3.37275 3.09445 3.46387 3.05664C3.55486 3.01894 3.65248 3.00001 3.75098 3C3.84945 3 3.94711 3.01896 4.03809 3.05664C4.12917 3.09444 4.21257 3.14992 4.28223 3.21973L12.001 10.9385L19.7197 3.21973C19.7894 3.14988 19.8727 3.09445 19.9639 3.05664C20.0549 3.01894 20.1525 3.00001 20.251 3Z" fill="#281414" />
                </svg>
            </button>
            <h2 class="modal-title"><?= __('Publicar alterações de recomendação') ?></h2>
            <p><?= __('Tem certeza que deseja publicar as alterações feitas nesta recomendação?') ?></p>
            <div class="situacao-atual__buttons">
                <button type="button" id="voltar-modal" class="multistepform__button back">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M12.7743 1.85276C12.8266 1.90501 12.8682 1.96708 12.8966 2.03542C12.9249 2.10376 12.9395 2.17702 12.9395 2.25101C12.9395 2.32499 12.9249 2.39826 12.8966 2.46659C12.8682 2.53493 12.8266 2.597 12.7743 2.64926L6.42138 9.001L12.7743 15.3528C12.8799 15.4584 12.9392 15.6016 12.9392 15.751C12.9392 15.9004 12.8799 16.0436 12.7743 16.1493C12.6686 16.2549 12.5254 16.3142 12.376 16.3142C12.2266 16.3142 12.0834 16.2549 11.9778 16.1493L5.22776 9.39925C5.17537 9.347 5.13381 9.28493 5.10545 9.21659C5.0771 9.14825 5.0625 9.07499 5.0625 9.001C5.0625 8.92702 5.0771 8.85376 5.10545 8.78542C5.13381 8.71708 5.17537 8.65501 5.22776 8.60275L11.9778 1.85276C12.03 1.80037 12.0921 1.75881 12.1604 1.73045C12.2288 1.7021 12.302 1.6875 12.376 1.6875C12.45 1.6875 12.5233 1.7021 12.5916 1.73045C12.6599 1.75881 12.722 1.80037 12.7743 1.85276V1.85276Z" fill="#281414" />
                    </svg>
                    <?= __('Voltar') ?>
                </button>
                <button type="button" id="confirmar-salvar" class="multistepform__button multistepform__button-submit">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                        <path d="M15.5865 4.10276C15.6388 4.15501 15.6804 4.21708 15.7088 4.28542C15.7371 4.35376 15.7517 4.42702 15.7517 4.50101C15.7517 4.57499 15.7371 4.64826 15.7088 4.71659C15.6804 4.78493 15.6388 4.847 15.5865 4.89926L7.71146 12.7743C7.65921 12.8266 7.59714 12.8682 7.5288 12.8966C7.46046 12.9249 7.3872 12.9395 7.31321 12.9395C7.23922 12.9395 7.16596 12.9249 7.09762 12.8966C7.02928 12.8682 6.96721 12.8266 6.91496 12.7743L2.97746 8.83676C2.87184 8.73113 2.8125 8.58788 2.8125 8.43851C2.8125 8.28913 2.87184 8.14588 2.97746 8.04026C3.08308 7.93463 3.22634 7.87529 3.37571 7.87529C3.52508 7.87529 3.66834 7.93463 3.77396 8.04026L7.31321 11.5806L14.79 4.10276C14.8422 4.05037 14.9043 4.00881 14.9726 3.98045C15.041 3.9521 15.1142 3.9375 15.1882 3.9375C15.2622 3.9375 15.3355 3.9521 15.4038 3.98045C15.4721 4.00881 15.5342 4.05037 15.5865 4.10276V4.10276Z" fill="#F9F3EA" />
                    </svg>
                    <?= __('Salvar alteração') ?>
                </button>
            </div>
        </div>
    </div>
</div>
