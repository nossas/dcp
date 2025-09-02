import { Splide } from '@splidejs/splide'

const splideInstances = {};

function buildGallery(container, feature) {
    const gallery = container.querySelector('.splide');
    const slidesList = gallery.querySelector('.splide__list');

    if (!gallery.id) {
        const modalType = container.classList.contains('risk-modal') ? 'risk' : 'support';
        gallery.id = `splide-${modalType}`;
    }
    const galleryId = gallery.id;

    const medias = (typeof feature.media === 'string') ? JSON.parse(feature.media) : feature.media;

    const slides = [];
    if (medias && medias.length > 0) {
        for (const media of medias) {
            let slideContent = null;
            if (media.mime.startsWith('image')) {
                slideContent = document.createElement('img');
                slideContent.src = media.src;
                slideContent.alt = media.alt || '';
            } else if (media.mime.startsWith('video')) {
                slideContent = document.createElement('video');
                slideContent.controls = true;
                slideContent.src = media.src;
            }

            if (slideContent) {
                const slide = document.createElement('li');
                const isVertical = media.custom_fields && media.custom_fields.orientation === 'vertical';
                const verticalClass = isVertical ? 'is-vertical' : '';
                slide.className = `splide__slide ${verticalClass}`;
                slide.appendChild(slideContent);
                slides.push(slide);
            }
        }
    }

    if (slides.length === 0) {
        gallery.style.display = 'none';
        return;
    }

    gallery.style.display = '';

    const existingInstance = splideInstances[galleryId];

    if (existingInstance && existingInstance.state.is('mounted')) {
        existingInstance.remove(() => true);
        existingInstance.add(slides);
    } else {
        slidesList.replaceChildren(...slides);

        const newInstance = new Splide(gallery);
        splideInstances[galleryId] = newInstance;

        newInstance.mount();

        setTimeout(() => {
            newInstance.refresh();
        }, 0);
    }
}

function createFeature(coordinates, properties) {
    return {
        type: 'Feature',
        geometry: {
            type: 'Point',
            coordinates,
        },
        properties,
    }
}

function createApoioFeature(apoio) {
    const { lat, lon, type, ...data } = apoio
    return createFeature([lon, lat], {
        kind: 'apoio',
        icon: type,
        type,
        ...data,
    })
}

function createRiscoFeature(risco) {
    const { lat, lon, type, ...data } = risco
    return createFeature([lon, lat], {
        kind: 'risco',
        icon: `risco-${type}`,
        type,
        ...data,
    })
}

function createSpiderifier(map, container) {
    const spiderifier = new MapboxglSpiderifier(map, {
        animate: true,
        animateSpeed: 200,
        customPin: true,
        circleFootSeparation: 44,
        initializeLeg (leg) {
            leg.elements.pin.style.backgroundImage = `url("${getImageUrl(leg.feature.icon)}")`
            leg.elements.container.addEventListener('click', () => {
                displayModal(container, leg.feature)
            })
        },
    })

    return spiderifier
}

