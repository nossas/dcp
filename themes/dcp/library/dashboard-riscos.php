<?php

function get_posts_riscos( $args = [ 'post_status' => 'publish' ] ) {

//    $args['post_type'] = 'risco';
//    $args['posts_per_page'] = -1;
//    //$args['posts_per_page'] = 10;
//    $args['orderby'] = 'date';
//    $args['order'] = 'DESC';

    //$args['post_status'] = 'publish';
    //$args['post_status'] = 'draft';
    //$args['post_status'] = 'pending';
    //$args['post_status'] = 'future';
    //$args['post_status'] = 'private';

    wp_die( 'get_posts_riscos : DIE TO DEPRECATED' );
    //return new WP_Query( $args );
}

function get_riscos_by_status( $status, $page = 1, $limit = 6 ) {

    $query = new WP_Query([
        'post_type'      => 'risco',
        'post_status'    => $status,
        'posts_per_page' => $limit,
        'paged'          => $page,
        'orderby'        => 'date',
        'order'          => 'DESC'
    ]);

    $total_posts = $query->found_posts;

    return [
        'pagination' => ( $total_posts < $limit ) ? false : true,
        'pagination_current' => $page,
        'pagination_total' => $query->max_num_pages,
        'total_posts' => $total_posts,
        'posts' => $query
    ];
}

function get_dashboard_riscos( $page = 1, $limit = 6 ) {

    return [
        'riscosAprovacao' => get_riscos_by_status( 'draft', $page, $limit ),
        'riscosPublicados' => get_riscos_by_status( 'publish', $page, $limit ),
        'riscosArquivados' => get_riscos_by_status( 'pending', $page, $limit )
    ];

}
