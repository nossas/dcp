<?php
global $post;
$original_post = $post;
$post = $args['post'] ?? $post;

$image_size = $args['image_size'] ?? 'card-large';

$hide_author = (bool) ($args['hide_author'] ?? false);
$hide_categories = (bool) ($args['hide_categories'] ?? false);
$hide_date = (bool) ($args['hide_date'] ?? false);
$hide_excerpt = (bool) ($args['hide_excerpt'] ?? false);
$hide_hour = (bool) ($args['hide_hour'] ?? false);
$hide_date = (bool) ($args['hide_date'] ?? false);
$hide_address = (bool) ($args['hide_address'] ?? false);

$modifiers = (array) ($args['modifiers'] ?? []);
$modifiers = array_map(fn($modifier) => "post-card--{$modifier}", $modifiers);
$modifiers = implode(' ', $modifiers);

$categories = get_the_category();
?>
<article id="post-ID-<?php the_ID(); ?>" class="post-card <?= $modifiers ?>">
    <header class="post-card__image">
        <a href="<?php the_permalink(); ?>">
            <?php if (has_post_thumbnail()): ?>
                <?php the_post_thumbnail($image_size); ?>
            <?php else: ?>
                <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/placeholder.png" alt="">
            <?php endif; ?>
        </a>
    </header>

    <main class="post-card__content">
        <?php if (!$hide_categories && !empty($categories)): ?>
            <div class="post-card__category">
                <?php foreach ($categories as $category): ?>
                    <a class="tag tag--solid tag--category-<?= $category->slug ?>" href="<?= get_term_link($category, 'category') ?>">
                        <?= $category->name ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

        <div class="post-card__term">
            <?php
            $terms = get_the_terms(get_the_ID(), 'tipo_acao');

            if (!empty($terms) && !is_wp_error($terms)) {
                $term = $terms[0];
                $term_slug = esc_attr($term->slug);
                $term_name = esc_html($term->name);
                echo '<span class="post-card__taxonomia term-' . $term_slug . '">' . $term_name . '</span>';
            }
            ?>
        </div>

        <h3 class="post-card__title">
            <a href="<?php the_permalink(); ?>"><?php the_title(); ?></a>
        </h3>

        <?php if (!$hide_excerpt): ?>
            <div class="post-card__excerpt">
                <?= get_the_excerpt(); ?>
            </div>
        <?php endif; ?>

        <div class="post-card__divisor">
            <div class="post-card__divisor-line"></div>
        </div>

        <?php if (!$hide_author || !$hide_date): ?>
            <div class="post-card__meta">
                <?php if (!$hide_author): ?>
                    <div class="post-card__author">
                        <?php the_author(); ?>
                    </div>
                <?php endif; ?>

                <?php
                $pod = pods('acao', get_the_ID());
                if (!$hide_date && $pod) :
                    $data_raw = $pod->field('data');
                    $data = is_array($data_raw) ? reset($data_raw) : $data_raw;

                    $hora_raw = $pod->field('horario');
                    $hora = is_array($hora_raw) ? reset($hora_raw) : $hora_raw;

                    if (!empty($data) && !empty($hora)) {
                        $data_obj = DateTime::createFromFormat('Y-m-d', $data);
                        $hora_obj = DateTime::createFromFormat('H:i:s', $hora) ?: DateTime::createFromFormat('H:i', $hora);

                        if ($data_obj && $hora_obj) {
                            echo '<time class="post-card__datetime">Dia: ' . esc_html($data_obj->format('d/m/Y')) . ', ' . esc_html($hora_obj->format('H:i')) . '</time>';
                        }
                    }
                endif;
                ?>
            </div>
        <?php endif; ?>

        <?php if (!$hide_address): ?>
            <div class="post-card__address">
                <?php
                $pod = pods('acao', get_the_ID());
                $endereco_raw = $pod->field('endereco');

                if (!empty($endereco_raw)) {
                    echo esc_html($endereco_raw);
                }
                ?>
            </div>
        <?php endif; ?>
    </main>
</article>

<?php
$post = $original_post;
