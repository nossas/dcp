<?php
/**
 *
 * Remove recaptcha from tainacan
 *
 */
add_action( 'init', function() {
    wp_dequeue_script( 'tainacan-google-recaptcha-script' );
}, 150 );

function get_page_by_template (string $template) {
	$pages = get_pages([
		'post_type' => 'page',
		'meta_key' => '_wp_page_template',
		'hierarchical' => 0,
		'meta_value' => $template,
	]);

	foreach ($pages as $page) {
		return $page;
	}

	return false;
}


/**
 * Print the excerpt with limit words
 */
function get_custom_excerpt( $post_id = '', $limit = 30 ) {

    if ( empty( $post_id ) ) {
        $post_id = get_the_ID();
    }

    // If exists excerpt metadata
    $excerpt = get_post_meta( $post_id, 'excerpt', true );

    if ( empty( $excerpt ) ) {
        $excerpt = get_the_excerpt( $post_id );
    }

    if ( empty( $excerpt ) ) {
        $excerpt = wp_trim_excerpt( '', $post_id );
    }

    $excerpt = wp_strip_all_tags( $excerpt );
    $excerpt = explode( ' ', $excerpt, $limit );

    if ( count( $excerpt ) >= $limit ) {
        array_pop( $excerpt );
        $excerpt = implode( ' ', $excerpt ) . ' ...';
    } else {
        $excerpt = implode( ' ', $excerpt );
    }

    return $excerpt;

}

/**
 * Rename the defaults taxonomies
 */
function rename_taxonomies() {

    // Tags -> Temas
    $post_tag_args = get_taxonomy( 'post_tag' );

    $post_tag_args->label = 'Temas';
    $post_tag_args->labels->name = 'Temas';
    $post_tag_args->labels->singular_name = 'Tema';
    $post_tag_args->labels->search_items = 'Pesquisar tema';
    $post_tag_args->labels->popular_items = 'Temas populares';
    $post_tag_args->labels->all_items = 'Todos temas';
    $post_tag_args->labels->parent_item = 'Tema superior';
    $post_tag_args->labels->edit_item = 'Editar tema';
    $post_tag_args->labels->view_item = 'Ver tema';
    $post_tag_args->labels->update_item = 'Atualizar tema';
    $post_tag_args->labels->add_new_item = 'Adicionar novo tema';
    $post_tag_args->labels->new_item_name = 'Nome do novo tema';
    $post_tag_args->labels->separate_items_with_commas = 'Separe os temas com vírgulas';
    $post_tag_args->labels->add_or_remove_items = 'Adicionar ou remover temas';
    $post_tag_args->labels->choose_from_most_used = 'Escolha entre os temas mais usados';
    $post_tag_args->labels->not_found = 'Nenhum tema encontrado';
    $post_tag_args->labels->no_terms = 'Nenhum tema';
    $post_tag_args->labels->items_list_navigation = 'Navegação da lista de temas';
    $post_tag_args->labels->items_list = 'Lista de temas';
    $post_tag_args->labels->most_used = 'Temas mais utilizados';
    $post_tag_args->labels->back_to_items = '&larr; Ir para os temas';
    $post_tag_args->labels->item_link = 'Link do tema';
    $post_tag_args->labels->item_link_description = 'Um link para o tema';
    $post_tag_args->labels->menu_name = 'Temas';
    $post_tag_args->hierarchical = true;

    $object_type = array_merge( $post_tag_args->object_type, ['page'] );
    $object_type = array_unique( $object_type );

    register_taxonomy( 'post_tag', $object_type, (array) $post_tag_args );

    // Category -> Projetos
    $category_args = get_taxonomy( 'category' );

    $category_args->label = 'Projetos';
    $category_args->labels->name = 'Projetos';
    $category_args->labels->singular_name = 'Projeto';
    $category_args->labels->search_items = 'Pesquisar Projeto';
    $category_args->labels->popular_items = 'Projetos populares';
    $category_args->labels->all_items = 'Todos Projetos';
    $category_args->labels->parent_item = 'Projeto superior';
    $category_args->labels->edit_item = 'Editar Projeto';
    $category_args->labels->view_item = 'Ver Projeto';
    $category_args->labels->update_item = 'Atualizar Projeto';
    $category_args->labels->add_new_item = 'Adicionar novo Projeto';
    $category_args->labels->new_item_name = 'Nome do novo Projeto';
    $category_args->labels->separate_items_with_commas = 'Separe os Projetos com vírgulas';
    $category_args->labels->add_or_remove_items = 'Adicionar ou remover Projetos';
    $category_args->labels->choose_from_most_used = 'Escolha entre os Projetos mais usados';
    $category_args->labels->not_found = 'Nenhum Projeto encontrado';
    $category_args->labels->no_terms = 'Nenhum Projeto';
    $category_args->labels->items_list_navigation = 'Navegação da lista de Projetos';
    $category_args->labels->items_list = 'Lista de Projetos';
    $category_args->labels->most_used = 'Projetos mais utilizados';
    $category_args->labels->back_to_items = '&larr; Ir para os Projetos';
    $category_args->labels->item_link = 'Link do Projeto';
    $category_args->labels->item_link_description = 'Um link para o Projeto';
    $category_args->labels->menu_name = 'Projetos';

    $object_type = array_merge( $category_args->object_type, ['page'] );
    $object_type = array_unique( $object_type );

    register_taxonomy( 'category', $object_type, (array) $category_args );

}
// Descomentar para renomear as taxonomias padrão do WP
// add_action( 'init', 'rename_taxonomies', 11 );

