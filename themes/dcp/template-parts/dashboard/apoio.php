<div class="apoio__container">
    <div class="apoio__header situacao-atual__header">
        <h1 class="apoio__title"><?= __('Apoio') ?></h1>
        <div class="apoio__btn">
            <div class="apoio__icon--add">
                <!-- SVG -->
            </div>
            <a class="apoio__btn-wpp" href="">
                <!-- SVG -->
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
            <?php
            $classes_cards = 'apoio__cards';

            if ($termo_selecionado === 'arquivados') {
                $classes_cards .= ' apoio__cards--arquivados';
            } else {
                $termo_slug = $termo_selecionado ?: ($termos[0]->slug ?? '');
                if ($termo_slug) {
                    $classes_cards .= ' apoio__cards--' . sanitize_html_class($termo_slug);
                }
            }
            ?>
            <div class="<?= esc_attr($classes_cards) ?>">
                <?php
                if ($termo_selecionado === 'quem-acionar') :
                    $subtermos = get_terms([
                        'taxonomy'   => 'tipo_apoio',
                        'hide_empty' => false,
                        'parent'     => get_term_by('slug', 'quem-acionar', 'tipo_apoio')->term_id,
                    ]);

                    if (!empty($subtermos)) :
                        foreach ($subtermos as $subtermo) :
                            $subtermo_nome = esc_html($subtermo->name);
                            $subtermo_slug = $subtermo->slug;

                            $query_args = [
                                'post_type'      => 'apoio',
                                'posts_per_page' => -1,
                                'tax_query'      => [[
                                    'taxonomy' => 'tipo_apoio',
                                    'field'    => 'slug',
                                    'terms'    => $subtermo_slug,
                                ]],
                                'meta_query'     => [[
                                    'key'     => 'apoio_arquivado',
                                    'compare' => 'NOT EXISTS',
                                ]],
                            ];

                            $sub_query = new WP_Query($query_args);

                            if ($sub_query->have_posts()) :
                                echo '<div class="apoio__grupo">';
                                echo '<h3 class="apoio__grupo-title">' . $subtermo_nome . '</h3>';
                                echo '<div class="apoio__cards">';

                                while ($sub_query->have_posts()) : $sub_query->the_post();
                                    get_template_part('template-parts/post-card', 'vertical');
                                endwhile;

                                echo '</div></div>';
                            endif;

                            wp_reset_postdata();
                        endforeach;
                    endif;

                else :
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

                endif;
                ?>
            </div>
        </div>
    </div>
</div>