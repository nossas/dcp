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
    return createFeature([apoio.lng, apoio.lat], {
        icon: 'apoio',
        title: apoio.title,
    })
}

function createRiscoFeature (risco) {
    return createFeature([risco.lng, risco.lat], {
        icon: 'risco',
        title: risco.title,
    })
}

function insertFeatureCollection (map, slug, popupRef, features) {
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
		const title = feature.properties.title
		displayPopup(map, popupRef, title, feature.geometry.coordinates)
	})

	map.on('mousemove', (event) => {
		const features = map.queryRenderedFeatures(event.point, { layers: [pinsSlug] })
		map.getCanvas().style.cursor = (features.length > 0) ? 'pointer' : ''
	})
}

function displayPopup (map, popupRef, html, coordinates) {
    popupRef.current?.remove()
    popupRef.current = new globalThis.mapboxgl.Popup()
        .setLngLat([...coordinates])
        .setHTML(html)
        .addTo(map)
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

export function setupMap (jeoMap, riscos, apoios, initialSource) {
    const map = jeoMap.map

    const riscoPopup = { current: null }
    const apoioPopup = { current: null }

    const riscoFeatures = riscos.map(createRiscoFeature)
    const apoioFeatures = apoios.map(createApoioFeature)

    function toggleLayer (cpt) {
        apoioPopup.current?.remove()
        riscoPopup.current?.remove()

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
            loadImage(map, 'alagamento'),
            loadImage(map, 'lixo'),
            loadImage(map, 'risco'),
        ])

        insertFeatureCollection(map, 'risco', riscoPopup, riscoFeatures)
        insertFeatureCollection(map, 'apoio', apoioPopup, apoioFeatures)
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
