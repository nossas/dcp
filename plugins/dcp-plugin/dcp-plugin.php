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
    $riscos = [
        [
            'id' => 1,
            'timestamp' => '2025-04-10T12:00:00',
            'id_usuario' => 101,
            'latitude' => -23.55052,
            'longitude' => -46.633308,
            'endereco' => 'Av. Paulista, São Paulo, SP',
            'classificacao' => 'Alto',
            'descricao' => 'Deslizamento de terra reportado.',
            'url_imagens' => ['https://imgs.mongabay.com/wp-content/uploads/sites/29/2024/12/16154440/1280px-05.05.2024_-_Sobrevoo_das_areas_afetadas_pelas_chuvas_na_Regiao_metropolitana_de_Porto_Alegre.jpg','https://imgs.mongabay.com/wp-content/uploads/sites/29/2024/12/16154440/1280px-05.05.2024_-_Sobrevoo_das_areas_afetadas_pelas_chuvas_na_Regiao_metropolitana_de_Porto_Alegre.jpg','https://imgs.mongabay.com/wp-content/uploads/sites/29/2024/12/16154440/1280px-05.05.2024_-_Sobrevoo_das_areas_afetadas_pelas_chuvas_na_Regiao_metropolitana_de_Porto_Alegre.jpg'],
            'url_videos' => ['https://willow.art.br/VID_20250421_170928.mp4','https://willow.art.br/VID_20250421_170913.mp4'],
            'identificar' => true,
        ],
        [
            'id' => 2,
            'timestamp' => '2025-04-09T08:30:00',
            'id_usuario' => 102,
            'latitude' => -22.906847,
            'longitude' => -43.172896,
            'endereco' => 'Centro, Rio de Janeiro, RJ',
            'classificacao' => 'Médio',
            'descricao' => 'Enchente em rua principal.',
            'url_imagens' => ['https://imgs.mongabay.com/wp-content/uploads/sites/29/2024/12/16154440/1280px-05.05.2024_-_Sobrevoo_das_areas_afetadas_pelas_chuvas_na_Regiao_metropolitana_de_Porto_Alegre.jpg','https://imgs.mongabay.com/wp-content/uploads/sites/29/2024/12/16154440/1280px-05.05.2024_-_Sobrevoo_das_areas_afetadas_pelas_chuvas_na_Regiao_metropolitana_de_Porto_Alegre.jpg','https://imgs.mongabay.com/wp-content/uploads/sites/29/2024/12/16154440/1280px-05.05.2024_-_Sobrevoo_das_areas_afetadas_pelas_chuvas_na_Regiao_metropolitana_de_Porto_Alegre.jpg'],
            'url_videos' => ['https://willow.art.br/VID_20250421_170928.mp4'],
            'identificar' => false,
        ],
        [
            'id' => 3,
            'timestamp' => '2025-04-08T16:45:00',
            'id_usuario' => 103,
            'latitude' => -15.794229,
            'longitude' => -47.882166,
            'endereco' => 'Asa Norte, Brasília, DF',
            'classificacao' => 'Baixo',
            'descricao' => 'Árvore caída próxima a escola.',
            'url_imagens' => ['https://imgs.mongabay.com/wp-content/uploads/sites/29/2024/12/16154440/1280px-05.05.2024_-_Sobrevoo_das_areas_afetadas_pelas_chuvas_na_Regiao_metropolitana_de_Porto_Alegre.jpg','https://imgs.mongabay.com/wp-content/uploads/sites/29/2024/12/16154440/1280px-05.05.2024_-_Sobrevoo_das_areas_afetadas_pelas_chuvas_na_Regiao_metropolitana_de_Porto_Alegre.jpg','https://imgs.mongabay.com/wp-content/uploads/sites/29/2024/12/16154440/1280px-05.05.2024_-_Sobrevoo_das_areas_afetadas_pelas_chuvas_na_Regiao_metropolitana_de_Porto_Alegre.jpg'],
            'url_videos' => ['https://willow.art.br/VID_20250421_170928.mp4','https://willow.art.br/VID_20250421_170913.mp4'],
            'identificar' => true,
        ],
        [
            'id' => 4,
            'timestamp' => '2025-04-07T22:10:00',
            'id_usuario' => 104,
            'latitude' => -30.034647,
            'longitude' => -51.217658,
            'endereco' => 'Centro Histórico, Porto Alegre, RS',
            'classificacao' => 'Alto',
            'descricao' => 'Incêndio em edifício abandonado.',
            'url_imagens' => ['https://imgs.mongabay.com/wp-content/uploads/sites/29/2024/12/16154440/1280px-05.05.2024_-_Sobrevoo_das_areas_afetadas_pelas_chuvas_na_Regiao_metropolitana_de_Porto_Alegre.jpg','https://imgs.mongabay.com/wp-content/uploads/sites/29/2024/12/16154440/1280px-05.05.2024_-_Sobrevoo_das_areas_afetadas_pelas_chuvas_na_Regiao_metropolitana_de_Porto_Alegre.jpg','https://imgs.mongabay.com/wp-content/uploads/sites/29/2024/12/16154440/1280px-05.05.2024_-_Sobrevoo_das_areas_afetadas_pelas_chuvas_na_Regiao_metropolitana_de_Porto_Alegre.jpg'],
            'url_videos' => ['https://willow.art.br/VID_20250421_170928.mp4'],
            'identificar' => false,
        ],
        [
            'id' => 5,
            'timestamp' => '2025-04-06T14:20:00',
            'id_usuario' => 105,
            'latitude' => -3.731862,
            'longitude' => -38.526669,
            'endereco' => 'Aldeota, Fortaleza, CE',
            'classificacao' => 'Médio',
            'descricao' => 'Obstrução de via pública por alagamento.',
            'url_imagens' => ['https://imgs.mongabay.com/wp-content/uploads/sites/29/2024/12/16154440/1280px-05.05.2024_-_Sobrevoo_das_areas_afetadas_pelas_chuvas_na_Regiao_metropolitana_de_Porto_Alegre.jpg','https://imgs.mongabay.com/wp-content/uploads/sites/29/2024/12/16154440/1280px-05.05.2024_-_Sobrevoo_das_areas_afetadas_pelas_chuvas_na_Regiao_metropolitana_de_Porto_Alegre.jpg','https://imgs.mongabay.com/wp-content/uploads/sites/29/2024/12/16154440/1280px-05.05.2024_-_Sobrevoo_das_areas_afetadas_pelas_chuvas_na_Regiao_metropolitana_de_Porto_Alegre.jpg'],
            'url_videos' => ['https://willow.art.br/VID_20250421_170928.mp4'],
            'identificar' => true,
        ],
        [
            'id' => 6,
            'timestamp' => '2025-04-05T09:00:00',
            'id_usuario' => 106,
            'latitude' => -9.66599,
            'longitude' => -35.735,
            'endereco' => 'Jaraguá, Maceió, AL',
            'classificacao' => 'Baixo',
            'descricao' => 'Risco de curto-circuito após chuva intensa.',
            'url_imagens' => ['https://imgs.mongabay.com/wp-content/uploads/sites/29/2024/12/16154440/1280px-05.05.2024_-_Sobrevoo_das_areas_afetadas_pelas_chuvas_na_Regiao_metropolitana_de_Porto_Alegre.jpg','https://imgs.mongabay.com/wp-content/uploads/sites/29/2024/12/16154440/1280px-05.05.2024_-_Sobrevoo_das_areas_afetadas_pelas_chuvas_na_Regiao_metropolitana_de_Porto_Alegre.jpg','https://imgs.mongabay.com/wp-content/uploads/sites/29/2024/12/16154440/1280px-05.05.2024_-_Sobrevoo_das_areas_afetadas_pelas_chuvas_na_Regiao_metropolitana_de_Porto_Alegre.jpg'],
            'url_videos' => ['https://willow.art.br/VID_20250421_170928.mp4','https://willow.art.br/VID_20250421_170913.mp4'],
            'identificar' => false,
        ],
        [
            'id' => 7,
            'timestamp' => '2025-04-04T18:15:00',
            'id_usuario' => 107,
            'latitude' => -12.9714,
            'longitude' => -38.5014,
            'endereco' => 'Pelourinho, Salvador, BA',
            'classificacao' => 'Alto',
            'descricao' => 'Desabamento parcial de imóvel antigo.',
            'url_imagens' => ['https://imgs.mongabay.com/wp-content/uploads/sites/29/2024/12/16154440/1280px-05.05.2024_-_Sobrevoo_das_areas_afetadas_pelas_chuvas_na_Regiao_metropolitana_de_Porto_Alegre.jpg','https://imgs.mongabay.com/wp-content/uploads/sites/29/2024/12/16154440/1280px-05.05.2024_-_Sobrevoo_das_areas_afetadas_pelas_chuvas_na_Regiao_metropolitana_de_Porto_Alegre.jpg','https://imgs.mongabay.com/wp-content/uploads/sites/29/2024/12/16154440/1280px-05.05.2024_-_Sobrevoo_das_areas_afetadas_pelas_chuvas_na_Regiao_metropolitana_de_Porto_Alegre.jpg'],
            'url_videos' => ['https://willow.art.br/VID_20250421_170928.mp4'],
            'identificar' => true,
        ],
        [
            'id' => 8,
            'timestamp' => '2025-04-03T07:45:00',
            'id_usuario' => 108,
            'latitude' => -25.4284,
            'longitude' => -49.2733,
            'endereco' => 'Batel, Curitiba, PR',
            'classificacao' => 'Médio',
            'descricao' => 'Fumaça densa vinda de mata próxima.',
            'url_imagens' => ['https://imgs.mongabay.com/wp-content/uploads/sites/29/2024/12/16154440/1280px-05.05.2024_-_Sobrevoo_das_areas_afetadas_pelas_chuvas_na_Regiao_metropolitana_de_Porto_Alegre.jpg','https://imgs.mongabay.com/wp-content/uploads/sites/29/2024/12/16154440/1280px-05.05.2024_-_Sobrevoo_das_areas_afetadas_pelas_chuvas_na_Regiao_metropolitana_de_Porto_Alegre.jpg','https://imgs.mongabay.com/wp-content/uploads/sites/29/2024/12/16154440/1280px-05.05.2024_-_Sobrevoo_das_areas_afetadas_pelas_chuvas_na_Regiao_metropolitana_de_Porto_Alegre.jpg'],
            'url_videos' => ['https://willow.art.br/VID_20250421_170928.mp4'],
            'identificar' => false,
        ],
        [
            'id' => 9,
            'timestamp' => '2025-04-02T13:00:00',
            'id_usuario' => 109,
            'latitude' => -10.9472,
            'longitude' => -37.0731,
            'endereco' => 'Centro, Aracaju, SE',
            'classificacao' => 'Baixo',
            'descricao' => 'Vazamento de água em avenida principal.',
            'url_imagens' => ['https://imgs.mongabay.com/wp-content/uploads/sites/29/2024/12/16154440/1280px-05.05.2024_-_Sobrevoo_das_areas_afetadas_pelas_chuvas_na_Regiao_metropolitana_de_Porto_Alegre.jpg','https://imgs.mongabay.com/wp-content/uploads/sites/29/2024/12/16154440/1280px-05.05.2024_-_Sobrevoo_das_areas_afetadas_pelas_chuvas_na_Regiao_metropolitana_de_Porto_Alegre.jpg','https://imgs.mongabay.com/wp-content/uploads/sites/29/2024/12/16154440/1280px-05.05.2024_-_Sobrevoo_das_areas_afetadas_pelas_chuvas_na_Regiao_metropolitana_de_Porto_Alegre.jpg'],
            'url_videos' => ['https://willow.art.br/VID_20250421_170928.mp4'],
            'identificar' => true,
        ],
        [
            'id' => 10,
            'timestamp' => '2025-04-01T21:30:00',
            'id_usuario' => 110,
            'latitude' => -22.1183,
            'longitude' => -51.3974,
            'endereco' => 'Centro, Presidente Prudente, SP',
            'classificacao' => 'Médio',
            'descricao' => 'Rompimento de galeria pluvial.',
            'url_imagens' => ['https://imgs.mongabay.com/wp-content/uploads/sites/29/2024/12/16154440/1280px-05.05.2024_-_Sobrevoo_das_areas_afetadas_pelas_chuvas_na_Regiao_metropolitana_de_Porto_Alegre.jpg','https://imgs.mongabay.com/wp-content/uploads/sites/29/2024/12/16154440/1280px-05.05.2024_-_Sobrevoo_das_areas_afetadas_pelas_chuvas_na_Regiao_metropolitana_de_Porto_Alegre.jpg','https://imgs.mongabay.com/wp-content/uploads/sites/29/2024/12/16154440/1280px-05.05.2024_-_Sobrevoo_das_areas_afetadas_pelas_chuvas_na_Regiao_metropolitana_de_Porto_Alegre.jpg'],
            'url_videos' => ['https://willow.art.br/VID_20250421_170928.mp4','https://willow.art.br/VID_20250421_170913.mp4'],
            'identificar' => false,
        ],
    ];
    
    

    $per_page = $request->get_param('per_page');
    $page = $request->get_param('page');

    // Se ambos os parâmetros estiverem definidos, aplica a paginação
    if ($per_page && $page) {
        $per_page = intval($per_page);
        $page = intval($page);
        $offset = ($page - 1) * $per_page;
        $riscos = array_slice($riscos, $offset, $per_page);
    }

    return rest_ensure_response($riscos);
}