function insertFeatureCollection(map, spiderifier, container, slug, features) {
    const pinsLayer = `${slug}-pins`
    const clustersLayer = `${slug}-clusters`
    const countLayer = `${slug}-count`

    let lastZoom = map.getZoom()

    map.addSource(slug, {
        type: 'geojson',
        cluster: true,
        clusterRadius: 54,
        clusterMaxZoom: 24,
        data: {
            type: 'FeatureCollection',
            features: features,
        }
    })

    map.addLayer({
        id: pinsLayer,
        type: 'symbol',
        source: slug,
        filter: ['all', ['!has', 'point_count']],
        layout: {
            'icon-allow-overlap': true,
            'icon-anchor': 'bottom',
            'icon-image': ['get', 'icon'],
        },
    })

    map.addLayer({
        id: clustersLayer,
        type: 'circle',
        source: slug,
        filter: ['all', ['has', 'point_count']],
        paint: {
            'circle-color': '#000000',
            'circle-radius': 14,
        },
    })

    map.addLayer({
        id: countLayer,
        type: 'symbol',
        source: slug,
        layout: {
            'text-field': '{point_count}',
            'text-font': ['Open Sans Bold', 'Arial Unicode MS Bold'],
            'text-size': 12,
        },
        paint: {
            'text-color': '#ffffff',
        },
    })

    map.on('click', pinsLayer, (event) => {
        const feature = event.features[0]
        displayModal(container, feature.properties)
    })

    map.on('click', clustersLayer, (event) => {
        const features = map.queryRenderedFeatures(event.point, { layers: [clustersLayer] })

        spiderifier.unspiderfy()
        if (!features.length) {
            return
        } else {
            map.getSource(slug).getClusterLeaves(features[0].properties.cluster_id, 100, 0, (err, leafFeatures) => {
                if (err) {
                    console.error('Error getting leaves from cluster', err)
                } else {
                    const markers = leafFeatures.map((feature) => feature.properties)
                    spiderifier.spiderfy(features[0].geometry.coordinates, markers)
                }
            })
        }
    })

    map.on('mousemove', (event) => {
        const features = map.queryRenderedFeatures(event.point, { layers: [clustersLayer, pinsLayer] })
        map.getCanvas().style.cursor = (features.length > 0) ? 'pointer' : ''
    })

    map.on('zoom', () => {
        const currentZoom = map.getZoom()
        if (Math.abs(currentZoom - lastZoom) > 0.1) {
            lastZoom = currentZoom
            spiderifier.unspiderfy()
        }
    })
}

function closeModals(container) {
    container.querySelectorAll('dialog').forEach((dialog) => {
        dialog.close()
    })
}

function displayModal(container, feature) {
    closeModals(container)
    if (feature.kind === 'apoio') {
        displayApoioModal(container, feature)
    } else if (feature.kind === 'risco') {
        displayRiscoModal(container, feature)
    }
}

function displayApoioModal(container, apoio) {
    const dialog = container.querySelector('.support-modal')

    dialog.querySelector('.dcp-map-modal__title').innerHTML = apoio.title
    dialog.querySelector('.dcp-map-modal__excerpt').innerHTML = apoio.excerpt
    dialog.querySelector('.support-modal__hour').innerHTML = apoio.horario
    dialog.querySelector('.support-modal__address').innerHTML = apoio.endereco

    const whatsappButton = dialog.querySelector('.dcp-map-modal__whatsapp')
    const shareUrl = `${container.dataset.shareUrl}?tab=apoio`
    const shareText = `[Apoio] ${apoio.title} - ${apoio.endereco} ${decodeURI(shareUrl)}`
    whatsappButton.href = whatsappButton.dataset.href.replace('$', decodeURIComponent(shareText))

    buildGallery(dialog, apoio)
    dialog.showModal()
}

function displayRiscoModal(container, risco) {
    const dialog = container.querySelector('.risk-modal')
    dialog.classList.toggle('risk-modal--alagamento', risco.type === 'alagamento')
    dialog.classList.toggle('risk-modal--lixo', risco.type === 'lixo')

    let typeLabel
    switch (risco.type) {
        case 'alagamento':
            typeLabel = 'Alagamento'
            break
        case 'lixo':
            typeLabel = 'Lixo'
            break
        default:
            typeLabel = 'Outro'
            break
    }

    const pillImage = dialog.querySelector('.dcp-map-modal__pill > img')
    pillImage.src = pillImage.dataset.src.replace('$', risco.type)
    dialog.querySelector('.dcp-map-modal__pill > span').textContent = typeLabel

    dialog.querySelector('.dcp-map-modal__title').innerHTML = risco.title
    dialog.querySelector('.dcp-map-modal__excerpt').innerHTML = risco.excerpt
    dialog.querySelector('.risk-modal__date').innerHTML = risco.date

    const whatsappButton = dialog.querySelector('.dcp-map-modal__whatsapp')
    const shareUrl = `${container.dataset.shareUrl}?tab=risco`
    const shareText = `[Risco - ${typeLabel}] ${risco.date} - ${risco.title} ${shareUrl}`
    whatsappButton.href = whatsappButton.dataset.href.replace('$', decodeURIComponent(shareText))

    buildGallery(dialog, risco)
    dialog.showModal()
}

