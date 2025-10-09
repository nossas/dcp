<section class=" container container--medium proximas-acoes">
  <h2 class="secao-titulo">Próximas Ações</h2>
  <p class="secao-subtitulo">Aenean egestas ultricies nibh, at tempus purus fringilla in. Curabitur ornare enim justo, at tristique.</p>

  <div class="posts-grid__content">

      <div class="posts-grid__content-cards-agendada">
          <?php
          $agendar_query = new WP_Query([
              'post_type' => 'acao',
              'post_status' => 'publish',
              'posts_per_page' => 3,
              'orderby' => 'meta_value',
              'meta_key' => 'data_e_horario',
          ]);
          if ( $agendar_query->have_posts() ) : ?>
              <div class="posts-grid__content-cards-agendada">
                  <?php
                      while ( $agendar_query->have_posts() ) : $agendar_query->the_post();
                          get_template_part( 'template-parts/post-card', 'vertical' );
                      endwhile;
                  ?>
              </div>
              <?php
              $disabled_agendar = ($agendar_query->found_posts <= 3) ? 'disabled' : '';
              ?>
              <button class="load-more-agendar ver-mais-acoes" data-status="Agendar" data-page="1" <?php echo $disabled_agendar; ?>>Mostrar mais</button>
              <?php
              wp_reset_postdata();
          else : ?>
          <div style="background-color: rgba(0,0,0,0.03); padding: 25px; margin: 25px 0; border-radius: 25px;">
              <p>Não existe ações agendadas.</p>
          </div>
          <?php endif; ?>
      </div>
  </div>
</section>
