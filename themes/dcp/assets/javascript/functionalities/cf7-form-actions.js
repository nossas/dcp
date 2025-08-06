console.log('Arquivo de formulários carregado com sucesso.');
function showSuccessSnackbar(formElement, message) {
    const snackbar = formElement.querySelector('.cf7-snackbar');
    if (snackbar) {
        snackbar.innerHTML = `
            <svg class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 8px;">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            <span>${message}</span>
        `;
        snackbar.classList.add('show');
        setTimeout(function() {
            snackbar.classList.remove('show');
        }, 3000);
    }
}

document.addEventListener('wpcf7mailsent', function(event) {
    const formId = event.detail.contactFormId;
    let successMessage = '';

    const idFormSugerirAcao = '712';
    const idFormFacaParte = '185';

    switch (formId.toString()) {
        case idFormSugerirAcao:
            successMessage = 'Sua sugestão foi enviada com sucesso!';
            break;

        case idFormFacaParte:
            successMessage = 'Cadastro foi enviado com sucesso!';
            break;
    }

    if (successMessage) {
        showSuccessSnackbar(event.target, successMessage);
    }

}, false);

