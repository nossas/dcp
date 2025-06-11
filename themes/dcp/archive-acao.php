<?php
get_header(); ?>

    <?php echo hacklabr\get_layout_part_header(); ?>

    <div class="container container--wide">

            <main class="posts-grid__content">
                <?php while ( have_posts() ) : the_post(); ?>
                    <?php get_template_part( 'template-parts/post-card', 'vertical' ); ?>
                <?php endwhile; ?>
            </main>

            <?php
            the_posts_pagination([
                'prev_text' => __( '<iconify-icon icon="iconamoon:arrow-left-2-bold"></iconify-icon>', 'hacklbr'),
                'next_text' => __( '<iconify-icon icon="iconamoon:arrow-right-2-bold"></iconify-icon>', 'hacklbr'),

            ]); ?>

    </div><!-- /.container -->

    <?php echo hacklabr\get_layout_part_footer(); ?>

<?php get_footer();
