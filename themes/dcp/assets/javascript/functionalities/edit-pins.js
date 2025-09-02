import { until } from '../shared/wait'

async function fetchCoordinates (address) {
    const { rest_url } = globalThis.hl_edit_pins_data
    const res = await fetch(`${rest_url}?address=${encodeURIComponent(address)}`, {
        method: 'POST',
    })
    if (res.ok) {
        return res.json()
    } else {
        return null
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
