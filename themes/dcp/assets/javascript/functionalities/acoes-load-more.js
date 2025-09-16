jQuery(function($) {
    function handleLoadMore(buttonClass, statusValue, containerClass) {
        $(document).on('click', buttonClass, function() {
            let button = $(this);
            if (button.hasClass('disabled')) return;

            let action = 'load_more_acoes';
            if( button.hasClass( 'load-more-concluir' ) ) {
                action = 'load_more_relatos';
            }

            let page = parseInt(button.data('page')) + 1;

            $.ajax({
                url: acoesLoadMore.ajaxurl,
                type: 'POST',
                data: {
                    action: action,
                    status: statusValue,
                    page: page
                },
                beforeSend: function() {
                    button.text('Carregando...');
                },
                success: function(response) {
                    if (response.html.trim()) {
                        $(containerClass).append(response.html);
                        button.data('page', page);
                        button.text('Mostrar mais');

                        if (page >= response.max) {
                            button
                            .text('Mostrar mais')
                            .addClass('disabled')
                            .prop('disabled', true);
                        }
                    } else {
                        button
                        .text('Mostrar mais')
                        .addClass('disabled')
                        .prop('disabled', true);
                    }
                }
            });
        });
    }

    handleLoadMore('.load-more-agendar', 'Agendar', '.posts-grid__content-cards-agendada');
    handleLoadMore('.load-more-concluir', 'Concluir', '.posts-grid__content-cards-concluidas');

});
