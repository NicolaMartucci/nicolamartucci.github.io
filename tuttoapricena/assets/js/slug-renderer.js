// ============================================================
// TuttoApricena — Slug Page Renderer
// Handles rendering for notizie/SLUG/, eventi/SLUG/, locali/SLUG/
// ============================================================
(function(){
  'use strict';

  function cc(s){ return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;'); }
  function fmt(d,opts){
    try{ return new Date(d).toLocaleDateString('it-IT', opts||{day:'numeric',month:'long',year:'numeric'}); }
    catch(e){ return d||''; }
  }

  // Detect section from URL path
  function getSection(){
    var path = window.location.pathname;
    if(path.indexOf('/notizie/') >= 0) return 'notizie';
    if(path.indexOf('/eventi/')  >= 0) return 'eventi';
    if(path.indexOf('/locali/')  >= 0) return 'locali';
    return '';
  }

  // Get slug from URL
  function getSlug(){
    var qs = new URLSearchParams(window.location.search);
    if(qs.get('slug')) return qs.get('slug');
    var parts = window.location.pathname.replace(/\/$/, '').split('/');
    var s = parts[parts.length - 1];
    return (s === '_slug_' || !s) ? '' : s;
  }

  // Load sessionStorage items (unpublished CMS items)
  function loadSession(){
    try{
      var map = {notizie:'ta_n', eventi:'ta_e', locali:'ta_l'};
      Object.keys(map).forEach(function(k){
        try{
          var raw = sessionStorage.getItem(map[k]);
          if(!raw) return;
          var items = JSON.parse(raw);
          if(!Array.isArray(items)) return;
          var slugs = (TA[k]||[]).map(function(x){ return x.slug; });
          items.forEach(function(it){
            var i = slugs.indexOf(it.slug);
            if(i >= 0){ TA[k][i] = it; }
            else{ TA[k] = (TA[k]||[]).concat([it]); slugs.push(it.slug); }
          });
        }catch(e){}
      });
    }catch(e){}
  }

  function show404(main, section, up){
    var label = section === 'notizie' ? 'Notizia non trovata' :
                section === 'eventi'  ? 'Evento non trovato' : 'Locale non trovato';
    var back  = section === 'notizie' ? up+'notizie/' :
                section === 'eventi'  ? up+'eventi/'  : up+'locali/';
    main.innerHTML =
      '<div style="padding:80px 24px;text-align:center">' +
        '<div style="font-size:5rem;font-weight:900;color:var(--color-accent);font-family:var(--font-display)">404</div>' +
        '<h2 style="font-family:var(--font-display);font-size:1.8rem;color:var(--color-primary);margin:12px 0">' + label + '</h2>' +
        '<p style="color:var(--color-text-muted);margin-bottom:24px">La pagina cercata non esiste o non &egrave; stata ancora pubblicata.</p>' +
        '<a href="' + back + '" class="btn-primary">Torna indietro</a>' +
      '</div>';
    lucide.createIcons();
  }

  function renderNotizia(main, n, up){
    document.title = n.titolo + ' \u2014 TuttoApricena';
    var cat = TA.getCatColor ? TA.getCatColor(n.categoriaSlug) : '#E8A838';
    var testo = (n.testo||n.abstract||'').split('\n\n')
      .map(function(p){ return p.trim() ? '<p>' + cc(p) + '</p>' : ''; }).join('');
    if(!testo) testo = '<p>' + cc(n.abstract||'') + '</p>';

    main.innerHTML =
      '<div class="article-header"><div class="article-header-inner">' +
        '<a href="' + up + 'notizie/" class="back-link"><i data-lucide="arrow-left" width="16" height="16"></i> Tutte le notizie</a>' +
        '<div class="article-meta">' +
          '<span class="cat-badge" style="background:' + cat + '">' + cc(n.categoria||'Notizia') + '</span>' +
          (n.inEvidenza ? '<span style="background:rgba(232,168,56,.15);color:var(--color-accent);font-size:10px;font-weight:700;padding:3px 10px;border-radius:50px">In evidenza</span>' : '') +
        '</div>' +
        '<h1 class="article-title">' + cc(n.titolo) + '</h1>' +
        '<p class="article-abstract">' + cc(n.abstract||'') + '</p>' +
        '<div class="article-info">' +
          '<i data-lucide="calendar" width="13" height="13"></i> ' + fmt(n.data) +
          (n.fonte ? ' &middot; ' + cc(n.fonte) : '') +
        '</div>' +
      '</div></div>' +
      '<div class="article-body">' +
        (n.immagine ? '<img class="article-img" src="' + cc(n.immagine) + '" alt="' + cc(n.titolo) + '" loading="lazy" onerror="this.style.display=\'none\'">' : '') +
        '<div class="prose-content">' + testo + '</div>' +
        (n.fonteUrl && n.fonteUrl !== '#'
          ? '<div class="source-box">' +
              '<i data-lucide="external-link" width="16" height="16" color="var(--color-accent)"></i>' +
              '<span>Fonte: <strong>' + cc(n.fonte||'') + '</strong></span>' +
              '<a href="' + cc(n.fonteUrl) + '" target="_blank" rel="noopener" style="color:var(--color-accent);margin-left:8px">Leggi &rarr;</a>' +
            '</div>'
          : '') +
      '</div>';
    lucide.createIcons();
  }

  function renderEvento(main, e, up){
    document.title = e.titolo + ' \u2014 TuttoApricena';
    var multi = e.dataFine && e.dataFine !== e.dataInizio;
    var dateStr = fmt(e.dataInizio) + (multi ? ' &ndash; ' + fmt(e.dataFine) : '');

    main.innerHTML =
      '<div class="article-header"><div class="article-header-inner">' +
        '<a href="' + up + 'eventi/" class="back-link"><i data-lucide="arrow-left" width="16" height="16"></i> Tutti gli eventi</a>' +
        '<div class="article-meta"><span class="cat-badge" style="background:var(--color-accent);color:var(--color-primary)">' + cc(e.categoria||'Evento') + '</span></div>' +
        '<h1 class="article-title">' + cc(e.titolo) + '</h1>' +
        '<div class="article-info">' +
          '<i data-lucide="calendar" width="13" height="13"></i> ' + dateStr +
          ' &middot; <i data-lucide="clock" width="13" height="13"></i> ' + cc(e.orario||'') +
          ' &middot; <i data-lucide="map-pin" width="13" height="13"></i> ' + cc(e.luogo||'') +
        '</div>' +
      '</div></div>' +
      '<div class="article-body">' +
        (e.immagine ? '<img class="article-img" src="' + cc(e.immagine) + '" alt="' + cc(e.titolo) + '" loading="lazy" onerror="this.style.display=\'none\'">' : '') +
        '<div class="prose-content"><p style="font-size:17px;line-height:1.8">' + cc(e.descrizione||'') + '</p></div>' +
        '<div style="background:#fff;border-radius:16px;padding:24px;margin-top:28px;box-shadow:0 4px 20px rgba(26,26,46,.07);display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:18px">' +
          '<div><div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--color-text-muted);margin-bottom:4px">Data</div><strong>' + dateStr + '</strong></div>' +
          '<div><div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--color-text-muted);margin-bottom:4px">Orario</div><strong>' + cc(e.orario||'Da definire') + '</strong></div>' +
          '<div><div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--color-text-muted);margin-bottom:4px">Luogo</div><strong>' + cc(e.luogo||'Da definire') + '</strong></div>' +
        '</div>' +
        (e.ticketUrl
          ? '<div style="margin-top:24px"><a href="' + cc(e.ticketUrl) + '" target="_blank" rel="noopener" class="btn-primary" style="display:inline-flex;align-items:center;gap:8px;font-size:15px;padding:13px 28px">' +
              '<i data-lucide="ticket" width="18" height="18"></i> Acquista biglietti</a></div>'
          : '') +
      '</div>';
    lucide.createIcons();
  }

  function renderLocale(main, l, up){
    document.title = l.nome + ' \u2014 TuttoApricena';
    main.innerHTML =
      '<div class="article-header"><div class="article-header-inner">' +
        '<a href="' + up + 'locali/" class="back-link"><i data-lucide="arrow-left" width="16" height="16"></i> Tutti i locali</a>' +
        '<div class="article-meta"><span class="cat-badge" style="background:var(--color-primary)">' + cc(l.tipo||'Locale') + '</span></div>' +
        '<h1 class="article-title">' + cc(l.nome) + '</h1>' +
        '<div class="article-info">' +
          '<i data-lucide="map-pin" width="13" height="13"></i> ' + cc(l.indirizzo||'') +
          (l.telefono ? ' &middot; <a href="tel:' + cc(l.telefono) + '" style="color:var(--color-accent)">' + cc(l.telefono) + '</a>' : '') +
        '</div>' +
      '</div></div>' +
      '<div class="article-body">' +
        (l.immagine ? '<img class="article-img" src="' + cc(l.immagine) + '" alt="' + cc(l.nome) + '" loading="lazy" onerror="this.style.display=\'none\'">' : '') +
        '<div class="prose-content"><p style="font-size:17px;line-height:1.8">' + cc(l.descrizione||'') + '</p></div>' +
        '<div style="background:#fff;border-radius:16px;padding:24px;margin-top:28px;box-shadow:0 4px 20px rgba(26,26,46,.07);display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:18px">' +
          '<div><div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--color-text-muted);margin-bottom:4px">Indirizzo</div><strong>' + cc(l.indirizzo||'&mdash;') + '</strong></div>' +
          '<div><div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--color-text-muted);margin-bottom:4px">Orari</div><strong>' + cc(l.orari||'&mdash;') + '</strong></div>' +
          (l.telefono ? '<div><div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--color-text-muted);margin-bottom:4px">Telefono</div><a href="tel:' + cc(l.telefono) + '" style="color:var(--color-accent);font-weight:700">' + cc(l.telefono) + '</a></div>' : '') +
          (l.sitoWeb ? '<div><div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--color-text-muted);margin-bottom:4px">Sito web</div><a href="' + cc(l.sitoWeb) + '" target="_blank" style="color:var(--color-accent);font-weight:700">Visita &rarr;</a></div>' : '') +
        '</div>' +
      '</div>';
    lucide.createIcons();
  }

  // Main render entry point
  window.TARenderer = {
    render: function(sectionOverride){
      if(typeof TA === 'undefined'){ console.error('TA not loaded'); return; }
      loadSession();

      var section = sectionOverride || getSection();
      var slug    = getSlug();
      var main    = document.getElementById('pg-main');
      if(!main) return;

      // Detect up path from current URL depth
      var path = window.location.pathname;
      // Count how deep we are from the repo root
      var parts = path.replace(/\/$/, '').split('/').filter(Boolean);
      // For /tuttoapricena/notizie/slug/ -> up = ../../
      var depth = parts.length - 1; // minus the root repo folder
      // Simple: always use ../../ for section/slug/ pages
      var up = '../../';

      if(!section || !slug){ show404(main, section||'notizie', up); return; }

      if(section === 'notizie'){
        var item = (TA.notizie||[]).find(function(x){ return x.slug === slug; });
        item ? renderNotizia(main, item, up) : show404(main, section, up);
      } else if(section === 'eventi'){
        var item = (TA.eventi||[]).find(function(x){ return x.slug === slug; });
        item ? renderEvento(main, item, up) : show404(main, section, up);
      } else if(section === 'locali'){
        var item = (TA.locali||[]).find(function(x){ return x.slug === slug; });
        item ? renderLocale(main, item, up) : show404(main, section, up);
      } else {
        show404(main, section, up);
      }
    }
  };
})();
