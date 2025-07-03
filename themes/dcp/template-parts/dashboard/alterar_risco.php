<?php

namespace hacklabr\dashboard;

?>

<main class="dashboard dashboard--alterar-risco">
    <div class="container">
        <h1>Situação atual:</h1>

        <div id="situacao-atual">
            <div class="card card--alerta card--atencao">
                <strong>ATENÇÃO</strong> Alagamento em algumas áreas do Jacarezinho. Evite locais de risco.<br>
                <span class="card__info">
                    Rio de Janeiro: <strong>ESTÁGIO 3</strong> | 32º | Chuvas medianas
                </span><br>
                <span class="card__data">Última atualização: 15:30 - 15/07/25</span>
            </div>
        </div>

        <h2>Selecionar outra:</h2>

        <form id="form-risco">
            <ul class="lista-riscos">
                <li class="card card--normal">
                    <label>
                        <input type="checkbox" name="risco_selecionado" value="1">
                        <strong>NORMAL</strong> Sem registros de ocorrências. Não há riscos no momento.<br>
                        <span>Rio de Janeiro: <strong>ESTÁGIO 1</strong> | 28º | Nublado</span><br>
                        <span class="card__data">Última atualização: 15:00 - 15/07/25</span>
                    </label>
                </li>
                <li class="card card--atencao">
                    <label>
                        <input type="checkbox" name="risco_selecionado" value="2">
                        <strong>ATENÇÃO</strong> Alagamento em algumas áreas do Jacarezinho. Evite locais de risco.<br>
                        <span>Rio de Janeiro: <strong>ESTÁGIO 3</strong> | 32º | Chuvas medianas</span><br>
                        <span class="card__data">Última atualização: 15:30 - 15/07/25</span>
                    </label>
                </li>
                <li class="card card--perigo">
                    <label>
                        <input type="checkbox" name="risco_selecionado" value="3">
                        <strong>PERIGO</strong> Risco alto de alagamentos e outras ocorrências.<br>
                        <span>Rio de Janeiro: <strong>ESTÁGIO 3</strong> | 32º | Chuvas fortes</span><br>
                        <span class="card__data">Última atualização: 15:30 - 15/07/25</span>
                    </label>
                </li>
            </ul>

            <div class="botoes-acoes">
                <a href="<?= get_permalink(get_page_by_path('dashboard')) ?>" class="btn btn--voltar">
                    <svg><!-- ícone de voltar --></svg> Voltar
                </a>
                <button type="button" id="btn-publicar" class="btn btn--publicar">
                    Publicar alteração
                </button>
            </div>
        </form>
    </div>
</main>

<script src="<?= get_template_directory_uri() ?>/assets/js/alterar-risco.js"></script>
