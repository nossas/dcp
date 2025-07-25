document.addEventListener('DOMContentLoaded', function() {
    const menuContainer = document.querySelector('.main-header-lateral__menu-mobile');
    if (!menuContainer) {
        return;
    }

    const allLinks = menuContainer.querySelectorAll('ul li > a');

    allLinks.forEach(function(link) {
        link.addEventListener('click', function(event) {
            const parentLi = this.parentElement;
            const hasSubmenu = parentLi.classList.contains('menu-item-has-children');

            if (hasSubmenu) {
                event.preventDefault();
                event.stopPropagation();
            }

            const wasActive = this.classList.contains('is-active');

            allLinks.forEach(a => a.classList.remove('is-active'));
            menuContainer.querySelectorAll('.menu-item--open').forEach(li => li.classList.remove('menu-item--open'));

            if (!wasActive) {
                this.classList.add('is-active');
                if (hasSubmenu) {
                    parentLi.classList.add('menu-item--open');
                }
            }
        });
    });
});