// Page Slug Body Class
function add_slug_body_class( $classes ) {
    global $post;
    if ( isset( $post ) ) {
    $classes[] = $post->post_type . '-' . $post->post_name;
    }
    return $classes;
}
add_filter( 'body_class', 'add_slug_body_class' );

/**
 * Return the structure HTML of the posts separetade by month
 *
 * @param array $args use params of the class WP_Query
 * @link https://developer.wordpress.org/reference/classes/wp_query/#parameters
 *
 * @return array months|slider
 */
function get_posts_by_month( $args = [] ) {

    $args['orderby'] = 'date';

    $items = new WP_Query( $args );

    if( $items->have_posts() ) :

        $month_titles   = [];
        $close_ul       = false;
        $content_slider = '';

        while( $items->have_posts() ) : $items->the_post();

            $month_full = [
                'Jan' => 'Janeiro',
                'Feb' => 'Fevereiro',
                'Mar' => 'Marco',
                'Apr' => 'Abril',
                'May' => 'Maio',
                'Jun' => 'Junho',
                'Jul' => 'Julho',
                'Aug' => 'Agosto',
                'Nov' => 'Novembro',
                'Sep' => 'Setembro',
                'Oct' => 'Outubro',
                'Dec' => 'Dezembro'
            ];

            $year = date( 'Y', strtotime( get_the_date( 'Y-m-d H:i:s' ) ) );
            $month = date( 'M', strtotime( get_the_date( 'Y-m-d H:i:s' ) ) );

            $month_title = $month_full[$month] . ' ' . $year;

            if ( ! in_array( $month_title, $month_titles ) ) :
                if ( $close_ul ) $content_slider .= '</ul>';
                $content_slider .= '<ul id="items-' . sanitize_title( $month_title ) . '" class="item-slider">';
                $month_titles[] = $month_title;
                $close_ul = true;
            endif;

            $thumbnail = ( has_post_thumbnail( get_the_ID() ) ) ? get_the_post_thumbnail( get_the_ID() ) : '<img src="' . get_stylesheet_directory_uri() . '/assets/images/default-image.png">';

            $content_slider .= sprintf(
                '<li id="item-%1$s" class="item item-month-%2$s"><a href="%3$s"><div class="thumb">%4$s</div><div class="title"><h3>%5$s</h3></div></a></li>',
                get_the_ID(),
                $month_title,
                get_permalink( get_the_ID() ),
                $thumbnail,
                get_the_title( get_the_ID() )
            );

        endwhile;

        if ( $close_ul ) $content_slider .= '</ul>';
    endif;

    return [
        'months' => $month_titles,
        'slider' => $content_slider
    ];

}

