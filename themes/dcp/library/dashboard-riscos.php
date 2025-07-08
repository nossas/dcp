<?php


function get_posts_riscos( $args = [ 'post_status' => 'publish' ] ) {

    $args['post_type'] = 'risco';
    $args['posts_per_page'] = -1;
    //$args['posts_per_page'] = 10;
    $args['orderby'] = 'date';
    $args['order'] = 'DESC';

    //$args['post_status'] = 'publish';
    //$args['post_status'] = 'draft';
    //$args['post_status'] = 'pending';
    //$args['post_status'] = 'future';
    //$args['post_status'] = 'private';

    return new WP_Query( $args );
}

function get_dashboard_riscos() {

    return [
        'riscosAprovacao' => [
            'is_active' => true,
            'pagination' => false,
            'riscos' => new WP_Query([
                'post_type'      => 'risco',
                'post_status'    => 'draft',
                'posts_per_page' => -1,
                'orderby'        => 'date',
                'order'          => 'DESC'
            ])
        ],
        'riscosPublicados' =>[
            'is_active' => false,
            'pagination' => false,
            'riscos' => new WP_Query([
                'post_type'      => 'risco',
                'post_status'    => 'publish',
                'posts_per_page' => -1,
                'orderby'        => 'date',
                'order'          => 'DESC'
            ])
        ],
        'riscosArquivados' => [
            'is_active' => false,
            'pagination' => false,
            'riscos' => new WP_Query([
                'post_type'      => 'risco',
                'post_status'    => 'pending',
                'posts_per_page' => -1,
                'orderby'        => 'date',
                'order'          => 'DESC'
            ])
        ],
    ];

}

function get_riscos_by_status( $status ) {
    return [
        'pagination' => false,
        'riscos' => new WP_Query([
            'post_type'      => 'risco',
            'post_status'    => $status,
            'posts_per_page' => -1,
            'orderby'        => 'date',
            'order'          => 'DESC'
        ])
    ];
}
