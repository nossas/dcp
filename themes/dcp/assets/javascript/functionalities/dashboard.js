import Alpine from 'alpinejs';
import 'iconify-icon';

window.Alpine = Alpine;

//TODO: CRIAR UTILS
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
}

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

// TODO: COMPORTAMENTO MOCK jQUERY
jQuery(function($) {

    function _ajax_dele_media_by_id( post_id, attachment_id, success, error ) {
        $.ajax({
            url: $( '#riscoSingleForm' ).attr( 'data-action' ),
            type: 'POST',
            data: {
                action : 'form_single_risco_delete_attachment',
                post_id : post_id,
                attachment_id : attachment_id
            },
            beforeSend: function() {},
            success: function( response ) {
                if( typeof success === 'function' ) {
                    success( response );
                }
            },
            error: function ( response ) {
                if( typeof error === 'function' ) {
                    error( response );
                }
            },
            complete: function() {

            }
        });
    }

    $( document ).ready( function() {

        // TODO: COMPORTAMENTO MOCK TAB PANELS ( componentizar / usar Alpine já existente )
        $( '.tabs__header a' ).on('click', function() {

            const $this = $( this );
            const tab = $this.attr( 'href' );

            $( '.tabs__header a, .tabs__panels' ).removeClass( 'is-active' );
            $( '.tabs__header a .total' ).html( '' );
            $( '.tabs__panels' ).hide();

            switch ( tab ) {

                case '#aprovacao':

                    $this.addClass( 'is-active' );
                    $( '#riscosAprovacao' ).show().addClass( 'is-active' );
                    $this.find( '.total' ).html( '(' + $( '#riscosAprovacao .post-card' ).length + ')' );

                    break;


                case '#publicados':

                    $this.addClass( 'is-active' );
                    $( '#riscosPublicados' ).show().addClass( 'is-active' );
                    $this.find( '.total' ).html( '(' + $( '#riscosPublicados .post-card' ).length + ')' );

                    break;

                case '#arquivados':

                    $this.addClass( 'is-active' );
                    $( '#riscosArquivados' ).show().addClass( 'is-active' );
                    $this.find( '.total' ).html( '(' + $( '#riscosArquivados .post-card' ).length + ')' );

                    break;

                default:

                    break;

            }

            $( '.tabs__panels.is-active .dashboard-content-skeleton' ).hide();
            $( '.tabs__panels.is-active .post-card, .tabs__panels.is-active .message-response, .tabs__panels .tabs__panel__pagination' ).show();

        });
        $( '.tabs__header a.is-active' ).trigger( 'click' );
        // TODO: COMPORTAMENTO MOCK TAB PANELS ( componentizar / usar Alpine já existente )

        $( '.tabs__panel__content .post-card__excerpt-wrapped .read-more' ).on('click', function() {
            const $this = $( this );
            $this.hide();
            $this.parent().find( '.read-more-full' ).show();
        })

        $( 'img' ).each( function () {

            this.ondragstart = function() {
                return false;
            };

        });

        $( '.asset-item-preview .is-play' ).each( function () {

            $( this ).on( 'click', function() {
                $( this ).css( 'opacity', '0.3' );
                $( this ).parent().find( 'video' ).get(0).play();
            });

        });

        $( '.asset-item-preview .is-blur' ).on( 'click', function() {});

        $( '.modal-asset-fullscreen' ).each( function () {
            const $this = $( this );
            $this.find( '.is-close, .is-delete' ).on( 'click', function() {
                $this.fadeOut( 200, function() {});
            });
        });

        $( '.asset-item-preview .is-fullscreen' ).on( 'click', function() {
            const $this = $( this );
            const $modalFullscreen = $( '.modal-asset-fullscreen' );

            $modalFullscreen.find( 'img, video' ).hide();

            if( $this.parent().parent().find( 'img' ).length ) {
                $modalFullscreen.find( 'img' ).show();
                $modalFullscreen.find( 'img' ).attr( 'src', $this.parent().parent().find( 'img' ).attr( 'src') );
            }
            if( $this.parent().parent().find( 'video' ).length ) {
                $modalFullscreen.find( 'video' ).show();
                $modalFullscreen.find( 'video source' ).attr( 'src', $this.parent().parent().parent().find( 'video source' ).attr( 'src') );
                $modalFullscreen.find( 'video' )[0].load();
            }

            $modalFullscreen.find( '.is-delete' ).attr( 'data-id', $this.attr( 'data-id' ) );
            $modalFullscreen.find( '.is-download' ).attr( 'href', $this.attr( 'data-href' ) );

            $modalFullscreen.fadeIn( 200, function() {});
        });

        $( '.asset-item-preview-actions .is-delete, .modal-asset-fullscreen .is-delete' ).on( 'click', function() {
            const $this = $( this );
            custom_modal_confirm({
                title: "Confirmar Exclusão",
                description: "Deseja realmente excluir este item?",
                cancelText: "Voltar",
                onCancel: function () {
                    console.log("Ação cancelada");
                },
                confirmText: "Excluir",
                onConfirm: function () {
                    $this.parent().parent().css({
                        opacity : 0.5,
                        cursor : 'wait'
                    });
                    _ajax_dele_media_by_id(
                        $( '#riscoSingleForm' ).find( 'input[name="post_id"]' ).val(),
                        $this.attr( 'data-id' ),
                        function ( response ) {
                            custom_modal_confirm({
                                title: response.data.title,
                                description: response.data.message,

                                cancelText: "CANCELAR",
                                onCancel: function () {},

                                confirmText: "ATUALIZAR",
                                onConfirm: function () {
                                    window.location.reload();
                                }
                            });
                            $this.parent().parent().remove();
                        },
                        function () {

                        }
                    );
                }
            });
        });

        $( '.is-edit-input' ).each( function () {

            const $this = $( this );

            $this.on( 'click', function() {
                $this.hide();
                $this.parent().find( '.chips-checkbox' ).css({
                    height : 'auto',
                    opacity : 1
                });
                $this.parent().find( '.input, .textarea, .select' ).removeAttr( 'readonly disabled' ).focus();
            });

        });

        $( '#mediaUploadButton' ).on( 'click', function () {
            const $this = $( this );

            $this.parent().append( '<input id="mediaUploadInput" type="file" name="media_files[]" style="display:none;" accept="image/*,video/*" multiple >');
            $this.parent().find( '#mediaUploadInput' ).on( 'change', function ( e ) {
                const files = Array.from( e.target.files );

                $( '.input-media-uploader-progress' ).show().html( '' );

                files.forEach( function ( file ) {

                    $( '.input-media-uploader-progress' ).append( '<div class="progress is-small">' +
                        '<div class="progress-bar"><span>' +
                        formatFileSize( file.size ) + '</span><span>' +
                        file.name + '</span></div> </div>' );

                });

            }).trigger( 'click' );

        });

        $( '#selectCategory' ).on( 'change', function () {
            $( '.input-chips .chips-wrap').html( '' );
            $( '.chips-checkbox input[type="checkbox"]').prop( 'checked', false );
        });

        $( '.input-chips input[type="checkbox"]' ).on( 'change', function () {
            if( $( this ).is( ':checked' ) ) {
                $( '.input-chips .chips-wrap').append( '<span id="chips_' + $( this ).val() + '" class="chips"><iconify-icon icon="bi:check2"></iconify-icon>' + $( this ).attr( 'data-label' ) + '</span>' );
            } else {
                $( '.input-chips .chips-wrap').find( '#chips_' + $( this ).val() ).remove();
            }
        });

        $( '.input-chips .chips' ).each( function () {
            $( '#input_' + $( this ).attr( 'data-slug' ) ).prop( 'checked', true );
        });

        function custom_modal_confirm(options) {
            const {
                title,
                description,
                cancelText = 'Cancelar',
                confirmText = 'Confirmar',
                customConfirmText,
                onCancel,
                onConfirm,
                onCustomConfirm
            } = options;

            const $modal = $('.modal-confirm');

            // Preenche os conteúdos dinâmicos
            $modal.find('h3').text(title);
            $modal.find('.is-body p').html(description);
            $modal.find('.is-error').html( '' );
            $modal.find('.is-cancel').text(cancelText);
            $modal.find('.is-confirm span').text(confirmText);

            // Configura botão customizado (se fornecido)
            const $customBtn = $modal.find('.is-custom');
            if (customConfirmText) {
                $customBtn.text(customConfirmText).show();
            } else {
                $customBtn.hide();
            }

            // Remove eventos anteriores
            $modal.off('click', '.is-close, .is-cancel');
            $modal.off('click', '.is-confirm');
            $modal.off('click', '.is-custom');
            $(document).off('keyup.modal');

            // Evento de fechamento (cancelar)
            $modal.on('click', '.is-close, .is-cancel', function() {
                _modal_confirm_close();
                if (typeof onCancel === 'function') onCancel();
            });

            // Evento de confirmação principal
            $modal.on('click', '.is-confirm', function() {
                _modal_confirm_close();
                if (typeof onConfirm === 'function') onConfirm();
            });

            // Evento de confirmação customizada
            if (customConfirmText) {
                $modal.on('click', '.is-custom', function() {
                    _modal_confirm_close();
                    if (typeof onCustomConfirm === 'function') onCustomConfirm();
                });
            }

            // Fechar com ESC
            $(document).on('keyup.modal', function(e) {
                if (e.key === 'Escape') {
                    _modal_confirm_close();
                    if (typeof onCancel === 'function') onCancel();
                }
            });

            // Mostrar modal
            $modal.fadeIn(200);
        }

        function _modal_confirm_close() {
            $('.modal-confirm').fadeOut(200);
        }

        $( '#riscoSingleForm' ).on( 'submit', function ( e ) {
            const $this = $( this );

            const form = e.target;
            const formData = new FormData( form );

            $this.addClass( 'is-sending' );

            fetch( $this.attr( 'data-action' ), {
                method: 'POST',
                body: formData
            })
                .then( res => res.json() )
                .then( response => {
                    $this.removeClass( 'is-sending' );

                    if( response.success ) {

                        if( response.data.is_new !== undefined ) {

                            custom_modal_confirm({
                                title: response.data.title,
                                description: response.data.message,

                                cancelText: "Criar novo Risco",
                                onCancel: function () {
                                    $this.find( 'input, textarea, select' ).val( '' );
                                    $( '.input-chips .chips-wrap').html( '' );
                                    $( '#mediaUpload .input-media-uploader-progress').html( '' );
                                    $( '.chips-checkbox input[type="checkbox"]').prop( 'checked', false );
                                },
                                confirmText: "Visualizar Risco",
                                onConfirm: function () {
                                    window.location.href = window.location.origin + window.location.pathname + '?ver=riscos-single&risco_id=' + response.data.post_id;
                                }
                            });

                        } else {

                            custom_modal_confirm({
                                title: response.data.title,
                                description: response.data.message,

                                cancelText: "CANCELAR",
                                onCancel: function () {},

                                confirmText: "ATUALIZAR",
                                onConfirm: function () {

                                    window.location.reload();

                                }
                            });

                        }


                    } else {
                        custom_modal_confirm({
                            title: response.data.title,
                            description: response.data.message,

                            cancelText: "CANCELAR",
                            onCancel: function () {},

                            confirmText: "ATUALIZAR",
                            onConfirm: function () {
                                window.location.reload();
                            }
                        });
                    }

                })
                .catch(error => {});

        });
        $( '#riscoSingleForm .is-archive' ).on( 'click', function () {
            custom_modal_confirm({
                title: 'Arquivar esse registro de risco?',
                description: 'As informações não serão publicadas e poderão ser acessadas novamente na aba “Arquivados”',

                cancelText: "Cancelar",
                onCancel: function () {},

                confirmText: "Arquivar",
                onConfirm: function () {
                    $( 'input[name="post_status"]' ).val( 'pending' );
                    $( '#riscoSingleForm' ).submit();
                }
            });
        });
        $( '#riscoSingleForm .is-publish' ).on( 'click', function () {
            custom_modal_confirm({
                title: 'Publicar registro de risco?',
                description: 'Confirme que não há informações impróprias antes de publicar.',

                cancelText: "Cancelar",
                onCancel: function () {},

                confirmText: "Publicar",
                onConfirm: function () {
                    $( 'input[name="post_status"]' ).val( 'publish' );
                    $( '#riscoSingleForm' ).submit();
                }
            });
        });
        $( '#riscoSingleForm .is-save' ).on( 'click', function () {
            custom_modal_confirm({
                title: 'Publicar registro de risco?',
                description: 'Confirme que não há informações impróprias antes de publicar.',

                cancelText: "Cancelar",
                onCancel: function () {},

                confirmText: "Publicar alterações",
                onConfirm: function () {
                    $( 'input[name="post_status"]' ).val( 'publish' );
                    $( '#riscoSingleForm' ).submit();
                }
            });
        });
        $( '#riscoSingleForm .is-new' ).on( 'click', function () {
            custom_modal_confirm({
                title: 'Criar novo registro de risco?',
                description: 'Confirme que não há informações impróprias antes de publicar.',
                cancelText: "Cancelar",
                onCancel: function () {},
                confirmText: "Publicar alterações",
                onConfirm: function () {
                    $( '#riscoSingleForm' ).submit();
                }
            });
        });

        $( window ).on( 'dragover', function( event ) {
            event.preventDefault();

            $( '.input-media-uploader-progress' ).show();
            $( '.input-media' ).css({
                opacity : 0.5
            });

        });
        $( window ).on( 'dragleave', function( event ) {
            event.preventDefault();

            $( '.input-media-uploader-progress' ).hide();
            $( '.input-media' ).css({
                opacity : 1
            });

        });
        $( window ).on( 'drop', function( event ) {
            event.preventDefault();
            $( '.input-media-uploader-progress' ).hide();
            $( '.input-media' ).css({
                opacity : 1
            });
        });

    });
    $( window ).on( 'load', function() {

        $( 'body' ).attr( 'class', 'loading is-loaded' );

        $( '.tabs__panels.is-active .dashboard-content-skeleton' ).hide();
        $( '.tabs__panels.is-active .post-card, .tabs__panels.is-active .message-response, .tabs__panels .tabs__panel__pagination' ).show();
        $( '#dashboardInicio .tabs__panels .post-card' ).show();

        // TODO: REFECTORY P/ COMPONENTE DE LOADING TIPO SKELETON
        $( '.dashboard-content-skeleton' ).hide();
        setTimeout( function() {
            $( '.dashboard-content-single .dashboard-content-skeleton' ).remove();
        }, 3000 );

    });
});

document.addEventListener('DOMContentLoaded', function () {
    const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);

    console.log( 'DOCUMENT LOADED' );

    document.body.addEventListener("wheel", function(event) {
        if (event.deltaY < 0) {
            document.body.classList.add( 'is-scrolling-up' );
            document.body.classList.remove('is-scrolling-down' );
        } else {
            document.body.classList.add("is-scrolling-down");
            document.body.classList.remove("is-scrolling-up");
        }
    });
});

window.addEventListener('DOMContentLoaded', () => {
    Alpine.start();

    if ( document.querySelector( '.is-load-now' ) ) {
        new MediaLoader({
            targetClass: '.is-load-now',
            progressBar: document.getElementById( 'mainProgressBar' ),
            progressContainer: document.getElementById( 'mainProgressContainer' )
        });
    }
    console.log( 'WINDOW LOADED' );
});

window.addEventListener('resize', function () {
    console.log( 'WINDOW RESIZE' );
});

window.addEventListener('beforeunload', function(event) {

});

window.addEventListener('unload', function(event) {
    //console.log('I am the 4th and last one…');
});
