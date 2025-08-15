<?php

namespace hacklabr\dashboard;

$data_inicio = get_query_var('data_inicio' );
$data_inicio = !empty($data_inicio) ? $data_inicio : date('Y-m-01');

$data_termino = get_query_var('data_termino' );
$data_termino = !empty($data_termino) ? $data_termino : date('Y-m-d');

$indicadores_riscos = indicadores_riscos( $data_inicio, $data_termino );
$indicadores_acoes = indicadores_acoes( $data_inicio, $data_termino );
$indicadores_apoios = indicadores_apoio( $data_inicio, $data_termino );

// INICIO DO ANO ATÉ DATA ATUAL
$indicadores_riscos_gerais = indicadores_riscos( '2025-01-01', date( 'Y-m-d' ) );
$indicadores_acoes_gerais = indicadores_acoes( '2025-01-01', date( 'Y-m-d' ) );
$indicadores_apoios_gerais = indicadores_apoio( '2025-01-01', date( 'Y-m-d' ) );

//
$indicadores_riscos_alagamento = dashboard_get_riscos_count_by_term(
    'alagamento',
    'situacao_de_risco',
    $data_inicio,
    $data_termino
);
$indicadores_riscos_lixo = dashboard_get_riscos_count_by_term(
    'lixo',
    'situacao_de_risco',
    $data_inicio,
    $data_termino
);
$indicadores_riscos_outros = dashboard_get_riscos_count_by_term(
    'outros',
    'situacao_de_risco',
    $data_inicio,
    $data_termino
);

//echo '<pre>';
//print_r( $indicadores_acoes );
//echo '</pre>';

?>

