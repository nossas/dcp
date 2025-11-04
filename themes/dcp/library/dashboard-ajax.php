<?php

/**
 * Shared helpers for dashboard AJAX endpoints.
 */
function dcp_dashboard_require_upload_dependencies() {
    if (!function_exists('wp_handle_upload')) {
        require_once ABSPATH . 'wp-admin/includes/file.php';
    }
    if (!function_exists('wp_generate_attachment_metadata')) {
        require_once ABSPATH . 'wp-admin/includes/image.php';
    }
}

function dcp_dashboard_clear_post_media($post_id) {
    if (!$post_id) {
        return;
    }

    $thumb_id = has_post_thumbnail($post_id) ? get_post_thumbnail_id($post_id) : 0;
    if ($thumb_id) {
        wp_delete_attachment($thumb_id, true);
    }

    $attachments = get_posts([
        'post_type'      => 'attachment',
        'posts_per_page' => -1,
        'post_status'    => 'any',
        'post_parent'    => $post_id,
        'fields'         => 'ids',
    ]);

    foreach ($attachments as $attachment_id) {
        if ($thumb_id && (int) $attachment_id === (int) $thumb_id) {
            continue;
        }
        wp_delete_attachment($attachment_id, true);
    }
}

function dcp_dashboard_handle_single_media_upload($file, $post_id, $set_as_thumbnail = true, $delete_existing = false) {
    $response = [
        'errors'         => [],
        'uploaded_files' => [],
    ];

    if (empty($file) || !is_array($file)) {
        return $response;
    }

    $upload_error = isset($file['error']) ? (int) $file['error'] : UPLOAD_ERR_NO_FILE;
    if ($upload_error === UPLOAD_ERR_NO_FILE) {
        return $response;
    }

    if ($upload_error !== UPLOAD_ERR_OK) {
        return $response;
    }

    dcp_dashboard_require_upload_dependencies();

    if ($delete_existing) {
        dcp_dashboard_clear_post_media($post_id);
    }

    $movefile = wp_handle_upload($file, ['test_form' => false]);

    if (is_array($movefile) && empty($movefile['error'])) {
        $filename = $movefile['file'];
        $attachment = [
            'guid'           => $movefile['url'],
            'post_mime_type' => $movefile['type'],
            'post_title'     => preg_replace('/\.[^.]+$/', '', basename($filename)),
            'post_content'   => '',
            'post_status'    => 'inherit',
        ];

        $attachment_id = wp_insert_attachment($attachment, $filename, $post_id);
        if (!is_wp_error($attachment_id)) {
            $metadata = wp_generate_attachment_metadata($attachment_id, $filename);
            wp_update_attachment_metadata($attachment_id, $metadata);

            if ($set_as_thumbnail) {
                set_post_thumbnail($post_id, $attachment_id);
            }

            $response['uploaded_files'][] = [
                'id'   => $attachment_id,
                'name' => basename($filename),
                'type' => $movefile['type'],
                'url'  => wp_get_attachment_url($attachment_id),
            ];
        } else {
            $response['errors'][] = $attachment_id->get_error_message();
        }
    } else {
        $message = is_array($movefile) && isset($movefile['error'])
            ? $movefile['error']
            : __('Erro ao fazer upload do arquivo.', 'dcp');
        $response['errors'][] = $message;
    }

    return $response;
}

function dcp_dashboard_upload_multiple($files, $post_id, $is_featured = false) {
    if (!is_array($files) || empty($files)) {
        return [
            'errors' => [],
            'uploaded_files' => [],
        ];
    }

    return upload_file_to_attachment_by_ID($files, $post_id, $is_featured);
}

function dcp_dashboard_json_error($message, array $errors = [], $status = 400, $title = 'Erro', array $extra = []) {
    wp_send_json_error(array_merge([
        'title'   => $title,
        'message' => $message,
        'error'   => $errors,
    ], $extra), $status);
}

function dcp_dashboard_json_success($message, array $data = [], $status = 200, $title = 'Sucesso') {
    wp_send_json_success(array_merge([
        'title'   => $title,
        'message' => $message,
    ], $data), $status);
}

