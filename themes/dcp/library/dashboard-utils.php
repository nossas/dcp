<?php

function risco_badge_category( $slug = 'default', $label = 'CATEGORIA GERAL', $class = 'post-card__taxonomia term-alagamento' ) {

    //TODO:  REFATORY P/ COMPONENTE (MELHOR LÓGICA)
    echo '<span class=" ' . $class . ' is-' . $slug . '">';

    switch ( $slug ) {

        case 'alagamento':
            echo '<iconify-icon icon="bi:water"></iconify-icon>';
            break;

        case 'lixo':
            echo '<iconify-icon icon="bi:trash3-fill"></iconify-icon>';
            break;

        case 'outros':
            echo '<iconify-icon icon="bi:moisture"></iconify-icon>';
            break;


        case 'reparos':
            echo '<iconify-icon icon="bi:hammer"></iconify-icon>';
            break;

        case 'limpeza':
            echo '<iconify-icon icon="bi:bucket-fill"></iconify-icon>';
            break;

        case 'cultural':
            echo '<iconify-icon icon="bi:mic-fill"></iconify-icon>';
            break;

        case 'mobilizacao':
            echo '<iconify-icon icon="bi:people-fill"></iconify-icon>';
            break;

        default:
            echo '<iconify-icon icon="bi:life-preserver"></iconify-icon>';
            break;
    }

    echo '<span>' . $label . '</span>' . '</span>';

}

function risco_convert_terms( $terms = [] )
{
    $all_terms_new = [];
    foreach ($terms as $term_key => $term) {

        if( !$term->parent ) {
            $all_terms_new[ $term->term_id ] = [
                'id' => $term->term_id,
                'name' => $term->name,
                'slug' => $term->slug,
                'children' => [],
            ];
        } else {
            $all_terms_new[ $term->parent ][ 'children' ][] = [
                'id' => $term->term_id,
                'name' => $term->name,
                'slug' => $term->slug,
            ];
        }

    }

    return $all_terms_new;
}

function upload_file_to_attachment_by_ID( $files = null, $postID = null, $is_featured = false ) {

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

            if( $is_featured ) {
                set_post_thumbnail( $postID, $attachment_id );
            }

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


function dashboard_excerpt( $descricao = null ) {
    if( !empty( $descricao ) ) {
        if ( strlen( $descricao ) <= 125 ) {
            echo $descricao;
        } else {
            echo substr( $descricao, 0, 125 ) . '<a class="read-more" href="#/">Ver mais</a>';
            echo '<span class="read-more-full">' . substr( $descricao, 125 ) . '</span>';
        }
    }
}
