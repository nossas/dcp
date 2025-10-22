<?php

namespace hacklabr\dashboard;

    $post_id = get_query_var('post_id' );

    $postSingle = new \WP_Query([
        'p' => $post_id,
        'post_type' => 'acao'
    ]);


    $tipos_acoes = [
        'draft' => [
            'name' => 'Sugestões',
            'link' => '',
            'icon' => 'lightbulb-fill',
            'tipo_acao' => 'sugestoes'
        ],
        'publish' => [
            'name' => 'Agendadas',
            'link' => '',
            'icon' => 'calendar3',
            'tipo_acao' => 'agendadas'
        ],
        'private' => [
            'name' => 'Realizadas',
            'link' => '',
            'icon' => 'check-square-fill',
            'tipo_acao' => 'realizadas'
        ],
        'pending' => [
            'name' => 'Arquivadas',
            'link' => '',
            'icon' => 'x-octagon-fill',
            'tipo_acao' => 'arquivadas'
        ]
    ];

    if ( $postSingle->have_posts() ) :

        while ( $postSingle->have_posts()) :
            $postSingle->the_post();

            $post_status = get_post_status();

            $pod = pods( 'acao', get_the_ID());
            $attachment_cover_id = get_post_thumbnail_id(get_the_ID());
            $get_attachment = get_the_post_thumbnail_url(get_the_ID(), 'large');
            $get_terms = get_the_terms( get_the_ID(), 'tipo_acao' );

            $all_terms = get_terms([
                'taxonomy' => 'tipo_acao',
                'hide_empty' => false,
            ]);

            switch ( $post_status ) {
                case 'draft':
                    $class = 'is-draft';
                    $text = 'Ação Sugerida';
                    $title = 'Avaliar ação sugerida';
                    $current_page = 'Avaliar';
                    break;

                case 'publish':
                    $class = 'is-publish';
                    $text = 'Ação Agendada';
                    $title = 'Editar ação agendada';
                    $current_page = 'Editar Ação';
                    $color = '#AA7700';
                    break;

                case 'private':
                    $class = 'is-scheduled';
                    $text = 'Ação Realizada';
                    $title = 'Criar relato de ação';
                    $current_page = 'Editar Ação';
                    $color = '#000';
                    break;

                case 'pending':
                    $class = 'is-pending';
                    $text = 'Ação Arquivada';
                    $title = 'Ações Arquivadas';
                    $current_page = 'Editar Ação';
                    break;

                default:
                    $class = 'is-blocked';
                    $text = 'BLOQUEADO';
                    $title = 'SEM STATUS';
                    $current_page = 'Editar Ação';
                    break;
            }
        ?>

