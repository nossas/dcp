<?php

namespace hacklabr\dashboard;

    //TODO: REFACTORY P/ METHOD/FUNCTIONS
    $risco_id = get_query_var('post_id' );

    $riscoSingle = new \WP_Query([
        'p' => $risco_id,
        'post_type' => 'risco'
    ]);

    if ( $riscoSingle->have_posts() ) :

        while ( $riscoSingle->have_posts()) :
            $riscoSingle->the_post();

            $pod = pods( 'risco', get_the_ID());
            //$attachments = get_attached_media('', get_the_ID() );

            //TODO: REFACTORY P/ MELHOR MANEIRA
            $videos = [];
            $images = [];

            foreach ( get_attached_media('', get_the_ID() ) as $key => $value ) {
                if( in_array($value->post_mime_type, ['image/jpeg', 'image/png']) ) {
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
            ]);

            ?>

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
            <header class="dashboard-content-header is-single">
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
                            <input type="hidden" name="full_address" value="<?=$pod->field( 'full_address' )?>">
                            <input type="hidden" name="latitude" value="<?=$pod->field( 'latitude' )?>">
                            <input type="hidden" name="longitude" value="<?=$pod->field( 'longitude' )?>">
                            <a class="button is-edit-input">
                                <iconify-icon icon="bi:pencil-square"></iconify-icon>
                            </a>
                            <a class="button is-success" style="display: none">
                                <iconify-icon icon="bi:check-circle"></iconify-icon>
                            </a>
                            <p class="is-error-geolocation" style="font-size: 12px; color: #c10202; padding-left: 10px; display: none; ">Não foi possível encontrar este endereço, aguarde atualizações do mapa.</p>
                            <a class="button is-loading" style="display: none">
                                <img src="<?=get_template_directory_uri()?>/assets/images/loading.gif"  alt="<?= __('carregamento') ?>">
                            </a>
                        </div>
                        <div class="input-help">
                            <a href="#/" class="button">
                                <iconify-icon icon="bi:question"></iconify-icon>
                            </a>
                            <p>
                                <?= __('Verifique se o endereço está correto e completo.  Se não encontrar, use um ponto de referência próximo.') ?>
                            </p>
                        </div>
                    </div>

                    <div class="fields">
                        <div class="input-wrap">
                            <label class="label">Categoria</label>
                            <select id="selectCategory" class="select" name="situacao_de_risco" required >
                                <option class="placeholder-text" value="">Selecione uma categoria</option>
                                <?php foreach ( $all_terms as $key => $term ) :
                                    if( !$term->parent ) : ?>
                                    <option value="<?=$term->slug?>" <?=( !empty($get_terms) && $term->slug == $get_terms[0]->slug ) ? 'selected' : '' ?> ><?=$term->name?></option>
                                <?php endif; endforeach; ?>
                            </select>

                            <div class="category-icon-container" style="display: none;">
                                <a class="button is-category">
                                    <?php if( !empty($get_terms) ) risco_badge_category( $get_terms[0]->slug, $get_terms[0]->name, '' ); ?>
                                </a>
                            </div>

                            <a class="button is-select-input is-categoria-toggle">
                                <iconify-icon icon="bi:chevron-down"></iconify-icon>
                            </a>
                        </div>
                        <div class="input-help">
                            <a href="#/" class="button">
                                <iconify-icon icon="bi:question"></iconify-icon>
                            </a>
                            <p>
                                <?= __('Escolha o tipo de situação relatada.') ?>
                            </p>
                        </div>
                    </div>

                    <div class="fields">
                        <div class="input-wrap">
                            <label class="label">Subcategoria</label>

                            <div class="subcategory-list-icon">
                                <iconify-icon icon="bi:list"></iconify-icon>
                            </div>

                            <div id="subCategoryInput" class="input-chips">
                                <div class="chips-wrap"></div>

                                <span class="placeholder-text">Selecione uma subcategoria</span>

                                <div class="chips-checkbox">
                                    <?php
                                    $sub_terms_selecionados = [];
                                    if (!empty($get_terms)) {
                                        $sub_terms_selecionados = array_filter($get_terms, function($term) {
                                            return $term->parent != 0;
                                        });
                                    }
                                    $all_sub_terms = array_filter($all_terms, function($term) {
                                        return $term->parent != 0;
                                    });

                                    foreach ( $all_sub_terms as $subterm ) : ?>
                                        <label for="input_<?=$subterm->slug?>">
                                            <input
                                                id="input_<?=$subterm->slug?>"
                                                type="checkbox"
                                                value="<?=$subterm->slug?>"
                                                data-label="<?=$subterm->name?>"
                                                name="subcategories[]"
                                                <?= in_array($subterm->slug, array_column($sub_terms_selecionados, 'slug')) ? 'checked' : '' ?>>
                                            <?=$subterm->name?>
                                        </label>
                                    <?php endforeach; ?>
                                </div>
                            </div>

                            <a class="button is-select-input is-subcategoria-toggle">
                                <iconify-icon icon="bi:chevron-down"></iconify-icon>
                            </a>
                        </div>
                        <div class="input-help">
                            <a href="#/" class="button">
                                <iconify-icon icon="bi:question"></iconify-icon>
                            </a>
                            <p>
                                <?= __('Especifique o tipo de ocorrência dentro da categoria escolhida. Isso nos ajuda a entender o risco com mais precisão.') ?>
                            </p>
                        </div>
                    </div>

                    <div class="fields">
                        <div class="input-wrap">
                            <label class="label">Telefone</label>
                            <input class="input" type="text" name="telefone" placeholder="Digite o telefone aqui" value="<?=formatarTelefoneBR( $pod->field( 'telefone' ) )?>" readonly required>
                            <a class="button is-edit-input">
                                <iconify-icon icon="bi:pencil-square"></iconify-icon>
                            </a>
                        </div>
                        <div class="input-help">
                            <a href="#/" class="button">
                                <iconify-icon icon="bi:question"></iconify-icon>
                            </a>
                            <p>
                                <?= __('<b>Este número não será exibido publicamente</b>. Use apenas para contato interno ou emergências.') ?>
                            </p>
                        </div>
                    </div>
                    <div class="fields">
                        <div class="input-wrap">
                            <label class="label">Descrição</label>
                            <textarea class="textarea" name="descricao" readonly required><?=wp_unslash( $pod->field('descricao') )?></textarea>
                            <a class="button is-edit-input">
                                <iconify-icon icon="bi:pencil-square"></iconify-icon>
                            </a>
                        </div>
                        <div class="input-help">
                            <a href="#/" class="button">
                                <iconify-icon icon="bi:question"></iconify-icon>
                            </a>
                            <p>
                                <?= __('Confira se o texto não traz nomes, acusações ou informações delicadas. Antes de publicar, <b>remova nomes e expressões que possam causar exposição ou constrangimento.</b>') ?>
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
                                            <?= __('Confira se as partes sensíveis das imagens, como rostos, placas ou nomes, foram borradas corretamente. Se ainda houver algo visível, edite, exclua ou substitua o arquivo antes de publicar.') ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div id="mediaPreviewContainer" class="media-preview-container">
                                <h4 class="media-preview-title" style="display: none;">Mídias adicionadas</h4>
                                <div class="media-preview-list">
                                    </div>
                            </div>

                            <div id="file-input-storage" style="display: none;"></div>

                            <div class=input-media-divider></div>
                            <div class="input-media-preview">
                                <?php if( !empty( $videos ) ) : ?>
                                    <div class="input-media-preview-assets is-video">
                                        <h4>Vídeos</h4>
                                        <div class="assets-list">
                                            <?php foreach ( $videos as $video) : ?>
                                                <figure class="asset-item-preview">
                                                    <video class="" poster="" playsinline loop muted>
                                                        <source src="<?=$video->guid?>" type="video/mp4">
                                                    </video>
                                                    <a class="button is-play">
                                                        <iconify-icon icon="bi:play-fill"></iconify-icon>
                                                    </a>
                                                    <div class="asset-item-preview-actions">
                                                        <a class="button is-fullscreen"><iconify-icon icon="bi:arrows-fullscreen"></iconify-icon></a>
                                                        <a class="button is-delete" data-id="<?=$video->ID?>"><iconify-icon icon="bi:trash-fill"></iconify-icon></a>
                                                        <a class="button is-download" href="<?=$video->guid?>" target="_blank"><iconify-icon icon="bi:download"></iconify-icon></a>
                                                        <a class="button is-show-hide"><iconify-icon icon="bi:eye-slash-fill"></iconify-icon></a>
                                                    </div>
                                                </figure>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; if( !empty( $images ) ) : ?>
                                    <div class="input-media-preview-assets is-images">
                                        <h4>Fotos</h4>
                                        <div class="assets-list">
                                            <?php foreach ( $images as $image ) : ?>
                                                <figure class="asset-item-preview">
                                                    <img src="<?=$image->guid?>" alt="Foto do risco">
                                                    <div class="asset-item-preview-actions">
                                                        <a class="button is-fullscreen" data-id="<?=$image->ID?>" data-href="<?=$image->guid?>"><iconify-icon icon="bi:arrows-fullscreen"></iconify-icon></a>
                                                        <a class="button is-delete" data-id="<?=$image->ID?>"><iconify-icon icon="bi:trash-fill"></iconify-icon></a>
                                                        <a class="button is-download" href="<?=$image->guid?>" target="_blank"><iconify-icon icon="bi:download"></iconify-icon></a>
                                                        <a class="button is-show-hide"><iconify-icon icon="bi:eye-slash-fill"></iconify-icon></a>
                                                    </div>
                                                </figure>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                <?php endif; if( empty( $videos ) && empty( $images ) ) : ?>
                                    <div class="input-media-preview-assets is-empty">
                                        <p class="is-empty-text">Nenhuma imagem ou vídeo adicionado ainda.</p>
                                    </div>
                                <?php endif; ?>
                            </div>

                            <?php if( wp_is_mobile() ) : ?>
                                <div class="input-media-uploader">
                                    <div class="input-media-uploader-files">
                                        <a id="mediaUploadButtonMobile" class="button is-primary is-small is-upload-media is-multiple">
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
                                    <?= __('Confira se as partes sensíveis das imagens, como rostos, placas ou nomes, <b>foram borradas corretamente.</b> Se ainda houver algo visível, edite, exclua ou substitua o arquivo antes de publicar.') ?>
                                </p>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div id="formSubmit" class="form-submit">
                        <input type="hidden" name="action" value="form_single_risco_edit">
                        <input type="hidden" name="post_id" value="<?=get_the_ID()?>">
                        <input type="hidden" name="post_status" value="<?=$post_status?>">
                        <input type="hidden" name="post_status_current" value="<?=$post_status?>">
                        <input type="hidden" name="post_status_new" value="">

                        <?php if( !wp_is_mobile() ) : ?>
                            <a class="button is-archive">
                                <iconify-icon icon="bi:x-lg"></iconify-icon>
                                <span>Arquivar</span>
                            </a>
                        <?php endif; ?>

                        <?php if( wp_is_mobile() ) : ?>
                            <a class="button is-archive">
                                <iconify-icon icon="bi:x-lg"></iconify-icon>
                                <span>Arquivar</span>
                            </a>
                        <?php endif; ?>

                        <?php
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
                                            <span>Publicar</span>
                                        </a>

                                        <?php

                                        break;
                                }
                        ?>

                    </div>
                    <div id="file-input-storage" style="display: none;"></div>
                </form>
                <?php echo get_template_part('template-parts/dashboard/ui/modal-confirm' ); ?>
                <?php echo get_template_part('template-parts/dashboard/ui/modal-assetset-fullscreen' ); ?>

            </div>
            <div id="dashboard-snackbar" class="dashboard-snackbar">
            </div>
        </div>
    <?php endwhile; ?>
<?php endif; ?>
