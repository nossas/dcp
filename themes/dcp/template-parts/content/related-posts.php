<section class="transparencia">
  <div class="container">
    <div class="transparencia-topo">
      <div class="transparencia-textos">
        <h2 class="transparencia-titulo">Transparência</h2>
        <p class="transparencia-subtitulo">
Aenean egestas ultricies nibh, at tempus purus fringilla in. Curabitur ornare enim justo, at tristique lorem iaculis et. Vivamus eget mollis nibh. Quisque quis neque a augue vehicula laoreet at sed sem. Proin ut ornare enim. Donec rhoncus elit id neque tempor, in imperdiet ipsum blandit.          </p>
      </div>
      <a href="<?= get_permalink(get_page_by_path('politica-de-privacidade')) ?>" class="transparencia-politica">Política de privacidade</a>
    </div>

    <div class="transparencia-grid">
      <?php
      $args = array(
        'post_type' => 'post',
        'posts_per_page' => 3,
        'orderby' => 'date',
        'order' => 'DESC'
      );
      $query = new WP_Query($args);

      if ($query->have_posts()) :
        while ($query->have_posts()) : $query->the_post();
          $categoria = get_the_category();
          $categoria_nome = $categoria ? $categoria[0]->name : '';
          $categoria_slug = $categoria ? $categoria[0]->slug : '';
          $cor_categoria = [
            'boletim' => '#198754',
            'relatorio' => '#0d6efd',
            'publicacao' => '#198754',
          ];
          $cor = $cor_categoria[$categoria_slug] ?? '#235540';

          $thumbnail = get_the_post_thumbnail_url(get_the_ID(), 'large');
          $data = get_the_date('d/m/Y');

          // Verificação do ícone baseado no slug (.svg ou .png)
          $icon_base_path = get_template_directory() . '/assets/images/related-posts-icons/';
          $icon_base_url  = get_template_directory_uri() . '/assets/images/related-posts-icons/';
          $icon_file      = '';

          if (file_exists($icon_base_path . $categoria_slug . '.svg')) {
              $icon_file = $icon_base_url . $categoria_slug . '.svg';
          } elseif (file_exists($icon_base_path . $categoria_slug . '.png')) {
              $icon_file = $icon_base_url . $categoria_slug . '.png';
          } else {
              $icon_file = $icon_base_url . 'default.svg'; // opcional: ícone padrão
          }
      ?>
        <div class="transparencia-card ">
          <?php if ($thumbnail): ?>
            <div class="transparencia-thumb">
              <img src="<?= esc_url($thumbnail) ?>" alt="<?= esc_attr(get_the_title()) ?>">
            </div>
          <?php endif; ?>

          <div class="transparencia-body">
            <div class="transparencia-meta">
              <?php if ($categoria_nome): ?>
                <span class="transparencia-tag" style="background-color: <?= esc_attr($cor) ?>;">
                  <span class="tag-icon acao-card--<?= $categoria_slug ?>" style="background-image: url('<?= esc_url($icon_file) ?>');"></span>
                  <?= esc_html($categoria_nome) ?>
                </span>
              <?php endif; ?>
              <span class="transparencia-data"><?= esc_html($data) ?></span>
            </div>

            <h3 class="transparencia-titulo-card"><?php the_title(); ?></h3>
            <p class="transparencia-descricao"><?php echo wp_trim_words(get_the_excerpt(), 15); ?></p>

            <a href="<?php the_permalink(); ?>" class="transparencia-link">Saiba mais</a>
          </div>
        </div>
      <?php
        endwhile;
        wp_reset_postdata();
      endif;
      ?>
    </div>
  </div>
</section>
