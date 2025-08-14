<?php

function dashboard_get_post_type_by_status_between_date(
    $post_type,
    $status,
    $date_init = '',
    $date_end = '',
    $use_custom_field = false
) {
    if (empty($post_type)) {
        return ['error' => 'Post type não especificado'];
    }

    $args = [
        'post_type'      => $post_type,
        'post_status'    => $status,
        'posts_per_page' => -1,
        'fields'         => 'ids', // Otimização de performance
    ];

    if (!empty($date_init) && !empty($date_end)) {
        if ($use_custom_field) {
            // CONVERSÃO PARA DATETIME CORRETO
            $start_date = date('Y-m-d H:i:s', strtotime($date_init));
            $end_date = date('Y-m-d H:i:s', strtotime($date_end . ' 23:59:59'));

            $args['meta_query'] = [
                'relation' => 'AND',
                [
                    'key'     => 'data_e_horario',
                    'value'   => $start_date,
                    'compare' => '>=',
                    'type'    => 'DATETIME'
                ],
                [
                    'key'     => 'data_e_horario',
                    'value'   => $end_date,
                    'compare' => '<=',
                    'type'    => 'DATETIME'
                ]
            ];
        } else {
            $args['date_query'] = [
                [
                    'after'     => $date_init,
                    'before'    => $date_end,
                    'inclusive' => true,
                ]
            ];
        }
    }

    $query = new WP_Query($args);
    $total_posts = $query->found_posts;

    return [
        'date_init'   => $date_init,
        'date_end'    => $date_end,
        'total_posts' => $total_posts,
    ];
}

function dashboard_get_riscos_count_by_taxonomy(
    $taxonomy,
    $date_init = '',
    $date_end = '',
    $use_custom_field = false
) {
    $args = [
        'post_type'      => 'risco',
        //'post_status'    => ['draft', 'publish', 'pending'],
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'fields'         => 'ids', // Otimização: busca apenas IDs
        'tax_query'      => [
            [
                'taxonomy' => $taxonomy,
                'operator' => 'EXISTS'
            ]
        ]
    ];

    if (!empty($date_init) && !empty($date_end)) {
        if ($use_custom_field) {
            // CONVERSÃO PARA DATETIME CORRETO
            $start_date = date('Y-m-d H:i:s', strtotime($date_init));
            $end_date = date('Y-m-d H:i:s', strtotime($date_end . ' 23:59:59'));

            $args['meta_query'] = [
                'relation' => 'AND',
                [
                    'key'     => 'data_e_horario',
                    'value'   => $start_date,
                    'compare' => '>=',
                    'type'    => 'DATETIME'
                ],
                [
                    'key'     => 'data_e_horario',
                    'value'   => $end_date,
                    'compare' => '<=',
                    'type'    => 'DATETIME'
                ]
            ];
        } else {
            $args['date_query'] = [
                [
                    'after'     => $date_init,
                    'before'    => $date_end,
                    'inclusive' => true,
                ]
            ];
        }
    }

    $query = new WP_Query($args);
    $post_ids = $query->posts;

    if (empty($post_ids)) {
        return [
            'totais' => [],
            'termos' => [],
            'filtro_data' => [
                'inicio' => $date_init,
                'termino' => $date_end
            ]
        ];
    }

    // Obtém os termos para todos os posts encontrados
    $terms = wp_get_object_terms($post_ids, $taxonomy);

    // Agrupa contagem por termo
    $counts = [];
    foreach ($terms as $term) {
        if (!isset($counts[$term->term_id])) {
            $counts[$term->term_id] = [
                'count' => 0,
                'term'  => $term
            ];
        }
        $counts[$term->term_id]['count']++;
    }

    // Prepara o resultado final
    $result = [];
    foreach ($counts as $term_id => $data) {
        $result[$term_id] = [
            'count' => $data['count'],
            'name'  => $data['term']->name,
            'slug'  => $data['term']->slug
        ];
    }

    return [
        'totais' => $result,
        'termos' => $terms,
        'counts' => $counts,
        'filtro_data' => [
            'inicio' => $date_init,
            'termino' => $date_end
        ]
    ];
}

function dashboard_get_riscos_count_by_term(
    $term_slug,
    $taxonomy,
    $date_init = '',
    $date_end = '',
    $use_custom_field = false
) {
    // Validação do slug
    if (empty($term_slug) || !is_string($term_slug)) {
        return new WP_Error('invalid_term_slug', 'Slug do termo inválido');
    }

    // Configuração base da query
    $args = [
        'post_type'      => 'risco',
        //'post_status'    => ['draft', 'publish', 'pending'],
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'tax_query'      => [
            [
                'taxonomy' => $taxonomy,
                'field'    => 'slug',
                'terms'    => $term_slug
            ]
        ]
    ];

    if (!empty($date_init) && !empty($date_end)) {
        if ($use_custom_field) {
            // CONVERSÃO PARA DATETIME CORRETO
            $start_date = date('Y-m-d H:i:s', strtotime($date_init));
            $end_date = date('Y-m-d H:i:s', strtotime($date_end . ' 23:59:59'));

            $args['meta_query'] = [
                'relation' => 'AND',
                [
                    'key'     => 'data_e_horario',
                    'value'   => $start_date,
                    'compare' => '>=',
                    'type'    => 'DATETIME'
                ],
                [
                    'key'     => 'data_e_horario',
                    'value'   => $end_date,
                    'compare' => '<=',
                    'type'    => 'DATETIME'
                ]
            ];
        } else {
            $args['date_query'] = [
                [
                    'after'     => $date_init,
                    'before'    => $date_end,
                    'inclusive' => true,
                ]
            ];
        }
    }

    $query = new WP_Query($args);
    $total_posts = $query->found_posts;

    // Obtém informações completas do termo
    $term = get_term_by('slug', $term_slug, $taxonomy);

    return [
        'term' => $term,
        'total_posts' => $total_posts,
        'filtro_data' => [
            'inicio' => $date_init,
            'termino' => $date_end
        ]
    ];
}








function indicadores_riscos(
    $date_init = '2025-01-01',
    $date_end = '2025-12-31'
)
{
    return [
        'aprovacao' => dashboard_get_post_type_by_status_between_date(
            'risco',
            'draft',
            $date_init,
            $date_end
        ),
        'publicados' => dashboard_get_post_type_by_status_between_date(
            'risco',
            'publish',
            $date_init,
            $date_end
        ),
        'arquivados' => dashboard_get_post_type_by_status_between_date(
            'risco',
            'pending',
            $date_init,
            $date_end
        )
    ];
}
function indicadores_acoes( $date_init = '2025-01-01', $date_end = '2025-12-31' )
{
    return [
        'sugestoes' => dashboard_get_post_type_by_status_between_date(
            'acao',
            'draft',
            $date_init,
            $date_end
        ),
        'agendadas' => dashboard_get_post_type_by_status_between_date(
            'acao',
            'publish',
            $date_init,
            $date_end
        ),
        'realizadas' => dashboard_get_post_type_by_status_between_date(
            'acao',
            'private',
            $date_init,
            $date_end
        ),
        'arquivadas' => dashboard_get_post_type_by_status_between_date(
            'acao',
            'pending',
            $date_init,
            $date_end
        )
    ];
}
function indicadores_apoio( $date_init = '2025-01-01', $date_end = '2025-12-31' )
{
    return [
        'publicados' => dashboard_get_post_type_by_status_between_date(
            'apoio',
            'publish',
            $date_init,
            $date_end
        )
    ];
}
