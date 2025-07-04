<?php

namespace hacklabr;

function add_custom_post_status_acao() {
    $custom_status = [
        'acao-agendada' => 'Ação Agendada',
        'acao-relatada' => 'Ação Relatada',
    ];
    foreach ( $custom_status as $status_slug => $status_label ) {
        register_post_status( $status_slug, array(
            'label'                     => _x( $status_label, 'post' ),
            'public'                    => true,
            'post_type'                 => 'acao',
            'exclude_from_search'       => false,
            'show_in_admin_all_list'    => true,
            'show_in_admin_status_list' => true,
            'label_count'               => _n_noop("$status_label <span class='count'>(%s)</span>", "$status_label <span class='count'>(%s)</span>"),
        ));
    }
}
add_action('init', 'hacklabr\\add_custom_post_status_acao' );
