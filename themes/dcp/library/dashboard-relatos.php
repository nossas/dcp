<?php

function get_relatos_by_status() {
    return [
        'pagination' => false,
        'posts' => new WP_Query([
            'post_type'      => 'relato',
            'post_status'    => 'publish',
            'posts_per_page' => -1,
            'orderby'        => 'date',
            'order'          => 'DESC'
        ])
    ];
}
