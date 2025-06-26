<?php

    $get_riscos_dashboard = [

        'riscosAprovacao' => [
            'is_active' => true,
            'pagination' => false,
            'riscos' => new WP_Query([

                'post_type'      => 'risco',
                'post_status'    => 'draft',
                'posts_per_page' => -1,
                'orderby'        => 'date',
                'order'          => 'DESC',

            ])
        ],
        'riscosPublicados' =>[
            'is_active' => false,
            'pagination' => false,
            'riscos' => new WP_Query([

                'post_type'      => 'risco',
                'post_status'    => 'publish',
                'posts_per_page' => -1,
                'orderby'        => 'date',
                'order'          => 'DESC',

            ])
        ],
        'riscosArquivados' => [
            'is_active' => false,
            'pagination' => false,
            'riscos' => new WP_Query([

                'post_type'      => 'risco',
                'post_status'    => 'pending',
                'posts_per_page' => -1,
                'orderby'        => 'date',
                'order'          => 'DESC',

            ])
        ],
    ];




?><div id="dashboardRiscos" class="dashboard-content">
    <header class="dashboard-content-header">
        <h1>RISCOS MAPEADOS</h1>
        <a href="./?ver=riscos-adicionar" class="button">
            <iconify-icon icon="bi:plus-lg"></iconify-icon>
            <span>Adicionar Risco</span>
        </a>
    </header>
    <div class="dashboard-content-tabs tabs">
        <div class="tabs__header">
            <a href="#aprovacao" class="is-active">AGUARDANDO APROVAÇÃO <span>(9)</span> </a>
            <a href="#publicados">PUBLICADOS</a>
            <a href="#arquivados">ARQUIVADOS</a>
        </div>

        <div class="tabs__panels-container"></div>

        <?php

            foreach ($get_riscos_dashboard as $panel_id => $value) {

                $is_active = $value['is_active'] ? 'is-active' : '';

                ?>

                    <div id="<?=$panel_id?>" class="tabs__panels container--wide <?=$is_active?>">
                        <?php echo get_template_part('template-parts/dashboard/ui/skeleton' ); ?>
                        <div class="tabs__panel__content">
                            <?php
                            if( $value[ 'riscos' ]->have_posts() ) :
                                while( $value[ 'riscos' ]->have_posts() ) :
                                    $value[ 'riscos' ]->the_post();
                                    $pod = pods( 'risco', get_the_ID() );
                                    ?>

                                    <article class="post-card" style="display: none;">
                                        <main class="post-card__content">
                                            <pre>

                                            </pre>
                                            <div class="post-card__term">
                                                <?php
                                                $get_terms = get_the_terms( get_the_ID(), 'situacao_de_risco' );
                                                if( !empty( $get_terms ) && !is_wp_error( $get_terms ) ) {
                                                    risco_badge_category( $get_terms[0]->slug, $get_terms[0]->name );
                                                } else {
                                                    risco_badge_category( 'sem-categoria', 'NENHUMA CARTEGORIA ADICIONADA' );
                                                }
                                                ?>
                                                <div class="post-card__risco-meta"><?=date( 'H:i | d/m/Y', strtotime( $pod->field('data_e_horario') ))?></div>
                                            </div>

                                            <h3 class="post-card__title">
                                                <span><?=$pod->field( 'endereco' )?></span>
                                            </h3>

                                            <div class="post-card__excerpt-wrapped">
                                                <div class="post-card__excerpt">
                                                    <?=$pod->field( 'descricao' )?>
                                                    <a href="#/">Ver mais</a>
                                                </div>
                                            </div>

                                            <div class="post-card__see-more">
                                                <a href="./?ver=riscos-single&risco_id=<?=get_the_ID()?>" class="button">
                                                    <span>Avaliar</span>
                                                    <iconify-icon icon="bi:chevron-right"></iconify-icon>
                                                </a>
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
                        <?php if( $value[ 'pagination' ] ) : ?>
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
                <?php
            }

        ?>

    </div>
</div>

