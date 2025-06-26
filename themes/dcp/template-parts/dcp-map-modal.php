<dialog class="dcp-map-modal risk-modal" x-ref="riskModal">
    <article>
        <header>
            <button class="dcp-map-modal__close" type="button" aria-label="Fechar" @click="$refs.riskModal.close()">
                <iconify-icon icon="bi:x-lg"></iconify-icon>
            </button>
            <div class="dcp-map-modal__pill risk-modal__pill">
                <img src="" data-src="<?= get_stylesheet_directory_uri() ?>/assets/images/risco-$.svg" alt="">
                <span></span>
            </div>
            <p class="risk-modal__date"></p>
        </header>
        <main>
            <h1 class="dcp-map-modal__title">$</h1>
            <p class="dcp-map-modal__excerpt"></p>
        </main>
        <footer>
            <a class="dcp-map-modal__whatsapp" href="#" data-href="https://api.whatsapp.com/send?text=$">
                <iconify-icon icon="formkit:whatsapp"></iconify-icon>
                <span class="dcp-map-modal__whatsapp-desktop">Compartilhar no WhatsApp</span>
                <span class="dcp-map-modal__whatsapp-mobile">Compartilhar</span>
            </a>
        </footer>
    </article>
</dialog>

<dialog class="dcp-map-modal support-modal" x-ref="supportModal">
    <article>
        <header>
            <button class="dcp-map-modal__close" type="button" aria-label="Fechar" @click="$refs.supportModal.close()">
                <iconify-icon icon="bi:x-lg"></iconify-icon>
            </button>
            <div class="dcp-map-modal__pill support-modal__pill">
                <span>Apoio</span>
            </div>
        </header>
        <main>
            <h1 class="dcp-map-modal__title">$</h1>
            <p class="dcp-map-modal__excerpt"></p>
            <div class="dcp-map-modal__details">
                <!-- <p><iconify-icon icon="bi:calendar3"></iconify-icon> Horário: <span class="support-modal__hour"></span></p> -->
                <p><iconify-icon icon="bi:geo-alt-fill"></iconify-icon> Endereço: <span class="support-modal__address"></span></p>
            </div>
        </main>
        <footer>
            <a class="dcp-map-modal__whatsapp" href="#" data-href="https://api.whatsapp.com/send?text=$">
                <iconify-icon icon="formkit:whatsapp"></iconify-icon>
                <span class="dcp-map-modal__whatsapp-desktop">Compartilhar no WhatsApp</span>
                <span class="dcp-map-modal__whatsapp-mobile">Compartilhar</span>
            </a>
        </footer>
    </article>
</dialog>
