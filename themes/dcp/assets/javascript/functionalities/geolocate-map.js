function getDefaultCoords () {
    return globalThis.hl_form_actions_data.default_coords
}

function getGeolocation ()  {
    return new Promise((resolve) => {
        const onError = (error) => {
            console.error(error)
            resolve(getDefaultCoords())
        }

        try {
            navigator.geolocation.getCurrentPosition(({ coords }) => {
                resolve([coords.longitude, coords.latitude])
            }, onError, {
                maximumAge: 86_400_000, // 1 day
                timeout: 15_000,
            })
        } catch (err) {
            onError()
        }
    })
}

async function getInitialCoords (risk) {
    if (risk.latitude && risk.longitude) {
        return [risk.longitude, risk.latitude]
    }
    try {
        const coords = await getGeolocation()
        return coords
    } catch (defaultCoords) {
        return defaultCoords
    }
}

export async function showDraggableMap (jeoMap, risk, updateAddress) {
    const map = jeoMap.map

    const initialCoords = await getInitialCoords(risk)
    risk.longitude = initialCoords[0]
    risk.latitude = initialCoords[1]
    map.easeTo({ center: initialCoords, zoom: map.getZoom() })

    const marker = new mapboxgl.Marker({ draggable: true })
        .setLngLat(initialCoords)
        .addTo(map)

    marker.on('dragend', async () => {
        const coords = marker.getLngLat()
        updateAddress(coords.lat, coords.lng)
    })

    const updateMarker = (latitude, longitude) => {
        marker.setLngLat([longitude, latitude])
    }

    return updateMarker
}
