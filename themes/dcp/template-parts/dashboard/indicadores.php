<?php

namespace hacklabr\dashboard;

$data_inicio = get_query_var('data_inicio' );
$data_inicio = !empty($data_inicio) ? $data_inicio : date('Y-m-01');

$data_termino = get_query_var('data_termino' );
$data_termino = !empty($data_termino) ? $data_termino : date('Y-m-d');

$indicadores_riscos = indicadores_riscos( $data_inicio, $data_termino );
$indicadores_acoes = indicadores_acoes( $data_inicio, $data_termino );
$indicadores_apoios = indicadores_apoio( $data_inicio, $data_termino );


$indicadores_riscos_gerais = indicadores_riscos( '2025-01-01', date( 'Y-m-d' ) );
$indicadores_acoes_gerais = indicadores_acoes( '2025-01-01', date( 'Y-m-d' ) );
$indicadores_apoios_gerais = indicadores_apoio( '2025-01-01', date( 'Y-m-d' ) );

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
                <div class="cards">
                    <div class="card">
                        <div class="is-chart-filter">
                            <header>
                                <h3>Riscos mapeados</h3>
                                <p>
                                    <select>
                                        <option>Nesse mês</option>
                                    </select>
                                </p>
                            </header>
                            <div>
                                <form id="formFilterBetweenDates" method="get" action="<?=get_dashboard_url( 'indicadores' )?>/">
                                    <p>
                                        <label for="start">Data início:</label>

                                        <input type="date"
                                               id="data_inicio"
                                               name="data_inicio"
                                               value="<?=$data_inicio?>"
                                               min="2025-01-01"
                                               max="<?=date( 'Y-m-d' )?>" />
                                    </p>
                                    <p>
                                        <label for="start">Data término:</label>

                                        <input
                                            type="date"
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
                            <div>
                                <canvas id="myChart"></canvas>
                            </div>

                        </div>
                    </div>
                    <div class="card">
                        <div class="is-counter">
                            <h3><?=$indicadores_acoes[ 'agendadas' ][ 'total_posts' ]?></h3>
                            <p>Novas ações</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="is-counter">
                            <h3><?=$indicadores_acoes[ 'realizadas' ][ 'total_posts' ]?></h3>
                            <p>Ações realizadas</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="dashboard-content-section">
            <header class="dashboard-content-section-header">
                <h2>Indicadores Gerais</h2>
            </header>
            <div class="dashboard-content-section-body">
                <div class="cards">
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
            <header class="dashboard-content-section-header">
                <h2>Indicadores Whatsapp</h2>
            </header>
            <div class="dashboard-content-section-body">
                <div class="cards">
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

        <pre style="display: none;">
            <?php
            echo '<h5>RISCOS</h5>';
            print_r( $indicadores_riscos );
            echo '<h5>AÇÕES</h5>';
            print_r( $indicadores_acoes );
            echo '<h5>APOIOS</h5>';
            print_r( $indicadores_apoios );
            ?>
        </pre>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('myChart').getContext('2d');
        const dados = [30, 50, 20]; // Valores absolutos
        const labels = ['Alagamento', 'Lixo', 'Outros'];

        // Calcula o total para conversão em percentual
        const total = dados.reduce((acc, valor) => acc + valor, 0);

        // Configuração do gráfico
        const myChart = new Chart(ctx, {
            type: 'doughnut',
            data: {
                labels: labels,
                datasets: [{
                    data: dados,
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
    </script>
</div>

