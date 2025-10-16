<?php
/*
Template Name: Login Personalizado
*/

// Redirecionar se já estiver logado como admin
if (is_user_logged_in() && current_user_can('administrator')) {
    wp_redirect(home_url('/dashboard'));
    exit;
}

get_header();
?>

    <div class="auth-page-wrapper">
        <div class="auth-container">
            <div class="auth-form-container">
                <div class="auth-header">
                    <h2>Login Administrativo</h2>
                    <p>Acesso restrito para administradores</p>
                </div>

                <form id="customLoginForm" class="auth-form">
                    <div class="form-group">
                        <label for="username">Usuário:</label>
                        <input type="text" id="username" name="username" required autocomplete="username">
                    </div>

                    <div class="form-group">
                        <label for="password">Senha:</label>
                        <input type="password" id="password" name="password" required autocomplete="current-password">
                    </div>

                    <!-- reCAPTCHA Integration -->
                    <div id="recaptchaContainer" class="form-group" style="display: none;">
                        <div id="googleRecaptcha"></div>
                    </div>

                    <button type="submit" id="loginButton" class="auth-button">Entrar</button>

                    <div class="auth-links">
                        <a href="<?php echo home_url('/recuperar-senha'); ?>">Esqueci minha senha</a>
                    </div>

                    <div id="loginMessage" class="auth-message"></div>

                    <?php wp_nonce_field('custom_login_nonce', 'security'); ?>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const loginForm = document.getElementById('customLoginForm');
            const loginButton = document.getElementById('loginButton');
            const messageDiv = document.getElementById('loginMessage');
            const recaptchaContainer = document.getElementById('recaptchaContainer');

            // Verificar se o reCAPTCHA está disponível
            const recaptchaEnabled = <?php echo is_advanced_recaptcha_active() ? 'true' : 'false'; ?>;

            if (recaptchaEnabled) {
                recaptchaContainer.style.display = 'block';

                // Se o plugin não carregou o reCAPTCHA automaticamente, carregamos nós mesmos
                if (typeof grecaptcha === 'undefined') {
                    const script = document.createElement('script');
                    script.src = 'https://www.google.com/recaptcha/api.js?render=explicit';
                    document.head.appendChild(script);

                    script.onload = function() {
                        renderRecaptcha();
                    };
                } else {
                    renderRecaptcha();
                }
            }

            function renderRecaptcha() {
                if (typeof grecaptcha !== 'undefined' && grecaptcha.render) {
                    grecaptcha.render('googleRecaptcha', {
                        'sitekey': '<?php echo get_option('agr_site_key') ?: ''; ?>',
                        'theme': 'light',
                        'size': 'normal'
                    });
                }
            }

            function getRecaptchaResponse() {
                if (!recaptchaEnabled) return '';

                const recaptchaResponse = grecaptcha.getResponse();
                if (!recaptchaResponse) {
                    throw new Error('Por favor, complete a verificação reCAPTCHA.');
                }
                return recaptchaResponse;
            }

            function resetRecaptcha() {
                if (recaptchaEnabled && typeof grecaptcha !== 'undefined') {
                    grecaptcha.reset();
                }
            }

            loginForm.addEventListener('submit', function(e) {
                e.preventDefault();

                let recaptchaResponse = '';
                try {
                    recaptchaResponse = getRecaptchaResponse();
                } catch (error) {
                    messageDiv.innerHTML = '<div class="error">' + error.message + '</div>';
                    return;
                }

                const formData = new FormData();
                formData.append('action', 'custom_login');
                formData.append('username', document.getElementById('username').value);
                formData.append('password', document.getElementById('password').value);
                formData.append('security', document.querySelector('input[name="security"]').value);

                if (recaptchaEnabled) {
                    formData.append('g-recaptcha-response', recaptchaResponse);
                }

                // Mostrar loading
                loginButton.disabled = true;
                loginButton.textContent = 'Entrando...';
                messageDiv.innerHTML = '';

                fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            messageDiv.innerHTML = '<div class="success">' + data.data.message + '</div>';
                            // Redirecionar após sucesso
                            setTimeout(() => {
                                window.location.href = data.data.redirect;
                            }, 1000);
                        } else {
                            messageDiv.innerHTML = '<div class="error">' + data.data + '</div>';
                            loginButton.disabled = false;
                            loginButton.textContent = 'Entrar';
                            resetRecaptcha();
                        }
                    })
                    .catch(error => {
                        messageDiv.innerHTML = '<div class="error">Erro na requisição. Tente novamente.</div>';
                        loginButton.disabled = false;
                        loginButton.textContent = 'Entrar';
                        resetRecaptcha();
                        console.error('Error:', error);
                    });
            });
        });
    </script>

    <style>
        .main-footer-subscribe,
        .main-footer {
            display: none !important;
        }
    </style>

    <style>
        /* Estilos anteriores mantidos */

        #googleRecaptcha {
            margin: 15px 0;
            display: flex;
            justify-content: center;
        }

        .g-recaptcha {
            transform: scale(0.9);
            transform-origin: center;
        }
    </style>

<?php  get_footer(); ?>
