<?php
    get_header();
    echo hacklabr\get_layout_part_header();
?>
<div class="container container--wide archive-acao">
    <hr class="is-separator">
    <div class="container container--wide proximas-acoes">
        <h2><?= __('PRÓXIMAS AÇÕES') ?></h2>
        <p><?php _e('Fique por dentro das próximas ações que vão rolar na comunidade!') ?></p>
    </div>
    <div class="posts-grid__content">
        <?php
        $agendar_query = new WP_Query([
            'post_type' => 'acao',
            'post_status' => 'publish',
            'posts_per_page' => 3,
            'orderby' => 'meta_value',
            'meta_key' => 'data_e_horario',
        ]);
        if ( $agendar_query->have_posts() ) : ?>
            <div class="posts-grid__content-cards-agendada">
                <?php
                    while ( $agendar_query->have_posts() ) : $agendar_query->the_post();
                        get_template_part( 'template-parts/post-card', 'vertical' );
                    endwhile;
                ?>
            </div>
            <?php
            $disabled_agendar = ($agendar_query->found_posts <= 3) ? 'disabled' : '';
            ?>
            <button class="load-more-agendar ver-mais-acoes" data-status="Agendar" data-page="1" <?php echo $disabled_agendar; ?>>Mostrar mais</button>
            <?php
            wp_reset_postdata();
        else : ?>
        <div style="background-color: rgba(0,0,0,0.03); padding: 25px; margin: 25px 0; border-radius: 25px;">
            <p>Não existe ações agendadas.</p>
        </div>
        <?php endif; ?>
    </div>

    <hr class="is-separator">

    <div class="container container--wide proximas-acoes">
        <h2><?= __('O que já rolou') ?></h2>
        <p><?php _e('Quer saber o que já aconteceu? Veja outras atividades que já rolaram na comunidade!') ?></p>
        <p>&nbsp;</p>
    </div>

    <div class="posts-grid__content">

        <?php
        $concluir_query = new WP_Query([
            'post_type' => 'relato',
            'post_status' => 'publish',
            'posts_per_page' => 3
        ]);
        if ( $concluir_query->have_posts() ) : ?>
            <!--<p class="posts-grid__excerpt">--><?php //= __('Quer saber o que já aconteceu? Veja outras atividades que já rolaram no território!') ?><!--</p>-->
            <div class="posts-grid__content-cards-concluidas">
                <?php while ( $concluir_query->have_posts() ) {
                    $concluir_query->the_post();
                    get_template_part( 'template-parts/post-card', 'vertical', [
                        'show_top_date' => true
                    ] );
                } ?>
            </div>
            <?php
            $disabled_concluir = ($concluir_query->found_posts <= 3) ? 'disabled' : '';
            ?>
            <button class="load-more-concluir ver-mais-acoes" data-status="Concluir" data-page="1" <?php echo $disabled_concluir; ?>><?= __('Mostrar mais') ?></button>
            <?php
            wp_reset_postdata();
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
