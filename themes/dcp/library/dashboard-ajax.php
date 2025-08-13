<?php

/**
 * FRONTEND
 */
function download_participantes_acao() {

    if ( !current_user_can('manage_options' ) ) wp_die('Você não tem permissão para editar posts.' );

    $postID = isset($_GET['post_id']) ? intval($_GET['post_id']) : 0;
    $get_acao = get_post( $postID );

    if ( !$get_acao ) wp_die('ID do post inválido ou post não encontrado.' );

    $subscriptions = new WP_Query([
        'post_type' => 'acao_subscription',
        'post_status' => 'private',
        'meta_key' => 'post_id',
        'meta_value' => $postID, // ID do evento atual
        'posts_per_page' => -1,
    ]);

    header('Content-Type: text/csv; charset=utf-8');
    header('Content-Disposition: attachment; filename=' . date( 'Y-m-d_H.i' ) . '-participantes-' . $get_acao->post_name . '.csv' );

    $output = fopen('php://output', 'w' );
    fputcsv( $output, [ '#ID', 'Nome completo', 'Telefone', 'E-mail', 'Data e horário' ] );

    foreach ( $subscriptions->posts as $key => $item ) {
        $pod = pods( 'acao_subscription', $item->ID );
        fputcsv($output, [
            $item->ID,
            $pod->field( 'nome_completo' ),
            formatarTelefoneBR( $pod->field( 'telefone' ) ),
            $pod->field( 'email' ),
            $pod->field( 'data_e_horario' )
        ]);
    }
    fclose($output);
    exit;
}
add_action('wp_ajax_download_participantes_acao', 'download_participantes_acao');

function form_participar_acao() {

    $acao_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;
    $get_acao = get_post( $acao_id );

    if ( !$get_acao ) {
        wp_send_json_error([
            'title' => 'Erro',
            'message' => 'Ação não finalizada ou indisponível.',
            'error' => [],
        ], 400);
    }

    $postID = wp_insert_post([
        'post_type' => 'acao_subscription',
        'post_status' => 'private',
        'post_title' => time(),
        'post_content' => base64_encode( json_encode( [ $_SERVER, $_SESSION, $_COOKIE, $_REQUEST ] ) ),
        'meta_input' => [
            'nome_completo' => sanitize_text_field($_POST['nome_completo']),
            'email' => sanitize_text_field($_POST['email']),
            'telefone' => sanitize_text_field( limparTelefone( $_POST[ 'telefone' ] ) ),
            'aceite_termos' => sanitize_text_field($_POST['aceite_termos']),
            'data_e_horario' => date('Y-m-d H:i:s'),
            'ip_address' => $_SERVER[ 'REMOTE_ADDR' ],
            'post_id' => $acao_id,
        ]
    ], true );

    if (is_wp_error($postID)) {
        wp_send_json_error([
            'title' => 'Erro',
            'message' => 'Erro ao cadastrar participação.',
            'error' => $postID->get_error_message()
        ], 500);
    }

    $total_participantes = get_post_meta( $acao_id, 'total_participantes', true );
    $updated_acao = wp_update_post([
            'ID' => $acao_id,
            'post_type' => 'acao',
            'meta_input' => [
                'total_participantes' => ($total_participantes) ? $total_participantes + 1 : 1
            ],
        ],
        true
    );

    if ( is_wp_error( $updated_acao ) ) {
        wp_send_json_error([
            'title' => 'Erro',
            'message' => 'ID do post inválido ou post não encontrado.',
            'error' => $updated_acao->get_error_message(),
        ], 500);
    }

    wp_send_json_success([
        'title' => 'Sucesso',
        'message' => 'Formulário enviado com sucesso!'
    ]);
}
add_action('wp_ajax_form_participar_acao', 'form_participar_acao');
add_action('wp_ajax_nopriv_form_participar_acao', 'form_participar_acao');


/**
 * APOIOS
 */
