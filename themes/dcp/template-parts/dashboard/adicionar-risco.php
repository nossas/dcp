<?php



    $all_terms = get_terms([
        'taxonomy' => 'situacao_de_risco',
        'hide_empty' => false,
    ]);

?>
<div id="dashboardRiscoSingle" class="dashboard-content">

    <div class="dashboard-content-breadcrumb">
        <ol class="breadcrumb">
            <li>
                <a href="./?ver=riscos">Riscos</a>
                <iconify-icon icon="bi:chevron-right"></iconify-icon>
            </li>
            <li><a href="#/">Adicionar novo</a></li>
        </ol>
    </div>

    <header class="dashboard-content-header is-single-new">
        <h1>ADICIONAR NOVO RISCO</h1>
    </header>

    <div class="dashboard-content-single">
        <form id="riscoSingleForm" class="" method="post" enctype="multipart/form-data" action="javascript:void(0);" data-action="<?php bloginfo( 'url' );?>/wp-admin/admin-ajax.php">

            <div class="fields">
                <div class="input-wrap">
                    <label class="label">Localização</label>
                    <input class="input" type="text" name="endereco" placeholder="Digite o local ou endereço aqui" value="" required>
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
                    <select id="selectCategory" class="select" name="situacao_de_risco" required >
                        <option value="">SELECIONE UMA CATEGORIA</option>
                        <?php foreach ( $all_terms as $key => $term ) :
                            if( !$term->parent ) : ?>
                                <option value="<?=$term->slug?>"><?=$term->name?></option>
                            <?php endif; endforeach; ?>
                    </select>
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
                    <label class="label">Subcategoria</label>
                    <div class="input-chips">
                        <div class="chips-wrap"></div>
                        <div class="chips-checkbox">
                            <?php foreach ( risco_convert_terms( $all_terms ) as $term ) : ?>
                                <div class="is-<?=$term[ 'slug' ]?>">
                                    <h4><?=$term[ 'name' ]?></h4>
                                    <?php foreach ( $term[ 'children' ] as $subterm ) : ?>
                                        <label for="input_<?=$subterm[ 'slug' ]?>">
                                            <input id="input_<?=$subterm[ 'slug' ]?>" type="checkbox" value="<?=$subterm[ 'slug' ]?>" data-label="<?=$subterm[ 'name' ]?>" name="subcategories[]">
                                            <?=$subterm[ 'name' ]?>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <a class="button is-category" style=" font-size: 21px; top: 34px; ">
                        <iconify-icon icon="bi:list"></iconify-icon>
                    </a>
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
                    <textarea class="textarea" name="descricao" readonly required></textarea>
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
                <div id="mediaUpload" class="input-media">
                    <?php if( !wp_is_mobile() ) : ?>
                        <div class="input-media-uploader">
                            <h4>Mídias</h4>
                            <div class="input-media-uploader-files">
                                <a id="mediaUploadButtonCover" class="button is-primary is-small is-upload-media">
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
                                <a id="mediaUploadButtonCover" class="button is-primary is-small is-upload-media">
                                    <iconify-icon icon="bi:upload"></iconify-icon>
                                    <span>Adicionar fotos e vídeos</span>
                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div id="formSubmit" class="form-submit">
                <input type="hidden" name="action" value="form_single_risco_new">
                <input type="hidden" name="email" value="admin@admin.com">
                <?php if( !wp_is_mobile() ) : ?>
                    <a class="button is-archive">
                        <iconify-icon icon="bi:x-lg"></iconify-icon>
                        <span>Cancelar</span>
                    </a>
                    <a class="button is-new">
                        <iconify-icon icon="bi:check2"></iconify-icon>
                        <span>Enviar para aprovação</span>
                    </a>
                <?php else : ?>
                    <a class="button is-new">
                        <iconify-icon icon="bi:check2"></iconify-icon>
                        <span>Enviar para aprovação</span>
                    </a>
                    <a class="button is-archive">
                        <iconify-icon icon="bi:x-lg"></iconify-icon>
                        <span>Cancelar</span>
                    </a>
                <?php endif; ?>
            </div>
        </form>

        <?php echo get_template_part('template-parts/dashboard/ui/modal-confirm' ); ?>
    </div>


</div>

