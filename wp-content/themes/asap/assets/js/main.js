(() => {
  const reducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

  const openButton = document.querySelector('.calendar-trigger');
  const inlineCalendarButton = document.querySelector('.calendar-trigger-inline');
  const closeButton = document.querySelector('.calendar-close');
  const overlay = document.querySelector('#calendar-overlay');

  if (closeButton && overlay) {
    let previousFocus = null;

    const openCalendar = (source) => {
      previousFocus = source || document.activeElement;
      overlay.classList.add('is-open');
      overlay.setAttribute('aria-hidden', 'false');
      if (openButton) openButton.setAttribute('aria-expanded', 'true');
      document.body.classList.add('calendar-open');
      closeButton.focus();
    };

    const closeCalendar = () => {
      overlay.classList.remove('is-open');
      overlay.setAttribute('aria-hidden', 'true');
      if (openButton) openButton.setAttribute('aria-expanded', 'false');
      document.body.classList.remove('calendar-open');
      if (previousFocus && previousFocus.focus) previousFocus.focus();
    };

    if (openButton) openButton.addEventListener('click', () => openCalendar(openButton));
    if (inlineCalendarButton) inlineCalendarButton.addEventListener('click', () => openCalendar(inlineCalendarButton));
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
  const aboutSection = document.querySelector('#about');

  if (hero && aboutSection) {
    hero.addEventListener('pointerup', (event) => {
      if (event.target.closest('a, button, input, textarea, select, video[controls]')) return;
      aboutSection.scrollIntoView({ behavior: reducedMotion ? 'auto' : 'smooth', block: 'start' });
    });
  }

  const sWordForm = document.querySelector('#asap-s-word-form');
  if (sWordForm) {
    const input = sWordForm.querySelector('input[name="word"]');
    const feedback = sWordForm.querySelector('.home-sword__feedback');
    const phraseWord = document.querySelector('.home-sword__word');
    const wordList = document.querySelector('#asap-s-word-list');

    const addWordToVisualizer = (word) => {
      if (!wordList) return;

      const item = document.createElement('div');
      item.className = 'home-sword__list-item is-new';
      item.style.setProperty('--i', '0');
      item.textContent = word;
      wordList.prepend(item);

      Array.from(wordList.children).forEach((child, index) => {
        child.style.setProperty('--i', String(index));
      });

      while (wordList.children.length > 18) {
        wordList.lastElementChild?.remove();
      }

      if (!reducedMotion) {
        window.setTimeout(() => item.classList.remove('is-new'), 850);
      }
    };

    sWordForm.addEventListener('submit', async (event) => {
      event.preventDefault();
      const word = input.value.trim();
      feedback.textContent = '';

      if (!/^s/i.test(word)) {
        feedback.textContent = 'la parola deve iniziare con S';
        return;
      }

      try {
        const response = await fetch(sWordForm.dataset.endpoint, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify({ word }),
        });
        const data = await response.json();
        if (!response.ok) throw new Error(data?.message || 'errore');

        feedback.textContent = 'saved';
        if (phraseWord) phraseWord.textContent = data.word;
        addWordToVisualizer(data.word);
        input.value = '';
      } catch (error) {
        feedback.textContent = error.message || 'non riesco a salvare la parola';
      }
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
  animateIn('.site-nav a, .site-nav button', { delay: 350, stagger: 70, y: -14, duration: 760, scale: 0.95 });

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

  const revealGroups = document.querySelectorAll('.home-story__label, .home-story__copy > p, .home-story__link, .home-sword__phrase, .home-sword__form, .home-sword__visualizer');
  const observer = new IntersectionObserver((entries, obs) => {
    entries.forEach((entry) => {
      if (!entry.isIntersecting) return;
      animateIn([entry.target], { duration: 950, y: 30, blur: 8 });
      obs.unobserve(entry.target);
    });
  }, { threshold: 0.2, root: document.querySelector('.home-main'), rootMargin: '0px 0px -8% 0px' });

  revealGroups.forEach((element) => observer.observe(element));
})();
