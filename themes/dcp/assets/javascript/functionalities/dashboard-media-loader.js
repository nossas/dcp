
class MediaLoader {

    constructor( options = {} ) {
        this.targetClass = options.targetClass || '.is-load-now';
        this.progressBar = options.progressBar || document.getElementById('progress-bar');
        this.progressContainer = options.progressContainer || document.getElementById('progress-container');
        this.config = {
            delayBetweenLoads: options.delayBetweenLoads || 100,
            maxRetries: options.maxRetries || 3
        };
        this.mediaElements = [];
        this.totalBytes = 0;
        this.loadedBytes = 0;
        this.init();
    }

    async init() {
        try {
            await this.calculateTotalSize();
            this.showProgress();
            this.startLoading();
        } catch (error) {
            console.error('Erro de inicialização:', error);
        }
    }

    async calculateTotalSize() {
        const mediaNodes = document.querySelectorAll(`${this.targetClass}[data-media-src]`);
        this.mediaElements = Array.from(mediaNodes);

        const sizeRequests = this.mediaElements.map(async (media) => {
            const url = media.dataset.mediaSrc;
            const size = await this.getFileSize(url);
            return { media, url, size };
        });

        const results = await Promise.all(sizeRequests);
        this.mediaElements = results.filter(item => item.size > 0);
        this.totalBytes = this.mediaElements.reduce((sum, item) => sum + item.size, 0);
    }

    async getFileSize(url) {
        try {
            const response = await fetch(url, { method: 'HEAD' });
            if (!response.ok) throw new Error(`HTTP ${response.status}`);
            return parseInt(response.headers.get('Content-Length')) || 0;
        } catch (error) {
            console.error(`Erro ao obter tamanho de ${url}:`, error);
            return 0;
        }
    }

    showProgress() {
        this.progressContainer.style.display = 'block';
        this.updateProgress(0);
    }

    updateProgress(loaded) {
        this.loadedBytes += loaded;
        const percent = (this.loadedBytes / this.totalBytes) * 100;
        this.progressBar.style.width = `${Math.min(percent, 100)}%`;
    }

    async startLoading() {
        for (const item of this.mediaElements) {
            try {
                await this.loadMediaItem(item);
                item.media.classList.remove(this.targetClass.replace('.', ''));
            } catch (error) {
                console.error(`Falha no carregamento de ${item.url}:`, error);
                item.media.style.display = 'none';
            }
        }
        this.progressContainer.style.display = 'none';
    }

    loadMediaItem(item) {
        return new Promise((resolve, reject) => {
            const xhr = new XMLHttpRequest();
            let retries = 0;

            xhr.open('GET', item.url);
            xhr.responseType = 'blob';

            xhr.onprogress = (e) => {
                if (e.lengthComputable) {
                    this.updateProgress(e.loaded - (item.loaded || 0));
                    item.loaded = e.loaded;
                }
            };

            xhr.onload = () => {
                if (xhr.status === 200) {
                    this.applyMedia(item.media, URL.createObjectURL(xhr.response));
                    resolve();
                } else if (retries < this.config.maxRetries) {
                    retries++;
                    xhr.send();
                } else {
                    reject(new Error(`Status ${xhr.status}`));
                }
            };

            xhr.onerror = () => {
                if (retries < this.config.maxRetries) {
                    retries++;
                    xhr.send();
                } else {
                    reject(new Error('Erro de rede'));
                }
            };

            xhr.send();
        });
    }

    applyMedia(element, url) {

        if (element.tagName === 'IMG') {
            element.src = url;
        } else if (element.tagName === 'SOURCE') {
            element.src = url;
            element.parentNode.load();
        }
        element.style.display = 'block';

    }
}



window.addEventListener('DOMContentLoaded', () => {
    if ( document.querySelector( '.is-load-now' ) ) {
        new MediaLoader({
            targetClass: '.is-load-now',
            progressBar: document.getElementById( 'mainProgressBar' ),
            progressContainer: document.getElementById( 'mainProgressContainer' )
        });
    }
    console.log( 'MEDIA LOADER LOADED' );
});