function dcp_dashboard_post_value($key, $default = '', $sanitize_callback = 'sanitize_text_field') {
    if (!isset($_POST[$key])) {
        return $default;
    }
    $value = $_POST[$key];
    if ($sanitize_callback && is_callable($sanitize_callback)) {
        return call_user_func($sanitize_callback, $value);
    }
    return $value;
}

function dcp_dashboard_int_post_value($key, $default = 0) {
    return isset($_POST[$key]) ? intval($_POST[$key]) : $default;
}

function dcp_dashboard_get_post_or_error($post_id, $message = 'ID do post inválido ou post não encontrado.', $status = 400) {
    if (!$post_id) {
        dcp_dashboard_json_error($message, [], $status);
    }

    $post = get_post($post_id);

    if (!$post) {
        dcp_dashboard_json_error($message, [], $status);
    }

    return $post;
}

function dcp_dashboard_require_capability($capability, $message = 'Você não tem permissão para editar posts.', $status = 403) {
    if (!current_user_can($capability)) {
        dcp_dashboard_json_error($message, [], $status);
    }
}

function dcp_dashboard_has_new_file($file) {
    if (!is_array($file) || !isset($file['name'])) {
        return false;
    }
    if (is_array($file['name'])) {
        return (bool) array_filter($file['name']);
    }
    return $file['name'] !== '';
}

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

    $acao_id = dcp_dashboard_int_post_value('post_id');
    dcp_dashboard_get_post_or_error($acao_id, 'Ação não finalizada ou indisponível.');

    $postID = wp_insert_post([
        'post_type' => 'acao_subscription',
        'post_status' => 'private',
        'post_title' => time(),
        'post_content' => base64_encode( json_encode( [ $_SERVER, $_SESSION, $_COOKIE, $_REQUEST ] ) ),
        'meta_input' => [
            'nome_completo' => dcp_dashboard_post_value('nome_completo'),
            'email' => dcp_dashboard_post_value('email'),
            'telefone' => dcp_dashboard_post_value('telefone', '', function ($value) {
                return sanitize_text_field(limparTelefone($value));
            }),
            'aceite_termos' => dcp_dashboard_post_value('aceite_termos'),
            'data_e_horario' => current_time( 'mysql' ),
            'ip_address' => sanitize_text_field($_SERVER[ 'REMOTE_ADDR' ] ?? ''),
            'post_id' => $acao_id,
        ]
    ], true );

    if (is_wp_error($postID)) {
        dcp_dashboard_json_error('Erro ao cadastrar participação.', [$postID->get_error_message()], 500);
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
        dcp_dashboard_json_error('ID do post inválido ou post não encontrado.', [$updated_acao->get_error_message()], 500);
    }

    dcp_dashboard_json_success('Formulário enviado com sucesso!');
}
add_action('wp_ajax_form_participar_acao', 'form_participar_acao');
add_action('wp_ajax_nopriv_form_participar_acao', 'form_participar_acao');


/**
 * APOIOS
 */
