<div id="modalConfirm" class="modal-confirm">
    <div class="modal-confirm-content">
        <header class="is-title">
            <h3>{TUITULO}</h3>
            <button class="button is-close">
                <iconify-icon icon="bi:x-lg"></iconify-icon>
            </button>
        </header>

        <article class="is-body">
            <p>{DESCRIÇÃO}</p>
        </article>
        <div class="is-error"></div>

        <div class="is-actions">
            <?php if( wp_is_mobile() ) : ?>
                <button class="button is-confirm">
                    <i><iconify-icon icon="bi:check"></iconify-icon></i>
                    <span>{MODAL}</span>
                </button>
                <button class="button is-custom">{CUSTOM}</button>
                <button class="button is-cancel">{CANCEL}</button>
            <?php else : ?>
                <button class="button is-cancel">{CANCEL}</button>
                <button class="button is-custom">{CUSTOM}</button>
                <button class="button is-confirm">
                    <i><iconify-icon icon="bi:check"></iconify-icon></i>
                    <span>{MODAL}</span>
                </button>
            <?php endif; ?>
        </div>
    </div>
</div>
