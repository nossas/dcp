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

function changedCoords (risk, coords) {
    return coords[0] !== risk.longitude || coords[1] !== risk.latitude
}

export async function showDraggableMap (map, risk, updateAddress) {
    const initialCoords = await getInitialCoords(risk)
    map.easeTo({ center: initialCoords, zoom: map.getZoom() })
    if (changedCoords(risk, initialCoords)) {
        updateAddress(...initialCoords, false)
    }

    const marker = new mapboxgl.Marker({ anchor: 'bottom', draggable: true })
        .setLngLat(initialCoords)
        .addTo(map)

    marker.on('dragend', async () => {
        const coords = marker.getLngLat()
        updateAddress(coords.lng, coords.lat, true)
    })

    const updateMarker = (longitude, latitude) => {
        marker.setLngLat([longitude, latitude])
    }

    return updateMarker
}
