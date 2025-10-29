<?php

namespace hacklabr;

function format_coord_meta(int $post_id, string $meta_key): float {
    $meta_value = get_post_meta($post_id, $meta_key, true) ?: 0.0;
    if (is_string($meta_value)) {
        return floatval(str_replace(',', '.', $meta_value));
    } else {
        return floatval($meta_value);
    }
}

function get_pin_attachments(\WP_Post $post): array {
    $media = [];

    $attachments = get_attached_media('', $post->ID);

    foreach ($attachments as $attachment) {
        $attachment_id = $attachment->ID;
        $metadata = wp_get_attachment_metadata($attachment_id);

        $is_vertical = !empty($metadata['height']) &&
                       !empty($metadata['width']) &&
                       $metadata['height'] > $metadata['width'];

        $media[] = [
            'id'   => $attachment_id,
            'src'  => wp_get_attachment_url($attachment_id),
            'mime' => $attachment->post_mime_type,
            'custom_fields' => [
                'orientation' => $is_vertical ? 'vertical' : 'horizontal',
            ],
        ];
    }

    return $media;
}

function format_risk_pin(\WP_Post $post): array {
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
        'media' => get_pin_attachments($post),
        'lat' => format_coord_meta($post->ID, 'latitude'),
        'lon' => format_coord_meta($post->ID, 'longitude'),
    ];
}
function format_support_pin(\WP_Post $post): array {
    $types = wp_get_post_terms($post->ID, 'tipo_apoio', [
        'fields' => 'slugs',
        'parent' => 0,
    ]);
    if (is_array($types) && in_array('cacambas', $types)) {
        $type = 'cacamba';
    } elseif ( is_array($types) && in_array('iniciativas-locais', $types ) ) {
        $type = 'iniciativas-locais';
    } else {
        $type = 'apoio';
    }

    return [
        'ID' => $post->ID,
        'title' => $post->post_title,
        'type' => $type,
        'excerpt' => get_the_excerpt($post),
        'endereco' => get_post_meta($post->ID, 'endereco', true),
        'horario' => implode('; ', get_post_meta($post->ID, 'horario_de_atendimento')),
        'media' => get_pin_attachments($post),
        'lat' => format_coord_meta($post->ID, 'latitude'),
        'lon' => format_coord_meta($post->ID, 'longitude'),
    ];
}

function dcp_map_should_load_jeo(bool $should_load): bool {
    if (is_page_template('page-dcp-map.php') || is_page_template('template-parts/page-register-risk.php')) {
        return true;
    } elseif (is_singular() && has_block('hacklabr/dcp-map')) {
        return true;
    }
    return $should_load;
}
add_filter('jeo_should_load_assets', 'hacklabr\\dcp_map_should_load_jeo');

function get_dcp_map_data(): array {
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
        'tax_query' => [
            [
                'taxonomy' => 'tipo_apoio',
                'field'    => 'slug',
                'terms'    => 'quem-acionar',
                'operator' => 'NOT IN',
            ]
        ],
    ]);

    foreach ($risks as $risk) {
        $data['riscos'][] = format_risk_pin($risk);
    }
    foreach ($supports as $support) {
        $data['apoios'][] = format_support_pin($support);
    }

    return $data;
}

function render_dcp_map_callback(array $attributes) {
    $maps_page = get_page_by_template('page-dcp-map.php');
    $risks_page = get_page_by_path('informar-riscos');

    $jeo_maps = get_posts([
        'post_type' => 'map',
        'posts_per_page' => 1,
    ]);

    if (empty($jeo_maps)) {
        return '';
    }

    $jeo_map = $jeo_maps[0];
    assert($jeo_map instanceof \WP_Post);
    $tab = is_page('conteudo-sobre-o-lixo') ? 'apoio' : 'risco';

    $data = get_dcp_map_data();

    ob_start();
?>
    <div class="dcp-map-block" :class="`dcp-map--${tab}`" data-share-url="<?= get_permalink($maps_page) ?>" x-data="{ tab: '<?= $tab ?>' }">
        <script type="application/json"><?= json_encode($data) ?></script>
        <div class="dcp-map-block__tabs" data-selected="<?= $tab ?>">
            <button type="button" class="dcp-map-block__tab <?= ($tab === 'risco') ? 'dcp-map-block__tab--selected' : '' ?>" data-cpt="risco" role="tab" aria-selected="<?= ($tab === 'risco') ? 'true' : 'false' ?>" @click="tab = 'risco'">
                Riscos (<?= count($data['riscos']) ?>)
            </button>
            <button type="button" class="dcp-map-block__tab <?= ($tab === 'apoio') ? 'dcp-map-block__tab--selected' : '' ?>" data-cpt="apoio" role="tab" aria-selected="<?= ($tab === 'apoio') ? 'true' : 'false' ?>" @click="tab = 'apoio'">
                Apoio (<?= count($data['apoios']) ?>)
            </button>
        </div>
        <div class="dcp-map-block__buttons">
            <a class="dcp-map-block__add-risk" href="<?= get_permalink($risks_page) ?>">
                <iconify-icon icon="bi:geo-alt-fill"></iconify-icon>
                <span>Informar risco</span>
            </a>
            <a class="dcp-map-block__open-map" href="<?= get_permalink($maps_page) ?>">
                <span>Abrir</span>
                <iconify-icon icon="bi:chevron-right"></iconify-icon>
            </a>
        </div>
        <div class="jeomap map_id_<?= $jeo_map->ID ?>"></div>
        <?php get_template_part('template-parts/dcp-map-legend') ?>
        <?php get_template_part('template-parts/dcp-map-modal') ?>
    </div>
<?php
    $html = ob_get_clean();
    return $html;
}

function localize_dcp_map_script() {
    wp_localize_script( 'hacklabr-dcp-map-script', 'hl_dcp_map_data', [
        'themeAssets' => get_stylesheet_directory_uri(),
    ]);
}
add_action( 'wp_enqueue_scripts', 'hacklabr\\localize_dcp_map_script' );
