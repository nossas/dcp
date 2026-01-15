<?php get_header(); ?>

<main id="site-content" role="main">
    <?php
    $content = get_post_field('post_content', get_the_ID());

    // verifica se já existe um <h1> no conteúdo
    $has_h1 = (bool) preg_match('/<h1\b[^>]*>/i', $content);

    if ( ! $has_h1 ) : ?>
        <h1 class="sr-only"><?php the_title(); ?></h1>
    <?php endif; ?>

    <div class="jeomap map_id_<?php echo esc_attr( get_the_ID() ); ?>"></div>
</main>

<?php get_footer(); ?>
