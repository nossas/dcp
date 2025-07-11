<?php
get_header(); ?>

<?php echo hacklabr\get_layout_part_header(); ?>

<div class="container container--wide">

    <main class="posts-grid__content">
        <div class="posts-grid__content-cards-agendada">

        <?php
        $agendar_query = new WP_Query([
            'post_type' => 'acao',
            'post_status' => 'publish',
            'posts_per_page' => 3,
//            'meta_query' => [
//                [
//                    'key' => 'status_da_acao',
//                    'value' => 'Agendar',
//                    'compare' => '='
//                ]
//            ]
        ]);

        if ( $agendar_query->have_posts() ) :
            while ( $agendar_query->have_posts() ) : $agendar_query->the_post();
                get_template_part( 'template-parts/post-card', 'vertical' );
            endwhile;
            wp_reset_postdata();
        else : ?>
        <div style="background-color: rgba(0,0,0,0.03); padding: 25px; border-radius: 25px;">
            <p>Não há ações a serem agendadas.</p>
        </div>
        <?php endif; ?>
        </div>
        <button class="load-more-agendar ver-mais-acoes" data-status="Agendar" data-page="1">Ver mais</button>
    </main>

    <div class="posts-grid__content">
        <h2 class="posts-grid__title"><?= __('O que já rolou') ?></h2>
        <p class="posts-grid__excerpt"><?= __('Quer saber o que já aconteceu? Veja outras atividades que já rolaram no território!') ?></p>
        <div class="posts-grid__content-cards-concluidas">
        <?php
        $concluir_query = new WP_Query([
            'post_type' => 'relato',
            'post_status' => 'publish',
            'posts_per_page' => 3,
//            'meta_query' => [
//                [
//                    'key' => 'status_da_acao',
//                    'value' => 'Concluir',
//                    'compare' => '='
//                ]
//            ]
        ]);

        if ( $concluir_query->have_posts() ) :
            while ( $concluir_query->have_posts() ) : $concluir_query->the_post();
                get_template_part( 'template-parts/post-card', 'vertical' );
            endwhile;
            wp_reset_postdata();
        else : ?>
            <div style="background-color: rgba(0,0,0,0.03); padding: 25px; border-radius: 25px;">
                <p>Não há ações a serem agendadas.</p>
            </div>
        <?php endif; ?>
        </div>
        <button class="load-more-concluir ver-mais-acoes" data-status="Concluir" data-page="1"><?= __('Ver mais') ?></button>
    </div>

</div><!-- /.container -->

<?php echo hacklabr\get_layout_part_footer(); ?>
<?php get_footer(); ?>
