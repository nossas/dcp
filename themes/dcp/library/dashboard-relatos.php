<?php

function get_relatos( $page = 1, $limit = 6 ) {

    $query = new WP_Query([
        'post_type'      => 'relato',
        'post_status'    => [ 'draft', 'publish' ],
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
