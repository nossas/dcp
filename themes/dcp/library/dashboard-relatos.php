<?php

function get_relatos() {
    return [
        'pagination' => false,
        'posts' => new WP_Query([
            'post_type'      => 'relato',
            'post_status'    => [ 'draft', 'publish' ],
            'posts_per_page' => -1,
            'orderby'        => 'date',
            'order'          => 'DESC'
        ])
    ];
}
