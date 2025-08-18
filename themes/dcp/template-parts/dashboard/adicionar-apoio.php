<?php

namespace hacklabr\dashboard;

    $all_terms = get_terms([
        'taxonomy' => 'tipo_apoio',
        'hide_empty' => false,
    ]);

?>
<div id="dashboardApoioNew" class="dashboard-content">
    <div class="dashboard-content-breadcrumb">
        <ol class="breadcrumb">
            <li>
                <a href="<?=get_dashboard_url( 'apoio' )?>">Apoio</a>
                <iconify-icon icon="bi:chevron-right"></iconify-icon>
            </li>
            <li>
                <a href="">Adicionar</a>
                <iconify-icon icon="bi:chevron-right"></iconify-icon>
            </li>
            <li><a href="#/">Novo apoio</a></li>
        </ol>
    </div>
    <header class="dashboard-content-header is-single-new">
        <h1>Adicionar Apoio</h1>
    </header>
    <div class="dashboard-content-single">
        <form id="apoioSingleForm" class="" method="post" enctype="multipart/form-data" action="javascript:void(0);" data-action="<?php bloginfo( 'url' );?>/wp-admin/admin-ajax.php">
            <div class="fields is-tipo-apoio">
                <div class="input-wrap">
                    <label class="label">Tipo de Apoio</label>
                    <select id="selectTipoApoio" class="select" name="tipo_apoio" required>
                        <option value="">SELECIONE UM TIPO DE APOIO</option>
                        <?php foreach ( $all_terms as $key => $term ) :
                            if( !$term->parent ) : ?>
                                <option value="<?=$term->slug?>"><?=$term->name?></option>
                            <?php endif;
                        endforeach; ?>
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
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus ullamcorper.
                    </p>
                </div>
            </div>
            <div class="fields is-subcategory" style="display: none">
                <div class="input-wrap">
                    <label class="label">Subcategoria</label>
                    <select id="selectTipoApoioSubcategory" class="select" name="tipo_apoio_subcategory" required>
                        <option value="">SELECIONE UMA SUBCATEGORIA</option>
                        <?php foreach ( $all_terms as $key => $term ) :
                            if( $term->parent ) : ?>
                                <option value="<?=$term->slug?>"><?=$term->name?></option>
                            <?php endif;
                        endforeach; ?>
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
                        Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus ullamcorper.
                    </p>
                </div>
            </div>
            <div class="fields is-nome">
                <div class="input-wrap">
                    <label class="label">Nome</label>
                    <input class="input" type="text" name="titulo" placeholder="Digite aqui" value="" disabled required>
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
                    <textarea class="textarea" name="descricao" disabled required></textarea>
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
                    <input class="input" type="text" name="horario_de_atendimento" placeholder="Digite aqui" value="" disabled required>
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
                    <input class="input" type="text" name="endereco" placeholder="Digite o local ou endereço aqui" value="" disabled required>
                    <input type="hidden" name="full_address" value="">
                    <input type="hidden" name="latitude" value="">
                    <input type="hidden" name="longitude" value="">
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
                    <input class="input" type="text" name="website" placeholder="Digite o local ou endereço aqui" value="" required>
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
                    <textarea class="textarea" name="info_extra" required></textarea>
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
                        <div id="apoio-image-preview-wrapper" style="display: none; margin-bottom: 15px;">
                            <p style="font-size: 14px; color: #555; margin-bottom: 5px;">Foto de capa</p>
                            <img id="apoio-image-preview" src="#" alt="Preview da imagem" style="max-width: 100%; height: auto; border-radius: 8px; border: 1px solid #ddd;">
                        </div>

                        <div class="apoio-input-wrap" style="display: flex; align-items: center; gap: 15px; border: 1px solid #ccc; border-radius: 8px; padding: 10px;">
                            <input type="file" name="media_file" id="apoio-cover-input" accept="image/*" style="display: none;">

                            <label for="apoio-cover-input" class="apoio-cover-input button is-primary is-small" style="cursor: pointer; margin: 0;">
                                <iconify-icon icon="bi:upload"></iconify-icon>
                                <span>Escolher arquivo</span>
                            </label>

                            <span id="apoio-file-name">Nenhum arquivo selecionado</span>

                            <button type="button" id="apoio-delete-button" class="button is-delete" style="display: none; margin-left: auto;">
                                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17" fill="none">
                                    <path d="M2.50195 1.73047C2.23674 1.73047 1.98238 1.83583 1.79485 2.02336C1.60731 2.2109 1.50195 2.46525 1.50195 2.73047V3.73047C1.50195 3.99569 1.60731 4.25004 1.79485 4.43758C1.98238 4.62511 2.23674 4.73047 2.50195 4.73047H3.00195V13.7305C3.00195 14.2609 3.21267 14.7696 3.58774 15.1447C3.96281 15.5198 4.47152 15.7305 5.00195 15.7305H11.002C11.5324 15.7305 12.0411 15.5198 12.4162 15.1447C12.7912 14.7696 13.002 14.2609 13.002 13.7305V4.73047H13.502C13.7672 4.73047 14.0215 4.62511 14.2091 4.43758C14.3966 4.25004 14.502 3.99569 14.502 3.73047V2.73047C14.502 2.46525 14.3966 2.2109 14.2091 2.02336C14.0215 1.83583 13.7672 1.73047 13.502 1.73047H10.002C10.002 1.46525 9.8966 1.2109 9.70906 1.02336C9.52152 0.835826 9.26717 0.730469 9.00195 0.730469H7.00195C6.73674 0.730469 6.48238 0.835826 6.29485 1.02336C6.10731 1.2109 6.00195 1.46525 6.00195 1.73047H2.50195ZM5.50195 5.73047C5.63456 5.73047 5.76174 5.78315 5.85551 5.87692C5.94927 5.97068 6.00195 6.09786 6.00195 6.23047V13.2305C6.00195 13.3631 5.94927 13.4903 5.85551 13.584C5.76174 13.6778 5.63456 13.7305 5.50195 13.7305C5.36934 13.7305 5.24217 13.6778 5.1484 13.584C5.05463 13.4903 5.00195 13.3631 5.00195 13.2305V6.23047C5.00195 6.09786 5.05463 5.97068 5.1484 5.87692C5.24217 5.78315 5.36934 5.73047 5.50195 5.73047ZM8.00195 5.73047C8.13456 5.73047 8.26174 5.78315 8.35551 5.87692C8.44928 5.97068 8.50195 6.09786 8.50195 6.23047V13.2305C8.50195 13.3631 8.44928 13.4903 8.35551 13.584C8.26174 13.6778 8.13456 13.7305 8.00195 13.7305C7.86934 13.7305 7.74217 13.6778 7.6484 13.584C7.55463 13.4903 7.50195 13.3631 7.50195 13.2305V6.23047C7.50195 6.09786 7.55463 5.97068 7.6484 5.87692C7.74217 5.78315 7.86934 5.73047 8.00195 5.73047ZM11.002 6.23047V13.2305C11.002 13.3631 10.9493 13.4903 10.8555 13.584C10.7617 13.6778 10.6346 13.7305 10.502 13.7305C10.3693 13.7305 10.2422 13.6778 10.1484 13.584C10.0546 13.4903 10.002 13.3631 10.002 13.2305V6.23047C10.002 6.09786 10.0546 5.97068 10.1484 5.87692C10.2422 5.78315 10.3693 5.73047 10.502 5.73047C10.6346 5.73047 10.7617 5.78315 10.8555 5.87692C10.9493 5.97068 11.002 6.09786 11.002 6.23047Z" fill="#F9F3EA"/>
                                </svg>Excluir
                            </button>
                        </div>
                    </div>
                </div>
                <div class="input-help">
                    <a href="#/" class="button"><iconify-icon icon="bi:question"></iconify-icon></a>
                    <p>Adicione uma imagem para este local ou iniciativa.</p>
                </div>
            </div>
            <div class="form-submit">
                <input type="hidden" name="action" value="form_single_apoio_new">
                <div></div>
                <div class="form-submit-actions">
                    <a class="button is-goback" href="<?=get_dashboard_url('apoio')?>/"><iconify-icon icon="bi:chevron-left"></iconify-icon><span>Voltar</span></a>
                    <a class="button is-new"><iconify-icon icon="bi:check2"></iconify-icon><span>Criar Apoio</span></a>
                </div>
            </div>
        </form>

        <?php echo get_template_part('template-parts/dashboard/ui/modal-confirm' ); ?>
    </div>
    <div id="dashboard-snackbar" class="dashboard-snackbar">
    </div>
</div>

