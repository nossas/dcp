<?php

function dashboard_get_post_type_by_status_between_date(
    $post_type = 'risco',
    $status,
    $page = 1,
    $limit = 6,
    $date_init = '',
    $date_end = '',
    $use_custom_field = false
) {

    $args = [
        'post_type'      => $post_type,
        'post_status'    => $status,
        'posts_per_page' => $limit,
        'paged'          => $page,
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
        //'pagination' => ($total_posts > $limit),
        //'pagination_current' => $page,
        //'pagination_total' => $query->max_num_pages,
        'date_init' => $date_init,
        'date_end' => $date_end,
        'total_posts' => $total_posts,
        //'posts' => $query->posts
    ];
}

function indicadores_riscos( $page = 1, $limit = 5 )
{
    return [
        'aprovacao' => dashboard_get_post_type_by_status_between_date(
            'risco',
            'draft',
            $page,
            $limit,
            '2025-07-01',
            '2025-08-01'
        ),
        'publicados' => dashboard_get_post_type_by_status_between_date(
            'risco',
            'publish',
            $page, $limit,
            '2025-07-01',
            '2025-08-01'
        ),
        'arquivados' => dashboard_get_post_type_by_status_between_date(
            'risco',
            'pending',
            $page, $limit,
            '2025-07-01',
            '2025-08-01'
        )
    ];
}


function indicadores_acoes( $page = 1, $limit = 5 )
{
    return [
        'sugestoes' => dashboard_get_post_type_by_status_between_date(
            'acao',
            'draft',
            $page,
            $limit,
            '2025-07-01',
            '2025-08-01'
        ),
        'agendadas' => dashboard_get_post_type_by_status_between_date(
            'acao',
            'publish',
            $page, $limit,
            '2025-07-01',
            '2025-08-01'
        ),
        'realizadas' => dashboard_get_post_type_by_status_between_date(
            'acao',
            'private',
            $page, $limit,
            '2025-07-01',
            '2025-08-01'
        ),
        'arquivadas' => dashboard_get_post_type_by_status_between_date(
            'acao',
            'pending',
            $page, $limit,
            '2025-07-01',
            '2025-08-01'
        )
    ];
}

function indicadores_apoio( $page = 1, $limit = 5 )
{
    return [
        'sugestoes' => dashboard_get_post_type_by_status_between_date(
            'apoio',
            'publish',
            $page,
            $limit,
            '2025-07-01',
            '2025-08-01'
        )
    ];
}
