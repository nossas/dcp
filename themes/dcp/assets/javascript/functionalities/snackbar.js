/**
 * Exibe um snackbar de feedback no dashboard.
 * @param {string} message - A mensagem a ser exibida.
 * @param {string} [type='success'] - O tipo de snackbar ('success', 'error', ou 'archive').
 * @param {number} [duration=3000] - A duração em milissegundos.
 */
export function showDashboardSnackbar(message, type = 'success', duration = 3000) {
    let snackbar = document.getElementById('dashboard-snackbar');
    if (!snackbar) {
        snackbar = document.createElement('div');
        snackbar.id = 'dashboard-snackbar';
        snackbar.className = 'dashboard-snackbar';
        document.body.appendChild(snackbar);
    }

    snackbar.classList.remove('is-success', 'is-error', 'is-archive');

    let icon = '';
    const successIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                            <path d="M14.916 4.2207C15.0257 4.2207 15.1352 4.24169 15.2363 4.28418C15.3374 4.32666 15.4291 4.3895 15.5059 4.46777C15.8273 4.79292 15.8315 5.31876 15.5166 5.64941L8.86426 13.5127C8.78875 13.5956 8.69685 13.6627 8.59473 13.709C8.49276 13.7551 8.3824 13.7801 8.27051 13.7822C8.15848 13.7843 8.0471 13.763 7.94336 13.7207C7.83952 13.6783 7.7446 13.6152 7.66602 13.5352L3.61816 9.43359C3.46216 9.27441 3.375 9.05981 3.375 8.83691C3.37508 8.61422 3.46235 8.40031 3.61816 8.24121C3.69486 8.16295 3.78669 8.1001 3.8877 8.05762C3.98882 8.01513 4.09832 7.99316 4.20801 7.99316C4.31755 7.99322 4.42635 8.01519 4.52734 8.05762C4.62838 8.1001 4.72015 8.16293 4.79688 8.24121L8.23047 11.7207L14.3047 4.49219C14.3116 4.48357 14.3192 4.4755 14.3271 4.46777C14.4039 4.38947 14.4956 4.32666 14.5967 4.28418C14.6977 4.24175 14.8065 4.22076 14.916 4.2207Z" fill="#235540"/>
                        </svg>`;
    const errorIcon = `<svg viewBox="0 0 24 24" fill="none" stroke="#B83D13" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"></circle><line x1="12" y1="8" x2="12" y2="12"></line><line x1="12" y1="16" x2="12.01" y2="16"></line></svg>`;

    const archiveIcon = `<svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                            <path d="M14.916 4.2207C15.0257 4.2207 15.1352 4.24169 15.2363 4.28418C15.3374 4.32666 15.4291 4.3895 15.5059 4.46777C15.8273 4.79292 15.8315 5.31876 15.5166 5.64941L8.86426 13.5127C8.78875 13.5956 8.69685 13.6627 8.59473 13.709C8.49276 13.7551 8.3824 13.7801 8.27051 13.7822C8.15848 13.7843 8.0471 13.763 7.94336 13.7207C7.83952 13.6783 7.7446 13.6152 7.66602 13.5352L3.61816 9.43359C3.46216 9.27441 3.375 9.05981 3.375 8.83691C3.37508 8.61422 3.46235 8.40031 3.61816 8.24121C3.69486 8.16295 3.78669 8.1001 3.8877 8.05762C3.98882 8.01513 4.09832 7.99316 4.20801 7.99316C4.31755 7.99322 4.42635 8.01519 4.52734 8.05762C4.62838 8.1001 4.72015 8.16293 4.79688 8.24121L8.23047 11.7207L14.3047 4.49219C14.3116 4.48357 14.3192 4.4755 14.3271 4.46777C14.4039 4.38947 14.4956 4.32666 14.5967 4.28418C14.6977 4.24175 14.8065 4.22076 14.916 4.2207Z" fill="#B83D13"/>
                        </svg>`;

    switch(type) {
        case 'archive':
            snackbar.classList.add('is-archive');
            icon = archiveIcon;
            break;
        case 'error':
            snackbar.classList.add('is-error');
            icon = errorIcon;
            break;
        case 'success':
        default:
            snackbar.classList.add('is-success');
            icon = successIcon;
            break;
    }

    snackbar.innerHTML = icon + `<span>${message}</span>`;
    snackbar.classList.add('show');

    setTimeout(() => {
        snackbar.classList.remove('show');
    }, duration);
}

window.showDashboardSnackbar = showDashboardSnackbar;
