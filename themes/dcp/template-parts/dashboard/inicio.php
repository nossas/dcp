<?php

namespace hacklabr\dashboard;

?>
<div id="dashboardInicio" class="dashboard-content">
    <div class="dashboard-content-home">
        <header class="dashboard-content-header">
            <h1>Olá, <?=wp_get_current_user()->display_name?></h1>
            <p><?=get_user_meta( wp_get_current_user()->ID, 'description', true )?></p>
        </header>

        <div class="dashboard-content-section">

            <header class="dashboard-content-section-header">
                <h2>Situação Atual</h2>
                <a class="button" href="<?=get_dashboard_url( 'situacao_atual' )?>">
                    Editar
                    <iconify-icon icon="bi:chevron-right"></iconify-icon>
                </a>
            </header>

            <div class="dashboard-content-section-body">

                <div class="header-cards">
                    <div class="card">
                        <header>
                            <figure>
                                <img src="<?=get_template_directory_uri()?>/assets/images/icones/marrom-sofa.svg">
                            </figure>
                            <p> <strong>ATENÇÃO</strong> </p>
                            <p>Alagamento em algumas áreas do Jacarezinho. Evite locais de risco.</p>
                        </header>
                        <article>
                            <p>
                                Rio de Janeiro:
                                <strong>ESTÁGIO 3</strong>
                                <span class="is-separator">|</span>
                                <strong>32º</strong>
                                <span class="is-separator">・</span>
                                Chuvas medianas
                            </p>
                            <p>Última atualização: 15:30 <span class="is-separator">・</span> 15/01/25</p>
                        </article>
                    </div>
                    <div class="card">
                        <div class="is-counter">
                            <h3>0</h3>
                            <p>Novos Relatos</p>
                        </div>
                    </div>
                    <div class="card">
                        <div class="is-counter">
                            <h3>0</h3>
                            <p>Estágio do COR</p>
                        </div>
                    </div>
                </div>

            </div>

        </div>


        <div class="dashboard-content-section">

            <div class="dashboard-content-section-header">
                <h2>Relatos de Risco Aguardando Avaliação <span>(0)</span> </h2>
                <a class="button" href="<?=get_dashboard_url( 'riscos' )?>">
                    Gerenciar
                    <iconify-icon icon="bi:chevron-right"></iconify-icon>
                </a>
            </div>

            <div class="dashboard-content-section-body">
                <div class="dashboard-content-tabs tabs">
                    <div id="riscosAprovacao" class="tabs__panels container--wide" style=" display: block !important; ">
                        <?php echo get_template_part('template-parts/dashboard/ui/skeleton' ); ?>
                        <div class="tabs__panel__content dashboard-content-cards " style="display: block;">
                            <?php $get_riscos = get_riscos_by_status( 'draft' );  if( $get_riscos[ 'riscos' ]->have_posts() ) :
                                while( $get_riscos[ 'riscos' ]->have_posts() ) :
                                    $get_riscos[ 'riscos' ]->the_post();
                                    $pod = pods( 'risco', get_the_ID() ); ?>

                                    <article class="post-card is-draft" style="display: none;">
                                        <main class="post-card__content">
                                            <div class="post-card__term">
                                                <?php
                                                $get_terms = get_the_terms( get_the_ID(), 'situacao_de_risco' );
                                                if( !empty( $get_terms ) && !is_wp_error( $get_terms ) ) {
                                                    risco_badge_category( $get_terms[0]->slug, $get_terms[0]->name );
                                                } else {
                                                    risco_badge_category( 'sem-categoria', 'NENHUMA CATEGORIA ADICIONADA' );
                                                }
                                                ?>
                                                <div class="post-card__risco-meta"><?=wp_date( 'H:i | d/m/Y', strtotime( $pod->field('data_e_horario') ))?></div>
                                            </div>

                                            <h3 class="post-card__title">
                                                <span><?=$pod->field( 'endereco' )?></span>
                                            </h3>

                                            <div class="post-card__excerpt-wrapped">
                                                <p class="text-excerpt">
                                                    <?php

                                                    //TODO: REFACTORY P/ UTILS
                                                    $descricao = $pod->field( 'descricao' );

                                                    if ( strlen( $descricao ) <= 125 ) {
                                                        echo $descricao;
                                                    } else {
                                                        echo substr( $descricao, 0, 125 ) . '<a class="read-more" href="#/">Ver mais</a>';
                                                        echo '<span class="read-more-full">' . substr( $descricao, 125 ) . '</span>';
                                                    }
                                                    //TODO: REFACTORY P/ UTILS

                                                    ?>

                                                </p>
                                            </div>

                                            <div class="post-card__see-more">
                                                <div></div>
                                                <div>
                                                    <a class="is-aprovacao button" href="<?=get_dashboard_url( 'riscos-single', [ 'risco_id' => get_the_ID() ] )?>">
                                                        Avaliar
                                                        <iconify-icon icon="bi:chevron-right"></iconify-icon>
                                                    </a>
                                                </div>
                                            </div>

                                        </main>
                                    </article>

                                <?php endwhile;

                            else : ?>

                                <div class="message-response">
                                    <span class="tabs__panel-message">Nenhum risco foi publicado ainda.</span>
                                </div>

                            <?php endif; ?>

                        </div>
                        <?php if( $get_riscos[ 'pagination' ] ) : ?>
                            <div class="tabs__panel__pagination">
                                <ol class="">
                                    <li>
                                        <a href="" class="button is-previous is-disabled">
                                            <iconify-icon icon="bi:chevron-left"></iconify-icon>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="" class="button is-active">
                                            <span>1</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="" class="button">
                                            <span>3</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="" class="button">
                                            <span>4</span>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="" class="button is-next">
                                            <iconify-icon icon="bi:chevron-right"></iconify-icon>
                                        </a>
                                    </li>
                                </ol>
                            </div>
                        <?php endif; ?>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>

