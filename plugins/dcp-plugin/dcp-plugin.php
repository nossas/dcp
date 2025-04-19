<?php
/**
 * Plugin Name: DCP Plugin
 * Description: Cria um endpoint público de API REST com riscos para teste do bot.
 * Version: 1.0
 * Author: WordPress Wizard
 */

add_action('rest_api_init', function () {
    register_rest_route('riscos/v1', '/list', [
        'methods'  => 'GET',
        'callback' => 'dcp_get_riscos',
        'permission_callback' => '__return_true', // Endpoint público
    ]);
});

function dcp_get_riscos() {
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
            'id_midias' => [1, 2],
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
            'id_midias' => [3],
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
            'id_midias' => [],
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
            'id_midias' => [4, 5, 6],
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
            'id_midias' => [7],
            'identificar' => true,
        ],
    ];

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
        'args' => [
            'regiao' => [
                'required' => true,
                'validate_callback' => function($param) {
                    return is_string($param) && strlen($param) > 1;
                },
            ],
        ],
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
