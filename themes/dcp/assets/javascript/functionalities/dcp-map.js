import { setupLegends } from '../shared/legends';
import { setupMap } from '../shared/pins';
import { until } from '../shared/wait';

document.addEventListener('DOMContentLoaded', async () => {
    const container = document.querySelector('.dcp-map') || document.querySelector('.dcp-map-block');
    if (!container) return;

    const isBlockContext = container.classList.contains('dcp-map-block');

    const tabSelector = isBlockContext ? '.dcp-map-block__tab' : '.dcp-map__tab';
    const selectedTabClass = isBlockContext ? 'dcp-map-block__tab--selected' : 'dcp-map__tab--selected';
    const tabsContainerSelector = isBlockContext ? '.dcp-map-block__tabs' : '.dcp-map__tabs';
    const { apoios, riscos } = JSON.parse(container.querySelector('script').innerText);
    const tabsList = container.querySelector(tabsContainerSelector);
    const tabs = [...container.querySelectorAll(tabSelector)];
    const map = container.querySelector('.jeomap');
    let switchView = null;

    if (!tabsList || tabs.length === 0) return;

    function updateSearchParams(tab) {
        const url = new URL(location.href);
        url.searchParams.set('tab', tab);
        window.history.replaceState(null, '', url.toString());
    }

function selectCPT(cpt) {
        tabs.forEach((tab) => {
            const isSelected = tab.dataset.cpt === cpt;
            tab.ariaSelected = isSelected ? 'true' : 'false';
            tab.classList.toggle(selectedTabClass, isSelected);
        });

        switchView?.(cpt);
        updateSearchParams(cpt);
    }

    let initialTab = tabsList.dataset.selected;
    const query = new URLSearchParams(location.search);
    if (query.has('tab') && ['apoio', 'risco'].includes(query.get('tab'))) {
        initialTab = query.get('tab');
    }

    selectCPT(initialTab);

    await until(() => map.dataset.map_id);
    const jeoMap = globalThis.jeomaps[map.dataset.uui_id];
    await until(() => jeoMap.map);

    const selectedCPT = { current: initialTab };
    const selectedLayers = { alagamento: [null, true, true, true, true, true] }
    const mapContext = setupMap(jeoMap, container, riscos, apoios, selectedCPT, selectedLayers);
    switchView = mapContext.switchView;
    setupLegends(jeoMap, selectedLayers);

    tabs.forEach((tab) => {
        tab.addEventListener('click', (e) => {
            e.preventDefault();
            selectCPT(tab.dataset.cpt);
        });
    });

    const form = document.querySelector('.dcp-map__form');
    if (form) {
        form.addEventListener('submit', async (event) => {
            event.preventDefault();
            const { restUrl } = globalThis.hl_dcp_map_data;
            const input = event.target.querySelector('input[name="address"]');
            if (input.value.length > 2) {
                const address = input.value;
                const res = await fetch(`${restUrl}?address=${encodeURIComponent(address)}`, {
                    method: 'POST'
                });
                if (res.ok) {
                    const { lat, lon } = await res.json();
                    const mapGL = await until(() => jeoMap.map);
                    mapGL.flyTo({
                        center: [lon, lat],
                        zoom: 19
                    });
                } else if (res.status === 404) {
                    input.value = '';
                }
            }
        });
    }

    jeoMap.element.style.height = (window.innerHeight - container.offsetTop) + 'px';
    const canvas = container.querySelector('canvas');
    if (canvas) {
        canvas.style.height = jeoMap.element.style.height;
    }

    (function () {
        const iconBtn = document.getElementById('icon-btn');

        function updateIcon() {
            if (window.innerWidth <= 768) {
                iconBtn.setAttribute('icon', 'bi:search');
            } else {
                iconBtn.setAttribute('icon', 'bi:plus');
            }
        }

        updateIcon();
        window.addEventListener('resize', updateIcon);
    })();
});
