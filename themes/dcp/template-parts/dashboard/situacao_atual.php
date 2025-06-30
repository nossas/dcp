<?php

namespace hacklabr\dashboard;

if (!function_exists(__NAMESPACE__ . '\render_svg')) {
    function render_svg($id)
    {
        $url = wp_get_attachment_url($id);
        $path = get_attached_file($id);

        if ($url && pathinfo($url, PATHINFO_EXTENSION) === 'svg' && file_exists($path)) {
            return file_get_contents($path);
        }

        return '';
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
                    <path d="M15.3011 2.61778C14.4759 1.78492 13.4932 1.12462 12.4102 0.675363C11.3272 0.226106 10.1657 -0.00312325 8.99325 0.00103206C4.08037 0.00103206 0.0765 4.00378 0.072 8.91778C0.072 10.4917 0.48375 12.0228 1.26113 13.3784L0 18.001L4.7295 16.7613C6.03788 17.4733 7.50367 17.8465 8.99325 17.8469H8.99775C13.9118 17.8469 17.9145 13.8442 17.919 8.92566C17.9201 7.75346 17.6893 6.59262 17.2398 5.51002C16.7903 4.42742 16.1311 3.44447 15.3 2.61778H15.3011ZM8.99325 16.3372C7.66462 16.3376 6.36041 15.9801 5.21775 15.3022L4.94775 15.1402L2.142 15.8759L2.89125 13.1388L2.71575 12.8564C1.97303 11.6755 1.58023 10.3083 1.58287 8.91328C1.58287 4.83403 4.9095 1.50628 8.99775 1.50628C9.97171 1.50453 10.9364 1.69559 11.8361 2.06843C12.7359 2.44128 13.553 2.98854 14.2402 3.67866C14.9299 4.36609 15.4766 5.18326 15.8489 6.08304C16.2212 6.98282 16.4116 7.94741 16.4093 8.92116C16.4048 13.015 13.0781 16.3372 8.99325 16.3372V16.3372ZM13.0601 10.7864C12.8385 10.675 11.7439 10.1362 11.538 10.0597C11.3332 9.98653 11.1836 9.94828 11.0374 10.171C10.8877 10.3927 10.4603 10.8978 10.332 11.0429C10.2038 11.1925 10.071 11.2094 9.84825 11.0992C9.62662 10.9867 8.90775 10.7527 8.05725 9.99103C7.3935 9.40041 6.94913 8.66916 6.81638 8.44753C6.68813 8.22478 6.804 8.10553 6.91537 7.99416C7.01325 7.89516 7.137 7.73316 7.24837 7.60491C7.36087 7.47666 7.398 7.38216 7.47112 7.23366C7.54425 7.08291 7.50937 6.95466 7.45425 6.84328C7.398 6.73191 6.95362 5.63278 6.76575 5.18953C6.58575 4.75191 6.40237 4.81266 6.26512 4.80703C6.13687 4.79916 5.98725 4.79916 5.83763 4.79916C5.72463 4.80197 5.61344 4.8281 5.51103 4.87592C5.40862 4.92374 5.3172 4.99221 5.2425 5.07703C5.03775 5.29978 4.46513 5.83866 4.46513 6.93778C4.46513 8.03691 5.26387 9.09328 5.37637 9.24291C5.48662 9.39253 6.94462 11.6414 9.18225 12.6089C9.711 12.8395 10.1273 12.9757 10.4524 13.0792C10.9868 13.2502 11.4694 13.2243 11.8541 13.1692C12.2816 13.1039 13.1715 12.6292 13.3594 12.1083C13.5439 11.5863 13.5439 11.1408 13.4876 11.0474C13.4325 10.9529 13.2829 10.8978 13.0601 10.7864V10.7864Z" fill="#F9F3EA" />
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
