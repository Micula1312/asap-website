(() => {
  const openButton = document.querySelector('.calendar-trigger');
  const closeButton = document.querySelector('.calendar-close');
  const overlay = document.querySelector('#calendar-overlay');

  if (openButton && closeButton && overlay) {
    let previousFocus = null;

    const openCalendar = () => {
      previousFocus = document.activeElement;
      overlay.classList.add('is-open');
      overlay.setAttribute('aria-hidden', 'false');
      openButton.setAttribute('aria-expanded', 'true');
      document.body.classList.add('calendar-open');
      closeButton.focus();
    };

    const closeCalendar = () => {
      overlay.classList.remove('is-open');
      overlay.setAttribute('aria-hidden', 'true');
      openButton.setAttribute('aria-expanded', 'false');
      document.body.classList.remove('calendar-open');
      if (previousFocus) previousFocus.focus();
    };

    openButton.addEventListener('click', openCalendar);
    closeButton.addEventListener('click', closeCalendar);

    overlay.addEventListener('click', (event) => {
      if (event.target === overlay) closeCalendar();
    });

    document.addEventListener('keydown', (event) => {
      if (event.key === 'Escape' && overlay.classList.contains('is-open')) {
        closeCalendar();
      }
    });
  }

  const scrollTriggers = document.querySelectorAll('[data-scroll-target]');
  scrollTriggers.forEach((trigger) => {
    trigger.addEventListener('click', () => {
      const target = document.querySelector(trigger.dataset.scrollTarget);
      if (target) target.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  });

  const hero = document.querySelector('.home-video');
  const nextSection = document.querySelector('#home-head');

  if (hero && nextSection) {
    hero.addEventListener('pointerup', (event) => {
      const interactive = event.target.closest('a, button, input, textarea, select, video[controls]');
      if (interactive) return;

      nextSection.scrollIntoView({ behavior: 'smooth', block: 'start' });
    });
  }
})();
