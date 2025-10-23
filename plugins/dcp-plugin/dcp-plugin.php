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
    register_rest_route('dcp/v1', '/riscos-resumo', [
        'methods' => 'GET',
        'callback' => 'dcp_get_riscos_resumo',
        'permission_callback' => '__return_true',
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
            'timestamp' => $pod->field('data_e_horario'),
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

function dcp_get_riscos_resumo($request) {
    $now = current_time('timestamp');
    $last_24h = wp_date('Y-m-d H:i:s', $now - 24 * 3600);

    $args = [
        'post_type'      => 'risco',
        'post_status'    => 'publish',
        'posts_per_page' => -1,
        'meta_query' => array(
            array(
            'key' => 'data_e_horario',
            'value' => $last_24h,
            'compare' => '>=',
            'type' => 'DATETIME',
            ),
        )
    ];

    $query = new WP_Query($args);

    $total = 0;
    $alagamento = 0;
    $lixo = 0;
    $outros = 0;

    foreach ($query->posts as $post) {
        $pod = pods('risco', $post->ID);
        $classificacao = strtolower(trim($pod->field('situacao_de_risco')[0]['slug']));

        $total++;

        if ($classificacao === 'alagamento') {
            $alagamento++;
        } elseif ($classificacao === 'lixo') {
            $lixo++;
        } else {
            $outros++;
        }
    }

    return rest_ensure_response([
        'total' => $total,
        'alagamento' => $alagamento,
        'lixo' => $lixo,
        'outros' => $outros,
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
// Função para adicionar a coluna "classificacao" antes da coluna "date" na listagem de riscos do painel do WordPress.
add_filter('manage_risco_posts_columns', function($columns) {
    $new_columns = [];
    foreach ($columns as $key => $value) {
        if ($key === 'date') {
            $new_columns['classificacao'] = __('Classificação', 'dcp-plugin');
            $new_columns['data_ocorrencia'] = __('Data de Ocorrência', 'dcp-plugin');
        }
        $new_columns[$key] = $value;
    }
    return $new_columns;
});

// Exibe os valores das colunas personalizadas
add_action('manage_risco_posts_custom_column', function($column, $post_id) {
    if ($column === 'classificacao') {
        $pod = pods('risco', $post_id);
        $classificacao = $pod->field('situacao_de_risco');
        if (is_array($classificacao) && isset($classificacao[0]['name'])) {
            echo esc_html($classificacao[0]['name']);
        } elseif (is_string($classificacao)) {
            echo esc_html($classificacao);
        } else {
            echo '-';
        }
    }
    if ($column === 'data_ocorrencia') {
        $pod = pods('risco', $post_id);
        $data = $pod->field('data_e_horario');
        if ($data) {
            echo esc_html($data);
        } else {
            echo '-';
        }
    }
}, 10, 2);

// Torna a coluna "data de ocorrência" ordenável
add_filter('manage_edit-risco_sortable_columns', function($columns) {
    $columns['data_ocorrencia'] = 'data_ocorrencia';
    return $columns;
});

// Altera a query para ordenar pela coluna "data de ocorrência"
add_action('pre_get_posts', function($query) {
    if (!is_admin() || !$query->is_main_query()) {
        return;
    }
    $orderby = $query->get('orderby');
    if ($query->get('post_type') === 'risco' && $orderby === 'data_ocorrencia') {
        $query->set('meta_key', 'data_e_horario');
        $query->set('orderby', 'meta_value');
    }
});


//
function dcp_webhook_situacao_atual($request) {

    $estagio = $request->get_param( 'estagio' );
    $clima = $request->get_param( 'clima' );

    $args = array(
        'post_type' => 'situacao_atual',
        'posts_per_page' => 1
    );

    if( !empty( $estagio ) ) {

        $args[ 'meta_query' ] = [
            [
                'key' => 'is_active',
                'value' => true,
                'compare' => '='
            ]
        ];
        $meta_key = 'estagio';

        switch ( strtolower( $estagio ) ) {

            case 'estágio 1':
                $meta_value = 1;
                break;
            case 'estágio 2':
                $meta_value = 2;
                break;
            case 'estágio 3':
                $meta_value = 3;
                break;
        }

    }

    if( !empty( $clima ) ) {
        switch ( strtolower( $clima ) ) {

            case 'calor 1':
                $args[ 'name' ] = 'situacao-atual-normal-ensolarado';
                break;

            case 'calor 2':
                $args[ 'name' ] = 'situacao-atual-atencao-calor';
                break;

            case 'calor 3':
                $args[ 'name' ] = 'situacao-atual-calor-extremo';
                break;
        }
        $meta_key = 'is_active';
        $meta_value = true;

        $situacoes_ids = get_posts([
            'post_type' => 'situacao_atual',
            'posts_per_page' => -1,
            'fields' => 'ids'
        ]);

        foreach ( $situacoes_ids as $id ) {
            $pods = \pods( 'situacao_atual', $id );
            $pods->save( 'is_active', false );
        }
    }


    $query = new WP_Query( $args );

    if ( $query->have_posts() ) {
        $query->the_post();
        $post_id = get_the_ID();

        sleep( 1 );

        $updated_post_id = update_post_meta( $post_id, $meta_key, $meta_value );

        if (is_wp_error($updated_post_id)) {
            $is_save = false;
        } else {
            $is_save = true;
        }

        wp_reset_postdata();
    } else {
        $is_save = false;
    }

    return rest_ensure_response([
        'status' => true,
        'data' => [
            'total' => 213,
            'is_save' => $is_save,
        ]
    ]);

}
add_action('rest_api_init', function () {
    register_rest_route('dcp/v1', '/webhook/situacao-atual', [
        'methods' => 'POST',
        'callback' => 'dcp_webhook_situacao_atual',
        'permission_callback' => '__return_true',
    ]);
});


function dcp_home_situacao_atual($request) {
    $args = array(
        'post_type' => 'recomendacao',
        'posts_per_page' => -1,
        'orderby' => 'date',
        'order' => 'ASC',
        'meta_query' => [
            [
                'key' => 'is_active',
                'value' => true,
                'compare' => '='
            ]
        ]
    );
    $recomendacoes = get_posts( $args );

//    $posts = [];
//    foreach ( $recomendacoes as $key => $post ) {
//        $pod = pods('recomendacao', $post->ID);
//
//        $posts[$key] = [
//            'recomendacao_1' => [
//                'icon' => $pod->display('icone_1.guid'),
//                'text' => $pod->display('recomendacao_1'),
//            ],
//            'recomendacao_2' => [
//                'icon' => $pod->display('icone_2.guid'),
//                'text' => $pod->display('recomendacao_2'),
//            ],
//            'recomendacao_3' => [
//                'icon' => $pod->display('icone_3.guid'),
//                'text' => $pod->display('recomendacao_3'),
//            ]
//        ];
//    }

    return rest_ensure_response([
        'status' => true,
        //'data' => $posts[0]
    ]);

}
add_action('rest_api_init', function () {
    register_rest_route('dcp/v1', '/situacao-atual-home', [
        'methods' => 'GET',
        'callback' => 'dcp_home_situacao_atual',
        'permission_callback' => function($request) {
            return current_user_can('write');
        }
    ]);
});