function form_single_apoio_new() {

    if( empty( $_POST['tipo_apoio'] ) ) {
        wp_send_json_error([
            'status' => false,
            'title' => 'Erro',
            'message' => 'Selecione o tipo de Apoio.',
            'error' => [ 'Nenhum tipo de apoio foi selecionado no campo, corrija e tente novamente.' ],
        ], 200);
    }

    $postID = wp_insert_post([
        'post_type' => 'apoio',
        'post_status' => 'publish',
        'post_title' => sanitize_text_field($_POST['titulo']),
        'post_content' => sanitize_text_field($_POST['descricao']),
        'meta_input' => [
            'titulo' => sanitize_text_field($_POST['titulo']),
            'descricao' => sanitize_text_field($_POST['descricao']),
            'endereco' => sanitize_text_field($_POST['endereco']),
            'latitude' => sanitize_text_field( $_POST[ 'latitude' ] ),
            'longitude' => sanitize_text_field( $_POST[ 'longitude' ] ),
            'full_address' => sanitize_text_field( $_POST[ 'full_address' ] ),
            'horario_de_atendimento' => sanitize_text_field( $_POST[ 'horario_de_atendimento' ] ),
            'telefone' => sanitize_text_field( limparTelefone( $_POST[ 'telefone' ] ) ),
            'website' => sanitize_text_field($_POST['site']),
            'info_extra' => sanitize_text_field($_POST['observacoes']),
            'data_e_horario' => date( 'Y-m-d H:i:s' ),
        ]
    ], true );

    if (is_wp_error($postID)) {
        wp_send_json_error([
            'title' => 'Erro',
            'message' => 'Erro ao cadastrar o Ação.',
            'error' => $postID->get_error_message()
        ], 500);
    }

    $new_terms = array(
        sanitize_text_field( $_POST[ 'tipo_apoio' ] )
    );
    if( sanitize_text_field( $_POST[ 'tipo_apoio_subcategory' ] ) ) {
        $new_terms[] = sanitize_text_field( $_POST[ 'tipo_apoio_subcategory' ] );
    }

    wp_set_object_terms( $postID, $new_terms, 'tipo_apoio', false );

    $save_attachment = upload_file_to_attachment_by_ID( $_FILES[ 'media_file' ], $postID, true );

    if (empty($save_attachment['errors'])) {

        wp_send_json_success([
            'title' => 'Sucesso',
            'message' => 'Formulário enviado com sucesso!',
            'uploaded_files' => $save_attachment['uploaded_files'],
            'post_id' => $postID,
            'url_callback' => get_site_url() . '/dashboard/editar-apoio-novo/?post_id=' . $postID,
            'is_new' => true,
        ]);
    }

    wp_send_json_error([
        'title' => 'Erro',
        'message' => 'Erro ao salvar o formulário / anexos',
        'error' => $save_attachment['errors'],
    ], 400);
}
add_action('wp_ajax_form_single_apoio_new', 'form_single_apoio_new');

function form_single_apoio_edit() {

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

    //TODO: REFACTORY
    $post_status = sanitize_text_field($_POST['post_status'] ?? 'draft');

    $latitude = sanitize_text_field( $_POST[ 'latitude' ] );
    $longitude = sanitize_text_field( $_POST[ 'longitude' ] );

    $message_return = null;
    if( empty( $latitude ) || empty( $longitude ) ) {
        $post_status = 'draft';
        $message_return = 'O apoio foi salvo em modo "Rascunho" pois não foi possível geolocalizar no mapa este endereço.';
    }
    //TODO: REFACTORY

    $updated_id = wp_update_post([
        'ID' => $postID,
        'post_type' => 'apoio',
        'post_status' => $post_status,
        'post_title' => sanitize_text_field($_POST['titulo']),
        'post_content' => sanitize_text_field($_POST['descricao']),
        'meta_input' => [
            'titulo' => sanitize_text_field($_POST['titulo']),
            'descricao' => sanitize_text_field($_POST['descricao']),
            'endereco' => sanitize_text_field($_POST['endereco']),
            'latitude' => $latitude,
            'longitude' => $longitude,
            'full_address' => sanitize_text_field( $_POST[ 'full_address' ] ),
            'horario_de_atendimento' => sanitize_text_field( $_POST[ 'horario_de_atendimento' ] ),
            'telefone' => sanitize_text_field( limparTelefone( $_POST[ 'telefone' ] ) ),
            'website' => sanitize_text_field($_POST['site']),
            'info_extra' => sanitize_text_field($_POST['observacoes']),
            'data_e_horario' => date( 'Y-m-d H:i:s' ),
        ]
    ], true);

    if ( is_wp_error( $updated_id ) ) {
        wp_send_json_error([
            'title' => 'Erro',
            'message' => 'Erro ao editar Apoio.',
            'error' => $updated_id->get_error_message()
        ], 500);
    }

    //TODO: REFACTORY
    if( !empty( $_FILES['media_file'] ) ) {

        if ( has_post_thumbnail( $updated_id ) ) {
            $old_attachment_id = get_post_thumbnail_id( $updated_id );
            wp_delete_attachment( $old_attachment_id );
        }

        $get_attachments = get_posts([
            'post_type'      => 'attachment',
            'posts_per_page' => -1,
            'post_status'    => 'any',
            'post_parent'    => $updated_id,
        ]);

        foreach ( $get_attachments as $attachment ) {
            wp_delete_attachment( $attachment->ID );
        }
    }
    //TODO: REFACTORY

    $save_cover = upload_file_to_attachment_by_ID($_FILES['media_file'], $postID, true );
    if (empty($save_cover['errors'])) {
        wp_send_json_success([
            'title' => 'Sucesso',
            'message' => 'Formulário enviado com sucesso! ' . $message_return,
            'uploaded_files' => $save_cover,
            'post_id' => $postID,
            'url_callback' => get_site_url() . '/dashboard/editar-apoio-novo/?post_id=' . $postID,
            'is_new' => true,
        ]);
    }

    wp_send_json_error([
        'title' => 'Erro',
        'message' => 'Erro ao salvar o formulário / anexos',
        'error' => $save_cover['errors'],
    ], 400);
}
add_action('wp_ajax_form_single_apoio_edit', 'form_single_apoio_edit');





