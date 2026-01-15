import { toggleMapboxLayer, toggleMapboxLayers } from './pins'

function hideHighlightLayers (map, level) {
    toggleMapboxLayer(map, `areas alagadas nivel ${level} contorno`, false)
}

function showHighlithLayers (map, level) {
    toggleMapboxLayer(map, `areas alagadas nivel ${level} contorno`, true)
}

export function setupLegends(jeoMap, selectedLayers) {
    const map = jeoMap.map

    for (let level = 1; level <= 5; level++) {
        const levelToggle = document.querySelector(`.dcp-map-legend__alagamento-nivel${level}`)

        if (levelToggle) {
            levelToggle.addEventListener('click', () => {
                selectedLayers.alagamento[level] = !selectedLayers.alagamento[level]
                levelToggle.classList.toggle('enabled', selectedLayers.alagamento[level])
                toggleMapboxLayers(map, selectedLayers)
            })

            levelToggle.addEventListener('mouseover', () => showHighlithLayers(map, level))
            levelToggle.addEventListener('mouseout', () => hideHighlightLayers(map, level))

            const levelToggleButton = levelToggle.querySelector('button')
            if (levelToggleButton) {
                levelToggleButton.addEventListener('focus', () => showHighlithLayers(map, level))
                levelToggleButton.addEventListener('blur', () => hideHighlightLayers(map, level))
            }
        }
    }
}
