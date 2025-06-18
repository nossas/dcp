import Alpine from 'alpinejs';
import 'iconify-icon';

window.Alpine = Alpine;




// TODO : COMPORTAMENTO MOCK
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
                $( '.tabs__panels .post-card, .tabs__panels .message-response' ).hide();
                $( '.tabs__panels .dashboard-content-skeleton' ).show();
            },
            success: function() {
                //
            },
            error: function () {

            },
            complete: function() {
                $( '.tabs__panels.is-active .dashboard-content-skeleton' ).hide();
                $( '.tabs__panels.is-active .post-card, .tabs__panels.is-active .message-response' ).show();
            }
        });
    }

    $( document ).ready( function() {

        window.location.hash = 'aguardando-aprovacao';

        _mock_ajax_dashboard();


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




    });





});





document.addEventListener("DOMContentLoaded", function () {
    const isMobile = /iPhone|iPad|iPod|Android/i.test(navigator.userAgent);

    // TODO : SEPARAR E COMPONENIZAR AO FINALIZAR dashboardRiscos
    const dashboardRiscos = document.querySelector("#dashboardRiscos" );


    console.log( 'DOCUMENT LOADED' );
});

window.addEventListener('DOMContentLoaded', () => {
    Alpine.start();

    console.log( 'WINDOW LOADED' );
});

window.addEventListener('resize', function () {




});