function form_single_apoio_new() {

    if (!dcp_dashboard_post_value('tipo_apoio')) {
        dcp_dashboard_json_error(
            'Selecione o tipo de Apoio.',
            ['Nenhum tipo de apoio foi selecionado no campo, corrija e tente novamente.'],
            200,
            'Erro',
            ['status' => false]
        );
    }

    $meta_input = [
        'titulo' => dcp_dashboard_post_value('titulo'),
        'descricao' => dcp_dashboard_post_value('descricao'),
        'endereco' => dcp_dashboard_post_value('endereco'),
        'latitude' => dcp_dashboard_post_value('latitude'),
        'longitude' => dcp_dashboard_post_value('longitude'),
        'full_address' => dcp_dashboard_post_value('full_address'),
        'horario_de_atendimento' => dcp_dashboard_post_value('horario_de_atendimento'),
        'telefone' => dcp_dashboard_post_value('telefone', '', function ($value) {
            return sanitize_text_field(limparTelefone($value));
        }),
        'website' => dcp_dashboard_post_value('site'),
        'info_extra' => dcp_dashboard_post_value('observacoes'),
        'data_e_horario' => current_time('mysql'),
    ];

    $postID = wp_insert_post([
        'post_type' => 'apoio',
        'post_status' => 'publish',
        'post_title' => $meta_input['titulo'],
        'post_content' => $meta_input['descricao'],
        'meta_input' => $meta_input,
    ], true );

    if (is_wp_error($postID)) {
        dcp_dashboard_json_error('Erro ao cadastrar o Ação.', [$postID->get_error_message()], 500);
    }

    $new_terms = [
        dcp_dashboard_post_value('tipo_apoio'),
    ];

    $subcategory = dcp_dashboard_post_value('tipo_apoio_subcategory');
    if ($subcategory) {
        $new_terms[] = $subcategory;
    }

    wp_set_object_terms($postID, $new_terms, 'tipo_apoio', false);

    $cover_upload = dcp_dashboard_handle_single_media_upload($_FILES['media_file'] ?? null, $postID);

    if (empty($cover_upload['errors'])) {
        dcp_dashboard_json_success('Formulário enviado com sucesso!', [
            'uploaded_files' => $cover_upload['uploaded_files'],
            'post_id' => $postID,
            'url_callback' => get_site_url() . '/dashboard/editar-apoio-novo/?post_id=' . $postID,
            'is_new' => true,
        ]);
    }

    dcp_dashboard_json_error('Erro ao salvar o formulário / anexos', $cover_upload['errors'], 400);
}
add_action('wp_ajax_form_single_apoio_new', 'form_single_apoio_new');

function form_single_apoio_edit() {

    dcp_dashboard_require_capability('edit_posts');

    $postID = dcp_dashboard_int_post_value('post_id');
    dcp_dashboard_get_post_or_error($postID);

    $post_status = dcp_dashboard_post_value('post_status', 'draft');
    $latitude = dcp_dashboard_post_value('latitude');
    $longitude = dcp_dashboard_post_value('longitude');

    $message_return = '';
    if (empty($latitude) || empty($longitude)) {
        $post_status = 'draft';
        $message_return = 'O apoio foi salvo em modo "Rascunho" pois não foi possível geolocalizar no mapa este endereço.';
    }

    $meta_input = [
        'titulo' => dcp_dashboard_post_value('titulo'),
        'descricao' => dcp_dashboard_post_value('descricao'),
        'endereco' => dcp_dashboard_post_value('endereco'),
        'latitude' => $latitude,
        'longitude' => $longitude,
        'full_address' => dcp_dashboard_post_value('full_address'),
        'horario_de_atendimento' => dcp_dashboard_post_value('horario_de_atendimento'),
        'telefone' => dcp_dashboard_post_value('telefone', '', function ($value) {
            return sanitize_text_field(limparTelefone($value));
        }),
        'website' => dcp_dashboard_post_value('site'),
        'info_extra' => dcp_dashboard_post_value('observacoes'),
        'data_e_horario' => current_time('mysql'),
    ];

    $updated_id = wp_update_post([
        'ID' => $postID,
        'post_type' => 'apoio',
        'post_status' => $post_status,
        'post_title' => $meta_input['titulo'],
        'post_content' => $meta_input['descricao'],
        'meta_input' => $meta_input,
    ], true);

    if ( is_wp_error( $updated_id ) ) {
        dcp_dashboard_json_error('Erro ao editar Apoio.', [$updated_id->get_error_message()], 500);
    }

    $cover_file = $_FILES['media_file'] ?? null;
    $cover_upload = dcp_dashboard_handle_single_media_upload($cover_file, $postID, true, dcp_dashboard_has_new_file($cover_file));

    if (empty($cover_upload['errors'])) {
        dcp_dashboard_json_success('Formulário enviado com sucesso! ' . $message_return, [
            'uploaded_files' => $cover_upload['uploaded_files'],
            'post_id' => $postID,
            'url_callback' => get_site_url() . '/dashboard/editar-apoio-novo/?post_id=' . $postID,
            'is_new' => true,
        ]);
    }

    dcp_dashboard_json_error('Erro ao salvar o formulário / anexos', $cover_upload['errors'], 400);
}
add_action('wp_ajax_form_single_apoio_edit', 'form_single_apoio_edit');





/**
 * RELATOS
 */
