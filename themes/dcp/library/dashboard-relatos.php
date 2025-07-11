<?php


function form_single_relato_new() {

    wp_send_json_error([
        'title' => 'Erro',
        'message' => 'Erro ao salvar o formulário',
        //'error' => $save_post[ 'errors' ],
    ], 400 );

}
add_action('wp_ajax_form_single_relato_new', 'form_single_relato_new');
