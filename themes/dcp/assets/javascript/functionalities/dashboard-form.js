jQuery(function($) {

    //SUBMIT FORM ADICIONAR + EDITAR
    $( '#riscoSingleForm, #acaoSingleForm, #apoioSingleForm' ).on( 'submit', function ( e ) {
        const $this = $( this );
        const form = e.target;
        const formData = new FormData( form );

        $this.addClass( 'is-sending' );
        $( '.loading-global' ).fadeIn( 400 );
        $( '.is-editing' ).each( function () {
            $(this).removeClass( 'is-editing' );
        });

        fetch( $this.attr( 'data-action' ), {
            method: 'POST',
            body: formData
        })
            .then( res => res.json() )
            .then( response => {
                $this.removeClass( 'is-sending' );
                $( '.loading-global' ).fadeOut( 400 );
                if( response.success ) {

                    if( response.data.is_new !== undefined ) {

                        custom_modal_confirm({
                            title: response.data.title,
                            description: response.data.message,

                            cancelText: "Criar novo",
                            onCancel: function () {
                                $this.find( 'input[type="text"], input[type="date"], input[type="time"], textarea, select' ).val( '' );
                                $( '.input-chips .chips-wrap').html( '' );
                                $( '#mediaUpload .input-media-uploader-progress').html( '' );
                                $( '.chips-checkbox input[type="checkbox"]').prop( 'checked', false );
                            },
                            confirmText: "Visualizar",
                            onConfirm: function () {
                                window.location.href = response.data.url_callback;
                            }
                        });

                    } else
                    {
                        custom_modal_confirm({
                            title: response.data.title,
                            description: response.data.message,

                            cancelText: "FECHAR",
                            onCancel: function () {},

                            confirmText: "ATUALIZAR PÁGINA",
                            onConfirm: function () {

                                window.location.reload();

                            }
                        });
                    }

                } else {
                    custom_modal_confirm({
                        title: response.data.title,
                        description: response.data.message,
                        error: response.data.error,

                        cancelText: "FECHAR",
                        onCancel: function () {},

                        confirmText: "ATUALIZAR PÁGINA",
                        onConfirm: function () {
                            window.location.reload();
                        }
                    });
                }

            })
            .catch(error => {
                custom_modal_confirm({
                    title: 'ERRO INESPERADO',
                    description: 'Houve um erro inesperado ao enviar os dados do formulário, aguarde um momento e tente novamente.',
                    error: 'BACKEND ERROR FORM DATA',

                    cancelText: "FECHAR",
                    onCancel: function () {},

                    confirmText: "ATUALIZAR",
                    onConfirm: function () {
                        window.location.reload();
                    }
                });
            });
    });


});
