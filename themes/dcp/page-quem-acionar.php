<?php
/**
 * Template Name: Quem Acionar
 * Description: Template específico para a página "Quem Acionar"
 */

get_header();
?>

<main id="primary" class="site-main">
    <div class="title container container--wide">
        <h2> Quem Acionar </h2>
        <p>Selecione um tipo de ocorrência ou descreva o que está acontecendo para saber qual serviço ou órgão pode te ajudar.</p>
    </div>
    <?php get_template_part( 'template-parts/filter-apoio-search' ); ?>
    <div class="title-card container container--wide">
        <span>
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/arrow-green.svg" alt="Seta verde" style="height: 25px;" />
            <p> Iniciativas no Jacarezinho </p>
        </span>

    </div>
    <!-- Lista de Apoios -->
    <?php get_template_part( 'template-parts/card-apoio-list' ); ?>


    <!-- Conteúdo da página -->
    <?php while ( have_posts() ) : the_post(); the_content(); endwhile; ?>
</main>

<!-- Modal -->
<div id="call-modal" class="call-modal">
  <div class="call-modal__content">
    <p id="modal-text"></p>
    <button class="call-modal__close">Fechar</button>
  </div>
</div>

<?php get_footer(); ?>



