import { Splide } from '@splidejs/splide'

function buildGallery(container, feature) {
    const gallery = container.querySelector('.splide')
    const slidesList = gallery.querySelector('.splide__list')

    gallery.splide?.destroy()

    const medias = (typeof feature.media === 'string') ? JSON.parse(feature.media) : feature.media

    const slides = []
    for (const media of medias) {
        let slideContent = null

        if (media.mime.startsWith('image')) {
            slideContent = document.createElement('img')
            slideContent.src = media.src
        } else if (media.mime.startsWith('video')) {
            slideContent = document.createElement('video')
            slideContent.controls = true
            slideContent.src = media.src
        }

        if (slideContent) {
            const slide = document.createElement('div')
            slide.className = 'splide__slide'
            slide.appendChild(slideContent)
            slides.push(slide)
        }
    }

    if (slides.length > 0) {
        gallery.style.display = ''
        slidesList.replaceChildren(...slides)

        gallery.splide = new Splide(gallery)
        gallery.splide.mount()
    } else {
        gallery.style.display = 'none'
        slidesList.replaceChildren()
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
        icon: type,
        ...data,
    })
}

function createRiscoFeature(risco) {
    const { lat, lon, type, ...data } = risco
    return createFeature([lon, lat], {
        icon: `risco-${type}`,
        type,
        ...data,
    })
}

function getColors(slug) {
    if (slug === 'apoio') {
        return { backgroundColor: '#235540', textColor: '#ffffff' }
    } else {
        return { backgroundColor: '#000000', textColor: '#ffffff' }
    }
}

function insertFeatureCollection(map, container, slug, features) {
    const pinsLayer = `${slug}-pins`
    const clustersLayer = `${slug}-clusters`
    const countLayer = `${slug}-count`

    let lastZoom = map.getZoom()

    const { backgroundColor, textColor } = getColors(slug)

    map.addSource(slug, {
        type: 'geojson',
        cluster: true,
        clusterRadius: 54,
        clusterMaxZoom: 17,
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
            'circle-color': backgroundColor,
            'circle-radius': 14,
        },
    })

    map.addLayer({
        id: countLayer,
        type: 'symbol',
        source: slug,
        layout: {
            'text-field': '{point_count}',
            'text-font': ['Open Sans Bold'],
            'text-size': 12,
        },
        paint: {
            'text-color': textColor,
        },
    })

    const spiderifier = new MapboxglSpiderifier(map, {
        animate: true,
        animateSpeed: 200,
        customPin: true,
        initializeLeg (leg) {
            const type = leg.feature.type
            leg.elements.pin.style.backgroundImage = `url("${getImageUrl(slug === 'risco' ? `risco-${type}` : type)}")`
            leg.elements.container.addEventListener('click', () => {
                displayModal(container, slug, leg.feature)
            })
        },
    })

    map.on('click', pinsLayer, (event) => {
        const feature = event.features[0]
        displayModal(container, slug, feature.properties)
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
        if (Math.abs(currentZoom - lastZoom) < 0.1) {
            if (currentZoom < lastZoom) {
                spiderifier.unspiderfy()
            }
        }

        lastZoom = currentZoom
    })
}

function closeModals(container) {
    container.querySelectorAll('dialog').forEach((dialog) => {
        dialog.close()
    })
}

function displayModal(container, type, feature) {
    closeModals(container)
    if (type === 'apoio') {
        displayApoioModal(container, feature)
    } else if (type === 'risco') {
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

export function setupMap(jeoMap, container, riscos, apoios, initialSource) {
    const map = jeoMap.map

    const riscoFeatures = riscos.map(createRiscoFeature)
    const apoioFeatures = apoios.map(createApoioFeature)

    function toggleLayer(cpt) {
        closeModals(container)

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
            loadImage(map, 'risco-alagamento'),
            loadImage(map, 'risco-lixo'),
            loadImage(map, 'risco-outros'),
        ])

        insertFeatureCollection(map, container, 'risco', riscoFeatures)
        insertFeatureCollection(map, container, 'apoio', apoioFeatures)
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
