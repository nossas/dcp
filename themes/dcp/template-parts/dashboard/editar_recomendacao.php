<?php

namespace hacklabr\dashboard;

$post_slug = $_GET['situacao'] ?? '';
$post_obj = get_page_by_path($post_slug, OBJECT, 'recomendacao');
if (!$post_obj) {
    echo '<p>Recomendação não encontrada.</p>';
    return;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['post_id'])) {
    $post_id = intval($_POST['post_id']);
    $pod_to_save = \pods('recomendacao', $post_id);

    $recomendacoes = [
        'recomendacao_1',
        'recomendacao_2',
        'recomendacao_3',
    ];

    $icones = [
        'icone_1',
        'icone_2',
        'icone_3',
    ];

    foreach ($recomendacoes as $index => $reco_field) {
        $icone_field = $icones[$index];
        $novo_texto = sanitize_text_field($_POST[$reco_field] ?? '');
        $novo_icone_id = intval($_POST[$icone_field] ?? 0);

        $pod_to_save->save($reco_field, $novo_texto);
        $pod_to_save->save($icone_field, $novo_icone_id);
    }

    $pod = \pods('recomendacao', $post_id);
}


$pod = \pods('recomendacao', $post_obj->ID);

$recomendacoes = [
    'recomendacao_1',
    'recomendacao_2',
    'recomendacao_3',
];

