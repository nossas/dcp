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

        // console.log( 'loaded', loaded );
        // console.log( 'this.loadedBytes', this.loadedBytes );
        // console.log( 'this.totalBytes', this.totalBytes );
        // console.log( 'percent', percent );
        // console.log( 'Math.min(percent, 100)', Math.min(percent, 100) );
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
        //__scroll.update();
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

    function _mock_ajax_dashboard() {
        $.ajax({
            url: './',
            type: 'POST',
            data: {
                action: 'dashboard_get_risks',
                filter: 'lasted'
            },
            beforeSend: function() {
                $( '.tabs__panels .post-card, .tabs__panels .message-response, .tabs__panels .tabs__panel__pagination' ).hide();
                $( '.tabs__panels .dashboard-content-skeleton' ).show();
            },
            success: function() {
                //
            },
            error: function () {

            },
            complete: function() {
                $( '.tabs__panels.is-active .dashboard-content-skeleton' ).hide();
                $( '.tabs__panels.is-active .post-card, .tabs__panels.is-active .message-response, .tabs__panels .tabs__panel__pagination' ).show();
            }
        });
    }

    $( document ).ready( function() {

        // TODO: COMPORTAMENTO MOCK TAB PANELS ( componentizar / usar Alpine já existente )
        $( '.tabs__header a' ).on('click', function() {

            const $this = $( this );
            const tab = $this.attr( 'href' );

            $( '.tabs__header a, .tabs__panels' ).removeClass( 'is-active' );
            $( '.tabs__panels' ).hide();

            switch ( tab ) {

                case '#aprovacao':

                    $this.addClass( 'is-active' );
                    $( '#riscosAprovacao' ).show().addClass( 'is-active' );

                    break;


                case '#publicados':

                    $this.addClass( 'is-active' );
                    $( '#riscosPublicados' ).show().addClass( 'is-active' );

                    break;

                case '#arquivados':

                    $this.addClass( 'is-active' );
                    $( '#riscosArquivados' ).show().addClass( 'is-active' );

                    break;

                default:

                    break;

            }

            $( '.tabs__panels.is-active .dashboard-content-skeleton' ).hide();
            $( '.tabs__panels.is-active .post-card, .tabs__panels.is-active .message-response, .tabs__panels .tabs__panel__pagination' ).show();

        });

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

        $( '.asset-item-preview .is-show-hide' ).on( 'click', function() {

            $( '.modal-confirm' ).fadeIn( 200, function() {});

        });

        $( '.modal-asset-fullscreen' ).each( function () {

            const $this = $( this );

            $this.find( '.is-close, .button' ).on( 'click', function() {

                $this.fadeOut( 200, function() {});

            });

        });

        $( '.asset-item-preview .is-fullscreen' ).on( 'click', function() {

            $( '.modal-asset-fullscreen' ).fadeIn( 200, function() {});

        });

        $( '.asset-item-preview-actions .is-delete' ).on( 'click', function() {
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
                    console.log("Item excluído");
                    $this.parent().parent().remove();
                },
                //customConfirmText: "Arquivar",
                //onCustomConfirm: function () { console.log("Item arquivado"); }
            });
        });

        $( '.is-edit-input' ).each( function () {

            const $this = $( this );

            $this.on( 'click', function() {
                $this.parent().find( '.input, .textarea, .select' ).removeAttr( 'readonly disabled' ).focus();
                $this.css({
                    opacity : 0.5,
                    cursor : 'not-allowed'
                });
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

            //TODO: REFACTORY P/ COMPONENTES/LIBRARY-JS
            let isValid = true;

            $this.find( '.input, .textarea' ).each( function () {
                const $field = $(this);
                const value = $field.val().trim();

                if (!value || value.length < 5) {
                    isValid = false;
                    $field.addClass('is-invalid');
                    return false;
                } else {
                    $field.removeClass('is-invalid');
                }
            });
            $this.find( '.select' ).each( function () {
                const $field = $(this);
                //TODO: VALIDATION SELECT
            });
            $this.find( '.checkbox' ).each( function () {
                const $field = $(this);
                //TODO: VALIDATION CHECKBOX
            });

            if (!isValid) {
                const $this = $( this );

                custom_modal_confirm({
                    title: "Validação de formulário",
                    description: "Verifique os campos do formulário. Todos os campos devem ter pelo menos 5 caracteres.",

                    cancelText: "Voltar",
                    onCancel: function () {},

                    confirmText: "OK",
                    onConfirm: function () {}
                });

                $this.addClass('is-blocked');
                $this.removeClass('is-sendable');
                return;
            }
            else {
                $this.removeClass('is-blocked');
                $this.addClass('is-sendable');
            }

            if( !$this.hasClass( 'is-sendable' ) ) {

                custom_modal_confirm({
                    title: "Validação de formulário",
                    description: "O formulário não está correto para enviar, verifique os campos e as instruções",

                    cancelText: "Voltar",
                    onCancel: function () {},

                    confirmText: "VERIFICAR",
                    onConfirm: function () {}
                });

            }
            else {

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
            }

        });

        $( '#riscoSingleForm .is-archive' ).on( 'click', function () {
            $( 'input[name="post_status"]' ).val( 'pending' );
            $( '#riscoSingleForm' ).submit();

        });
        $( '#riscoSingleForm .is-publish' ).on( 'click', function () {
            $( 'input[name="post_status"]' ).val( 'publish' );
            $( '#riscoSingleForm' ).submit();
        });
        $( '#riscoSingleForm .is-save' ).on( 'click', function () {
            $( 'input[name="post_status"]' ).val( $( 'input[name="post_status_current"]' ).val() );
            $( '#riscoSingleForm' ).submit();
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


        // TODO: REFECTORY P/ COMPONENTE DE LOADING TIPO SKELETON
        $( '.dashboard-content-skeleton' ).hide();
        setTimeout( function() {
            $( '.dashboard-content-single .dashboard-content-skeleton' ).remove();
        }, 3000 );

        $( '.is-select-load-category' ).each( function () {
            const $this = $( this );
            $.ajax({
                url: $this.attr( 'data-endpoint' ),
                type: 'GET',
                data: {},
                beforeSend: function() {
                    $this.html( '<option>CARREGANDO...</option>' );
                    $this.parent().find( '.is-edit-input' ).hide();
                    $this.parent().find( '.is-loading' ).show();
                },
                success: function( response ) {
                    $this.html( '<option>SELECIONE UMA CATEGORIA</option>' );
                    console.log( response );

                    response.forEach( function( item ) {
                        if( item.parent === 0) {
                            $this.append( '<option value="' + item.id + '">' + item.name + '</option>' );
                        }
                    });
                },
                error: function () {
                    $this.html( '<option>ERROR</option>' );
                },
                complete: function() {
                    $this.parent().find( '.is-edit-input' ).show();
                    $this.parent().find( '.is-loading' ).hide();
                }
            });
        });

        $( '.is-chip-load-subcategory' ).each( function () {
            const $this = $( this );
            $.ajax({
                url: $this.attr( 'data-endpoint' ),
                type: 'GET',
                data: {},
                beforeSend: function() {
                    $this.val( 'CARREGANDO . . .' );
                    $this.parent().find( '.is-edit-input' ).hide();
                    $this.parent().find( '.is-loading' ).show();
                },
                success: function( response ) {
                    console.log( response );

                    let _text = '';
                    response.forEach( function( item ) {
                        //_text += '<div class="chip is-chip-subcategory">' + item.name + '</div>';
                        _text += item.name + ', ';
                    });

                    $this.val( _text );
                },
                error: function () {
                    $this.val( 'ERROR' );
                },
                complete: function() {
                    $this.parent().find( '.is-edit-input' ).show();
                    $this.parent().find( '.is-loading' ).hide();
                }
            });
        });

    });

});


document.addEventListener("DOMContentLoaded", function () {
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
