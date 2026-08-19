(() => {
  const openButton = document.querySelector('.calendar-trigger');
  const closeButton = document.querySelector('.calendar-close');
  const overlay = document.querySelector('#calendar-overlay');

  if (!openButton || !closeButton || !overlay) return;

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
})();
