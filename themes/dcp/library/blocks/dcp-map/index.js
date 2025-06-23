import { setupMap } from './pins'
import { until } from '../../../assets/javascript/shared/wait'

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.dcp-map-block').forEach(async (block) => {
        const { apoios, riscos } = JSON.parse(block.querySelector('script').innerText)
        const tabsList = block.querySelector('.dcp-map-block__tabs')
        const tabs = [...block.querySelectorAll('.dcp-map-block__tab')]
        const map = block.querySelector('.jeomap')
        let mapLoaded = false

        let selectedCPT = tabsList.dataset.selected
        function selectCPT (cpt) {
            tabs.forEach((tab) => {
                selectedCPT = cpt
                if (tab.dataset.cpt === cpt) {
                    tab.classList.add('dcp-map-block__tab--selected')
                } else {
                    tab.classList.remove('dcp-map-block__tab--selected')
                }
            })
        }

        tabs.forEach((tab) => {
            tab.addEventListener('click', () => {
                const cpt = tab.dataset.cpt
                if (cpt !== selectedCPT) {
                    selectCPT(cpt)
                }
            })
        })

        await until(() => map.dataset.map_id)

        const jeoMap = globalThis.jeomaps[map.dataset.uui_id]

        await until(() => jeoMap.map)

        setupMap(jeoMap, riscos, apoios)
        mapLoaded = true
    })
})