/**
 * RELATOS
 */
function form_single_relato_new() {

    if (!current_user_can('edit_posts')) {
        wp_send_json_error([
            'title' => 'Erro',
            'message' => 'Você não tem permissão para editar posts.',
            'error' => [],
        ], 403);
    }

    $acao_id = isset($_POST['post_id']) ? intval($_POST['post_id']) : 0;

    if( empty( $_POST['tipo_acao'] ) ) {
        wp_send_json_error([
            'title' => 'Erro',
            'message' => 'Selecione uma ação.',
            'error' => []
        ], 400);
    }

    $current_user = wp_get_current_user();
    $data_e_horario = sanitize_text_field($_POST['data']) . ' ' . sanitize_text_field($_POST['horario']) . ':00';

    $postID = wp_insert_post([
        'post_type' => 'relato',
        'post_status' => 'publish',
        'post_title' => sanitize_text_field($_POST['titulo']),
        'post_content' => sanitize_text_field($_POST['text_post']),
        'meta_input' => [
            'titulo' => sanitize_text_field($_POST['titulo']),
            'endereco' => sanitize_text_field($_POST['endereco']),
            'descricao' => sanitize_text_field($_POST['descricao']),
            'data_e_horario' => date( 'Y-m-d H:i:s', strtotime( $data_e_horario ) ),
            'nome_completo' => $current_user->display_name,
            'email' => $current_user->user_email,
            'post_id' => $acao_id,
            'acao_titulo' => sanitize_text_field($_POST['acao_titulo']),
        ]
    ], true);

    if (is_wp_error($postID)) {
        wp_send_json_error([
            'title' => 'Erro',
            'message' => 'Erro ao cadastrar o Ação.',
            'error' => $postID->get_error_message()
        ], 500);
    }

    wp_set_object_terms(
        $postID,
        [sanitize_text_field($_POST['tipo_acao'])],
        'tipo_acao',
        false
    );

    $save_cover = [];

    if( empty( $_FILES['media_file'] ) ) {
        $attachment_id = isset($_POST['attatchment_cover_id']) ? intval($_POST['attatchment_cover_id']) : 0;
        $save_cover = [
            'errors' => [],
            'uploaded_files' => [ [ 'id' => set_post_thumbnail( $postID, $attachment_id ) ] ],
        ];
    } else {
        $save_cover = upload_file_to_attachment_by_ID($_FILES['media_file'], $postID, true );
    }

    $save_attachment = upload_file_to_attachment_by_ID($_FILES['media_files'], $postID, false );

    if ( empty($save_cover['errors']) && empty($save_attachment['errors']) ) {
        wp_send_json_success([
            'title' => 'Sucesso',
            'message' => 'Formulário enviado com sucesso!',
            'post_id' => $postID,
            'url_callback' => get_site_url() . '/dashboard/editar-relato/?post_id=' . $postID,
            'is_new' => true,
        ]);
    }

    wp_send_json_error([
        'title' => 'Erro',
        'message' => 'Erro ao salvar o formulário / anexos',
        'error' => array_merge( $save_attachment, $save_cover ),
    ], 400);

}
add_action('wp_ajax_form_single_relato_new', 'form_single_relato_new');

