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

function dashboard_get_all_post_type_by_status(
    $post_type,
    $status,
    $use_custom_field = false
) {
    if( empty( $post_type ) ) return false;


    return [
        //'pagination' => ($total_posts > $limit),
        //'pagination_current' => $page,
        //'pagination_total' => $query->max_num_pages,

        //'posts' => $query->posts
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
