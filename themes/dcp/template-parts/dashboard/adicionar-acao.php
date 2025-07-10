<?php

namespace hacklabr\dashboard;

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
            <li>
                <a href="">Adicionar</a>
                <iconify-icon icon="bi:chevron-right"></iconify-icon>
            </li>
            <li><a href="#/">Nova Ação</a></li>
        </ol>
    </div>

    <header class="dashboard-content-header">
        <h1>Adicionar Ação</h1>
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
                    <label class="label">Título</label>
                    <input class="input" type="text" name="titulo" placeholder="Digite aqui" value="" required>
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
                    <textarea class="textarea" name="descricao" required></textarea>
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
                        <input class="input" type="date" name="date" placeholder="Digite aqui" value="" required>
                    </div>
                    <div class="input-wrap">
                        <label class="label">Horário</label>
                        <input class="input" type="time" name="horario" placeholder="Digite aqui" value="" required>
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

            <div class="fields is-media-attachments">
                <div id="mediaUploadCover" class="input-media">
                    <div class="input-media-uploader">
                        <h3>Foto de capa</h3>
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
                <input type="hidden" name="action" value="form_single_acao_new">
                <div></div>
                <div>
                    <a class="button is-goback" href="<?=get_dashboard_url( 'acoes' )?>/">
                        <iconify-icon icon="bi:chevron-left"></iconify-icon>
                        <span>Voltar</span>
                    </a>
                    <a class="button is-new">
                        <iconify-icon icon="bi:check2"></iconify-icon>
                        <span>Criar Ação</span>
                    </a>
                </div>
            </div>
        </form>

        <?php echo get_template_part('template-parts/dashboard/ui/modal-confirm' ); ?>
    </div>


</div>