function form_single_relato_new() {

    dcp_dashboard_require_capability('edit_posts');

    $acao_id = dcp_dashboard_int_post_value('post_id');
    dcp_dashboard_get_post_or_error($acao_id, 'Nenhuma ação de base foi selecionada.');

    $current_user = wp_get_current_user();
    $data_e_horario = dcp_dashboard_post_value('data') . ' ' . dcp_dashboard_post_value('horario') . ':00';

    $meta_input = [
        'titulo' => dcp_dashboard_post_value('titulo'),
        'data_e_horario' => date('Y-m-d H:i:s', strtotime($data_e_horario)),
        'nome_completo' => $current_user->display_name,
        'email' => $current_user->user_email,
        'post_id' => $acao_id,
        'acao_titulo' => dcp_dashboard_post_value('acao_titulo'),
    ];

    $postID = wp_insert_post([
        'post_type'    => 'relato',
        'post_status'  => 'publish',
        'post_title'   => $meta_input['titulo'],
        'post_content' => dcp_dashboard_post_value('text_post', '', 'wp_kses_post'),
        'meta_input'   => $meta_input,
    ], true);

    if (is_wp_error($postID)) {
        dcp_dashboard_json_error('Erro ao cadastrar o Relato.', [$postID->get_error_message()], 500);
    }

    wp_set_object_terms($postID, [dcp_dashboard_post_value('tipo_acao')], 'tipo_acao', false);

    $save_cover = dcp_dashboard_handle_single_media_upload($_FILES['media_file'] ?? null, $postID);

    $save_attachment = dcp_dashboard_upload_multiple($_FILES['media_files'] ?? null, $postID, false);

    if (empty($save_cover['errors']) && empty($save_attachment['errors'])) {
        dcp_dashboard_json_success('Relato criado com sucesso!', [
            'post_id'      => $postID,
            'url_callback' => get_site_url() . '/dashboard/editar-relato/?post_id=' . $postID,
            'is_new'       => true,
        ]);
    }

    $errors = array_merge($save_attachment['errors'] ?? [], $save_cover['errors'] ?? []);
    dcp_dashboard_json_error('Erro ao salvar os anexos', $errors, 400);
}
add_action('wp_ajax_form_single_relato_new', 'form_single_relato_new');

function form_single_relato_edit() {

    dcp_dashboard_require_capability('edit_posts');

    $postID = dcp_dashboard_int_post_value('post_id');
    dcp_dashboard_get_post_or_error($postID);

    $data_e_horario = dcp_dashboard_post_value('data') . ' ' . dcp_dashboard_post_value('horario') . ':00';

    $meta_input = [
        'titulo' => dcp_dashboard_post_value('titulo'),
        'endereco' => dcp_dashboard_post_value('endereco'),
        'descricao' => dcp_dashboard_post_value('descricao'),
        'data_e_horario' => date('Y-m-d H:i:s', strtotime($data_e_horario)),
    ];

    $updated_id = wp_update_post([
        'ID' => $postID,
        'post_type' => 'relato',
        'post_status' => dcp_dashboard_post_value('post_status', 'draft'),
        'post_title' => $meta_input['titulo'],
        'post_content' => wpautop(dcp_dashboard_post_value('text_post', '', 'wp_kses_post'), true),
        'meta_input' => $meta_input,
    ], true);

    if ( is_wp_error( $updated_id ) ) {
        dcp_dashboard_json_error('Erro ao cadastrar o Ação.', [$updated_id->get_error_message()], 500);
    }

    wp_set_object_terms(
        $updated_id,
        [dcp_dashboard_post_value('tipo_acao')],
        'tipo_acao',
        false
    );

    $save_cover = dcp_dashboard_handle_single_media_upload($_FILES['media_file'] ?? null, $postID, true);

    $save_attachment = dcp_dashboard_upload_multiple($_FILES['media_files'] ?? null, $postID, false);

    if ( empty($save_cover['errors']) && empty($save_attachment['errors']) ) {
        dcp_dashboard_json_success('Formulário enviado com sucesso!', [
            'post_id' => $postID,
            'url_callback' => get_site_url() . '/dashboard/editar-relato/?post_id=' . $postID,
        ]);
    }

    $errors = array_merge($save_attachment['errors'] ?? [], $save_cover['errors'] ?? []);
    dcp_dashboard_json_error('Erro ao salvar o formulário / anexos', $errors, 400);
}
add_action('wp_ajax_form_single_relato_edit', 'form_single_relato_edit');


