function createFeature (coordinates, properties) {
    return {
        type: 'Feature',
        geometry: {
            type: 'Point',
            coordinates,
        },
        properties,
    }
}

function createApoioFeature (apoio) {
    const { lat, lon, ...data } = apoio
    return createFeature([lon, lat], {
        icon: 'apoio',
        ...data,
    })
}

function createRiscoFeature (risco) {
    const { lat, lon, type, ...data } = risco
    return createFeature([lon, lat], {
        icon: `risco-${type}`,
        type,
        ...data,
    })
}

function insertFeatureCollection (map, container, slug, features) {
    const sourceSlug = slug
    const pinsSlug = `${slug}-pins`

    map.addSource(sourceSlug, {
        type: 'geojson',
        data: {
            type: 'FeatureCollection',
            features: features,
        }
    })

    map.addLayer({
        id: pinsSlug,
        type: 'symbol',
        source: sourceSlug,
        layout: {
            'icon-allow-overlap': true,
            'icon-image': ['get', 'icon'],
        },
    })

    map.on('click', pinsSlug, (event) => {
		const feature = event.features[0]
        displayModal(container, slug, feature.properties)
	})

	map.on('mousemove', (event) => {
		const features = map.queryRenderedFeatures(event.point, { layers: [pinsSlug] })
		map.getCanvas().style.cursor = (features.length > 0) ? 'pointer' : ''
	})
}

function closeModals (container) {
    container.querySelectorAll('dialog').forEach((dialog) => {
        dialog.close()
    })
}

function displayModal (container, type, feature) {
    closeModals(container)
    if (type === 'apoio') {
        displayApoioModal(container, feature)
    } else if (type === 'risco') {
        displayRiscoModal(container, feature)
    }
}

function displayApoioModal (container, apoio) {
    const dialog = container.querySelector('.support-modal')

    dialog.querySelector('.dcp-map-modal__title').innerHTML = apoio.title
    dialog.querySelector('.dcp-map-modal__excerpt').innerHTML = apoio.excerpt
    dialog.querySelector('.support-modal__address').innerHTML = apoio.endereco

    const whatsappButton = dialog.querySelector('.dcp-map-modal__whatsapp')
    const shareText = `[Apoio] ${apoio.title} - ${apoio.endereco} ${location.href}`
    whatsappButton.href = whatsappButton.dataset.href.replace('$', decodeURIComponent(shareText))

    dialog.showModal()
}

function displayRiscoModal (container, risco) {
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
    const shareText = `[Risco - ${typeLabel}] ${risco.date} - ${risco.title} ${location.href}`
    whatsappButton.href = whatsappButton.dataset.href.replace('$', decodeURIComponent(shareText))

    dialog.showModal()
}

function getImageUrl (slug) {
    return `${globalThis.dcp_map_data.themeAssets}/assets/images/pin-${slug}.svg`
}

async function loadImage (map, slug, height = 54, width = 44) {
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

export function setupMap (jeoMap, container, riscos, apoios, initialSource) {
    const map = jeoMap.map

    const riscoFeatures = riscos.map(createRiscoFeature)
    const apoioFeatures = apoios.map(createApoioFeature)

    function toggleLayer (cpt) {
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

    return toggleLayer
}
