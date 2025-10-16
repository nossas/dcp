<?php
/*
Template Name: Recuperar Senha
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
                    <h2>Recuperar Senha</h2>
                    <p>Digite seu e-mail para recuperar a senha</p>
                </div>

                <form id="recoveryForm" class="auth-form">
                    <div class="form-group">
                        <label for="email">E-mail:</label>
                        <input type="email" id="email" name="email" required autocomplete="email">
                    </div>

                    <!-- reCAPTCHA Integration -->
                    <div id="recaptchaContainer" class="form-group" style="display: none;">
                        <div id="googleRecaptcha"></div>
                    </div>

                    <button type="submit" id="recoveryButton" class="auth-button">Recuperar Senha</button>

                    <div class="auth-links">
                        <a href="<?php echo home_url('/login'); ?>">← Voltar para o login</a>
                    </div>

                    <div id="recoveryMessage" class="auth-message"></div>

                    <?php wp_nonce_field('custom_recovery_nonce', 'security'); ?>
                </form>
            </div>
        </div>
    </div>

    <style>
        .main-footer-subscribe,
        .main-footer {
            display: none !important;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const recoveryForm = document.getElementById('recoveryForm');
            const recoveryButton = document.getElementById('recoveryButton');
            const messageDiv = document.getElementById('recoveryMessage');
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

            recoveryForm.addEventListener('submit', function(e) {
                e.preventDefault();

                let recaptchaResponse = '';
                try {
                    recaptchaResponse = getRecaptchaResponse();
                } catch (error) {
                    messageDiv.innerHTML = '<div class="error">' + error.message + '</div>';
                    return;
                }

                const formData = new FormData();
                formData.append('action', 'custom_password_recovery');
                formData.append('email', document.getElementById('email').value);
                formData.append('security', document.querySelector('input[name="security"]').value);

                if (recaptchaEnabled) {
                    formData.append('g-recaptcha-response', recaptchaResponse);
                }

                // Mostrar loading
                recoveryButton.disabled = true;
                recoveryButton.textContent = 'Enviando...';
                messageDiv.innerHTML = '';

                fetch('<?php echo admin_url('admin-ajax.php'); ?>', {
                    method: 'POST',
                    body: formData
                })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            messageDiv.innerHTML = '<div class="success">' + data.data.message + '</div>';
                            // Em desenvolvimento, mostrar o link
                            if (data.data.reset_url) {
                                messageDiv.innerHTML += '<div class="info">Link de desenvolvimento: <a href="' + data.data.reset_url + '" target="_blank">Redefinir Senha</a></div>';
                            }
                        } else {
                            messageDiv.innerHTML = '<div class="error">' + data.data + '</div>';
                        }
                        recoveryButton.disabled = false;
                        recoveryButton.textContent = 'Recuperar Senha';
                        resetRecaptcha();
                    })
                    .catch(error => {
                        messageDiv.innerHTML = '<div class="error">Erro na requisição. Tente novamente.</div>';
                        recoveryButton.disabled = false;
                        recoveryButton.textContent = 'Recuperar Senha';
                        resetRecaptcha();
                        console.error('Error:', error);
                    });
            });
        });
    </script>

    <style>
        /* Estilos anteriores mantidos */
    </style>

<?php get_footer(); ?>