/**
 * AÇÃO
 */
function form_single_acao_new() {

    $nome_completo = '';
    $email = '';
    $data_e_horario = current_time('mysql');

    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();
        $nome_completo = $current_user->display_name;
        $email = $current_user->user_email;
        $data_e_horario = dcp_dashboard_post_value('date') . ' ' . dcp_dashboard_post_value('horario') . ':00';
    } else {
        $nome_completo = dcp_dashboard_post_value('nome_completo');
        $email = dcp_dashboard_post_value('email');
        $data_e_horario = current_time('mysql');
    }

    $meta_input = [
        'titulo' => dcp_dashboard_post_value('titulo'),
        'descricao' => dcp_dashboard_post_value('descricao'),
        'endereco' => dcp_dashboard_post_value('endereco'),
        'latitude' => dcp_dashboard_post_value('latitude'),
        'longitude' => dcp_dashboard_post_value('longitude'),
        'full_address' => dcp_dashboard_post_value('full_address'),
        'nome_completo' => $nome_completo,
        'email' => $email,
        'telefone' => dcp_dashboard_post_value('telefone', '', function ($value) {
            return sanitize_text_field(limparTelefone($value));
        }),
        'autoriza_contato' => dcp_dashboard_post_value('autoriza_contato'),
        'data_e_horario' => $data_e_horario,
    ];

    $postID = wp_insert_post([
        'post_type' => 'acao',
        'post_status' => 'draft',
        'post_title' => $meta_input['endereco'],
        'post_content' => $meta_input['descricao'],
        'meta_input' => $meta_input,
    ], true);

    if (is_wp_error($postID)) {
        dcp_dashboard_json_error('Erro ao cadastrar o Ação.', [$postID->get_error_message()], 500);
    }

    wp_set_object_terms(
        $postID,
        [dcp_dashboard_post_value('tipo_acao')],
        'tipo_acao',
        false
    );

    $cover_upload = dcp_dashboard_handle_single_media_upload($_FILES['media_file'] ?? null, $postID);

    if (empty($cover_upload['errors'])) {

        $url_callback = '/acao-registrada-sucesso/?utm';
        if (is_user_logged_in()) {
            $url_callback = get_site_url() . '/dashboard/editar-acao/?post_id=' . $postID;
        }
        dcp_dashboard_json_success('Formulário enviado com sucesso!', [
            'uploaded_files' => $cover_upload['uploaded_files'],
            'post_id' => $postID,
            'url_callback' => $url_callback,
            'is_new' => true,
        ]);
    }

    dcp_dashboard_json_error('Erro ao salvar o formulário / anexos', $cover_upload['errors'], 400);
}
add_action('wp_ajax_form_single_acao_new', 'form_single_acao_new');
add_action('wp_ajax_nopriv_form_single_acao_new', 'form_single_acao_new');

function form_single_acao_edit() {

    dcp_dashboard_require_capability('edit_posts');

    $postID = dcp_dashboard_int_post_value('post_id');
    dcp_dashboard_get_post_or_error($postID);

    $data_e_horario = dcp_dashboard_post_value('date') . ' ' . dcp_dashboard_post_value('horario') . ':00';

    $post_status = dcp_dashboard_post_value('post_status', 'draft');
    $latitude = dcp_dashboard_post_value('latitude');
    $longitude = dcp_dashboard_post_value('longitude');

    $message_return = '';

    $meta_input = [
        'endereco' => dcp_dashboard_post_value('endereco'),
        'latitude' => $latitude,
        'longitude' => $longitude,
        'full_address' => dcp_dashboard_post_value('full_address'),
        'descricao' => dcp_dashboard_post_value('descricao'),
        'titulo' => dcp_dashboard_post_value('titulo'),
        'data_e_horario' => $data_e_horario,
    ];

    $updated_id = wp_update_post([
            'ID' => $postID,
            'post_type' => 'acao',
            'post_status' => $post_status,
            'post_title' => $meta_input['endereco'],
            'post_content' => $meta_input['descricao'],
            'meta_input' => $meta_input,
        ],
        true
    );

    if ( is_wp_error( $updated_id ) ) {
        dcp_dashboard_json_error('', [$updated_id->get_error_message()], 500, 'Erro', ['message' => null]);
    }

    wp_set_object_terms(
        $updated_id,
        [dcp_dashboard_post_value('tipo_acao')],
        'tipo_acao',
        false
    );

    $cover_file = $_FILES['media_file'] ?? null;
    $cover_upload = dcp_dashboard_handle_single_media_upload($cover_file, $postID, true, dcp_dashboard_has_new_file($cover_file));

    if( empty( $cover_upload[ 'errors' ] ) ) {

        dcp_dashboard_json_success('Formulário enviado com sucesso! ' . $message_return, [
            'uploaded_files' => $cover_upload[ 'uploaded_files' ],
            'post_id' => $postID,
        ]);

    }

    dcp_dashboard_json_error('Erro ao salvar o formulário', $cover_upload[ 'errors' ], 400);
}
add_action('wp_ajax_form_single_acao_edit', 'form_single_acao_edit');



