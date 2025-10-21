export function setupLegends(toggleLayer, selectedLayers) {
    const alagamentoToggle = document.querySelector('.icon-alagamento')
    const lixoToggle = document.querySelector('.icon-lixo')

    if (alagamentoToggle) {
        const alagamentoOnImg = alagamentoToggle.getAttribute('src')
        const alagamentoOffImg = alagamentoOnImg.replace('button-alagamento-on.svg', 'button-alagamento-off.svg')

        alagamentoToggle.addEventListener('click', () => {
            selectedLayers.alagamento = !selectedLayers.alagamento
            alagamentoToggle.src = selectedLayers.alagamento ? alagamentoOnImg : alagamentoOffImg
            toggleLayer('risco')
        })

        alagamentoToggle.addEventListener('mouseenter', () => {
            alagamentoToggle.src = alagamentoOffImg
        })

        alagamentoToggle.addEventListener('mouseleave', () => {
            alagamentoToggle.src = selectedLayers.alagamento ? alagamentoOnImg : alagamentoOffImg
        })
    }

    if (lixoToggle) {
        const lixoOnImg = lixoToggle.getAttribute('src')
        const lixoOffImg = lixoOnImg.replace('button-lixo-on.svg', 'button-lixo-off.svg')

        lixoToggle.addEventListener('click', () => {
            selectedLayers.lixo = !selectedLayers.lixo
            lixoToggle.src = selectedLayers.lixo ? lixoOnImg : lixoOffImg
            toggleLayer('risco')
        })

        lixoToggle.addEventListener('mouseenter', () => {
            lixoToggle.src = lixoOffImg
        })

        lixoToggle.addEventListener('mouseleave', () => {
            lixoToggle.src = selectedLayers.lixo ? lixoOnImg : lixoOffImg
        })
    }
}
