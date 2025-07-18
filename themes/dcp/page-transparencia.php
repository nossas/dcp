<?php
// page-transparencia.php

get_header(); ?>

<main class="container container--wide transparencia">
    <?php
    if (have_posts()) :
        while (have_posts()) : the_post(); ?>
            <article id="post-<?php the_ID(); ?>" <?php post_class(); ?>>
                <div class="page-content">
                    <?php the_content(); // Aqui virão os blocos do painel ?>
                </div>
            </article>
        <?php endwhile;
    endif;
    ?>
    <?php get_template_part('template-parts/form-ficou-alguma-duvida'); ?>

</main>

<?php get_footer(); ?>
