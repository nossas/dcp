<?php get_header(); ?>
<div class="error-404 container container--wide">
    <div class="error-404__content">
        <div class="error-404__image">
            <img src="<?= get_template_directory_uri() ?>/assets/images/image-404.svg" alt="404">
        </div>
        <div class="error-404__error">
            <h1 class="error-404__error-title">
                <?php _e('Algo deu errado', 'hacklabr') ?>
            </h1>
            <p class="error-404__error-subtitle">
                <?php _e("Não conseguimos encontrar a página que você procurou. Talvez o link esteja incorreto ou a página tenha sido removida.", 'hacklabr') ?>
            </p>
            <a href="<?= home_url() ?>" class="button"> <span><?php _e('Return to home page', 'hacklabr') ?></span> </a>
            <div class="error-404__error-divider"></div>
            <div class="error-404__error-wrapper-contact">
                <span class="error-404__error-contact">
                    <?php _e('Se o problema persistir, fale com a gente pelo e-mail ', 'hacklabr') ?>
                    <a href="mailto:contato@defesaclimaticapopular.com<?= get_field('email', 'option') ?>"><?php _e('contato@defesaclimaticapopular.com', 'hacklabr') ?></a>
                </span>
            </div>
        </div>
    </div>

<?php get_footer(); ?>
