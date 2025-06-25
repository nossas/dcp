<div id="dashboardRiscos" class="dashboard-content">
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

        <div id="riscosAprovacao" class="tabs__panels container--wide is-active">
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

            <div class="tabs__panel__content">
                <?php

                $riscos = get_query_var('riscos_id' );
                    $riscosDraft = new WP_Query([

                        'post_type'      => 'risco',
                        'post_status'    => 'draft',
                        'posts_per_page' => -1,
                        'orderby'        => 'date',
                        'order'          => 'DESC',

                    ]);

                    if( $riscosDraft->have_posts() ) :
                        while( $riscosDraft->have_posts() ) :
                            $riscosDraft->the_post(); ?>

                    <article class="post-card" style="display: none;">
                        <main class="post-card__content">

                            <div class="post-card__term">
                                <span class="post-card__taxonomia term-alagamento">Alagamento</span>
                                <div class="post-card__risco-meta"><?=get_the_date('H:i | d/m/Y')?> <em>( update : <?=get_the_modified_date('H:i | d/m/Y')?> )</em> </div>
                            </div>

                            <h3 class="post-card__title">
                                <span><?=the_title()?></span>
                            </h3>

                            <div class="post-card__excerpt-wrapped">
                                <div class="post-card__excerpt">
                                    <?=get_the_excerpt()?>
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
                        <pre style=" margin-top: 25px; border-radius: 15px; display: block; width: 100%; height: 75px; overflow: auto; background-color: #222; color: #fff; font-size: 10px;">
                            <?php
                            print_r( get_the_terms(get_the_ID(), 'situacao_de_risco') );
                            ?>
                        </pre>
                    </article>

                    <?php endwhile;

                    else : ?>

                    <div class="message-response">
                        <span class="tabs__panel-message">Nenhum risco foi publicado ainda.</span>
                    </div>

                    <?php endif; ?>

            </div>

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
        </div>

        <div id="riscosPublicados" class="tabs__panels" style="display: none;">
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

            <div class="tabs__panel__content">
                <?php
                $riscosPublish = new WP_Query([

                    'post_type'      => 'risco',
                    'post_status'    => 'publish',
                    'posts_per_page' => -1,
                    'orderby'        => 'date',
                    'order'          => 'DESC',

                ]);

                if( $riscosPublish->have_posts() ) :
                    while( $riscosPublish->have_posts() ) :
                        $riscosPublish->the_post();  ?>

                        <article class="post-card" style="display: none;">
                            <main class="post-card__content">

                                <div class="post-card__term">
                                    <span class="post-card__taxonomia term-alagamento">Alagamento</span>
                                    <div class="post-card__risco-meta"><?=get_the_date('H:i | d/m/Y')?> ( update : <?=get_the_modified_date('H:i | d/m/Y')?> )</div>
                                </div>

                                <h3 class="post-card__title">
                                    <span><?=the_title()?></span>
                                </h3>

                                <div class="post-card__excerpt-wrapped">
                                    <div class="post-card__excerpt">
                                        <?=get_the_excerpt()?>
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
                            <pre style=" margin-top: 25px; border-radius: 15px; display: block; width: 100%; height: 75px; overflow: auto; background-color: #222; color: #fff; font-size: 10px;">
                            <?php
                            print_r( get_the_terms(get_the_ID(), 'situacao_de_risco') );
                            ?>
                        </pre>
                        </article>

                    <?php endwhile;

                else : ?>
                    <div class="message-response">
                        <span class="tabs__panel-message">Nenhum risco foi publicado ainda.</span>
                    </div>
                <?php endif; ?>

            </div>

        </div>

        <div id="riscosArquivados" class="tabs__panels" style="display: none;">
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

            <div class="tabs__panel__content">
                <?php
                $riscosPending = new WP_Query([

                    'post_type'      => 'risco',
                    'post_status'    => 'pending',
                    'posts_per_page' => -1,
                    'orderby'        => 'date',
                    'order'          => 'DESC',

                ]);

                if( $riscosPending->have_posts() ) :
                    while( $riscosPending->have_posts() ) :
                        $riscosPending->the_post(); ?>

                        <article class="post-card" style="display: none;">
                            <main class="post-card__content">

                                <div class="post-card__term">
                                    <span class="post-card__taxonomia term-alagamento">Alagamento</span>
                                    <div class="post-card__risco-meta"><?=get_the_date('H:i | d/m/Y')?> ( update : <?=get_the_modified_date('H:i | d/m/Y')?> )</div>
                                </div>

                                <h3 class="post-card__title">
                                    <span><?=the_title()?></span>
                                </h3>

                                <div class="post-card__excerpt-wrapped">
                                    <div class="post-card__excerpt">
                                        <?=get_the_excerpt()?>
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
                            <pre style=" margin-top: 25px; border-radius: 15px; display: block; width: 100%; height: 75px; overflow: auto; background-color: #222; color: #fff; font-size: 10px;">
                            <?php
                            print_r( get_the_terms(get_the_ID(), 'situacao_de_risco') );
                            ?>
                        </pre>
                        </article>
                    <?php endwhile;

                else : ?>
                    <div class="message-response">
                        <span class="tabs__panel-message">Nenhum risco foi publicado ainda.</span>
                    </div>
                <?php endif; ?>

            </div
        </div>
    </div>
</div>

