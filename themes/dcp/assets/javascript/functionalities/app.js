import Alpine from 'alpinejs';

import 'iconify-icon';

window.Alpine = Alpine;

window.addEventListener('DOMContentLoaded', () => {
    Alpine.start();
});

window.addEventListener('load', function () {
    let DESIRED_BOTTOM = '40%';

    let attempts = 0;
    const maxAttempts = 40;

    const forceUserWayStyle = setInterval(() => {
        const buttonWrapper = document.querySelector('.userway_p3 .userway_buttons_wrapper');

        attempts++;

        if (attempts >= maxAttempts) {
            clearInterval(forceUserWayStyle);
            return;
        }

        if (document.body.classList.contains('page-mapa') && buttonWrapper) {
            DESIRED_BOTTOM = '13%';
            buttonWrapper.style.setProperty('bottom', DESIRED_BOTTOM, 'important');
        }

        if (buttonWrapper) {
            buttonWrapper.style.setProperty('bottom', DESIRED_BOTTOM, 'important');
            buttonWrapper.style.setProperty('right', 'auto', 'important');
            buttonWrapper.style.setProperty('top', 'auto', 'important');

            if (attempts > 15) {
                clearInterval(forceUserWayStyle);
            }
        }
    }, 300);
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
                    modalText.innerHTML = `Em caso de emergência, ligue para o número <strong>${number}</strong>.`;
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
        input.placeholder = 'Descreva o problema';
    } else {
        input.placeholder = 'Descreva o problema e veja quem acionar';
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



document.addEventListener('DOMContentLoaded', function () {

    if( document.querySelector('.anexos-slider') ) {
        const swiper = new Swiper( '.anexos-slider', {
            loop: true,
            slidesPerView: 1,
            spaceBetween: 30,
            navigation: {
                nextEl: '.swiper-button-next',
                prevEl: '.swiper-button-prev',
            },
            pagination: {
                el: '.swiper-pagination',
                clickable: true,
            },
        });
    }

});

//adiciona classe no card da home
// Só se esse script for executado após os elementos estarem no HTML
const container = document.getElementById("acontece-na-comunidade");

if (container) {
  const meta = container.querySelector(".post-card__meta");
  const datetime = container.querySelector(".post-card__datetime");

  if (meta && datetime) {
    const wrapper = document.createElement("div");
    wrapper.className = "post-card__meta-group";

    wrapper.style.display = "flex";
    wrapper.style.alignItems = "center";
    wrapper.style.gap = "8px";
    wrapper.style.color = "#605E5D";
    wrapper.style.fontSize = "14px";

    meta.style.color = "#605E5D";
    meta.style.fontSize = "14px";
    datetime.style.color = "#605E5D";
    datetime.style.fontSize = "14px";

    meta.parentNode.insertBefore(wrapper, meta);
    wrapper.appendChild(meta);
    wrapper.appendChild(datetime);
  }
}

 //ajusta o posicionamento do userway
document.addEventListener("DOMContentLoaded", function () {
  const isMobile = () => window.innerWidth <= 820;

  const ajustarEstiloBotaoUserWay = () => {
    if (!isMobile()) return; // só executa no mobile

    const wrapper = document.querySelector('.uwy.userway_p3 .userway_buttons_wrapper');
    if (wrapper && document.body.classList.contains('page-mapa')) {
      wrapper.removeAttribute('style');

      const left = 'calc(-12px + 100vw)';
      const bottom = '115px';

      wrapper.style.left = left;
      wrapper.style.bottom = bottom;
    }
  };

  if (isMobile()) {
    // Aplica repetidamente por até 6 segundos
    let tentativas = 0;
    const intervalo = setInterval(() => {
      ajustarEstiloBotaoUserWay();
      tentativas++;
      if (tentativas > 20) clearInterval(intervalo);
    }, 300);
  }

  // Aplica também ao redimensionar (só se for mobile)
  window.addEventListener('resize', () => {
    if (isMobile()) ajustarEstiloBotaoUserWay();
  });
});

document.addEventListener('DOMContentLoaded', function() {
  const phoneInputs = document.querySelectorAll('.phone');

  phoneInputs.forEach(function(input) {
    input.addEventListener('input', function(e) {
      let value = e.target.value.replace(/\D/g, '');

      if (value.length > 10) {
        value = value.replace(/^(\d{2})(\d{5})(\d{4}).*/, '($1) $2-$3');
      }
      else if (value.length > 6) {
        value = value.replace(/^(\d{2})(\d{4})(\d{0,4}).*/, '($1) $2-$3');
      }
      else if (value.length > 2) {
        value = value.replace(/^(\d{2})(\d{0,5})/, '($1) $2');
      }
      else {
        value = value.replace(/^(\d*)/, '($1');
      }

      e.target.value = value;
    });
  });
});

document.addEventListener('DOMContentLoaded', function () {
    const form = document.querySelector('#formParticiparAcao');
    const phoneInput = form.querySelector('.phone');

    form.addEventListener('submit', function (e) {
        e.preventDefault();

        const phoneValue = phoneInput.value.trim();
        const phoneRegex = /^\(\d{2}\) \d{5}-\d{4}$/;

        if (!phoneRegex.test(phoneValue)) {
            const snackbar = document.querySelector('#cf7-snackbar');
            //snackbar.textContent = 'Preencha um telefone válido (xx) xxxxx-xxxx';
            snackbar.classList.add('show');
            return false;
        }

        form.submit();
    });
});

document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('formParticiparAcao');
  if (!form) return console.warn('formParticiparAcao não encontrado.');

  const snackbarError = document.getElementById('cf7-snackbar-error');
  const snackbarSuccess = document.getElementById('cf7-snackbar-success');

  const requiredFields = Array.from(
    form.querySelectorAll('input[required], textarea[required], select[required]')
  );

  function findMsgEl(field) {
    const lbl = field.closest('label');
    if (lbl && lbl.nextElementSibling?.classList.contains('msg-erro')) return lbl.nextElementSibling;
    let el = field.nextElementSibling;
    for (let i = 0; i < 4 && el; i++) {
      if (el.classList?.contains('msg-erro')) return el;
      el = el.nextElementSibling;
    }
    return field.parentElement?.querySelector('.msg-erro') || null;
  }

  function addErrorVisual(field) {
    if (!field) return;
    if (field.type === 'checkbox' || field.type === 'radio') {
      const lbl = field.closest('label');
      if (lbl) lbl.classList.add('campo-erro');
    } else {
      field.classList.add('campo-erro');
      if (field.parentElement) field.parentElement.classList.add('campo-erro');
    }
    field.setAttribute('aria-invalid', 'true');
    const msg = findMsgEl(field);
    if (msg) msg.style.display = 'block';
  }

  function removeErrorVisual(field) {
    if (!field) return;
    if (field.type === 'checkbox' || field.type === 'radio') {
      const lbl = field.closest('label');
      if (lbl) lbl.classList.remove('campo-erro');
    } else {
      field.classList.remove('campo-erro');
      if (field.parentElement) field.parentElement.classList.remove('campo-erro');

      try {
        field.style.setProperty('border', '1px solid #d1c6ba', 'important'); // ajuste a cor se necessário
        field.style.setProperty('box-shadow', 'none', 'important');
        field.style.setProperty('outline', 'none', 'important');
      } catch (err) {
        field.style.border = '';
        field.style.boxShadow = '';
      }
    }
    field.removeAttribute('aria-invalid');
    try { field.setCustomValidity(''); } catch (err) {}
    const msg = findMsgEl(field);
    if (msg) msg.style.display = 'none';
  }

  function clearAllErrors() {
    Array.from(form.querySelectorAll('.campo-erro')).forEach(el => el.classList.remove('campo-erro'));

    Array.from(form.querySelectorAll('.wpcf7-not-valid, .wpcf7-form-control.wpcf7-not-valid')).forEach(el => {
      el.classList.remove('wpcf7-not-valid');
    });

    Array.from(form.querySelectorAll('input, textarea, select')).forEach(f => {
      f.removeAttribute('aria-invalid');
      try { f.setCustomValidity(''); } catch (err) {}
      try {
        f.style.setProperty('border', '1px solid #d1c6ba', 'important');
        f.style.setProperty('box-shadow', 'none', 'important');
        f.style.setProperty('outline', 'none', 'important');
      } catch (e) {
        f.style.border = '';
        f.style.boxShadow = '';
      }
    });

    Array.from(form.querySelectorAll('.msg-erro')).forEach(m => m.style.display = 'none');
  }

  function showModal(modal) {
    if (!modal) return;
    modal.style.display = 'flex';
    setTimeout(() => modal.classList.add('show'), 10);
  }

  function hideModal(modal) {
    if (!modal) return;
    modal.classList.remove('show');
    setTimeout(() => modal.style.display = 'none', 250);
  }

  form.querySelectorAll('.modal-close').forEach(btn => {
    btn.addEventListener('click', () => {
      hideModal(btn.closest('.modal'));
    });
  });

  form.addEventListener('submit', (e) => {
    e.preventDefault();

    const nowRequired = Array.from(form.querySelectorAll('input[required], textarea[required], select[required]'));

    const invalids = nowRequired.filter(f => !f.checkValidity());
    invalids.forEach(f => addErrorVisual(f));

    if (invalids.length > 0) {
      showModal(snackbarError);
      invalids[0].focus();
      console.log('[formParticiparAcao] invalids:', invalids.length, 'total .campo-erro:', form.querySelectorAll('.campo-erro').length);
      return;
    }

    hideModal(snackbarError);

    nowRequired.forEach(f => {
      removeErrorVisual(f);

      try { f.setCustomValidity(''); } catch (err) {}
      try {
        f.style.setProperty('border', '1px solid #281414', 'important');
        f.style.setProperty('box-shadow', 'none', 'important');
        f.style.setProperty('outline', 'none', 'important');
      } catch (err) {
      }
    });

    clearAllErrors();

    console.log('[formParticiparAcao] after-clear .campo-erro:', form.querySelectorAll('.campo-erro').length);

    showModal(snackbarSuccess);
  });

  requiredFields.forEach(field => {
    const ev = (field.type === 'checkbox' || field.type === 'radio') ? 'change' : 'input';
    field.addEventListener(ev, () => {
      if (field.checkValidity()) removeErrorVisual(field);
      if (Array.from(form.querySelectorAll('input[required], textarea[required], select[required]'))
          .every(f => f.checkValidity())) {
        hideModal(snackbarError);
      }
    });
    field.addEventListener('blur', () => {
      if (!field.checkValidity()) addErrorVisual(field);
    });
  });
});

