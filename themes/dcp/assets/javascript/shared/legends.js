export function setupLegends(toggleLayer, selectedLayers) {
    for (let level = 1; level <= 5; level++) {
        const levelToggle = document.querySelector(`.dcp-map-legend__alagamento-nivel${level}`)

        if (levelToggle) {
            levelToggle.addEventListener('click', () => {
                selectedLayers.alagamento[level] = !selectedLayers.alagamento[level]
                levelToggle.classList.toggle('enabled', selectedLayers.alagamento[level])
                toggleLayer('risco')
            })
        }
    }
}
