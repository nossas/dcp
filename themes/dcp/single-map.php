<?php get_header(); ?>
<main id="site-content" role="main">
    <h1 class="sr-only"><?php the_title(); ?></h1>
    <div class="jeomap map_id_<?php echo esc_attr( $post->ID ); ?>"></div>
</main>
<?php get_footer(); ?>
