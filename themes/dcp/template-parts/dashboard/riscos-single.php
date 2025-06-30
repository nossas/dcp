<?php

    //TODO: REFACTORY P/ METHOD/FUNCTIONS
    $risco_id = get_query_var('risco_id' );

    $riscoSingle = new WP_Query([
        'p' => $risco_id,
        'post_type' => 'risco'
    ]);

    if ( $riscoSingle->have_posts() ) :

        while ( $riscoSingle->have_posts()) :
            $riscoSingle->the_post();

            $pod = pods( 'risco', get_the_ID());
            $attachments = get_attached_media('', get_the_ID() );

            //TODO: REFACTORY P/ MELHOR MANEIRA
            $videos = [];
            $images = [];

            foreach ( get_attached_media('', get_the_ID() ) as $key => $value ) {

                if( $value->post_mime_type == 'image/jpeg' || $value->post_mime_type == 'image/png' ) {
                    $images[] = $value;
                }

                if( $value->post_mime_type == 'video/mp4' ) {
                    $videos[] = $value;
                }

            }

            $get_terms = get_the_terms( get_the_ID(), 'situacao_de_risco' );
            $all_terms = get_terms([
                'taxonomy' => 'situacao_de_risco',
                'hide_empty' => false,
            ]); ?>

        <div id="dashboardRiscoSingle" class="dashboard-content">
            <div class="dashboard-content-breadcrumb">
                <ol class="breadcrumb">
                    <li>
                        <a href="./?ver=riscos">Riscos</a>
                        <iconify-icon icon="bi:chevron-right"></iconify-icon>
                    </li>
                    <li>
                        <a href="#/">Aguardando aprovação</a>
                        <iconify-icon icon="bi:chevron-right"></iconify-icon>
                    </li>
                    <li><a href="#/">Avaliar risco</a></li>
                </ol>
            </div>
            <header class="dashboard-content-header">
                <h2>Confira se está tudo correto:</h2>
                <?php
                    //TODO: REFACTORY P/ COMPONENT
                    $post_status = get_post_status();
                    switch ( $post_status ) {
                        case 'publish':
                            $class = 'is-publish';
                            $text = 'Risco Publicado';
                            break;

                        case 'draft':
                            $class = 'is-draft';
                            $text = 'Risco ainda não publicado';
                            break;

                        case 'pending':
                            $class = 'is-pending';
                            $text = 'Risco Arquivado';
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
                <form id="riscoSingleForm" class="" method="post" enctype="multipart/form-data" action="javascript:void(0);" data-action="<?php bloginfo( 'url' );?>/wp-admin/admin-ajax.php">
                    <div class="fields">
                        <div class="input-wrap">
                            <label class="label">Localização</label>
                            <input class="input" type="text" name="endereco" placeholder="Digite o local ou endereço aqui" value="<?=$pod->field('endereco')?>" readonly required>
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
                            <label class="label">Categoria</label>
                            <select id="selectCategory" class="select" name="category" required >
                                <option value="">SELECIONE UMA CATEGORIA</option>
                                <?php foreach ( $all_terms as $key => $term ) :
                                    if( !$term->parent ) : ?>
                                    <option value="<?=$term->slug?>" <?=( $term->slug == $get_terms[0]->slug ) ? 'selected' : '' ?> ><?=$term->name?></option>
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
                            <label class="label">Subcategoria</label>
                            <div class="input-chips">
                                <div class="chips-wrap">
                                    <?php if( !empty( $get_terms ) ) : foreach ( $get_terms as $key => $term ) : if( $term->parent ) : ?>
                                        <span id="chips_<?=$term->slug?>" class="chips" data-id="<?=$term->term_id?>" data-name="<?=$term->name?>" data-slug="<?=$term->slug?>">
                                            <iconify-icon icon="bi:check2"></iconify-icon>
                                            <?=$term->name?>
                                        </span>
                                    <?php endif; endforeach; endif; ?>
                                </div>
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
                            <div id="modalChips" class="modal-chips">
                                <div class="modal-confirm-content">
                                    <header class="is-title">
                                        <h3>{TUITULO}</h3>
                                        <button class="button is-close">
                                            <iconify-icon icon="bi:x-lg"></iconify-icon>
                                        </button>
                                    </header>

                                    <article class="is-body">
                                        <p>{DESCRIÇÃO}</p>
                                    </article>
                                    <div class="is-error"></div>

                                    <div class="is-actions">
                                        <button class="button is-cancel">{CANCEL}</button>
                                        <button class="button is-custom">{CUSTOM}</button>
                                        <button class="button is-confirm">
                                            <i><iconify-icon icon="bi:check"></iconify-icon></i>
                                            <span>{MODAL}</span>
                                        </button>
                                    </div>
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
                    <div class="fields is-media-attachments">
                        <div id="mediaUpload" class="input-media">
                            <div class="input-media-uploader">
                                <h3>Mídias</h3>
                                <div class="input-media-uploader-files">
                                    <a id="mediaUploadButton" class="button is-primary is-small is-upload-media">
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
                                <?php if( !empty( $videos ) ) : ?>
                                <div class="input-media-preview-assets is-video">
                                    <h4>Vídeos</h4>
                                    <?php echo get_template_part('template-parts/dashboard/ui/skeleton' ); ?>
                                    <div class="assets-list">
                                        <?php foreach ( $videos as $video) : ?>
                                            <figure class="asset-item-preview">
                                                <video class="" poster="" playsinline loop muted>
                                                    <source class="is-load-now" data-media-src="<?=$video->guid?>" type="video/mp4">
                                                </video>
                                                <a class="button is-play">
                                                    <iconify-icon icon="bi:play-fill"></iconify-icon>
                                                </a>

                                                <div class="asset-item-preview-actions">
                                                    <a class="button is-fullscreen">
                                                        <iconify-icon icon="bi:arrows-fullscreen"></iconify-icon>
                                                    </a>
                                                    <a class="button is-delete" data-id="<?=$video->ID?>">
                                                        <iconify-icon icon="bi:trash-fill"></iconify-icon>
                                                    </a>
                                                    <a class="button is-download" href="<?=$video->guid?>" target="_blank">
                                                        <iconify-icon icon="bi:download"></iconify-icon>
                                                    </a>
                                                    <a class="button is-show-hide">
                                                        <iconify-icon icon="bi:eye-slash-fill"></iconify-icon>
                                                    </a>
                                                </div>

                                            </figure>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                                <?php endif;

                                if( !empty( $images ) ) : ?>
                                <div class="input-media-preview-assets is-images">
                                    <h4>Fotos</h4>
                                    <?php echo get_template_part('template-parts/dashboard/ui/skeleton' ); ?>
                                    <div class="assets-list">
                                        <?php foreach ( $images as $image ) : ?>
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
                                </div>
                                <?php endif; ?>

                                <?php if( empty( $videos ) && empty( $images ) ) : ?>
                                <div class="input-media-preview-assets is-empty">
                                    <p class="is-empty-text">Nenhuma imagem ou vídeo adicionado ainda.</p>
                                </div>
                                <?php endif; ?>
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
                    <div id="formSubmit" class="form-submit">

                        <input type="hidden" name="action" value="form_single_risco_edit">
                        <input type="hidden" name="post_id" value="<?=get_the_ID()?>">
                        <input type="hidden" name="post_status" value="<?=$post_status?>">
                        <input type="hidden" name="post_status_current" value="<?=$post_status?>">
                        <input type="hidden" name="post_status_new" value="">

                        <a class="button is-archive">
                            <iconify-icon icon="bi:x-lg"></iconify-icon>
                            <span>Arquivar</span>
                        </a>

                        <?php

                            //TODO: REFACTORY P/ MELHOR LOGICA
                            switch ( $post_status ) {

                                case 'draft':

                                    ?>
                                    <a class="button is-publish">
                                        <iconify-icon icon="bi:check2"></iconify-icon>
                                        <span>Publicar</span>
                                    </a>

                                    <?php

                                    break;
                                case 'publish':
                                case 'pending':

                                    ?>

                                    <a class="button is-save">
                                        <iconify-icon icon="bi:check2"></iconify-icon>
                                        <span>Publicar Alterações</span>
                                    </a>

                                    <?php

                                    break;
                            }

                        ?>
                    </div>
                </form>
                <?php echo get_template_part('template-parts/dashboard/ui/modal-confirm' ); ?>
                <?php echo get_template_part('template-parts/dashboard/ui/modal-assetset-fullscreen' ); ?>

            </div>
        </div>
    <?php endwhile; ?>
<?php endif; ?>

