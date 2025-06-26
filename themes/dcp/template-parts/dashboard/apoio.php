<div class="apoio__container situacao-atual__container">
    <div class="situacao-atual__header">
        <h1 class="situacao-atual__title"><?= __('Apoio') ?></h1>
        <div class="situacao-atual__btn">
            <div class="situacao-atual__icon--add">
                <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                    <path fill-rule="evenodd" clip-rule="evenodd" d="M9.00391 2.25195C9.15309 2.25195 9.29617 2.31122 9.40165 2.41671C9.50714 2.52219 9.56641 2.66527 9.56641 2.81445V8.43945H15.1914C15.3406 8.43945 15.4837 8.49872 15.5892 8.60421C15.6946 8.70969 15.7539 8.85277 15.7539 9.00195C15.7539 9.15114 15.6946 9.29421 15.5892 9.3997C15.4837 9.50519 15.3406 9.56445 15.1914 9.56445H9.56641V15.1895C9.56641 15.3386 9.50714 15.4817 9.40165 15.5872C9.29617 15.6927 9.15309 15.752 9.00391 15.752C8.85472 15.752 8.71165 15.6927 8.60616 15.5872C8.50067 15.4817 8.44141 15.3386 8.44141 15.1895V9.56445H2.81641C2.66722 9.56445 2.52415 9.50519 2.41866 9.3997C2.31317 9.29421 2.25391 9.15114 2.25391 9.00195C2.25391 8.85277 2.31317 8.70969 2.41866 8.60421C2.52415 8.49872 2.66722 8.43945 2.81641 8.43945H8.44141V2.81445C8.44141 2.66527 8.50067 2.52219 8.60616 2.41671C8.71165 2.31122 8.85472 2.25195 9.00391 2.25195V2.25195Z" fill="#F9F3EA" />
                </svg>
            </div>
            <a class="situacao-atual__btn-wpp" href=""><?= __('Adicionar informações de apoio') ?></a>
        </div>
    </div>

    <div class="apoio__content">
        <?php
        $locais_seguros_query = new WP_Query([
            'post_type' => 'apoio',
            'posts_per_page' => 3,
            'tax_query' => [
                [
                    'taxonomy' => 'tipo_apoio',
                    'field'    => 'slug',
                    'terms'    => 'locais-seguros',
                ],
            ],
        ]);

        if ($locais_seguros_query->have_posts()) :
            while ($locais_seguros_query->have_posts()) : $locais_seguros_query->the_post();
                get_template_part('template-parts/post-card', 'vertical');
            endwhile;
            wp_reset_postdata();
        endif;
        ?>
    </div>
</div>
