<?php

namespace hacklabr;

function format_risk_pin(\WP_Post $post): array {
    $latitude = get_post_meta($post->ID, 'latitude', true) ?: 0;
    $longitude = get_post_meta($post->ID, 'longitude', true) ?: 0;

    $types = wp_get_post_terms($post->ID, 'situacao_de_risco', [
        'fields' => 'slugs',
        'parent' => 0,
    ]);
    if (is_array($types)) {
        if (in_array('alagamento', $types)) {
            $type = 'alagamento';
        } elseif (in_array('lixo', $types)) {
            $type = 'lixo';
        } else {
            $type = 'outros';
        }
    } else {
        $type = 'outros';
    }

    return [
        'ID' => $post->ID,
        'title' => $post->post_title,
        'type' => $type,
        'date' => get_the_date('H:i | d/m/Y', $post),
        'excerpt' => get_the_excerpt($post),
        'lat' => $latitude,
        'lon' => $longitude,
    ];
}

function format_support_pin(\WP_Post $post): array {
    $latitude = get_post_meta($post->ID, 'latitude', true) ?: 0;
    $longitude = get_post_meta($post->ID, 'longitude', true) ?: 0;

    return [
        'ID' => $post->ID,
        'title' => $post->post_title,
        'excerpt' => get_the_excerpt($post),
        'endereco' => get_post_meta($post->ID, 'endereco', true),
        'lat' => $latitude,
        'lon' => $longitude,
    ];
}

function dcp_map_should_load_jeo(bool $should_load): bool {
    if (is_page_template('page-dcp-map.php')) {
        return true;
    } elseif (is_singular() && has_block('hacklabr/dcp-map')) {
        return true;
    }
    return $should_load;
}
add_filter('jeo_should_load_assets', 'hacklabr\\dcp_map_should_load_jeo');

function render_dcp_map_callback(array $attributes) {
    $jeo_maps = get_posts([
        'post_type' => 'map',
    ]);

    if (empty($jeo_maps)) {
        return '';
    }

    $jeo_map = $jeo_maps[0];
    assert($jeo_map instanceof \WP_Post);

    $data = [
        'riscos' => [],
        'apoios' => [],
    ];

    $risks = get_posts([
        'post_type' => 'risco',
        'posts_per_page' => -1,
    ]);
    $supports = get_posts([
        'post_type' => 'apoio',
        'posts_per_page' => -1,
    ]);

    foreach ($risks as $risk) {
        $data['riscos'][] = format_risk_pin($risk);
    }
    foreach ($supports as $support) {
        $data['apoios'][] = format_support_pin($support);
    }

    ob_start();
?>
    <div class="dcp-map-block" x-data>
        <script type="application/json"><?= json_encode($data) ?></script>
        <div class="dcp-map-block__tabs" data-selected="risco">
            <button type="button" class="dcp-map-block__tab dcp-map-block__tab--selected" data-cpt="risco">
                Riscos (<?= count($risks) ?>)
            </button>
            <button type="button" class="dcp-map-block__tab" data-cpt="apoio">
                Apoio (<?= count($supports) ?>)
            </button>
        </div>
        <div class="jeomap map_id_<?= $jeo_map->ID ?>"></div>
        <?php get_template_part('template-parts/dcp-map-modal') ?>
    </div>
<?php
    $html = ob_get_clean();
    return $html;
}

function localize_dcp_map_script() {
    wp_localize_script( 'hacklabr-dcp-map-script', 'dcp_map_data', [
        'themeAssets' => get_stylesheet_directory_uri(),
    ]);
}
add_action( 'wp_enqueue_scripts', 'hacklabr\\localize_dcp_map_script' );
