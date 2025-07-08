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

document.addEventListener('DOMContentLoaded', function () {
    const abrirBtn = document.getElementById('abrir-modal-apoio');
    const modal = document.getElementById('modal-confirmar-salvar');
    const fecharBtn = modal.querySelector('.modal-confirmar-salvar__close');
    const voltarBtn = modal.querySelector('.modal-confirmar-salvar__btn.voltar');
    const salvarBtn = modal.querySelector('.modal-confirmar-salvar__btn.salvar');
    const form = document.querySelector('.apoio-card__form');
    const arquivarInput = document.getElementById('arquivar_apoio');

    if (abrirBtn && modal && form && salvarBtn) {
        abrirBtn.addEventListener('click', (e) => {
            e.preventDefault();
            modal.classList.remove('is-hidden');
        });

        [fecharBtn, voltarBtn].forEach(btn => {
            btn?.addEventListener('click', () => {
                modal.classList.add('is-hidden');
            });
        });

        salvarBtn.addEventListener('click', () => {
            if (arquivarInput) {
                arquivarInput.remove();
            }
            modal.classList.add('is-hidden');
            form.submit();
        });
    }
});

document.addEventListener('DOMContentLoaded', function () {
    const arquivarBtn = document.querySelector('.apoio__btn-arquivar');
    const arquivarInput = document.getElementById('arquivar_apoio');
    const form = document.querySelector('.apoio-card__form');

    if (arquivarBtn && arquivarInput && form) {
        arquivarBtn.addEventListener('click', function (e) {
            e.preventDefault();
            arquivarInput.value = '1';
            form.submit();
        });
    }
});

document.addEventListener("DOMContentLoaded", () => {
    const checkboxes = document.querySelectorAll('input[name="risco_selecionado"]');
    const btnPublicar = document.getElementById("btn-publicar");
    const situacaoAtual = document.getElementById("situacao-atual");

    const modal = document.getElementById("modal-publicar");
    const fecharModal = () => (modal.style.display = "none");

    const btnFechar = modal.querySelector(".modal-publicar__fechar");
    const btnVoltar = modal.querySelector(".modal-publicar__voltar");
    const btnConfirmar = modal.querySelector(".modal-publicar__confirmar");

    checkboxes.forEach((checkbox) => {
        checkbox.addEventListener("change", () => {
            checkboxes.forEach((cb) => {
                if (cb !== checkbox) cb.checked = false;
            });
        });
    });

    btnPublicar.addEventListener("click", () => {
        const selected = Array.from(checkboxes).find(cb => cb.checked);
        if (!selected) {
            alert("Por favor, selecione uma situação de risco.");
            return;
        }
        modal.style.display = "flex";
    });

    btnFechar.addEventListener("click", fecharModal);
    btnVoltar.addEventListener("click", fecharModal);

    btnConfirmar.addEventListener("click", () => {
        const selected = Array.from(checkboxes).find(cb => cb.checked);
        if (!selected) return;

        const label = selected.nextElementSibling;
        if (!label || !label.classList.contains("alerta-faixa")) {
            alert("Erro ao localizar o card selecionado.");
            return;
        }

        const clone = label.cloneNode(true);
        clone.querySelectorAll('input, .alerta-faixa__remover').forEach(el => el.remove());

        situacaoAtual.innerHTML = "";
        situacaoAtual.appendChild(clone);

        fecharModal();
    });
});

