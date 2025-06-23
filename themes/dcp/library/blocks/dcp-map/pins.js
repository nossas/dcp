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
        title: apoio.title,
    })
}

function insertFeatureCollection (map, slug, features, { circleColor, textColor }) {
    const SPIDERIFIER_FROM_ZOOM = 10

    const sourceSlug = slug
    const clustersSlug = `${slug}-clusters`
    const countSlug = `${slug}-count`
    const pinsSlug = `${slug}-pins`

    let lastZoom = map.getZoom()
    let spiderifier = null

    const popupRef = { current: null }

    map.addSource(sourceSlug, {
        type: 'geojson',
        cluster: true,
        clusterRadius: 30,
        clusterMaxZoom: 25,
        data: {
            type: 'FeatureCollection',
            features: features,
        }
    })

    map.addLayer({
        id: pinsSlug,
        type: 'symbol',
        source: sourceSlug,
        filter: ['all', ['!has', 'point_count']],
        layout: {
            'icon-allow-overlap': true,
            'icon-image': ['get', 'icon'],
        },
    })

    map.addLayer({
        id: clustersSlug,
        type: 'circle',
        source: sourceSlug,
        filter: ['all', ['has', 'point_count']],
        paint: {
            'circle-color': circleColor,
            'circle-opacity': 0.75,
            'circle-radius': 14,
        },
    })

    map.addLayer({
        id: countSlug,
        type: 'symbol',
        source: sourceSlug,
        layout: {
            'text-field': '{point_count}',
            'text-font': ['Open Sans Bold', 'Arial Unicode MS Bold'],
            'text-size': 12,
        },
        paint: {
            'text-color': textColor,
        },
    })

    spiderifier = new globalThis.MapboxglSpiderifier(map, {
        animate: true,
        animateSpeed: 200,
        customPin: true,
        initializeLeg (leg) {
            const popup = new mapboxgl.Popup({
                closeOnClick: false,
                offset: MapboxglSpiderifier.popupOffsetForSpiderLeg(leg)
            })
                .setLngLat(leg.mapboxMarker._lngLat)
                .setHTML(leg.feature.title)
            leg.elements.pin.style.backgroundImage = `url("${getImageUrl(leg.feature.icon)}")`
            leg.elements.container.addEventListener('click', () => {
                popupRef.current?.remove()
                popup.addTo(map)
                popupRef.current = popup
            })
        },
    })

    map.on('click', pinsSlug, (event) => {
		const feature = event.features[0]
		const title = JSON.parse(feature.properties.title)
		displayPopup(map, popupRef, title, feature.geometry.coordinates)
	})

	map.on('click', clustersSlug, (event) => {
		const features = map.queryRenderedFeatures(event.point, { layers: [clustersSlug] })

		spiderifier?.unspiderfy()
		if (!features.length) {
			return
		} else if (map.getZoom() < SPIDERIFIER_FROM_ZOOM) {
			map.easeTo({ center: event.lngLat, zoom: map.getZoom() + 2 })
		} else {
			map.getSource(sourceSlug).getClusterLeaves(features[0].properties.cluster_id, 100, 0, (err, leafFeatures) => {
				if (err) {
					console.error('Error getting leaves from cluster', err)
				} else {
					const markers = leafFeatures.map((feature) => feature.properties)
					spiderifier?.spiderfy(features[0].geometry.coordinates, markers)
				}
			})
		}
	})

	map.on('zoom', (event) => {
		const currentZoom = map.getZoom()

		if (Math.abs(currentZoom - lastZoom) < 0.1) {
			if (currentZoom < lastZoom) {
				spiderifier?.unspiderfy()
			}
		}

		lastZoom = currentZoom
	})

	map.on('mousemove', (event) => {
		const features = map.queryRenderedFeatures(event.point, { layers: [pinsSlug, clustersSlug] })
		map.getCanvas().style.cursor = (features.length > 0) ? 'pointer' : ''
	})
}

function displayPopup (map, popupRef, html, coordinates) {
    popupRef.current?.remove()
    popupRef.current = new globalThis.mapboxgl.Pop()
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

export function setupMap (jeoMap, riscos, apoios) {
    const map = jeoMap.map

    map.on('load', async () => {
        await Promise.all([
            loadImage(map, 'apoio'),
            loadImage(map, 'alagamento'),
            loadImage(map, 'lixo'),
            loadImage(map, 'risco'),
        ])

        insertFeatureCollection(map, 'riscos', riscos.map(createRiscoFeature), {
            circleColor: '#6AA23E',
            textColor: '#F1F8EC',
        })

        insertFeatureCollection(map, 'apoios', apoios.map(createApoioFeature), {
            circleColor: '#235540',
            textColor: '#F1F8EC',
        })

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
}
