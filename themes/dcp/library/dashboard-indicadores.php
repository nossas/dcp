<?php

function dashboard_get_post_type_by_status_between_date(
    $post_type,
    $status,
    $date_init = '',
    $date_end = '',
    $use_custom_field = false
) {
    if( empty( $post_type ) ) return [ 'não encontrado' ];

    $args = [
        'post_type'      => $post_type,
        'post_status'    => $status,
        'posts_per_page' => -1,
        //'paged'          => $page,
        //'orderby'        => 'date',
        //'order'          => 'DESC'
    ];

    if (!empty($date_init) && !empty($date_end)) {
        if ($use_custom_field) {
            $args['meta_query'] = [
                [
                    'key'     => 'data_e_horario',
                    'value'   => [$date_init, $date_end],
                    'compare' => 'BETWEEN',
                    'type'    => 'DATETIME'
                ]
            ];
        } else {
            $args['date_query'] = [
                [
                    'after'     => $date_init,
                    'before'    => $date_end,
                    'inclusive' => true,
                    'column'    => 'post_date'
                ]
            ];
        }
    }

    $query = new WP_Query($args);
    $total_posts = $query->found_posts;

    return [
        'date_init' => $date_init,
        'date_end' => $date_end,
        'total_posts' => $total_posts,
    ];
}


function dashboard_get_riscos_count_by_taxonomy($taxonomy, $data_init = '', $date_end = '', $use_custom_field = false) {
    // Valores padrão para datas
    $data_init = !empty($data_init) ? $data_init : date('Y-m-01');
    $date_end = !empty($date_end) ? $date_end : date('Y-m-d');

    // Configuração base da query
    $args = [
        'post_type'      => 'risco',
        'post_status'    => ['draft', 'publish', 'pending'],
        'posts_per_page' => -1,
        'fields'         => 'ids', // Otimização: busca apenas IDs
        'tax_query'      => [
            [
                'taxonomy' => $taxonomy,
                'operator' => 'EXISTS'
            ]
        ]
    ];

    // Adiciona filtro de data
    if ($use_custom_field) {
        $args['meta_query'] = [
            [
                'key'     => 'data_e_horario',
                'value'   => [$data_init, $date_end],
                'compare' => 'BETWEEN',
                'type'    => 'DATETIME'
            ]
        ];
    } else {
        $args['date_query'] = [
            [
                'after'     => $data_init,
                'before'    => $date_end,
                'inclusive' => true
            ]
        ];
    }

    // Obtém todos os posts IDs que atendem aos critérios
    $query = new WP_Query($args);
    $post_ids = $query->posts;

    // Se não houver posts, retorna vazio
    if (empty($post_ids)) {
        return [
            'totais' => [],
            'termos' => [],
            'filtro_data' => [
                'inicio' => $data_init,
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
        'filtro_data' => [
            'inicio' => $data_init,
            'termino' => $date_end
        ]
    ];
}

function indicadores_riscos( $date_init = '2025-01-01', $date_end = '2025-12-31' )
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