add_action('rest_api_init', function () {
    register_rest_route('dcp/v1', '/abrigos', [
        'methods' => 'GET',
        'callback' => 'dcp_api_abrigos',
        'permission_callback' => '__return_true',
    ]);
});

function dcp_api_abrigos($request) {
    $abrigos = [
        [
            'id' => 1,
            'nome' => 'Igreja Padre Nelson',
            'endereco' => 'Rua Projetada A, 120 - Jacarezinho, Japeri - RJ',        
        ],
        [
            'id' => 2,
            'nome' => 'Igreja Batista',
            'endereco' => 'Av. Principal, 980 - Jacarezinho, Japeri - RJ',
        ],
        [
            'id' => 3,
            'nome' => 'Centro cultural Jacarézinho',
            'endereco' => 'Rua da Paz, 789 - Vila Nova',
        ],
    ];

    return rest_ensure_response($abrigos);
}
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
            'nome' => 'Defesa Civil',
            'telefone' => '199',
            'descricao' => 'Atendimento a emergências e desastres naturais',
        ],
        [
            'nome' => 'Corpo de Bombeiros',
            'telefone' => '193',
            'descricao' => 'Combate a incêndios e resgate de vítimas',
        ],
        [
            'nome' => 'Posto de Saúde Central',
            'telefone' => '(21) 9999-8888',
            'descricao' => 'Atendimento médico de urgência em JCarezinho',
        ],
        [
            'nome' => 'Polícia Militar',
            'telefone' => '190',
            'descricao' => 'Emergências de segurança pública',
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
