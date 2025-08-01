import { Splide } from '@splidejs/splide'

export async function buildGallery (container, files, editable = false) {
    const gallery = container.querySelector('.splide')
    const slidesList = gallery.querySelector('.splide__list')

    const medias = await Promise.all(files.map(loadMedia))

    gallery.splide?.destroy()

    const slides = []
    medias.forEach((media, index) =>  {
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

            if (editable) {
                const removeButton = document.createElement('button')
                removeButton.type = 'button'
                removeButton.classList.add('remove-media-btn')
                removeButton.innerHTML = `
                <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" viewBox="0 0 20 20" fill="none">
                    <path d="M5 5L15 15M15 5L5 15" stroke="#B83D13" stroke-width="2" stroke-linecap="round"/>
                </svg>`
                removeButton.style.position = 'absolute'
                removeButton.style.top = '0'
                removeButton.style.right = '0'
                removeButton.style.background = 'transparent'
                removeButton.style.border = 'none'
                removeButton.style.cursor = 'pointer'
                removeButton.style.padding = '4px'

                removeButton.addEventListener('click', (e) => {
                    e.preventDefault()
                    files.splice(index, 1)
                    buildGallery(container, files, editable)
                })

                slide.appendChild(removeButton)
            }

            slides.push(slide)
        }
    })

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

function loadMedia(file) {
    const reader = new FileReader();
    return new Promise((resolve, reject) => {
        reader.addEventListener("load", () => {
           resolve({
                src: reader.result,
                mime: file.type,

           })
        });

        if (file) {
            reader.readAsDataURL(file);
        }
    })
}
