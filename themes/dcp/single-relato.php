<?php get_header(); ?>

<main id="primary" class="site-main container  container--wide">
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post();

        // Campos personalizados via Pods
        $pods = pods( 'relato', get_the_ID() );
        $dia = $pods->display( 'dia' );
        $endereco = $pods->display( 'endereco' );
    ?>

    <section class="relato-grid">
        <div class="relato-thumb">
            <?php if ( has_post_thumbnail() ) : ?>
                <div class="relato-thumb-wrapper">
                    <?php the_post_thumbnail('large'); ?>
                </div>
            <?php endif; ?>
        </div>

        <div class="relato-conteudo">
            <nav class="breadcrumbs">
                <a href="/acoes">Ações</a> &gt; <span>Participe</span>
            </nav>



            <h1 class="relato-titulo"><?php the_title(); ?></h1>
 <!-- Categoria acima do título -->
            <div class="relato-categoria">
                <?php
                    $terms = get_the_terms(get_the_ID(), 'tipo_acao');
                    if ($terms && !is_wp_error($terms)) {
                        echo '<span class="badge">' . esc_html($terms[0]->name) . '</span>';
                    }
                ?>
            </div>
            <div class="relato-descricao">
                <?php the_content(); ?>
            </div>

            <ul class="relato-info">
                <?php if ( $dia ) : ?>
                    <li>Dia: <?php echo esc_html($dia); ?></li>
                <?php endif; ?>
                <?php if ( $endereco ) : ?>
                    <li>Endereço: <?php echo esc_html($endereco); ?></li>
                <?php endif; ?>
            </ul>
        </div>
    </section>

    <section class="formulario-participar">
        <h2>Participe:</h2>
        <p class="form-descricao">Aenean egestas ultricies nibh, at tempus purus fringilla in. Curabitur ornare enim justo, at tristique.</p>

        <form class="form-participar" action="#" method="post">
            <input type="text" name="nome" placeholder="Digite seu nome" required>
            <input type="text" name="telefone" placeholder="(xx) xxxxx-xxxx" required>
            <input type="email" name="email" placeholder="exemplo@email.com">

            <label class="checkbox-aceite">
                <input type="checkbox" name="aceita">
                Aceito receber confirmações e informações sobre esta ação via e-mail e WhatsApp
            </label>

            <button type="submit" class="botao-confirmar">
                Confirmar participação
            </button>
        </form>
    </section>

    <?php endwhile; else : ?>
        <p>Nenhum relato encontrado.</p>
    <?php endif; ?>
    <?php get_template_part('template-parts/content/related-posts-acao'); ?>

</main>

<?php get_footer(); ?>
