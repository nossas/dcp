<?php
/* Template Name: Registrar Risco */
get_header();
?>

<main class="formulario-risco">
<h1 class="sr-only"><?php get_the_title() ?></h1>
    <?php include get_template_directory() . '/template-parts/register-risk/register-risk.php'; ?>
</main>

<?php get_footer(); ?>
