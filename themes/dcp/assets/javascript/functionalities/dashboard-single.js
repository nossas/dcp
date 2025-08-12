jQuery(function($) {

    $( document ).ready( function() {

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

    });

});