<div id="dashboardIndicadores" class="dashboard-content">
    <div class="dashboard-content-home">
        <header class="dashboard-content-header">
            <h1>Indicadores</h1>
        </header>
        <div class="dashboard-content-section">
            <header class="dashboard-content-section-header">
                <h2>Nesse Mês</h2>
            </header>
            <div class="dashboard-content-section-body">
                <div class="cards cards--3">
                    <div class="card">
                        <div class="is-chart-filter">
                            <header>
                                <h3>Riscos mapeados</h3>
                                <p>
                                    <select id="selectFilter">
                                        <option value="current_month" <?=( empty( get_query_var('data_inicio' ) ) ) ? 'selected' : ''?>>Nesse mês</option>
                                        <option value="filter_by_dates" <?=( !empty( get_query_var('data_inicio' ) ) ) ? 'selected' : ''?> >Filtrar por datas</option>
                                    </select>
                                </p>
                            </header>
                            <div id="optionsFilter">
                                <form id="formFilterBetweenDates" method="get" action="<?=get_dashboard_url( 'indicadores' )?>/" style="display: flex; justify-content: space-between">
                                    <p>
                                        <label for="start">Data início:</label>
                                        <br>
                                        <input type="date"
                                               id="data_inicio"
                                               name="data_inicio"
                                               value="<?=$data_inicio?>"
                                               min="2025-01-01"
                                               max="<?=date( 'Y-m-d' )?>" />
                                    </p>
                                    <p>
                                        <label for="start">Data término:</label>
                                        <br>
                                        <input type="date"
                                            id="data_termino"
                                            name="data_termino"
                                            value="<?=$data_termino?>"
                                            min="2025-01-01"
                                            max="<?=date( 'Y-m-d' )?>" />
                                    </p>
                                    <button class="button">
                                        Filtrar
                                    </button>
                                </form>
                            </div>
                            <div class="is-counter">
                                <div style=" display: flex; justify-content: start; ">
                                    <h3>
                                        <?=( $indicadores_riscos_alagamento[ 'total_posts' ] + $indicadores_riscos_lixo[ 'total_posts' ] + $indicadores_riscos_outros[ 'total_posts' ] )?>
                                    </h3>
                                </div>
                            </div>
                            <canvas id="chartRiscosCategorias"></canvas>
                        </div>
                    </div>

                    <div class="card">
                        <div class="is-chart-filter">
                            <div class="is-counter">
                                <h3><?=$indicadores_acoes[ 'agendadas' ][ 'total_posts' ]?></h3>
                                <p>Novas ações</p>
                            </div>
                            <canvas id="chartAcoesAgendadas"></canvas>
                        </div>
                    </div>
                    <div class="card">
                        <div class="is-chart-filter">
                            <div class="is-counter">
                                <h3><?=$indicadores_acoes[ 'realizadas' ][ 'total_posts' ]?></h3>
                                <p>Ações realizadas</p>
                            </div>
                            <canvas id="chartAcoesRealizadas"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="dashboard-content-section">
            <div>
                <hr>
            </div>
        </div>
        <div class="dashboard-content-section">
            <header class="dashboard-content-section-header">
                <h2>Indicadores Gerais</h2>
            </header>
            <div class="dashboard-content-section-body">
                <div class="cards cards--4">
                    <div class="card">
                        <div class="is-counter">
                            <h3><?=( $indicadores_riscos_gerais[ 'publicados' ][ 'total_posts' ] + $indicadores_riscos_gerais[ 'aprovacao' ][ 'total_posts' ] + $indicadores_riscos_gerais[ 'arquivados' ][ 'total_posts' ] )?></h3>
                            <p>Riscos mapeados</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="is-counter">
                            <h3><?=$indicadores_apoios_gerais[ 'publicados' ][ 'total_posts' ]?></h3>
                            <p>Pontos de apoio</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="is-counter">
                            <h3><?=$indicadores_acoes_gerais[ 'realizadas' ][ 'total_posts' ]?></h3>
                            <p>Ações realizadas</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="is-counter">
                            <h4>Buraco do Lacerda</h4>
                            <h4>Campo do Abóbora</h4>
                            <p>Locais com mais registros</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="dashboard-content-section">
            <hr>
        </div>
        <div class="dashboard-content-section">
            <header class="dashboard-content-section-header">
                <h2>Indicadores Whatsapp</h2>
            </header>
            <div class="dashboard-content-section-body">
                <div class="cards cards--4">
                    <div class="card">
                        <div class="is-counter">
                            <h3>00</h3>
                            <p>Conversas no whatsapp</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="is-counter">
                            <h3>00s</h3>
                            <p>Tempo médio de conversa</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="is-counter">
                            <h3>00</h3>
                            <p>Contatos no grupo</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="is-counter">
                            <h3>00</h3>
                            <p>Contatos na lista</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctxCategorias = document.getElementById( 'chartRiscosCategorias' ).getContext( '2d' );
        const dadosCategorias = [
            <?=$indicadores_riscos_alagamento[ 'total_posts' ]?>,
            <?=$indicadores_riscos_lixo[ 'total_posts' ]?>,
            <?=$indicadores_riscos_outros[ 'total_posts' ]?>
        ];
        const labelsCategorias = [ 'Alagamento', 'Lixo', 'Outros' ];
        const total = dadosCategorias.reduce((acc, valor) => acc + valor, 0);
        const chartRiscosCategorias = new Chart(ctxCategorias, {
            type: 'doughnut',
            data: {
                labels: labelsCategorias,
                datasets: [{
                    data: dadosCategorias,
                    backgroundColor: ['#235540', '#51B2AF', '#EE7653'],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    // Plugin para customizar tooltips (pop-up)
                    tooltip: {
                        callbacks: {
                            label: (context) => {
                                const valor = context.raw;
                                const percentual = ((valor / total) * 100).toFixed(1);
                                return `${context.label}: ${percentual}%`;
                            }
                        }
                    },
                    // Plugin para mostrar percentuais no centro ou nas legendas
                    legend: {
                        position: 'right', // Legenda à direita
                        align: 'center',   // Centraliza verticalmente
                        labels: {
                            generateLabels: (chart) => {
                                return chart.data.labels.map((label, i) => {
                                    const valor = chart.data.datasets[0].data[i];
                                    const percentual = ((valor / total) * 100).toFixed(1) + '%';
                                    return {
                                        text: `${label}: ${percentual}`,
                                        fillStyle: chart.data.datasets[0].backgroundColor[i],
                                        hidden: false
                                    };
                                });
                            }
                        }
                    }
                }
            }
        });
        //
        const ctxAcoesAgend = document.getElementById( 'chartAcoesAgendadas' ).getContext( '2d' );
        const dadosAcoesAgend = [
            <?=$indicadores_acoes[ 'sugestoes' ][ 'total_posts' ]?>,
            <?=$indicadores_acoes[ 'agendadas' ][ 'total_posts' ]?>,
        ];
        const labelsAcoesAgend = [ 'Sugestões', 'Agendadas' ];
        const totalAcoesAgend = dadosAcoesAgend.reduce((acc, valor) => acc + valor, 0);
        const chartAcoesAgendadas = new Chart(ctxAcoesAgend, {
            type: 'pie',
            data: {
                labels: labelsAcoesAgend,
                datasets: [{
                    data: dadosAcoesAgend,
                    backgroundColor: ['#777777', '#333333'],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    // Plugin para customizar tooltips (pop-up)
                    tooltip: {
                        callbacks: {
                            label: (context) => {
                                const valor = context.raw;
                                const percentual = ((valor / totalAcoesAgend) * 100).toFixed(1);
                                return `${context.label}: ${percentual}%`;
                            }
                        }
                    },
                    // Plugin para mostrar percentuais no centro ou nas legendas
                    legend: {
                        position: 'right', // Legenda à direita
                        align: 'center',   // Centraliza verticalmente
                        labels: {
                            generateLabels: (chart) => {
                                return chart.data.labels.map((label, i) => {
                                    const valor = chart.data.datasets[0].data[i];
                                    const percentual = ((valor / totalAcoesAgend) * 100).toFixed(1) + '%';
                                    return {
                                        text: `${label}: ${percentual}`,
                                        fillStyle: chart.data.datasets[0].backgroundColor[i],
                                        hidden: false
                                    };
                                });
                            }
                        }
                    }
                }
            }
        });
        //
        const ctxAcoesRealiz = document.getElementById( 'chartAcoesRealizadas' ).getContext( '2d' );
        const dadosAcoesRealiz = [
            <?=$indicadores_acoes[ 'realizadas' ][ 'total_posts' ]?>,
            <?=$indicadores_acoes[ 'arquivadas' ][ 'total_posts' ]?>,
        ];
        const labelsAcoesRealiz = [ 'Realizadas', 'Arquivadas' ];
        const totalAcoesRealiz = dadosAcoesRealiz.reduce((acc, valor) => acc + valor, 0);
        const chartAcoesAcoesRealiz = new Chart(ctxAcoesRealiz, {
            type: 'pie',
            data: {
                labels: labelsAcoesRealiz,
                datasets: [{
                    data: dadosAcoesRealiz,
                    backgroundColor: ['#111111', '#999999'],
                    hoverOffset: 4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    // Plugin para customizar tooltips (pop-up)
                    tooltip: {
                        callbacks: {
                            label: (context) => {
                                const valor = context.raw;
                                const percentual = ((valor / totalAcoesRealiz) * 100).toFixed(1);
                                return `${context.label}: ${percentual}%`;
                            }
                        }
                    },
                    // Plugin para mostrar percentuais no centro ou nas legendas
                    legend: {
                        position: 'right', // Legenda à direita
                        align: 'center',   // Centraliza verticalmente
                        labels: {
                            generateLabels: (chart) => {
                                return chart.data.labels.map((label, i) => {
                                    const valor = chart.data.datasets[0].data[i];
                                    const percentual = ((valor / totalAcoesRealiz) * 100).toFixed(1) + '%';
                                    return {
                                        text: `${label}: ${percentual}`,
                                        fillStyle: chart.data.datasets[0].backgroundColor[i],
                                        hidden: false
                                    };
                                });
                            }
                        }
                    }
                }
            }
        });
    </script>
</div>

