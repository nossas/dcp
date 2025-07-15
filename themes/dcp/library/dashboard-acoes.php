<?php


function get_acoes_by_status( $status ) {
    return [
        'pagination' => false,
        'posts' => new WP_Query([
            'post_type'      => 'acao',
            'post_status'    => $status,
            'posts_per_page' => -1,
            'orderby'        => 'date',
            'order'          => 'DESC'
        ])
    ];
}



