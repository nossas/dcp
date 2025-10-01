<?php
// page-transparencia.php

get_header(); ?>

<main class="container container--wide transparencia">
    <?php if (function_exists('bcn_display')) : ?>
        <nav class="breadcrumb" typeof="BreadcrumbList" vocab="https://schema.org/">
            <?php bcn_display(); ?>
        </nav>
    <?php endif; ?>

    <?php
    if (have_posts()) :
        while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <div class="page-content">
                    <?php the_content();
                    ?>
                </div>
            </article>
    <?php endwhile;
    endif;
    ?>
    <?php get_template_part('template-parts/form-ficou-alguma-duvida'); ?>

</main>

<?php get_footer(); ?>
