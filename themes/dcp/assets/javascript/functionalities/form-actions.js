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

    const form = document.getElementById('multiStepForm');
    form.addEventListener('submit', e => {
        e.preventDefault();
        currentStep++;
        showStep(currentStep);
    });

    showStep(currentStep);
});
