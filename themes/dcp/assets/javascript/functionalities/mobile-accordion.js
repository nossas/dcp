(function() {

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

    // Sempre mantém aberto no mobile e no desktop
    function handleAccordionState() {
        const detailsElements = document.querySelectorAll('.closed-mobile');

        if (!detailsElements.length) return;

        detailsElements.forEach(function(details) {
            details.setAttribute('open', '');
        });
    }

    const debouncedHandleAccordionState = debounce(handleAccordionState, 150);

    document.addEventListener('DOMContentLoaded', handleAccordionState);
    window.addEventListener('resize', debouncedHandleAccordionState);

})();
