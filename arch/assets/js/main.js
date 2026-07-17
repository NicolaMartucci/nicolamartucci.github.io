// ============================================================
// STUDIO FPARCHITETTO — script comune a tutte le pagine del sito pubblico
// ============================================================
document.addEventListener('DOMContentLoaded', () => {

  /* Header: diventa "solido" dopo un po' di scroll */
  const header = document.querySelector('.site-header');
  if (header) {
    const onScroll = () => {
      header.classList.toggle('-solid', window.scrollY > 40);
    };
    onScroll();
    window.addEventListener('scroll', onScroll, { passive: true });
  }

  /* Menu mobile */
  const toggle = document.querySelector('.nav-toggle');
  const nav = document.querySelector('.main-nav');
  if (toggle && nav) {
    toggle.addEventListener('click', () => {
      nav.classList.toggle('-open');
      header.classList.toggle('-nav-open');
      document.body.style.overflow = nav.classList.contains('-open') ? 'hidden' : '';
    });
    nav.querySelectorAll('a').forEach(a => a.addEventListener('click', () => {
      nav.classList.remove('-open');
      header.classList.remove('-nav-open');
      document.body.style.overflow = '';
    }));
  }

  /* Home: click ovunque nel pannello porta al reparto (non solo sul link) */
  document.querySelectorAll('.split-panel[data-href]').forEach(panel => {
    panel.addEventListener('click', (e) => {
      if (e.target.closest('a')) return; // lascia gestire il link nativo
      window.location.href = panel.dataset.href;
    });
  });

  /* Lightbox galleria Opere: apre l'immagine cliccata a schermo intero */
  const lightbox = document.getElementById('lightbox');
  const cards = Array.from(document.querySelectorAll('.marquee-card'));
  if (lightbox && cards.length) {
    const imgEl = document.getElementById('lightbox-img');
    const nEl = document.getElementById('lightbox-n');
    const tEl = document.getElementById('lightbox-t');
    const closeBtn = document.getElementById('lightbox-close');
    const prevBtn = document.getElementById('lightbox-prev');
    const nextBtn = document.getElementById('lightbox-next');
    let current = 0;

    const bigSrc = (bg) => {
      const m = bg.match(/url\(["']?(.*?)["']?\)/);
      if (!m) return '';
      return m[1].replace(/\/(\d+)\/(\d+)(\?.*)?$/, '/1600/1000');
    };

    const openLightbox = (idx) => {
      current = idx;
      const card = cards[current];
      imgEl.src = bigSrc(card.style.backgroundImage);
      nEl.textContent = card.querySelector('.marquee-caption .n')?.textContent || '';
      tEl.textContent = card.querySelector('.marquee-caption .t')?.textContent || '';
      lightbox.classList.add('-open');
      lightbox.setAttribute('aria-hidden', 'false');
      document.body.style.overflow = 'hidden';
    };
    const closeLightbox = () => {
      lightbox.classList.remove('-open');
      lightbox.setAttribute('aria-hidden', 'true');
      document.body.style.overflow = '';
    };
    const nextImg = () => openLightbox((current + 1) % cards.length);
    const prevImg = () => openLightbox((current - 1 + cards.length) % cards.length);

    cards.forEach((card, idx) => {
      card.addEventListener('click', () => openLightbox(idx));
      card.addEventListener('keydown', (e) => {
        if (e.key === 'Enter' || e.key === ' ') { e.preventDefault(); openLightbox(idx); }
      });
    });
    closeBtn?.addEventListener('click', closeLightbox);
    nextBtn?.addEventListener('click', nextImg);
    prevBtn?.addEventListener('click', prevImg);
    lightbox.addEventListener('click', (e) => { if (e.target === lightbox) closeLightbox(); });
    document.addEventListener('keydown', (e) => {
      if (!lightbox.classList.contains('-open')) return;
      if (e.key === 'Escape') closeLightbox();
      if (e.key === 'ArrowRight') nextImg();
      if (e.key === 'ArrowLeft') prevImg();
    });
  }

});
