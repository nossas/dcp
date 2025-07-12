<?php
get_header(); ?>

<?php echo hacklabr\get_layout_part_header(); ?>

<div class="container container--wide archive-acao">

    <hr class="is-separator">

    <div class="container container--wide proximas-acoes">
        <h2><?= __('PRÓXIMAS AÇÕES') ?></h2>
        <p>Aenean egestas ultricies nibh, at tempus purus fringilla in. Curabitur ornare enim justo, at tristique.</p>
    </div>
    <div class="posts-grid__content">
        <?php
        $agendar_query = new WP_Query([
            'post_type' => 'acao',
            'post_status' => 'publish',
            'posts_per_page' => 3
        ]);
        if ( $agendar_query->have_posts() ) : ?>
            <div class="posts-grid__content-cards-agendada">
                <?php
                    while ( $agendar_query->have_posts() ) : $agendar_query->the_post();
                        get_template_part( 'template-parts/post-card', 'vertical' );
                    endwhile;
                ?>
            </div>
            <?php if( $agendar_query->post_count > 2 ) : ?>
                <button class="load-more-agendar ver-mais-acoes" data-status="Agendar" data-page="1">Ver mais</button>
            <?php endif; wp_reset_postdata();
        else : ?>
        <div style="background-color: rgba(0,0,0,0.03); padding: 25px; margin: 25px 0; border-radius: 25px;">
            <p>Não existe ações agendadas.</p>
        </div>
        <?php endif; ?>
    </div>

    <hr class="is-separator">

    <div class="container container--wide proximas-acoes">
        <h2><?= __('O que já rolou') ?></h2>
        <p>Aenean egestas ultricies nibh, at tempus purus fringilla in. Curabitur ornare enim justo, at tristique.</p>
    </div>

    <div class="posts-grid__content">

        <?php
        $concluir_query = new WP_Query([
            'post_type' => 'relato',
            'post_status' => 'publish',
            'posts_per_page' => 3
        ]);
        if ( $concluir_query->have_posts() ) : ?>
            <p class="posts-grid__excerpt"><?= __('Quer saber o que já aconteceu? Veja outras atividades que já rolaram no território!') ?></p>
            <div class="posts-grid__content-cards-concluidas">
                <?php while ( $concluir_query->have_posts() ) {
                    $concluir_query->the_post();
                    get_template_part( 'template-parts/post-card', 'vertical' );
                } ?>
            </div>
            <?php if( $concluir_query->post_count > 2 ) : ?>
                <button class="load-more-concluir ver-mais-acoes" data-status="Concluir" data-page="1"><?= __('Ver mais') ?></button>
            <?php endif; wp_reset_postdata();
        else : ?>
            <div style="background-color: rgba(0,0,0,0.03); padding: 25px; margin: 25px 0; border-radius: 25px;">
                <p>Não existe relatos agendados.</p>
            </div>
        <?php endif; ?>
    </div>

    <hr class="is-separator">

</div><!-- /.container -->

<?php echo hacklabr\get_layout_part_footer(); ?>
<?php get_footer(); ?>
