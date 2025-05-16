<!-- Lista de Apoios -->
<section class="apoio-lista container container--wide" id="apoio-lista">
    <?php
    $apoios = get_posts([
        'post_type' => 'apoio',
        'posts_per_page' => 2,
        'orderby' => 'menu_order',
        'order' => 'ASC',
    ]);

    foreach ( $apoios as $apoio ) {
        $endereco = get_post_meta( $apoio->ID, 'endereco', true );
        $horario = get_post_meta( $apoio->ID, 'horario_de_atendimento', true );
        $observacoes = get_post_meta ( $apoio->ID, 'observacoes', true );
        $terms = get_the_terms( $apoio->ID, 'tipo_apoio' );
        $term_slugs = [];

        if ( $terms && !is_wp_error($terms) ) {
            foreach ( $terms as $term ) {
                $term_slugs[] = $term->slug;
            }
        }

        $tag_classes = $term_slugs ? implode(' ', $term_slugs) : '';
        ?>
        <article class="apoio-item" data-tags="<?php echo esc_attr( $tag_classes ); ?>">
            <h2><?php echo esc_html( get_the_title( $apoio ) ); ?></h2>
            <div class="apoio-conteudo">
            <?php echo apply_filters( 'the_content', $apoio->post_content ); ?>
            <hr>
        </div>
            <?php if ( $endereco ) : ?>
                <div class="adress">
                <div class="icon-adress">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/wrapper.svg" alt="Ícone de endereço" />
                    </div>
                    <p><strong>Endereço:</strong> <?php echo esc_html( $endereco ); ?></p>
                </div>
            <?php endif; ?>
            <?php if ( $horario ) : ?>
                <div class="hour">
                <div class="icon-pin">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/pin.svg" alt="Ícone de horário" />
                </div>
                <p><strong>Horário de Atendimento:</strong> <?php echo esc_html( $horario ); ?></p>
            </div>
            <?php endif; ?>
            <hr class="separator">
            <?php if ( $observacoes ) : ?>
                <span><?php echo esc_html( $observacoes ); ?></p>
            <?php endif; ?>
        </article>
        <hr>
        <?php
    }
    ?>
</section>
