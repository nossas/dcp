import { setupLegends } from '../../../assets/javascript/shared/legends'
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
        const selectedLayers = { alagamento: [null, true, true, true, true, true] }

        function selectCPT(cpt) {
            tabs.forEach((tab) => {
                const isSelected = tab.dataset.cpt === cpt;
                selectedCPT.current = cpt;
                tab.ariaSelected = isSelected ? 'true' : 'false';
                tab.classList.toggle('dcp-map-block__tab--selected', isSelected);
            });

            const apoioLegendDesktop = document.querySelector('.dcp-map-legend-apoio');
            const apoioLegendMobile = document.querySelector('.dcp-map-legend-apoio__mobile');
            const riscoLegend = document.querySelector('.dcp-map-legend-risco');
            const riscoLegendMobile = document.querySelector('.dcp-map-legend-risco__mobile'); // caso queira versão mobile também

            if (apoioLegendDesktop) {
                apoioLegendDesktop.style.display = (cpt === 'apoio') ? 'block' : 'none';
            }

            if (apoioLegendMobile) {
                const isMobile = window.matchMedia('(max-width: 768px)').matches;
                apoioLegendMobile.style.display = (cpt === 'apoio' && isMobile) ? 'block' : 'none';
            }

            if (riscoLegend) {
                riscoLegend.style.display = (cpt === 'risco') ? 'block' : 'none';
            }

            if (riscoLegendMobile) {
                const isMobile = window.matchMedia('(max-width: 768px)').matches;
                riscoLegendMobile.style.display = (cpt === 'risco' && isMobile) ? 'block' : 'none';
            }

            toggleLayer?.(cpt);
        }

        tabs.forEach((tab) => {
            tab.addEventListener('click', () => {
                const cpt = tab.dataset.cpt
                if (cpt !== selectedCPT.current) {
                    selectCPT(cpt)
                }
            })
        })

        document.querySelectorAll('.risco .post-card a').forEach((link) => {
            link.addEventListener('click', (event) => {
                event.preventDefault()

                if (displayModal) {
                    const card = link.closest('.post-card')
                    const postId = Number(card.dataset.postId)
                    for (const risco of riscos) {
                        if (risco.ID == postId) {
                            displayModal(block, {
                                kind: 'risco',
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

        const mapContext = setupMap(jeoMap, block, riscos, apoios, selectedCPT, selectedLayers)
        displayModal = mapContext.displayModal
        toggleLayer = mapContext.toggleLayer
        setupLegends(toggleLayer, selectedLayers)
    })
})
