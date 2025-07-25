<?php get_header(); ?>

<main id="primary" class="site-main container container--wide">
    <?php if ( have_posts() ) : while ( have_posts() ) : the_post();
        $pods = pods( 'acao', get_the_ID() );
    ?>
    <section class="acao-grid">
         <?php if (function_exists('bcn_display')): ?>
                <nav class="bread-acao-mob" aria-label="Breadcrumb">
                    <?php bcn_display(); ?>
                </nav>
            <?php endif; ?>

        <div class="acao-thumb">
            <?php if ( has_post_thumbnail() ) : ?>
                <div class="acao-thumb-wrapper">
                    <?php the_post_thumbnail('large'); ?>
                </div>
            <?php else: ?>
                <div class="acao-thumb-wrapper no-thumb" style="background-color: #eee; width: 100%; height: 300px;">
                    <!-- Espaço reservado sem imagem -->
                </div>
            <?php endif; ?>
        </div>

        <div class="acao-conteudo">
             <?php if (function_exists('bcn_display')): ?>
            <nav class="bread-acao" aria-label="Breadcrumb">
                <?php bcn_display(); ?>
            </nav>
            <?php endif; ?>
            <h1 class="acao-titulo"><?= esc_html( $pods->field( 'titulo' ) ); ?></h1>

            <div class="acao-categoria">
                <?php
                $terms = get_the_terms(get_the_ID(), 'tipo_acao');
                if ($terms && !is_wp_error($terms)) {
                    $term = $terms[0];
                    $slug = $term->slug;
                    $nome = $term->name;

                    $template_dir = get_template_directory();
                    $template_uri = get_template_directory_uri();

                    $svg_path = $template_dir . '/assets/images/tipo-acao/' . $slug . '.svg';
                    $svg_uri = $template_uri . '/assets/images/tipo-acao/' . $slug . '.svg';

                    $png_path = $template_dir . '/assets/images/tipo-acao/' . $slug . '.png';
                    $png_uri = $template_uri . '/assets/images/tipo-acao/' . $slug . '.png';

                    if (file_exists($svg_path)) {
                        $img_path = $svg_uri;
                    } elseif (file_exists($png_path)) {
                        $img_path = $png_uri;
                    } else {
                        $img_path = $template_uri . '/assets/images/tipo-acao/default.png';
                    }

                    echo '<span class="badge" style="display: inline-flex; align-items: center; gap: 0.5em;">';
                    echo '<iconify-icon icon="bi:mic-fill"></iconify-icon>';
                    echo esc_html($nome);
                    echo '</span>';
                }
                ?>
            </div>

            <div class="acao-descricao">
                <?php nl2br( $pods->display( 'descricao' ) ); ?>
            </div>

            <ul class="acao-info">xw
                <li>
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/wrapper.svg" alt="Ícone de endereço" style="width: 1em; vertical-align: middle; margin-right: 0.5em;">
                    Endereço: <?php echo esc_html( $pods->display( 'endereco' ) ); ?>
                </li>
                <li>
                    <img src="<?php echo get_template_directory_uri(); ?>/assets/images/pin.svg" alt="Ícone de horário" style="width: 1em; vertical-align: middle; margin-right: 0.5em;">
                    Dia: <?= date( 'd/m/Y, H:i', strtotime( $pods->field( 'data_e_horario' ) ) ); ?>
                </li>
                <?php
                $imagem = $pods->display('imagem_relato');
                if ( $imagem ) : ?>
                    <li>
                        <img src="<?php echo esc_url( $imagem ); ?>" alt="Imagem da ação" style="width: 1em; vertical-align: middle; margin-right: 0.5em;">
                        Imagem da ação
                    </li>
                <?php endif; ?>
            </ul>

            <section class="formulario-participar">
                <h2>Participe:</h2>
                <p class="form-descricao">Aenean egestas ultricies nibh, at tempus purus fringilla in. Curabitur ornare enim justo, at tristique.</p>

                <form id="formParticiparAcao" class="form-participar" action="javascript:void(0);" method="post" data-action="<?php bloginfo( 'url' ); ?>/wp-admin/admin-ajax.php">
                    <p>Nome</p>
                    <input type="text" name="nome_completo" placeholder="Digite seu nome" required>
                    <p> Telefone (WhatsApp) </p>
                    <input type="text" name="telefone" placeholder="(xx) xxxxx-xxxx" required>
                    <p> E-mail (opcional) </p>
                    <input type="email" name="email" placeholder="exemplo@email.com">

                    <label class="checkbox-aceite">
                        <input type="checkbox" name="aceite_whatsapp" value="ACEITE">
                        Aceito receber confirmações e informações sobre esta ação via e-mail e WhatsApp
                    </label>
                    <label class="checkbox-aceite">
                        <input type="radio" name="aceite_termos">
                        Aceito termos de uso e política de privacidade.
                    </label>
                    <input type="hidden" name="post_id" value="<?= get_the_ID(); ?>">
                    <input type="hidden" name="action" value="form_participar_acao">

                    <div style="display: flex; justify-content: space-between">
                        <div></div>
                        <button class="botao-confirmar">
                            <iconify-icon icon="bi:check2"></iconify-icon>
                            &nbsp;
                            <span>Confirmar participação</span>
                            &nbsp;
                        </button>
                    </div>
                </form>
            </section>
        </div>
    </section>

    <?php endwhile; else : ?>
        <p>Nenhuma ação encontrada.</p>
    <?php endif; ?>

    <hr class="is-separator">
    <?php get_template_part('template-parts/content/related-posts-acao'); ?>
    <p style="padding: 100px 0;">
        Ficou com alguma dúvida? Fale com a gente para saber mais sobre o projeto ou como participar.
        <strong style="text-decoration: underline;">Entre em contato</strong>
    </p>
</main>

<?php get_footer(); ?>
