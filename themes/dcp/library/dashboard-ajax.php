<?php








function form_single_acao_new() {

    wp_send_json_error([
        'title' => 'Erro',
        'message' => 'Erro ao salvar o formulário',
        //'error' => $save_post[ 'errors' ],
    ], 400 );

}
add_action('wp_ajax_form_single_acao_new', 'form_single_acao_new');


function form_single_acao_edit() {

    if (!current_user_can('edit_posts')) {
        wp_send_json_error([
            'title' => 'Erro',
            'message' => 'Você não tem permissão para editar posts.',
            'error' => [],
        ], 403);
    }

    $postID = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

    if ( !$postID || !get_post( $postID ) ) {
        wp_send_json_error([
            'title' => 'Erro',
            'message' => 'ID do post inválido ou post não encontrado.',
            'error' => [],
        ], 400);
    }

    $data = [
        'ID' => $postID,
        'post_type' => 'acao',
        'post_status' => sanitize_text_field($_POST['post_status'] ?? 'draft'),
        'post_title' => sanitize_text_field( $_POST[ 'endereco' ] ),
        'post_content' => sanitize_text_field( $_POST[ 'descricao' ] ),
        'meta_input' => [
            'endereco' => sanitize_text_field( $_POST[ 'endereco' ] ),
            'descricao' => sanitize_text_field( $_POST[ 'descricao' ] ),
            'titulo' => sanitize_text_field( $_POST[ 'titulo' ] ),
            'data' => sanitize_text_field( $_POST[ 'data' ] ),
            'horario' => sanitize_text_field( $_POST[ 'horario' ] )
        ],
    ];

    $updated_id = wp_update_post( $data, true );

    if ( is_wp_error( $updated_id ) ) {

        wp_send_json_error([
            'title' => 'Erro',
            'message' => 'ID do post inválido ou post não encontrado.',
            'error' => $updated_id->get_error_message(),
        ], 500);

    }

    wp_set_object_terms(
        $postID,
        array(
            sanitize_text_field( $_POST[ 'tipo_acao' ] )
        ),
        'tipo_acao',
        false
    );

    $save_post = upload_file_to_attachment_by_ID( $_FILES['media_files'], $postID );

    if( empty( $save_post[ 'errors' ] ) ) {

        wp_send_json_success([
            'title' => 'Sucesso',
            'message' => 'Formulário enviado com sucesso!',
            'uploaded_files' => $save_post[ 'uploaded_files' ],
            'post_id' => $postID,
        ]);

    }

    wp_send_json_error([
        'title' => 'Erro',
        'message' => 'Erro ao salvar o formulário',
        'error' => $save_post[ 'errors' ],
    ], 400 );

}
add_action('wp_ajax_form_single_acao_edit', 'form_single_acao_edit');




function form_single_risco_new() {

    $term = get_term_by( 'slug', sanitize_text_field( $_POST[ 'situacao_de_risco' ] ), 'situacao_de_risco' );

    $data = [
        'post_type' => 'risco',
        'post_status' => 'draft',
        'post_title' => sanitize_text_field( $_POST[ 'endereco' ] ),
        'post_content' => sanitize_text_field( $_POST['descricao'] ),
        'meta_input' => [
            'endereco' => sanitize_text_field( $_POST[ 'endereco' ] ),
            'latitude' => sanitize_text_field( $_POST[ 'latitude' ] ),
            'longitude' => sanitize_text_field( $_POST[ 'longitude' ] ),
            'nome_completo' => sanitize_text_field( $_POST[ 'nome_completo' ] ),
            'email' => sanitize_text_field( $_POST[ 'email' ] ),
            'telefone' => sanitize_text_field( $_POST[ 'telefone' ] ),
            'autoriza_contato' => sanitize_text_field( $_POST[ 'autoriza_contato' ] ),
            'data_e_horario' => date('Y-m-d H:i:s'),
            'descricao' => sanitize_text_field( $_POST[ 'descricao' ] ),
        ],
        'tax_input' => [
            'situacao_de_risco' => [ $term->term_id ],
        ],
    ];

    $postID = wp_insert_post( $data, true );

    if ( is_wp_error( $postID ) ) {

        wp_send_json_error([
            'title' => 'Erro',
            'message' => 'Erro ao cadastrar o risco.',
            'error' => $postID->get_error_message()
        ], 500 );

    }

    $new_terms = array(
        sanitize_text_field( $_POST[ 'situacao_de_risco' ] )
    );
    foreach ( $_POST[ 'subcategories' ] as $term ) {
        $new_terms[] = sanitize_text_field( $term );
    }
    wp_set_object_terms( $postID, $new_terms, 'situacao_de_risco', false );

    $pod = pods( 'risco', $postID );
    $pod->save( 'endereco', sanitize_text_field( $data[ 'meta_input' ][ 'endereco' ] ) );
    $pod->save( 'descricao', sanitize_text_field( $data[ 'meta_input' ][ 'descricao' ] ) );
    $pod->save( 'latitude', sanitize_text_field( $data[ 'meta_input' ][ 'latitude' ] ) );
    $pod->save( 'longitude', sanitize_text_field( $data[ 'meta_input' ][ 'longitude' ] ) );
    $pod->save( 'nome_completo', sanitize_text_field( $data[ 'meta_input' ][ 'nome_completo' ] ) );
    $pod->save( 'email', sanitize_text_field( $data[ 'meta_input' ][ 'email' ] ) );
    $pod->save( 'telefone', sanitize_text_field( $data[ 'meta_input' ][ 'telefone' ] ) );
    //$pod->save( 'autoriza_contato', sanitize_text_field( $data[ 'autoriza_contato' ] ) );
    $pod->save( 'data_e_horario', sanitize_text_field( $data[ 'meta_input' ][ 'data_e_horario' ] ) );

    $save_post = upload_file_to_attachment_by_ID( $_FILES['media_files'], $postID );

    if( empty( $save_post[ 'errors' ] ) ) {

        wp_send_json_success([
            'title' => 'Sucesso',
            'message' => 'Formulário enviado com sucesso!',
            'uploaded_files' => $save_post[ 'uploaded_files' ],
            'post_id' => $postID,
            'is_new' => true,
        ]);

    }

    wp_send_json_error([
        'title' => 'Erro',
        'message' => 'Erro ao salvar o formulário',
        'error' => $save_post[ 'errors' ],
    ], 400 );

}
add_action('wp_ajax_form_single_risco_new', 'form_single_risco_new');
add_action('wp_ajax_nopriv_form_single_risco_new', 'form_single_risco_new');

