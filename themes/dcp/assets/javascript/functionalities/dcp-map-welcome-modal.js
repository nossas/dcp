document.addEventListener('alpine:init', () => {
  Alpine.data('welcomeModal', () => ({
    isOpen: false,
    step: 1,

    init() {
      this.isOpen = true;
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
    }
  }));
});
