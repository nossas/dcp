document.addEventListener('DOMContentLoaded', function() {
    if (!document.body.classList.contains('home')) {
        return;
    }

    function atualizarRecomendacoes() {

        const elemento = document.querySelector('.home .recomendacoes');
        if (elemento) {
            elemento.style.opacity = 0.5;
            elemento.style.cursor = 'wait';
        }

        fetch('/wp-json/dcp/v1/situacao-atual-home')
            .then(response => {
                if (!response.ok) {
                    throw new Error('Erro na requisição: ' + response.status);
                }
                return response.json();
            })
            .then(data => {

                if (data.status && data.data) {
                    const recomendacoes = data.data;
                    const paragraphs = document.querySelectorAll('.home .recomendacoes p');

                    paragraphs.forEach((p, index) => {
                        const chave = `recomendacao_${index + 1}`;
                        if (recomendacoes[chave]) {
                            const img = p.querySelector('img');
                            if (img && recomendacoes[chave].icon) {
                                img.src = recomendacoes[chave].icon;
                                img.alt = recomendacoes[chave].text || 'Ícone recomendação';
                            }

                            // Atualiza o texto mantendo a estrutura HTML
                            const textNode = Array.from(p.childNodes)
                                .find(node => node.nodeType === Node.TEXT_NODE);
                            if (textNode && recomendacoes[chave].text) {
                                textNode.textContent = ' ' + recomendacoes[chave].text;
                            }
                        }
                    });

                    elemento.style.opacity = 1;
                    elemento.style.cursor = 'default';
                    console.log('Recomendações atualizadas com sucesso');
                }
            })
            .catch(error => console.error('Erro ao atualizar recomendações:', error));
    }

    // Executa ao carregar a página
    if (document.readyState === 'loading') {
        window.addEventListener('load', atualizarRecomendacoes);
    } else {
        atualizarRecomendacoes();
    }

    let intervalo = setInterval(atualizarRecomendacoes, 300000);
    window.addEventListener('beforeunload', function() {
        clearInterval(intervalo);
    });
});
