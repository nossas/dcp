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


// Filtro dos tipos de apoios com os termos
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('apoio-search');
    const searchButton = document.getElementById('apoio-search-button');
    const tagButtons = document.querySelectorAll('.tag-button');
    const cards = document.querySelectorAll('.apoio-item');

    let activeTag = 'all';

    // Função que filtra os cards com base na busca e na tag
    function filterCards() {
        const searchTerm = searchInput.value.toLowerCase().trim();

        cards.forEach(card => {
            const tags = card.dataset.tags.toLowerCase();
            const content = card.textContent.toLowerCase(); // Pega todo o conteúdo do card

            // Verifica se a tag ativa corresponde às tags do card
            const matchesTag = (activeTag === 'all') || tags.includes(activeTag);

            // Verifica se a busca está no conteúdo do card
            const matchesSearch = (searchTerm === '') || content.includes(searchTerm);

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
            activeTag = this.dataset.tag;
            filterCards();
        });
    });

    // Filtro por busca ao digitar
    searchInput.addEventListener('input', function () {
        if (searchInput.value.length >= 2 || searchInput.value.length === 0) {
            filterCards();
        }
    });

    // Filtro ao clicar no botão de busca
    searchButton.addEventListener('click', function () {
        filterCards();
    });
});
