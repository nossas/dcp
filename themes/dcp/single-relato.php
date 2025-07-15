<?php
/**
 * Template para single do CPT "ação"
 */

get_header();
the_post();
$excerpt = !empty($post->post_excerpt) ? wp_kses_post($post->post_excerpt) : '';
$termos_tipo_acao = get_the_terms(get_the_ID(), 'tipo_acao');

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
                <img src="<?= esc_url($icon_url) ?>" alt="Ícone <?= esc_attr($termo->name) ?>" style="width: 16px; height: 16px; margin-right: 6px; vertical-align: middle;">
                <?= esc_html($termo->name) ?>
            </a>
            <p class="post-header__date"><?= get_the_date(); ?></p>
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

    if (!empty($attachments)) : ?>
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
        <script ></script>

        <!-- Script de inicialização -->
        <script>

        </script>
    <?php endif; ?>
</main>

<?php get_template_part('template-parts/content/related-posts-acao'); ?>

<?php get_footer(); ?>