function getImageUrl(slug) {
    return `${globalThis.hl_dcp_map_data.themeAssets}/assets/images/pin-${slug}.svg`
}

async function loadImage(map, slug, height = 54, width = 44) {
    const src = getImageUrl(slug)

    return new Promise((resolve) => {
        const img = new globalThis.Image(width, height)
        img.onload = () => {
            map.addImage(slug, img)
            resolve(slug)
        }
        img.src = src
    })
}

function setupLightbox() {
    const lightbox = document.getElementById('simpleLightbox');
    if (!lightbox) {
        console.warn('Elemento do Lightbox não encontrado.');
        return;
    }

    const lightboxImage = lightbox.querySelector('img');
    const closeButton = lightbox.querySelector('.simple-lightbox__close');

    let lastOpenedModal = null;

    const openLightbox = (imageElement) => {
        const modal = imageElement.closest('.dcp-map-modal');

        if (modal) {
            lastOpenedModal = modal;
            lastOpenedModal.close();
        }

        lightboxImage.src = imageElement.src;
        lightbox.classList.add('is-active');
    };

    const closeLightbox = () => {
        lightbox.classList.remove('is-active');
        setTimeout(() => { lightboxImage.src = ''; }, 300);

        if (lastOpenedModal) {
            lastOpenedModal.showModal();
            lastOpenedModal = null;
        }
    };

    document.body.addEventListener('click', function(event) {
        const clickedImage = event.target.closest('.dcp-map-modal .splide__slide img');
        if (clickedImage) {
            event.preventDefault();
            openLightbox(clickedImage);
        }
    });

    closeButton.addEventListener('click', closeLightbox);
    lightbox.addEventListener('click', (event) => {
        if (event.target === lightbox) {
            closeLightbox();
        }
    });
    document.addEventListener('keydown', (event) => {
        if (event.key === 'Escape' && lightbox.classList.contains('is-active')) {
            closeLightbox();
        }
    });
}

export function setupMap(jeoMap, container, riscos, apoios, initialSource) {
    setupLightbox()
    const map = jeoMap.map
    let spiderifier

    const riscoFeatures = riscos.map(createRiscoFeature)
    const apoioFeatures = apoios.map(createApoioFeature)

    function toggleLayer(cpt) {
        closeModals(container)
        spiderifier?.unspiderfy()

        for (const [source, features] of [['apoio', apoioFeatures], ['risco', riscoFeatures]]) {
            const filteredFeatures = (source === cpt) ? features : []
            map.getSource(source)?.setData({
                type: 'FeatureCollection',
                features: filteredFeatures,
            })
        }
    }

    map.on('load', async () => {
        await Promise.all([
            loadImage(map, 'apoio'),
            loadImage(map, 'cacamba'),
            // Adicionar outro tipo de apoio

            loadImage(map, 'risco-alagamento'),
            loadImage(map, 'risco-lixo'),
            loadImage(map, 'risco-outros'),
        ])

        spiderifier = createSpiderifier(map, container)
        insertFeatureCollection(map, spiderifier, container, 'risco', riscoFeatures)
        insertFeatureCollection(map, spiderifier, container, 'apoio', apoioFeatures)
        toggleLayer(initialSource.current)

        if (window.innerWidth < 800) {
            map.dragPan.disable()
            map.scrollZoom.disable()
            map.touchPitch.disable()

            map.on('touchstart', (event) => {
                const originalEvent = event.originalEvent
                if (originalEvent?.touches) {
                    if (originalEvent.touches.length > 1) {
                        originalEvent.stopImmediatePropagation()
                        map.dragPan.enable()
                    } else {
                        map.dragPan.disable()
                    }
                }
            })
        }
    })

    return { displayModal, toggleLayer }
}