$icones = [
    'icone_1',
    'icone_2',
    'icone_3',
];

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
        <span class="breadcrumb__separator"> > </span>
        <span class="breadcrumb__current">Editar recomendações</span>
    </nav>

    <h2 class="editar-recomendacao__card-title-big">
        <?= esc_html($post_obj->post_title) ?>
    </h2>

    <form method="post" action="">
        <h2 class="editar-recomendacao__card-title-small">
            <?= esc_html($post_obj->post_title) ?>
        </h2>

        <input type="hidden" name="post_id" value="<?= esc_attr($post_obj->ID) ?>">

        <?php for ($i = 0; $i < 3; $i++):
            $reco_field = $recomendacoes[$i];
            $icone_field = $icones[$i];
            $texto = $pod->display($reco_field);
            $icone_atual_id = $pod->field("{$icone_field}.ID");
        ?>

            <div class="situacao-atual__card-text">
                <div class="situacao-atual__icon" id="preview-icon-<?= $i ?>">
                    <?= $icone_atual_id ? render_svg_preview($icone_atual_id) : ''; ?>
                </div>

                <select name="<?= $icone_field ?>" class="situacao-atual__icon-select"
                    data-preview-id="preview-icon-<?= $i ?>">
                    <option value=""><?= __('Escolha um ícone') ?></option>
                    <?php foreach ($opcoes_icones as $icon): ?>
                        <option value="<?= $icon->ID ?>" <?= selected($icone_atual_id, $icon->ID) ?>>
                            <?= esc_html($icon->post_title) ?>
                        </option>
                    <?php endforeach; ?>
                </select>

                <div class="situacao-atual__textarea-wrapper">
                    <textarea name="<?= $reco_field ?>" rows="2" class="situacao-atual__textarea"><?= esc_textarea($texto) ?></textarea>
                    <span class="textarea__icon">
                        <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                        <path d="M17.4397 2.18294C17.5449 2.28838 17.6039 2.43119 17.6039 2.58007C17.6039 2.72895 17.5449 2.87175 17.4397 2.97719L16.2664 4.15169L14.0164 1.90169L15.1897 0.727192C15.2952 0.62174 15.4383 0.5625 15.5874 0.5625C15.7366 0.5625 15.8796 0.62174 15.9851 0.727192L17.4397 2.18182V2.18294ZM15.471 4.94594L13.221 2.69594L5.55638 10.3617C5.49446 10.4236 5.44785 10.4991 5.42025 10.5822L4.51463 13.2979C4.4982 13.3475 4.49587 13.4006 4.50789 13.4513C4.51991 13.5021 4.54581 13.5485 4.5827 13.5854C4.61958 13.6223 4.666 13.6482 4.71676 13.6602C4.76751 13.6722 4.82062 13.6699 4.87012 13.6534L7.58588 12.7478C7.66886 12.7205 7.74435 12.6743 7.80638 12.6128L15.471 4.94594Z" fill="#605E5D"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M1.125 15.1879C1.125 15.6355 1.30279 16.0647 1.61926 16.3812C1.93573 16.6977 2.36495 16.8754 2.8125 16.8754H15.1875C15.6351 16.8754 16.0643 16.6977 16.3807 16.3812C16.6972 16.0647 16.875 15.6355 16.875 15.1879V8.43794C16.875 8.28876 16.8157 8.14568 16.7102 8.0402C16.6048 7.93471 16.4617 7.87544 16.3125 7.87544C16.1633 7.87544 16.0202 7.93471 15.9148 8.0402C15.8093 8.14568 15.75 8.28876 15.75 8.43794V15.1879C15.75 15.3371 15.6907 15.4802 15.5852 15.5857C15.4798 15.6912 15.3367 15.7504 15.1875 15.7504H2.8125C2.66332 15.7504 2.52024 15.6912 2.41475 15.5857C2.30926 15.4802 2.25 15.3371 2.25 15.1879V2.81294C2.25 2.66376 2.30926 2.52068 2.41475 2.41519C2.52024 2.30971 2.66332 2.25044 2.8125 2.25044H10.125C10.2742 2.25044 10.4173 2.19118 10.5227 2.08569C10.6282 1.9802 10.6875 1.83713 10.6875 1.68794C10.6875 1.53876 10.6282 1.39568 10.5227 1.29019C10.4173 1.18471 10.2742 1.12544 10.125 1.12544H2.8125C2.36495 1.12544 1.93573 1.30323 1.61926 1.6197C1.30279 1.93617 1.125 2.36539 1.125 2.81294V15.1879Z" fill="#605E5D"/>
                        </svg>
                    </span>
                </div>
            </div>

        <?php endfor; ?>

        <div class="situacao-atual__buttons">
            <a href="<?= get_dashboard_url('situacao_atual') ?>" class="multistepform__button back">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M12.7743 1.85276C12.8266 1.90501 12.8682 1.96708 12.8966 2.03542C12.9249 2.10376 12.9395 2.17702 12.9395 2.25101C12.9395 2.32499 12.9249 2.39826 12.8966 2.46659C12.8682 2.53493 12.8266 2.597 12.7743 2.64926L6.42138 9.001L12.7743 15.3528C12.8799 15.4584 12.9392 15.6016 12.9392 15.751C12.9392 15.9004 12.8799 16.0436 12.7743 16.1493C12.6686 16.2549 12.5254 16.3142 12.376 16.3142C12.2266 16.3142 12.0834 16.2549 11.9778 16.1493L5.22776 9.39925C5.17537 9.347 5.13381 9.28493 5.10545 9.21659C5.0771 9.14825 5.0625 9.07499 5.0625 9.001C5.0625 8.92702 5.0771 8.85376 5.10545 8.78542C5.13381 8.71708 5.17537 8.65501 5.22776 8.60275L11.9778 1.85276C12.03 1.80037 12.0921 1.75881 12.1604 1.73045C12.2288 1.7021 12.302 1.6875 12.376 1.6875C12.45 1.6875 12.5233 1.7021 12.5916 1.73045C12.6599 1.75881 12.722 1.80037 12.7743 1.85276V1.85276Z" fill="#281414"/>
                </svg>
                <?= __('Voltar') ?>
            </a>
            <button type="submit" class="multistepform__button multistepform__button-submit">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                    <path d="M15.5865 4.10276C15.6388 4.15501 15.6804 4.21708 15.7088 4.28542C15.7371 4.35376 15.7517 4.42702 15.7517 4.50101C15.7517 4.57499 15.7371 4.64826 15.7088 4.71659C15.6804 4.78493 15.6388 4.847 15.5865 4.89926L7.71146 12.7743C7.65921 12.8266 7.59714 12.8682 7.5288 12.8966C7.46046 12.9249 7.3872 12.9395 7.31321 12.9395C7.23922 12.9395 7.16596 12.9249 7.09762 12.8966C7.02928 12.8682 6.96721 12.8266 6.91496 12.7743L2.97746 8.83676C2.87184 8.73113 2.8125 8.58788 2.8125 8.43851C2.8125 8.28913 2.87184 8.14588 2.97746 8.04026C3.08308 7.93463 3.22634 7.87529 3.37571 7.87529C3.52508 7.87529 3.66834 7.93463 3.77396 8.04026L7.31321 11.5806L14.79 4.10276C14.8422 4.05037 14.9043 4.00881 14.9726 3.98045C15.041 3.9521 15.1142 3.9375 15.1882 3.9375C15.2622 3.9375 15.3355 3.9521 15.4038 3.98045C15.4721 4.00881 15.5342 4.05037 15.5865 4.10276V4.10276Z" fill="#F9F3EA"/>
                </svg>
                <?= __('Salvar alterações') ?>
            </button>
        </div>
    </form>
</div>
