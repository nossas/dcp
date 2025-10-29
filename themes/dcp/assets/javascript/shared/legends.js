export function setupLegends(toggleLayer, selectedLayers) {
    const level5Toggle = document.querySelector('.dcp-map-legend__alagamento-nivel5')
    const level4Toggle = document.querySelector('.dcp-map-legend__alagamento-nivel4')

    if (level5Toggle) {
        const level5Img = level5Toggle.querySelector('img')
        const level5OnImg = level5Img.getAttribute('src')
        const level5OffImg = level5OnImg.replace('button-alagamento-on.svg', 'button-alagamento-off.svg')

        level5Toggle.addEventListener('click', () => {
            selectedLayers.alagamentoNivel5 = !selectedLayers.alagamentoNivel5
            level5Img.src = selectedLayers.alagamentoNivel5 ? level5OnImg : level5OffImg
            toggleLayer('risco')
        })

        level5Toggle.addEventListener('mouseenter', () => {
            level5Img.src = level5OffImg
        })

        level5Toggle.addEventListener('mouseleave', () => {
            level5Img.src = selectedLayers.alagamentoNivel5 ? level5OnImg : level5OffImg
        })
    }

    if (level4Toggle) {
        const level4Img = level4Toggle.querySelector('img')
        const level4OffImg = level4Img.getAttribute('src')
        const level4OnImg = level4OffImg.replace('button-alagamento-nivel4-off.svg', 'button-alagamento-nivel4-on.svg')

        level4Toggle.addEventListener('click', () => {
            selectedLayers.alagamentoNivel4 = !selectedLayers.alagamentoNivel4
            level4Img.src = selectedLayers.alagamentoNivel4 ? level4OnImg : level4OffImg
            toggleLayer('risco')
        })

        level4Toggle.addEventListener('mouseenter', () => {
            level4Img.src = level4OffImg
        })

        level4Toggle.addEventListener('mouseleave', () => {
            level4Img.src = selectedLayers.alagamentoNivel4 ? level4OnImg : level4OffImg
        })
    }
}
