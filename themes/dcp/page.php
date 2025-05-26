<?php
get_header();
?>
<div class="container">
    <div class="post-header post-header__separator">
        <!-- <h1 class="post-header__title"><?php the_title() ?></h1>
            <div class="post-header__excerpt container--wide">
                <?php the_excerpt() ?>
            </div> -->
    </div>
</div>
<div class="post-content content content--wide">
    <?php if (function_exists('bcn_display')) : ?>
        <nav class="breadcrumb" typeof="BreadcrumbList" vocab="https://schema.org/">
            <?php bcn_display(); ?>
        </nav>
    <?php endif; ?>
    <?php the_content() ?>
</div>
<?php get_footer();