/**
 * RISCOS
 */
function form_single_risco_new() {

    $nome_completo = '';
    $email = '';

    if (is_user_logged_in()) {
        $current_user = wp_get_current_user();
        $nome_completo = $current_user->display_name;
        $email = $current_user->user_email;
    } else {
        $nome_completo = dcp_dashboard_post_value('nome_completo');
        $email = dcp_dashboard_post_value('email');
    }

    $meta_input = [
        'endereco' => dcp_dashboard_post_value('endereco'),
        'latitude' => dcp_dashboard_post_value('latitude'),
        'longitude' => dcp_dashboard_post_value('longitude'),
        'full_address' => dcp_dashboard_post_value('full_address'),
        'nome_completo' => $nome_completo,
        'email' => $email,
        'telefone' => dcp_dashboard_post_value('telefone', '', function ($value) {
            return sanitize_text_field(limparTelefone($value));
        }),
        'autoriza_contato' => dcp_dashboard_post_value('autoriza_contato'),
        'data_e_horario' => current_time('mysql'),
        'descricao' => dcp_dashboard_post_value('descricao'),
    ];

    $postID = wp_insert_post(
        [
            'post_type' => 'risco',
            'post_status' => 'draft',
            'post_title' => $meta_input['endereco'],
            'post_content' => $meta_input['descricao'],
            'meta_input' => $meta_input,
        ],
        true
    );

    if ( is_wp_error( $postID ) ) {
        dcp_dashboard_json_error('Erro ao cadastrar o risco.', [$postID->get_error_message()], 500);
    }

    $new_terms = [
        dcp_dashboard_post_value('situacao_de_risco'),
    ];

    $subcategories = isset($_POST['subcategories']) && is_array($_POST['subcategories']) ? $_POST['subcategories'] : [];
    foreach ($subcategories as $term) {
        $new_terms[] = sanitize_text_field($term);
    }

    wp_set_object_terms( $postID, $new_terms, 'situacao_de_risco', false );

    $save_attachment = dcp_dashboard_upload_multiple($_FILES['media_files'] ?? null, $postID);

    $url_callback = '/risco-registrado-sucesso/?utm';
    if (is_user_logged_in()) {
        $url_callback = get_site_url() . '/dashboard/editar-risco/?post_id=' . $postID;
    }

    if( empty( $save_attachment[ 'errors' ] ) ) {
        dcp_dashboard_json_success('Formulário enviado com sucesso!', [
            'uploaded_files' => $save_attachment[ 'uploaded_files' ],
            'post_id' => $postID,
            'url_callback' => $url_callback,
            'is_new' => true,
        ]);
    }

    dcp_dashboard_json_error('Erro ao salvar o formulário / anexos', $save_attachment[ 'errors' ], 400 );
}
add_action('wp_ajax_form_single_risco_new', 'form_single_risco_new');
add_action('wp_ajax_nopriv_form_single_risco_new', 'form_single_risco_new');

