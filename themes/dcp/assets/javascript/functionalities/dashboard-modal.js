console.log('dashboard-modal');

document.addEventListener('DOMContentLoaded', function () {
    const abrirModalBtn = document.getElementById('abrir-modal');
    const modal = document.getElementById('modal-confirmacao');
    const fecharModalBtn = document.getElementById('fechar-modal');
    const confirmarSalvarBtn = document.getElementById('confirmar-salvar');

    abrirModalBtn?.addEventListener('click', function () {
        modal.style.display = 'flex';
    });

    fecharModalBtn?.addEventListener('click', function () {
        modal.style.display = 'none';
    });

    confirmarSalvarBtn?.addEventListener('click', function () {
        const form = document.querySelector('.editar-recomendacao form');
        if (form) {
            const redirectInput = document.createElement('input');
            redirectInput.type = 'hidden';
            redirectInput.name = 'redirect_to';
            redirectInput.value = '<?= esc_url(get_dashboard_url("situacao_atual")) ?>';
            form.appendChild(redirectInput);
            form.submit();
        }
    });

    const voltarModalBtn = document.getElementById('voltar-modal');

    voltarModalBtn?.addEventListener('click', function () {
        modal.style.display = 'none';
    });
});
