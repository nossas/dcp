<!-- Filtro: busca e tags -->
<section class="apoio-filtro">
    <div class="apoio-search-wrap">
        <input type="text" id="apoio-search" placeholder="Buscar por nome ou palavra-chave..." />
        <button id="apoio-search-button" aria-label="Buscar">
            🔍
        </button>
    </div>

    <div class="apoio-tags">
        <button class="tag-button active" data-tag="all">Todos</button>
        <?php
        $terms = get_terms([
            'taxonomy' => 'tipo_apoio',
            'hide_empty' => false,
        ]);

        foreach ( $terms as $term ) {
            echo '<button class="tag-button" data-tag="' . esc_attr( $term->slug ) . '">' . esc_html( $term->name ) . '</button>';
        }
        ?>
    </div>
</section>