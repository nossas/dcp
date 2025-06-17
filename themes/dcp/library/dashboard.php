<?php

namespace hacklabr\dashboard;

define('DASHBOARD_ROUTING_VAR', 'ver');

function filter_query_vars(array $query_vars): array {
    $query_vars[] = DASHBOARD_ROUTING_VAR;
    return $query_vars;
}
add_filter('query_vars', 'hacklabr\\dashboard\\filter_query_vars');

function filter_show_admin_bar(bool $show_bar): bool {
    if (is_dashboard()) {
        return false;
    }
    return $show_bar;
}
add_filter('show_admin_bar', 'hacklabr\\dashboard\\filter_show_admin_bar');

function get_dashboard_content(): void {
    $route = get_query_var(DASHBOARD_ROUTING_VAR);
    if (locate_template("template-parts/dashboard/{$route}.php")) {
        get_template_part('template-parts/dashboard/' . $route);
    } else {
        get_template_part('template-parts/dashboard/' . 'default');
    }
}

function get_dashboard_title(): string {
    return esc_html__('Dashboard', 'hacklabr');
}

function get_dashboard_home_url(): string {
    $pages = get_posts([
        'post_type' => 'page',
        'meta_key' => '_wp_page_template',
        'meta_value' => 'page-dashboard.php',
    ]);

    if (!empty($pages)) {
        return get_permalink($pages[0]);
    } else {
        return get_home_url();
    }
}

function get_dashboard_url(string $route = 'inicio', array $params = []): string {
    $base_url = get_dashboard_home_url();
    $query_args = [
        ...$params,
        DASHBOARD_ROUTING_VAR => $route,
    ];
    return esc_url(add_query_arg($query_args, $base_url));
}

function is_dashboard(?string $route = null): bool {
    $is_dashboard = is_page_template('page-dashboard.php');
    if ($is_dashboard && $route) {
        return get_query_var(DASHBOARD_ROUTING_VAR) === $route;
    }
    return $is_dashboard;
}
