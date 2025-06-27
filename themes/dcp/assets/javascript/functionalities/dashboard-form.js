
// EXEMPLO PARA GERAR THUMBNAIL DO VÍDEO E DA IMAGEM NATES DE ENVIAR PARA ENDPOINT




// {
//         action : 'form_single_risco_new',
//         endereco : 'Rua da Paz, 3099 - Ipanema, São Paulo/SP - CEP: 01000-394',
//         descricao : 'Lorem ipsum',
//         [ DEMAIS CAMPOS ]
// }



// Processar arquivos selecionados
fileInput.addEventListener('change', async (e) => {
    const files = Array.from(e.target.files);

    for (const file of files) {
        try {
            const thumbUrl = await generateThumbnail(file);
            createPreviewItem(file, thumbUrl);
        } catch (error) {
            console.error('Erro ao gerar thumbnail:', error);
        }
    }
});


// Gerar thumbnail (imagem ou vídeo)
function generateThumbnail(file) {

    return new Promise((resolve, reject) => {

        const isVideo = file.type.startsWith('video/');
        const url = URL.createObjectURL(file);

        if (!isVideo) {
            // Processar imagem
            const img = new Image();
            img.onload = () => {
                const canvas = document.createElement('canvas');
                const ctx = canvas.getContext('2d');

                // Dimensionar thumbnail (100x100)
                const maxSize = 100;
                let width = img.width;
                let height = img.height;

                if (width > height) {
                    height *= maxSize / width;
                    width = maxSize;
                } else {
                    width *= maxSize / height;
                    height = maxSize;
                }

                canvas.width = width;
                canvas.height = height;
                ctx.drawImage(img, 0, 0, width, height);
                URL.revokeObjectURL(url);
                resolve(canvas.toDataURL('image/jpeg'));
            };
            img.onerror = reject;
            img.src = url;
        } else {
            // Processar vídeo
            const video = document.createElement('video');
            video.onloadeddata = () => {
                video.currentTime = Math.min(0.5, video.duration / 2);
            };
            video.onseeked = () => {
                const canvas = document.createElement('canvas');
                canvas.width = video.videoWidth;
                canvas.height = video.videoHeight;
                const ctx = canvas.getContext('2d');
                ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
                URL.revokeObjectURL(url);

                // Redimensionar
                const resizedCanvas = document.createElement('canvas');
                const maxSize = 100;
                let width = video.videoWidth;
                let height = video.videoHeight;

                if (width > height) {
                    height *= maxSize / width;
                    width = maxSize;
                } else {
                    width *= maxSize / height;
                    height = maxSize;
                }

                resizedCanvas.width = width;
                resizedCanvas.height = height;
                const resizedCtx = resizedCanvas.getContext('2d');
                resizedCtx.drawImage(canvas, 0, 0, width, height);

                resolve(resizedCanvas.toDataURL('image/jpeg'));
            };
            video.onerror = reject;
            video.src = url;
        }
    });
}

// Criar item de pré-visualização
function createPreviewItem(file, thumbUrl) {
    const item = document.createElement('div');
    item.className = 'media-item';
    item.dataset.filename = file.name;

    const img = document.createElement('img');
    img.src = thumbUrl;
    img.className = 'media-thumb';

    const deleteBtn = document.createElement('button');
    deleteBtn.className = 'delete-btn';
    deleteBtn.innerHTML = '×';

    const progressContainer = document.createElement('div');
    progressContainer.className = 'progress-container';

    const progressBar = document.createElement('div');
    progressBar.className = 'progress-bar';

    progressContainer.appendChild(progressBar);
    item.append(img, deleteBtn, progressContainer);
    previewContainer.appendChild(item);

    // Iniciar upload
    uploadFile(file, progressBar, () => {
        progressBar.style.backgroundColor = '#4caf50';
        // Sucesso: manter ou remover preview conforme necessário
    }, (error) => {
        progressBar.style.backgroundColor = '#f44336';
        console.error('Erro no upload:', error);
    });

    // Remover item
    deleteBtn.addEventListener('click', () => {
        item.remove();
    });
}
