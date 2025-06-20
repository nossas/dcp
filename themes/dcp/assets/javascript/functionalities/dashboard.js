import Alpine from 'alpinejs';
import 'iconify-icon';

window.Alpine = Alpine;

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

        // TODO: COMPORTAMENTO MOCK RISCOS
        if( $( '#dashboardRiscos' ).length ) {

            window.location.hash = 'aguardando-aprovacao';
            _mock_ajax_dashboard();
        }


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

            _mock_ajax_dashboard();

        });

        // if ( document.querySelector( '.is-load-now' ) ) {
        //
        //     new MediaLoader({
        //         targetClass: '.is-load-now',
        //         progressBar: document.getElementById('main-progress-bar'),
        //         progressContainer: document.getElementById('main-progress-container')
        //     });
        //
        // }

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

        $( '.modal-confirm' ).each( function () {

            const $this = $( this );

            $this.find( '.is-close' ).on( 'click', function() {

                $this.fadeOut( 200, function() {});

            });

        });


        $( '#formSubmit .is-archive, #formSubmit .is-publish' ).on( 'click', function() {

            $( '.modal-confirm' ).fadeIn( 200, function() {});

        });

        $( '.asset-item-preview-actions .is-delete' ).on( 'click', function() {

            $( '.modal-confirm' ).fadeIn( 200, function() {});

        });



        $( window ).on( 'dragover', function( event ) {
            console.log( '#mediaUpload dragover' );

            $( '.input-media-uploader-progress' ).show();
            $( '.input-media' ).css({
                opacity : 0.5
            });

        });
        $( window ).on( 'dragleave', function( event ) {
            console.log( '#mediaUpload dragleave' );

            $( '.input-media-uploader-progress' ).hide();
            $( '.input-media' ).css({
                opacity : 1
            });

        });
        $( window ).on( 'drop', function( event ) {
            console.log( '#mediaUpload drop' );

            $( '.input-media-uploader-progress' ).show();


        });

        $( window ).mouseenter( function ( event ) {});
        $( window ).mousemove( function ( event ) {});
        $( window ).mouseleave( function ( event ) {});

    });

    $( window ).on( 'load', function() {

        $( 'body' ).addClass( 'is-loaded' );

        // TODO: REFECTORY P/ COMPONENTE DE LOADING TIPO SKELETON
        $( '.dashboard-content-skeleton' ).hide();
        setTimeout( function() {
            $( '.dashboard-content-single .dashboard-content-skeleton' ).remove();
        }, 3000 );

    });
});


document.addEventListener("DOMContentLoaded", function () {
    const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);

    console.log( 'DOCUMENT LOADED' );

    document.body.addEventListener("wheel", function(event) {

        if (event.deltaY < 0) {
            document.body.classList.add( 'is-scrolling-up' );
            document.body.classList.remove('is-scrolling-down' );

            // TODO: REFACTORY P/ COMPONENTE
            if( document.body.className.match( 'loaded' ) ) {
                document.getElementById( 'formSubmit' ).classList.add( 'show-minimal' );
                document.getElementById( 'formSubmit' ).classList.remove( 'show' );
            }


        } else {
            document.body.classList.add("is-scrolling-down");
            document.body.classList.remove("is-scrolling-up");

            // TODO: REFACTORY P/ COMPONENTE
            if( document.body.className.match( 'loaded' ) ) {
                document.getElementById( 'formSubmit' ).classList.add( 'show' );
                document.getElementById( 'formSubmit' ).classList.remove( 'show-minimal' );
            }
        }

    });
});

window.addEventListener('DOMContentLoaded', () => {
    Alpine.start();

    console.log( 'WINDOW LOADED' );
});

window.addEventListener('resize', function () {

});

window.addEventListener('beforeunload', function(event) {

    $( 'body' ).attr( 'class', 'loading is-loaded' );

});

window.addEventListener('unload', function(event) {
    //console.log('I am the 4th and last one…');
});

window.addEventListener('offline', (event) => {
    //console.log( "The network connection has been lost." );
});

window.onoffline = (event) => {
    //console.log( "The network connection has been lost." );
};

window.addEventListener('online', (event) => {
    //console.log("You are now connected to the network.");
});

window.ononline = (event) => {
    //console.log("You are now connected to the network.");
};

document.addEventListener('DOMContentLoaded', (event) => {
    document.addEventListener('contextmenu', (e) => {
        e.preventDefault();
    });
});
window.oncontextmenu = function () {
    //return false;
}

document.addEventListener("keydown", function(event) {

    //console.log( 'KEY DOWN : ', event.code );

    // if (event.code === "Space" && !document.activeElement.matches("input, textarea")) {
    //     event.preventDefault();
    //     //_space_special();
    // }
});
