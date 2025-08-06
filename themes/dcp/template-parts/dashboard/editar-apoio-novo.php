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
                    <textarea class="textarea" name="info_extra" required readonly><?=$pod->field( 'info_extra' )?></textarea>
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
                <div id="mediaUploadCover" class="input-media is-cover-view">
                    <div class="is-cover-image">
                        <h3>Foto de capa</h3>
                        <?php if( !empty( $attachments ) ) : ?>
                            <?php foreach ( $attachments as $image ) : ?>
                                <input type="hidden" name="attatchment_cover_id" value="<?=$image->ID?>">
                                <figure class="asset-item-preview">
                                    <img class="is-load-now" data-media-src="<?=$image->guid?>">
                                </figure>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <?php if( wp_is_mobile() ) : ?>
                        <div class="input-media-uploader is-mobile-only">
                            <div class="input-help">
                                <a href="#/" class="button" style="top: 0 !important;">
                                    <iconify-icon icon="bi:question"></iconify-icon>
                                </a>
                                <p>
                                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus ullamcorper.
                                </p>
                            </div>
                        </div>
                    <?php endif; ?>
                    <div class="input-media-uploader-progress">
                        <div class="progress is-empty">
                            <p class="is-empty-text">Funcionalidade de arrasta e solta ainda não disponível.</p>
                        </div>
                    </div>
                    <?php if( !wp_is_mobile() ) : ?>
                        <div class="input-media-uploader">
                            <div class="input-media-uploader-files">
                                <a id="mediaUploadButtonCover" class="button is-primary is-small is-upload-media">
                                    <iconify-icon icon="bi:upload"></iconify-icon>
                                    <span>Escolher arquivo</span>
                                </a>
                                <p class="is-name-file">...</p>
                            </div>
                            <div>
                                <?php if( !empty( $attachments ) ) : ?>
                                    <?php foreach ( $attachments as $image ) : ?>
                                        <a class="button is-delete" data-id="<?=$image->ID?>">
                                            <iconify-icon icon="bi:trash-fill"></iconify-icon>
                                            <span>Excluir</span>
                                        </a>
                                    <?php endforeach; ?>
                                <?php endif; ?>
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
                            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus ullamcorper.
                        </p>
                    </div>
                <?php endif; ?>
            </div>
            <div class="form-submit">
                <input type="hidden" name="action" value="form_single_apoio_edit">
                <input type="hidden" name="post_id" value="<?=$postSingle->ID?>">
                <input type="hidden" name="post_status" value="<?=$postSingle->post_status?>">
                <div>
                    <a class="button is-goback" href="<?=get_dashboard_url( 'apoio', [ 'tipo' => $get_terms[0]->slug ] )?>">
                        <iconify-icon icon="bi:chevron-left"></iconify-icon>
                        <span>Voltar</span>
                    </a>
                </div>
                <div>
                    <a class="button is-archive">
                        <iconify-icon icon="bi:x-lg"></iconify-icon>
                        <span>Arquivar</span>
                    </a>
                    <a class="button is-save">
                        <iconify-icon icon="bi:check2"></iconify-icon>
                        <span>Salvar alterações</span>
                    </a>
                </div>
            </div>
        </form>
        <?php endif; ?>
        <?php echo get_template_part('template-parts/dashboard/ui/modal-confirm' ); ?>
        <?php echo get_template_part('template-parts/dashboard/ui/modal-assetset-fullscreen' ); ?>
    </div>
</div>

