function showSuccessSnackbar(formElement, message) {
    const snackbar = formElement.querySelector('.cf7-snackbar');
    if (snackbar) {
        snackbar.innerHTML = `
            <svg class="icon" width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3" stroke-linecap="round" stroke-linejoin="round" style="vertical-align: middle; margin-right: 8px;">
                <polyline points="20 6 9 17 4 12"></polyline>
            </svg>
            <span>${message}</span>
        `;
        snackbar.classList.add('show');
        setTimeout(function() {
            snackbar.classList.remove('show');
        }, 3000);
    }
}

document.addEventListener('wpcf7mailsent', function(event) {
    const formId = event.detail.contactFormId;
    let successMessage = '';

    const idFormSugerirAcao = '712';
    const idFormFacaParte = '185';

    switch (formId.toString()) {
        case idFormSugerirAcao:
            successMessage = 'Sua sugestão foi enviada com sucesso!';
            break;

        case idFormFacaParte:
            successMessage = 'Cadastro foi enviado com sucesso!';
            break;
    }

    if (successMessage) {
        showSuccessSnackbar(event.target, successMessage);
    }

}, false);

document.addEventListener('DOMContentLoaded', function() {
  const phoneInputs = document.querySelectorAll('.phone');

  phoneInputs.forEach(function(input) {
    input.addEventListener('input', function(e) {
      let value = e.target.value.replace(/\D/g, '');

      if (value.length > 10) {
        value = value.replace(/^(\d{2})(\d{5})(\d{4}).*/, '($1) $2-$3');
      }
      else if (value.length > 6) {
        value = value.replace(/^(\d{2})(\d{4})(\d{0,4}).*/, '($1) $2-$3');
      }
      else if (value.length > 2) {
        value = value.replace(/^(\d{2})(\d{0,5})/, '($1) $2');
      }
      else {
        value = value.replace(/^(\d*)/, '($1');
      }

      e.target.value = value;
    });
  });
});

