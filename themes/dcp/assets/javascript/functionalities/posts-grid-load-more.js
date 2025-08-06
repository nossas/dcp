document.addEventListener('DOMContentLoaded', function () {
    const loadMoreButtons = document.querySelectorAll('.hacklabr-posts-grid__load-more-button');

    loadMoreButtons.forEach(button => {
        button.addEventListener('click', function () {
            const wrapper = this.closest('.hacklabr-posts-grid-wrapper');
            const grid = wrapper.querySelector('.hacklabr-posts-grid-block');

            let page = parseInt(this.dataset.page, 10);
            const perPage = this.dataset.perPage;
            const queryAttributes = this.dataset.queryAttributes;

            this.textContent = 'Carregando...';
            this.disabled = true;

            const formData = new FormData();
            formData.append('action', 'load_more_posts');
            formData.append('page', page);
            formData.append('per_page', perPage);
            formData.append('query_attributes', queryAttributes);

            fetch(hl_posts_grid_load_more_data.ajax_url, {
                method: 'POST',
                body: formData,
            })
            .then(response => response.json())
            .then(data => {
                if (data.success && data.data.html) {
                    grid.insertAdjacentHTML('beforeend', data.data.html);
                    this.dataset.page = page + 1;

                    if (!data.data.more_posts_available) {
                        this.style.display = 'none';
                    }
                } else {
                    this.style.display = 'none';
                }

                this.textContent = 'Ver mais';
                this.disabled = false;
            })
            .catch(error => {
                console.error('Erro ao carregar mais posts:', error);
                this.textContent = 'Erro. Tente novamente.';
                this.disabled = false;
            });
        });
    });
});
