<!-- Filtro: busca e tags -->
<section class="apoio-filtro container container--wide">
    <div class="apoio-search-wrap">
        <input type="text" id="apoio-search" placeholder="Descreva o problema e veja quem acionar..." />
        <button id="apoio-search-button" aria-label="Buscar">
            Buscar
        </button>
    </div>

    <div class="apoio-tags">
<!--         <button class="tag-button active" data-tag="all">Todos</button>
 -->
        <?php
        // Pega o termo pai "QUEM ACIONAR"
        $parent_term = get_term_by('name', 'QUEM ACIONAR', 'tipo_apoio');

        if ($parent_term && !is_wp_error($parent_term)) {
            // Pega os filhos do termo "QUEM ACIONAR"
            $child_terms = get_terms([
                'taxonomy'   => 'tipo_apoio',
                'parent'     => $parent_term->term_id,
                'hide_empty' => false,
            ]);

            foreach ( $child_terms as $term ) {
                echo '<button class="tag-button" data-tag="' . esc_attr( $term->slug ) . '">' . esc_html( $term->name ) . '</button>';
            }
        }
        ?>
    </div>
</section>