document.addEventListener('DOMContentLoaded', () => {
  const form = document.getElementById('formParticiparAcao');
  if (!form) return console.warn('formParticiparAcao não encontrado.');

  const snackbarError = document.getElementById('cf7-snackbar-error');
  const snackbarSuccess = document.getElementById('cf7-snackbar-success');

  const requiredFields = Array.from(
    form.querySelectorAll('input[required], textarea[required], select[required]')
  );

  function findMsgEl(field) {
    const lbl = field.closest('label');
    if (lbl && lbl.nextElementSibling?.classList.contains('msg-erro')) return lbl.nextElementSibling;
    let el = field.nextElementSibling;
    for (let i = 0; i < 4 && el; i++) {
      if (el.classList?.contains('msg-erro')) return el;
      el = el.nextElementSibling;
    }
    return field.parentElement?.querySelector('.msg-erro') || null;
  }

  function addErrorVisual(field) {
    if (!field) return;
    if (field.type === 'checkbox' || field.type === 'radio') {
      const lbl = field.closest('label');
      if (lbl) lbl.classList.add('campo-erro');
    } else {
      field.classList.add('campo-erro');
      if (field.parentElement) field.parentElement.classList.add('campo-erro');
    }
    field.setAttribute('aria-invalid', 'true');
    const msg = findMsgEl(field);
    if (msg) msg.style.display = 'block';
  }

  function removeErrorVisual(field) {
    if (!field) return;
    if (field.type === 'checkbox' || field.type === 'radio') {
      const lbl = field.closest('label');
      if (lbl) lbl.classList.remove('campo-erro');
    } else {
      field.classList.remove('campo-erro');
      if (field.parentElement) field.parentElement.classList.remove('campo-erro');

      try {
        field.style.setProperty('border', '1px solid #d1c6ba', 'important'); // ajuste a cor se necessário
        field.style.setProperty('box-shadow', 'none', 'important');
        field.style.setProperty('outline', 'none', 'important');
      } catch (err) {
        field.style.border = '';
        field.style.boxShadow = '';
      }
    }
    field.removeAttribute('aria-invalid');
    try { field.setCustomValidity(''); } catch (err) {}
    const msg = findMsgEl(field);
    if (msg) msg.style.display = 'none';
  }

  function clearAllErrors() {
    Array.from(form.querySelectorAll('.campo-erro')).forEach(el => el.classList.remove('campo-erro'));

    Array.from(form.querySelectorAll('.wpcf7-not-valid, .wpcf7-form-control.wpcf7-not-valid')).forEach(el => {
      el.classList.remove('wpcf7-not-valid');
    });

    Array.from(form.querySelectorAll('input, textarea, select')).forEach(f => {
      f.removeAttribute('aria-invalid');
      try { f.setCustomValidity(''); } catch (err) {}
      try {
        f.style.setProperty('border', '1px solid #d1c6ba', 'important');
        f.style.setProperty('box-shadow', 'none', 'important');
        f.style.setProperty('outline', 'none', 'important');
      } catch (e) {
        f.style.border = '';
        f.style.boxShadow = '';
      }
    });

    Array.from(form.querySelectorAll('.msg-erro')).forEach(m => m.style.display = 'none');
  }

  function showModal(modal) {
    if (!modal) return;
    modal.style.display = 'flex';
    setTimeout(() => modal.classList.add('show'), 10);
  }

  function hideModal(modal) {
    if (!modal) return;
    modal.classList.remove('show');
    setTimeout(() => modal.style.display = 'none', 250);
  }

  form.querySelectorAll('.modal-close').forEach(btn => {
    btn.addEventListener('click', () => {
      hideModal(btn.closest('.modal'));
    });
  });

  form.addEventListener('submit', (e) => {
    e.preventDefault();

    const nowRequired = Array.from(form.querySelectorAll('input[required], textarea[required], select[required]'));

    const invalids = nowRequired.filter(f => !f.checkValidity());
    invalids.forEach(f => addErrorVisual(f));

    if (invalids.length > 0) {
      showModal(snackbarError);
      invalids[0].focus();
      console.log('[formParticiparAcao] invalids:', invalids.length, 'total .campo-erro:', form.querySelectorAll('.campo-erro').length);
      return;
    }

    hideModal(snackbarError);

    nowRequired.forEach(f => {
      removeErrorVisual(f);

      try { f.setCustomValidity(''); } catch (err) {}
      try {
        f.style.setProperty('border', '1px solid #281414', 'important');
        f.style.setProperty('box-shadow', 'none', 'important');
        f.style.setProperty('outline', 'none', 'important');
      } catch (err) {
      }
    });

    clearAllErrors();

    console.log('[formParticiparAcao] after-clear .campo-erro:', form.querySelectorAll('.campo-erro').length);

    showModal(snackbarSuccess);
  });

  requiredFields.forEach(field => {
    const ev = (field.type === 'checkbox' || field.type === 'radio') ? 'change' : 'input';
    field.addEventListener(ev, () => {
      if (field.checkValidity()) removeErrorVisual(field);
      if (Array.from(form.querySelectorAll('input[required], textarea[required], select[required]'))
          .every(f => f.checkValidity())) {
        hideModal(snackbarError);
      }
    });
    field.addEventListener('blur', () => {
      if (!field.checkValidity()) addErrorVisual(field);
    });
  });
});

document.addEventListener('DOMContentLoaded', function () {
  const form = document.querySelector('form.wpcf7-form');
  if (!form) return;

  form.addEventListener('wpcf7invalid', function () {
    setTimeout(() => {
      const responseOutput = form.querySelector('.wpcf7-response-output');
      const telField = form.querySelector('input[name="tel-932"]');
      const invalidFields = form.querySelectorAll('.wpcf7-not-valid');

      if (telField && invalidFields.length === 1 && telField.classList.contains('wpcf7-not-valid')) {
        responseOutput.textContent = 'Telefone inválido, use o formato com DDD, por exemplo: (21) 99999-8888';
      }
    }, 10);
  });
});

document.addEventListener('DOMContentLoaded', function () {
  function updateNoBorderForCheckboxWraps() {
    document.querySelectorAll('.wpcf7-form-control-wrap.wpcf7-not-valid').forEach(function (wrap) {
      if (wrap.querySelector('input[type="checkbox"], input[type="radio"]')) {
        wrap.classList.add('no-border-checkbox');
      } else {
        wrap.classList.remove('no-border-checkbox');
      }
    });
  }

  updateNoBorderForCheckboxWraps();
  document.addEventListener('wpcf7invalid', function () { setTimeout(updateNoBorderForCheckboxWraps, 8); });
  document.addEventListener('wpcf7submit', function () { setTimeout(updateNoBorderForCheckboxWraps, 8); });
});


