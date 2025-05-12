<?php
/**
 * Template Name: Quem Acionar
 * Description: Template específico para a página "Quem Acionar"
 */

get_header();
?>

<main id="primary" class="site-main">

    <?php get_template_part( 'template-parts/filter-apoio-search' ); ?>

    <!-- Lista de Apoios -->
    <?php get_template_part( 'template-parts/card-apoio-list' ); ?>


    <!-- Conteúdo da página -->
    <?php while ( have_posts() ) : the_post(); the_content(); endwhile; ?>
</main>

<!-- Modal -->
<div id="call-modal" class="call-modal">
  <div class="call-modal__content">
    <p id="modal-text"></p>
    <button class="call-modal__close">Fechar</button>
  </div>
</div>

<?php get_footer(); ?>

<!-- JavaScript para filtro -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const searchInput = document.getElementById('apoio-search');
    const searchButton = document.getElementById('apoio-search-button');
    const tagButtons = document.querySelectorAll('.tag-button');
    const cards = document.querySelectorAll('.apoio-item');

    let activeTag = 'all';

    // Função que filtra os cards com base na busca e na tag
    function filterCards() {
        const searchTerm = searchInput.value.toLowerCase().trim(); // .trim() para eliminar espaços extras

        cards.forEach(card => {
            // Pega os termos da taxonomia (tags) associadas ao card
            const tags = card.dataset.tags.toLowerCase();  // tags associadas ao card (em minúsculas para facilitar a busca)

            // Verifica se a tag ativa corresponde às tags do card
            const matchesTag = (activeTag === 'all') || tags.includes(activeTag);

            // Verifica se a busca contém o termo de pesquisa nas tags
            const matchesSearch = (searchTerm === '' || tags.includes(searchTerm));

            // O card será exibido se corresponder a ambos os filtros
            if (matchesSearch && matchesTag) {
                card.style.display = '';
            } else {
                card.style.display = 'none';
            }
        });
    }

    // Filtro por tags
    tagButtons.forEach(button => {
        button.addEventListener('click', function () {
            tagButtons.forEach(btn => btn.classList.remove('active'));
            this.classList.add('active');
            activeTag = this.dataset.tag;  // Define a tag ativa
            filterCards();  // Aplica o filtro com base na tag selecionada
        });
    });

    // Filtro por busca (ao digitar no input)
    searchInput.addEventListener('input', function() {
        if (searchInput.value.length >= 2 || searchInput.value.length === 0) {
            filterCards();
        }
    });

    // Ativa o filtro ao clicar no botão de busca
    searchButton.addEventListener('click', function () {
        filterCards();
    });
});


</script>

<style>
.apoio-filtro {
    margin-bottom: 2rem;
}
.apoio-search-wrap {
    display: flex;
    width: 100%;
    max-width: 600px;
    margin: 0 auto;
    position: relative;
}

.apoio-search-wrap input {
    flex: 1;
    padding: 0.5rem 1rem;
    font-size: 1rem;
    border: 1px solid #ccc;
    border-radius: 5px 0 0 5px;
    outline: none;
}

.apoio-search-wrap button {
    padding: 0 1rem;
    background: #0073aa;
    color: #fff;
    border: 1px solid #0073aa;
    border-left: none;
    border-radius: 0 5px 5px 0;
    cursor: pointer;
    font-size: 1rem;
    transition: background 0.2s;
}

.apoio-search-wrap button:hover {
    background: #005f8d;
}

.apoio-tags {
    margin-top: 1rem;
}

.tag-button {
    margin: 0.2rem;
    padding: 0.5rem 1rem;
    border: 1px solid #ccc;
    background: #f0f0f0;
    cursor: pointer;
    border-radius: 5px;
}

.tag-button.active {
    background: #0073aa;
    color: #fff;
    border-color: #0073aa;
}

.apoio-item {
    border: 1px solid #eee;
    padding: 1rem;
    margin-bottom: 1rem;
    border-radius: 5px;
}
</style>
