import Alpine from 'alpinejs';

import 'iconify-icon';

window.Alpine = Alpine;

window.addEventListener('DOMContentLoaded', () => {
    Alpine.start();
});


document.addEventListener("DOMContentLoaded", function () {
    const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
    const callNumbers = ["193", "199", "192", "1746"];

    const buttons = document.querySelectorAll(".page-quem-acionar .wp-block-buttons > .wp-block-button");
    const modal = document.getElementById("call-modal");
    const modalText = document.getElementById("modal-text");
    const modalClose = document.querySelector(".call-modal__close");

    buttons.forEach((button, index) => {
        const number = callNumbers[index];
        if (!number) return;

        const link = button.querySelector("a");
        if (!link) return;

        link.addEventListener("click", function (e) {
        if (isMobile) {
            // Mobile: define href para ligar
            link.setAttribute("href", `tel:${number}`);
        } else {
            // Desktop: abre modal
            e.preventDefault();
            modalText.textContent = `Em caso de emergência, ligue para o número ${number}.`;
            modal.classList.add("call-modal--visible");
        }
        });
    });

    modalClose.addEventListener("click", () => {
        modal.classList.remove("call-modal--visible");
    });

    // Fecha ao clicar fora do conteúdo do modal
    modal.addEventListener("click", function (e) {
        if (e.target === modal) {
        modal.classList.remove("call-modal--visible");
        }
    });
});


//filtro dos tipos de apoios com os termos
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('apoio-search');
    const searchButton = document.getElementById('apoio-search-button');
    const tagButtons = document.querySelectorAll('.tag-button');
    const cards = document.querySelectorAll('.apoio-item');

    let activeTag = 'all';

    // Função que filtra os cards com base na busca e na tag
    function filterCards() {
        const searchTerm = searchInput.value.toLowerCase().trim(); // .trim() para eliminar espaços extras

        cards.forEach(card => {
            // Pega os termos da taxonomia (tags) associadas ao card
            const tags = card.dataset.tags.toLowerCase();  // tags associadas ao card (em minúsculas para facilitar a busca)

            // Verifica se a tag ativa corresponde às tags do card
            const matchesTag = (activeTag === 'all') || tags.includes(activeTag);

            // Verifica se a busca contém o termo de pesquisa nas tags
            const matchesSearch = (searchTerm === '' || tags.includes(searchTerm));

            // O card será exibido se corresponder a ambos os filtros
            if (matchesSearch && matchesTag) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Filtro por tags
    tagButtons.forEach(button => {
        button.addEventListener('click', function () {
            tagButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            activeTag = this.dataset.tag;  // Define a tag ativa
            filterCards();  // Aplica o filtro com base na tag selecionada
        });
    });

    // Filtro por busca (ao digitar no input)
    searchInput.addEventListener('input', function() {
        if (searchInput.value.length >= 2 || searchInput.value.length === 0) {
            filterCards();
        }
    });

    // Ativa o filtro ao clicar no botão de busca
    searchButton.addEventListener('click', function () {
        filterCards();
    });
});
