(() => {
  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

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
      if (event.key === 'Escape' && overlay.classList.contains('is-open')) closeCalendar();
    });
  }

  document.querySelectorAll('[data-scroll-target]').forEach((trigger) => {
    trigger.addEventListener('click', () => {
      const target = document.querySelector(trigger.dataset.scrollTarget);
      if (target) target.scrollIntoView({ behavior: reducedMotion ? 'auto' : 'smooth', block: 'start' });
    });
  });

  const hero = document.querySelector('.home-video');
  const nextSection = document.querySelector('#home-head');

  if (hero && nextSection) {
    hero.addEventListener('pointerup', (event) => {
      if (event.target.closest('a, button, input, textarea, select, video[controls]')) return;
      nextSection.scrollIntoView({ behavior: reducedMotion ? 'auto' : 'smooth', block: 'start' });
    });
  }

  if (reducedMotion || !document.body.classList.contains('home')) return;

  const animateIn = (elements, options = {}) => {
    const nodes = typeof elements === 'string' ? document.querySelectorAll(elements) : elements;
    const {
      delay = 0,
      stagger = 90,
      duration = 900,
      x = 0,
      y = 22,
      scale = 0.985,
      blur = 8,
    } = options;

    nodes.forEach((element, index) => {
      element.animate([
        {
          opacity: 0,
          transform: `translate3d(${x}px, ${y}px, 0) scale(${scale})`,
          filter: `blur(${blur}px)`,
        },
        {
          opacity: 1,
          transform: 'translate3d(0, 0, 0) scale(1)',
          filter: 'blur(0px)',
        },
      ], {
        duration,
        delay: delay + index * stagger,
        easing: 'cubic-bezier(.16,1,.3,1)',
        fill: 'both',
      });
    });
  };

  animateIn('.home-video__media, .home-video__fallback', {
    delay: 20,
    stagger: 0,
    duration: 1800,
    y: 0,
    scale: 1.06,
    blur: 16,
  });

  animateIn('.glow', {
    delay: 120,
    stagger: 170,
    duration: 1700,
    y: 35,
    scale: 0.82,
    blur: 28,
  });

  animateIn('.site-brand', { delay: 260, x: -28, y: 0, duration: 850 });
  animateIn('.site-nav a, .site-nav .pill', { delay: 350, stagger: 85, y: -16, duration: 780, scale: 0.94 });
  animateIn('.calendar-trigger', { delay: 720, x: 28, y: 0, duration: 820 });
  animateIn('.home-video__scroll', { delay: 920, y: 18, duration: 850, scale: 0.9 });

  const rainbow = document.querySelector('.rainbow-bar');
  if (rainbow) {
    rainbow.animate([
      { transform: 'scaleX(0)', transformOrigin: 'left center' },
      { transform: 'scaleX(1)', transformOrigin: 'left center' },
    ], {
      duration: 1200,
      delay: 80,
      easing: 'cubic-bezier(.16,1,.3,1)',
      fill: 'both',
    });
  }

  const revealGroups = document.querySelectorAll(
    '.home-head__statement > *, .home-head__intro > *, .section-heading > *, .work-card, .news-row'
  );

  const observer = new IntersectionObserver((entries, obs) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) return;
      animateIn([entry.target], { duration: 950, y: 32, blur: 10 });
      obs.unobserve(entry.target);
    });
  }, { threshold: 0.14, rootMargin: '0px 0px -5% 0px' });

  revealGroups.forEach((element) => observer.observe(element));
})();
