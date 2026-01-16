import { queryHoveredHighlightLayer, toggleMapboxHighlightLayers, toggleMapboxLayers } from './pins'

function updateLevelAnnouncer (level, event) {
    const { levelLabels, themeAssets } = globalThis.hl_dcp_map_data

    const announcer = document.querySelector('.dcp-map-selected-layer')
    if (announcer) {
        if (level) {
            const img = announcer.querySelector('img')
            const span = announcer.querySelector('span')

            announcer.dataset.level = level
            img.src = `${themeAssets}/assets/images/alagamento-nivel-${level}.svg`
            img.alt = levelLabels[level]
            span.innerText = levelLabels[level]
            announcer.style.left = event.point.x + 'px'
            announcer.style.top = event.point.y + 'px'
            announcer.style.display = ''
        } else {
            announcer.style.display = 'none'
        }
    }
}

export function setupLegends(jeoMap, selectedLayers) {
    const map = jeoMap.map

    map.on('mousemove', (event) => {
        const selectedLevel = queryHoveredHighlightLayer(map, event)
        toggleMapboxHighlightLayers(map, selectedLevel)
        updateLevelAnnouncer(selectedLevel, event)
    })

    for (let level = 1; level <= 5; level++) {
        const levelToggle = document.querySelector(`.dcp-map-legend__alagamento-nivel${level}`)

        if (levelToggle) {
            levelToggle.addEventListener('click', () => {
                selectedLayers.alagamento[level] = !selectedLayers.alagamento[level]
                levelToggle.classList.toggle('enabled', selectedLayers.alagamento[level])
                toggleMapboxLayers(map, selectedLayers)
            })

            levelToggle.addEventListener('mouseover', () => toggleMapboxHighlightLayers(map, level))
            levelToggle.addEventListener('mouseout', () => toggleMapboxHighlightLayers(map, false))

            const levelToggleButton = levelToggle.querySelector('button')
            if (levelToggleButton) {
                levelToggleButton.addEventListener('focus', () => toggleMapboxHighlightLayers(map, level))
                levelToggleButton.addEventListener('blur', () => toggleMapboxHighlightLayers(map, false))
            }
        }
    }
}
