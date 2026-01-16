<?php
global $post;
$original_post = $post;
$post = $args['post'] ?? $post;

$image_size = $args['image_size'] ?? 'card-large';

$hide_author = (bool) ($args['hide_author'] ?? false);
$hide_categories = (bool) ($args['hide_categories'] ?? false);
$hide_excerpt = (bool) ($args['hide_excerpt'] ?? false);
$hide_hour = (bool) ($args['hide_hour'] ?? false);
$hide_date = (bool) ($args['hide_date'] ?? false);
$hide_address = (bool) ($args['hide_address'] ?? false);
$show_top_date = (bool) ($args['show_top_date'] ?? false);

$modifiers = (array) ($args['modifiers'] ?? []);
$modifiers = array_map(fn($modifier) => "post-card--{$modifier}", $modifiers);
$modifiers = implode(' ', $modifiers);

$categories = get_the_category();

// Pega categorias do post e encontra apenas a categoria pai
$categories = get_the_category($post->ID);
$categoria_pai = '';
if (!empty($categories)) {
    foreach ($categories as $cat) {
        if ($cat->category_parent == 0) {
            $categoria_pai = $cat->name;
            break;
        }
    }

    if (empty($categoria_pai)) {
        $categoria_pai = $categories[0]->name;
    }
}

$data_post = get_the_date('d/m/Y', $post->ID);
$show_info_between = !$hide_date;

