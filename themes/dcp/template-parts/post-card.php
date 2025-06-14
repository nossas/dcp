<?php
global $post;
$original_post = $post;
$post = $args['post'] ?? $post;

$image_size = $args['image_size'] ?? 'card-large';

$hide_author = (bool) ($args['hide_author'] ?? false);
$hide_categories = (bool) ($args['hide_categories'] ?? false);
$hide_date = (bool) ($args['hide_date'] ?? false);
$hide_excerpt = (bool) ($args['hide_excerpt'] ?? false);
$hide_hour = (bool) ($args['hide_hour'] ?? false);
$hide_date = (bool) ($args['hide_date'] ?? false);
$hide_address = (bool) ($args['hide_address'] ?? false);

$modifiers = (array) ($args['modifiers'] ?? []);
$modifiers = array_map(fn($modifier) => "post-card--{$modifier}", $modifiers);
$modifiers = implode(' ', $modifiers);

$categories = get_the_category();
?>
<article id="post-ID-<?php the_ID(); ?>" class="post-card <?= $modifiers ?>">
    <header class="post-card__image">
        <a href="<?php the_permalink(); ?>">
            <?php if (has_post_thumbnail()): ?>
                <?php the_post_thumbnail($image_size); ?>
            <?php else: ?>
                <img src="<?= get_stylesheet_directory_uri() ?>/assets/images/placeholder.png" alt="">
            <?php endif; ?>
        </a>
    </header>

    <main class="post-card__content">
        <?php if (!$hide_categories && !empty($categories)): ?>
            <div class="post-card__category">
                <?php foreach ($categories as $category): ?>
                    <a class="tag tag--solid tag--category-<?= $category->slug ?>" href="<?= get_term_link($category, 'category') ?>">
                        <?= $category->name ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>

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
                        $data_formatada = date('H:i | d/m/Y', $timestamp);
                        echo ' ' . esc_html($data_formatada);
                    }
                }
                ?>
            </div>

        </div>

        <h3 class="post-card__title">
            <a href="<?php the_permalink(); ?>">
                <?php
                the_title();
                ?>
            </a>
        </h3>

        <?php if (!$hide_excerpt): ?>
            <div class="post-card__excerpt-wrapped">
                <div class="post-card__excerpt">
                    <?= get_the_excerpt(); ?>
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

                if ($post_type === 'apoio' && has_term('locais-seguros', 'tipo_apoio', $post_id)) {
                    $hora_atendimento = $pod->field('horario_de_atendimento');
                    $telefone = $pod->field('telefone');
                    $site = $pod->field('site');
                    $observacoes = $pod->field('observacoes');
                ?>

                    <?php if (!empty($hora_atendimento)): ?>
                        <div class="post-card__field post-card__schedule">
                            <strong>Horário de atendimento:</strong>
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
            </div>
        <?php endif; ?>

        <?php if (!$hide_address): ?>
            <div class="post-card__address">
                <?php
                $pod = pods('acao', get_the_ID());
                $endereco_raw = $pod->field('endereco');

                if (!empty($endereco_raw)) {
                    echo esc_html($endereco_raw);
                }
                ?>

                <?php
                $pod = pods('apoio', get_the_ID());
                $endereco_raw = $pod->field('endereco');

                if (!empty($endereco_raw)) {
                    echo esc_html($endereco_raw);
                }
                ?>
            </div>
        <?php endif; ?>

        <?php if ($post_type == 'apoio'): ?>
            <div class="post-card__see-in-map">
                <button class="post-card__map-button">
                    <a href="<?= get_permalink(); ?>"><?= __("Veja no mapa", "dcp"); ?></a>
                </button>
            </div>
        <?php endif; ?>

        <?php if ($post_type == 'risco'): ?>
            <div class="post-card__see-more">
                <a href="<?= get_permalink(); ?>" class="post-card__risco-btn">
                    <?= __("Ver detalhes", "dcp"); ?>
                </a>
            </div>
        <?php endif; ?>

        <?php
        // Exibir campos personalizados apenas para posts da categoria "manuais-de-referencia"
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
        <?php } ?>

    </main>
    <?php if ($post_type == 'acao'): ?>
        <div class="post-card__acao-buttons">
            <button class="post-card__acao-button">
                <a href="#"><?= __("Participe", "dcp"); ?></a>
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
$post = $original_post;