<div id="dashboardAcaoSingle" class="dashboard-content">
    <div class="dashboard-content-breadcrumb">
        <ol class="breadcrumb">
            <li>
                <a href="<?=get_dashboard_url( 'acoes' )?>">Ações</a>
                <iconify-icon icon="bi:chevron-right"></iconify-icon>
            </li>
            <li>
                <a href="<?=get_dashboard_url( 'acoes', [ 'tipo_acao' => $tipos_acoes[ $post_status ][ 'tipo_acao' ] ] )?>"><?=$tipos_acoes[ $post_status ][ 'name' ]?></a>
                <iconify-icon icon="bi:chevron-right"></iconify-icon>
            </li>
            <li><a href="#/"><?=$current_page?></a></li>
        </ol>
    </div>
    <header class="dashboard-content-header is-single">
        <h2><?=$title?></h2>
        <a class="button is-status <?=$class?>" style="<?=( isset( $color ) ? 'background-color : ' . $color : '' )?>">
            <span><?=$text?></span>
        </a>
    </header>
    <div class="dashboard-content-single">
        <form id="acaoSingleForm" class="" method="post" enctype="multipart/form-data" action="javascript:void(0);" data-action="<?php bloginfo( 'url' );?>/wp-admin/admin-ajax.php">
            <div class="fields">
                <div class="input-wrap">
                    <label class="label">Categoria</label>
                    <select id="selectCategory" class="select" name="tipo_acao" required >
                        <option value="">Selecione uma categoria</option>
                        <?php foreach ( $all_terms as $key => $term ) :
                            if( !$term->parent ) : ?>
                                <option value="<?=$term->slug?>" <?=( $term->slug == $get_terms[0]->slug ) ? 'selected' : '' ?>><?=$term->name?></option>
                            <?php endif; endforeach; ?>
                    </select>
                    <p class="input-links">
                        <a href="<?=the_permalink()?>" target="_blank">
                            <iconify-icon icon="bi:box-arrow-up-right"></iconify-icon>
                            <span>Ver Ação</span>
                        </a>
                    </p>

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
                        <?= __('Escolha a categoria que melhor representa a ação.') ?>
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
                        <?= __('Dê um nome curto e claro para a ação. Use um título que ajude a identificar o que vai acontecer.') ?>
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
                        <?= __('Explique o objetivo da ação e o que será feito. Inclua informações úteis, como quem pode participar, o que levar e qual o propósito da atividade.') ?>
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
                        <?= __('Indique a data e horário que a ação vai acontecer.') ?>
                    </p>
                </div>
            </div>
            <div class="fields">
                <div class="input-wrap">
                    <label class="label">Localização</label>
                    <input class="input" type="text" name="endereco" placeholder="Digite o local ou endereço aqui" value="<?=$pod->field('endereco')?>" required readonly>
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
                        <img src="<?=get_template_directory_uri()?>/assets/images/loading.gif">
                    </a>
                </div>
                <div class="input-help">
                    <a href="#/" class="button">
                        <iconify-icon icon="bi:question"></iconify-icon>
                    </a>
                    <p>
                        <?= __('Informe o endereço ou ponto de referência onde a ação acontecerá.') ?>
                    </p>
                </div>
            </div>
            <div class="fields is-media-attachments">
                <div id="mediaUploadCover" class="input-media">
                    <div class="input-media-uploader">
                        <h4>Foto</h4>
                        <div class="input-media-uploader-files">
                            <a id="mediaUploadButtonCover" class="button is-primary is-small is-upload-media" <?= !empty($get_attachment) ? 'disabled' : '' ?>>
                                <iconify-icon icon="bi:upload"></iconify-icon>
                                <span>Adicionar foto</span>
                            </a>
                        </div>
                    </div>

                    <div class="media-preview-container">
                        <h4 class="media-preview-title" style="display: none;">Nova foto</h4>
                        <div class="media-preview-list"></div>
                    </div>

                    <div class="input-media-preview">
                        <div class="input-media-preview-assets is-images">
                            <?php if (!empty($get_attachment)) : ?>
                                <div class="assets-list">
                                    <figure class="asset-item-preview">
                                        <img src="<?= esc_url($get_attachment) ?>">
                                        <div class="asset-item-preview-actions">
                                            <a class="button is-fullscreen" data-id="<?= $attachment_cover_id ?>" data-href="<?= esc_url($get_attachment) ?>"><iconify-icon icon="bi:arrows-fullscreen"></iconify-icon></a>
                                            <a class="button is-delete" data-id="<?= $attachment_cover_id ?>"><iconify-icon icon="bi:trash-fill"></iconify-icon></a>
                                            <a class="button is-download" href="<?= esc_url($get_attachment) ?>" target="_blank"><iconify-icon icon="bi:download"></iconify-icon></a>
                                        </div>
                                    </figure>
                                </div>
                            <?php else : ?>
                                <div class="input-media-preview-assets is-empty">
                                    <p class="is-empty-text">Nenhuma foto adicionada ainda.</p>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div class="input-help">
                    <a href="#/" class="button"><iconify-icon icon="bi:question"></iconify-icon></a>
                    <p><?= __('Adicione uma imagem que represente bem a ação, como o local, um momento anterior ou um símbolo da atividade. Evite fotos com pessoas identificáveis ou informações sensíveis.') ?></p>
                </div>
            </div>
            <div class="fields">
                <?php if( !empty( $pod->field( 'total_participantes' ) ) ) : ?>
                    <a class="is-download button" href="<?=admin_url( 'admin-ajax.php?action=download_participantes_acao&post_id=' . get_the_ID() )?>" target="_blank">
                        <iconify-icon icon="bi:download"></iconify-icon>
                        Lista de participantes <span>(<?=$pod->field( 'total_participantes' )?>)</span>
                    </a>
                <?php else : ?>
                    <a class="is-download button" style="cursor: not-allowed; opacity: 0.5">
                        <iconify-icon icon="bi:download"></iconify-icon>
                        Lista de participantes indisponível</span>
                    </a>
                <?php endif; ?>
            </div>
            <div class="form-submit acao-edit">
                <input type="hidden" name="action" value="form_single_acao_edit">
                <input type="hidden" name="post_id" value="<?=get_the_ID()?>">
                <input type="hidden" name="post_status" value="<?=$post_status?>">

                <?php if( !wp_is_mobile() ) : ?>
                    <div>
                        <a class="button is-goback" href="<?=get_dashboard_url( 'acoes' )?>/">
                            <iconify-icon icon="bi:chevron-left"></iconify-icon>
                            <span>Voltar</span>
                        </a>
                    </div>
                <?php endif; ?>

                <div class="form-submit-actions acao-edit-actions">
                    <?php
                        if( !wp_is_mobile() ) :
                            if( $post_status === 'draft' ) : ?>
                        <a class="button is-archive">
                            <iconify-icon icon="bi:x-lg"></iconify-icon>
                            <span>Arquivar</span>
                        </a>
                    <?php endif; endif; ?>

                    <?php
                        switch ( $post_status ) {

                            case 'draft': ?>
                                <a class="button is-scheduled">
                                    <iconify-icon icon="bi:check2"></iconify-icon>
                                    <span>Agendar Ação</span>
                                </a>
                                <?php break;
                            case 'publish': ?>
                                <a class="button is-save">
                                    <iconify-icon icon="bi:check2"></iconify-icon>
                                    <span>Publicar Alterações</span>
                                </a>
                                <a class="button is-done">
                                    <iconify-icon icon="bi:check-square-fill"></iconify-icon>
                                    <span>Concluir Ação</span>
                                </a>
                                <?php break;

                            case 'pending': ?>
                                <a class="button is-scheduled">
                                    <iconify-icon icon="bi:chevron-left"></iconify-icon>
                                    <span>Agendar Novamente</span>
                                </a>
                                <a class="button is-save">
                                    <iconify-icon icon="bi:check2"></iconify-icon>
                                    <span>Salvar Alterações</span>
                                </a>
                                <?php break;

                            case 'private': ?>
                                <a class="button is-archive">
                                    <iconify-icon icon="bi:x-lg"></iconify-icon>
                                    <span>Arquivar</span>
                                </a>
                                <a class="button is-duplicate" href="<?=get_dashboard_url( 'adicionar-relato' )?>/?post_id=<?=get_the_ID()?>">
                                    <iconify-icon icon="bi:pencil-square"></iconify-icon>
                                    Criar Relato
                                </a>
                                <?php break;
                        }
                    ?>

                    <?php
                    if( wp_is_mobile() ) :
                        if( $post_status === 'draft' ) : ?>
                            <a class="button is-archive">
                                <iconify-icon icon="bi:x-lg"></iconify-icon>
                                <span>Arquivar</span>
                            </a>
                        <?php endif; endif; ?>
                </div>

                <?php if( wp_is_mobile() ) : ?>
                    <div>
                        <a class="button is-goback" href="<?=get_dashboard_url( 'acoes' )?>/">
                            <iconify-icon icon="bi:chevron-left"></iconify-icon>
                            <span>Voltar</span>
                        </a>
                    </div>
                <?php endif; ?>
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