//if ( get_post_status() === 'publish' ) :
?>
<article id="post-ID-<?php the_ID(); ?>" class="post-card <?= $modifiers ?>" data-post-id="<?php the_ID() ?>">
    <header class="post-card__image">
        <a href="<?php the_permalink(); ?>">
            <?php if (has_post_thumbnail()): ?>
                <?php the_post_thumbnail($image_size); ?>
            <?php else: ?>
                <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/placeholder.png" alt="<?= esc_attr__('Imagem padrão', 'dcp') ?>">
            <?php endif; ?>
        </a>
    </header>

    <section class="post-card__content">
        <?php if (!$hide_categories && !empty($categoria_pai)): ?>

        <div class="post-card__pre-header">
            <div class="post-card__category">
                <?php
                $categoria_obj = null;
                foreach ($categories as $cat) {
                    if ($cat->name === $categoria_pai) {
                        $categoria_obj = $cat;
                        break;
                    }
                }

                if ($categoria_obj): ?>
                    <a class="tag tag--solid tag--category-<?= $categoria_obj->slug ?>" href="<?= get_term_link($categoria_obj, 'category') ?>">
                        <?= esc_html($categoria_obj->name); ?>
                    </a>
                <?php endif; ?>
            </div>

            <div class="post-card__date">
                <?php
                $dia_raw = get_post_meta(get_the_ID(), 'dia', true);

                if (!empty($dia_raw)) {
                    $timestamp = strtotime($dia_raw);
                    if ($timestamp) {
                        echo esc_html(date('d/m/y', $timestamp));
                    } else {
                        echo esc_html($dia_raw);
                    }
                }
                ?>
            </div>
        </div>

        <?php endif; ?>
        <div class="post-card__top-meta">
            <div class="post-card__term">
                <?php
                $post_type = get_post_type(get_the_ID());
                $taxonomia = ($post_type === 'risco') ? 'situacao_de_risco' : 'tipo_acao';

                $terms = get_the_terms(get_the_ID(), $taxonomia);

                if (!empty($terms) && !is_wp_error($terms)) {
                    $term = $terms[0];
                    $term_slug = esc_attr($term->slug);
                    $term_name = esc_html($term->name);
                    echo '<span class="post-card__taxonomia term-' . $term_slug . '">' . $term_name . '</span>';
                }
                ?>

                <div class="post-card__risco-meta">
                    <?php
                    if (get_post_type() === 'risco') {
                        $data_bruta = get_post_meta(get_the_ID(), 'data_e_horario', true);

                        if (!empty($data_bruta)) {
                            $timestamp = strtotime($data_bruta);
                            $timezone = new DateTimeZone('UTC');
                            $data_formatada = date('H:i | d/m/Y', $timestamp);
                            echo ' ' . esc_html($data_formatada);
                        }
                    }
                    ?>
                </div>
            </div>

            <?php
            if ($show_top_date) :
                $pods_relato = pods('relato', get_the_ID());
                $data_do_relato = $pods_relato->field('data_e_horario');
                if (!empty($data_do_relato)) :
                    $timezone = new DateTimeZone('UTC');
                    $data_formatada = date('d/m/Y', strtotime($data_do_relato));
            ?>
                    <span class="post-card__top-date">
                        <?= $data_formatada; ?>
                    </span>
            <?php
                endif;
            endif;
            ?>
        </div>

        <h2 class="post-card__title">
            <a href="<?php the_permalink(); ?>">
                <?php

                if ($post_type == 'acao') {
                    $pods = pods('acao', get_the_ID());
                    echo $pods->field('titulo');
                } else {
                    the_title();
                }
                ?>
            </a>
        </h2>

        <?php if (!$hide_excerpt): ?>
            <div class="post-card__excerpt-wrapped">
                <div class="post-card__excerpt">
                    <?php
                    if ($post_type == 'acao') {
                        $pods = pods('acao', get_the_ID());
                        echo (mb_strlen($pods->field('descricao')) > 150) ? substr($pods->field('descricao'), 0, 150) . ' ...' : $pods->field('descricao');
                    } else if ($post_type == 'relato') {
                        $pods = pods('relato', get_the_ID());
                        echo (mb_strlen($pods->field('descricao')) > 200) ? substr($pods->field('descricao'), 0, 200) . ' ...' : $pods->field('descricao');
                    } else {
                        echo get_the_excerpt();
                    }
                    ?>
                </div>
            </div>
        <?php endif; ?>

        <div class="post-card__divisor">
            <div class="post-card__divisor-line"></div>
        </div>

        <?php if (!$hide_author || !$hide_date): ?>
            <div class="post-card__meta">
                <?php if (!$hide_author): ?>
                    <div class="post-card__author">
                        <?php the_author(); ?>
                    </div>
                <?php endif; ?>

                <?php
                $post_type = get_post_type();
                $post_id = get_the_ID();
                $pod = pods($post_type, $post_id);

                if (!$pod) {
                    return;
                }

                if ($post_type === 'apoio' && has_term(['locais-seguros', 'quem-acionar', 'cacambas', 'iniciativas-locais'], 'tipo_apoio', $post_id)) {
                    $hora_atendimento = $pod->field('horario_de_atendimento');
                    $telefone = $pod->field('telefone');
                    $site = $pod->field('site');
                    $observacoes = $pod->field('observacoes');
                ?>

                    <div class="post-card__apoio-meta">
                        <?php
                        $post_id = get_the_ID();

                        if (get_post_type($post_id) === 'apoio') {
                            $termos = get_the_terms($post_id, 'tipo_apoio');

                            if (!empty($termos) && !is_wp_error($termos)) {
                                $slugs = wp_list_pluck($termos, 'slug');

                                if (in_array('quem-acionar', $slugs) || in_array('cacambas', $slugs)) {
                                    $data_bruta = get_post_meta($post_id, 'data_e_horario', true);

                                    if (!empty($data_bruta)) {
                                        $timestamp = strtotime($data_bruta);
                                        if ($timestamp) {
                                            $timezone = new DateTimeZone('UTC');
                                            $data_formatada = date('d/m/Y', $timestamp);
                                            $hora_formatada = date('H:i', $timestamp);
                                            echo 'Dia: ' . esc_html($data_formatada) . ', ' . esc_html($hora_formatada);
                                        }
                                    }
                                }
                            }
                        }
                        ?>
                    </div>

                    <?php if (!empty($hora_atendimento)): ?>
                        <div class="post-card__field post-card__schedule">
                            Horário de atendimento:
                            <?= esc_html(is_array($hora_atendimento) ? implode(', ', $hora_atendimento) : $hora_atendimento); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($telefone)): ?>
                        <div class="post-card__field post-card__phone">
                            <strong>Telefone:</strong> <?= esc_html($telefone); ?>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($site)): ?>
                        <div class="post-card__field post-card__site">
                            <strong>Site:</strong>
                            <a href="<?= esc_url($site); ?>" target="_blank"><?= esc_html($site); ?></a>
                        </div>
                    <?php endif; ?>

                    <?php if (!empty($observacoes)): ?>
                        <div class="post-card__field post-card__notes">
                            <strong>Observações:</strong> <?= esc_html($observacoes); ?>
                        </div>
                    <?php endif; ?>

                    <?php
                } elseif (!$hide_date) {
                    $data_raw = $pod->field('data');
                    $hora_raw = $pod->field('horario');

                    $data = is_array($data_raw) ? reset($data_raw) : $data_raw;
                    $hora = is_array($hora_raw) ? reset($hora_raw) : $hora_raw;

                    $data_obj = !empty($data) ? DateTime::createFromFormat('Y-m-d', $data) : null;
                    $hora_obj = !empty($hora) ? (
                        DateTime::createFromFormat('H:i:s', $hora) ?: DateTime::createFromFormat('H:i', $hora)
                    ) : null;

                    if ($data_obj && $hora_obj): ?>
                        <time class="post-card__datetime">
                            Dia: <?= esc_html($data_obj->format('d/m/Y')); ?>, <?= esc_html($hora_obj->format('H:i')); ?>
                        </time>
                <?php endif;
                }
                ?>
                <?php if ($post_type == 'acao') : $pods = pods('acao', get_the_ID());
                    //$timezone = new DateTimeZone('UTC');
                ?>
                    <time class="post-card__datetime">
                        Dia: <?= date('d/m/Y, H:i', strtotime($pods->field('data_e_horario'))) ?>
                    </time>
                <?php endif; ?>
            </div>
        <?php endif; ?>



        <?php if ($post_type == 'relato') : $pods = pods('relato', get_the_ID());
            //$timezone = new DateTimeZone('UTC');
        ?>
            <time class="post-card__datetime">
                Dia: <?= date('d/m/Y, H:i', strtotime($pods->field('data_e_horario'))) ?>
            </time>
        <?php endif; ?>

        <?php if (!$hide_address): ?>
            <div class="post-card__address">
                <?php
                $post_type = get_post_type(get_the_ID());
                $endereco_final = '';

                $post_types_com_endereco = ['acao', 'apoio', 'relato'];

                if (in_array($post_type, $post_types_com_endereco)) {
                    $pod = pods($post_type, get_the_ID());
                    if ($pod->exists()) {
                        $endereco_final = 'Endereço: ' . $pod->field('endereco');
                    }
                }

                if (!empty($endereco_final)) {
                    echo esc_html($endereco_final);
                }
                ?>
            </div>
        <?php endif; ?>

        <?php if ($post_type == 'apoio'): ?>

            <?php if (get_post_status() === 'publish') : ?>
                <div class="post-card__see-in-map" style="display: flex; justify-content: space-between; align-items: center; padding: 10px 0;">
                    <div> <?php
                            if (has_term('iniciativas-locais', 'tipo_apoio', $post)) :
                            ?>
                            <a href="<?php the_permalink(); ?>" class="saiba-mais-link" style="display: inline-flex; align-items: center; gap: 6px; color: #281414; text-decoration: none;">
                                <span class="saiba-mais-text" style="text-decoration: underline;"><?= __('Conheça a iniciativa', 'dcp') ?></span>
                                <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none">
                                    <path d="M15.8516 2.25098C16.0007 2.25098 16.1445 2.31053 16.25 2.41602C16.3554 2.52139 16.4139 2.66447 16.4141 2.81348V9.56348C16.4141 9.71266 16.3555 9.85642 16.25 9.96191C16.1445 10.0674 16.0007 10.126 15.8516 10.126C15.7026 10.1258 15.5595 10.0673 15.4541 9.96191C15.3486 9.85642 15.2891 9.71266 15.2891 9.56348V4.17188L3.875 15.5869C3.8227 15.6392 3.76071 15.6807 3.69238 15.709C3.62407 15.7373 3.5505 15.752 3.47656 15.752C3.40279 15.7519 3.32988 15.7372 3.26172 15.709C3.19339 15.6807 3.1314 15.6392 3.0791 15.5869C3.0268 15.5346 2.98534 15.4726 2.95703 15.4043C2.92874 15.336 2.91406 15.2624 2.91406 15.1885C2.91413 15.1147 2.92881 15.0418 2.95703 14.9736C2.98534 14.9053 3.0268 14.8433 3.0791 14.791L14.4941 3.37598H9.10156C8.95255 3.37585 8.80948 3.31729 8.7041 3.21191C8.59861 3.10642 8.53906 2.96266 8.53906 2.81348C8.53919 2.66447 8.59873 2.52139 8.7041 2.41602C8.80948 2.31064 8.95255 2.2511 9.10156 2.25098H15.8516Z" fill="#281414" />
                                </svg>
                            </a>

                        <?php elseif (has_term('locais-seguros', 'tipo_apoio', $post)) : ?>
                            <a href="/mapa/?tab=apoio" class="saiba-mais-link" style="display: inline-flex; align-items: center; gap: 6px; color: #281414; text-decoration: none;">
                                <span class="saiba-mais-text" style="text-decoration: underline;"><?= __('Veja no mapa', 'dcp') ?></span>
                                <img src="<?= get_template_directory_uri() ?>/assets/images/seta_card.svg" alt="Seta" class="saiba-mais-icon" style="width: 12px; height: auto;">
                            </a>
                        <?php else : ?>
                            <a href="/mapa" class="saiba-mais-link" style="display: inline-flex; align-items: center; gap: 6px; color: #281414; text-decoration: none;">
                                <span class="saiba-mais-text" style="text-decoration: underline;"><?= __('Veja no mapa', 'dcp') ?></span>
                                <img src="<?= get_template_directory_uri() ?>/assets/images/seta_card.svg" alt="Seta" class="saiba-mais-icon" style="width: 12px; height: auto;">
                            </a>
                        <?php endif; ?>
                    </div>
                    <div> <?php
                            $tem_quem_acionar = has_term('quem-acionar', 'tipo_apoio', $post);
                            $tem_cacambas = has_term('cacambas', 'tipo_apoio', $post);
                            if ($tem_quem_acionar || $tem_cacambas || has_term(['locais-seguros', 'iniciativas-locais'], 'tipo_apoio', $post)) :
                            ?>
                            <a class="situacao-atual__edit-btn post-card__editar-btn" href="<?= hacklabr\dashboard\get_dashboard_url('editar-apoio-novo', ['post_id' => $post->ID]); ?>">
                                <?= __('Editar') ?>
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php if (get_post_status() === 'draft') : ?>
                    <h3 style="font-size: 12px; font-weight: 700; opacity: 0.5; text-align: center; ">MODO RASCUNHO</h3>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>

        <?php if ($post_type == 'risco'): ?>
            <div class="post-card__see-more">
                <a href="<?= get_permalink(); ?>" class="post-card__risco-btn">
                    <?= __("Ver detalhes", "dcp"); ?>
                </a>
            </div>
        <?php endif; ?>

        <?php

        if (has_category('manuais-de-referencia', $post)) {
            $pod = pods('post', get_the_ID());

            $dia_raw = $pod->field('dia');
            $endereco = $pod->field('endereco');
            $arquivo = $pod->field('enviar_arquivo');
            $dia_formatado = '';
            if (!empty($dia_raw)) {
                $dia_obj = DateTime::createFromFormat('Y-m-d', $dia_raw);
                if ($dia_obj) {
                    $dia_formatado = $dia_obj->format('d/m/Y');
                }
            }
        ?>

            <div class="post-card__meta post-card__meta--manuais">
                <?php if (!empty($dia_formatado)): ?>
                    <hr>
                    <div class="post-card__field post-card__dia">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/wrapper.svg" alt="Ícone de endereço" />
                        <strong>Dia:</strong> <?= esc_html($dia_formatado); ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($endereco)): ?>
                    <div class="post-card__field post-card__endereco">
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/pin.svg" alt="Ícone de horário" />
                        <strong>Endereço:</strong> <?= esc_html($endereco); ?>
                    </div>
                <?php endif; ?>
                <?php if (!empty($arquivo)): ?>
                    <div class="post-card__field post-card__manual">
                        <a href="<?= esc_url($arquivo['guid']); ?>" target="_blank" rel="noopener noreferrer">
                            Acesse o manual
                        </a>
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/icon-left-link.png" alt="Ícone de seta" />
                    </div>
                <?php endif; ?>
            </div>
            <?php } else {

            if ($post_type === 'relato') : ?>
                <div class="post-card__meta post-card__meta--default" style="text-align: end;">
                    <a href="<?php the_permalink(); ?>" class="saiba-mais-link" style="display: inline-flex; align-items: center; gap: 6px; color: #281414; text-decoration: none;">
                        <span class="saiba-mais-text" style="text-decoration: underline;"><?php echo __("Ver como foi", "dcp"); ?></span>
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/seta_card.svg" alt="Seta" class="saiba-mais-icon" style="width: 12px; height: auto;" />
                    </a>
                </div>
            <?php elseif ($post_type !== 'acao') : ?>
                <div class="post-card__meta post-card__meta--default" style="text-align: end;">
                    <a href="<?php the_permalink(); ?>" class="saiba-mais-link" style="display: inline-flex; align-items: center; gap: 6px; color: #281414; text-decoration: none;">
                    <span class="saiba-mais-text" style="text-decoration: underline;"><?php echo __("Ver publicação", "dcp"); ?></span>
                        <img src="<?php echo get_template_directory_uri(); ?>/assets/images/seta_card.svg" alt="Seta" class="saiba-mais-icon" style="width: 12px; height: auto;" />
                    </a>
                </div>
        <?php
            endif;
        } ?>

        <?php
        //$status = get_post_meta(get_the_ID(), 'status_da_acao', true);
        /* if ($status === 'Concluir') : ?>
                <div class="post-card__concluir-link">
                    <a href="<?= get_permalink(); ?>" class="post-card__concluir-button">
                        <?= __("Ver como foi", "dcp"); ?>
                        <svg xmlns="http://www.w3.org/2000/svg" width="17" height="16" viewBox="0 0 17 16" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M4.98283 1.64689C5.02928 1.60033 5.08445 1.56339 5.1452 1.53818C5.20594 1.51297 5.27106 1.5 5.33683 1.5C5.4026 1.5 5.46772 1.51297 5.52846 1.53818C5.58921 1.56339 5.64439 1.60033 5.69083 1.64689L11.6908 7.64689C11.7374 7.69334 11.7743 7.74852 11.7995 7.80926C11.8247 7.87001 11.8377 7.93513 11.8377 8.00089C11.8377 8.06666 11.8247 8.13178 11.7995 8.19253C11.7743 8.25327 11.7374 8.30845 11.6908 8.35489L5.69083 14.3549C5.59694 14.4488 5.46961 14.5015 5.33683 14.5015C5.20406 14.5015 5.07672 14.4488 4.98283 14.3549C4.88894 14.261 4.8362 14.1337 4.8362 14.0009C4.8362 13.8681 4.88894 13.7408 4.98283 13.6469L10.6298 8.00089L4.98283 2.35489C4.93627 2.30845 4.89932 2.25327 4.87412 2.19253C4.84891 2.13178 4.83594 2.06666 4.83594 2.00089C4.83594 1.93513 4.84891 1.87001 4.87412 1.80926C4.89932 1.74852 4.93627 1.69334 4.98283 1.64689Z" fill="#281414" />
                        </svg>
                    </a>
                </div>
            <?php endif; */
        ?>
    </section>
    <?php if ($post_type == 'acao'): ?>
        <div class="post-card__acao-buttons">
            <button class="post-card__acao-button">
                <a href="/participe"><?= __("Participe", "dcp"); ?></a>
            </button>
        </div>
    <?php endif; ?>

    <?php if ($post_type === 'acao') : ?>
        <?php
        $tipo_acao_term = get_the_terms(get_the_ID(), 'tipo_acao');
        $tipo_acao_slug = (!empty($tipo_acao_term) && !is_wp_error($tipo_acao_term)) ? $tipo_acao_term[0]->slug : 'padrao';
        ?>
        <div class="post-card__cta post-card__cta--<?= esc_attr($tipo_acao_slug); ?>">
            <a href="<?= get_permalink(); ?>" class="post-card__cta-button">
                <?= __("Saiba mais e participe", "dcp"); ?>
            </a>
        </div>
    <?php endif; ?>
</article>
<?php
//endif;

$post = $original_post;
