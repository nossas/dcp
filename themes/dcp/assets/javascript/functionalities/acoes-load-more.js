jQuery(function($) {
    function handleLoadMore(buttonClass, statusValue, containerClass) {
        $(document).on('click', buttonClass, function() {
            var button = $(this);

            if (button.hasClass('disabled')) return;

            var page = parseInt(button.data('page')) + 1;

            $.ajax({
                url: acoesLoadMore.ajaxurl,
                type: 'POST',
                data: {
                    action: 'load_more_acoes',
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
                        button.text('Ver mais');

                        if (page >= response.max) {
                            button
                            .text('Ver mais')
                            .addClass('disabled')
                            .prop('disabled', true);
                        }
                    } else {
                        button
                        .text('Ver mais')
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
