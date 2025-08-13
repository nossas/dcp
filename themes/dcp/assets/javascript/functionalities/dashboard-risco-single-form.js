document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('riscoSingleForm');
    if (!form) return;

    // --- Categoria ---
    const categorySelect = document.getElementById('selectCategory');
    const categoryIconContainer = form.querySelector('.category-icon-container');
    const categoryToggleBtn = form.querySelector('.is-categoria-toggle');

    // --- Subcategoria ---
    const subCategoryInput = document.getElementById('subCategoryInput');
    const subCategoryChipsWrap = subCategoryInput.querySelector('.chips-wrap');
    const subCategoryPlaceholder = subCategoryInput.querySelector('.placeholder-text');
    const subCategoryToggleBtn = form.querySelector('.is-subcategoria-toggle');
    const subCategoryCheckboxContainer = subCategoryInput.querySelector('.chips-checkbox');
    const subCategoryCheckboxes = subCategoryCheckboxContainer.querySelectorAll('input[type="checkbox"]');

    //----- Categoria -----
    let savedSubCategoryValues = [];

    function updateCategoryState() {
        if (!categorySelect || !categoryIconContainer) return;
        const hasValue = categorySelect.value !== '';
        categoryIconContainer.style.display = hasValue ? 'block' : 'none';
        categorySelect.style.paddingLeft = hasValue ? '50px' : '20px';
        categorySelect.style.color = hasValue ? '#281414' : '#484646';
    }

    function renderSubCategoryChips() {
        subCategoryChipsWrap.innerHTML = '';
        const checkedCheckboxes = Array.from(subCategoryCheckboxes).filter(cb => cb.checked);

        checkedCheckboxes.forEach(checkbox => {
            const chip = document.createElement('div');
            chip.className = 'chip';
            chip.innerHTML = `
                <iconify-icon class="chip-icon" icon="bi:check"></iconify-icon>
                <span>${checkbox.dataset.label}</span>
                <button type="button" class="remove-chip" data-value="${checkbox.value}">&times;</button>
            `;
            subCategoryChipsWrap.appendChild(chip);
        });

        subCategoryPlaceholder.style.display = checkedCheckboxes.length > 0 ? 'none' : 'block';
    }

    if (categorySelect) {
        categorySelect.addEventListener('mousedown', () => {
            savedSubCategoryValues = Array.from(subCategoryCheckboxes)
                                          .filter(cb => cb.checked)
                                          .map(cb => cb.value);
        });

        categorySelect.addEventListener('change', () => {
            updateCategoryState();

            subCategoryCheckboxes.forEach(cb => {
                cb.checked = false;
                if (savedSubCategoryValues.includes(cb.value)) {
                    cb.checked = true;
                }
            });

            setTimeout(renderSubCategoryChips, 0);
        });

        if (categoryToggleBtn) {
            categoryToggleBtn.addEventListener('click', (e) => {
                e.preventDefault();
                if (typeof categorySelect.showPicker === 'function') {
                    categorySelect.showPicker();
                }
            });
        }
    }

    //----- Subcategoria -----
    function toggleSubCategoryDropdown(e) {
        if (e.target.closest('.chip')) return;
        if (subCategoryCheckboxContainer.contains(e.target)) return;
        e.preventDefault();
        subCategoryCheckboxContainer.classList.toggle('is-open');
        subCategoryInput.closest('.input-wrap').classList.toggle('is-active', subCategoryCheckboxContainer.classList.contains('is-open'));
    }

    subCategoryChipsWrap.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-chip')) {
            const valueToRemove = e.target.dataset.value;
            const checkboxToUncheck = subCategoryCheckboxContainer.querySelector(`input[value="${valueToRemove}"]`);
            if (checkboxToUncheck) {
                checkboxToUncheck.checked = false;
                checkboxToUncheck.dispatchEvent(new Event('change', { bubbles: true }));
            }
        }
    });

    subCategoryToggleBtn.addEventListener('click', toggleSubCategoryDropdown);
    subCategoryInput.addEventListener('click', toggleSubCategoryDropdown);

    subCategoryCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', () => {
            setTimeout(renderSubCategoryChips, 0);
        });
    });

    document.addEventListener('click', (e) => {
        const isClickInside = subCategoryInput.closest('.input-wrap').contains(e.target);
        if (!isClickInside && subCategoryCheckboxContainer.classList.contains('is-open')) {
            subCategoryCheckboxContainer.classList.remove('is-open');
            subCategoryInput.closest('.input-wrap').classList.remove('is-active');
        }
    });

    // --- INICIALIZAÇÃO ---
    updateCategoryState();
    setTimeout(renderSubCategoryChips, 0);
});
