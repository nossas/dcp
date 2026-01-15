<?php get_header(); ?>
<main id="site-content" role="main">
    <h2 class="sr-only"><?php the_title(); ?></h2>
    <div class="jeomap map_id_<?php echo esc_attr( $post->ID ); ?>"></div>
</main>
<?php get_footer(); ?>
