<?php
/**
 * Template Name: Quem Acionar
 * Description: Template específico para a página "Quem Acionar"
 */

get_header(); ?>

<main id="primary" class="site-main">
    <?php

    while ( have_posts() ) :
        the_post();

        // Exibe os blocos criados no editor Gutenberg
        the_content();

    endwhile;
    ?>
</main><!-- #primary -->
<div id="call-modal" class="call-modal">
  <div class="call-modal__content">
    <p id="modal-text"></p>
    <button class="call-modal__close">Fechar</button>
  </div>
</div>
<?php get_footer(); ?>
