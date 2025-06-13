jQuery(function($) {
    function handleLoadMore(buttonClass, statusValue, containerClass) {
        $(document).on('click', buttonClass, function() {
            var button = $(this);
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
                success: function(data) {
                    if (data) {

                        $(containerClass).append(data);
                        button.data('page', page);
                        button.text('Ver mais');
                    } else {
                        button.remove();
                    }
                }
            });
        });
    }

    handleLoadMore('.load-more-agendar', 'Agendar', '.posts-grid__content-cards-agendada');
    handleLoadMore('.load-more-concluir', 'Concluir', '.posts-grid__content-cards-concluidas');
});