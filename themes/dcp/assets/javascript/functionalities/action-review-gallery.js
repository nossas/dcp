import { Splide } from '@splidejs/splide'

export async function buildGallery (container, files) {
    const gallery = container.querySelector('.splide')
    const slidesList = gallery.querySelector('.splide__list')

    const medias = await Promise.all(files.map(loadMedia))

    gallery.splide?.destroy()

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
