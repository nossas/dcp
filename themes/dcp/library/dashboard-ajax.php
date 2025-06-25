<?php

// TODO: CRIAR FUNÇÕES PARA CRUD DOS POSTS
function get_posts_riscos( $args = [ 'post_status' => 'publish' ] ) {

    $args['post_type'] = 'risco';
    $args['posts_per_page'] = -1;
    //$args['posts_per_page'] = 10;
    $args['orderby'] = 'date';
    $args['order'] = 'DESC';

    //$args['post_status'] = 'publish';
    //$args['post_status'] = 'draft';
    //$args['post_status'] = 'pending';
    //$args['post_status'] = 'future';
    //$args['post_status'] = 'private';

    return new WP_Query( $args );
}



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

        //'category' => sanitize_text_field( $_POST[ 'category' ] ),
        //'subcategory' => sanitize_text_field( $_POST[ 'subcategory' ] ),
        'descricao' => sanitize_text_field( $_POST[ 'descricao' ] ),
    ];

    //TODO: REMOVE DEPOIS DE TESTAR
    $data[ 'post_title' ] = '[' . $data[ 'post_status_current' ] . '] RISCO : ' . $data[ 'endereco' ];
    $data[ 'post_content' ] = 'RISCO : ' . $data[ 'endereco' ] . ' - ' . $data[ 'descricao' ];
    //TODO: REMOVE DEPOIS DE TESTAR

    $updated_id = wp_update_post( $data, true );

    if ( is_wp_error( $updated_id ) ) {

        wp_send_json_error([
            'title' => 'Erro',
            'message' => 'ID do post inválido ou post não encontrado.',
            'error' => $updated_id->get_error_message(),
        ], 500);

    }

    $pod = pods( 'risco', $postID );
    $pod->save( 'endereco', sanitize_text_field( $data[ 'endereco' ] ) );
    $pod->save( 'descricao', sanitize_text_field( $data[ 'descricao' ] ) );

    $errors = [];
    $uploaded_files = [];
    $files = $_FILES['media_files'];

    if( !empty( $files ) ) {

        foreach ( $files['name'] as $key => $item ) {

            if ( $files['error'][$key] === UPLOAD_ERR_OK ) {
                $file = [
                    'name'     => $files['name'][$key],
                    'type'     => $files['type'][$key],
                    'tmp_name' => $files['tmp_name'][$key],
                    'error'    => $files['error'][$key],
                    'size'     => $files['size'][$key]
                ];
            }

            $file_type = wp_check_filetype( $file['name'] );
            $allowed_types = array('jpg', 'jpeg', 'png', 'gif', 'mp4', 'mov' );

            if ( !in_array( strtolower( $file_type[ 'ext' ] ), $allowed_types ) ) {
                $errors[] = 'Arquivo "%s" não permitido. Apenas imagens e vídeos são aceitos.';
            }

            $attachment_id = media_handle_sideload( $file, $postID, $file[ 'name' ] );

            if (is_wp_error($attachment_id)) {
                $errors[] = sprintf(
                    __('Erro ao enviar "%s": %s', 'text-domain'),
                    $file['name'],
                    $attachment_id->get_error_message()
                );
            } else {
                $uploaded_files[] = array(
                    'id'  => $attachment_id,
                    'name'  => $file['name'],
                    'type'  => $file['type'],
                    'url' => wp_get_attachment_url( $attachment_id )
                );
            }

        }

    }

    if( empty( $errors ) ) {

        wp_send_json_success([
            'title' => 'Sucesso',
            'message' => 'Formulário enviado com sucesso!',
            'uploaded_files' => $uploaded_files,
            'updated_id' => $updated_id,
        ]);

    }

    wp_send_json_error([
        'title' => 'Erro',
        'message' => 'Erro ao salvar o formulário',
        'error' => $errors,
    ], 400 );

}
add_action('wp_ajax_form_single_risco_edit', 'form_single_risco_edit');
