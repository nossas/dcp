<div id="dashboardRiscoNovo" class="dashboard-content">

    <div class="dashboard-content-breadcrumb">
        <ol class="breadcrumb">
            <li>
                <a href="./?ver=riscos">Riscos</a>
                <iconify-icon icon="bi:chevron-right"></iconify-icon>
            </li>
            <li><a href="#/">Adicionar novo</a></li>
        </ol>
    </div>

    <header class="dashboard-content-header">
        <h1>ADICIONAR NOVO RISCO</h1>
        <a href="./?ver=riscos" class="button">
            <iconify-icon icon="bi:folder"></iconify-icon>
            <span>Salvar como rascunho</span>
        </a>
    </header>




    <div class="dashboard-content-single">
        <form id="riscoSingleForm" class="" method="post" enctype="multipart/form-data" action="javascript:void(0);">

            <div class="fields">
                <div class="input-wrap">
                    <label class="label">Localização</label>
                    <input class="input" type="text" name="location" placeholder="Digite o local ou endereço aqui" required>
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
                    <input class="input" type="text" name="category" placeholder="Digite o local ou endereço aqui" required>
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
                    <input class="input" type="text" name="subcategory" placeholder="Digite o local ou endereço aqui" required>
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
                    <textarea class="textarea" name="description" required></textarea>
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
                <div id="mediaUpload" class="input-media">
                    <div class="input-media-uploader">
                        <h3>Mídias</h3>
                        <div class="input-media-uploader-files">
                            <input id="uploader_media" type="file" name="uploader_media" style="display:none;" accept="image/*,video/*" multiple >
                            <button class="button is-primary is-small is-upload-media">
                                <iconify-icon icon="bi:upload"></iconify-icon>
                                <span>Adicionar fotos e vídeos</span>
                            </button>
                        </div>
                    </div>
                    <div class="input-media-uploader-progress">

                        <div class="progress is-small">
                            <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>

                        <div class="progress is-small">
                            <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>

                        <div class="progress is-small">
                            <div class="progress-bar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                        </div>

                    </div>
                    <div class="input-media-preview">

                        <div class="input-media-preview-empty">
                            <p class="is-empty">nenhum vídeo e imagem foi adicionado ainda.</p>
                        </div>
                        <!--

                        <div class="input-media-preview-assets is-video">
                            <h4>Vídeos</h4>
                            <div class="assets-list"></div>
                        </div>
                        <div class="input-media-preview-assets is-images">
                            <h4>Fotos</h4>
                            <div class="assets-list"></div>
                        </div>

                        -->
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
                    <iconify-icon icon="bi:file-earmark"></iconify-icon>
                    <span>Aprovação</span>
                </button>

                <button class="button is-publish">
                    <iconify-icon icon="bi:check2"></iconify-icon>
                    <span>Publicar</span>
                </button>
            </div>
        </form>

        <div class="modal-confirm">
            <div class="modal-confirm-content">
                <header class="is-title">
                    <h3>Modal Exemplo</h3>
                    <button class="button is-close">
                        <iconify-icon icon="bi:x-lg"></iconify-icon>
                    </button>
                </header>

                <article class="is-body">
                    <p>
                        Este é modal exemplo de uso . . .
                    </p>
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


        <div class="modal-asset-fullscreen">


        </div>



    </div>


</div>

