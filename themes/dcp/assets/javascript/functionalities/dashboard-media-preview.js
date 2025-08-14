import { showDashboardSnackbar } from "./snackbar";

function initializeMediaPreview() {
    const mediaUploadButtons = document.querySelectorAll('#mediaUploadButton, #mediaUploadButtonCover');
    const form = document.querySelector('#riscoSingleForm, #acaoSingleForm');

    if (mediaUploadButtons.length === 0 || !form) return;

    const emptyMessageContainer = document.querySelector('.input-media-preview-assets.is-empty');

    // Função unificada para gerenciar a visibilidade dos títulos e mensagens
    const updateMediaContainerState = () => {
        document.querySelectorAll('.media-preview-list').forEach(list => {
            const container = list.closest('.media-preview-container');
            if (!container) return;

            const title = container.querySelector('.media-preview-title');
            const hasNewItems = list.children.length > 0;
            const hasSavedItems = document.querySelector('.asset-item-preview') !== null;

            list.style.display = hasNewItems ? 'flex' : 'none';

            if (title) {
                title.style.display = hasNewItems ? 'block' : 'none';
            }

            if (emptyMessageContainer) {
                emptyMessageContainer.style.display = (hasNewItems || hasSavedItems) ? 'none' : 'block';
            }
        });
    };

    const createPreviewItemHTML = (file, objectURL, fileId) => {
        const thumb = file.type.startsWith('image/') ?
            `<img src="${objectURL}" alt="Preview" class="media-preview-thumb">` :
            `<div class="media-preview-thumb"></div>`;

        return `
            ${thumb}
            <div class="media-preview-info">
                <span class="media-preview-size">${(file.size / 1024).toFixed(2)} KB</span>
                <span class="media-preview-name">${file.name}</span>
            </div>
            <button type="button" class="media-preview-delete-new" data-file-id="${fileId}">&times;</button>
        `;
    };

    mediaUploadButtons.forEach(button => {
        button.addEventListener('click', () => {
            const mediaPreviewList = button.closest('.input-media').querySelector('.media-preview-list');
            if (!mediaPreviewList) return;
            const isMultiple = button.classList.contains('is-multiple');
            const fileInput = document.createElement('input');
            fileInput.type = 'file';
            fileInput.name = isMultiple ? 'media_files[]' : 'media_file';
            fileInput.multiple = isMultiple;
            fileInput.accept = isMultiple ? 'image/*,video/*' : 'image/*';
            const inputId = `file-input-${Date.now()}`;
            fileInput.id = inputId;

            fileInput.addEventListener('change', (event) => {
                const files = Array.from(event.target.files);
                if (!isMultiple) {
                    mediaPreviewList.innerHTML = '';
                    const storage = document.getElementById('file-input-storage');
                    if(storage) storage.innerHTML = '';
                }
                files.forEach(file => {
                    const fileId = `${inputId}-${file.name}`;
                    const previewItem = document.createElement('div');
                    previewItem.className = 'media-preview-item is-new';
                    previewItem.dataset.fileId = fileId;
                    const objectURL = URL.createObjectURL(file);
                    previewItem.innerHTML = createPreviewItemHTML(file, objectURL, fileId);
                    mediaPreviewList.appendChild(previewItem);
                });
                const fileInputStorage = document.getElementById('file-input-storage');
                if (fileInputStorage) fileInputStorage.appendChild(fileInput);

                const coverButton = document.getElementById('mediaUploadButtonCover');
                if (coverButton) {
                    coverButton.setAttribute('disabled', true);
                }

                updateMediaContainerState();
            });
            fileInput.click();
        });
    });

    document.body.addEventListener('click', function(event) {
        const target = event.target;

        if (target.classList.contains('media-preview-delete-new')) {
            const fileId = target.dataset.fileId;
            const previewItem = target.closest('.media-preview-item');
            const inputId = fileId.split('-').slice(0, 3).join('-');
            const inputToRemove = document.getElementById(inputId);
            if (inputToRemove) inputToRemove.remove();
            previewItem.remove();


            updateMediaContainerState();
            showDashboardSnackbar('Mídia removida da lista.', 'archive');
        }
        if (target.classList.contains('media-preview-delete') && target.dataset.attachmentId) {
            const attachmentId = target.dataset.attachmentId;
            const itemToRemove = document.getElementById(`media-item-${attachmentId}`);

            itemToRemove.remove();
            updateMediaContainerState();
            showDashboardSnackbar('Mídia removida com sucesso do servidor (simulação).', 'success');
            const coverButton = document.getElementById('mediaUploadButtonCover');
            if (coverButton) {
                coverButton.removeAttribute('disabled');
            }
        }
    });

    updateMediaContainerState();
}

document.addEventListener('DOMContentLoaded', initializeMediaPreview);
