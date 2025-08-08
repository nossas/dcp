(function() {
    /**
     * @param {Function} func
     * @param {number} wait
     */
    function debounce(func, wait) {
        let timeout;
        return function executedFunction(...args) {
            const later = () => {
                clearTimeout(timeout);
                func(...args);
            };
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
        };
    }

    /**
     * Verifica o tamanho da tela e ajusta o estado dos acordeões.
     * Fecha em telas mobile e garante que estejam abertos em telas desktop.
     */
    function handleAccordionState() {
        const mobileBreakpoint = 768;
        const detailsElements = document.querySelectorAll('.closed-mobile');

        if (!detailsElements.length) {
            return;
        }

        if (window.innerWidth <= mobileBreakpoint) {
            detailsElements.forEach(function(details) {
                details.removeAttribute('open');
            });
        } else {
            detailsElements.forEach(function(details) {
                details.setAttribute('open', '');
            });
        }
    }

    const debouncedHandleAccordionState = debounce(handleAccordionState, 150);

    document.addEventListener('DOMContentLoaded', handleAccordionState);

    window.addEventListener('resize', debouncedHandleAccordionState);

})();
