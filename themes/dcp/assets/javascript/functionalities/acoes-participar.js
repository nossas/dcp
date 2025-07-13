jQuery(function($) {
    //
    $( window ).on( 'load', function() {

        $( '#formParticiparAcao' ).on( 'submit', function () {
            const $this = $( this );

            console.log( $this.attr( 'data-action' ) );
            console.log( $this.serialize() );

            if( !$this.hasClass( 'is-sending' ) ) {
                $.ajax({
                    url: $this.attr( 'data-action' ),
                    type: 'POST',
                    data: $this.serialize(),
                    beforeSend: function() {
                        $this.find( '.botao-confirmar span' ).text( 'Enviando participação . . .' );
                        $this.addClass( 'is-sending' );
                        $this.css({
                            opacity : 0.5,
                            cursor : 'not-allowed'
                        });
                    },
                    success: function( response ) {

                    },
                    error: function ( response ) {

                    },
                    complete: function() {
                        $this.find( 'input[type="text"], input[type="email"]' ).val( '' );
                        $this.find( 'input[type="radio"]').prop( 'checked', false );
                        $this.find( '.botao-confirmar span' ).text( 'Confirmar participação' );
                        $this.removeClass( 'is-sending' );
                        $this.css({
                            opacity : 1,
                            cursor : 'pointer'
                        });
                    }
                });
            }

        });

    });
});
