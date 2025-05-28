import Alpine from 'alpinejs';

import 'iconify-icon';

window.Alpine = Alpine;

window.addEventListener('DOMContentLoaded', () => {
    Alpine.start();
});


document.addEventListener("DOMContentLoaded", function () {
    const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);
    const callNumbers = ["193", "199", "192", "1746"];

    const page = document.querySelector(".page-quem-acionar");

    if (page) {
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
                    link.setAttribute("href", `tel:${number}`);
                } else {
                    e.preventDefault();
                    modalText.textContent = `Em caso de emergência, ligue para o número ${number}.`;
                    modal.classList.add("call-modal--visible");
                }
            });
        });

        modalClose.addEventListener("click", () => {
            modal.classList.remove("call-modal--visible");
        });

        modal.addEventListener("click", function (e) {
            if (e.target === modal) {
                modal.classList.remove("call-modal--visible");
            }
        });
    }
});

// Filtro dos tipos de apoios com os termos
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('apoio-search');
    const searchButton = document.getElementById('apoio-search-button');
    const tagButtons = document.querySelectorAll('.tag-button');
    const cards = document.querySelectorAll('.apoio-item');

    if (!searchInput || !searchButton || cards.length === 0) return;

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

//função para troca de placeholder mobile e deskt
function atualizarPlaceholder() {
    const input = document.getElementById('apoio-search');
    if (!input) return;

    if (window.innerWidth <= 768) {
        input.placeholder = 'Descreva o problema...';
    } else {
        input.placeholder = 'Descreva o problema e veja quem acionar...';
    }
}

window.addEventListener('DOMContentLoaded', atualizarPlaceholder);

window.addEventListener('resize', atualizarPlaceholder);

document.addEventListener("DOMContentLoaded", function () {
    const separatorSVG = `
        <svg xmlns="http://www.w3.org/2000/svg" width="15" height="14" viewBox="0 0 15 14" fill="none" style="vertical-align: middle; margin: 0 6px;">
          <path fill-rule="evenodd" clip-rule="evenodd" d="M4.56603 1.44103C4.60667 1.40029 4.65495 1.36796 4.7081 1.34591C4.76125 1.32385 4.81824 1.3125 4.87578 1.3125C4.93333 1.3125 4.99031 1.32385 5.04346 1.34591C5.09661 1.36796 5.14489 1.40029 5.18553 1.44103L10.4355 6.69103C10.4763 6.73167 10.5086 6.77995 10.5307 6.8331C10.5527 6.88625 10.5641 6.94324 10.5641 7.00078C10.5641 7.05833 10.5527 7.11531 10.5307 7.16846C10.5086 7.22161 10.4763 7.26989 10.4355 7.31053L5.18553 12.5605C5.10338 12.6427 4.99196 12.6888 4.87578 12.6888C4.7596 12.6888 4.64818 12.6427 4.56603 12.5605C4.48388 12.4784 4.43773 12.367 4.43773 12.2508C4.43773 12.1346 4.48388 12.0232 4.56603 11.941L9.50716 7.00078L4.56603 2.06053C4.52529 2.01989 4.49296 1.97161 4.47091 1.91846C4.44885 1.86531 4.4375 1.80833 4.4375 1.75078C4.4375 1.69324 4.44885 1.63625 4.47091 1.5831C4.49296 1.52995 4.52529 1.48167 4.56603 1.44103Z" fill="#281414"/>
        </svg>`;

    document.querySelectorAll("nav.breadcrumb").forEach(nav => {
        nav.innerHTML = nav.innerHTML.replace(/&gt;|›|»|&raquo;|&nbsp;&gt;&nbsp;/g, separatorSVG);
    });
});

document.addEventListener("DOMContentLoaded", function () {
  // Só executa em mobile
  if (window.innerWidth > 768) return;

  const excerptWrappers = document.querySelectorAll(".post-card__excerpt-wrapped");

  excerptWrappers.forEach(wrapper => {
    const excerpt = wrapper.querySelector(".post-card__excerpt");
    const moreBtn = wrapper.querySelector(".post-card__excerpt-more");

    if (!excerpt || !moreBtn) return;

    // Pega o line-height real computado
    let lineHeight = parseFloat(getComputedStyle(excerpt).lineHeight);
    if (isNaN(lineHeight)) lineHeight = 20;

    const maxHeight = lineHeight * 2;

    // Verifica se o texto está maior que 2 linhas
    if (excerpt.scrollHeight > maxHeight) {
      moreBtn.style.display = "inline"; // mostra o botão
    } else {
      moreBtn.style.display = "none"; // garante que fique oculto
    }

    // Expande o texto ao clicar
    moreBtn.addEventListener("click", () => {
      excerpt.style.display = "block"; // remove o -webkit-box
      excerpt.style.webkitLineClamp = "unset";
      moreBtn.style.display = "none";
    });
  });
});