function form_single_risco_edit() {

    if (!current_user_can('edit_posts')) {
        wp_send_json_error([
            'title' => 'Erro',
            'message' => 'Você não tem permissão para editar posts.',
            'error' => [],
        ], 403);
    }

    $postID = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

    if ( !$postID || !get_post( $postID ) ) {
        wp_send_json_error([
            'title' => 'Erro',
            'message' => 'ID do post inválido ou post não encontrado.',
            'error' => [],
        ], 400);
    }

    $data = [
        'ID' => $postID,
        'post_type' => 'risco',
        'post_status' => sanitize_text_field($_POST['post_status'] ?? 'draft'),
        'endereco' => sanitize_text_field( $_POST[ 'endereco' ] ),
        'descricao' => sanitize_text_field( $_POST[ 'descricao' ] ),
        'post_title' => sanitize_text_field( $_POST[ 'endereco' ] ),
        'post_content' => sanitize_text_field( $_POST[ 'descricao' ] ),
        'meta_input' => [
            'endereco' => sanitize_text_field( $_POST[ 'endereco' ] ),
            'descricao' => sanitize_text_field( $_POST[ 'descricao' ] ),
        ],
    ];

    $updated_id = wp_update_post( $data, true );

    if ( is_wp_error( $updated_id ) ) {

        wp_send_json_error([
            'title' => 'Erro',
            'message' => 'ID do post inválido ou post não encontrado.',
            'error' => $updated_id->get_error_message(),
        ], 500);

    }

    $new_terms = array(
        sanitize_text_field( $_POST[ 'situacao_de_risco' ] )
    );

    foreach ( $_POST[ 'subcategories' ] as $term ) {
        $new_terms[] = sanitize_text_field( $term );
    }

    wp_set_object_terms( $postID, $new_terms, 'situacao_de_risco', false );


    $pod = pods( 'risco', $postID );
    $pod->save( 'endereco', sanitize_text_field( $data[ 'endereco' ] ) );
    $pod->save( 'descricao', sanitize_text_field( $data[ 'descricao' ] ) );

    $save_post = upload_file_to_attachment_by_ID( $_FILES['media_files'], $postID );

    if( empty( $save_post[ 'errors' ] ) ) {

        wp_send_json_success([
            'title' => 'Sucesso',
            'message' => 'Formulário enviado com sucesso!',
            'uploaded_files' => $save_post[ 'uploaded_files' ],
            'post_id' => $postID,
        ]);

    }

    wp_send_json_error([
        'title' => 'Erro',
        'message' => 'Erro ao salvar o formulário',
        'error' => $save_post[ 'errors' ],
    ], 400 );

}
add_action('wp_ajax_form_single_risco_edit', 'form_single_risco_edit');



function form_single_delete_attachment() {

    if (!current_user_can('edit_posts')) {
        wp_send_json_error([
            'title' => 'Erro',
            'message' => 'Você não tem permissão para editar posts.',
            'error' => [],
        ], 403 );
    }

    $postID = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    $attachmentID = isset($_POST['attachment_id']) ? intval($_POST['attachment_id']) : 0;

    if ( !$postID || !get_post( $postID ) ) {
        wp_send_json_error([
            'title' => 'Erro',
            'message' => 'ID do post inválido ou post não encontrado.',
            'error' => [],
        ], 400);
    }

    if( wp_delete_attachment( $attachmentID, true) ) {

        wp_send_json_success([
            'title' => 'Sucesso',
            'message' => 'Mídia deletada com sucesso!'
        ]);

    }
    else {

        wp_send_json_error([
            'title' => 'Erro',
            'message' => 'Erro ao deletar mídia'
        ], 400 );

    }

}
add_action('wp_ajax_form_single_delete_attachment', 'form_single_delete_attachment');
