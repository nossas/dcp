<?php

namespace hacklabr;

function add_datetime_to_imported_post (int $post_id) {
    $post = get_post($post_id);
    assert($post instanceof \WP_Post);

    if ($post->post_type === 'apoio') {
        $datetime = get_post_meta($post_id, 'data_e_horario', true);

        if (empty($datetime)) {
            $datetime = date('Y-m-d H:i:s');
            update_post_meta($post_id, 'data_e_horario', $datetime);
        }
    }
}
add_action('wpie_after_post_import', 'hacklabr\\add_datetime_to_imported_post');