function form_single_relato_edit() {

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

    $data_e_horario = sanitize_text_field($_POST['data']) . ' ' . sanitize_text_field($_POST['horario']) . ':00';

    $updated_id = wp_update_post([
        'ID' => $postID,
        'post_type' => 'relato',
        'post_status' => sanitize_text_field($_POST['post_status'] ?? 'draft'),
        'post_title' => sanitize_text_field($_POST['titulo']),
        'post_content' => wpautop( $_POST['text_post'], true ),
        'meta_input' => [
            'titulo' => sanitize_text_field($_POST['titulo']),
            'endereco' => sanitize_text_field($_POST['endereco']),
            'descricao' => sanitize_text_field($_POST['descricao']),
            'data_e_horario' => date( 'Y-m-d H:i:s', strtotime( $data_e_horario ) ),
        ]
    ], true);

    if ( is_wp_error( $updated_id ) ) {
        wp_send_json_error([
            'title' => 'Erro',
            'message' => 'Erro ao cadastrar o Ação.',
            'error' => $updated_id->get_error_message()
        ], 500);
    }

    wp_set_object_terms(
        $updated_id,
        [sanitize_text_field($_POST['tipo_acao'])],
        'tipo_acao',
        false
    );

    $save_cover = [];

    if( !empty( $_FILES['media_file'] ) ) {
        $save_cover = upload_file_to_attachment_by_ID($_FILES['media_file'], $postID, true );
    }

    $save_attachment = upload_file_to_attachment_by_ID($_FILES['media_files'], $postID, false );

    if ( empty($save_cover['errors']) && empty($save_attachment['errors']) ) {
        wp_send_json_success([
            'title' => 'Sucesso',
            'message' => 'Formulário enviado com sucesso!',
            'post_id' => $postID,
            'url_callback' => get_site_url() . '/dashboard/editar-relato/?post_id=' . $postID
        ]);
    }

    wp_send_json_error([
        'title' => 'Erro',
        'message' => 'Erro ao salvar o formulário / anexos',
        'error' => array_merge( $save_attachment, $save_cover ),
    ], 400);

}
add_action('wp_ajax_form_single_relato_edit', 'form_single_relato_edit');


/**
 * AÇÃO
 */
function form_single_acao_new() {

    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();
        $nome_completo = $current_user->display_name;
        $email = $current_user->user_email;
        $data_e_horario = sanitize_text_field($_POST['date']) . ' ' . sanitize_text_field($_POST['horario']) . ':00';
    } else {
        $nome_completo = sanitize_text_field($_POST['nome_completo']);
        $email = sanitize_text_field($_POST['email']);
        $data_e_horario = date('Y-m-d H:i:s');
    }

    $postID = wp_insert_post([
        'post_type' => 'acao',
        'post_status' => 'draft',
        'post_title' => sanitize_text_field($_POST['endereco']),
        'post_content' => sanitize_text_field($_POST['descricao']),
        'meta_input' => [
            'titulo' => sanitize_text_field($_POST['titulo']),
            'descricao' => sanitize_text_field($_POST['descricao']),
            'endereco' => sanitize_text_field($_POST['endereco']),
            'latitude' => sanitize_text_field( $_POST[ 'latitude' ] ),
            'longitude' => sanitize_text_field( $_POST[ 'longitude' ] ),
            'full_address' => sanitize_text_field( $_POST[ 'full_address' ] ),
            'nome_completo' => $nome_completo,
            'email' => $email,
            'telefone' => sanitize_text_field( limparTelefone( $_POST[ 'telefone' ] ) ),
            'autoriza_contato' => sanitize_text_field($_POST['autoriza_contato']),
            'data_e_horario' => $data_e_horario,
        ]
    ], true);

    if (is_wp_error($postID)) {
        wp_send_json_error([
            'title' => 'Erro',
            'message' => 'Erro ao cadastrar o Ação.',
            'error' => $postID->get_error_message()
        ], 500);
    }

    wp_set_object_terms(
        $postID,
        [sanitize_text_field($_POST['tipo_acao'])],
        'tipo_acao',
        false
    );

    $save_attachment = upload_file_to_attachment_by_ID($_FILES['media_file'], $postID, true );

    if (empty($save_attachment['errors'])) {

        $url_callback = '/acao-registrada-sucesso/?utm';
        if (is_user_logged_in()) {
            $url_callback = get_site_url() . '/dashboard/editar-acao/?post_id=' . $postID;
        }
        wp_send_json_success([
            'title' => 'Sucesso',
            'message' => 'Formulário enviado com sucesso!',
            'uploaded_files' => $save_attachment['uploaded_files'],
            'post_id' => $postID,
            'url_callback' => $url_callback,
            'is_new' => true,
        ]);
    }

    wp_send_json_error([
        'title' => 'Erro',
        'message' => 'Erro ao salvar o formulário / anexos',
        'error' => $save_attachment['errors'],
    ], 400);

}
add_action('wp_ajax_form_single_acao_new', 'form_single_acao_new');
add_action('wp_ajax_nopriv_form_single_acao_new', 'form_single_acao_new');

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

    $data_e_horario = sanitize_text_field($_POST['date']) . ' ' . sanitize_text_field($_POST['horario']) . ':00';

    //TODO: REFACTORY
    $post_status = sanitize_text_field($_POST['post_status'] ?? 'draft');
    $latitude = sanitize_text_field( $_POST[ 'latitude' ] );
    $longitude = sanitize_text_field( $_POST[ 'longitude' ] );

    $message_return = null;
