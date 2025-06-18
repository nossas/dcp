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
        <a class="button">
            <span>Risco ainda não publicado</span>
        </a>
    </header>

    <div class="dashboard-content-single">
        <form id="riscoSingleForm" class="" method="post" enctype="multipart/form-data" action="javascript:void(0);">

            <div class="fields">
                <div class="input-wrap">
                    <label class="label">Localização</label>
                    <input class="input" type="text" name="location" placeholder="Digite o local ou endereço aqui" value="Buraco do lacerda" readonly required>
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
                    <input class="input" type="text" name="category" placeholder="Digite o local ou endereço aqui" value="Buraco do lacerda" readonly required>
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
                    <input class="input" type="text" name="subcategory" placeholder="Digite o local ou endereço aqui" value="Buraco do lacerda" readonly required>
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
                    <textarea class="textarea" name="description" readonly required>Água subindo muito rápido, já tá quase transbordando</textarea>
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
                <div class="input-media">

                    <div class="input-media-uploader">

                        <h3>Mídias</h3>

                        <div class="input-media-uploader-files">

                            <input type="file" name="risco-media" id="risco-media" multiple>

                            <button class="button is-primary is-small is-upload-media">
                                <iconify-icon icon="bi:upload"></iconify-icon>
                                <span>Adicionar fotos e vídeos</span>
                            </button>
                        </div>

                    </div>

                    <div class="input-media-preview">

                        <div class="input-media-preview-videos">
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
                        </div>
                        <div class="input-media-preview-images">
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
                        </div>

                    </div>



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

            <div class="form-submit">

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
    </div>
</div>