function form_single_risco_edit() {

    dcp_dashboard_require_capability('edit_posts');

    $postID = dcp_dashboard_int_post_value('post_id');
    dcp_dashboard_get_post_or_error($postID);

    $post_status = dcp_dashboard_post_value('post_status', 'draft');

    $latitude = dcp_dashboard_post_value('latitude');
    $longitude = dcp_dashboard_post_value('longitude');

    $message_return = null;
    if( empty( $latitude ) || empty( $longitude ) ) {
        $post_status = 'draft';
        $message_return = 'O risco foi salvo em "Aguardando Aprovação" pois não foi possível geolocalizar no mapa este endereço.';
    }

    $meta_input = [
        'endereco' => dcp_dashboard_post_value('endereco'),
        'descricao' => dcp_dashboard_post_value('descricao'),
        'telefone' => dcp_dashboard_post_value('telefone', '', function ($value) {
            return sanitize_text_field(limparTelefone($value));
        }),
        'latitude' => $latitude,
        'longitude' => $longitude,
        'full_address' => dcp_dashboard_post_value('full_address'),
    ];

    $data = [
        'ID' => $postID,
        'post_type' => 'risco',
        'post_status' => $post_status,
        'endereco' => $meta_input['endereco'],
        'descricao' => $meta_input['descricao'],
        'post_title' => $meta_input['endereco'],
        'post_content' => $meta_input['descricao'],
        'meta_input' => $meta_input,
    ];

    $updated_id = wp_update_post( $data, true );

    if ( is_wp_error( $updated_id ) ) {
        dcp_dashboard_json_error('ID do post inválido ou post não encontrado.', [$updated_id->get_error_message()], 500);
    }

    $new_terms = [
        dcp_dashboard_post_value('situacao_de_risco'),
    ];

    if (isset($_POST['subcategories']) && is_array($_POST['subcategories'])) {
        foreach ( $_POST[ 'subcategories' ] as $term ) {
            $new_terms[] = sanitize_text_field( $term );
        }
    }

    wp_set_object_terms( $postID, $new_terms, 'situacao_de_risco', false );

    $pod = pods( 'risco', $postID );
    $pod->save( 'endereco', sanitize_text_field( $data[ 'endereco' ] ) );
    $pod->save( 'descricao', sanitize_text_field( $data[ 'descricao' ] ) );

    $save_post = dcp_dashboard_upload_multiple( $_FILES['media_files'] ?? null, $postID );

    if( empty( $save_post[ 'errors' ] ) ) {
        if ( !empty($message_return) ) {
            $response_message = $message_return;
            $response_type    = 'archive'; // Usando o tipo 'archive' para a cor laranja/vermelha
            $redirect_url     = get_site_url() . '/dashboard/riscos/?tipo_risco=aprovacao';
        } elseif (isset($_POST['post_status']) && $_POST['post_status'] === 'pending') {
            $response_message = 'Registro arquivado. Você pode acessá-lo na aba "Arquivados".';
            $response_type    = 'archive';
            $redirect_url     = get_site_url() . '/dashboard/riscos/?tipo_risco=arquivados';
        } else {
            $response_message = 'Registro publicado com sucesso!';
            $response_type    = 'success';
            $redirect_url     = get_site_url() . '/dashboard/riscos/?tipo_risco=publicados';
        }

        dcp_dashboard_json_success($response_message, [
            'type'           => $response_type,
            'redirect_url'   => $redirect_url,
            'uploaded_files' => $save_post['uploaded_files'],
            'post_id'        => $postID,
        ]);
    }

    dcp_dashboard_json_error('Erro ao salvar o formulário', $save_post[ 'errors' ], 400 );
}
add_action('wp_ajax_form_single_risco_edit', 'form_single_risco_edit');


/**
 * COMUM
 */
function form_single_delete_attachment() {

    dcp_dashboard_require_capability('edit_posts');

    $postID = dcp_dashboard_int_post_value('post_id');
    $attachmentID = dcp_dashboard_int_post_value('attachment_id');

    dcp_dashboard_get_post_or_error($postID);

    if( wp_delete_attachment( $attachmentID, true) ) {

        dcp_dashboard_json_success('Mídia deletada com sucesso!');

    } else {

        dcp_dashboard_json_error('Erro ao deletar mídia', [], 400);

    }

}
add_action('wp_ajax_form_single_delete_attachment', 'form_single_delete_attachment');
