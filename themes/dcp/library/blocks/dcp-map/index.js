import { setupMap } from '../../../assets/javascript/shared/pins'
import { until } from '../../../assets/javascript/shared/wait'

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.dcp-map-block').forEach(async (block) => {
        const { apoios, riscos } = JSON.parse(block.querySelector('script').innerText)
        const tabsList = block.querySelector('.dcp-map-block__tabs')
        const tabs = [...block.querySelectorAll('.dcp-map-block__tab')]
        const map = block.querySelector('.jeomap')
        let displayModal
        let toggleLayer = null

        const selectedCPT = { current: tabsList.dataset.selected }
        function selectCPT (cpt) {
            tabs.forEach((tab) => {
                selectedCPT.current = cpt
                if (tab.dataset.cpt === cpt) {
                    tab.classList.add('dcp-map-block__tab--selected')
                } else {
                    tab.classList.remove('dcp-map-block__tab--selected')
                }
                toggleLayer?.(cpt)
            })
        }

        tabs.forEach((tab) => {
            tab.addEventListener('click', () => {
                const cpt = tab.dataset.cpt
                if (cpt !== selectedCPT.current) {
                    selectCPT(cpt)
                }
            })
        })

        document.querySelectorAll('.risco a').forEach((link) => {
            link.addEventListener('click', (event) => {
                event.preventDefault()

                if (displayModal) {
                    const card = link.closest('.post-card')
                    const postId = Number(card.dataset.postId)
                    for (const risco of riscos) {
                        if (risco.ID == postId) {
                            displayModal(block, 'risco', {
                                icon: `risco-${risco.type}`,
                                ...risco,
                            })
                            break
                        }
                    }
                }
            })
        })

        await until(() => map.dataset.map_id)

        const jeoMap = globalThis.jeomaps[map.dataset.uui_id]

        await until(() => jeoMap.map)

        const mapContext = setupMap(jeoMap, block, riscos, apoios, selectedCPT)
        displayModal = mapContext.displayModal
        toggleLayer = mapContext.toggleLayer
    })
})
