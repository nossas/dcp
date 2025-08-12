<?php

namespace hacklabr\dashboard;

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
                        <div class="is-counter">
                            <h3>00</h3>
                            <p>.</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="is-counter">
                            <h3>00</h3>
                            <p>Novas ações</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="is-counter">
                            <h3>0</h3>
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
                            <h3>00</h3>
                            <p>Riscos mapeados</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="is-counter">
                            <h3>00</h3>
                            <p>Pontos de apoio</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="is-counter">
                            <h3>0</h3>
                            <p>Ações realizadas</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="is-counter">
                            <h4>Burado do Lacerda</h4>
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

        <pre>
            <?php print_r( indicadores_riscos( ) ); ?>
            <?php print_r( indicadores_acoes( ) ); ?>
            <?php print_r( indicadores_apoio( ) ); ?>
        </pre>
    </div>
</div>

