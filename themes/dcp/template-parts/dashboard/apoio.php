<div class="apoio__container">
    <div class="apoio__header situacao-atual__header">
        <h1 class="apoio__title"><?= __('Apoio') ?></h1>
        <div class="apoio__btn">
            <div class="apoio__icon--add">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M9.00391 2.25195C9.15309 2.25195 9.29617 2.31122 9.40165 2.41671C9.50714 2.52219 9.56641 2.66527 9.56641 2.81445V8.43945H15.1914C15.3406 8.43945 15.4837 8.49872 15.5892 8.60421C15.6946 8.70969 15.7539 8.85277 15.7539 9.00195C15.7539 9.15114 15.6946 9.29421 15.5892 9.3997C15.4837 9.50519 15.3406 9.56445 15.1914 9.56445H9.56641V15.1895C9.56641 15.3386 9.50714 15.4817 9.40165 15.5872C9.29617 15.6927 9.15309 15.752 9.00391 15.752C8.85472 15.752 8.71165 15.6927 8.60616 15.5872C8.50067 15.4817 8.44141 15.3386 8.44141 15.1895V9.56445H2.81641C2.66722 9.56445 2.52415 9.50519 2.41866 9.3997C2.31317 9.29421 2.25391 9.15114 2.25391 9.00195C2.25391 8.85277 2.31317 8.70969 2.41866 8.60421C2.52415 8.49872 2.66722 8.43945 2.81641 8.43945H8.44141V2.81445C8.44141 2.66527 8.50067 2.52219 8.60616 2.41671C8.71165 2.31122 8.85472 2.25195 9.00391 2.25195V2.25195Z" fill="#F9F3EA" />
                </svg>
            </div>
            <a class="apoio__btn-wpp" href="">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M9.00391 2.25098C9.15309 2.25098 9.29617 2.31024 9.40165 2.41573C9.50714 2.52122 9.56641 2.66429 9.56641 2.81348V8.43848H15.1914C15.3406 8.43848 15.4837 8.49774 15.5892 8.60323C15.6946 8.70872 15.7539 8.85179 15.7539 9.00098C15.7539 9.15016 15.6946 9.29324 15.5892 9.39872C15.4837 9.50421 15.3406 9.56348 15.1914 9.56348H9.56641V15.1885C9.56641 15.3377 9.50714 15.4807 9.40165 15.5862C9.29617 15.6917 9.15309 15.751 9.00391 15.751C8.85472 15.751 8.71165 15.6917 8.60616 15.5862C8.50067 15.4807 8.44141 15.3377 8.44141 15.1885V9.56348H2.81641C2.66722 9.56348 2.52415 9.50421 2.41866 9.39872C2.31317 9.29324 2.25391 9.15016 2.25391 9.00098C2.25391 8.85179 2.31317 8.70872 2.41866 8.60323C2.52415 8.49774 2.66722 8.43848 2.81641 8.43848H8.44141V2.81348C8.44141 2.66429 8.50067 2.52122 8.60616 2.41573C8.71165 2.31024 8.85472 2.25098 9.00391 2.25098V2.25098Z" fill="#F9F3EA" />
                </svg>
                <?= __('Adicionar informações de apoio') ?>
            </a>
        </div>
    </div>

    <div class="apoio__content">
        <?php
        $taxonomia = 'tipo_apoio';
        $termo_selecionado = $_GET['tipo'] ?? '';
        $termos = get_terms([
            'taxonomy'   => $taxonomia,
            'hide_empty' => false,
            'parent'     => 0,
        ]);

        usort($termos, function ($a, $b) {
            if ($a->slug === 'locais-seguros') return -1;
            if ($b->slug === 'locais-seguros') return 1;
            return 0;
        });
        ?>

        <div class="apoio__tabs">
            <?php foreach ($termos as $termo):
                $ativo = ($termo_selecionado === $termo->slug || (!$termo_selecionado && $termo === reset($termos))) ? 'ativo' : '';
            ?>
                <a
                    href="?tipo=<?= esc_attr($termo->slug) ?>"
                    class="apoio__tab <?= $ativo ?>">
                    <?= esc_html($termo->name) ?>
                </a>
            <?php endforeach; ?>

            <?php
            $ativo_arquivados = ($termo_selecionado === 'arquivados') ? 'ativo' : '';
            ?>
            <a href="?tipo=arquivados" class="apoio__tab <?= $ativo_arquivados ?>">
                Arquivados
            </a>
        </div>

        <div class="apoio__grid">
            <div class="apoio__cards">
                <?php
                $query_args = [
                    'post_type'      => 'apoio',
                    'posts_per_page' => -1,
                ];

                if ($termo_selecionado === 'arquivados') {
                    $query_args['meta_query'][] = [
                        'key'     => 'apoio_arquivado',
                        'value'   => '1',
                        'compare' => '=',
                    ];
                } else {
                    $termo_valido = $termo_selecionado ?: ($termos[0]->slug ?? '');
                    $query_args['tax_query'][] = [
                        'taxonomy' => 'tipo_apoio',
                        'field'    => 'slug',
                        'terms'    => $termo_valido,
                    ];

                    $query_args['meta_query'][] = [
                        'key'     => 'apoio_arquivado',
                        'compare' => 'NOT EXISTS',
                    ];
                }

                $apoios_query = new WP_Query($query_args);

                if ($apoios_query->have_posts()) :
                    while ($apoios_query->have_posts()) : $apoios_query->the_post();
                        get_template_part('template-parts/post-card', 'vertical');
                    endwhile;
                    wp_reset_postdata();
                else :
                    echo '<p style="padding:1rem;">Nenhum item encontrado.</p>';
                endif;
                ?>
            </div>
        </div>
    </div>
    <div class="apoio__pagination">
        <?php
        the_posts_pagination([
            'prev_text' => __('<iconify-icon icon="iconamoon:arrow-left-2-bold"></iconify-icon>', 'hacklbr'),
            'next_text' => __('<iconify-icon icon="iconamoon:arrow-right-2-bold"></iconify-icon>', 'hacklbr'),

        ]); ?>
    </div>
</div>
