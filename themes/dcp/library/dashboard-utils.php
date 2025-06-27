<?php


function risco_badge_category( $slug = 'default', $label = 'CATEGORIA GERAL', $class = 'post-card__taxonomia term-alagamento' ) {

    //TODO:  REFATORY P/ COMPONENTE (MELHOR LÓGICA)
    echo '<span class=" ' . $class . ' is-' . $slug . '">';

    switch ( $slug ) {

        case 'alagamento':
            echo '<iconify-icon icon="bi:water"></iconify-icon>';
            break;

        case 'lixo':
            echo '<iconify-icon icon="bi:trash3-fill"></iconify-icon>';
            break;
        case 'outros':
            echo '<iconify-icon icon="bi:moisture"></iconify-icon>';
            break;

        default:
            echo '<iconify-icon icon="bi:life-preserver"></iconify-icon>';
            break;
    }

    echo '<span>' . $label . '</span>' . '</span>';

}
