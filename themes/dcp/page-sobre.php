<?php
get_header();

if (have_posts()) :
    while (have_posts()) : the_post(); ?>

        <main class="container container--wide page-sobre">
            <?php the_content(); ?>
        </main>
    <?php endwhile;
endif;

get_footer();
