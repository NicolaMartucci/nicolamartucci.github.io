/* ============================================================
   TuttoApricena — index.js
   Legge data.json e popola dinamicamente il sito
   ============================================================ */

(function () {
  'use strict';

  const DATA_URL = './assets/data.json';

  async function loadData() {
    try {
      const r = await fetch(DATA_URL + '?v=' + Date.now());
      if (!r.ok) throw new Error('HTTP ' + r.status);
      return await r.json();
    } catch (e) {
      console.error('Impossibile caricare i dati:', e);
      return null;
    }
  }

  function esc(str) {
    const d = document.createElement('div');
    d.textContent = str || '';
    return d.innerHTML;
  }

  function formatDate(iso) {
    if (!iso) return '';
    try {
      return new Date(iso).toLocaleDateString('it-IT', { day: '2-digit', month: 'long', year: 'numeric' });
    } catch { return iso; }
  }

  function imgOrPlaceholder(src, alt, cls) {
    if (src) return `<img src="${esc(src)}" alt="${esc(alt)}" class="${cls}" loading="lazy">`;
    return `<div class="${cls}" style="background:linear-gradient(135deg,#e8f5ee,#d4edda);display:flex;align-items:center;justify-content:center;color:#aaa;font-size:2rem;">📷</div>`;
  }

  /* ─── SITO ─── */
  function applySite(site) {
    // Titolo
    document.title = site.title || 'TuttoApricena';
    const titleEl = document.getElementById('site-title');
    const subtitleEl = document.getElementById('site-subtitle');
    if (titleEl) titleEl.textContent = site.title || 'TuttoApricena';
    if (subtitleEl) subtitleEl.textContent = site.subtitle || '';

    // Logo
    const logoEl = document.getElementById('site-logo');
    if (logoEl && site.logo) {
      logoEl.src = site.logo;
      logoEl.classList.add('visible');
    }

    // Header background
    const headerBg = document.querySelector('.header-bg');
    if (headerBg) {
      // Usa primo headerImages oppure headerImage
      const imgs = site.headerImages && site.headerImages.length ? site.headerImages : (site.headerImage ? [site.headerImage] : []);
      if (imgs.length > 0) {
        headerBg.style.backgroundImage = `url('${imgs[0]}')`;
        // Slideshow se più immagini
        if (imgs.length > 1) {
          let idx = 0;
          setInterval(() => {
            idx = (idx + 1) % imgs.length;
            headerBg.style.backgroundImage = `url('${imgs[idx]}')`;
          }, 5000);
        }
      }
    }

    // Colore primario custom
    if (site.primaryColor) {
      document.documentElement.style.setProperty('--primary', site.primaryColor);
    }

    // Social footer
    const socialDiv = document.getElementById('social-links');
    if (socialDiv) {
      let html = '';
      if (site.facebook) html += `<a href="${esc(site.facebook)}" class="social-link" target="_blank" rel="noopener">📘 Facebook</a>`;
      if (site.instagram) html += `<a href="${esc(site.instagram)}" class="social-link" target="_blank" rel="noopener">📸 Instagram</a>`;
      if (site.email) html += `<a href="mailto:${esc(site.email)}" class="social-link">✉️ ${esc(site.email)}</a>`;
      socialDiv.innerHTML = html;
    }

    // Copyright
    const copy = document.getElementById('footer-copy');
    if (copy) copy.textContent = `© ${new Date().getFullYear()} ${site.title || 'TuttoApricena'} — Tutti i diritti riservati`;
  }

  /* ─── NOTIZIE ─── */
  function renderNotizie(list) {
    const el = document.getElementById('notizie-list');
    if (!el) return;
    const sorted = [...(list || [])].sort((a, b) => new Date(b.data || 0) - new Date(a.data || 0));
    if (!sorted.length) {
      el.innerHTML = `<div class="empty-state"><span>📰</span>Nessuna notizia disponibile</div>`;
      return;
    }
    el.innerHTML = sorted.map(n => `
      <div class="news-item">
        ${imgOrPlaceholder(n.immagine, n.titolo, 'news-item-img')}
        <div class="news-item-body">
          ${n.categoria ? `<span class="card-category">${esc(n.categoria)}</span>` : ''}
          <div class="news-item-title">${esc(n.titolo)}</div>
          <div class="card-date">${formatDate(n.data)}</div>
          <div class="news-item-excerpt">${esc(n.contenuto)}</div>
        </div>
      </div>`).join('');
  }

  /* ─── EVENTI ─── */
  function renderEventi(list) {
    const el = document.getElementById('eventi-list');
    if (!el) return;
    const sorted = [...(list || [])].sort((a, b) => new Date(a.data || 0) - new Date(b.data || 0));
    if (!sorted.length) {
      el.innerHTML = `<div class="empty-state"><span>📅</span>Nessun evento in programma</div>`;
      return;
    }
    el.innerHTML = `<div class="cards-grid">${sorted.map(e => `
      <div class="card">
        ${imgOrPlaceholder(e.immagine, e.titolo, 'card-img')}
        <div class="card-body">
          <span class="card-category">📅 ${formatDate(e.data)}</span>
          <div class="card-title">${esc(e.titolo)}</div>
          ${e.luogo ? `<div class="card-date">📍 ${esc(e.luogo)}</div>` : ''}
          <div class="card-text">${esc(e.descrizione)}</div>
        </div>
      </div>`).join('')}</div>`;
  }

  /* ─── SERVIZI ─── */
  function renderServizi(list) {
    const el = document.getElementById('servizi-list');
    if (!el) return;
    if (!list || !list.length) {
      el.innerHTML = `<div class="empty-state"><span>🏛️</span>Nessun servizio disponibile</div>`;
      return;
    }
    el.innerHTML = `<div class="cards-grid">${list.map(s => `
      <div class="card">
        ${imgOrPlaceholder(s.immagine, s.nome, 'card-img')}
        <div class="card-body">
          ${s.categoria ? `<span class="card-category">${esc(s.categoria)}</span>` : ''}
          <div class="card-title">${esc(s.nome)}</div>
          ${s.indirizzo ? `<div class="card-date">📍 ${esc(s.indirizzo)}</div>` : ''}
          ${s.telefono ? `<div class="card-date">📞 ${esc(s.telefono)}</div>` : ''}
          <div class="card-text">${esc(s.descrizione)}</div>
        </div>
      </div>`).join('')}</div>`;
  }

  /* ─── LOCALI ─── */
  function renderLocali(list) {
    const el = document.getElementById('locali-list');
    if (!el) return;
    if (!list || !list.length) {
      el.innerHTML = `<div class="empty-state"><span>🍕</span>Nessun locale disponibile</div>`;
      return;
    }
    el.innerHTML = `<div class="cards-grid">${list.map(l => `
      <div class="card">
        ${imgOrPlaceholder(l.immagine, l.nome, 'card-img')}
        <div class="card-body">
          ${l.tipo ? `<span class="card-category">${esc(l.tipo)}</span>` : ''}
          <div class="card-title">${esc(l.nome)}</div>
          ${l.indirizzo ? `<div class="card-date">📍 ${esc(l.indirizzo)}</div>` : ''}
          ${l.telefono ? `<div class="card-date">📞 ${esc(l.telefono)}</div>` : ''}
          <div class="card-text">${esc(l.descrizione)}</div>
        </div>
      </div>`).join('')}</div>`;
  }

  /* ─── SPONSOR ─── */
  function renderSponsor(list) {
    const el = document.getElementById('sponsor-list');
    const section = document.getElementById('section-sponsor');
    if (!el) return;
    if (!list || !list.length) {
      if (section) section.style.display = 'none';
      return;
    }
    if (section) section.style.display = '';
    el.innerHTML = `<div class="sponsors-grid">${list.map(s => {
      const inner = `
        ${s.logo ? `<img src="${esc(s.logo)}" alt="${esc(s.nome)}" class="sponsor-logo">` : ''}
        <span class="sponsor-name">${esc(s.nome)}</span>`;
      return s.sito
        ? `<a href="${esc(s.sito)}" class="sponsor-item" target="_blank" rel="noopener sponsored">${inner}</a>`
        : `<div class="sponsor-item">${inner}</div>`;
    }).join('')}</div>`;
  }

  /* ─── INIT ─── */
  async function init() {
    const data = await loadData();
    if (!data) return;
    if (data.site) applySite(data.site);
    renderNotizie(data.notizie);
    renderEventi(data.eventi);
    renderServizi(data.servizi);
    renderLocali(data.locali);
    renderSponsor(data.sponsor);
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', init);
  } else {
    init();
  }
})();
