<?php

/**
 * Template Name: Climate defense map
 * Description: Show risks and support spots on map
 */

namespace hacklabr;
$risks_page = get_page_by_path('reportar-riscos');

$jeo_maps = get_posts([
    'post_type' => 'map',
    'posts_per_page' => 1,
]);

if (empty($jeo_maps)) {
    return '';
}

$jeo_map = $jeo_maps[0];
assert($jeo_map instanceof \WP_Post);

$data = get_dcp_map_data();

$tab = sanitize_text_field($_GET['tab'] ?? '');
if (empty($tab)) {
    $tab = 'risco';
}

get_header();
?>

<div class="dcp-map" data-share-url="<?= get_permalink() ?>" x-data>
    <script type="application/json"><?= json_encode($data) ?></script>
    <div class="dcp-map__tabs-container">
        <div class="dcp-map__tabs" data-selected="risco">
            <button type="button" class="dcp-map__tab<?= $tab === 'risco' ? ' dcp-map__tab--selected' : '' ?>" data-cpt="risco">
                Riscos (<?= count($data['riscos']) ?>)
            </button>
            <button type="button" class="dcp-map__tab<?= $tab === 'apoio' ? ' dcp-map__tab--selected' : '' ?>" data-cpt="apoio">
                Apoio (<?= count($data['apoios']) ?>)
            </button>
        </div>
    </div>
    <div class="dcp-map__form-container">
      <form class="dcp-map__form">
    <iconify-icon id="icon-input" icon="bi:search"></iconify-icon>
        <input type="text" name="address" autocomplete="address-line1" placeholder="Busque sua localização">
        <button type="submit" aria-label="Buscar" id="search-btn">
            <iconify-icon id="icon-btn" icon="bi:plus" width="16"></iconify-icon>
            <span>Buscar</span>
        </button>
    </form>

    </div>
    <div class="dcp-map__buttons-container">
        <div class="dcp-map__buttons">
            <a class="dcp-map__add-risk" href="<?= get_permalink($risks_page) ?>">
                <iconify-icon icon="bi:geo-alt-fill"></iconify-icon>
                <span>Informar risco</span>
            </a>
            <button type="button" class="dcp-map__show-recommendations" @click="$refs.recommendations.showModal()">
                <span>O que fazer</span>
            </button>
        </div>
    </div>
    <div class="jeomap map_id_<?= $jeo_map->ID ?>"></div>
    <?php get_template_part('template-parts/dcp-map-legend') ?>
    <?php get_template_part('template-parts/dcp-map-modal') ?>
    <?php get_template_part('template-parts/recommendations') ?>
    <?php get_template_part('template-parts/dcp-map-welcome-modal'); ?>
</div>

<?php wp_footer(); ?>
<?php get_footer(); ?>
</div>
</body>
</html>
