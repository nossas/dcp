<?php
/**
 * Template para single do CPT "ação"
 */

get_header();
the_post();

$termos_tipo_acao = get_the_terms(get_the_ID(), 'tipo_acao');
$pods = pods( 'relato', get_the_ID() );

$excerpt = !empty( $pods->field( 'descricao' ) ) ? nl2br( $pods->field( 'descricao' ) ) : '';

$base_icon_dir = get_template_directory_uri() . '/assets/images/tipo-acao/';
$icon_filename = 'default.svg';
if (!empty($termos_tipo_acao) && !is_wp_error($termos_tipo_acao)) {
    $termo = $termos_tipo_acao[0];
    $slug = $termo->slug;

    $base_icon_path = get_template_directory() . '/assets/images/tipo-acao/';

    $svg_file = $slug . '.svg';
    $png_file = $slug . '.png';

    if (file_exists($base_icon_path . $svg_file)) {
        $icon_filename = $svg_file;
    } elseif (file_exists($base_icon_path . $png_file)) {
        $icon_filename = $png_file;
    }

    $icon_url = $base_icon_dir . $icon_filename;
}
?>

<header class="post-header container container--medium">
    <?php if (function_exists('bcn_display')) : ?>
        <nav class="bread-relato" typeof="BreadcrumbList" vocab="https://schema.org/">
            <?php bcn_display(); ?>
        </nav>
    <?php endif; ?>

    <h1 class="post-header__title" style="color: #281414;"><?php the_title(); ?></h1>

    <?php if (!empty($termo)) : ?>
        <div class="post-header__tags">
            <a class="tag tag--<?= esc_attr($termo->slug) ?>" href="<?= esc_url(get_term_link($termo)) ?>">
                <iconify-icon icon="bi:mic-fill"></iconify-icon>
                <?= esc_html($termo->name) ?>
            </a>
            <p class="post-header__date">
                <?=date( 'd', strtotime( $pods->field( 'data_e_horario' ) ) )?>
                de
                <?=date( 'F', strtotime( $pods->field( 'data_e_horario' ) ) )?>
                de
                <?=date( 'Y', strtotime( $pods->field( 'data_e_horario' ) ) )?>
            </p>
        </div>
    <?php endif; ?>

    <?php if ($excerpt) : ?>
        <p class="post-header__excerpt"><?= $excerpt; ?></p>
    <?php endif; ?>

    <div class="post-header__featured-image">
        <?= get_the_post_thumbnail(null, 'post-thumbnail', [
            'class' => 'featured-image',
            'style' => 'height: 455px; object-fit: cover; width: 100%; border-radius:20px;'
        ]); ?>
    </div>
</header>

<main class="post-content container container--medium">
    <?php the_content(); ?>

    <?php
        // Exibe slider de anexos (imagens)
        $attachments = get_attached_media('image', get_the_ID());

        if (!empty($attachments)) :
            unset( $attachments[ get_post_thumbnail_id( get_the_ID() ) ] ); ?>
            <!-- Swiper CSS -->
            <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css" />

            <!-- Estrutura do slider -->
            <div class="anexos-slider swiper">
                <div class="swiper-wrapper">
                    <?php foreach ($attachments as $attachment) :
                        $img_url = wp_get_attachment_url($attachment->ID); ?>
                        <div class="swiper-slide">
                            <img src="<?= esc_url($img_url); ?>" alt="Anexo">
                        </div>
                    <?php endforeach; ?>
                </div>

                <!-- Botões -->
                <div class="swiper-button-next"></div>
                <div class="swiper-button-prev"></div>

                <!-- Paginação -->
                <div class="swiper-pagination"></div>
            </div>

            <!-- Swiper JS -->
            <script src="https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js"></script>

            <!-- Script de inicialização -->
            <script>
                document.addEventListener('DOMContentLoaded', function () {
                    const swiper = new Swiper('.anexos-slider', {
                        loop: true,
                        slidesPerView: 1,
                        spaceBetween: 30,
                        navigation: {
                            nextEl: '.swiper-button-next',
                            prevEl: '.swiper-button-prev',
                        },
                        pagination: {
                            el: '.swiper-pagination',
                            clickable: true,
                        },
                    });
                });
            </script>
        <?php endif; ?>
</main>

<?php get_template_part('template-parts/content/related-posts-acao' ); ?>

<?php get_footer(); ?>
