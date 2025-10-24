<?php
get_header();

if (have_posts()) :
    while (have_posts()) : the_post(); ?>

        <main class="container container--wide page-sobre">
            <h1 class="sr-only"><?php get_the_title() ?></h1>
            <?php the_content(); ?>
        </main>
    <?php endwhile;
endif;

get_footer();
