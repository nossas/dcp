document.addEventListener("DOMContentLoaded", function () {
    function scrollToElement(selector) {
      const target = document.querySelector(selector);
      if (target) {
        target.scrollIntoView({
          behavior: "smooth",
          block: "start"
        });
      }
    }

    const btnProximas = document.getElementById("proximas");
    if (btnProximas) {
      btnProximas.addEventListener("click", function (e) {
        e.preventDefault();
        scrollToElement(".proximas-acoes");
      });
    }

    const btnSugestao = document.getElementById("sugestao");
    if (btnSugestao) {
      btnSugestao.addEventListener("click", function (e) {
        e.preventDefault();
        scrollToElement(".layout-part--footer-archive-acao");
      });
    }
});