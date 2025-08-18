<?php
/**
 * Plugin Name: DCP Plugin
 * Description: Cria um endpoint público de API REST com riscos para teste do bot.
 * Version: 1.0
 * Author: WordPress Wizard
 */

 add_action('rest_api_init', function () {
    register_rest_route('dcp/v1', '/riscos', [
        'methods' => 'GET',
        'callback' => 'dcp_get_riscos',
        'permission_callback' => '__return_true',
        'args' => [
            'per_page' => [
                'required' => false,
                'validate_callback' => function($value) {
                    return is_numeric($value);
                },
            ],
            'page' => [
                'required' => false,
                'validate_callback' => function($value) {
                    return is_numeric($value);
                },
            ],
        ],
    ]);
});


function dcp_get_riscos($request) {
    $per_page = intval($request->get_param('per_page') ?? 10);
    $page = intval($request->get_param('page') ?? 1);

    $args = [
        'post_type'      => 'risco',
        'post_status'    => 'publish',
        'posts_per_page' => $per_page,
        'paged'          => $page,
        'orderby'        => 'date',
        'order'          => 'DESC',
    ];

    $query = new WP_Query($args);

    $riscos = [];

    foreach ($query->posts as $post) {
        $post_id = $post->ID;
        $pod = pods('risco', $post_id);

        $riscos[] = [
            'id' => $post_id,
            'timestamp' => get_the_date('c', $post),
            'id_usuario' => $post->post_author,
            'latitude' => $pod->field('latitude'),
            'longitude' => $pod->field('longitude'),
            'endereco' => $pod->field('endereco'),
            'classificacao' => $pod->field('situacao_de_risco'),
            'descricao' => $pod->field('descricao'),
            'url_imagens' => get_risco_attachments_urls($post_id, 'image'),
            'url_videos' => get_risco_attachments_urls($post_id, 'video'),
            'identificar' => (bool) $pod->field('autoriza_contato'),
        ];
    }

    return rest_ensure_response([
        'page' => $page,
        'per_page' => $per_page,
        'total' => $query->found_posts,
        'total_pages' => $query->max_num_pages,
        'data' => $riscos,
    ]);
}

function get_risco_attachments_urls($post_id, $type = 'image') {
    $args = [
        'post_parent' => $post_id,
        'post_type' => 'attachment',
        'post_status' => 'inherit',
        'numberposts' => -1,
    ];

    $attachments = get_posts($args);

    $urls = [];

    foreach ($attachments as $attachment) {
        $mime = get_post_mime_type($attachment->ID);

        if (
            ($type === 'image' && strpos($mime, 'image/') === 0) ||
            ($type === 'video' && strpos($mime, 'video/') === 0)
        ) {
            $urls[] = wp_get_attachment_url($attachment->ID);
        }
    }

    return $urls;
}

function dcp_api_abrigos($request) {
    $query_args = [
        'post_type'      => 'apoio',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'fields'         => 'ids',
        'tax_query'      => [[
            'taxonomy' => 'tipo_apoio',
            'field'    => 'slug',
            'terms'    => 'locais-seguros',
        ]]
    ];

    $locaisSeguros = new WP_Query($query_args);
    $abrigos = [];

    foreach ( $locaisSeguros->posts as $post ) {

        $pod = pods('apoio', $post );
        $abrigos[] = [
            'id' => $post,
            'nome' => $pod->field( 'titulo' ),
            'endereco' => $pod->field( 'endereco' ),
            'latitude' => $pod->field( 'latitude' ),
            'longitude' => $pod->field( 'longitude' ),
            'geo_full_address' => $pod->field( 'full_address' )
        ];
    }

    return rest_ensure_response($abrigos);
}
add_action('rest_api_init', function () {
    register_rest_route('dcp/v1', '/abrigos', [
        'methods' => 'GET',
        'callback' => 'dcp_api_abrigos',
        'permission_callback' => '__return_true',
    ]);
});



add_action('rest_api_init', function () {
    register_rest_route('dcp/v1', '/dicas', [
        'methods' => 'GET',
        'callback' => 'dcp_api_dicas',
        'args' => [
            'tipo' => [
                'required' => true,
                'validate_callback' => function($param) {
                    return in_array($param, ['enchente', 'lixo', 'calor']);
                },
            ],
        ],
        'permission_callback' => '__return_true',
    ]);
});

function dcp_api_dicas($request) {
    $tipo = $request->get_param('tipo');

    $dicas = [
        'enchente' => [
            'Evite contato com a água da enchente.',
            'Desligue a energia elétrica ao sinal de alagamento.',
            'Mantenha documentos e objetos importantes em locais altos.',
        ],
        'lixo' => [
            'Não jogue lixo nas ruas ou em bueiros.',
            'Separe resíduos recicláveis e orgânicos.',
            'Mantenha o lixo tampado para evitar proliferação de doenças.',
        ],
        'calor' => [
            'Beba bastante água durante o dia.',
            'Evite exposição ao sol entre 10h e 16h.',
            'Use roupas leves e protetor solar.',
        ],
    ];

    return rest_ensure_response($dicas[$tipo]);
}

add_action('rest_api_init', function () {
    register_rest_route('dcp/v1', '/contatos', [
        'methods' => 'GET',
        'callback' => 'dcp_api_contatos',
        'permission_callback' => '__return_true',
    ]);
});

function dcp_api_contatos($request) {
    $contatos = [
        [
            'nome' => '🚒 Bombeiros',
            'telefone' => '193',
            'descricao' => 'Incêndios, desmoronamentos e resgates.',
        ],
        [
            'nome' => '🏠 Defesa Civil',
            'telefone' => '199',
            'descricao' => 'Ajuda em enchentes, deslizamentos e outras situações de risco.',
        ],
        [
            'nome' => '🚑 SAMU',
            'telefone' => '192',
            'descricao' => 'Emergências médicas e acidentes.',
        ],
    ];


    return rest_ensure_response($contatos);
}

add_action('rest_api_init', function () {
    register_rest_route('dcp/v1', '/risco-regiao', [
        'methods' => 'GET',
        'callback' => 'dcp_api_risco_regiao',
        'permission_callback' => '__return_true',
    ]);
});

function dcp_api_risco_regiao($request) {

    $minuto = intval(date('s'));
    $risco_index = $minuto % 3;

    $graus = ['Baixo', 'Médio', 'Alto'];
    $grau_risco = $graus[$risco_index];

    return rest_ensure_response([
        'grau_risco' => $grau_risco,
    ]);
}
