<?php

namespace hacklabr\dashboard;

if ( isset( $_POST['risco_selecionado'] ) ) {
    $post_id = intval( $_POST['risco_selecionado'] );

    $situacoes_ids = get_posts([
        'post_type' => 'situacao_atual',
        'posts_per_page' => -1,
        'fields' => 'ids'
    ]);

    foreach ( $situacoes_ids as $id ) {
        $pods = \pods( 'situacao_atual', $id );
        $pods->save( 'is_active', false );
    }

    $pod_ativar = \pods( 'situacao_atual', $post_id );
    $pod_ativar->save( 'is_active', true );
    //$pod_ativar->save( 'data_e_horario', date( 'Y-m-d H:i:s' ) );

    $recomendacoes_ids = get_posts([
        'post_type' => 'recomendacao',
        'posts_per_page' => -1,
        'fields' => 'ids'
    ]);

    foreach ( $recomendacoes_ids as $id ) {
        $pods = \pods( 'recomendacao', $id );
        $pods->save( 'is_active', false );
    }

    $pod_recomendacao = \pods( 'recomendacao', $pod_ativar->field( 'recomendacao_id' ) );
    $pod_recomendacao->save( 'is_active', true );
    //$pod_recomendacao->save( 'data_e_horario', date( 'Y-m-d H:i:s' ) );
}

//TODO: REFACTORY TO LIBRARY
if (!function_exists(__NAMESPACE__ . '\render_svg')) {
    function render_svg($id)
    {
        $url = wp_get_attachment_url($id);
        $path = get_attached_file($id);

        if ($url && pathinfo($url, PATHINFO_EXTENSION) === 'svg' && file_exists($path)) {
            return file_get_contents($path);
        }

        return '';
    }
}

function get_cor_by_name( $name = 'NORMAL' ) {
    $cor = '';
    switch ($name) {
        case 'NORMAL';
            $cor = 'normal';
            break;
        case 'ATENÇÃO';
            $cor = 'atencao';
            break;
        case 'PERIGO';
            $cor = 'perigo';
            break;
    }
    return $cor;
}

//TODO: REFACTORY TO LIBRARY
$situacao_post = get_posts([
    'post_type' => 'situacao_atual',
    'posts_per_page' => -1,
    'orderby'        => 'date',
    'order'          => 'ASC',
]);

$situacao_ativa_post = get_posts([
    'post_type' => 'situacao_atual',
    'posts_per_page' => -1,
    'orderby'        => 'date',
    'order'          => 'ASC',
    'meta_query' => [
        [
            'key' => 'is_active',
            'value' => true,
            'compare' => '='
        ]
    ]
]);

$pod_ativo = \pods('situacao_atual', $situacao_ativa_post[0]->ID);

?>

