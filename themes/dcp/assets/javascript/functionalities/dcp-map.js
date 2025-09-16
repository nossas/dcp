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
    let toggleLayer = null;

    if (!tabsList || tabs.length === 0) return;

    function updateSearchParams(tab) {
        const url = new URL(location.href);
        url.searchParams.set('tab', tab);
        window.history.replaceState(null, '', url.toString());
    }

    function selectCPT(cpt) {
        tabs.forEach((tab) => {
            const isSelected = tab.dataset.cpt === cpt;
            tab.classList.toggle(selectedTabClass, isSelected);
        });

        const legendDesktop = document.querySelector('.dcp-map-legend-apoio');
        const legendMobile = document.querySelector('.dcp-map-legend-apoio__mobile');

        if (legendDesktop) {
            legendDesktop.style.display = (cpt === 'apoio') ? 'block' : 'none';
        }

        if (legendMobile) {
            const isMobile = window.matchMedia('(max-width: 768px)').matches;
            legendMobile.style.display = (cpt === 'apoio' && isMobile) ? 'block' : 'none';
        }

        toggleLayer?.(cpt);
        updateSearchParams(cpt);
    }

    let initialTab = tabsList.dataset.selected;
    const query = new URLSearchParams(location.search);
    if (query.has('tab') && ['apoio', 'risco'].includes(query.get('tab'))) {
        initialTab = query.get('tab');
    }

    await until(() => map.dataset.map_id);
    const jeoMap = globalThis.jeomaps[map.dataset.uui_id];
    await until(() => jeoMap.map);

    const selectedCPT = { current: initialTab };
    const mapContext = setupMap(jeoMap, container, riscos, apoios, selectedCPT);
    toggleLayer = mapContext.toggleLayer;

    setTimeout(() => {
        selectCPT(initialTab);
    }, 50);

    tabs.forEach((tab) => {
        tab.addEventListener('click', (e) => {
            e.preventDefault();
            const currentSelectedCpt = new URLSearchParams(location.search).get('tab');
            if (tab.dataset.cpt !== currentSelectedCpt) {
                selectCPT(tab.dataset.cpt);
            }
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
                const res = await fetch(`${restUrl}?address=${encodeURIComponent(address)}`, { method: 'POST' });
                if (res.ok) {
                    const { lat, lon } = await res.json();
                    const mapbox = await until(() => jeoMap.map);
                    mapbox.flyTo({ center: [lon, lat], zoom: 19 });
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

    (function() {
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



