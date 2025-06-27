<?php

/**
 * The template for displaying all single posts
 */

get_header();
the_post();
$category = get_the_category();
$excerpt = !empty($post->post_excerpt) ? wp_kses_post($post->post_excerpt) : '';
$termos_tipo_acao = get_the_terms(get_the_ID(), 'tipo_acao');

?>

<header class="post-header container container--medium">
    <?php if (function_exists('bcn_display')) : ?>
        <nav class="breadcrumb" typeof="BreadcrumbList" vocab="https://schema.org/">
            <?php bcn_display(); ?>
        </nav>
    <?php endif; ?>
    <h1 class="post-header__title"> <?php the_title(); ?> </h1>
     <?php if (!empty($termos_tipo_acao) && !is_wp_error($termos_tipo_acao)) :
    $termo = $termos_tipo_acao[0];
    $imagem_url = get_template_directory_uri() . '/assets/images/thrash-tag.png';
?>
    <div class="post-header__tags">
        <a class="tag tag--<?= esc_attr($termo->slug) ?>" href="<?= esc_url(get_term_link($termo)) ?>">
            <img src="<?= esc_url($imagem_url) ?>" alt="Ícone" style="width: 16px; height: 16px; margin-right: 6px; vertical-align: middle;">
            <?= esc_html($termo->name) ?>
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
    <?php the_content() ?>
</main>

<?php get_template_part('template-parts/content/related-posts') ?>

<?php get_footer();
