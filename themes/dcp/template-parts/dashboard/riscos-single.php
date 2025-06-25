<?php

    //TODO: REFACTORY P/ METHOD/FUNCTIONS
    $risco_id = get_query_var('risco_id' );

    $riscoSingle = new WP_Query([
        'p' => $risco_id,
        'post_type' => 'risco',
        //'post_status' => 'draft'
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
//            echo '<pre>';
//            print_r($videos);
//            print_r($images);
//            echo '</pre>';
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
            <span><?=$text?> (<?=$post_status?>)</span>
        </a>
    </header>

    <div class="dashboard-content-single">
        <div class="dashboard-content-skeleton is-load-form">
            <svg width="100%" height="100%" viewBox="0 0 300 70" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:1.41421;">
                    <defs>
                        <mask id="mask-element">
                            <path fill="#777" d="M283,18.75c0,-0.69 -0.56,-1.25 -1.25,-1.25l-183.5,0c-0.69,0 -1.25,0.56 -1.25,1.25l0,2.5c0,0.69 0.56,1.25 1.25,1.25l183.5,0c0.69,0 1.25,-0.56 1.25,-1.25l0,-2.5Z"/>
                            <path fill="#777" d="M283,28.75c0,-0.69 -0.56,-1.25 -1.25,-1.25l-183.5,0c-0.69,0 -1.25,0.56 -1.25,1.25l0,2.5c0,0.69 0.56,1.25 1.25,1.25l183.5,0c0.69,0 1.25,-0.56 1.25,-1.25l0,-2.5Z"/>
                            <path fill="#777" d="M254,38.75c0,-0.69 -0.56,-1.25 -1.25,-1.25l-154.5,0c-0.69,0 -1.25,0.56 -1.25,1.25l0,2.5c0,0.69 0.56,1.25 1.25,1.25l154.5,0c0.69,0 1.25,-0.56 1.25,-1.25l0,-2.5Z"/>
                            <path fill="#777" d="M281.75,48.75c0,-0.69 -0.56,-1.25 -1.25,-1.25l-182.25,0c-0.69,0 -1.25,0.56 -1.25,1.25l0,2.5c0,0.69 0.56,1.25 1.25,1.25l182.25,0c0.69,0 1.25,-0.56 1.25,-1.25l0,-2.5Z"/>
                            <path id="qube" fill="#777" d="M92,20.87c0,-1.86 -1.51,-3.37 -3.37,-3.37l-28.26,0c-1.86,0 -3.37,1.51 -3.37,3.37l0,28.26c0,1.86 1.51,3.37 3.37,3.37l28.26,0c1.86,0 3.37,-1.51 3.37,-3.37l0,-28.26Z"/>
                            <path fill="hsla(200,0%,10%,.6)" id="mask" d="M52,17.5l0,35l-40,0l20,-35l20,0Z"/>
                        </mask>
                    </defs>
                <path mask="url(#mask-element)" d="M283,18.75c0,-0.69 -0.56,-1.25 -1.25,-1.25l-183.5,0c-0.69,0 -1.25,0.56 -1.25,1.25l0,2.5c0,0.69 0.56,1.25 1.25,1.25l183.5,0c0.69,0 1.25,-0.56 1.25,-1.25l0,-2.5Z" fill="#dadada"/>
                <path mask="url(#mask-element)" d="M283,28.75c0,-0.69 -0.56,-1.25 -1.25,-1.25l-183.5,0c-0.69,0 -1.25,0.56 -1.25,1.25l0,2.5c0,0.69 0.56,1.25 1.25,1.25l183.5,0c0.69,0 1.25,-0.56 1.25,-1.25l0,-2.5Z" fill="#dadada"/>
                <path mask="url(#mask-element)" d="M254,38.75c0,-0.69 -0.56,-1.25 -1.25,-1.25l-154.5,0c-0.69,0 -1.25,0.56 -1.25,1.25l0,2.5c0,0.69 0.56,1.25 1.25,1.25l154.5,0c0.69,0 1.25,-0.56 1.25,-1.25l0,-2.5Z" fill="#dadada"/>
                <path mask="url(#mask-element)" d="M281.75,48.75c0,-0.69 -0.56,-1.25 -1.25,-1.25l-182.25,0c-0.69,0 -1.25,0.56 -1.25,1.25l0,2.5c0,0.69 0.56,1.25 1.25,1.25l182.25,0c0.69,0 1.25,-0.56 1.25,-1.25l0,-2.5Z" fill="#dadada"/>
                <path mask="url(#mask-element)" id="qube" d="M92,20.87c0,-1.86 -1.51,-3.37 -3.37,-3.37l-28.26,0c-1.86,0 -3.37,1.51 -3.37,3.37l0,28.26c0,1.86 1.51,3.37 3.37,3.37l28.26,0c1.86,0 3.37,-1.51 3.37,-3.37l0,-28.26Z" fill="#dadada"/>
            </svg>
            <svg width="100%" height="100%" viewBox="0 0 300 70" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:1.41421;">
                    <defs>
                        <mask id="mask-element">
                            <path fill="#777" d="M283,18.75c0,-0.69 -0.56,-1.25 -1.25,-1.25l-183.5,0c-0.69,0 -1.25,0.56 -1.25,1.25l0,2.5c0,0.69 0.56,1.25 1.25,1.25l183.5,0c0.69,0 1.25,-0.56 1.25,-1.25l0,-2.5Z"/>
                            <path fill="#777" d="M283,28.75c0,-0.69 -0.56,-1.25 -1.25,-1.25l-183.5,0c-0.69,0 -1.25,0.56 -1.25,1.25l0,2.5c0,0.69 0.56,1.25 1.25,1.25l183.5,0c0.69,0 1.25,-0.56 1.25,-1.25l0,-2.5Z"/>
                            <path fill="#777" d="M254,38.75c0,-0.69 -0.56,-1.25 -1.25,-1.25l-154.5,0c-0.69,0 -1.25,0.56 -1.25,1.25l0,2.5c0,0.69 0.56,1.25 1.25,1.25l154.5,0c0.69,0 1.25,-0.56 1.25,-1.25l0,-2.5Z"/>
                            <path fill="#777" d="M281.75,48.75c0,-0.69 -0.56,-1.25 -1.25,-1.25l-182.25,0c-0.69,0 -1.25,0.56 -1.25,1.25l0,2.5c0,0.69 0.56,1.25 1.25,1.25l182.25,0c0.69,0 1.25,-0.56 1.25,-1.25l0,-2.5Z"/>
                            <path id="qube" fill="#777" d="M92,20.87c0,-1.86 -1.51,-3.37 -3.37,-3.37l-28.26,0c-1.86,0 -3.37,1.51 -3.37,3.37l0,28.26c0,1.86 1.51,3.37 3.37,3.37l28.26,0c1.86,0 3.37,-1.51 3.37,-3.37l0,-28.26Z"/>
                            <path fill="hsla(200,0%,10%,.6)" id="mask" d="M52,17.5l0,35l-40,0l20,-35l20,0Z"/>
                        </mask>
                    </defs>
                <path mask="url(#mask-element)" d="M283,18.75c0,-0.69 -0.56,-1.25 -1.25,-1.25l-183.5,0c-0.69,0 -1.25,0.56 -1.25,1.25l0,2.5c0,0.69 0.56,1.25 1.25,1.25l183.5,0c0.69,0 1.25,-0.56 1.25,-1.25l0,-2.5Z" fill="#dadada"/>
                <path mask="url(#mask-element)" d="M283,28.75c0,-0.69 -0.56,-1.25 -1.25,-1.25l-183.5,0c-0.69,0 -1.25,0.56 -1.25,1.25l0,2.5c0,0.69 0.56,1.25 1.25,1.25l183.5,0c0.69,0 1.25,-0.56 1.25,-1.25l0,-2.5Z" fill="#dadada"/>
                <path mask="url(#mask-element)" d="M254,38.75c0,-0.69 -0.56,-1.25 -1.25,-1.25l-154.5,0c-0.69,0 -1.25,0.56 -1.25,1.25l0,2.5c0,0.69 0.56,1.25 1.25,1.25l154.5,0c0.69,0 1.25,-0.56 1.25,-1.25l0,-2.5Z" fill="#dadada"/>
                <path mask="url(#mask-element)" d="M281.75,48.75c0,-0.69 -0.56,-1.25 -1.25,-1.25l-182.25,0c-0.69,0 -1.25,0.56 -1.25,1.25l0,2.5c0,0.69 0.56,1.25 1.25,1.25l182.25,0c0.69,0 1.25,-0.56 1.25,-1.25l0,-2.5Z" fill="#dadada"/>
                <path mask="url(#mask-element)" id="qube" d="M92,20.87c0,-1.86 -1.51,-3.37 -3.37,-3.37l-28.26,0c-1.86,0 -3.37,1.51 -3.37,3.37l0,28.26c0,1.86 1.51,3.37 3.37,3.37l28.26,0c1.86,0 3.37,-1.51 3.37,-3.37l0,-28.26Z" fill="#dadada"/>
            </svg>
            <svg width="100%" height="100%" viewBox="0 0 300 70" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:1.41421;">
                    <defs>
                        <mask id="mask-element">
                            <path fill="#777" d="M283,18.75c0,-0.69 -0.56,-1.25 -1.25,-1.25l-183.5,0c-0.69,0 -1.25,0.56 -1.25,1.25l0,2.5c0,0.69 0.56,1.25 1.25,1.25l183.5,0c0.69,0 1.25,-0.56 1.25,-1.25l0,-2.5Z"/>
                            <path fill="#777" d="M283,28.75c0,-0.69 -0.56,-1.25 -1.25,-1.25l-183.5,0c-0.69,0 -1.25,0.56 -1.25,1.25l0,2.5c0,0.69 0.56,1.25 1.25,1.25l183.5,0c0.69,0 1.25,-0.56 1.25,-1.25l0,-2.5Z"/>
                            <path fill="#777" d="M254,38.75c0,-0.69 -0.56,-1.25 -1.25,-1.25l-154.5,0c-0.69,0 -1.25,0.56 -1.25,1.25l0,2.5c0,0.69 0.56,1.25 1.25,1.25l154.5,0c0.69,0 1.25,-0.56 1.25,-1.25l0,-2.5Z"/>
                            <path fill="#777" d="M281.75,48.75c0,-0.69 -0.56,-1.25 -1.25,-1.25l-182.25,0c-0.69,0 -1.25,0.56 -1.25,1.25l0,2.5c0,0.69 0.56,1.25 1.25,1.25l182.25,0c0.69,0 1.25,-0.56 1.25,-1.25l0,-2.5Z"/>
                            <path id="qube" fill="#777" d="M92,20.87c0,-1.86 -1.51,-3.37 -3.37,-3.37l-28.26,0c-1.86,0 -3.37,1.51 -3.37,3.37l0,28.26c0,1.86 1.51,3.37 3.37,3.37l28.26,0c1.86,0 3.37,-1.51 3.37,-3.37l0,-28.26Z"/>
                            <path fill="hsla(200,0%,10%,.6)" id="mask" d="M52,17.5l0,35l-40,0l20,-35l20,0Z"/>
                        </mask>
                    </defs>
                <path mask="url(#mask-element)" d="M283,18.75c0,-0.69 -0.56,-1.25 -1.25,-1.25l-183.5,0c-0.69,0 -1.25,0.56 -1.25,1.25l0,2.5c0,0.69 0.56,1.25 1.25,1.25l183.5,0c0.69,0 1.25,-0.56 1.25,-1.25l0,-2.5Z" fill="#dadada"/>
                <path mask="url(#mask-element)" d="M283,28.75c0,-0.69 -0.56,-1.25 -1.25,-1.25l-183.5,0c-0.69,0 -1.25,0.56 -1.25,1.25l0,2.5c0,0.69 0.56,1.25 1.25,1.25l183.5,0c0.69,0 1.25,-0.56 1.25,-1.25l0,-2.5Z" fill="#dadada"/>
                <path mask="url(#mask-element)" d="M254,38.75c0,-0.69 -0.56,-1.25 -1.25,-1.25l-154.5,0c-0.69,0 -1.25,0.56 -1.25,1.25l0,2.5c0,0.69 0.56,1.25 1.25,1.25l154.5,0c0.69,0 1.25,-0.56 1.25,-1.25l0,-2.5Z" fill="#dadada"/>
                <path mask="url(#mask-element)" d="M281.75,48.75c0,-0.69 -0.56,-1.25 -1.25,-1.25l-182.25,0c-0.69,0 -1.25,0.56 -1.25,1.25l0,2.5c0,0.69 0.56,1.25 1.25,1.25l182.25,0c0.69,0 1.25,-0.56 1.25,-1.25l0,-2.5Z" fill="#dadada"/>
                <path mask="url(#mask-element)" id="qube" d="M92,20.87c0,-1.86 -1.51,-3.37 -3.37,-3.37l-28.26,0c-1.86,0 -3.37,1.51 -3.37,3.37l0,28.26c0,1.86 1.51,3.37 3.37,3.37l28.26,0c1.86,0 3.37,-1.51 3.37,-3.37l0,-28.26Z" fill="#dadada"/>
            </svg>
        </div>
        <form id="riscoSingleForm" class="" method="post" enctype="multipart/form-data" action="javascript:void(0);" data-action="<?php bloginfo( 'url' );?>/wp-admin/admin-ajax.php">
            <input type="hidden" name="action" value="form_single_risco_edit">
            <input type="hidden" name="post_id" value="<?=get_the_ID()?>">
            <input type="hidden" name="post_status_current" value="<?=$post_status?>">
            <input type="hidden" name="post_status_new" value="">
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
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus ullamcorper.
                    </p>
                </div>
            </div>
            <div class="fields">
                <div class="input-wrap">
                    <label class="label">Categoria</label>
                    <select class="select is-select-load-category" name="category" data-endpoint="<?=bloginfo( 'url' )?>/wp-json/wp/v2/situacao_de_risco/" readonly required>
                        <option>CARREGANDO . . .</option>
                    </select>
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
            <div class="fields">
                <div class="input-wrap">
                    <label class="label">Subcategoria</label>
                    <input class="input is-chip-load-subcategory" type="text" name="subcategory" placeholder="" value="CARREGANDO . . ."  data-endpoint="<?=bloginfo( 'url' )?>/wp-json/wp/v2/situacao_de_risco/?post=<?=$risco_id?>" readonly required>
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
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus ullamcorper.
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
                            <div class="dashboard-content-skeleton">
                                <svg width="100%" height="100%" viewBox="0 0 300 70" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:1.41421;">
                                    <defs>
                                        <mask id="mask-element">
                                            <path fill="#777" d="M283,18.75c0,-0.69 -0.56,-1.25 -1.25,-1.25l-183.5,0c-0.69,0 -1.25,0.56 -1.25,1.25l0,2.5c0,0.69 0.56,1.25 1.25,1.25l183.5,0c0.69,0 1.25,-0.56 1.25,-1.25l0,-2.5Z"/>
                                            <path fill="#777" d="M283,28.75c0,-0.69 -0.56,-1.25 -1.25,-1.25l-183.5,0c-0.69,0 -1.25,0.56 -1.25,1.25l0,2.5c0,0.69 0.56,1.25 1.25,1.25l183.5,0c0.69,0 1.25,-0.56 1.25,-1.25l0,-2.5Z"/>
                                            <path fill="#777" d="M254,38.75c0,-0.69 -0.56,-1.25 -1.25,-1.25l-154.5,0c-0.69,0 -1.25,0.56 -1.25,1.25l0,2.5c0,0.69 0.56,1.25 1.25,1.25l154.5,0c0.69,0 1.25,-0.56 1.25,-1.25l0,-2.5Z"/>
                                            <path fill="#777" d="M281.75,48.75c0,-0.69 -0.56,-1.25 -1.25,-1.25l-182.25,0c-0.69,0 -1.25,0.56 -1.25,1.25l0,2.5c0,0.69 0.56,1.25 1.25,1.25l182.25,0c0.69,0 1.25,-0.56 1.25,-1.25l0,-2.5Z"/>
                                            <path id="qube" fill="#777" d="M92,20.87c0,-1.86 -1.51,-3.37 -3.37,-3.37l-28.26,0c-1.86,0 -3.37,1.51 -3.37,3.37l0,28.26c0,1.86 1.51,3.37 3.37,3.37l28.26,0c1.86,0 3.37,-1.51 3.37,-3.37l0,-28.26Z"/>
                                            <path fill="hsla(200,0%,10%,.6)" id="mask" d="M52,17.5l0,35l-40,0l20,-35l20,0Z"/>
                                        </mask>
                                    </defs>
                                    <path mask="url(#mask-element)" d="M283,18.75c0,-0.69 -0.56,-1.25 -1.25,-1.25l-183.5,0c-0.69,0 -1.25,0.56 -1.25,1.25l0,2.5c0,0.69 0.56,1.25 1.25,1.25l183.5,0c0.69,0 1.25,-0.56 1.25,-1.25l0,-2.5Z" fill="#dadada"/>
                                    <path mask="url(#mask-element)" d="M283,28.75c0,-0.69 -0.56,-1.25 -1.25,-1.25l-183.5,0c-0.69,0 -1.25,0.56 -1.25,1.25l0,2.5c0,0.69 0.56,1.25 1.25,1.25l183.5,0c0.69,0 1.25,-0.56 1.25,-1.25l0,-2.5Z" fill="#dadada"/>
                                    <path mask="url(#mask-element)" d="M254,38.75c0,-0.69 -0.56,-1.25 -1.25,-1.25l-154.5,0c-0.69,0 -1.25,0.56 -1.25,1.25l0,2.5c0,0.69 0.56,1.25 1.25,1.25l154.5,0c0.69,0 1.25,-0.56 1.25,-1.25l0,-2.5Z" fill="#dadada"/>
                                    <path mask="url(#mask-element)" d="M281.75,48.75c0,-0.69 -0.56,-1.25 -1.25,-1.25l-182.25,0c-0.69,0 -1.25,0.56 -1.25,1.25l0,2.5c0,0.69 0.56,1.25 1.25,1.25l182.25,0c0.69,0 1.25,-0.56 1.25,-1.25l0,-2.5Z" fill="#dadada"/>
                                    <path mask="url(#mask-element)" id="qube" d="M92,20.87c0,-1.86 -1.51,-3.37 -3.37,-3.37l-28.26,0c-1.86,0 -3.37,1.51 -3.37,3.37l0,28.26c0,1.86 1.51,3.37 3.37,3.37l28.26,0c1.86,0 3.37,-1.51 3.37,-3.37l0,-28.26Z" fill="#dadada"/>
                </svg>
                            </div>
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
                                            <a class="button is-fullscreen">
                                                <iconify-icon icon="bi:arrows-fullscreen"></iconify-icon>
                                            </a>
                                            <a class="button is-delete">
                                                <iconify-icon icon="bi:trash-fill"></iconify-icon>
                                            </a>
                                            <a class="button is-download">
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
                            <div class="dashboard-content-skeleton">
                                <svg width="100%" height="100%" viewBox="0 0 300 70" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" xml:space="preserve" style="fill-rule:evenodd;clip-rule:evenodd;stroke-linejoin:round;stroke-miterlimit:1.41421;">
                                    <defs>
                                        <mask id="mask-element">
                                            <path fill="#777" d="M283,18.75c0,-0.69 -0.56,-1.25 -1.25,-1.25l-183.5,0c-0.69,0 -1.25,0.56 -1.25,1.25l0,2.5c0,0.69 0.56,1.25 1.25,1.25l183.5,0c0.69,0 1.25,-0.56 1.25,-1.25l0,-2.5Z"/>
                                            <path fill="#777" d="M283,28.75c0,-0.69 -0.56,-1.25 -1.25,-1.25l-183.5,0c-0.69,0 -1.25,0.56 -1.25,1.25l0,2.5c0,0.69 0.56,1.25 1.25,1.25l183.5,0c0.69,0 1.25,-0.56 1.25,-1.25l0,-2.5Z"/>
                                            <path fill="#777" d="M254,38.75c0,-0.69 -0.56,-1.25 -1.25,-1.25l-154.5,0c-0.69,0 -1.25,0.56 -1.25,1.25l0,2.5c0,0.69 0.56,1.25 1.25,1.25l154.5,0c0.69,0 1.25,-0.56 1.25,-1.25l0,-2.5Z"/>
                                            <path fill="#777" d="M281.75,48.75c0,-0.69 -0.56,-1.25 -1.25,-1.25l-182.25,0c-0.69,0 -1.25,0.56 -1.25,1.25l0,2.5c0,0.69 0.56,1.25 1.25,1.25l182.25,0c0.69,0 1.25,-0.56 1.25,-1.25l0,-2.5Z"/>
                                            <path id="qube" fill="#777" d="M92,20.87c0,-1.86 -1.51,-3.37 -3.37,-3.37l-28.26,0c-1.86,0 -3.37,1.51 -3.37,3.37l0,28.26c0,1.86 1.51,3.37 3.37,3.37l28.26,0c1.86,0 3.37,-1.51 3.37,-3.37l0,-28.26Z"/>
                                            <path fill="hsla(200,0%,10%,.6)" id="mask" d="M52,17.5l0,35l-40,0l20,-35l20,0Z"/>
                                        </mask>
                                    </defs>
                                    <path mask="url(#mask-element)" d="M283,18.75c0,-0.69 -0.56,-1.25 -1.25,-1.25l-183.5,0c-0.69,0 -1.25,0.56 -1.25,1.25l0,2.5c0,0.69 0.56,1.25 1.25,1.25l183.5,0c0.69,0 1.25,-0.56 1.25,-1.25l0,-2.5Z" fill="#dadada"/>
                                    <path mask="url(#mask-element)" d="M283,28.75c0,-0.69 -0.56,-1.25 -1.25,-1.25l-183.5,0c-0.69,0 -1.25,0.56 -1.25,1.25l0,2.5c0,0.69 0.56,1.25 1.25,1.25l183.5,0c0.69,0 1.25,-0.56 1.25,-1.25l0,-2.5Z" fill="#dadada"/>
                                    <path mask="url(#mask-element)" d="M254,38.75c0,-0.69 -0.56,-1.25 -1.25,-1.25l-154.5,0c-0.69,0 -1.25,0.56 -1.25,1.25l0,2.5c0,0.69 0.56,1.25 1.25,1.25l154.5,0c0.69,0 1.25,-0.56 1.25,-1.25l0,-2.5Z" fill="#dadada"/>
                                    <path mask="url(#mask-element)" d="M281.75,48.75c0,-0.69 -0.56,-1.25 -1.25,-1.25l-182.25,0c-0.69,0 -1.25,0.56 -1.25,1.25l0,2.5c0,0.69 0.56,1.25 1.25,1.25l182.25,0c0.69,0 1.25,-0.56 1.25,-1.25l0,-2.5Z" fill="#dadada"/>
                                    <path mask="url(#mask-element)" id="qube" d="M92,20.87c0,-1.86 -1.51,-3.37 -3.37,-3.37l-28.26,0c-1.86,0 -3.37,1.51 -3.37,3.37l0,28.26c0,1.86 1.51,3.37 3.37,3.37l28.26,0c1.86,0 3.37,-1.51 3.37,-3.37l0,-28.26Z" fill="#dadada"/>
                </svg>
                            </div>

                            <div class="assets-list">
                                <?php foreach ( $images as $image ) : ?>
                                    <figure class="asset-item-preview">
                                        <img src="<?=$image->guid?>" alt="">

                                        <div class="asset-item-preview-actions">

                                            <a class="button is-fullscreen">
                                                <iconify-icon icon="bi:arrows-fullscreen"></iconify-icon>
                                            </a>
                                            <a class="button is-delete">
                                                <iconify-icon icon="bi:trash-fill"></iconify-icon>
                                            </a>
                                            <a class="button is-download">
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


                        <?php /*if( empty( $attachment ) ) : ?>
                        <div class="input-media-preview-assets is-empty">
                            <p class="is-empty-text">Nenhuma imagem ou vídeo adicionado ainda.</p>
                        </div>
                        <?php endif;*/ ?>
                    </div>
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
            <div id="formSubmit" class="form-submit">
                <button class="button is-archive">
                    <iconify-icon icon="bi:x-lg"></iconify-icon>
                    <span>Arquivar</span>
                </button>

                <button class="button is-publish">
                    <iconify-icon icon="bi:check2"></iconify-icon>
                    <span>Publicar</span>
                </button>
            </div>
        </form>

        <div style=" background-color: rgba( 0,0,0, 0.05 ); padding: 1rem; margin-bottom: 1rem; border-radius: 25px; font-size: 14px;">
            <p><strong>OUTRAS INFOS :</strong></p>
            <p>NOME : <?=$pod->field('nome_completo')?></p>
            <p>EMAIL : <?=$pod->field('email')?></p>
            <p>TELEFONE : <?=$pod->field('telefone')?></p>
            <p>AUTORIZA CONTATO : <?=$pod->field('autoriza_contato')?></p>
            <p>DATA E HORA : <?=$pod->field('data_e_horario')?></p>
        </div>

        <div id="modalConfirm" class="modal-confirm">
            <div class="modal-confirm-content">
                <header class="is-title">
                    <h3>{TUITULO MODAL}</h3>
                    <button class="button is-close">
                        <iconify-icon icon="bi:x-lg"></iconify-icon>
                    </button>
                </header>

                <article class="is-body">
                    <p>
                        {DESCRIÇÃO MODAL}
                    </p>
                </article>

                <div class="is-actions">
                    <button class="button is-cancel">{CANCEL MODAL}</button>
                    <button class="button is-custom">{CUSTOM CONFIRM MODAL}</button>
                    <button class="button is-confirm">
                        <i><iconify-icon icon="bi:check"></iconify-icon></i>
                        <span>{CONFIRM MODAL}</span>
                    </button>
                </div>
            </div>
        </div>
        <div class="modal-asset-fullscreen">

            <div class="modal-asset-fullscreen-content">
                <header class="is-title">
                    <h3>Modal Asset Fullscreen</h3>
                    <button class="button is-close">
                        <iconify-icon icon="bi:x-lg"></iconify-icon>
                    </button>
                </header>

                <article class="is-body">
                    <img src="http://localhost/wp-content/uploads/2025/05/image-2_1-2.png" alt="">
                    <video class="" poster="http://localhost/wp-content/uploads/2025/05/image-2_1-2.png" playsinline loop muted controls>
                        <source src="http://localhost/wp-content/uploads/2025/06/dgs-artwork-generative-art-abstract-generative-00<?=( 5 + $i )?>.mp4" type="video/mp4">
                    </video>
                </article>

                <div class="is-actions">
                    <button class="button is-cancel">Cancelar</button>
                    <button class="button is-confirm">
                        <i><iconify-icon icon="bi:check"></iconify-icon></i>
                        <span>Confirmar</span>
                    </button>
                </div>
            </div>
        </div>
    </div>


</div>
        <?php endwhile; ?>
    <?php endif; ?>

