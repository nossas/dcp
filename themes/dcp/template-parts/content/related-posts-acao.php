<section class=" container container--medium proximas-acoes">
  <h2 class="secao-titulo">Próximas Ações</h2>
  <p class="secao-subtitulo">Aenean egestas ultricies nibh, at tempus purus fringilla in. Curabitur ornare enim justo, at tristique.</p>

  <div class="acoes-grid">
    <?php
    $args = array(
      'post_type' => 'acao',
      'posts_per_page' => 3,
      'orderby' => 'date',
      'order' => 'DESC'
    );
    $query = new WP_Query($args);

   if ($query->have_posts()) :
    while ($query->have_posts()) : $query->the_post();
        $categorias = get_the_terms(get_the_ID(), 'tipo_acao');
        $categoria_nome = $categorias ? $categorias[0]->name : '';
        $categoria_slug = $categorias ? $categorias[0]->slug : '';

        // Mapeamento de cores por categoria
        $cores = [
            'limpeza'       => '#235540',
            'educacao'      => '#50B15C',
            'solidariedade' => '#FC7753',
            'reparos'       => '#51B2AF',
            'cultural'      => '#B83D13',
            'incidencia'      => '#FFB300'
        ];

                // Cor final baseada no slug, ou cinza padrão
                $cor = $cores[$categoria_slug] ?? '#888';

                $pod   = pods('acao', get_the_ID());
                $data  = $pod->display('data');
                $hora  = $pod->display('horario');
                $local = $pod->display('endereco');
        ?>

      <div class="acao-card card-style--<?= $categoria_slug ?>">
        <div class="acao-topo " style="background: <?= esc_attr($cor) ?>;">
          <span class="acao-categoria acao-card--<?= $categoria_slug ?>">
            <span class="acao-icon"></span> <?= esc_html($categoria_nome) ?>
          </span>
        </div>

        <div class="acao-conteudo">
            <h3 class="acao-titulo"><?php the_title(); ?></h3>
            <p class="acao-descricao"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>
            <hr>
            <ul class="acao-infos">
                <?php
                    $imagem_calendar = get_template_directory_uri() . '/assets/images/wrapper.svg';
                    $imagem_pin = get_template_directory_uri() . '/assets/images/pin.svg';
                ?>

                <?php if ($data && $hora): ?>
                    <li><img src="<?= esc_url($imagem_calendar) ?>" alt="Data e hora"> Dia: <?= esc_html($data) ?>, <?= esc_html($hora) ?></li>
                <?php elseif ($data): ?>
                    <li><img src="<?= esc_url($imagem_calendar) ?>" alt="Data"> Dia: <?= esc_html($data) ?></li>
                <?php elseif ($hora): ?>
                    <li><img src="<?= esc_url($imagem_calendar) ?>" alt="Horário"> Horário: <?= esc_html($hora) ?></li>
                <?php endif; ?>

                <?php if ($local): ?>
                    <li><img src="<?= esc_url($imagem_pin) ?>" alt="Endereço"> Endereço: <?= esc_html($local) ?></li>
                <?php endif; ?>
            </ul>
        </div>


        <div class="acao-rodape" style="background: <?= esc_attr($cor) ?>;">
          <a href="<?php the_permalink(); ?>" class="acao-botao">Saiba mais e participe</a>
        </div>
      </div>
    <?php
      endwhile;
      wp_reset_postdata();
    endif;
    ?>
  </div>
</section>