function allow_svg_uploads( $file_types ){
	$file_types['svg'] = 'image/svg+xml';
	return $file_types;
}
add_filter( 'upload_mimes', 'allow_svg_uploads' );

function archive_filter_posts( $query ) {
    // Apply filter of the archives
    if ( $query->is_main_query() && ! is_admin() ) {

        $is_blog = false;
        $page_for_posts = get_option( 'page_for_posts' );

        if ( $query->is_home() && isset( $query->get_queried_object()->ID ) && $query->get_queried_object()->ID == $page_for_posts ) {
            $is_blog = true;
        }

        if ( is_archive() || $is_blog ) {
            if ( isset( $_GET['filter_term'] ) && 'all' !== $_GET['filter_term'] ) {
                $term = get_term_by_slug( $_GET['filter_term'] );

                if ( $term && ! is_wp_error( $term ) ) {
                    $tax_query = [
                        [
                            'field'    => 'slug',
                            'taxonomy' => $term->taxonomy,
                            'terms'    => [ $term->slug ]
                        ]
                    ];

                    $query->set( 'tax_query', $tax_query );
                }
            }
        }
    }
}
add_action( 'pre_get_posts', 'archive_filter_posts' );

add_filter('bcn_display', function($output) {
    $svg = '<svg xmlns="http://www.w3.org/2000/svg" width="15" height="14" viewBox="0 0 15 14" fill="none" style="margin: 0 6px; vertical-align: middle;">
        <path fill-rule="evenodd" clip-rule="evenodd" d="M4.56603 1.44103C4.60667 1.40029 4.65495 1.36796 4.7081 1.34591C4.76125 1.32385 4.81824 1.3125 4.87578 1.3125C4.93333 1.3125 4.99031 1.32385 5.04346 1.34591C5.09661 1.36796 5.14489 1.40029 5.18553 1.44103L10.4355 6.69103C10.4763 6.73167 10.5086 6.77995 10.5307 6.8331C10.5527 6.88625 10.5641 6.94324 10.5641 7.00078C10.5641 7.05833 10.5527 7.11531 10.5307 7.16846C10.5086 7.22161 10.4763 7.26989 10.4355 7.31053L5.18553 12.5605C5.10338 12.6427 4.99196 12.6888 4.87578 12.6888C4.7596 12.6888 4.64818 12.6427 4.56603 12.5605C4.48388 12.4784 4.43773 12.367 4.43773 12.2508C4.43773 12.1346 4.48388 12.0232 4.56603 11.941L9.50716 7.00078L4.56603 2.06053C4.52529 2.01989 4.49296 1.97161 4.47091 1.91846C4.44885 1.86531 4.4375 1.80833 4.4375 1.75078C4.4375 1.69324 4.44885 1.63625 4.47091 1.5831C4.49296 1.52995 4.52529 1.48167 4.56603 1.44103Z" fill="#281414"/>
    </svg>';

    // Substitui o separador padrão pelo SVG (ajuste se seu separador atual for diferente de ' &gt; ')
    return str_replace(' &gt; ', $svg, $output);
});

// Botão "Ver mais",  archive de ações

function acao_enqueue_scripts() {
    wp_enqueue_script('jquery');

    wp_enqueue_script(
        'acoes-load-more',
        get_template_directory_uri() . '/js/acoes-load-more.js',
        ['jquery'],
        null,
        true
    );

    wp_localize_script('acoes-load-more', 'acoesLoadMore', [
        'ajaxurl' => admin_url('admin-ajax.php')
    ]);
}
add_action('wp_enqueue_scripts', 'acao_enqueue_scripts');

function load_more_acoes_callback() {
    $paged = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $status = sanitize_text_field($_POST['status']);

    $query = new WP_Query([
        'post_type' => 'acao',
        'post_status' => 'publish',
        'posts_per_page' => 3,
        'paged' => $paged,
//        'meta_query' => [
//            [
//                'key' => 'status_da_acao',
//                'value' => $status,
//                'compare' => '='
//            ]
//        ]
    ]);

    ob_start();

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            get_template_part('template-parts/post-card', 'vertical');
        }
    }

    $html = ob_get_clean();

    // Retorna HTML + total de páginas
    wp_send_json([
        'html' => $html,
        'max'  => $query->max_num_pages
    ]);
}
add_action('wp_ajax_load_more_acoes', 'load_more_acoes_callback');
add_action('wp_ajax_nopriv_load_more_acoes', 'load_more_acoes_callback');



