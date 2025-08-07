<?php

namespace hacklabr;

class API {

    static function init () {
        add_action('rest_api_init', [self::class, 'register_routes']);
    }

    static function register_routes() {
        register_rest_route('hacklabr/v2', '/block_settings', [
            'methods' => 'GET',
            'callback' => 'hacklabr\API::rest_block_settings_callback',
            'permission_callback' => 'hacklabr\API::rest_permission_to_edit_posts',
        ]);

        register_rest_route('hacklabr/v2', '/options', [
            'methods' => 'GET',
            'callback' => 'hacklabr\API::rest_options_callback',
            'permission_callback' => 'hacklabr\API::rest_permission_to_edit_posts',
        ]);

        register_rest_route('hacklabr/v2', '/post_types', [
            'methods' => 'GET',
            'callback' => 'hacklabr\API::rest_post_types_callback',
            'permission_callback' => '__return_true',
        ]);

        register_rest_route('hacklabr/v2', '/taxonomies/(?P<post_type>[a-zA-Z0-9_-]+)', [
            'methods' => 'GET',
            'callback' => 'hacklabr\API::rest_taxonomies_callback',
            'args' => [
                'post_type' => [
                    'required' => true,
                    'validate_callback' => function($param, $request, $key) {
                        return post_type_exists(sanitize_text_field($param));
                    },
                ],
            ],
            'permission_callback' => '__return_true',
        ]);

        register_rest_route('hacklabr/v2', '/terms/(?P<taxonomy>[a-zA-Z0-9_-]+)', [
            'methods' => 'GET',
            'callback' => 'hacklabr\API::rest_terms_callback',
            'args' => [
                'search' => [
                    'type' => 'string',
                    'default' => '',
                ],
                'taxonomy' => [
                    'required' => true,
                    'validate_callback' => function($param, $request, $key) {
                        return taxonomy_exists(sanitize_text_field($param));
                    },
                ],
            ],
            'permission_callback' => '__return_true',
        ]);

        register_rest_route('hacklabr/v2', '/geocoding', [
            'methods' => ['GET', 'POST'],
            'callback' => 'hacklabr\API::rest_geocoding_callback',
            'args' => [
                'address' => [
                    'type' => 'string',
                    'required' => true,
                ],
            ],
            // 'permission_callback' => 'hacklabr\API::rest_permission_to_edit_riscos',
            'permission_callback' => '__return_true',
        ]);
    }

    static function send_html ($html) {
        header('Content-Type: text/html');
        echo $html;
        exit();
    }

    static function rest_permission_to_edit_posts () {
        return current_user_can('edit_posts');
    }

    static function rest_permission_to_edit_riscos () {
        return current_user_can('edit_riscos');
    }

    static function rest_block_settings_callback () {
        $response = [
            'cardModels' => [
                'options' => get_card_models(),
                'default' => get_default_card_model(),
            ],
            'cardModifiers' => [
                'options' => get_card_modifiers(),
            ],
        ];

        return new \WP_REST_Response($response, 200);
    }

    static function rest_geocoding_callback (\WP_REST_Request $request) {

        $address = $request->get_param('address');
        $params = [
            'q' => $address,
            'format' => 'jsonv2',
            'addressdetails' => 1,
            'countrycodes' => 'br',
            'viewbox' => apply_filters('dcp_geocoding_viewbox', '-43.27,-22.87,-43.23,-22.91'),
            'bounded' => '1',
        ];

        $url = 'https://nominatim.openstreetmap.org/search?' . http_build_query($params);
        $ch = curl_init();
        curl_setopt_array($ch, [
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_USERAGENT => 'Mozilla/5.0 (compatible; YourApp/1.0)',
            CURLOPT_SSL_VERIFYPEER => true,
            CURLOPT_TIMEOUT => 30,
        ]);

        $response = curl_exec($ch);
        $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $curl_error = curl_error($ch);
        curl_close($ch);

        if ($response === false || $http_code !== 200) {
            $error_message = $curl_error ?: "HTTP Error: {$http_code}";
            do_action('logger', $error_message, 'warning');
            return null;
        }

        do_action('logger', [
            'response' => $response,
            'http_code' => $http_code
        ]);

        $data = json_decode($response);

        if (is_array($data)) {
            foreach ($data as $match) {
                return [
                    'lat' => floatval($match->lat),
                    'lon' => floatval($match->lon),
                    'address' => $match->display_name,
                ];
            }
        }

        return null;
    }

    static function rest_options_callback () {
        $response = [
            'youtubeKey' => get_option('youtube_key', ''),
        ];

        return new \WP_REST_Response($response, 200);
    }

    static function rest_post_types_callback () {
        $args = [
            'public' => true,
            'show_in_rest' => true,
        ];

        $post_types = get_post_types($args, 'objects');

        $response = [];

        foreach ($post_types as $post_type) {
            $item = [
                'hierarchical' => $post_type->hierarchical,
                'icon' => $post_type->menu_icon,
                'label' => $post_type->label,
                'slug' => $post_type->name,
            ];

            if ($post_type->rest_namespace && $post_type->rest_base) {
                $item['rest_endpoint'] = $post_type->rest_namespace . '/' . $post_type->rest_base;
            } else {
                $item['rest_endpoint'] = null;
            }

            $response[$post_type->name] = $item;
        }

        return new \WP_REST_Response($response, 200);
    }

    static function rest_taxonomies_callback (\WP_REST_Request $request) {
        $post_type = sanitize_text_field($request->get_param('post_type'));

        $taxonomies = get_object_taxonomies($post_type, 'objects');

        $response = [];

        foreach ($taxonomies as $taxonomy) {
            if ($taxonomy->show_in_rest) {
                $item = [
                    'hierarchical' => $taxonomy->hierarchical,
                    'label' => $taxonomy->label,
                    'slug' => $taxonomy->name,
                ];

                if ($taxonomy->rest_namespace && $taxonomy->rest_base) {
                    $item['rest_endpoint'] = $taxonomy->rest_namespace . '/' . $taxonomy->rest_base;
                } else {
                    $item['rest_endpoint'] = null;
                }

                $response[$taxonomy->name] = $item;
            }
        }

        return new \WP_REST_Response($response, 200);
    }

    static function rest_terms_callback (\WP_REST_Request $request) {
        $taxonomy = sanitize_text_field($request->get_param('taxonomy'));
        $search = sanitize_text_field($request->get_param('search'));

        $args = [
            'hide_empty' => true,
            'number' => 0,
            'taxonomy' => $taxonomy,
        ];

        if (!empty($search)) {
            $args['search'] = $search;
        }

        $terms = get_terms($args);

        $response = [];

        foreach ($terms as $term) {
            $item = [
                'id' => $term->term_id,
                'parent' => $term->parent,
                'slug' => $term->slug,
                'name' => $term->name,
            ];

            $response[$term->slug] = $item;
        }

        return new \WP_REST_Response($response, 200);
    }
}

API::init();
