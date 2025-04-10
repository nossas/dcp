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
