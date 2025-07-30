<?php

$active_tag_name = '';
$active_slug = '';
$term_object = null;

if (isset($_GET['tag_selecionada']) && !empty($_GET['tag_selecionada'])) {
    $active_slug = sanitize_key($_GET['tag_selecionada']);
    $term_object = get_term_by('slug', $active_slug, 'tipo_apoio');

} else {

    $parent_term = get_term_by('name', 'QUEM ACIONAR', 'tipo_apoio');
    if ($parent_term) {
        $first_term_array = get_terms([
            'taxonomy' => 'tipo_apoio',
            'parent' => $parent_term->term_id,
            'hide_empty' => false,
            'number' => 1
        ]);
        if (!empty($first_term_array)) {
            $term_object = $first_term_array[0];
            $active_slug = $term_object->slug;
        }
    }
}


if ($term_object) {
    $active_tag_name = $term_object->name;
}
?>

<?php
if (!empty($active_tag_name)) :
?>
    <div class="title-card container container--wide">
        <span>
            <img src="<?php echo get_template_directory_uri(); ?>/assets/images/arrow-green.svg" alt="Seta verde" style="height: 25px; vertical-align: middle;" />
            Serviços de <?php echo esc_html($active_tag_name); ?>
        </span>
    </div>
<?php endif; ?>


<section class="apoio-lista container container--wide" id="apoio-lista">
    <?php

    $query_args = [
        'post_type' => 'apoio',
        'posts_per_page' => -1,
        'orderby' => 'menu_order',
        'order' => 'ASC',
    ];

    if (!empty($active_slug)) {
        $query_args['tax_query'] = [
            [
                'taxonomy' => 'tipo_apoio',
                'field'    => 'slug',
                'terms'    => $active_slug,
            ],
        ];
    } else {
        $query_args['posts_per_page'] = 2;
    }

    $apoios = get_posts($query_args);

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
            <?php if ( $horario ) : ?>
                <div class="hour">
                <div class="icon-pin">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/pin.svg" alt="Ícone de horário" />
                </div>
                <p><strong>Dia:</strong> <?php echo esc_html( $horario ); ?></p>
            </div>
            <?php endif; ?>

            <?php if ( $endereco ) : ?>
                <div class="adress">
                <div class="icon-adress">
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/wrapper.svg" alt="Ícone de endereço" />
                    </div>
                    <p><strong>Endereço:</strong> <?php echo esc_html( $endereco ); ?></p>
                </div>
            <?php endif; ?>
            <hr class="separator">
            <?php if ( $observacoes ) : ?>
                <span><?php echo esc_html( $observacoes ); ?></span>
            <?php endif; ?>
        </article>
        <hr>
        <?php
    }
    ?>
</section>
