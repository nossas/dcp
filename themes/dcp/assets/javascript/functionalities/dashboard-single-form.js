document.addEventListener('DOMContentLoaded', function() {
    // Seleciona qualquer um dos formulários do dashboard
    const form = document.querySelector('#riscoSingleForm, #acaoSingleForm');
    if (!form) return;

    //---------Categoria------------

    const categorySelect = form.querySelector('#selectCategory');
    if (categorySelect) {
        const categoryIconContainer = form.querySelector('.category-icon-container');
        const categoryToggleBtn = form.querySelector('.is-categoria-toggle');

        const updateCategoryState = () => {
            if (!categoryIconContainer) return;
            const hasValue = categorySelect.value !== '';
            categoryIconContainer.style.display = hasValue ? 'block' : 'none';
            categorySelect.style.paddingLeft = hasValue ? '50px' : '20px';
            categorySelect.style.color = hasValue ? '#281414' : '#484646';
        };

        categorySelect.addEventListener('change', updateCategoryState);

        if (categoryToggleBtn) {
            categoryToggleBtn.addEventListener('click', (e) => {
                e.preventDefault();
                if (typeof categorySelect.showPicker === 'function') {
                    categorySelect.showPicker();
                }
            });
        }
        updateCategoryState();
    }

    //---------SubCategoria------------
    const subCategoryInput = form.querySelector('#subCategoryInput');
    if (subCategoryInput) {
        const subCategoryChipsWrap = subCategoryInput.querySelector('.chips-wrap');
        const subCategoryPlaceholder = subCategoryInput.querySelector('.placeholder-text');
        const subCategoryToggleBtn = form.querySelector('.is-subcategoria-toggle');
        const subCategoryCheckboxContainer = subCategoryInput.querySelector('.chips-checkbox');

        if (subCategoryChipsWrap && subCategoryPlaceholder && subCategoryToggleBtn && subCategoryCheckboxContainer) {

            const subCategoryCheckboxes = subCategoryCheckboxContainer.querySelectorAll('input[type="checkbox"]');

            const renderSubCategoryChips = () => {
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
            };

            const toggleSubCategoryDropdown = (e) => {
                if (e.target.closest('.chip') || subCategoryCheckboxContainer.contains(e.target)) return;
                e.preventDefault();
                subCategoryCheckboxContainer.classList.toggle('is-open');
                subCategoryInput.closest('.input-wrap').classList.toggle('is-active', subCategoryCheckboxContainer.classList.contains('is-open'));
            };

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
                checkbox.addEventListener('change', () => setTimeout(renderSubCategoryChips, 0));
            });

            document.addEventListener('click', (e) => {
                const wrap = subCategoryInput.closest('.input-wrap');
                if (!wrap.contains(e.target) && subCategoryCheckboxContainer.classList.contains('is-open')) {
                    subCategoryCheckboxContainer.classList.remove('is-open');
                    wrap.classList.remove('is-active');
                }
            });

            setTimeout(renderSubCategoryChips, 0);

        } else {
            console.error('ERRO DE DEBUG: Um ou mais elementos essenciais da subcategoria não foram encontrados. Os eventos de clique NÃO foram adicionados.');
        }
    }
});
