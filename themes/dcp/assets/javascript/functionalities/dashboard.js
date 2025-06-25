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

            $( '.modal-confirm' ).fadeIn( 200, function() {});

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





        function _modal_confirm_open( title, body, actions ) {

            const $this = $( '.modal-confirm' );

            $this.find( 'h3' ).text( title );
            $this.find( '.is-body' ).html( body );

            if( actions ) {
                $this.find( '.is-actions' ).show();
            } else {
                $this.find( '.is-actions' ).hide();
            }

            $this.fadeIn( 200, function() {

            });

        }
        function _modal_confirm_close() {}

        $( '.modal-confirm' ).each( function () {

            const $this = $( this );

            $this.find( '.is-close' ).on( 'click', function() {

                $this.fadeOut( 200, function() {});

            });

        });
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
                _modal_confirm_open(
                    'Validação de formulário',
                    'Verifique os campos do formulário. Todos os campos devem ter pelo menos 5 caracteres.',
                    false
                );
                $this.addClass('is-blocked');
                $this.removeClass('is-sendable');
                return;
            } else {
                $this.removeClass('is-blocked');
                $this.addClass('is-sendable');
            }

            if( !$this.hasClass( 'is-sendable' ) ) {

                _modal_confirm_open(
                    'Validação de formulário',
                    'O formulário não está correto para enviar, verifique os campos e as instruções',
                    false
                );

            } else {

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
                            _modal_confirm_open(
                                response.data.title,
                                response.data.message,
                                false
                            );
                            window.location.reload();
                        } else {
                            _modal_confirm_open(
                                response.data.title,
                                response.data.message,
                                false
                            );
                        }

                    })
                    .catch(error => {});
            }

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
