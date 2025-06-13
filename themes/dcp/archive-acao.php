<?php
get_header(); ?>

<?php echo hacklabr\get_layout_part_header(); ?>

<div class="container container--wide">

    <main class="posts-grid__content">
        <div class="posts-grid__content-cards-agendada">

        <?php
        $agendar_query = new WP_Query([
            'post_type' => 'acao',
            'posts_per_page' => 3,
            'meta_query' => [
                [
                    'key' => 'status_da_acao',
                    'value' => 'Agendar',
                    'compare' => '='
                ]
            ]
        ]);

        if ( $agendar_query->have_posts() ) :
            while ( $agendar_query->have_posts() ) : $agendar_query->the_post();
                get_template_part( 'template-parts/post-card', 'vertical' );
            endwhile;
            wp_reset_postdata();
        else :
            echo '<p>Não há ações a serem agendadas.</p>';
        endif;
        ?>
        </div>
        <button class="load-more-agendar" data-status="Agendar" data-page="1">Ver mais</button>
    </main>

    <div class="posts-grid__content">
        <h2>O que já rolou</h2>
        <div class="posts-grid__content-cards-concluidas">
        <?php
        $concluir_query = new WP_Query([
            'post_type' => 'acao',
            'posts_per_page' => 3,
            'meta_query' => [
                [
                    'key' => 'status_da_acao',
                    'value' => 'Concluir',
                    'compare' => '='
                ]
            ]
        ]);

        if ( $concluir_query->have_posts() ) :
            while ( $concluir_query->have_posts() ) : $concluir_query->the_post();
                get_template_part( 'template-parts/post-card', 'vertical' );
            endwhile;
            wp_reset_postdata();
        else :
            echo '<p>Não há ações concluídas.</p>';
        endif;
        ?>
        </div>
        <button class="load-more-concluir" data-status="Concluir" data-page="1">Ver mais</button>
    </div>

</div><!-- /.container -->

<?php echo hacklabr\get_layout_part_footer(); ?>
<?php get_footer(); ?>
