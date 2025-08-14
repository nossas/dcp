import Alpine from 'alpinejs';
import 'iconify-icon';

window.Alpine = Alpine;
const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);

//TODO: CRIAR UTILS
function formatFileSize(bytes) {
    if (bytes === 0) return '0 Bytes';
    const k = 1024;
    const sizes = ['Bytes', 'KB', 'MB', 'GB'];
    const i = Math.floor(Math.log(bytes) / Math.log(k));
    return parseFloat((bytes / Math.pow(k, i)).toFixed(2)) + ' ' + sizes[i];
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

// TODO: COMPORTAMENTO MOCK jQUERY
jQuery(function($) {
    function _ajax_dele_media_by_id( post_id, attachment_id, success, error ) {
        $.ajax({
            url: $( '.dashboard-content-single form' ).attr( 'data-action' ),
            type: 'POST',
            data: {
                action : 'form_single_delete_attachment',
                post_id : post_id,
                attachment_id : attachment_id
            },
            beforeSend: function() {
                $( '.loading-global' ).fadeIn( 400 );
            },
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
                $( '.loading-global' ).fadeOut( 400 );
            }
        });
    }
    function selectTipoApoio( tipo_apoio ) {
        console.log( 'tipo_apoio', tipo_apoio );
        switch ( tipo_apoio ) {

            case 'locais-seguros' :
                $( '#apoioSingleForm .is-subcategory, #apoioSingleForm .is-website, #apoioSingleForm .is-info-extra' ).hide();
                $( '#apoioSingleForm .is-media-attachments' ).show();
                break;

            case 'iniciativas-locais' :
                $( '#apoioSingleForm .is-subcategory, #apoioSingleForm .is-website, #apoioSingleForm .is-info-extra' ).hide();
                $( '#apoioSingleForm .is-media-attachments' ).show();
                break;

            case 'cacambas' :
                $( '#apoioSingleForm .is-subcategory, #apoioSingleForm .is-website, #apoioSingleForm .is-info-extra' ).hide();
                $( '#apoioSingleForm .is-media-attachments' ).hide();
                break;

            case 'quem-acionar' :
            case 'agua' :
            case 'assistencia-social' :
            case 'energia-eletrica' :
            case 'lixo' :
            case 'outros' :
            case 'saude' :
                $( '#apoioSingleForm .is-subcategory, #apoioSingleForm .is-website, #apoioSingleForm .is-info-extra' ).show();
                $( '#apoioSingleForm .is-media-attachments' ).hide();
                break;

            default :
                $( '#apoioSingleForm .is-subcategory, #apoioSingleForm .is-website, #apoioSingleForm .is-info-extra, #apoioSingleForm .is-media-attachments' ).hide();
                $( '#apoioSingleForm input, #apoioSingleForm textarea' ).prop( 'disabled', true );
                break;
        }
    }
    function gelocation_onblur_address( $this ) {

        $.ajax({
            url: window.location.origin + '/wp-json/hacklabr/v2/geocoding/',
            type: 'GET',
            data: {
                address : $this.val(),
            },
            beforeSend: function() {
                $( '.loading-global' ).fadeIn( 400 );
                $this.prop( 'disabled', true );
                $this.parent().find( '.is-loading' ).show();
                $this.parent().find( '.is-success' ).hide();
                $this.parent().find( '.is-edit-input' ).hide();
                $this.parent().find( '.is-error-geolocation' ).hide();
                $( '.dashboard-content-single input[name="latitude"]' ).val( '' );
                $( '.dashboard-content-single input[name="longitude"]' ).val( '' );
            },
            success: function( response ) {
                if( response.lat || response.lon ) {
                    //$this.val( response.address )
                    $( '.dashboard-content-single input[name="full_address"]' ).val( response.full_address || response.address );
                    $( '.dashboard-content-single input[name="latitude"]' ).val( response.lat );
                    $( '.dashboard-content-single input[name="longitude"]' ).val( response.lon );
                    $this.parent().find( '.is-success' ).show();
                } else {
                    $this.parent().find( '.is-error-geolocation' ).show();
                }
            },
            error: function ( response ) {
                $this.parent().find( '.is-error-geolocation' ).show();
                $this.parent().find( '.is-edit-input' ).show();
            },
            complete: function() {
                $( '.loading-global' ).fadeOut( 400 );
                $this.parent().find( '.is-loading' ).hide();
                $this.prop( 'disabled', false );
            }
        });

    }
    // TODO: COMPONENT
    function custom_modal_confirm(options) {
        const {
            title,
            description,
            error = null,
            cancelText = 'Fechar',
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
        $modal.find('.is-confirm span').text(confirmText);

        if( cancelText ) {
            $modal.find('.is-cancel').text(cancelText).show();
        } else {
            $modal.find('.is-cancel').text('').hide();
        }

        if( error ) {
            $modal.find('.is-error').html( error ).show();
        } else {
            $modal.find('.is-error').html( '' ).hide();
        }

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
    // TODO: COMPONENT
    function ajustarTabs() {
        const $tabsHeader = $('.tabs__header');
        if (!$tabsHeader.length) return;

        const $wrap = $tabsHeader.find('.tabs__header-wrap');
        const $links = $wrap.find('a');

        let totalWidth = 0;
        $links.each(function () {
            totalWidth += ( $(this).outerWidth(true) + 5 );
        });

        $wrap.css('width', totalWidth);

        const $active = $links.filter('.is-active');
        if ($active.length) {
            $tabsHeader.animate({ scrollLeft : $active.position().left }, 200 );
        }
    }
    function debounce(func, delay) {
        let timeout;
        return function () {
            clearTimeout(timeout);
            timeout = setTimeout(func, delay);
        };
    }

    $( document ).ready( function() {

        ajustarTabs();

        // DASHBOARD GERAL
        $( '.dashboard-content-cards .post-card__excerpt-wrapped .read-more' ).on('click', function() {
            const $this = $( this );
            $this.hide();
            $this.parent().find( '.read-more-etc' ).hide();
            $this.parent().find( '.read-more-full' ).show();
        })
        $( 'img' ).each( function () {

            this.ondragstart = function() {
                return false;
            };

        });
        $( '#btnOpenMenuMobile' ).on( 'click', function () {
            if( $( '#dashboardSidebar' ).hasClass( 'is-show' ) ) {
                //$( '#dashboardSidebar' ).hide();
                $( '#dashboardSidebar' ).removeClass( 'is-show' );
                $( this ).removeClass( 'is-opened' );
            } else {
                //$( '#dashboardSidebar' ).show();
                $( '#dashboardSidebar' ).addClass( 'is-show' );
                $( this ).addClass( 'is-opened' );
            }
        });
        $( '#dashboardSidebar a' ).each(function () {
            $( this ).on( 'click', function () {
                if( isMobile ) {
                    $( '#dashboardSidebar' ).hide();
                    $( '#dashboardSidebar' ).removeClass( 'is-show' );
                    $( '#btnOpenMenuMobile' ).removeClass( 'is-opened' );
                }
            });
        });
        $( '.dashboard .tabs__header a' ).each(function () {
            $( this ).on( 'click', function () {
                $( this ).removeClass( 'is-notification' );
                $( '.tabs__header a, .tabs__panels' ).removeClass( 'is-active' );
                $( this ).addClass( 'is-active' );
            });
        });


        // MODAL FULLSCREEN
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


        // COMPORTAMENTO FORMULARIO
        $( '.input-help .button' ).each( function () {
            const $this = $( this );
            $this.on( 'click', function() {
                const $currentTip = $this.parent().find( 'p' );
                if( $currentTip.hasClass( 'is-show' ) ) {
                    $currentTip.removeClass( 'is-show' );
                } else {
                    $currentTip.addClass( 'is-show' );
                }
            });
        });
        $( '.input-help p' ).each( function () {
            $( this ).on( 'click', function() {
                $( this ).removeClass( 'is-show' );
            });
        });
        $( '#selectAcaoRealizada' ).on( 'change', function () {
            const _location = window.location;
            if( $( this ).val().length ) {
                _location.href = _location.origin + _location.pathname + '?post_id=' + $( this ).val();
            }
        });
        $( '#selectCategory' ).on( 'change', function () {
            $( '.input-chips .chips-wrap').html( '' );
            $( '.chips-checkbox input[type="checkbox"]').prop( 'checked', false );
        });
        if( $( '#dashboardApoioSingle' ).length ) {
            selectTipoApoio( _current_apoio_edit );
        }
        $( '#selectTipoApoio' ).on( 'change', function () {
            $( 'input[type="text"], input[type="date"], input[type="time"], textarea' ).val( '' ).prop( 'disabled', false );
            selectTipoApoio( $( this ).val() );
        });
        $( '.dashboard-content-single input[name="endereco"]' ).on( 'change', function () {
            const $this = $( this );
            gelocation_onblur_address( $this );
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

        // INDICADORES
        $( '#selectFilter' ).on( 'change', function () {
            const _location = window.location;
            if( $( this ).val().length ) {
                $( '#optionsFilter' ).hide();
                switch ( $( this ).val() ) {
                    case 'current_month':
                        _location.href = _location.origin + _location.pathname + '/';
                        break;

                    case 'filter_by_dates':
                        $( '#optionsFilter' ).show();
                        break;

                    default:
                        _location.href = _location.origin + _location.pathname + '?filtro_indicadores=' + $( this ).val();
                        break;
                }
            }
        });

        // BOTÃO MEDIA UPLOAD
        $( '#mediaUploadButton, #mediaUploadButtonCover' ).on( 'click', function () {
            const $this = $( this );

            let isMultiple = '';
            let isAccept = 'image/*';
            let inputName = 'media_file[]';

            if( $this.hasClass( 'is-multiple' ) ) {
                isMultiple = 'multiple';
                isAccept = 'image/*,video/*';
                inputName = 'media_files[]';
            }

            if( !$this.parent().find( 'input[type="file"]' ).length ) {
                $this.parent().append( '<input type="file" name="' + inputName + '" style="display:none;" accept="' + isAccept + '" ' + isMultiple + ' >');
            }
            $this.parent().find( 'input[type="file"]' ).on( 'change', function ( e ) {
                const files = Array.from( e.target.files );
                const $preview = $this.parent().parent().parent().find( '.input-media-preview' );
                const $progress = $this.parent().parent().parent().find( '.input-media-uploader-progress' );

                $progress.show().html( '' );
                $preview.find( '.is-empty' ).remove();
                if( !$this.hasClass( 'is-multiple' ) ) {
                    $preview.html( '' );
                }

                files.forEach( function ( file ) {
                    $progress.append( '<div class="progress is-small">' +
                        '<div class="progress-bar"><span>' +
                        formatFileSize( file.size ) + '</span><span>' +
                        file.name + '</span></div> </div>' );
                });

            }).trigger( 'click' );
        });
        // COMPORTAMENTO MEDIA UPLOAD
        $( '.asset-item-preview-actions .is-delete, .modal-asset-fullscreen .is-delete, .input-media-uploader-options .is-delete' ).on( 'click', function() {
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
                        $( '.dashboard-content-single form' ).find( 'input[name="post_id"]' ).val(),
                        $this.attr( 'data-id' ),
                        function ( response ) {
                            custom_modal_confirm({
                                title: response.data.title,
                                description: response.data.message,
                                confirmText: "OK",
                                onConfirm: function () {
                                    //window.location.reload();
                                }
                            });

                            if( $this.hasClass( 'is-cover-picture' ) ) {
                                $this.parent().parent().css({
                                    opacity : 1,
                                    cursor : 'default'
                                });
                                $( '#mediaUploadCover .is-cover-image .cover-wrap' ).remove();
                                $this.remove();
                            } else {
                                $this.parent().parent().remove();
                            }

                        },
                        function () {

                        }
                    );
                }
            });
        });
        $( '.asset-item-preview .is-play' ).each( function () {

            $( this ).on( 'click', function() {
                $( this ).css( 'opacity', '0.3' );
                $( this ).parent().find( 'video' ).get(0).play();
            });

        });
        $( '.asset-item-preview .is-blur' ).on( 'click', function() {});
        $( '.is-edit-input, input[readonly], textarea[readonly], select[readonly]' ).each( function () {
            const $this = $( this );
            $this.on( 'click', function() {

                if( $this.hasClass( 'is-edit-input' ) ) {
                    $this.hide();
                    $this.parent().find( '.chips-checkbox' ).css({
                        height : 'auto',
                        opacity : 1
                    });
                    $this.parent().find( '.input, .textarea, .select' ).removeAttr( 'readonly disabled' ).addClass( 'is-editing' ).focus();
                } else {
                    $this.parent().find( '.is-edit-input' ).hide();
                    $this.removeAttr( 'readonly disabled' ).addClass( 'is-editing' ).focus();
                }

            });
        });

        //RISCOS
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

        //AÇÕES
        $( '#acaoSingleForm .is-new' ).on( 'click', function () {
            custom_modal_confirm({
                title: 'Criar ação?',
                description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus ullamcorper vestibulum erat in commodo.',
                cancelText: "Cancelar",
                onCancel: function () {},
                confirmText: "Criar Ação",
                onConfirm: function () {
                    $( 'input[name="post_status"]' ).val( 'draft' );
                    $( '#acaoSingleForm' ).submit();
                }
            });
        });
        $( '#acaoSingleForm .is-scheduled' ).on( 'click', function () {
            custom_modal_confirm({
                title: 'Agendar ação?',
                description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus ullamcorper vestibulum erat in commodo.',
                cancelText: "Cancelar",
                onCancel: function () {},
                confirmText: "Agendar Ação",
                onConfirm: function () {
                    $( 'input[name="post_status"]' ).val( 'publish' );
                    $( '#acaoSingleForm' ).submit();
                }
            });
        });
        $( '#acaoSingleForm .is-archive' ).on( 'click', function () {
            custom_modal_confirm({
                title: 'Arquivar essa ação?',
                description: 'As informações não serão publicadas e poderão ser acessadas novamente na aba “Arquivados”',

                cancelText: "Cancelar",
                onCancel: function () {},

                confirmText: "Arquivar",
                onConfirm: function () {
                    $( 'input[name="post_status"]' ).val( 'pending' );
                    $( '#acaoSingleForm' ).submit();
                }
            });
        });
        $( '#acaoSingleForm .is-done' ).on( 'click', function () {
            custom_modal_confirm({
                title: 'Concluir essa ação?',
                description: 'As informações não serão publicadas e poderão ser acessadas novamente na aba “Ações Realizadas”',

                cancelText: "Cancelar",
                onCancel: function () {},

                confirmText: "Concluir",
                onConfirm: function () {
                    $( 'input[name="post_status"]' ).val( 'private' );
                    $( '#acaoSingleForm' ).submit();
                }
            });
        });
        $( '#acaoSingleForm .is-save' ).on( 'click', function () {
            custom_modal_confirm({
                title: 'Publicar alterações essa ação?',
                description: 'As informações não serão publicadas e poderão ser acessadas novamente na aba “Arquivados”',

                cancelText: "Cancelar",
                onCancel: function () {},

                confirmText: "Publicar alterações",
                onConfirm: function () {
                    $( '#acaoSingleForm' ).submit();
                }
            });
        });

        //RELATOS
        $( '#acaoSingleForm .is-new.relato' ).on( 'click', function () {
            custom_modal_confirm({
                title: 'Criar Relato?',
                description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus ullamcorper vestibulum erat in commodo.',
                cancelText: "Cancelar",
                onCancel: function () {},
                confirmText: "Criar Relato",
                onConfirm: function () {
                    $( '#acaoSingleForm' ).submit();
                }
            });
        });
        $( '#acaoSingleForm .is-save.relato' ).on( 'click', function () {
            custom_modal_confirm({
                title: 'Publicar Relato?',
                description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus ullamcorper vestibulum erat in commodo.',
                cancelText: "Cancelar",
                onCancel: function () {},
                confirmText: "Publicar Relato",
                onConfirm: function () {
                    $( '#acaoSingleForm' ).submit();
                }
            });
        });
        $( '#acaoSingleForm .is-delete.relato' ).on( 'click', function () {
            custom_modal_confirm({
                title: 'Publicar Relato?',
                description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus ullamcorper vestibulum erat in commodo.',
                cancelText: "Cancelar",
                onCancel: function () {},
                confirmText: "Publicar Relato",
                onConfirm: function () {
                    $( 'input[name="post_status"]' ).val( 'pending' );
                    $( '#acaoSingleForm' ).submit();
                }
            });
        });

        //APOIOS
        $( '#apoioSingleForm .is-new' ).on( 'click', function () {
            custom_modal_confirm({
                title: 'Criar apoio?',
                description: 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus ullamcorper vestibulum erat in commodo.',
                cancelText: "Cancelar",
                onCancel: function () {},
                confirmText: "Criar Apoio",
                onConfirm: function () {
                    $( '#apoioSingleForm' ).submit();
                }
            });
        });
        $( '#apoioSingleForm .is-archive' ).on( 'click', function () {
            custom_modal_confirm({
                title: 'Arquivar esse apoio?',
                description: 'As informações não serão publicadas e poderão ser acessadas novamente na aba “Arquivados”',

                cancelText: "Cancelar",
                onCancel: function () {},

                confirmText: "Arquivar",
                onConfirm: function () {
                    $( 'input[name="post_status"]' ).val( 'pending' );
                    $( '#apoioSingleForm' ).submit();
                }
            });
        });
        $( '#apoioSingleForm .is-save' ).on( 'click', function () {
            custom_modal_confirm({
                title: 'Publicar alterações esse apoio?',
                description: 'As informações não serão publicadas e poderão ser acessadas novamente na aba “Arquivados”',

                cancelText: "Cancelar",
                onCancel: function () {},

                confirmText: "Publicar alterações",
                onConfirm: function () {
                    $( 'input[name="post_status"]' ).val( 'publish' );
                    $( '#apoioSingleForm' ).submit();
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

        console.log( 'JQUERY WINDOW LOADED' );

        $( 'body' ).removeClass( 'loading' );
        $( '.dashboard-content-pagination' ).show();
        $( '.loading-global' ).fadeOut( 400 );
        $( '.dashboard-content-single input[name="endereco"]' ).each( function () {
            const $this = $( this );
            if( $this.val().length ) {
                //gelocation_onblur_address( $this );
            }
        });

        //TODO: VERIFICAR E REMOVER
        $( '.tabs__panels.is-active .dashboard-content-skeleton' ).hide();
        $( '.tabs__panels.is-active .post-card, .tabs__panels.is-active .message-response, .tabs__panels .tabs__panel__pagination' ).show();
        $( '#dashboardRiscos .dashboard-content-cards .post-card, #dashboardRiscos .dashboard-content-cards .message-response' ).show();
        $( '#dashboardInicio .dashboard-content-cards .post-card, #dashboardInicio .dashboard-content-cards .message-response' ).show();
        $( '#dashboardAcoes .dashboard-content-cards .post-card, #dashboardAcoes .dashboard-content-cards .message-response' ).show();

        if (typeof tinymce !== 'undefined') {
            tinymce.init({
                selector: '#textoAcaoTinyMCE',
                body_class: 'texto-acao-tinymce',
                menubar: false,
                toolbar: 'bold italic underline | bullist numlist | link unlink | undo redo',
                plugins: 'lists link',
                setup: function(editor) {
                    editor.on('change', function() {
                        editor.save();
                    });
                },
                content_css: _tiny_mce_content_css,
                skin: 'lightgray',
                wpautop: true,
                indent: false,
                paste_as_text: true
            });
        }

        // TODO: REFECTORY P/ COMPONENTE DE LOADING TIPO SKELETON
        $( '.dashboard-content-skeleton' ).hide();
        setTimeout( function() {
            $( '.dashboard-content-single .dashboard-content-skeleton' ).remove();
        }, 3000 );

    });
    $( window ).on( 'beforeunload', function () {
        $( '.loading-global' ).fadeIn( 400 );
    });
    $(window).on( 'resize', debounce( ajustarTabs, 100 ));
});

document.addEventListener('DOMContentLoaded', function () {
    console.log( 'DOCUMENT LOADED' );

    const phoneInput = document.querySelector('input[name="telefone"]');
    if (phoneInput) {
        phoneInput.addEventListener('input', handlePhoneInput);
    }

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
    if ( document.querySelectorAll('input.is-editing, select.is-editing, textarea.is-editing').length > 0 ) {
        event.preventDefault();
        document.body.classList.remove( 'loading' );
        return 'Você tem alterações não salvas. Tem certeza que deseja atualizar a página?';
    }
});
window.addEventListener('unload', function(event) {
    //console.log('I am the 4th and last one…');
});
