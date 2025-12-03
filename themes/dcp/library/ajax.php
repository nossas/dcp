<?php

namespace hacklabr;

add_action('wp_ajax_load_more_posts', __NAMESPACE__ . '\\hacklabr_load_more_posts_ajax_handler');
add_action('wp_ajax_nopriv_load_more_posts', __NAMESPACE__ . '\\hacklabr_load_more_posts_ajax_handler'); // Para usuários não logados

function hacklabr_load_more_posts_ajax_handler() {
    $log_file = WP_CONTENT_DIR . '/ajax-debug.log';
    $debug_info = "--- AJAX Handler Executado em " . date('Y-m-d H:i:s') . " ---\n";
    $debug_info .= "Recebido Page: " . (isset($_POST['page']) ? $_POST['page'] : 'N/A') . "\n";
    $debug_info .= "Recebido Query Attributes: " . (isset($_POST['query_attributes']) ? stripslashes($_POST['query_attributes']) : 'N/A') . "\n";

    $query_args_from_js = json_decode(stripslashes($_POST['query_attributes']), true);
    $page = intval($_POST['page']);
    $posts_per_page = intval($_POST['per_page']);

    $query_args = build_posts_query($query_args_from_js, []);

    $query_args['posts_per_page'] = $posts_per_page;
    $query_args['post_status'] = 'publish';
    $query_args['paged'] = $page;
    $query_args['ignore_sticky_posts'] = 1;
    unset($query_args['offset']);

    $query = new \WP_Query($query_args);

    $debug_info .= "Query SQL Gerada: " . $query->request . "\n";
    $debug_info .= "Posts Encontrados nesta página: " . $query->post_count . "\n";
    file_put_contents($log_file, $debug_info, FILE_APPEND);

    ob_start();

    if ($query->have_posts()) {
        while ($query->have_posts()) {
            $query->the_post();
            get_template_part('template-parts/post-card', null, [
                'post' => get_post(),
            ]);
        }
    }

    $html = ob_get_clean();
    $max_pages = $query->max_num_pages;
    $more_posts_available = $page < $max_pages;

    wp_send_json_success([
        'html' => $html,
        'more_posts_available' => $more_posts_available
    ]);

    wp_die();
}
