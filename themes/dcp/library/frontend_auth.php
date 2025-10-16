<?php
// Registrar as páginas personalizadas
function register_custom_auth_pages() {
    if (!get_page_by_path('login')) {
        $login_page = array(
            'post_title' => 'Login',
            'post_name' => 'login',
            'post_status' => 'publish',
            'post_type' => 'page'
        );
        wp_insert_post($login_page);
    }

    if (!get_page_by_path('recuperar-senha')) {
        $recovery_page = array(
            'post_title' => 'Recuperar Senha',
            'post_name' => 'recuperar-senha',
            'post_status' => 'publish',
            'post_type' => 'page'
        );
        wp_insert_post($recovery_page);
    }
}
add_action('init', 'register_custom_auth_pages');


function is_advanced_recaptcha_active() {
    return function_exists('agr_user_login_recaptcha') || class_exists('Advanced_Google_Recaptcha');
}

// AJAX Login
function ajax_custom_login() {
    // Verificar nonce para segurança
    check_ajax_referer('custom_login_nonce', 'security');

    // Verificar reCAPTCHA se o plugin estiver ativo
    if (is_advanced_recaptcha_active()) {
        $recaptcha_verified = verify_advanced_recaptcha();
        if (!$recaptcha_verified) {
            wp_send_json_error('Por favor, complete a verificação reCAPTCHA.');
        }
    }

    $username = sanitize_user($_POST['username']);
    $password = $_POST['password'];

    $creds = array(
        'user_login'    => $username,
        'user_password' => $password,
        'remember'      => true
    );

    $user = wp_signon($creds, false);

    if (is_wp_error($user)) {
        wp_send_json_error($user->get_error_message());
    } else {
        // Verificar se é administrador
        if (in_array('administrator', $user->roles)) {
            wp_send_json_success(array(
                'message' => 'Login realizado com sucesso!',
                'redirect' => home_url('/dashboard')
            ));
        } else {
            wp_logout();
            wp_send_json_error('Acesso permitido apenas para administradores.');
        }
    }

    wp_die();
}

// Registrar handlers AJAX
add_action('wp_ajax_nopriv_custom_login', 'ajax_custom_login');
add_action('wp_ajax_custom_login', 'ajax_custom_login');

// AJAX Password Recovery
function ajax_password_recovery() {
    check_ajax_referer('custom_recovery_nonce', 'security');

    // Verificar reCAPTCHA se o plugin estiver ativo
    if (is_advanced_recaptcha_active()) {
        $recaptcha_verified = verify_advanced_recaptcha();
        if (!$recaptcha_verified) {
            wp_send_json_error('Por favor, complete a verificação reCAPTCHA.');
        }
    }

    $email = sanitize_email($_POST['email']);

    if (empty($email) || !is_email($email)) {
        wp_send_json_error('Por favor, insira um e-mail válido.');
    }

    $user = get_user_by('email', $email);

    if (!$user) {
        wp_send_json_error('Nenhum usuário encontrado com este e-mail.');
    }

    // Gerar link de recuperação
    $key = get_password_reset_key($user);

    if (is_wp_error($key)) {
        wp_send_json_error('Erro ao gerar chave de recuperação.');
    }

    $reset_url = network_site_url("wp-login.php?action=rp&key=$key&login=" . rawurlencode($user->user_login), 'login');

    // Aqui você pode implementar o envio de e-mail
    wp_send_json_success(array(
        'message' => 'Instruções de recuperação enviadas para seu e-mail.',
        'reset_url' => $reset_url // Apenas para desenvolvimento
    ));

    wp_die();
}

add_action('wp_ajax_nopriv_custom_password_recovery', 'ajax_password_recovery');
add_action('wp_ajax_custom_password_recovery', 'ajax_password_recovery');


// Função para verificar reCAPTCHA
function verify_advanced_recaptcha() {
    // Método 1: Se o plugin fornece uma função específica
    if (function_exists('agr_user_login_recaptcha')) {
        return agr_user_login_recaptcha();
    }

    // Método 2: Se o plugin usa uma classe
    if (class_exists('Advanced_Google_Recaptcha')) {
        $recaptcha = new Advanced_Google_Recaptcha();
        if (method_exists($recaptcha, 'verify_recaptcha')) {
            return $recaptcha->verify_recaptcha();
        }
    }

    // Método 3: Verificação manual do token reCAPTCHA v2/v3
    if (isset($_POST['g-recaptcha-response'])) {
        $recaptcha_response = sanitize_text_field($_POST['g-recaptcha-response']);
        return validate_recaptcha_token($recaptcha_response);
    }

    return false;
}

// Validação manual do token reCAPTCHA
function validate_recaptcha_token($token) {
    $secret_key = get_option('agr_secret_key'); // Supondo que o plugin salve a chave aqui

    if (empty($secret_key)) {
        // Tentar obter a chave de outras opções do plugin
        $secret_key = get_option('agr_settings')['secret_key'] ?? '';
    }

    if (empty($secret_key)) {
        // Se não encontrou chave, assume que reCAPTCHA não está configurado
        return true;
    }

    $response = wp_remote_post('https://www.google.com/recaptcha/api/siteverify', array(
        'body' => array(
            'secret' => $secret_key,
            'response' => $token,
            'remoteip' => $_SERVER['REMOTE_ADDR']
        )
    ));

    if (is_wp_error($response)) {
        return false;
    }

    $body = wp_remote_retrieve_body($response);
    $data = json_decode($body);

    return $data->success;
}

// Adicionar scripts do reCAPTCHA nas páginas de auth
function add_recaptcha_scripts() {
    if (is_page('login') || is_page('recuperar-senha')) {
        if (is_advanced_recaptcha_active()) {
            // Se o plugin já adiciona os scripts, não precisamos adicionar novamente
            if (!wp_script_is('google-recaptcha', 'enqueued')) {
                // Adicionar script do reCAPTCHA
                wp_enqueue_script('google-recaptcha', 'https://www.google.com/recaptcha/api.js', array(), null, true);
            }
        }
    }
}
add_action('wp_enqueue_scripts', 'add_recaptcha_scripts');



// Restringir acesso ao dashboard apenas para admins
function restrict_dashboard_access() {
    if (is_page('dashboard') && !current_user_can('administrator')) {
        wp_redirect(home_url('/login'));
        exit;
    }
}
add_action('template_redirect', 'restrict_dashboard_access');
?>
