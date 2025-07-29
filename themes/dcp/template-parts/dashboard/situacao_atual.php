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




$recomendacoes_ativas_post = get_posts([
    'post_type' => 'recomendacao',
    'posts_per_page' => -1,
    'orderby'        => 'date',
    'order'          => 'ASC',
        'meta_query' => [
            [
                'key' => 'is_active',
                'value' => 'ON',
                'compare' => '='
            ]
        ]
]);

?>
<div id="dashboardSituacaoAtual" class="dashboard-content">
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

    <div class="alerta-faixa">
        <div class="alerta-faixa__topo">
            <div class="alerta-faixa__mensagem">
                <span class="alerta-faixa__icone">
                    <svg xmlns="http://www.w3.org/2000/svg" width="40" height="30" viewBox="0 0 40 30" fill="none">
                        <path d="M4.03307 13.7394C4.03307 13.7394 4.03796 13.7467 4.04283 13.7467H4.46187C6.31106 13.7369 7.95317 14.9186 8.53302 16.6752L8.83512 17.5888C8.83512 17.5888 8.84243 17.5985 8.84974 17.5961L28.8936 10.979C28.8936 10.979 28.9033 10.9717 28.9009 10.9644L28.5988 10.0532C28.0189 8.299 28.6353 6.36941 30.1264 5.27549L30.5649 4.95389C30.5649 4.95389 30.5698 4.94658 30.5698 4.94171L30.2653 4.0159C29.4637 1.58685 26.8422 0.266351 24.4132 1.06791L6.66677 6.92733C4.23773 7.72889 2.91723 10.3504 3.71879 12.7794L4.03551 13.7418L4.03307 13.7394Z" fill="#281414" />
                        <path d="M8.21674 22.3297C7.68805 20.729 6.56488 17.3254 6.56488 17.323C5.98991 15.581 3.88002 15.6955 2.40116 15.7662C2.39872 15.7662 2.39629 15.7662 2.39385 15.7662C1.45586 16.1194 0.88332 17.1695 1.22441 18.2074L2.84701 23.1191C4.00915 22.6659 5.39299 22.3151 7.53211 22.3151C7.77087 22.3151 7.99745 22.32 8.21916 22.3297H8.21674Z" fill="#281414" />
                        <path d="M30.0354 20.8946L30.8711 20.6193L31.9187 20.2734C31.9187 20.2734 31.9309 20.2734 31.9333 20.2807L32.0917 20.7582C32.2695 21.2966 32.8201 21.6499 33.3707 21.5208C33.992 21.3746 34.3428 20.7314 34.1455 20.1394L33.9701 19.6107C33.9701 19.6107 33.9701 19.5985 33.9774 19.5961L35.1054 19.2233C37.3785 18.4729 38.6113 16.0219 37.8609 13.7488C37.8609 13.7488 35.5903 6.86857 35.5878 6.8637C35.3539 6.15472 34.7229 5.63578 33.9847 5.54076C33.6387 5.49691 33.3147 5.57731 33.0053 5.72349C32.3938 6.01585 31.8505 6.57378 31.3461 6.9441C30.5787 7.5069 30.262 8.49849 30.5616 9.40238L32.4669 15.1717C32.6374 15.6906 32.4133 16.2826 31.9114 16.4995C31.3461 16.7431 30.7054 16.4459 30.5153 15.8733L29.5505 12.9546C29.5505 12.9546 29.5432 12.9448 29.5359 12.9473L9.49208 19.5644C9.49208 19.5644 9.48233 19.5717 9.48477 19.579L10.4325 22.4466C10.4496 22.4978 10.4593 22.5489 10.469 22.6001C13.283 23.1994 14.4062 24.5833 17.6002 25.0048L30.0354 20.8995V20.8946Z" fill="#281414" />
                        <path d="M19.6346 27.312C13.5802 27.312 13.5802 24.5029 7.52589 24.5029C3.54977 24.5029 2.18297 25.7162 0 26.547V30.0773H40V26.6323C37.3176 25.7796 36.2017 24.5029 31.7432 24.5029C25.6889 24.5029 25.6889 27.312 19.6346 27.312Z" fill="#281414" />
                    </svg>
                </span>
                <div class="alerta-faixa__warning"><strong>ATENÇÃO</strong> Alagamento em algumas áreas do Jacarezinho. Evite locais de risco.</div>
            </div>
            <?php
                $slug = sanitize_title($post->post_title);
            ?>
            <a href="<?= get_dashboard_url('alterar_risco', ['alterar-risco' => $slug]) ?>" class="alerta-faixa__alterar">Alterar</a>
        </div>

        <div class="alerta-faixa__info">
            <div class="alerta-faixa__local">
                <p class="alerta-faixa__local--estado"><?= __('Rio de Janeiro:') ?></p> <strong><span>ESTÁGIO 3</span></strong>
                <div class="multistepform__pipe"> | </div>
                <span>32º</span> • Chuvas medianas
            </div>
            <div class="alerta-faixa__data">
                Última atualização: <span>15:30</span> <span>15/01/25</span>
            </div>
        </div>
    </div>


    <div class="recomendacoes-card">
        <div class="recomendacoes-card__header">
            <h2 class="recomendacoes-card__title situacao-atual__content-title-text"><?= __('Recomendações Ativas') ?></h2>
            <button class="recomendacoes-card__editar situacao-atual__edit-btn">
                <?= __('Editar') ?>
            </button>
        </div>

        <?php

        if( !empty( $recomendacoes_ativas_post ) ) :

        foreach ( $recomendacoes_ativas_post as $post_ativo ):

            $pod_ativo = \pods('recomendacao', $post_ativo->ID);

            $recomendacao_ativa_1 = $pod_ativo ? $pod_ativo->display('recomendacao_1') : '';
            $recomendacao_ativa_2 = $pod_ativo ? $pod_ativo->display('recomendacao_2') : '';
            $recomendacao_ativa_3 = $pod_ativo ? $pod_ativo->display('recomendacao_3') : '';

            $icone_ativa_1_id = $pod_ativo ? $pod_ativo->field('icone_1.ID') : null;
            $icone_ativa_2_id = $pod_ativo ? $pod_ativo->field('icone_2.ID') : null;
            $icone_ativa_3_id = $pod_ativo ? $pod_ativo->field('icone_3.ID') : null;

            ?>

        <div class="recomendacoes-card__body" style="margin-bottom: 25px;">
            <p class="recomendacoes-card__titulo situacao-atual__card-title"><?= esc_html($post_ativo->post_title) ?></p>
            <ul class="recomendacoes-card__lista">
                <li>
                    <span class="recomendacoes-card__icone">
                        <?= $icone_ativa_1_id ? render_svg($icone_ativa_1_id) : ''; ?>
                    </span>
                    <?= esc_html($recomendacao_ativa_1) ?>
                </li>
                <li>
                    <span class="recomendacoes-card__icone">
                        <?= $icone_ativa_2_id ? render_svg($icone_ativa_2_id) : ''; ?>
                    </span>
                    <?= esc_html($recomendacao_ativa_2) ?>
                </li>
                <li>
                    <span class="recomendacoes-card__icone">
                        <?= $icone_ativa_3_id ? render_svg($icone_ativa_3_id) : ''; ?>
                    </span>
                    <?= esc_html($recomendacao_ativa_3) ?>
                </li>
            </ul>
        </div>

        <?php endforeach;

        else : ?>
            <div class="message-response" style="display: block;">
                <span class="tabs__panel-message">Nenhuma recomendação foi ativa ainda.</span>
            </div>
        <?php endif; ?>
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
</div>
