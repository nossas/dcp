<div class="situacao-atual__container">
    <div class="situacao-atual__header">
        <h1 class="situacao-atual__title"><?= __('Situação atual') ?></h1>
        <a class="situacao-atual__wpp-btn" href=""><?= __('Notificar moradores') ?></a>
    </div>

    <div class="situacao-atual">
        <div class="situacao-atual__content-title">
            <h2 class="situacao-atual__content-title-text"><?= __('Todas recomendações') ?></h2>
        </div>

        <div class="situacao-atual__grid">

            <div class="situacao-atual__card">
                <div class="situacao-atual__card-header">
                    <h4 class="situacao-atual__card-title"><?= __('Normal | Chuva') ?></h4>
                    <a class="situacao-atual__edit-btn"><?= __('Editar') ?></a>
                </div>
                <div class="situacao-atual__icon"></div>
                <div class="situacao-atual__card-content">
                    <div class="situacao-atual__card-text">
                        <div class="situacao-atual__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M18 0.00195312C18.7956 0.00195313 19.5585 0.318299 20.1211 0.880859C20.6837 1.44345 21 2.20633 21 3.00195V21.002C21 21.7976 20.6837 22.5604 20.1211 23.123C19.5585 23.6857 18.7956 24.002 18 24.002H6C5.20438 24.0019 4.4415 23.6856 3.87891 23.123C3.31635 22.5604 3 21.7976 3 21.002V3.00195C3.00002 2.20633 3.31632 1.44345 3.87891 0.880859C4.4415 0.318269 5.20438 0.00197384 6 0.00195312H18ZM12 18.002C8.06555 18.002 5.78099 19.2413 4.5 20.6348V21.002C4.5 21.3997 4.6582 21.7812 4.93945 22.0625C5.22074 22.3438 5.6022 22.5019 6 22.502H18C18.3978 22.502 18.7792 22.3438 19.0605 22.0625C19.3419 21.7812 19.5 21.3998 19.5 21.002V20.6348C18.219 19.2398 15.9345 18.002 12 18.002ZM12 7.50195C10.8066 7.50197 9.66225 7.97642 8.81836 8.82031C7.97446 9.66421 7.50002 10.8085 7.5 12.002C7.5 13.1954 7.97449 14.3397 8.81836 15.1836C9.66225 16.0275 10.8066 16.5019 12 16.502C13.1935 16.502 14.3377 16.0275 15.1816 15.1836C16.0256 14.3397 16.5 13.1954 16.5 12.002C16.5 10.8085 16.0255 9.66421 15.1816 8.82031C14.3377 7.97645 13.1934 7.50195 12 7.50195ZM9.75 3.00195C9.55112 3.00197 9.36036 3.08105 9.21973 3.22168C9.07909 3.36231 9.00002 3.55307 9 3.75195C9 3.95083 9.07912 4.14158 9.21973 4.28223C9.36036 4.42286 9.55112 4.50193 9.75 4.50195H14.25C14.4489 4.50195 14.6396 4.42288 14.7803 4.28223C14.9209 4.14157 15 3.95087 15 3.75195C15 3.55307 14.9209 3.36231 14.7803 3.22168C14.6396 3.08108 14.4489 3.00195 14.25 3.00195H9.75Z" fill="#50B15C" />
                            </svg>
                        </div>
                        <p><?= __('Revise seu kit de emergência.') ?></p>
                    </div>
                    <div class="situacao-atual__card-text">
                        <div class="situacao-atual__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M11.9996 0.00195312C13.0345 0.00195312 14.7638 0.40136 16.3912 0.84082C17.845 1.24091 19.2895 1.67573 20.7223 2.14551C21.135 2.28205 21.5009 2.53214 21.7779 2.86719C22.0549 3.2023 22.2322 3.60894 22.2887 4.04004C23.1826 10.7555 21.1074 15.7329 18.5904 19.0254C17.5232 20.4337 16.2504 21.6735 14.815 22.7041C14.3187 23.0608 13.7925 23.3744 13.2428 23.6416C12.8213 23.8396 12.3716 24.002 11.9996 24.002C11.6276 24.0019 11.1764 23.8396 10.7564 23.6416C10.3004 23.4271 9.76318 23.1136 9.18418 22.7041C7.7489 21.6734 6.47607 20.4336 5.40879 19.0254C2.89179 15.7329 0.817524 10.7555 1.71152 4.04004C1.76805 3.60926 1.9444 3.20296 2.22129 2.86816C2.49822 2.5334 2.86443 2.28376 3.27695 2.14746C4.26395 1.82496 5.94301 1.2918 7.60801 0.841797C9.23536 0.399347 10.9647 0.00197309 11.9996 0.00195312ZM12.0025 15.002C11.6048 15.002 11.2233 15.1601 10.942 15.4414C10.6607 15.7227 10.5026 16.1042 10.5025 16.502C10.5025 16.8997 10.6608 17.2812 10.942 17.5625C11.2233 17.8438 11.6048 18.0019 12.0025 18.002C12.4003 18.002 12.7818 17.8438 13.0631 17.5625C13.3444 17.2812 13.5025 16.8998 13.5025 16.502C13.5025 16.1042 13.3444 15.7227 13.0631 15.4414C12.7818 15.1602 12.4003 15.002 12.0025 15.002ZM11.9996 5.99414C11.8094 5.99415 11.6216 6.03497 11.4479 6.1123C11.274 6.18972 11.1181 6.30289 10.9908 6.44434C10.8637 6.58565 10.7673 6.75182 10.7086 6.93262C10.6499 7.1135 10.6301 7.30502 10.65 7.49414L11.1744 12.7549C11.1921 12.9615 11.2873 13.1546 11.44 13.2949C11.5927 13.4349 11.7924 13.5127 11.9996 13.5127C12.207 13.5127 12.4074 13.4352 12.5602 13.2949C12.7129 13.1546 12.8072 12.9615 12.8248 12.7549L13.3492 7.49414C13.3691 7.30501 13.3493 7.11351 13.2906 6.93262C13.2319 6.75181 13.1356 6.58566 13.0084 6.44434C12.8812 6.30301 12.726 6.18972 12.5523 6.1123C12.3785 6.03489 12.1899 5.99414 11.9996 5.99414Z" fill="#50B15C" />
                            </svg>
                        </div>
                        <p><?= __('Conheça os pontos de apoio e saiba onde encontrar ajuda no território se precisar.') ?></p>
                    </div>
                    <div class="situacao-atual__card-text">
                        <div class="situacao-atual__icon">
                            <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                                <path d="M15 0.00195312C15.1989 0.00195313 15.3896 0.0810765 15.5303 0.22168C15.6709 0.362314 15.75 0.553069 15.75 0.751953V4.50195H17.25C17.4489 4.50195 17.6396 4.58108 17.7803 4.72168C17.9209 4.86231 18 5.05307 18 5.25195V9.75195C18 11.1443 17.4465 12.4793 16.4619 13.4639C15.4773 14.4484 14.1424 15.002 12.75 15.002C12.747 15.6529 12.7354 16.2695 12.6904 16.832C12.6289 17.6029 12.5012 18.3363 12.2148 18.9678C11.9244 19.6356 11.4095 20.181 10.7598 20.5098C10.0878 20.8518 9.2535 21.002 8.25 21.002C6.75305 21.002 5.83505 21.4971 5.28906 22.0791C4.79614 22.5997 4.51476 23.2852 4.5 24.002H3C3.00002 23.078 3.34785 21.9513 4.19531 21.0498C5.0608 20.1318 6.39154 19.502 8.25 19.502C9.12145 19.502 9.69315 19.3703 10.0771 19.1738C10.4386 18.9894 10.6767 18.7235 10.8477 18.3486C11.0291 17.9497 11.1379 17.4172 11.1934 16.7139C11.2339 16.2039 11.246 15.6365 11.249 15.002C9.8569 15.0016 8.52135 14.4484 7.53711 13.4639C6.55289 12.4793 6 11.1441 6 9.75195V5.25195C6.00002 5.05307 6.07909 4.86231 6.21973 4.72168C6.36036 4.58105 6.55112 4.50197 6.75 4.50195H8.25V0.751953C8.25002 0.553069 8.32909 0.362314 8.46973 0.22168C8.61036 0.0810458 8.80112 0.00197384 9 0.00195312C9.19888 0.00195313 9.38963 0.0810763 9.53027 0.22168C9.67091 0.362314 9.74998 0.553069 9.75 0.751953V4.50195H14.25V0.751953C14.25 0.553069 14.3291 0.362314 14.4697 0.22168C14.6104 0.081046 14.8011 0.00197384 15 0.00195312Z" fill="#50B15C" />
                            </svg>
                        </div>
                        <p><?= __('Acompanhe mutirões e outras atividades na sua área.') ?></p>
                    </div>
                </div>
            </div>

            <div class="situacao-atual__card">
                <div class="situacao-atual__card-header">
                    <h4 class="situacao-atual__card-title"><?= __('Normal | Ensolarado') ?></h4>
                    <a class="situacao-atual__edit-btn"><?= __('Editar') ?></a>
                </div>
                <div class="situacao-atual__icon"></div>
                <div class="situacao-atual__card-content">
                    <div class="situacao-atual__icon"></div>
                    <p><?= __('Revise seu kit de emergência.') ?></p>
                    <div class="situacao-atual__icon"></div>
                    <p><?= __('Conheça os pontos de apoio e saiba onde encontrar ajuda no território se precisar.') ?></p>
                    <div class="situacao-atual__icon"></div>
                    <p><?= __('Acompanhe mutirões e outras atividades na sua área.') ?></p>
                </div>
            </div>

            <div class="situacao-atual__card">
                <div class="situacao-atual__card-header">
                    <h4 class="situacao-atual__card-title"><?= __('Atenção | Chuvas') ?></h4>
                    <a class="situacao-atual__edit-btn"><?= __('Editar') ?></a>
                </div>
                <div class="situacao-atual__icon"></div>
                <div class="situacao-atual__card-content">
                    <div class="situacao-atual__icon"></div>
                    <p><?= __('Coloque os documentos importantes e receitas no kit de sobrevivência.') ?></p>
                    <div class="situacao-atual__icon"></div>
                    <p><?= __('Avise vizinhos sobre o risco e compartilhe as recomendações.') ?></p>
                    <div class="situacao-atual__icon"></div>
                    <p><?= __('Tire os aparelhos eletrônicos da tomada.') ?></p>
                </div>
            </div>

            <div class="situacao-atual__card">
                <div class="situacao-atual__card-header">
                    <h4 class="situacao-atual__card-title"><?= __('Atenção | Calor') ?></h4>
                    <a class="situacao-atual__edit-btn"><?= __('Editar') ?></a>
                </div>
                <div class="situacao-atual__icon"></div>
                <div class="situacao-atual__card-content">
                    <div class="situacao-atual__icon"></div>
                    <p><?= __('Coloque os documentos importantes e receitas no kit de sobrevivência.') ?></p>
                    <div class="situacao-atual__icon"></div>
                    <p><?= __('Avise vizinhos sobre o risco e compartilhe as recomendações.') ?></p>
                    <div class="situacao-atual__icon"></div>
                    <p><?= __('Tire os aparelhos eletrônicos da tomada.') ?></p>
                </div>
            </div>

            <div class="situacao-atual__card">
                <div class="situacao-atual__card-header">
                    <h4 class="situacao-atual__card-title"><?= __('Perigo | Chuvas fortes') ?></h4>
                    <a class="situacao-atual__edit-btn"><?= __('Editar') ?></a>
                </div>
                <div class="situacao-atual__icon"></div>
                <div class="situacao-atual__card-content">
                    <div class="situacao-atual__icon"></div>
                    <p><?= __('Coloque os documentos importantes e receitas no kit de sobrevivência.') ?></p>
                    <div class="situacao-atual__icon"></div>
                    <p><?= __('Avise vizinhos sobre o risco e compartilhe as recomendações.') ?></p>
                    <div class="situacao-atual__icon"></div>
                    <p><?= __('Tire os aparelhos eletrônicos da tomada.') ?></p>
                </div>
            </div>

            <div class="situacao-atual__card">
                <div class="situacao-atual__card-header">
                    <h4 class="situacao-atual__card-title"><?= __('Perigo | Calor Extremo') ?></h4>
                    <a class="situacao-atual__edit-btn"><?= __('Editar') ?></a>
                </div>
                <div class="situacao-atual__icon"></div>
                <div class="situacao-atual__card-content">
                    <div class="situacao-atual__icon"></div>
                    <p><?= __('Coloque os documentos importantes e receitas no kit de sobrevivência.') ?></p>
                    <div class="situacao-atual__icon"></div>
                    <p><?= __('Avise vizinhos sobre o risco e compartilhe as recomendações.') ?></p>
                    <div class="situacao-atual__icon"></div>
                    <p><?= __('Tire os aparelhos eletrônicos da tomada.') ?></p>
                </div>
            </div>
        </div>
    </div>
</div>
