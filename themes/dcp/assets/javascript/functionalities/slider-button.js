document.addEventListener('DOMContentLoaded', function () {
  const sliders = document.querySelectorAll('.slider-mobile');

  if (!sliders.length) return;

  sliders.forEach(slider => {

    const wrapper = document.createElement('div');
    wrapper.className = 'slider-wrapper-dynamic';

    const createArrow = (direction) => {
      const btn = document.createElement('button');
      btn.className = `slider-arrow ${direction}`;
      btn.innerHTML = `
      <svg xmlns="http://www.w3.org/2000/svg" width="16" height="17" viewBox="0 0 16 17" fill="none">
        <path d="M11.001 2C11.0667 2 11.1317 2.01295 11.1924 2.03809C11.2531 2.06329 11.308 2.1009 11.3545 2.14746C11.4011 2.19391 11.4387 2.24883 11.4639 2.30957C11.489 2.37024 11.502 2.4353 11.502 2.50098C11.502 2.56665 11.489 2.63171 11.4639 2.69238C11.4387 2.75313 11.4011 2.80805 11.3545 2.85449L5.70801 8.50098L11.3545 14.1475C11.4484 14.2413 11.502 14.3682 11.502 14.501C11.502 14.6338 11.4484 14.7606 11.3545 14.8545C11.2606 14.9484 11.1338 15.002 11.001 15.002C10.8682 15.002 10.7413 14.9484 10.6475 14.8545L4.64746 8.85449C4.6009 8.80805 4.56329 8.75313 4.53809 8.69238C4.51295 8.63171 4.5 8.56665 4.5 8.50098C4.5 8.4353 4.51295 8.37024 4.53809 8.30957C4.56329 8.24883 4.6009 8.19391 4.64746 8.14746L10.6475 2.14746C10.6939 2.1009 10.7488 2.06329 10.8096 2.03809C10.8702 2.01295 10.9353 2 11.001 2Z" fill="#F9F3EA"/>
      </svg>`;
      return btn;
    };

    const leftArrow = createArrow('left');
    const rightArrow = createArrow('right');

    const scrollAmount = slider.offsetWidth * 0.8;

    leftArrow.addEventListener('click', () => {
      slider.scrollBy({ left: -scrollAmount, behavior: 'smooth' });
    });

    rightArrow.addEventListener('click', () => {
      slider.scrollBy({ left: scrollAmount, behavior: 'smooth' });
    });

    slider.parentNode.insertBefore(wrapper, slider);
    wrapper.appendChild(leftArrow);
    wrapper.appendChild(slider);
    wrapper.appendChild(rightArrow);
  });
});
