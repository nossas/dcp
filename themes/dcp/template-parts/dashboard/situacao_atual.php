<?php

namespace hacklabr\dashboard;

if (!function_exists(__NAMESPACE__ . '\render_svg')) {
    function render_svg($id) {
        $url = wp_get_attachment_url($id);
        $path = get_attached_file($id);

        if ($url && pathinfo($url, PATHINFO_EXTENSION) === 'svg' && file_exists($path)) {
            return file_get_contents($path);
        }

        return ''; // Ou: return '<img src="'.esc_url($url).'" alt="ícone">';
    }
}

$args = [
    'post_type' => 'recomendacao',
    'posts_per_page' => -1,
    'orderby'        => 'date',
    'order'          => 'ASC',
];
$recomendacoes_post = get_posts($args);
?>

<div class="situacao-atual__container">
    <div class="situacao-atual__header">
        <h1 class="situacao-atual__title"><?= __('Situação atual') ?></h1>
        <div class="situacao-atual__btn">
            <div class="situacao-atual__icon--wpp">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                    <path d="M15.3011 2.61876C14.4759 1.78589 13.4932 1.1256 12.4102 0.676339C11.3272 0.227083 10.1657 -0.00214668 8.99325 0.00200862C4.08037 0.00200862 0.0765 4.00476 0.072 8.91876C0.072 10.4926 0.48375 12.0238 1.26113 13.3794L0 18.002L4.7295 16.7623C6.03788 17.4743 7.50367 17.8475 8.99325 17.8479H8.99775C13.9118 17.8479 17.9145 13.8451 17.919 8.92663C17.9201 7.75443 17.6893 6.59359 17.2398 5.511C16.7903 4.4284 16.1311 3.44544 15.3 2.61876H15.3011Z" fill="#F9F3EA"/>
                </svg>
            </div>
            <a class="situacao-atual__btn-wpp" href=""><?= __('Notificar moradores') ?></a>
        </div>
    </div>

    <div class="situacao-atual">
        <div class="situacao-atual__content-title">
            <h2 class="situacao-atual__content-title-text"><?= __('Todas recomendações') ?></h2>
        </div>

        <div class="situacao-atual__grid">
            <?php foreach ($recomendacoes_post as $post): ?>
                <?php
                $pod = \pods('recomendacao', $post->ID);

                $recomendacao_1 = $pod ? $pod->display('recomendacao_1') : '';
                $recomendacao_2 = $pod ? $pod->display('recomendacao_2') : '';
                $recomendacao_3 = $pod ? $pod->display('recomendacao_3') : '';

                $icone_1_id = $pod ? $pod->field('icone_1.ID') : null;
                $icone_2_id = $pod ? $pod->field('icone_2.ID') : null;
                $icone_3_id = $pod ? $pod->field('icone_3.ID') : null;

                $slug = sanitize_title($post->post_title);
                ?>
                <div class="situacao-atual__card">
                    <div class="situacao-atual__card-header">
                        <h4 class="situacao-atual__card-title"><?= esc_html($post->post_title) ?></h4>
                        <a class="situacao-atual__edit-btn" href="<?= get_dashboard_url('editar_recomendacao', ['situacao' => $slug]) ?>">
                            <?= __('Editar') ?>
                        </a>
                    </div>
                    <div class="situacao-atual__card-content">
                        <?php if (!empty($recomendacao_1)): ?>
                            <div class="situacao-atual__card-text">
                                <div class="situacao-atual__icon">
                                    <?= $icone_1_id ? render_svg($icone_1_id) : ''; ?>
                                </div>
                                <p><?= esc_html($recomendacao_1) ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($recomendacao_2)): ?>
                            <div class="situacao-atual__card-text">
                                <div class="situacao-atual__icon">
                                    <?= $icone_2_id ? render_svg($icone_2_id) : ''; ?>
                                </div>
                                <p><?= esc_html($recomendacao_2) ?></p>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($recomendacao_3)): ?>
                            <div class="situacao-atual__card-text">
                                <div class="situacao-atual__icon">
                                    <?= $icone_3_id ? render_svg($icone_3_id) : ''; ?>
                                </div>
                                <p><?= esc_html($recomendacao_3) ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>
