document.addEventListener('alpine:init', () => {
    Alpine.data('welcomeModal', () => ({
        isOpen: false,
        step: 1,

        init() {
            const query = new URLSearchParams(location.href);
            if (!localStorage.getItem('welcomeModal') && !query.has('open')) {
                this.isOpen = true;
            }
        },

        nextStep() {
            if (this.step < 3) {
                this.step++;
            } else {
                this.closeModal();
            }
        },

        closeModal() {
            this.isOpen = false;
            localStorage.setItem('welcomeModal', 'seen');
        }
    }));
});