function load_more_relatos_callback() {
    $paged = isset($_POST['page']) ? intval($_POST['page']) : 1;
    $status = sanitize_text_field($_POST['status']);

    $query = new WP_Query([
        'post_type' => 'relato',
        'post_status' => 'publish',
        'posts_per_page' => 3,
        'paged' => $paged,
//        'meta_query' => [
//            [
//                'key' => 'status_da_acao',
//                'value' => $status,
//                'compare' => '='
//            ]
//        ]
    ]);

    ob_start();

    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
            get_template_part('template-parts/post-card', 'vertical');
        }
    }

    $html = ob_get_clean();

    // Retorna HTML + total de páginas
    wp_send_json([
        'html' => $html,
        'max'  => $query->max_num_pages
    ]);
}
add_action('wp_ajax_load_more_relatos', 'load_more_relatos_callback');
add_action('wp_ajax_nopriv_load_more_relatos', 'load_more_relatos_callback');


add_filter('body_class', 'add_custom_body_classes');
function add_custom_body_classes($classes) {
    if (is_singular('acao')) {
        $classes[] = 'single-acao-agendada';
    }

    if (is_singular('post')) {
        $classes[] = 'single-transparencia';
    }

    return $classes;
}

function cpt_acao_assets() {
    if (is_singular('acao')) {
        wp_enqueue_style('swiper-css', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.css');

        // Carrega primeiro o Swiper.js
        wp_enqueue_script('swiper-js', 'https://cdn.jsdelivr.net/npm/swiper@10/swiper-bundle.min.js', [], null, true);

        // Depois, seu script que usa Swiper (com 'swiper-js' como dependência)
        wp_enqueue_script('app-js', get_template_directory_uri() . '/dist/js/functionalities/app.js', ['swiper-js'], null, true);
    }
}
add_action('wp_enqueue_scripts', 'cpt_acao_assets');






// Salvar dados de UM FORMULÁRIO ESPECÍFICO do CF7 como Custom Post Type
add_action('wpcf7_mail_sent', 'salvar_formulario_especifico_como_post');

function salvar_formulario_especifico_como_post($contact_form) {
    // ID do formulário específico que você quer direcionar
    $formulario_alvo_id = 712; // Substitua pelo ID do seu formulário

    // Verificar se é o formulário correto
    if ($contact_form->id() != $formulario_alvo_id) {
        return;
    }

    $submission = WPCF7_Submission::get_instance();

    if ($submission) {
        $posted_data = $submission->get_posted_data();

        $categoria = isset($posted_data['acao-categoria']) ? $posted_data['acao-categoria'] : 'sem-categoria';

        $postID = wp_insert_post(array(
            'post_type' => 'acao',
            'post_title' => 'WEBSITE / SUGESTÃO DE AÇÃO - ' . $categoria[0],
            'post_content' => isset($posted_data['descricao']) ? $posted_data['descricao'] : 'DESCRIÇÃO VAZIA',
            'post_status' => 'draft',
            'meta_input' => [
                'nome_completo' => isset($posted_data['nome-completo']) ? $posted_data['nome-completo'] : 'NOME COMPLETO VAZIO',
                'telefone' => isset($posted_data['telefone']) ? $posted_data['telefone'] : 'TELEFONE VAZIO',
                'categoria' => isset($posted_data['categoria']) ? $posted_data['categoria'] : 'CATEGORIA VAZIA',
                'descricao' => isset($posted_data['descricao']) ? $posted_data['descricao'] : 'DESCRIÇÃO VAZIA',
                'data_e_horario' => date('Y-m-d H:i:s')
            ]
        ));

        wp_set_object_terms(
            $postID,
            [ $categoria[0] ],
            'tipo_acao',
            false
        );

    }
}
