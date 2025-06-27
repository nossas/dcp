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

function get_dashboard_riscos() {

    return [
        'riscosAprovacao' => [
            'is_active' => true,
            'pagination' => false,
            'riscos' => new WP_Query([
                'post_type'      => 'risco',
                'post_status'    => 'draft',
                'posts_per_page' => -1,
                'orderby'        => 'date',
                'order'          => 'DESC'
            ])
        ],
        'riscosPublicados' =>[
            'is_active' => false,
            'pagination' => false,
            'riscos' => new WP_Query([
                'post_type'      => 'risco',
                'post_status'    => 'publish',
                'posts_per_page' => -1,
                'orderby'        => 'date',
                'order'          => 'DESC'
            ])
        ],
        'riscosArquivados' => [
            'is_active' => false,
            'pagination' => false,
            'riscos' => new WP_Query([
                'post_type'      => 'risco',
                'post_status'    => 'pending',
                'posts_per_page' => -1,
                'orderby'        => 'date',
                'order'          => 'DESC'
            ])
        ],
    ];

}

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
        //sanitize_text_field( $_POST[ 'subcategory' ] )
    );
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
        sanitize_text_field( $_POST[ 'category' ] )
        //sanitize_text_field( $_POST[ 'subcategory' ] )
    );
    wp_set_object_terms( $postID, $new_terms, 'situacao_de_risco', false );


    $pod = pods( 'risco', $postID );
    $pod->save( 'endereco', sanitize_text_field( $data[ 'meta_input' ][ 'endereco' ] ) );
    $pod->save( 'descricao', sanitize_text_field( $data[ 'meta_input' ][ 'descricao' ] ) );

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
//add_action('wp_ajax_nopriv_form_single_risco_edit', 'form_single_risco_edit');


function form_single_risco_delete_attachment() {

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
add_action('wp_ajax_form_single_risco_delete_attachment', 'form_single_risco_delete_attachment');
//add_action('wp_ajax_nopriv_form_single_risco_delete_attachment', 'form_single_risco_delete_attachment');



function upload_file_to_attachment_by_ID( $files = NULL, $postID = NULL, $attachment_id = NULL ) {

    $errors = [];
    $uploaded_files = [];

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

    return [
        'errors' => $errors,
        'uploaded_files' => $uploaded_files
    ];
}
