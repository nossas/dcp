import { until } from '../shared/wait'

async function fetchCoordinates (address) {
    const { address_suffix, rest_url } = globalThis.hl_edit_pins_data
    const fullAddress = address + address_suffix
    const res = await fetch(`${rest_url}?address=${encodeURIComponent(fullAddress)}`, {
        method: 'POST',
    })
    if (res.ok) {
        try {
            const json = await res.text()
            return JSON.parse(json)
        } catch (error) {
            console.error(error)
            return null
        }
    }
}

document.addEventListener('DOMContentLoaded', async () => {
    const addressInput = await until(() => document.querySelector('input[name="pods_meta_endereco"]'))

    addressInput?.addEventListener('change', async (event) => {
        const coordinates = await fetchCoordinates(event.target.value)
        if (coordinates) {
            const { lat, lon } = coordinates
            document.querySelector('input[name="pods_meta_latitude"]').value = lat
            document.querySelector('input[name="pods_meta_longitude"]').value = lon
        }
    })
})
