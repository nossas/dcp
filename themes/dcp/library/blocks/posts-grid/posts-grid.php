<?php

namespace hacklabr;

function get_posts_grid_data($attributes): \WP_Query
{
    $cached_query = get_block_transient('hacklabr/posts', $attributes);
    if ($cached_query !== false) {
        return $cached_query;
    }

    $post__not_in = get_used_post_ids();

    $query_args = build_posts_query($attributes, $post__not_in);
    $query = new \WP_Query($query_args);

    set_block_transient('hacklabr/posts', $attributes, $query);

    return $query;
}

function render_posts_grid_callback($attributes)
{
    $enableLoadMore = $attributes['enableLoadMore'] ?? false;

    if ($enableLoadMore) {
        $posts_per_row = $attributes['postsPerRow'] ?: 3;
        $query_attributes = normalize_posts_query($attributes);
        $query_attributes['postsPerPage'] = 6;

        delete_block_transient('hacklabr/posts', $query_attributes);
        $query = get_posts_grid_data($query_attributes);

        $is_disabled = ($query->found_posts <= 6) ? 'disabled' : '';

        unset($query_attributes['postsPerPage']);
        $query_attributes_json = htmlspecialchars(json_encode($query_attributes), ENT_QUOTES, 'UTF-8');

        ob_start();
        ?>
        <div class="hacklabr-posts-grid-wrapper">
            <div class="<?= build_class_list('hacklabr-posts-grid-block', $attributes) ?>" style="--grid-columns: <?= $posts_per_row ?>">
                <?php if (!empty($query->posts)):
                    foreach ($query->posts as $post):
                        mark_post_id_as_used($post->ID);
                        get_template_part('template-parts/post-card', null, ['post' => $post]);
                    endforeach;
                else: ?>
                    <p><?= __('Nenhum item encontrado para esta categoria.') ?></p>
                <?php endif; ?>
            </div>

            <div class="hacklabr-posts-grid__load-more-container">
                <button class="hacklabr-posts-grid__load-more-button" data-page="3" data-per-page="3" data-query-attributes='<?= $query_attributes_json ?>'  <?= $is_disabled ?>>
                    <span><?= __('Ver mais') ?></span>
                    <img src="<?= get_template_directory_uri(); ?>/assets/images/seta-card-disabled.svg" alt="Seta" class="saiba-mais-icon" style="width: 12px; height: auto;" />
                </button>
            </div>
        </div>
        <?php
        return ob_get_clean();
    }

    else {
        $card_model = $attributes['cardModel'];
        $card_modifiers = $attributes['cardModifiers'] ?: [];
        $hide_author = $attributes['hideAuthor'] ?: false;
        $hide_categories = $attributes['hideCategories'] ?: false;
        $hide_date = $attributes['hideDate'] ?: false;
        $hide_excerpt = $attributes['hideExcerpt'] ?: false;
        $posts_per_column = $attributes['postsPerColumn'] ?: 1;
        $posts_per_row = $attributes['postsPerRow'] ?: 1;

        $query_attributes = normalize_posts_query($attributes);
        $query_attributes['postsPerPage'] = $posts_per_column * $posts_per_row;
        delete_block_transient('hacklabr/posts', $query_attributes);
        $query = get_posts_grid_data($query_attributes);

        ob_start();
    ?>

        <div class="<?= build_class_list('hacklabr-posts-grid-block', $attributes) ?>" style="--grid-columns: <?= $posts_per_row ?>">
            <?php if (!empty($query->posts)) : ?>
                <?php foreach ($query->posts as $post):
                    mark_post_id_as_used($post->ID);
                    get_template_part('template-parts/post-card', $card_model ?: null, [
                        'hide_author' => $hide_author,
                        'hide_categories' => $hide_categories,
                        'hide_date' => $hide_date,
                        'hide_excerpt' => $hide_excerpt,
                        'modifiers' => $card_modifiers,
                        'post' => $post,
                    ]);
                endforeach; ?>
            <?php else: ?>

                <?php
                if (
                    isset($query_attributes['postType']) &&
                    $query_attributes['postType'] === 'risco'
                ) :
                ?>
                    <div class="empty-state empty-state--riscos">
                        <div class="empty-state__content">
                            <div class="empty-state__image">
                                <img src="<?= esc_url(get_template_directory_uri() . '/assets/images/wrapper70.png') ?>" alt="Nenhum risco cadastrado" />
                            </div>
                            <p><?= __('Nada nas últimas 24h.') ?> </p>
                            <p> <?= __('Viu lixo ou alagamento na rua?') ?> </p>
                            <a href="/reportar-riscos" class="button">
                                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none" aria-hidden="true" focusable="false">
                                    <path d="M9 18.001C9 18.001 15.75 11.6042 15.75 6.75098C15.75 4.96077 15.0388 3.24388 13.773 1.97801C12.5071 0.712135 10.7902 0.000976562 9 0.000976562C7.20979 0.000976562 5.4929 0.712135 4.22703 1.97801C2.96116 3.24388 2.25 4.96077 2.25 6.75098C2.25 11.6042 9 18.001 9 18.001ZM9 10.126C8.10489 10.126 7.24645 9.7704 6.61351 9.13746C5.98058 8.50453 5.625 7.64608 5.625 6.75098C5.625 5.85587 5.98058 4.99743 6.61351 4.36449C7.24645 3.73156 8.10489 3.37598 9 3.37598C9.89511 3.37598 10.7536 3.73156 11.3865 4.36449C12.0194 4.99743 12.375 5.85587 12.375 6.75098C12.375 7.64608 12.0194 8.50453 11.3865 9.13746C10.7536 9.7704 9.89511 10.126 9 10.126Z" fill="#F9F3EA" />
                                </svg>
                                <?= __('Adicionar risco') ?>
                            </a>
                        </div>
                    </div>
                <?php endif; ?>

            <?php endif; ?>
        </div>

    <?php
        $output = ob_get_clean();

        return $output;
    }
}