<main id="dashboardSituacaoAtual" class="dashboard dashboard--alterar-risco">
    <div class="situacao-atual__container">
        <nav class="breadcrumb-dashboard">
            <a href="/situacao-atual">Situação atual</a>
            <span class="breadcrumb-dashboard__separator"> > </span>
            <span class="breadcrumb-dashboard__current">Alterar Risco</span>
        </nav>

        <h2 class="alterar-risco__title"><?= __('Situação atual:') ?></h2>

        <div id="situacao-atual">
            <div class="alerta-faixa alerta-faixa--<?=get_cor_by_name( $pod_ativo->field( 'tipo_de_alerta' ) )?>">
                <div class="alerta-faixa__topo alerta-faixa__topo--<?=get_cor_by_name( $pod_ativo->field( 'tipo_de_alerta' ) )?>">
                    <div class="alerta-faixa__mensagem">
                        <span class="alerta-faixa__icone">
                            <img src="<?=$pod_ativo->field( 'icone.guid' )?>" alt="<?= __('ícone de guia') ?>">
                        </span>
                        <div class="alerta-faixa__warning">
                            <strong><?=$pod_ativo->field( 'tipo_de_alerta' )?></strong>
                            <?=$pod_ativo->field( 'descricao' )?>
                        </div>
                    </div>
                </div>
                <div class="alerta-faixa__info">
                    <div class="alerta-faixa__local">
                        <p class="alerta-faixa__local--estado"><?=$pod_ativo->field( 'localizacao' )?></p> <strong><span><?=$pod_ativo->field( 'estagio' )?></span></strong>
                        <div class="multistepform__pipe"> | </div>
                        <span><?=$pod_ativo->field( 'temperatura' )?>º</span> • <?=$pod_ativo->field( 'clima' )?>
                    </div>
                    <div class="alerta-faixa__data">
                        <?= __('Última atualização:') ?> <span><?=date( 'H:m', strtotime( $pod_ativo->field( 'data_e_horario' ) ) )?></span>
                        • <span><?=date( 'd/m/y', strtotime( $pod_ativo->field( 'data_e_horario' ) ) )?></span>
                    </div>
                </div>
            </div>
        </div>

        <h2 class="alterar-risco__title"><?= __('Selecionar outra:') ?></h2>

        <form id="form-risco" method="post" action="" >
            <ul class="lista-riscos">
                <?php foreach ( $situacao_post as $risco ):
                    $pod = \pods( 'situacao_atual', $risco->ID ); ?>
                    <li class="risco-item">
                        <input type="checkbox" id="risco-<?=$risco->ID?>" name="risco_selecionado" value="<?=$risco->ID?>" class="risco-checkbox" <?=( $pod->field( 'is_active' ) ) ? 'checked' : '' ?>>

                        <label for="risco-<?=$risco->ID?>" class="alerta-faixa">
                            <div class="alerta-faixa__topo alerta-faixa__topo--<?=get_cor_by_name( $pod->field( 'tipo_de_alerta' ) )?>">
                                <div class="alerta-faixa__mensagem">
                                    <span class="alerta-faixa__icone">
                                        <img src="<?=$pod->field( 'icone.guid' )?>" alt="<?= __('ícone de guia') ?>">
                                    </span>
                                    <div class="alerta-faixa__warning"><strong><?=$pod->field( 'tipo_de_alerta' )?></strong> <?=$pod->field( 'descricao' )?></div>
                                </div>

                                <div class="alerta-faixa__topo-acoes">
                                    <a href="<?= get_dashboard_url('editar_recomendacao', ['situacao' => get_post( $pod->field( 'recomendacao_id' ) )->post_name ]) ?>" class="alerta-faixa__saiba-mais"><?= __('Saiba mais') ?></a>
                                    <button type="button" class="alerta-faixa__remover" aria-label="<?= __('Remover card') ?>">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="20" height="20" viewBox="0 0 20 20" fill="none">
                                            <path d="M16.876 2.49951C16.9582 2.49951 17.0403 2.51586 17.1162 2.54736C17.1921 2.57887 17.2613 2.62496 17.3193 2.68311C17.3775 2.74114 17.4236 2.81034 17.4551 2.88623C17.4865 2.96204 17.5029 3.04342 17.5029 3.12549C17.5029 3.2077 17.4866 3.28979 17.4551 3.36572C17.4236 3.4416 17.3775 3.51083 17.3193 3.56885L10.8867 10.0005L17.3193 16.4331C17.4365 16.5504 17.5018 16.7097 17.502 16.8755C17.502 17.0415 17.4367 17.2015 17.3193 17.3188C17.202 17.4362 17.0419 17.5015 16.876 17.5015C16.7102 17.5014 16.5508 17.4361 16.4336 17.3188L10.001 10.8862L3.56934 17.3188C3.45198 17.4362 3.29195 17.5015 3.12598 17.5015C2.96019 17.5014 2.80085 17.4361 2.68359 17.3188C2.56624 17.2015 2.50098 17.0415 2.50098 16.8755C2.50108 16.7097 2.56633 16.5504 2.68359 16.4331L9.11523 10.0005L2.68359 3.56885C2.62544 3.51084 2.57936 3.44157 2.54785 3.36572C2.51634 3.28979 2.5 3.2077 2.5 3.12549C2.50005 3.04341 2.51639 2.96204 2.54785 2.88623C2.57936 2.81032 2.6254 2.74115 2.68359 2.68311C2.74164 2.62491 2.81081 2.57887 2.88672 2.54736C2.96253 2.51591 3.0439 2.49956 3.12598 2.49951C3.20819 2.49951 3.29028 2.51586 3.36621 2.54736C3.44206 2.57887 3.51133 2.62496 3.56934 2.68311L10.001 9.11475L16.4336 2.68311C16.4916 2.62491 16.5608 2.57887 16.6367 2.54736C16.7125 2.51591 16.7939 2.49956 16.876 2.49951Z" fill="#281414" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div class="alerta-faixa__info">
                                <div class="alerta-faixa__local">
                                    <p class="alerta-faixa__local--estado"><?=$pod->field( 'localizacao' )?></p> <strong><span><?=$pod->field( 'estagio' )?></span></strong>
                                    <div class="multistepform__pipe"> | </div>
                                    <span><?=$pod->field( 'temperatura' )?>º</span> • <?=$pod->field( 'clima' )?>
                                </div>
                                <div class="alerta-faixa__data">
                                    <?= __('Última atualização:') ?> <span><?=date( 'H:m', strtotime( $pod->field( 'data_e_horario' ) ) )?></span>
                                    • <span><?=date( 'd/m/y', strtotime( $pod->field( 'data_e_horario' ) ) )?></span>
                                </div>
                            </div>
                        </label>
                    </li>
                <?php endforeach; ?>
            </ul>
            <div class="botoes-acoes">
                <a href="<?= get_permalink(get_page_by_path('dashboard')) ?>" class="btn btn--voltar apoio__btn-cancelar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="18" height="18" viewBox="0 0 18 18" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M12.7743 1.85276C12.8266 1.90501 12.8682 1.96708 12.8966 2.03542C12.9249 2.10376 12.9395 2.17702 12.9395 2.25101C12.9395 2.32499 12.9249 2.39826 12.8966 2.46659C12.8682 2.53493 12.8266 2.597 12.7743 2.64926L6.42138 9.001L12.7743 15.3528C12.8799 15.4584 12.9392 15.6016 12.9392 15.751C12.9392 15.9004 12.8799 16.0436 12.7743 16.1493C12.6686 16.2549 12.5254 16.3142 12.376 16.3142C12.2266 16.3142 12.0834 16.2549 11.9778 16.1493L5.22776 9.39925C5.17537 9.347 5.13381 9.28493 5.10545 9.21659C5.0771 9.14825 5.0625 9.07499 5.0625 9.001C5.0625 8.92702 5.0771 8.85376 5.10545 8.78542C5.13381 8.71708 5.17537 8.65501 5.22776 8.60275L11.9778 1.85276C12.03 1.80037 12.0921 1.75881 12.1604 1.73045C12.2288 1.7021 12.302 1.6875 12.376 1.6875C12.45 1.6875 12.5233 1.7021 12.5916 1.73045C12.6599 1.75881 12.722 1.80037 12.7743 1.85276V1.85276Z" fill="#281414" />
                    </svg>
                    <?= __('Voltar') ?>
                </a>
                <button type="button" id="btn-publicar" class="btn btn--publicar apoio__btn-publicar">
                    <svg xmlns="http://www.w3.org/2000/svg" width="19" height="18" viewBox="0 0 19 18" fill="none">
                        <path d="M16.0865 4.10276C16.1388 4.15501 16.1804 4.21708 16.2088 4.28542C16.2371 4.35376 16.2517 4.42702 16.2517 4.50101C16.2517 4.57499 16.2371 4.64826 16.2088 4.71659C16.1804 4.78493 16.1388 4.847 16.0865 4.89926L8.21146 12.7743C8.15921 12.8266 8.09714 12.8682 8.0288 12.8966C7.96046 12.9249 7.8872 12.9395 7.81321 12.9395C7.73922 12.9395 7.66596 12.9249 7.59762 12.8966C7.52928 12.8682 7.46721 12.8266 7.41496 12.7743L3.47746 8.83676C3.37184 8.73113 3.3125 8.58788 3.3125 8.43851C3.3125 8.28913 3.37184 8.14588 3.47746 8.04026C3.58308 7.93463 3.72634 7.87529 3.87571 7.87529C4.02508 7.87529 4.16834 7.93463 4.27396 8.04026L7.81321 11.5806L15.29 4.10276C15.3422 4.05037 15.4043 4.00881 15.4726 3.98045C15.541 3.9521 15.6142 3.9375 15.6882 3.9375C15.7622 3.9375 15.8355 3.9521 15.9038 3.98045C15.9721 4.00881 16.0342 4.05037 16.0865 4.10276V4.10276Z" fill="#F9F3EA" />
                    </svg>
                    <?= __('Publicar alteração') ?>
                </button>
            </div>
        </form>

        <div id="modal-publicar" class="modal-publicar" style="display: none;">
            <div class="modal-publicar__conteudo">
                <button class="modal-publicar__fechar" aria-label="Fechar modal">&times;</button>

                <h2 class="modal-publicar__titulo">
                    <span class="modal-publicar__titulo--destaque">Publicar alterações da situação de risco?</span>
                </h2>

                <p class="modal-publicar__descricao">
                    Lorem ipsum dolor sit amet, consectetur adipiscing elit. Phasellus ullamcorper vestibulum erat in commodo. Vestibulum ante.
                </p>

                <div class="modal-publicar__botoes">
                    <button type="button" class="btn btn--voltar modal-publicar__voltar apoio__btn-cancelar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16">
                            <path d="M10 14L4 8L10 2" stroke="#000" stroke-width="2" fill="none" />
                        </svg>
                        Voltar
                    </button>

                    <button type="button" class="btn btn--publicar modal-publicar__confirmar apoio__btn-publicar">
                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16">
                            <path d="M2 8L6 12L14 4" stroke="#fff" stroke-width="2" fill="none" />
                        </svg>
                        Publicar alteração
                    </button>
                </div>
            </div>
        </div>
    </div>
</main>
