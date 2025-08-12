document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.custom-select-trigger').forEach(trigger => {
        trigger.addEventListener('click', function(e) {
            e.stopPropagation();
            const targetId = this.getAttribute('data-target');
            const options = document.getElementById(targetId);

            document.querySelectorAll('.custom-select-options.active').forEach(opt => {
                if (opt.id !== targetId) {
                    opt.classList.remove('active');
                    document.querySelector(`[data-target="${opt.id}"]`).classList.remove('active');
                }
            });

            options.classList.toggle('active');
            this.classList.toggle('active');
        });
    });

    document.querySelectorAll('.custom-select-option').forEach(option => {
        option.addEventListener('click', function() {
            const value = this.getAttribute('data-value');
            const svgContent = this.innerHTML;

            const hiddenInputId = this.getAttribute('data-hidden-input');
            const mainPreviewId = this.getAttribute('data-preview-target');
            const selectedPreviewId = this.getAttribute('data-selected-preview');

            document.getElementById(hiddenInputId).value = value;
            document.getElementById(mainPreviewId).innerHTML = svgContent;
            document.getElementById(selectedPreviewId).innerHTML = svgContent;

            const optionsContainer = this.parentElement;
            const trigger = document.querySelector(`[data-target="${optionsContainer.id}"]`);
            optionsContainer.classList.remove('active');
            trigger.classList.remove('active');
        });
    });

    window.addEventListener('click', function() {
        document.querySelectorAll('.custom-select-options.active').forEach(options => {
            options.classList.remove('active');
        });
        document.querySelectorAll('.custom-select-trigger.active').forEach(trigger => {
            trigger.classList.remove('active');
        });
    });
});
