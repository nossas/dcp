<?php

namespace hacklabr\dashboard;

    $post_id = get_query_var('post_id' );

    $postSingle = new \WP_Query([
        'p' => $post_id,
        'post_type' => 'acao'
    ]);

    if ( $postSingle->have_posts() ) :

        while ( $postSingle->have_posts()) :
            $postSingle->the_post();

            $pod = pods( 'acao', get_the_ID());
            $attachments = get_attached_media('', get_the_ID() );
            $get_terms = get_the_terms( get_the_ID(), 'tipo_acao' );

            $all_terms = get_terms([
                'taxonomy' => 'tipo_acao',
                'hide_empty' => false,
            ]);

            ?>

<div id="dashboardAcaoSingle" class="dashboard-content">
    <div class="dashboard-content-breadcrumb">
        <ol class="breadcrumb">
            <li>
                <a href="">Ações</a>
                <iconify-icon icon="bi:chevron-right"></iconify-icon>
            </li>
            <li><a href="#/">Avaliar / Editar ação</a></li>
        </ol>
    </div>
    <header class="dashboard-content-header">
        <h2>Avaliar ação sugerida</h2>
        <?php
            //TODO: REFACTORY P/ COMPONENT
            $post_status = get_post_status();
            switch ( $post_status ) {
                case 'draft':
                    $class = 'is-draft';
                    $text = 'Ação Sugerida';
                    break;

                case 'publish':
                    $class = 'is-publish';
                    $text = 'Ação Agendada';
                    break;

                case 'private':
                    $class = 'is-scheduled';
                    $text = 'Ação Realizada';
                    break;

                case 'pending':
                    $class = 'is-pending';
                    $text = 'Ação Arquivada';
                    break;

                default:
                    $class = 'is-blocked';
                    $text = 'BLOQUEADO';
                    break;
            }
        ?>
        <a class="button is-status <?=$class?>">
            <span><?=$text?></span>
        </a>
    </header>
    <div class="dashboard-content-single">
        <form id="acaoSingleForm" class="" method="post" enctype="multipart/form-data" action="javascript:void(0);" data-action="<?php bloginfo( 'url' );?>/wp-admin/admin-ajax.php">
            <div class="fields">
                <div class="input-wrap">
                    <label class="label">Categoria</label>
                    <select id="selectCategory" class="select" name="tipo_acao" required >
                        <option value="">SELECIONE UMA CATEGORIA</option>
                        <?php foreach ( $all_terms as $key => $term ) :
                            if( !$term->parent ) : ?>
                                <option value="<?=$term->slug?>" <?=( $term->slug == $get_terms[0]->slug ) ? 'selected' : '' ?>><?=$term->name?></option>
                            <?php endif; endforeach; ?>
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
                    <label class="label">Título</label>
                    <input class="input" type="text" name="titulo" placeholder="Digite aqui" value="<?=$pod->field('titulo')?>" required readonly>
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
            <div class="fields">
                <div class="input-wrap">
                    <label class="label">Descrição</label>
                    <textarea class="textarea" name="descricao" readonly required><?=$pod->field('descricao')?></textarea>
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
            <div class="fields">
                <div class="is-group">
                    <div class="input-wrap">
                        <label class="label">Data</label>
                        <input class="input" type="date" name="date" value="<?=date( 'Y-m-d', strtotime( $pod->field('data_e_horario' ) ) )?>" required readonly>
                        <a class="button is-edit-input">
                            <iconify-icon icon="bi:pencil-square"></iconify-icon>
                        </a>
                    </div>
                    <div class="input-wrap">
                        <label class="label">Horário</label>
                        <input class="input" type="time" name="horario" value="<?=date( 'H:i', strtotime( $pod->field('data_e_horario' ) ) )?>" required readonly>
                        <a class="button is-edit-input">
                            <iconify-icon icon="bi:pencil-square"></iconify-icon>
                        </a>
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
                    <label class="label">Localização</label>
                    <input class="input" type="text" name="endereco" placeholder="Digite o local ou endereço aqui" value="<?=$pod->field('endereco')?>" required readonly>
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
                <?php if( !empty( $pod->field( 'total_participantes' ) ) ) : ?>
                    <a class="is-download button" href="<?=admin_url( 'admin-ajax.php?action=download_participantes_acao&post_id=' . get_the_ID() )?>" target="_blank">
                        <iconify-icon icon="bi:download"></iconify-icon>
                        Lista de participantes <span>(<?=$pod->field( 'total_participantes' )?>)</span>
                    </a>
                <?php endif; ?>
            </div>
            <div class="form-submit">
                <input type="hidden" name="action" value="form_single_acao_edit">
                <input type="hidden" name="post_id" value="<?=get_the_ID()?>">
                <input type="hidden" name="post_status" value="<?=$post_status?>">
                <div>
                    <a class="button is-goback" href="<?=get_dashboard_url( 'acoes' )?>/">
                        <iconify-icon icon="bi:chevron-left"></iconify-icon>
                        <span>Voltar</span>
                    </a>
                </div>
                <div>
                    <?php if( $post_status !== 'pending' ) : ?>
                        <a class="button is-archive">
                            <iconify-icon icon="bi:x-lg"></iconify-icon>
                            <span>Arquivar</span>
                        </a>
                    <?php endif;

                        //TODO: REFACTORY P/ MELHOR LOGICA
                        switch ( $post_status ) {

                            case 'draft':

                                ?>
                                <a class="button is-scheduled">
                                    <iconify-icon icon="bi:check2"></iconify-icon>
                                    <span>Agendar Ação</span>
                                </a>
                                <?php

                                break;
                            case 'publish':

                                ?>
                                <a class="button is-save">
                                    <iconify-icon icon="bi:check2"></iconify-icon>
                                    <span>Publicar Alterações</span>
                                </a>
                                <a class="button is-done">
                                    <iconify-icon icon="bi:check-square-fill"></iconify-icon>
                                    <span>Concluir Ação</span>
                                </a>
                                <?php

                                break;

                            case 'pending':

                                ?>
                                <a class="button is-scheduled">
                                    <iconify-icon icon="bi:chevron-left"></iconify-icon>
                                    <span>Agendar Novamente</span>
                                </a>
                                <a class="button is-save">
                                    <iconify-icon icon="bi:check2"></iconify-icon>
                                    <span>Salvar Alterações</span>
                                </a>
                                <?php

                                break;
                        }
                    ?>
                </div>
            </div>
        </form>

        <?php echo get_template_part('template-parts/dashboard/ui/modal-confirm' ); ?>
        <?php echo get_template_part('template-parts/dashboard/ui/modal-assetset-fullscreen' ); ?>
    </div>


</div>
        <?php endwhile; ?>
    <?php endif; ?>

