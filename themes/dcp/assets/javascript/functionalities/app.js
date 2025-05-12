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
