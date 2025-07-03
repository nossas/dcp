<?php
/**
 * The template for displaying all single posts
 */

get_header();
the_post();
$excerpt = !empty($post->post_excerpt) ? wp_kses_post($post->post_excerpt) : '';
$categories = get_the_category();
$first_cat = !empty($categories) ? $categories[0] : null;

// Diretório base dos ícones SVG
$base_icon_dir = get_template_directory_uri() . '/assets/images/categorias/';
$icon_filename = $first_cat ? $first_cat->slug . '.svg' : 'default.svg';
$icon_url = $base_icon_dir . $icon_filename;

// Caminho físico do ícone (para fallback se não existir)
$icon_path = get_template_directory() . '/assets/images/categorias/' . $icon_filename;
if (!file_exists($icon_path)) {
    $icon_url = $base_icon_dir . 'default.svg';
}
?>

<header class="post-header container container--medium">
    <?php if (function_exists('bcn_display')) : ?>
        <nav class="breadcrumb" typeof="BreadcrumbList" vocab="https://schema.org/">
            <?php bcn_display(); ?>
        </nav>
    <?php endif; ?>

    <h1 class="post-header__title"><?php the_title(); ?></h1>

    <?php if ($first_cat): ?>
        <div class="post-header__tags">
            <a class="tag tag--<?= esc_attr($first_cat->slug) ?>" href="<?= esc_url(get_category_link($first_cat->term_id)) ?>">
                <img src="<?= esc_url($icon_url) ?>" alt="Ícone <?= esc_attr($first_cat->name) ?>" style="width: 16px; height: 16px; margin-right: 6px; vertical-align: middle;">
                <?= esc_html($first_cat->name) ?>
            </a>
            <p class="post-header__date"><?= get_the_date() ?></p>
        </div>
    <?php endif; ?>

    <?php if ($excerpt) : ?>
        <p class="post-header__excerpt"><?= get_the_excerpt() ?></p>
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
    <div class="contact-form-single"></div>
    <div class="wp-block-cover alignfull is-light ficou-alguma-duvida" style="display: flex;">
    <span aria-hidden="true" class="wp-block-cover__background has-secondary-light-background-color has-background-dim-100 has-background-dim"></span>

    <div class="wp-block-cover__inner-container is-layout-constrained wp-block-cover-is-layout-constrained wp-block-columns is-layout-flex wp-container-core-columns is-layout-28f84493 wp-block-columns is-layout-flex" style="display: flex;">

        <div class="wp-block-column is-layout-flow wp-block-column-is-layout-flow" style="flex-basis: 656px;">
        <figure class="wp-block-image size-full">
        <img decoding="async" src="<?php echo esc_url(get_template_directory_uri() . '/assets/images/banner-duvida.png'); ?>" alt="Descrição da imagem" style="object-fit: cover;">        </figure>
        <div style="height:100px" aria-hidden="true" class="wp-block-spacer"></div>
        </div>

        <div class="wp-block-column is-layout-flow wp-block-column-is-layout-flow" style="flex-basis: 676px;">
        <?php echo do_shortcode('[contact-form-7 id="76b0509" title="contato"]'); ?>
        </div>

        <div style="height:32px" aria-hidden="true" class="wp-block-spacer"></div>
    </div>
</div>

</main>

<?php get_template_part('template-parts/content/related-posts'); ?>


<?php get_footer(); ?>