//    if( empty( $latitude ) || empty( $longitude ) ) {
//        $post_status = 'draft';
//        $message_return = 'O risco foi salvo em "Aguardando Aprovação" pois não foi possível geolocalizar no mapa este endereço.';
//    }
    //TODO: REFACTORY

    $updated_id = wp_update_post([
            'ID' => $postID,
            'post_type' => 'acao',
            'post_status' => $post_status,
            'post_title' => sanitize_text_field( $_POST[ 'endereco' ] ),
            'post_content' => sanitize_text_field( $_POST[ 'descricao' ] ),
            'meta_input' => [
                'endereco' => sanitize_text_field( $_POST[ 'endereco' ] ),
                'latitude' => $latitude,
                'longitude' => $longitude,
                'full_address' => sanitize_text_field( $_POST[ 'full_address' ] ),
                'descricao' => sanitize_text_field( $_POST[ 'descricao' ] ),
                'titulo' => sanitize_text_field( $_POST[ 'titulo' ] ),
                'data_e_horario' => $data_e_horario
            ],
        ],
        true
    );

    if ( is_wp_error( $updated_id ) ) {

        wp_send_json_error([
            'title' => 'Erro',
            //'message' => 'ID do post inválido ou post não encontrado.',
            'message' => null,
            'error' => $updated_id->get_error_message(),
        ], 500);

    }

    wp_set_object_terms(
        $updated_id,
        array(
            sanitize_text_field( $_POST[ 'tipo_acao' ] )
        ),
        'tipo_acao',
        false
    );

    //TODO: REFACTORY
    if( !empty( $_FILES['media_file'] ) ) {

        if ( has_post_thumbnail( $updated_id ) ) {
            $old_attachment_id = get_post_thumbnail_id( $updated_id );
            wp_delete_attachment( $old_attachment_id );
        }

        $get_attachments = get_posts([
            'post_type'      => 'attachment',
            'posts_per_page' => -1,
            'post_status'    => 'any',
            'post_parent'    => $updated_id,
        ]);

        foreach ( $get_attachments as $attachment ) {
            wp_delete_attachment( $attachment->ID );
        }
    }
    //TODO: REFACTORY

    $save_post = upload_file_to_attachment_by_ID( $_FILES['media_file'], $postID, true );

    if( empty( $save_post[ 'errors' ] ) ) {

        wp_send_json_success([
            'title' => 'Sucesso',
            'message' => 'Formulário enviado com sucesso! ' . $message_return,
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



/**
 * RISCOS
 */
function form_single_risco_new() {

    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();
        $nome_completo = $current_user->display_name;
        $email = $current_user->user_email;
    } else {
        $nome_completo = sanitize_text_field($_POST['nome_completo']);
        $email = sanitize_text_field($_POST['email']);
    }

    $postID = wp_insert_post(
        [
            'post_type' => 'risco',
            'post_status' => 'draft',
            'post_title' => sanitize_text_field( $_POST[ 'endereco' ] ),
            'post_content' => sanitize_text_field( $_POST['descricao'] ),
            'meta_input' => [
                'endereco' => sanitize_text_field( $_POST[ 'endereco' ] ),
                'latitude' => sanitize_text_field( $_POST[ 'latitude' ] ),
                'longitude' => sanitize_text_field( $_POST[ 'longitude' ] ),
                'full_address' => sanitize_text_field( $_POST[ 'full_address' ] ),
                'nome_completo' => $nome_completo,
                'email' => $email,
                'telefone' => sanitize_text_field( limparTelefone( $_POST[ 'telefone' ] ) ),
                'autoriza_contato' => sanitize_text_field( $_POST[ 'autoriza_contato' ] ),
                'data_e_horario' => date('Y-m-d H:i:s'),
                'descricao' => sanitize_text_field( $_POST[ 'descricao' ] ),
            ]
        ]
        , true
    );

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

    $save_attachment = upload_file_to_attachment_by_ID( $_FILES['media_files'], $postID );

    $url_callback = '/risco-registrado-sucesso/?utm';
    if (is_user_logged_in()) {
        $url_callback = get_site_url() . '/dashboard/editar-risco/?post_id=' . $postID;
    }

    if( empty( $save_attachment[ 'errors' ] ) ) {
        wp_send_json_success([
            'title' => 'Sucesso',
            'message' => 'Formulário enviado com sucesso!',
            'uploaded_files' => $save_attachment[ 'uploaded_files' ],
            'post_id' => $postID,
            'url_callback' => $url_callback,
            'is_new' => true,
        ]);
    }

    wp_send_json_error([
        'title' => 'Erro',
        'message' => 'Erro ao salvar o formulário / anexos',
        'error' => $save_attachment[ 'errors' ],
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

    //TODO: REFACTORY
    $post_status = sanitize_text_field($_POST['post_status'] ?? 'draft');

    $latitude = sanitize_text_field( $_POST[ 'latitude' ] );
    $longitude = sanitize_text_field( $_POST[ 'longitude' ] );

    $message_return = null;
    if( empty( $latitude ) || empty( $longitude ) ) {
        $post_status = 'draft';
        $message_return = 'O risco foi salvo em "Aguardando Aprovação" pois não foi possível geolocalizar no mapa este endereço.';
    }
    //TODO: REFACTORY

    $data = [
        'ID' => $postID,
        'post_type' => 'risco',
        'post_status' => $post_status,
        'endereco' => sanitize_text_field( $_POST[ 'endereco' ] ),
        'descricao' => sanitize_text_field( $_POST[ 'descricao' ] ),
        'post_title' => sanitize_text_field( $_POST[ 'endereco' ] ),
        'post_content' => sanitize_text_field( $_POST[ 'descricao' ] ),
        'meta_input' => [
            'endereco' => sanitize_text_field( $_POST[ 'endereco' ] ),
            'descricao' => sanitize_text_field( $_POST[ 'descricao' ] ),
            'telefone' => sanitize_text_field( limparTelefone( $_POST[ 'telefone' ] ) ),
            'latitude' => $latitude,
            'longitude' => $longitude,
            'full_address' => sanitize_text_field( $_POST[ 'full_address' ] ),
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
        $response_message = 'Registro publicado com sucesso! ' . $message_return;
        $response_type = 'success';
        $redirect_url = get_site_url() . '/dashboard/riscos/?tipo_risco=publicados';

        if (isset($_POST['post_status']) && $_POST['post_status'] === 'pending') {
            $response_message = 'Registro arquivado. Você pode acessá-lo na aba "Arquivados".';
            $response_type = 'archive';
            $redirect_url = get_site_url() . '/dashboard/riscos/?tipo_risco=arquivados';
        }

        wp_send_json_success([
            'title'   => 'Sucesso',
            'message' => $response_message,
            'type'    => $response_type,
            'uploaded_files' => $save_post[ 'uploaded_files' ],
            'redirect_url' => $redirect_url,
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


/**
 * COMUM
 */
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
