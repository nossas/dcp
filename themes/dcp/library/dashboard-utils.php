<?php


function risco_badge_category( $slug = 'default', $label = 'CATEGORIA GERAL' ) {

    switch ( $slug ) {

        case 'alagamento':
            echo '<span class="post-card__taxonomia term-alagamento is-' . $slug . '">'
                . '<iconify-icon icon="bi:water"></iconify-icon>'
                . '<span>' . $label . '</span>'
                . '</span>';
            break;

        case 'lixo':
            echo '<span class="post-card__taxonomia term-alagamento is-' . $slug . '">'
                . '<iconify-icon icon="bi:trash3-fill"></iconify-icon>'
                . '<span>' . $label . '</span>'
                . '</span>';
            break;
        case 'outros':
            echo '<span class="post-card__taxonomia term-alagamento is-' . $slug . '">'
                . '<iconify-icon icon="bi:moisture"></iconify-icon>'
                . '<span>' . $label . '</span>'
                . '</span>';
            break;

        default:

            echo '<span class="post-card__taxonomia term-alagamento is-' . $slug . '">'
                . '<iconify-icon icon="bi:life-preserver"></iconify-icon>'
                . '<span>' . $label . '</span>'
                . '</span>';

            break;
    }

}
