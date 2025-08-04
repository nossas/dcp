<section class="apoio-filtro container container--wide">
    <div class="apoio-search-wrap">
        <input type="text" id="apoio-search" placeholder="Descreva o problema e veja quem acionar..." />
        <button id="apoio-search-button" aria-label="Buscar">
            Buscar
        </button>
    </div>

    <div class="apoio-tags">
        <?php

        $parent_term = get_term_by('name', 'QUEM ACIONAR', 'tipo_apoio');
        $child_terms_raw = [];

        if ($parent_term && !is_wp_error($parent_term)) {
            $child_terms_raw = get_terms([
                'taxonomy'   => 'tipo_apoio',
                'parent'     => $parent_term->term_id,
                'hide_empty' => false,
            ]);
        }

        $outros_term = null;
        $child_terms = [];

        foreach ($child_terms_raw as $term) {
            if (strtolower($term->name) === 'outros') {
                $outros_term = $term;
            } else {
                $child_terms[] = $term;
            }
        }

        if ($outros_term) {
            $child_terms[] = $outros_term;
        }

        if (isset($_GET['tag_selecionada'])) {
            $active_slug = sanitize_key($_GET['tag_selecionada']);
        } elseif (!empty($child_terms)) {
            $active_slug = $child_terms[0]->slug;
        } else {
            $active_slug = '';
        }

        if (!empty($child_terms)) {
            foreach ( $child_terms as $term ) {
                $link = add_query_arg('tag_selecionada', $term->slug);
                $class = ($active_slug === $term->slug) ? 'tag-button active' : 'tag-button';
                echo '<a href="' . esc_url($link) . '" class="' . esc_attr($class) . '" data-tag="' . esc_attr( $term->slug ) . '">' . esc_html( $term->name ) . '</a>';
            }
        }
        ?>
    </div>
</section>
