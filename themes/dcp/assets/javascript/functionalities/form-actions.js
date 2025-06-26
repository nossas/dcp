document.addEventListener('DOMContentLoaded', () => {
    const steps = document.querySelectorAll('.step');
    const stageText = document.getElementById('formStepsStage');
    const header = document.getElementById('formStepsHeader');

    const stepLabels = [
        '1. Localização',
        '2. Descrição',
        '3. Foto e vídeo (opcional)',
        '4. Identificação',
        '5. Confirmar informações',
        ''
    ];

    const riskDraft = {
        endereco: '',
        latitude: 0,
        longitude: 0,
        midias: [],
        nome_completo: '',
        email: '',
        telefone: '',
        autoriza_contato: true,
        data_e_horario: '',
        descricao: '',
        situacao_de_risco: '',
    }

    const injectCheckIcon = (circle) => {
        if (!circle.querySelector('.step-circle__check-icon')) {
            const svg = document.createElementNS('http://www.w3.org/2000/svg', 'svg');
            svg.setAttribute('class', 'step-circle__check-icon');
            svg.setAttribute('width', '12');
            svg.setAttribute('height', '12');
            svg.setAttribute('viewBox', '0 0 12 12');
            svg.setAttribute('fill', 'none');
            svg.innerHTML = `<path d="M10.125 2.625C10.1743 2.625 10.223 2.63541 10.2686 2.6543C10.3141 2.67319 10.3558 2.70045 10.3906 2.73535C10.4255 2.77018 10.4528 2.81187 10.4717 2.85742C10.4906 2.90297 10.501 2.95166 10.501 3.00098C10.501 3.05029 10.4906 3.09898 10.4717 3.14453C10.4528 3.19009 10.4255 3.23177 10.3906 3.2666L5.14062 8.5166C5.10579 8.55152 5.06411 8.57875 5.01855 8.59766C4.97301 8.61655 4.92431 8.62695 4.875 8.62695C4.82569 8.62695 4.77699 8.61656 4.73145 8.59766C4.6859 8.57875 4.6442 8.55152 4.60938 8.5166L1.98438 5.8916C1.91398 5.82119 1.875 5.72554 1.875 5.62598C1.87501 5.52641 1.91397 5.43076 1.98438 5.36035C2.05478 5.28995 2.15043 5.25099 2.25 5.25098C2.34956 5.25098 2.44521 5.28996 2.51562 5.36035L4.875 7.7207L9.85938 2.73535C9.89421 2.70043 9.93589 2.6732 9.98145 2.6543C10.027 2.6354 10.0757 2.62501 10.125 2.625Z" fill="#F9F3EA"/>`;
            circle.appendChild(svg);
        }
    };

    const updateStepIndicators = (index) => {
        const circles = document.querySelectorAll('.step-circle');

        circles.forEach((circle, i) => {
            circle.classList.remove('active', 'completed');

            const span = circle.querySelector('span');
            const icon = circle.querySelector('.step-circle__check-icon');
            if (icon) icon.remove();
            if (span) span.style.opacity = '1';

            if (i < index) {
                circle.classList.add('completed');
                if (span) span.style.opacity = '0';
                injectCheckIcon(circle);
            } else if (i === index) {
                circle.classList.add('active');
            }
        });

        const lines = document.querySelectorAll('.line');
        lines.forEach((line, i) => {
            line.classList.toggle('active', i < index);
        });
    };

    const handleShowStep = (index) => {
        steps.forEach((step, i) => {
            step.classList.toggle('active', i === index);
        });

        updateStepIndicators(index);

        if (index >= 0 && index <= 4) {
            if (stageText) stageText.textContent = stepLabels[index];
            if (header) header.style.display = 'flex';
        } else {
            if (header) header.style.display = 'none';
        }

        const stepsContainer = document.querySelector('.form-steps__container');
        if (stepsContainer) {
            if (index === 5) {
                stepsContainer.style.display = 'none';
            } else {
                stepsContainer.style.display = '';
            }
        }

        if (index === 4) preencherResumo(); // <-- chamada aqui
    };


    let currentStep = 0;

    const preencherResumo = () => {
        const endereco = document.getElementById('reviewEndereco');
        if (endereco) endereco.textContent = riskDraft.endereco || '';

        const tipoTexto = document.getElementById('reviewTipoRiscoTexto');
        if (tipoTexto) tipoTexto.textContent = riskDraft.situacao_de_risco || '';

        const descricao = document.getElementById('reviewDescricao');
        if (descricao) descricao.textContent = riskDraft.descricao || '';

        const midiasContainer = document.getElementById('reviewMidias');
        if (midiasContainer) {
            midiasContainer.innerHTML = '';

            if (riskDraft.midias && riskDraft.midias.length > 0) {
                riskDraft.midias.forEach((file) => {
                    const url = URL.createObjectURL(file);
                    const item = document.createElement('div');
                    item.classList.add('multistepform__carousel-item');

                    if (file.type.startsWith('image')) {
                        item.innerHTML = `<img src="${url}" alt="Imagem enviada" style="max-width: 100px; border-radius: 8px;">`;
                    } else if (file.type.startsWith('video')) {
                        item.innerHTML = `<video src="${url}" controls style="max-width: 100px; border-radius: 8px;"></video>`;
                    }

                    midiasContainer.appendChild(item);
                });
            } else {
                midiasContainer.innerHTML = '<p>Nenhuma mídia enviada.</p>';
            }
        }
    };

    document.querySelectorAll('.multistepform__button-next').forEach(btn => {
        btn.addEventListener('click', () => {
            if (currentStep < steps.length - 2) {
                currentStep++;
                handleShowStep(currentStep);
            }
        });
    });

    document.querySelectorAll('.prev').forEach(btn => {
        btn.addEventListener('click', () => {
            if (currentStep > 0) {
                currentStep--;
                handleShowStep(currentStep);
            }
        });
    });

    const form = document.getElementById('multiStepForm');
    form.addEventListener('submit', async (e) => {
        e.preventDefault();
        riskDraft.data_e_horario = new Date().toISOString();
        const success = await submitData(riskDraft);
        if (success) {
            currentStep++;
            handleShowStep(currentStep);
        } else {
            alert('Erro ao enviar o formulário. Tente novamente.');
        }
    });

    setTimeout(() => {
        handleShowStep(currentStep);
    }, 50);

    for (const textField of ['endereco', 'nome_completo', 'email', 'telefone', 'descricao']) {
        const input = document.querySelector(`[name="${textField}"]`);

        input.addEventListener('input', (event) => {
            riskDraft[textField] = event.target.value;
        });
    }

    for (const checkboxField of ['autoriza_contato']) {
        const input = document.querySelector(`[name="${checkboxField}"]`);

        input.addEventListener('input', (event) => {
            riskDraft[checkboxField] = event.target.checked;
        });
    }

    const midiaInput = document.querySelector('input[name="media_files[]"]');
    if (midiaInput) {
        midiaInput.addEventListener('change', (event) => {
            const files = Array.from(event.target.files);
            riskDraft.midias = files;
        });
    }

    document.querySelectorAll('input[name="situacao_de_risco"]').forEach(radio => {
        radio.addEventListener('change', (event) => {
            if (event.target.checked) {
                riskDraft.situacao_de_risco = event.target.value;
            }
        });
    });

    document.querySelector('input[name="endereco"]').addEventListener('change', async (event) => {
        const {
            address_suffix,
            rest_url
        } = globalThis.hl_form_actions_data
        const address = event.target.value;
        const fullAddress = address + address_suffix
        const res = await fetch(`${rest_url}?address=${encodeURIComponent(fullAddress)}`, {
            method: 'POST',
        })
        if (res.ok) {
            try {
                const json = await res.text()
                const data = JSON.parse(json)
                if (data) {
                    riskDraft.latitude = data.lat
                    riskDraft.longitude = data.lon
                }
            } catch (error) {
                console.error(error)
            }
        }
    })

    async function submitData(data) {
        const formData = new FormData();
        formData.append('action', 'form_single_risco_new');
        for (const key in data) {
            if (key === 'midias') {
                data.midias.forEach((file, i) => {
                    formData.append(`media_files[]`, file);
                });
            } else {
                formData.append(key, data[key]);
            }
        }
        const res = await fetch(new URL('/wp-admin/admin-ajax.php', location.href), {
            method: 'POST',
            body: formData,

        })
        if (res.ok) {
            return true;
        } else {
            return false;
        }
    }

});
