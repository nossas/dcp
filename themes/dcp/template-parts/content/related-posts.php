<section class="proximas-acoes">
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

        // Definir cor por categoria
        $cores = [
          'limpeza' => '#2EAF4F',
          'reparos' => '#3A9CA6',
          'cultural' => '#B44420'
        ];
        $cor = $cores[$categoria_slug] ?? '#888';

        // Pegando os campos via Pods
        $pod   = pods('acao', get_the_ID());
        $data  = $pod->display('data');
        $hora  = $pod->display('horario');
        $local = $pod->display('endereco');
    ?>
      <div class="acao-card">
        <!-- Cabeçalho da categoria -->
        <div class="acao-topo" style="background: <?= esc_attr($cor) ?>;">
          <span class="acao-categoria">
            <span class="acao-icon">🔈</span> <?= esc_html($categoria_nome) ?>
          </span>
        </div>

        <!-- Conteúdo do card -->
        <div class="acao-conteudo">
          <h3 class="acao-titulo"><?php the_title(); ?></h3>
          <p class="acao-descricao"><?php echo wp_trim_words(get_the_excerpt(), 20); ?></p>

          <ul class="acao-infos">
            <?php if ($data && $hora): ?>
              <li>📅 <strong>Dia:</strong> <?= esc_html($data) ?>, <?= esc_html($hora) ?></li>
            <?php elseif ($data): ?>
              <li>📅 <strong>Dia:</strong> <?= esc_html($data) ?></li>
            <?php elseif ($hora): ?>
              <li>⏰ <strong>Horário:</strong> <?= esc_html($hora) ?></li>
            <?php endif; ?>

            <?php if ($local): ?>
              <li>📍 <strong>Endereço:</strong> <?= esc_html($local) ?></li>
            <?php endif; ?>
          </ul>
        </div>

        <!-- Rodapé com botão -->
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
