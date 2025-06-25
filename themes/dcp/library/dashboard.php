<?php

namespace hacklabr\dashboard;

define('DASHBOARD_AGENT_ROLE', 'agente-dcp');
define('DASHBOARD_ROUTING_VAR', 'ver');

function action_init(): void {
    $option_key = 'role:' . DASHBOARD_AGENT_ROLE;

    if (empty(get_option($option_key))) {
        remove_role(DASHBOARD_AGENT_ROLE);

        $risk_caps = [
            'delete_risco' => true,
            'delete_riscos' => true,
            'delete_others_riscos' => true,
            'delete_private_riscos' => true,
            'delete_published_riscos' => true,
            'edit_risco' => true,
            'edit_riscos' => true,
            'edit_others_riscos' => true,
            'edit_private_riscos' => true,
            'edit_published_riscos' => true,
            'publish_riscos' => true,
            'read_risco' => true,
            'read_riscos' => true,
            'read_private_riscos' => true,
        ];

        add_role(DASHBOARD_AGENT_ROLE, __('Community agent', 'hacklabr'), array_merge($risk_caps, [
            'upload_files' => true,
            'view' => true,
        ]));

        $admin_role = get_role('administrator');
        foreach ( $risk_caps as $cap => $true ) {
            $admin_role->add_cap($cap);
        }

        update_option($option_key, '1');
    }

    add_rewrite_rule(
        '^dashboard/([^/]+)/?',
        'index.php?pagename=dashboard&' . DASHBOARD_ROUTING_VAR . '=$matches[1]',
        'top'
    );
}
add_action('init', 'hacklabr\\dashboard\\action_init');

function action_template_redirect(): void {
    if (is_dashboard() && !current_user_can('edit_riscos')) {
        wp_safe_redirect(get_home_url(), 302);
        exit;
    }
}
add_action('template_redirect', 'hacklabr\\dashboard\\action_template_redirect');

function filter_get_custom_logo(string $logo_html) {
    if (is_dashboard()) {
        $url = get_dashboard_url();
        $logo_html = preg_replace('/href="[^\"]*"/', 'href="' . $url . '"', $logo_html);
    }
    return $logo_html;
}
add_filter('get_custom_logo', 'hacklabr\\dashboard\\filter_get_custom_logo');

function filter_login_redirect(string $url, string $requested_url, \WP_User|\WP_Error $user): string {
    if ($user instanceof \WP_User) {
        if (in_array(DASHBOARD_AGENT_ROLE, $user->roles)) {
            return get_dashboard_url();
        }
    }
    return $url;
}
add_filter('login_redirect', 'hacklabr\\dashboard\\filter_login_redirect', 10, 3);

function filter_query_vars(array $query_vars): array {
    $query_vars[] = DASHBOARD_ROUTING_VAR;
    $query_vars[] = 'risco_id';
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
            return 'Ações';
        case 'apoio':
            return 'Apoio';
        case 'indicadores':
            return 'Indicadores';
        case 'inicio':
            return 'Início';
        case 'riscos':
            return 'Riscos';
        case 'situacao_atual':
            return 'Situação Atual';
        default:
            return esc_html__('Dashboard', 'hacklabr');
    }
}

function get_dashboard_url(string $route = 'inicio', array $params = []): string {
    $base_url = get_dashboard_base_url();
    $route_url = $base_url . (str_ends_with($base_url, '/') ? '' : '/') . $route;
    if (empty($params)) {
        return $route_url;
    } else {
        return esc_url(add_query_arg($params, $route_url));
    }
}

function is_dashboard(?string $route = null): bool {
    $is_dashboard = is_page_template('page-dashboard.php');
    if ($is_dashboard && $route) {
        return get_dashboard_route() === $route;
    }
    return $is_dashboard;
}
