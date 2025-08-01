<?php

namespace hacklabr\dashboard;

    $get_acoes = get_acoes_by_status( 'private' );
    $post_id = get_query_var('post_id' );
    $get_acao = get_post( $post_id );
?>
<div id="dashboardAcaoSingle" class="dashboard-content">
    <div class="dashboard-content-breadcrumb">
        <ol class="breadcrumb">
            <li>
                <a href="<?=get_dashboard_url( 'acoes' )?>">Ações</a>
                <iconify-icon icon="bi:chevron-right"></iconify-icon>
            </li>
            <li><a href="">Criar página de relato</a></li>
        </ol>
    </div>
    <header class="dashboard-content-header is-single-new">
        <h1>Criar página de relato de ação</h1>
    </header>
    <div class="dashboard-content-single">
        <?php
        //TODO : REFACTORY P/ MELHORAR o $get_acao (quando vazio vem o post_page Dashboard)
        if( !empty( $get_acao ) ) :
            $pod = pods( 'acao', $get_acao->ID );
            $attachments = get_attached_media('', $get_acao->ID );
            $get_terms = get_the_terms( $get_acao->ID, 'tipo_acao' ); ?>
        <form id="acaoSingleForm" class="" method="post" enctype="multipart/form-data" action="javascript:void(0);" data-action="<?php bloginfo( 'url' );?>/wp-admin/admin-ajax.php">
            <div class="fields">
                <div class="input-wrap">
                    <label class="label">Selecione a ação realizada</label>
                    <select id="selectAcaoRealizada" class="select" name="acao_realizada" required >
                        <option value="">SELECIONE UMA AÇÃO</option>
                        <?php if( $get_acoes[ 'posts' ]->have_posts() ) :
                            while( $get_acoes[ 'posts' ]->have_posts() ) :
                                $get_acoes[ 'posts' ]->the_post();
                                $acao_term = get_the_terms( get_the_ID(), 'tipo_acao' );
                                $pod_acoes = pods( 'acao', get_the_ID() );
                                ?>
                                <option value="<?=get_the_ID()?>" <?=( $get_acao->ID == get_the_ID() ) ? 'selected' : '' ?> ><?=( $get_acao->ID == get_the_ID() ) ? '( #' . get_the_ID() . ' )' : '( ' . $acao_term[0]->name . ' )' ?> <?=$pod_acoes->field( 'titulo') ?></option>
                            <?php endwhile; endif; ?>
                    </select>
                    <?php if( !empty( $get_acao->post_type === 'acao' ) ) : ?>
                        <p class="input-links">
                            <a href="<?=get_dashboard_url( 'editar-acao' )?>/?post_id=<?=$get_acao->ID?>" target="_blank">
                                <iconify-icon icon="bi:box-arrow-up-right"></iconify-icon>
                                <span>Editar Ação</span>
                            </a>
                            <a href="<?=$get_acao->guid?>" target="_blank">
                                <iconify-icon icon="bi:box-arrow-up-right"></iconify-icon>
                                <span>Ver Ação</span>
                            </a>
                        </p>
                    <?php endif; ?>
                    <a class="button is-category">
                        <?php risco_badge_category( 'sem-categoria', 'SEM CATEGORIA ADICIONADA', '' ); ?>
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
                <div class="input-wrap">
                    <label class="label">Categoria</label>
                    <input type="hidden" name="tipo_acao" value="<?=( !empty( $get_terms[0]->slug ) ) ? $get_terms[0]->slug : ''?>">
                    <select id="selectCategory" class="select" disabled >
                        <?php if( !empty( $get_terms[0]->slug ) ) : ?>
                            <option value="<?=$get_terms[0]->slug?>" selected ><?=$get_terms[0]->name?></option>
                        <?php else : ?>
                            <option value="">SELECIONE UMA AÇÃO</option>
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
                <div class="input-wrap">
                    <label class="label">Endereço</label>
                    <input type="hidden" name="endereco" value="<?=$pod->field( 'endereco' )?>">
                    <input class="input" type="text" name="endereco_preview" placeholder="Selecione uma Ação" value="<?=(!empty($pod->field( 'endereco' ))) ? $pod->field( 'endereco' ) : '' ?>" disabled>
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
                        <input class="input" type="hidden" name="data" value="<?=date( 'Y-m-d', strtotime( $pod->field('data_e_horario' ) ) )?>">
                        <input class="input" type="text" value="<?=date( 'd/m/Y', strtotime( $pod->field('data_e_horario' ) ) )?>" required disabled>
                    </div>
                    <div class="input-wrap">
                        <label class="label">Horário</label>
                        <input class="input" type="hidden" name="horario" value="<?=date( 'H:i', strtotime( $pod->field( 'data_e_horario' ) ) )?>">
                        <input class="input" type="text" value="<?=date( 'H:i', strtotime( $pod->field( 'data_e_horario' ) ) )?>" required disabled>
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
                    <input type="hidden" name="acao_titulo" value="<?=$pod->field( 'titulo' )?>">
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
                    <?php if( !wp_is_mobile() ) : ?>
                        <div class="input-media-uploader">
                            <h4>Foto de capa</h4>
                            <div class="input-media-uploader-files">
                                <a id="mediaUploadButtonCover" class="button is-primary is-small is-upload-media">
                                    <iconify-icon icon="bi:upload"></iconify-icon>
                                    <span>Adicionar foto</span>
                                </a>
                            </div>
                        </div>
                    <?php else : ?>
                        <div class="input-media-uploader is-mobile-only">
                            <h4 style="margin-top: 15px">Foto (opcional)</h4>
                            <div class="input-help">
                                <a href="#/" class="button" style="top: 0 !important;">
                                    <iconify-icon icon="bi:question"></iconify-icon>
                                </a>
                                <p>
                                    Todos os campos devem ter pelo menos 5 caracteres.
                                </p>
                            </div>
                        </div>
                    <?php endif; ?>
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
                                        <input type="hidden" name="attatchment_cover_id" value="<?=$image->ID?>">
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
                    <?php if( wp_is_mobile() ) : ?>
                        <div class="input-media-uploader">
                            <div class="input-media-uploader-files">
                                <a id="mediaUploadButtonCover" class="button is-primary is-small is-upload-media">
                                    <iconify-icon icon="bi:upload"></iconify-icon>
                                    <span>Adicionar foto</span>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
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
                    <textarea class="textarea" name="text_post" required style=" min-height: 80vh; "></textarea>
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
                    <?php if( !wp_is_mobile() ) : ?>
                        <div class="input-media-uploader">
                            <h4>Mídias</h4>
                            <div class="input-media-uploader-files">
                                <a id="mediaUploadButton" class="button is-primary is-small is-upload-media is-multiple">
                                    <iconify-icon icon="bi:upload"></iconify-icon>
                                    <span>Adicionar fotos e vídeos</span>
                                </a>
                            </div>
                        </div>
                    <?php else : ?>
                        <div class="input-media-uploader is-mobile-only">
                            <h4 style="margin-top: 15px">Mídias</h4>
                            <div class="input-help">
                                <a href="#/" class="button" style="top: 0 !important;">
                                    <iconify-icon icon="bi:question"></iconify-icon>
                                </a>
                                <p>
                                    Todos os campos devem ter pelo menos 5 caracteres.
                                </p>
                            </div>
                        </div>
                    <?php endif; ?>
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
                    <?php if( wp_is_mobile() ) : ?>
                        <div class="input-media-uploader">
                            <div class="input-media-uploader-files">
                                <a id="mediaUploadButton" class="button is-primary is-small is-upload-media is-multiple">
                                    <iconify-icon icon="bi:upload"></iconify-icon>
                                    <span>Adicionar fotos e vídeos</span>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
                <?php if( !wp_is_mobile() ) : ?>
                    <div class="input-help">
                        <a href="#/" class="button">
                            <iconify-icon icon="bi:question"></iconify-icon>
                        </a>
                        <p>
                            Todos os campos devem ter pelo menos 5 caracteres.
                        </p>
                    </div>
                <?php endif; ?>
            </div>
            <div class="form-submit">
                <input type="hidden" name="action" value="form_single_relato_new">
                <input type="hidden" name="post_id" value="<?=$get_acao->ID?>">
                <a class="button is-goback">
                    <iconify-icon icon="bi:chevron-left"></iconify-icon>
                    <span>Voltar</span>
                </a>
                <a class="button is-new relato">
                    <iconify-icon icon="bi:check2"></iconify-icon>
                    <span>Criar Relato</span>
                </a>
            </div>
        </form>
        <?php else : ?>
            <div class="message-response" style="display: block">
                <span class="tabs__panel-message">Nenhuma ação encontrada.</span>
            </div>
        <?php
            endif;
            echo get_template_part('template-parts/dashboard/ui/modal-confirm' ); ?>
        <script src="<?=includes_url('js/tinymce/tinymce.min.js')?>"></script>
        <script>
            const _tiny_mce_content_css = '<?php echo includes_url("css/dashicons.css"); ?>,<?php echo includes_url("js/tinymce/skins/wordpress/wp-content.css"); ?>';
        </script>
    </div>
</div>

