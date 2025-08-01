import { buildGallery } from "./action-review-gallery";

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

        if (index === 4) preencherResumo();
    };


    let currentStep = 0;

    const preencherResumo = () => {
        const endereco = document.getElementById('reviewEndereco');
        if (endereco) endereco.textContent = riskDraft.endereco || '';

        const tipoTexto = document.getElementById('reviewTipoRiscoTexto');
        if (tipoTexto) {
            const radio = document.querySelector(`input[name="situacao_de_risco"][value="${riskDraft.situacao_de_risco}"]`);
            const label = radio?.nextElementSibling?.textContent;
            tipoTexto.textContent = label || riskDraft.situacao_de_risco || '';
        }

        const descricao = document.getElementById('reviewDescricao');
        if (descricao) descricao.textContent = riskDraft.descricao || '';

        const midiasContainer = document.getElementById('reviewMidias');
        if (midiasContainer) {
            buildGallery(midiasContainer, riskDraft.midias, editandoResumo);
            const midiaMessage = midiasContainer.querySelector('p');

            if (riskDraft.midias && riskDraft.midias.length > 0) {
                midiaMessage.innerHTML = '';
            } else {
                midiaMessage.innerHTML = 'Nenhuma mídia enviada.';
            }

            // Exibe input para adicionar mídias só se estiver em modo edição
            if (editandoResumo) {
                if (document.querySelector('.add-media-btn')) {
                    return;
                }

                const addMediaWrapper = document.createElement('div');

                addMediaWrapper.style.marginTop = '10px';

                const addMediaLabel = document.createElement('label');
                addMediaLabel.classList.add('add-media-btn');
                addMediaLabel.htmlFor = 'addMidiaFromResumo';
                addMediaLabel.style.display = 'inline-flex';
                addMediaLabel.style.alignItems = 'center';
                addMediaLabel.style.gap = '6px';
                addMediaLabel.style.backgroundColor = '#B83D13';
                addMediaLabel.style.color = '#fff';
                addMediaLabel.style.borderRadius = '999px';
                addMediaLabel.style.padding = '6px 12px';
                addMediaLabel.style.cursor = 'pointer';
                addMediaLabel.style.fontSize = '14px';

                addMediaLabel.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" width="14" height="14" viewBox="0 0 20 20" fill="none">
                    <path d="M10 4V16M4 10H16" stroke="white" stroke-width="2" stroke-linecap="round"/>
                </svg>
                Adicionar mídia
            `;

                const addMediaInput = document.createElement('input');
                addMediaInput.type = 'file';
                addMediaInput.accept = 'image/*,video/*';
                addMediaInput.multiple = true;
                addMediaInput.id = 'addMidiaFromResumo';
                addMediaInput.hidden = true;

                addMediaInput.addEventListener('change', (e) => {
                    const newFiles = Array.from(e.target.files);
                    riskDraft.midias = riskDraft.midias.concat(newFiles);
                    preencherResumo();
                });

                addMediaWrapper.appendChild(addMediaLabel);
                addMediaWrapper.appendChild(addMediaInput);
                midiasContainer.appendChild(addMediaWrapper);
            }
        }
    };

    function validateStep(stepIndex) {
        switch (stepIndex) {
            case 0:
                return riskDraft.endereco.trim() !== '';
            case 1:
                return riskDraft.descricao.trim() !== '';
            case 2:
                return true;
            case 3:
                return (
                    riskDraft.nome_completo.trim() !== '' &&
                    riskDraft.email.trim() !== '' &&
                    riskDraft.telefone.trim() !== ''
                );
            case 4:
                return true;
            default:
                return true;
        }
    }

    document.querySelectorAll('.multistepform__button-next').forEach(btn => {
        btn.addEventListener('click', () => {
            if (validateStep(currentStep)) {
                if (currentStep < steps.length - 2) {
                    currentStep++;
                    handleShowStep(currentStep);
                }
            } else {
                alert('Por favor, preencha os campos obrigatórios antes de continuar.');
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

    const previewContainer = document.getElementById('mediaPreview');

    if (midiaInput && previewContainer) {
        previewContainer.innerHTML = '';
        riskDraft.midias = [];

        midiaInput.addEventListener('change', (event) => {
            const files = Array.from(event.target.files);

            files.forEach((file, index) => {
                const fileType = file.type;
                const reader = new FileReader();

                reader.onload = function (e) {
                    const wrapper = document.createElement('div');
                    wrapper.className = 'media-item';

                    const deleteBtn = document.createElement('button');
                    deleteBtn.className = 'media-delete';
                    deleteBtn.type = 'button';
                    deleteBtn.innerHTML = `
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 24 24" fill="#B83D13">
                            <path d="M18.3 5.71a1 1 0 0 0-1.41 0L12 10.59 7.11 5.7a1 1 0 1 0-1.41 1.42L10.59 12l-4.89 4.89a1 1 0 1 0 1.41 1.41L12 13.41l4.89 4.89a1 1 0 0 0 1.41-1.41L13.41 12l4.89-4.89a1 1 0 0 0 0-1.4z"/>
                        </svg>
                    `;

                    deleteBtn.addEventListener('click', () => {
                        wrapper.remove();
                        riskDraft.midias.splice(index, 1);
                    });

                    if (fileType.startsWith('image/')) {
                        const img = document.createElement('img');
                        img.src = e.target.result;
                        wrapper.appendChild(img);
                    } else if (fileType.startsWith('video/')) {
                        const video = document.createElement('video');
                        video.src = e.target.result;
                        video.controls = true;
                        wrapper.appendChild(video);
                    } else {
                        const span = document.createElement('span');
                        span.className = 'file-name';
                        span.textContent = file.name;
                        wrapper.appendChild(span);
                    }

                    wrapper.appendChild(deleteBtn);
                    previewContainer.appendChild(wrapper);
                };

                reader.readAsDataURL(file);
            });
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

    const editarBtn = document.getElementById('editarResumo');
    const enviarBtn = document.getElementById('enviarResumo');
    let editandoResumo = false;

    editarBtn.addEventListener('click', (e) => {
        e.preventDefault();

        const reviewEndereco = document.getElementById('reviewEndereco');
        const reviewTipo = document.getElementById('reviewTipoRiscoTexto');
        const reviewDescricao = document.getElementById('reviewDescricao');

        if (!editandoResumo) {
            editandoResumo = true;
            editarBtn.innerHTML = 'Salvar';
            if (enviarBtn) enviarBtn.disabled = true;

            if (reviewEndereco) {
                reviewEndereco.innerHTML = `<input type="text" value="${riskDraft.endereco || ''}" />`;
            }

            if (reviewTipo) {
                const select = document.createElement('select');
                const radioInputs = document.querySelectorAll('input[name="situacao_de_risco"]');
                const opcoes = Array.from(radioInputs).map(input => input.value);

                opcoes.forEach(opcao => {
                    const opt = document.createElement('option');
                    opt.value = opcao;
                    opt.textContent = opcao;
                    if (opcao === riskDraft.situacao_de_risco) opt.selected = true;
                    select.appendChild(opt);
                });

                reviewTipo.innerHTML = '';
                reviewTipo.appendChild(select);
            }

            if (reviewDescricao) {
                reviewDescricao.innerHTML = `<textarea>${riskDraft.descricao || ''}</textarea>`;
            }

            preencherResumo(); // Atualiza mídia com botões visíveis

        } else {
            editandoResumo = false;
            editarBtn.innerHTML = 'Editar';
            if (enviarBtn) enviarBtn.disabled = false;

            const inputEndereco = reviewEndereco.querySelector('input');
            if (inputEndereco) {
                riskDraft.endereco = inputEndereco.value;
                reviewEndereco.textContent = riskDraft.endereco;
            }

            const selectTipo = reviewTipo.querySelector('select');
            if (selectTipo) {
                riskDraft.situacao_de_risco = selectTipo.value;
                reviewTipo.textContent = riskDraft.situacao_de_risco;

                const tipoWrapper = document.getElementById('reviewTipoRisco');
                tipoWrapper.classList.forEach(cl => {
                    if (cl.startsWith('tipo-')) tipoWrapper.classList.remove(cl);
                });

                const slug = riskDraft.situacao_de_risco.toLowerCase().normalize('NFD')
                    .replace(/[\u0300-\u036f]/g, '').replace(/\s+/g, '-');
                tipoWrapper.classList.add(`tipo-${slug}`);
            }

            const textareaDescricao = reviewDescricao.querySelector('textarea');
            if (textareaDescricao) {
                riskDraft.descricao = textareaDescricao.value;
                reviewDescricao.textContent = riskDraft.descricao;
            }

            preencherResumo(); // Atualiza mídia com botões ocultos
        }
    });
});
