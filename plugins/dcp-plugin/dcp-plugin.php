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
            'telefone' => $pod->field( 'telefone' ),
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



function dcp_api_dicas($request) {

    $tipo = $request->get_param( 'tipo' );
    $is_active = $request->get_param( 'active' );

    $args = array(
        'post_type' => 'recomendacao',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'ASC',
    );

    if (!is_null($is_active)) {

        $active_value = filter_var($is_active, FILTER_VALIDATE_BOOLEAN) ? '1' : '0';
        $args['meta_query'] = array(
            array(
                'key' => 'is_active',
                'value' => $active_value,
                'compare' => '='
            )
        );
    }

    $recomendacoes = get_posts( $args );
    $posts = [];
    foreach ( $recomendacoes as $key => $post ) {
        $pod = pods('recomendacao', $post->ID);

        if( !empty( $tipo ) ) {

            if( $tipo === $post->post_name ) {

                $posts = [
                    'ID' => $post->ID,
                    'title' => $post->post_title,
                    'slug' => $post->post_name,
                    'is_active' => $pod->field('is_active'),
                    'recomendacoes' => [
                        $pod->display('recomendacao_1'),
                        $pod->display('recomendacao_2'),
                        $pod->display('recomendacao_3')
                    ]
                ];

            } else {
                $posts[ 'error' ] = 'Dica não encontrada';
            }

        } else {

            $posts[ $key ] = [
                'ID' => $post->ID,
                'title' => $post->post_title,
                'slug' => $post->post_name,
                'is_active' => $pod->field('is_active'),
                'recomendacoes' => [
                    $pod->display('recomendacao_1'),
                    $pod->display('recomendacao_2'),
                    $pod->display('recomendacao_3')
                ]
            ];

        }
    }
    return rest_ensure_response( $posts );
}
add_action('rest_api_init', function () {
    register_rest_route('dcp/v1', '/dicas', [
        'methods' => 'GET',
        'callback' => 'dcp_api_dicas',
        'args' => [
            'tipo' => [
                'required' => false,
                'validate_callback' => function( $param ) {
                    return $param;
                },
            ],
            'active' => array(
                'required' => false,
                'validate_callback' => function( $param ) {
                    return in_array( $param, array( 'true', 'false', '1', '0', 'yes', 'no' ) );
                }
            ),
        ],
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
    register_rest_route('dcp/v1', '/contatos', [
        'methods' => 'GET',
        'callback' => 'dcp_api_contatos',
        'permission_callback' => '__return_true',
    ]);
});


function dcp_api_risco_regiao($request) {

    $situacao_ativa_post = get_posts([
        'post_type' => 'situacao_atual',
        'posts_per_page' => -1,
        'orderby'        => 'date',
        'order'          => 'ASC',
        'meta_query' => [
            [
                'key' => 'is_active',
                'value' => true,
                'compare' => '='
            ]
        ]
    ]);
    $pod_situacao_ativa = pods( 'situacao_atual', $situacao_ativa_post[ 0 ]->ID );

    return rest_ensure_response([
        'grau_risco' => [
            'tipo_de_alerta' => $pod_situacao_ativa->field( 'tipo_de_alerta' ),
            'descricao' => $pod_situacao_ativa->field( 'descricao' ),
            'localizacao' => $pod_situacao_ativa->field( 'localizacao' ),
            'estagio' => $pod_situacao_ativa->field( 'estagio' ),
            'temperatura' => $pod_situacao_ativa->field( 'temperatura' ),
            'clima' => $pod_situacao_ativa->field( 'clima' ),
            'ultima_atualizacao' => $pod_situacao_ativa->field( 'data_e_horario' ),
        ]
    ]);
}
add_action('rest_api_init', function () {
    register_rest_route('dcp/v1', '/risco-regiao', [
        'methods' => 'GET',
        'callback' => 'dcp_api_risco_regiao',
        'permission_callback' => '__return_true',
    ]);
});
