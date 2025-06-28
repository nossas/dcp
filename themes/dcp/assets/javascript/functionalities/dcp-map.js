import { setupMap } from '../shared/pins'
import { until } from '../shared/wait'

document.addEventListener('DOMContentLoaded', async () => {
    const container = document.querySelector('.dcp-map')
    const { apoios, riscos } = JSON.parse(container.querySelector('script').innerText)
    const tabsList = container.querySelector('.dcp-map__tabs')
    const tabs = [...container.querySelectorAll('.dcp-map__tab')]
    const map = container.querySelector('.jeomap')
    let toggleLayer = null

    let selectedCPT = { current: tabsList.dataset.selected }

    const query = new URLSearchParams(location.search)
    if (query.has('tab')) {
        const tab = query.get('tab')
        if (['apoio', 'risco'].includes(tab)) {
            selectCPT(tab)
        }
    }

    function updateSearchParams (tab) {
        const url = new URL(location.href)
        url.searchParams.set('tab', tab)
        window.history.replaceState(null, '', url.toString())
    }

    function selectCPT (cpt) {
        tabs.forEach((tab) => {
            selectedCPT.current = cpt
            if (tab.dataset.cpt === cpt) {
                tab.classList.add('dcp-map__tab--selected')
            } else {
                tab.classList.remove('dcp-map__tab--selected')
            }
            toggleLayer?.(cpt)
        })
        updateSearchParams(cpt)
    }

    tabs.forEach((tab) => {
        tab.addEventListener('click', () => {
            const cpt = tab.dataset.cpt
            if (cpt !== selectedCPT.current) {
                selectCPT(cpt)
            }
        })
    })

    await until(() => map.dataset.map_id)

    const jeoMap = globalThis.jeomaps[map.dataset.uui_id]

    await until(() => jeoMap.map)

    const mapContext = setupMap(jeoMap, container, riscos, apoios, selectedCPT)
    toggleLayer = mapContext.toggleLayer

    jeoMap.element.style.height = (window.innerHeight - container.offsetTop) + 'px'
    container.querySelector('canvas').style.height = jeoMap.element.style.height
})
