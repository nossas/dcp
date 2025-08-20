import { buildGallery } from "./action-review-gallery";
import { showDraggableMap } from "./geolocate-map";
import { until } from "../shared/wait";

document.addEventListener('DOMContentLoaded', () => {
    const steps = document.querySelectorAll('.step');
    const stageText = document.getElementById('formStepsStage');
    const header = document.getElementById('formStepsHeader');

    const form = document.getElementById('multiStepForm');
    const inputEndereco = form.querySelector('input[name="endereco"]');
    const inputEnderecoWrapper = inputEndereco.closest('.multistepform__input');
    const mapWrapper = document.querySelector('.multistepform__map-wrapper');

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

    let updateMarker = null;
    let hasDraggedMarker = false;
    let hasEditedAddress = false;


    function showSnackbar(message, type = 'error') {
        const overlay = document.getElementById('snackbar-overlay');
        const snackbar = document.getElementById('formSnackbar');
        if (!overlay || !snackbar) return;

        snackbar.classList.remove('is-error', 'is-info');

        if(type == 'error'){
            snackbar.classList.add('is-error')
        } else {
            snackbar.classList.add('is-info');
            snackbar.style.backgroundColor = '#51B2AF';
            snackbar.style.color = '#281414';

            setTimeout(() => {
                overlay.classList.remove('show');
            }, 5000);
        }

        snackbar.innerHTML = `
            <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                <path d="M9 0.000976562C11.3869 0.000976563 13.6764 0.949868 15.3643 2.6377C17.052 4.32541 17.9999 6.6142 18 9.00098C18 11.3879 17.0521 13.6774 15.3643 15.3652C13.6764 17.0531 11.3869 18.001 9 18.001C6.61323 18.0008 4.32443 17.0529 2.63672 15.3652C0.948891 13.6774 0 11.3879 0 9.00098C0.000128218 6.6142 0.949006 4.32541 2.63672 2.6377C4.32443 0.949982 6.61323 0.00110478 9 0.000976562ZM9.00293 11.251C8.70456 11.251 8.41801 11.3701 8.20703 11.5811C7.99626 11.7919 7.87806 12.0779 7.87793 12.376C7.87793 12.6743 7.99614 12.9609 8.20703 13.1719C8.41801 13.3829 8.70456 13.501 9.00293 13.501C9.30116 13.5009 9.58693 13.3827 9.79785 13.1719C10.0088 12.9609 10.1279 12.6743 10.1279 12.376C10.1278 12.0778 10.0087 11.7919 9.79785 11.5811C9.58693 11.3702 9.30116 11.251 9.00293 11.251ZM9 4.50098C8.85805 4.50112 8.71763 4.53127 8.58789 4.58887C8.45794 4.64659 8.34148 4.73146 8.24609 4.83691C8.15083 4.94227 8.07861 5.06626 8.03418 5.20117C7.98974 5.33624 7.97414 5.47961 7.98828 5.62109L8.38184 9.56641C8.3951 9.72121 8.4657 9.86556 8.58008 9.9707C8.69454 10.0758 8.84462 10.1346 9 10.1348C9.15556 10.1348 9.30632 10.0759 9.4209 9.9707C9.53527 9.86556 9.60588 9.72121 9.61914 9.56641L10.0127 5.62109C10.0268 5.47961 10.0112 5.33624 9.9668 5.20117C9.92237 5.06626 9.85015 4.94227 9.75488 4.83691C9.6595 4.73146 9.54303 4.64659 9.41309 4.58887C9.2832 4.5312 9.14211 4.50106 9 4.50098Z" fill="#ffff"/>
            </svg>
            <span>${message}</span>
        `;
        overlay.classList.add('show');
        snackbar.classList.add('show');

        // Esconde o snackbar após 3 segundos
        setTimeout(() => {
            overlay.classList.remove('show');
        }, 5000);
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

    let reviewingInfo = false;

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

        if (reviewingInfo) {
            reviewingInfo = false;
            showSnackbar('Voltamos ao início. Suas informações estão salvas, você pode editar ou continuar.', 'info');
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
        if (inputEnderecoWrapper) {
            inputEnderecoWrapper.classList.remove('has-error');
        }

        switch (stepIndex) {
            case 0:
            const enderecoInput = document.querySelector('input[name="endereco"]');
            const enderecoWrapper = enderecoInput.closest('.multistepform__input');
            const enderecoError = enderecoWrapper.querySelector('.error-message');
            const errorIcon = enderecoWrapper.querySelector('.error-icon');
            const mapError = mapWrapper.querySelector('.multistepform__input .error-message');

            // Resetando estados
            enderecoError.style.display = 'none';
            mapError.style.display = 'none';
            enderecoWrapper.classList.remove('has-error');

            const isEnderecoValid = riskDraft.endereco.trim() !== '';
            const areCoordsValid = riskDraft.latitude && riskDraft.longitude;

            if (!isEnderecoValid) {
                // Campo vazio
                enderecoError.textContent = 'O campo de endereço está vazio';
                enderecoError.style.display = 'block';
                enderecoWrapper.classList.add('has-error');
                if (errorIcon) errorIcon.style.top = '50%'; // 👉 centralizado
                return false;
            }

            if (!areCoordsValid) {
                // Endereço inválido
                mapError.textContent = 'Endereço inválido';
                mapError.style.display = 'block';
                enderecoWrapper.classList.add('has-error');
                if (errorIcon) errorIcon.style.top = '65%'; // 👉 mais abaixo
                return false;
            }

            return true;

            case 1:
                const isTipoRiscoValid = riskDraft.situacao_de_risco.trim() !== '';
                const isDescricaoValid = riskDraft.descricao.trim() !== '';
                return isTipoRiscoValid;

            case 2:
                return true;

            case 3:
                const nomeInput = document.querySelector('input[name="nome_completo"]');
                const telefoneInput = document.querySelector('input[name="telefone"]');

                nomeInput.closest('.multistepform__input').classList.remove('has-error');
                telefoneInput.closest('.multistepform__input').classList.remove('has-error');

                const isNomeValid = riskDraft.nome_completo.trim() !== '';
                const telefoneLimpo = riskDraft.telefone.replace(/\D/g, '');
                const isTelefoneValid = telefoneLimpo.length >= 10;

                if (!isNomeValid) {
                    nomeInput.closest('.multistepform__input').classList.add('has-error');
                }
                if (!isTelefoneValid) {
                    telefoneInput.closest('.multistepform__input').classList.add('has-error');
                }

                return isNomeValid && isTelefoneValid;

            case 4:
            const autorizaWrapper = autorizaInput.closest('.multistepform__accept-wrapper');

            autorizaWrapper.classList.remove('has-error');

            const isAutorizaValid = autorizaInput.checked;

            if (!isAutorizaValid) {
                autorizaWrapper.classList.add('has-error');
                showSnackbar('É necessário marcar esta opção.', 'error');
            }

            return isAutorizaValid;

            default:
            return true;
        }
    }
    document.querySelectorAll('.multistepform__button-next').forEach(btn => {
        btn.addEventListener('click', (e) => {
            if (btn.type === 'submit') {
                return;
            }

            e.preventDefault();

            if (validateStep(currentStep)) {
                if (currentStep < steps.length - 2) {
                    currentStep++;
                    handleShowStep(currentStep);
                }
            } else {
                switch (currentStep) {
                    case 0:
                        showSnackbar('Para continuar, informe um endereço ou marque no mapa.');
                        break;


                    case 1:
                        if (riskDraft.situacao_de_risco.trim() === '') {
                            showSnackbar('Escolha um tipo de risco (alagamento, lixo ou outros) para continuar.');
                        }

                        break;

                    case 3:
                        const autorizaInput = document.querySelector('input[name="autoriza_contato"]');
                        if (!autorizaInput.checked) {
                            showSnackbar('Marque a caixa de autorização para seguir.');
                        } else {
                            showSnackbar('Preencha nome e telefone para continuar.');
                        }
                        break;

                    default:
                        showSnackbar('Por favor, preencha os campos obrigatórios.');
                        break;
                }
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
    const skipMediaButton = document.querySelector('.multistepform__no-media-button');

    skipMediaButton.addEventListener('click', (e) => {

        currentStep = 3
        handleShowStep(currentStep)
    })

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


    // GEOLOCALIZAÇÃO P/ ENDEREÇO
    document.querySelector('input[name="endereco"]').addEventListener('change', (event) => {
        const address = event.target.value

        hasEditedAddress = address.length > 0

        if (hasEditedAddress && !hasDraggedMarker) {
            updateCoordinates(address)
        }
    })

    async function updateCoordinates (address) {
        const nextButton = document.querySelector('.multistepform__1 .multistepform__button-next')
        nextButton.disabled = true

        const { rest_url } = globalThis.hl_form_actions_data
        const res = await fetch(`${rest_url}/geocoding?address=${encodeURIComponent(address)}`, {
            method: 'POST',
            cache: 'force-cache',
        })

        try {
            if (res.ok) {
                const data = await res.json()
                if (data) {
                    riskDraft.latitude = data.lat
                    riskDraft.longitude = data.lon
                    updateMarker?.(data.lon, data.lat)
                    return [data.lon, data.lat]
                }
            }
        } finally {
            nextButton.disabled = false
        }
    }

    async function updateAddress (longitude, latitude, isDrag = false) {
        hasDraggedMarker ||= isDrag
        riskDraft.latitude = latitude
        riskDraft.longitude = longitude

        if (!hasEditedAddress) {
            const { rest_url } = globalThis.hl_form_actions_data
            const res = await fetch(`${rest_url}/reverse_geocoding?lat=${latitude}&lon=${longitude}`, {
                method: 'POST',
                cache: 'force-cache',
            })
            if (res.ok) {
                const { address } = await res.json()
                inputEndereco.value = address
                riskDraft.endereco = address
            }
        }
    }

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
        editandoResumo = true;
        reviewingInfo = true;
        currentStep = 0
        handleShowStep(currentStep)

    });

    if (inputEndereco) {
        inputEndereco.addEventListener('input', () => {
            if (inputEndereco.value.trim() !== '') {
                inputEnderecoWrapper.classList.remove('has-error');
            }
        });
    }

    const phoneInput = document.querySelector('input[name="telefone"]');

    if (phoneInput) {
        phoneInput.addEventListener('input', handlePhoneInput);
    }

    function handlePhoneInput(event) {
        const input = event.target;
        let value = input.value.replace(/\D/g, '');

        value = value.substring(0, 11);

        if (value.length > 10) {
            value = value.replace(/^(\d{2})(\d{5})(\d{4})/, '($1) $2-$3');
        } else if (value.length > 6) {
            value = value.replace(/^(\d{2})(\d{4})(\d{0,4})/, '($1) $2-$3');
        } else if (value.length > 2) {
            value = value.replace(/^(\d{2})(\d*)/, '($1) $2');
        } else {
            if (value.length > 0) {
                value = value.replace(/^(\d*)/, '($1');
            }
        }

        input.value = value;
    }

    async function getMap () {
        const mapEl = document.querySelector('.jeomap');

        await until(() => mapEl.dataset.map_id);
        const jeoMap = globalThis.jeomaps[mapEl.dataset.uui_id];

        return until(() => jeoMap.map);
    }

    const toggleMapButton = document.querySelector('.multistepform__button-map');
    let mapActivated = false;
    toggleMapButton.addEventListener('click', async () => {
        const map = await getMap();
        if (updateMarker) {
            const center = await updateCoordinates(riskDraft.endereco);
            if (center) {
                map.easeTo({ center, zoom: map.getZoom() });
            }
        } else if (!mapActivated) {
            mapActivated = true;
            mapWrapper.querySelector('.jeomap').style.display = '';
            updateMarker = await showDraggableMap(map, riskDraft, updateAddress);
        }
    })
});
