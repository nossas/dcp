<?php

namespace hacklabr;

function geocode_imported_posts(int $meta_id, int $post_id, string $meta_key, mixed $meta_value) {
    $address_keys = ['endereco', 'full_address', 'latitude', 'longitude'];

    if (in_array($meta_key, $address_keys)) {
        $meta = get_post_meta($post_id);

        if (
            !empty($meta['latitude'][0])
            && !empty($meta['longitude'][0])
            && (empty($meta['endereco'][0]) || empty($meta['full_address'][0]))
        ) {
            $lat = format_coord_meta($post_id, 'latitude');
            $lon = format_coord_meta($post_id, 'longitude');

            $geocoder = API::get_geocoder();
            $data = $geocoder::reverse_geocode($lat, $lon);

            wp_update_post([
                'ID' => $post_id,
                'meta_input' => [
                    'endereco' => $data['address'],
                    'full_address' => $data['full_address'],
                ],
            ]);
        }
    }
}
add_action('added_post_meta', 'hacklabr\\geocode_imported_posts', 20, 4);
add_action('updated_post_meta', 'hacklabr\\geocode_imported_posts', 20, 4);

function get_default_boundaries() {
    /// @see https://maplibre.org/maplibre-gl-js/docs/API/type-aliases/LngLatBoundsLike/
    $jacarezinho = [[-43.265, -22.891], [-43.251, -22.885]];
    return apply_filters('dcp_default_bbox', $jacarezinho);
}

function get_default_coordinates () {
    $jacarezinho = [-43.2578789, -22.8875068];
    return apply_filters('dcp_default_coords', $jacarezinho);
}
