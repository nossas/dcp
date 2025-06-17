<?php

namespace hacklabr\dashboard;

define('DASHBOARD_ROUTING_VAR', 'ver');

function filter_get_custom_logo(string $logo_html) {
    if (is_dashboard()) {
        $url = get_dashboard_url();
        $logo_html = preg_replace('/href="[^\"]*"/', 'href="' . $url . '"', $logo_html);
    }
    return $logo_html;
}
add_filter('get_custom_logo', 'hacklabr\\dashboard\\filter_get_custom_logo');

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

function get_dashboard_base_url(): string {
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

function get_dashboard_content(): void {
    $route = get_dashboard_route();
    if (locate_template("template-parts/dashboard/{$route}.php")) {
        get_template_part('template-parts/dashboard/' . $route);
    } else {
        get_template_part('template-parts/dashboard/' . 'default');
    }
}

function get_dashboard_route(): string {
    return get_query_var(DASHBOARD_ROUTING_VAR, 'inicio');
}

function get_dashboard_title(): string {
    $route = get_dashboard_route();
    switch ($route) {
        case 'acoes':
            return esc_html_x('Actions', 'dashboard', 'hacklabr');
        case 'apoio':
            return esc_html_x('Support', 'dashboard', 'hacklabr');
        case 'indicadores':
            return esc_attr_x('Indicators', 'dashboard', 'hacklabr');
        case 'inicio':
            return esc_html_x('Start', 'dashboard', 'hacklabr');
        case 'riscos':
            return esc_html_x('Risks', 'dashboard', 'hacklabr');
        case 'situacao_atual':
            return esc_html_x('Current situation', 'dashboard', 'hacklabr');
        default:
            return esc_html__('Dashboard', 'hacklabr');
    }
}

function get_dashboard_url(string $route = 'inicio', array $params = []): string {
    $base_url = get_dashboard_base_url();
    $query_args = [
        ...$params,
        DASHBOARD_ROUTING_VAR => $route,
    ];
    return esc_url(add_query_arg($query_args, $base_url));
}

function is_dashboard(?string $route = null): bool {
    $is_dashboard = is_page_template('page-dashboard.php');
    if ($is_dashboard && $route) {
        return get_dashboard_route() === $route;
    }
    return $is_dashboard;
}
