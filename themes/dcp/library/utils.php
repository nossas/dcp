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
        'orderby' => 'meta_value',
        'meta_key' => 'data_e_horario',
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
        'paged' => $paged
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



// Altera os rótulos do post type "post"
function alterar_labels_post_para_conteudos( $labels ) {
    $labels->name               = 'Conteúdos';
    $labels->singular_name      = 'Conteúdo';
    $labels->add_new            = 'Adicionar novo';
    $labels->add_new_item       = 'Adicionar novo conteúdo';
    $labels->edit_item          = 'Editar conteúdo';
    $labels->new_item           = 'Novo conteúdo';
    $labels->view_item          = 'Ver conteúdo';
    $labels->search_items       = 'Buscar conteúdos';
    $labels->not_found          = 'Nenhum conteúdo encontrado';
    $labels->not_found_in_trash = 'Nenhum conteúdo na lixeira';
    $labels->all_items          = 'Todos os conteúdos';
    $labels->menu_name          = 'Conteúdos';
    $labels->name_admin_bar     = 'Conteúdo';
    return $labels;
}
add_filter( 'post_type_labels_post', 'alterar_labels_post_para_conteudos' );

// Altera o nome do menu lateral
function alterar_menu_posts_para_conteudos() {
    global $menu;
    global $submenu;

    // Altera o nome principal do menu
    foreach ( $menu as $key => $item ) {
        if ( isset($item[2]) && $item[2] == 'edit.php' ) {
            $menu[$key][0] = 'Conteúdos';
        }
    }

    // Altera os subitens
    if ( isset($submenu['edit.php']) ) {
        $submenu['edit.php'][5][0] = 'Todos os conteúdos';
        $submenu['edit.php'][10][0] = 'Adicionar novo';
        $submenu['edit.php'][15][0] = 'Categorias';
        $submenu['edit.php'][16][0] = 'Tags';
    }
}
add_action( 'admin_menu', 'alterar_menu_posts_para_conteudos' );



add_action('admin_menu', 'adicionar_link_dashboard_personalizado');

function adicionar_link_dashboard_personalizado() {
    // Adiciona o item principal do menu
    add_menu_page(
        'Dashboard',
        'Dashboard',
        'read',
        'dashboard_personalizado',
        'redirecionar_para_dashboard',
        'dashicons-chart-pie',
        1
    );

    // Remove o submenu padrão criado automaticamente
    remove_submenu_page('dashboard_personalizado', 'dashboard_personalizado');
}

// Função de redirecionamento
function redirecionar_para_dashboard() {
    echo '<h2 style="opacity: 0.5;">redirecionando . . .</h2>';
    echo "<script>window.location.href = window.location.origin + '/dashboard/';</script>";
    exit;
}

// Adiciona CSS personalizado
add_action('admin_head', 'estilo_personalizado_menu');

function estilo_personalizado_menu() {
    echo '<style>
        #adminmenu .wp-menu-image.dashicons-chart-pie:before {
            color: #00ff00 !important;
        }
        #adminmenu li.toplevel_page_dashboard_personalizado:hover .wp-menu-image:before,
        #adminmenu li.toplevel_page_dashboard_personalizado.current .wp-menu-image:before {
            color: #ffffff !important;
        }
    </style>';
}

add_filter('wpcf7_validate_tel*', 'custom_phone_validation', 20, 2);
add_filter('wpcf7_validate_tel', 'custom_phone_validation', 20, 2);

function custom_phone_validation($result, $tag) {
    $name = $tag->name;
    $value = isset($_POST[$name]) ? trim($_POST[$name]) : '';

    if ($name === 'tel-932') {
        if (!preg_match('/^\(\d{2}\) \d{5}-\d{4}$/', $value)) {
            $result->invalidate($tag, 'Telefone inválido');
        }
    }

    return $result;
}

add_filter('wpcf7_validate_textarea', 'cf7_descricao_custom_msg', 20, 2);
add_filter('wpcf7_validate_textarea*', 'cf7_descricao_custom_msg', 20, 2);

function cf7_descricao_custom_msg( $result, $tag ) {
    if ( isset($tag->name) && $tag->name === 'descricao' ) {
        $value = isset($_POST[$tag->name]) ? trim( wp_unslash($_POST[$tag->name]) ) : '';
        if ( $tag->is_required() && $value === '' ) {
            $result->invalidate($tag, 'Conte um pouco sobre a ideia para podermos avaliar.');
        }
    }
    return $result;
}

add_action( 'template_redirect', function() {
    if ( is_singular( 'apoio' ) ) {
        wp_redirect( home_url( '/mapa/?tab=apoio' ) );
        exit;
    }
});

