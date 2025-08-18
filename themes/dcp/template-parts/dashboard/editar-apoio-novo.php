<?php

namespace hacklabr\dashboard;

    $post_id = get_query_var('post_id' );
    $postSingle = get_post( $post_id );
    $pod = pods( $postSingle->post_type, $postSingle->ID );
    $get_terms = get_the_terms( $postSingle->ID, 'tipo_apoio' );
    $attachments = get_attached_media('', $postSingle->ID );

//     echo '<pre>';
//     print_r( $attachments );
//     echo '</pre>';

    $attachment_cover_id = get_post_thumbnail_id($postSingle->ID);
    $get_attachment = get_the_post_thumbnail_url($postSingle->ID, 'large');

?>
<script type="application/javascript"> const _current_apoio_edit = '<?=$get_terms[0]->slug?>'; </script>
<div id="dashboardApoioSingle" class="dashboard-content">
    <div class="dashboard-content-breadcrumb">
        <ol class="breadcrumb">
            <li>
                <a href="<?=get_dashboard_url( 'apoio' )?>">Apoio</a>
                <iconify-icon icon="bi:chevron-right"></iconify-icon>
            </li>
            <li>
                <a href=""><?=$get_terms[0]->name?></a>
                <iconify-icon icon="bi:chevron-right"></iconify-icon>
            </li>
            <li><a href="#/">Editar <?=strtolower( $get_terms[0]->name )?></a></li>
        </ol>
    </div>
    <header class="dashboard-content-header is-single-new">
        <h1><?=$get_terms[0]->name?></h1>
        <?php if( $postSingle->post_status === 'draft' ) : ?>
            <h3 style="font-size: 12px; font-weight: 700; opacity: 0.5; text-align: center; ">MODO RASCUNHO</h3>
        <?php endif; ?>
    </header>
    <div class="dashboard-content-single">
        <?php if( !empty( $postSingle ) ) : ?>
        <form id="apoioSingleForm" class="" method="post" enctype="multipart/form-data" action="javascript:void(0);" data-action="<?php bloginfo( 'url' );?>/wp-admin/admin-ajax.php">
            <div class="fields is-nome">
                <div class="input-wrap">
                    <label class="label">Nome</label>
                    <input class="input" type="text" name="titulo" placeholder="Digite aqui" value="<?=$postSingle->post_title?>" readonly required>
                    <a class="button is-edit-input">
                        <iconify-icon icon="bi:pencil-square"></iconify-icon>
                    </a>
                </div>
                <div class="input-help">
                    <a href="#/" class="button">
                        <iconify-icon icon="bi:question"></iconify-icon>
                    </a>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus ullamcorper.
                    </p>
                </div>
            </div>
            <div class="fields is-descricao">
                <div class="input-wrap">
                    <label class="label">Descrição</label>
                    <textarea class="textarea" name="descricao" required readonly><?=$postSingle->post_content?></textarea>
                    <a class="button is-edit-input">
                        <iconify-icon icon="bi:pencil-square"></iconify-icon>
                    </a>
                </div>
                <div class="input-help">
                    <a href="#/" class="button">
                        <iconify-icon icon="bi:question"></iconify-icon>
                    </a>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus ullamcorper.
                    </p>
                </div>
            </div>
            <div class="fields is-funcionamento">
                <div class="input-wrap">
                    <label class="label">Horário de atendimento</label>
                    <input class="input" type="text" name="horario_de_atendimento" placeholder="Digite aqui" value="<?=$pod->field( 'horario_de_atendimento' )?>" readonly required>
                    <a class="button is-edit-input">
                        <iconify-icon icon="bi:pencil-square"></iconify-icon>
                    </a>
                </div>
                <!--
                    <div class="is-group">
                        <div class="input-wrap">
                            <label class="label">Dias de funcionamento</label>
                            <input class="input" type="text" name="dias_funcionamento" placeholder="Digite aqui" value="" disabled required>
                        </div>
                        <div class="input-wrap">
                            <label class="label">Horário de funcionamento</label>
                            <input class="input" type="time" name="horario" placeholder="Digite aqui" value="" disabled required>
                        </div>
                    </div>
                -->
                <div class="input-help">
                    <a href="#/" class="button">
                        <iconify-icon icon="bi:question"></iconify-icon>
                    </a>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus ullamcorper.
                    </p>
                </div>
            </div>
            <div class="fields is-endereco">
                <div class="input-wrap">
                    <label class="label">Endereço</label>
                    <input class="input" type="text" name="endereco" placeholder="Digite o local ou endereço aqui" value="<?=$pod->field( 'endereco' )?>" readonly required>
                    <input type="hidden" name="full_address" value="<?=$pod->field( 'full_address' )?>">
                    <input type="hidden" name="latitude" value="<?=$pod->field( 'latitude' )?>">
                    <input type="hidden" name="longitude" value="<?=$pod->field( 'longitude' )?>">
                    <a class="button is-edit-input">
                        <iconify-icon icon="bi:pencil-square"></iconify-icon>
                    </a>
                    <a class="button is-loading" style="display: none">
                        <img src="<?=get_template_directory_uri()?>/assets/images/loading.gif">
                    </a>
                    <a class="button is-success" style="display: none">
                        <iconify-icon icon="bi:check-circle"></iconify-icon>
                    </a>
                    <p class="is-error-geolocation" style="font-size: 12px; color: #c10202; display: none; padding-left: 10px; ">Não foi possível encontrar este endereço, aguarde atualizações do mapa.</p>
                </div>
                <div class="input-help">
                    <a href="#/" class="button">
                        <iconify-icon icon="bi:question"></iconify-icon>
                    </a>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus ullamcorper.
                    </p>
                </div>
            </div>
            <div class="fields is-website" style="display: none;">
                <div class="input-wrap">
                    <label class="label">Website</label>
                    <input class="input" type="text" name="website" placeholder="Digite o local ou endereço aqui" value="<?=$pod->field( 'website' )?>" readonly required>
                    <a class="button is-edit-input">
                        <iconify-icon icon="bi:pencil-square"></iconify-icon>
                    </a>
                </div>
                <div class="input-help">
                    <a href="#/" class="button">
                        <iconify-icon icon="bi:question"></iconify-icon>
                    </a>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus ullamcorper.
                    </p>
                </div>
            </div>
            <div class="fields is-info-extra" style="display: none;">
                <div class="input-wrap">
                    <label class="label">Informações extras</label>
                    <textarea class="textarea" name="info_extra" required readonly><?=wp_unslash($pod->field( 'info_extra' ))?></textarea>
                    <a class="button is-edit-input">
                        <iconify-icon icon="bi:pencil-square"></iconify-icon>
                    </a>
                </div>
                <div class="input-help">
                    <a href="#/" class="button">
                        <iconify-icon icon="bi:question"></iconify-icon>
                    </a>
                    <p>
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus ullamcorper.
                    </p>
                </div>
            </div>
            <div class="fields is-media-attachments" style="display: none;">
                <div class="input-media apoio-uploader">
                    <div id="apoio-uploader-container">
                        <div id="apoio-image-preview-wrapper" style="<?= empty($get_attachment) ? 'display: none;' : '' ?> margin-bottom: 15px;">
                            <p style="font-size: 14px; color: #555; margin-bottom: 5px;">Foto de capa</p>
                            <img id="apoio-image-preview" src="<?= esc_url($get_attachment) ?>" alt="Preview da imagem" style="max-width: 100%; height: auto; border-radius: 8px; border: 1px solid #ddd;">
                        </div>

                        <div class="apoio-input-wrap" style="display: flex; align-items: center; gap: 15px; padding: 10px;">
                            <input type="file" name="media_file" id="apoio-cover-input" accept="image/*" style="display: none;">
                            <label for="apoio-cover-input" class="apoio-cover-input button is-primary is-small" style="cursor: pointer; margin: 0;">
                                <iconify-icon icon="bi:upload"></iconify-icon>
                                <span><?= empty($get_attachment) ? 'Escolher arquivo' : 'Trocar arquivo' ?></span>
                            </label>
                            <span id="apoio-file-name"><?= empty($get_attachment) ? 'Nenhum arquivo selecionado' : basename($get_attachment) ?></span>
                            <button type="button" id="apoio-delete-button" class="button is-delete" style="<?= empty($get_attachment) ? 'display: none;' : 'display: inline-flex;' ?> margin-left: auto;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17" fill="none">
                                    <path d="M2.50195 1.73047C2.23674 1.73047 1.98238 1.83583 1.79485 2.02336C1.60731 2.2109 1.50195 2.46525 1.50195 2.73047V3.73047C1.50195 3.99569 1.60731 4.25004 1.79485 4.43758C1.98238 4.62511 2.23674 4.73047 2.50195 4.73047H3.00195V13.7305C3.00195 14.2609 3.21267 14.7696 3.58774 15.1447C3.96281 15.5198 4.47152 15.7305 5.00195 15.7305H11.002C11.5324 15.7305 12.0411 15.5198 12.4162 15.1447C12.7912 14.7696 13.002 14.2609 13.002 13.7305V4.73047H13.502C13.7672 4.73047 14.0215 4.62511 14.2091 4.43758C14.3966 4.25004 14.502 3.99569 14.502 3.73047V2.73047C14.502 2.46525 14.3966 2.2109 14.2091 2.02336C14.0215 1.83583 13.7672 1.73047 13.502 1.73047H10.002C10.002 1.46525 9.8966 1.2109 9.70906 1.02336C9.52152 0.835826 9.26717 0.730469 9.00195 0.730469H7.00195C6.73674 0.730469 6.48238 0.835826 6.29485 1.02336C6.10731 1.2109 6.00195 1.46525 6.00195 1.73047H2.50195ZM5.50195 5.73047C5.63456 5.73047 5.76174 5.78315 5.85551 5.87692C5.94927 5.97068 6.00195 6.09786 6.00195 6.23047V13.2305C6.00195 13.3631 5.94927 13.4903 5.85551 13.584C5.76174 13.6778 5.63456 13.7305 5.50195 13.7305C5.36934 13.7305 5.24217 13.6778 5.1484 13.584C5.05463 13.4903 5.00195 13.3631 5.00195 13.2305V6.23047C5.00195 6.09786 5.05463 5.97068 5.1484 5.87692C5.24217 5.78315 5.36934 5.73047 5.50195 5.73047ZM8.00195 5.73047C8.13456 5.73047 8.26174 5.78315 8.35551 5.87692C8.44928 5.97068 8.50195 6.09786 8.50195 6.23047V13.2305C8.50195 13.3631 8.44928 13.4903 8.35551 13.584C8.26174 13.6778 8.13456 13.7305 8.00195 13.7305C7.86934 13.7305 7.74217 13.6778 7.6484 13.584C7.55463 13.4903 7.50195 13.3631 7.50195 13.2305V6.23047C7.50195 6.09786 7.55463 5.97068 7.6484 5.87692C7.74217 5.78315 7.86934 5.73047 8.00195 5.73047ZM11.002 6.23047V13.2305C11.002 13.3631 10.9493 13.4903 10.8555 13.584C10.7617 13.6778 10.6346 13.7305 10.502 13.7305C10.3693 13.7305 10.2422 13.6778 10.1484 13.584C10.0546 13.4903 10.002 13.3631 10.002 13.2305V6.23047C10.002 6.09786 10.0546 5.97068 10.1484 5.87692C10.2422 5.78315 10.3693 5.73047 10.502 5.73047C10.6346 5.73047 10.7617 5.78315 10.8555 5.87692C10.9493 5.97068 11.002 6.09786 11.002 6.23047Z" fill="#F9F3EA"/>
                                </svg>
                                <span>Excluir</span>
                            </button>
                        </div>
                    </div>
                </div>
                <div class="input-help">
                    <a href="#/" class="button"><iconify-icon icon="bi:question"></iconify-icon></a>
                    <p>Adicione ou troque a imagem para este local ou iniciativa.</p>
                </div>
            </div>
            <div class="form-submit">
                <input type="hidden" name="action" value="form_single_apoio_edit">
                <input type="hidden" name="post_id" value="<?=$postSingle->ID?>">
                <input type="hidden" name="post_status" value="<?=$postSingle->post_status?>">

                <?php if( !wp_is_mobile() ) : ?>
                <div>
                    <a class="button is-goback" href="<?=get_dashboard_url( 'apoio', [ 'tipo' => $get_terms[0]->slug ] )?>">
                        <iconify-icon icon="bi:chevron-left"></iconify-icon>
                        <span>Voltar</span>
                    </a>
                </div>
                <?php endif; ?>

                <div>
                    <?php
                    //TODO: REFACTORY P/ UI
                    if( !wp_is_mobile() ) :
                        if( $postSingle->post_status === 'draft' ) : ?>
                            <a class="button is-archive">
                                <iconify-icon icon="bi:x-lg"></iconify-icon>
                                <span>Arquivar</span>
                            </a>
                        <?php endif; endif; ?>


                    <a class="button is-save">
                        <iconify-icon icon="bi:check2"></iconify-icon>
                        <span>Salvar alterações</span>
                    </a>
                </div>

                <?php
                //TODO: REFACTORY P/ UI
                if( !wp_is_mobile() ) :
                    if( $postSingle->post_status === 'draft' ) : ?>
                        <a class="button is-archive">
                            <iconify-icon icon="bi:x-lg"></iconify-icon>
                            <span>Arquivar</span>
                        </a>
                    <?php endif; endif;

                if( wp_is_mobile() ) : ?>
                    <div>
                        <a class="button is-archive">
                            <iconify-icon icon="bi:x-lg"></iconify-icon>
                            <span>Arquivar</span>
                        </a>
                        <a class="button is-goback" href="<?=get_dashboard_url( 'acoes' )?>/">
                            <iconify-icon icon="bi:chevron-left"></iconify-icon>
                            <span>Voltar</span>
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </form>
        <?php endif; ?>
        <?php echo get_template_part('template-parts/dashboard/ui/modal-confirm' ); ?>
        <?php echo get_template_part('template-parts/dashboard/ui/modal-assetset-fullscreen' ); ?>
    </div>
    <div id="dashboard-snackbar" class="dashboard-snackbar">
    </div>
</div>
