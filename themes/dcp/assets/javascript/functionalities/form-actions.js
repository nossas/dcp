document.addEventListener('DOMContentLoaded', () => {
    const updateProgress = (index) => {
        const circles = document.querySelectorAll('.step-circle');
        circles.forEach((circle, i) => {
        circle.classList.add(i <= index ? 'active' : 'inactive');
        });
    };

    const showStep = (index) => {
        steps.forEach((step, i) => {
            step.classList.toggle('active', i === index);
        });
        updateProgress(index);
    };

    const steps = document.querySelectorAll('.step');
    let currentStep = 0;

    document.querySelectorAll('.next').forEach(btn => {
        btn.addEventListener('click', () => {
            if (currentStep < steps.length - 1) {
                currentStep++;
                showStep(currentStep);
            }
        });
    });

    document.querySelectorAll('.prev').forEach(btn => {
        btn.addEventListener('click', () => {
            if (currentStep > 0) {
                currentStep--;
                showStep(currentStep);
            }
        });
    });

    // Evita que envie o form antes de chegar ao final
    const form = document.getElementById('multiStepForm');
    form.addEventListener('submit', e => {
        e.preventDefault();
        currentStep++;
        showStep(currentStep);
        // Aqui você pode fazer um fetch/ajax ou deixar que envie normalmente
        // form.submit();
    });

    showStep(currentStep); // Inicializa
});
