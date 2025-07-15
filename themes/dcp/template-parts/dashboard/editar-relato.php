<?php

namespace hacklabr\dashboard;

    $post_id = get_query_var('post_id' );

    $postSingle = new \WP_Query([
        'p' => $post_id,
        'post_type' => 'relato'
    ]);


    if ( $postSingle->have_posts() ) :

        while ( $postSingle->have_posts()) :
            $postSingle->the_post();

            $pod = pods( 'relato', get_the_ID() );
            $attachments = get_attached_media('', get_the_ID() );
            $get_terms = get_the_terms( get_the_ID(), 'tipo_acao' );

            $get_acoes = get_acoes_by_status( 'private' );
            $get_acao = get_post( $post_id );
?>
<div id="dashboardAcaoSingle" class="dashboard-content">
    <div class="dashboard-content-breadcrumb">
        <ol class="breadcrumb">
            <li>
                <a href="">Ações</a>
                <iconify-icon icon="bi:chevron-right"></iconify-icon>
            </li>
            <li>
                <a href="">Adicionar</a>
                <iconify-icon icon="bi:chevron-right"></iconify-icon>
            </li>
            <li><a href="">Criar página relato</a></li>
        </ol>
    </div>
    <header class="dashboard-content-header">
        <h1>Editar Relato de ação</h1>
    </header>
    <div class="dashboard-content-single">
        <form id="acaoSingleForm" class="" method="post" enctype="multipart/form-data" action="javascript:void(0);" data-action="<?php bloginfo( 'url' );?>/wp-admin/admin-ajax.php">
            <div class="fields">
                <div class="input-wrap">
                    <label class="label">Ação realizada</label>
                    <input class="input" type="text" name="acao_titulo" value="<?=(!empty($pod->field( 'acao_titulo' ))) ? $pod->field( 'acao_titulo' ) : '' ?>" >
                </div>
                <div class="input-help">
                    <a href="#/" class="button">
                        <iconify-icon icon="bi:question"></iconify-icon>
                    </a>
                    <p>
                        Todos os campos devem ter pelo menos 5 caracteres.
                    </p>
                </div>
            </div>
            <div class="fields">
                <div class="input-wrap">
                    <label class="label">Categoria</label>
                    <select id="selectCategory" class="select" name="tipo_acao" required disabled >
                        <?php if( !empty( $get_terms[0]->slug ) ) : ?>
                            <option value="<?=$get_terms[0]->slug?>" selected ><?=$get_terms[0]->name?></option>
                        <?php else : ?>
                            <option value="">SELECIONE UMA CATEGORIA</option>
                        <?php endif; ?>
                    </select>
                    <a class="button is-category">
                        <?php
                            if( !empty( $get_terms ) && !is_wp_error( $get_terms ) ) {
                                risco_badge_category( $get_terms[0]->slug, $get_terms[0]->name, '' );
                            } else {
                                risco_badge_category( 'sem-categoria', 'SEM CATEGORIA ADICIONADA', '' );
                            }
                        ?>
                    </a>
                    <a class="button is-select-input">
                        <iconify-icon icon="bi:chevron-down"></iconify-icon>
                    </a>
                </div>
                <div class="input-help">
                    <a href="#/" class="button">
                        <iconify-icon icon="bi:question"></iconify-icon>
                    </a>
                    <p>
                        Todos os campos devem ter pelo menos 5 caracteres.
                    </p>
                </div>
            </div>
            <div class="fields">
                <div class="is-group">
                    <div class="input-wrap">
                        <label class="label">Data</label>
                        <input class="input" type="text" name="data" placeholder="Digite aqui" value="<?=(!empty($pod->field( 'data' ))) ? $pod->field( 'data' ) : '' ?>" required disabled>
                    </div>
                    <div class="input-wrap">
                        <label class="label">Horário</label>
                        <input class="input" type="text" name="horario" placeholder="Digite aqui" value="<?=(!empty($pod->field( 'horario' ))) ? $pod->field( 'horario' ) : '' ?>" required disabled>
                    </div>
                </div>
                <div class="input-help">
                    <a href="#/" class="button">
                        <iconify-icon icon="bi:question"></iconify-icon>
                    </a>
                    <p>
                        Todos os campos devem ter pelo menos 5 caracteres.
                    </p>
                </div>
            </div>
            <div class="fields">
                <div class="input-wrap">
                    <label class="label">Título</label>
                    <input class="input" type="text" name="titulo" placeholder="Digite aqui" value="<?=(!empty($pod->field( 'titulo' ))) ? $pod->field( 'titulo' ) : '' ?>" required>
                </div>
                <div class="input-help">
                    <a href="#/" class="button">
                        <iconify-icon icon="bi:question"></iconify-icon>
                    </a>
                    <p>
                        Todos os campos devem ter pelo menos 5 caracteres.
                    </p>
                </div>
            </div>
            <div class="fields">
                <div class="input-wrap">
                    <label class="label">Descrição</label>
                    <textarea class="textarea" name="descricao" readonly required><?=(!empty($pod->field( 'descricao' ))) ? $pod->field( 'descricao' ) : '' ?></textarea>
                    <a class="button is-edit-input">
                        <iconify-icon icon="bi:pencil-square"></iconify-icon>
                    </a>
                </div>
                <div class="input-help">
                    <a href="#/" class="button">
                        <iconify-icon icon="bi:question"></iconify-icon>
                    </a>
                    <p>
                        Todos os campos devem ter pelo menos 5 caracteres.
                    </p>
                </div>
            </div>
            <div class="fields is-media-attachments">
                <div id="mediaUploadCover" class="input-media">
                    <div class="input-media-uploader">
                        <h4>Foto de capa</h4>
                        <div class="input-media-uploader-files">
                            <a id="mediaUploadButtonCover" class="button is-primary is-small is-upload-media">
                                <iconify-icon icon="bi:upload"></iconify-icon>
                                <span>Adicionar foto</span>
                            </a>
                        </div>
                    </div>
                    <div class="input-media-uploader-progress">
                        <div class="progress is-empty">
                            <p class="is-empty-text">Funcionalidade de arrasta e solta ainda não disponível.</p>
                        </div>
                    </div>
                    <div class="input-media-preview">
                        <div class="input-media-preview-assets is-images">
                            <?php if( !empty( $attachments ) ) : ?>
                                <?php echo get_template_part('template-parts/dashboard/ui/skeleton' ); ?>
                                <div class="assets-list">
                                    <?php foreach ( $attachments as $image ) : ?>
                                        <figure class="asset-item-preview">
                                            <img class="is-load-now" data-media-src="<?=$image->guid?>">
                                            <div class="asset-item-preview-actions">
                                                <a class="button is-fullscreen" data-id="<?=$image->ID?>" data-href="<?=$image->guid?>">
                                                    <iconify-icon icon="bi:arrows-fullscreen"></iconify-icon>
                                                </a>
                                                <a class="button is-delete" data-id="<?=$image->ID?>">
                                                    <iconify-icon icon="bi:trash-fill"></iconify-icon>
                                                </a>
                                                <a class="button is-download" href="<?=$image->guid?>" target="_blank">
                                                    <iconify-icon icon="bi:download"></iconify-icon>
                                                </a>
                                                <a class="button is-show-hide">
                                                    <iconify-icon icon="bi:eye-slash-fill"></iconify-icon>
                                                </a>
                                            </div>
                                        </figure>
                                    <?php endforeach; ?>
                                </div>
                            <?php else : ?>
                                <div class="input-media-preview">
                                    <div class="input-media-preview-assets is-empty">
                                        <p class="is-empty-text">Nenhuma imagem ou vídeo adicionado ainda.</p>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="input-help">
                    <a href="#/" class="button">
                        <iconify-icon icon="bi:question"></iconify-icon>
                    </a>
                    <p>
                        Todos os campos devem ter pelo menos 5 caracteres.
                    </p>
                </div>
            </div>
            <div class="fields">
                <div class="input-wrap">
                    <label class="label">Texto</label>
                    <textarea id="textoAcaoTinyMCE" class="textarea" name="text_post" required style=" min-height: 80vh; "></textarea>
                </div>
                <div class="input-help">
                    <a href="#/" class="button">
                        <iconify-icon icon="bi:question"></iconify-icon>
                    </a>
                    <p>
                        Todos os campos devem ter pelo menos 5 caracteres.
                    </p>
                </div>
            </div>
            <div class="fields is-media-attachments">
                <div id="mediaUpload" class="input-media">
                    <div class="input-media-uploader">
                        <h3>Mídias</h3>
                        <div class="input-media-uploader-files">
                            <a id="mediaUploadButton" class="button is-primary is-small is-upload-media is-multiple">
                                <iconify-icon icon="bi:upload"></iconify-icon>
                                <span>Adicionar fotos e vídeos</span>
                            </a>
                        </div>
                    </div>
                    <div class="input-media-uploader-progress">
                        <div class="progress is-empty">
                            <p class="is-empty-text">Funcionalidade de arrasta e solta ainda não disponível.</p>
                        </div>
                    </div>
                    <div class="input-media-preview">
                        <div class="input-media-preview-assets is-empty">
                            <p class="is-empty-text">Nenhuma imagem ou vídeo adicionado ainda.</p>
                        </div>
                    </div>
                </div>
                <div class="input-help">
                    <a href="#/" class="button">
                        <iconify-icon icon="bi:question"></iconify-icon>
                    </a>
                    <p>
                        Todos os campos devem ter pelo menos 5 caracteres.
                    </p>
                </div>
            </div>
            <div class="form-submit">
                <input type="hidden" name="action" value="form_single_relato_edit">
                <input type="hidden" name="email" value="admin@admin.com">
                <a class="button is-goback">
                    <iconify-icon icon="bi:chevron-left"></iconify-icon>
                    <span>Voltar</span>
                </a>
                <a class="button is-new relato">
                    <iconify-icon icon="bi:check2"></iconify-icon>
                    <span>Publicar Alterações</span>
                </a>
            </div>
        </form>
        <?php echo get_template_part('template-parts/dashboard/ui/modal-confirm' ); ?>
        <script src="<?=includes_url('js/tinymce/tinymce.min.js')?>"></script>
        <script>
            const _tiny_mce_content_css = '<?php echo includes_url("css/dashicons.css"); ?>,<?php echo includes_url("js/tinymce/skins/wordpress/wp-content.css"); ?>';
        </script>
    </div>
</div>
        <?php endwhile; ?>
    <?php endif; ?>

