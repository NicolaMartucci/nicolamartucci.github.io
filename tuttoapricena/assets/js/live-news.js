// ============================================================
// TuttoApricena — Live News Auto-Update
// Aggiorna le notizie ogni ora tramite Claude API
// ============================================================
(function() {
  'use strict';
  
  var CACHE_KEY = 'ta_live_news';
  var CACHE_TTL = 3600000; // 1 ora in ms
  var SOURCES = [
    { name: 'La Gazzetta di Apricena', url: 'https://www.lagazzettadiapricena.it/', slug: 'gazzettadiapricena' },
    { name: 'FoggiaToday - Apricena', url: 'https://www.foggiatoday.it/notizie/apricena/', slug: 'foggiatoday' },
    { name: "l'Immediato - Apricena", url: 'https://www.immediato.net/tag/apricena/', slug: 'immediato' }
  ];

  // Check cache
  function getCached() {
    try {
      var cached = localStorage.getItem(CACHE_KEY);
      if (!cached) return null;
      var parsed = JSON.parse(cached);
      if (Date.now() - parsed.timestamp < CACHE_TTL) return parsed.items;
    } catch(e) {}
    return null;
  }
  
  function setCache(items) {
    try {
      localStorage.setItem(CACHE_KEY, JSON.stringify({ timestamp: Date.now(), items: items }));
    } catch(e) {}
  }

  // Fetch live news via Claude API
  async function fetchLiveNews() {
    var cached = getCached();
    if (cached && cached.length) return cached;
    
    try {
      var prompt = 'Cerca le ultime notizie di Apricena (FG), Puglia, Italia da questi siti:\n' +
        '- https://www.lagazzettadiapricena.it/\n' +
        '- https://www.foggiatoday.it/notizie/apricena/\n' +
        '- https://www.immediato.net/tag/apricena/\n\n' +
        'Restituisci SOLO un array JSON con le ultime 6 notizie nel formato:\n' +
        '[{"titolo":"...","abstract":"...","data":"YYYY-MM-DD","fonte":"...","fonteUrl":"...","categoria":"Cronaca|Cultura|Sport|Economia|Società|Turismo","categoriaSlug":"cronaca|cultura|sport|economia|societa|turismo","slug":"slug-generato-dal-titolo","immagine":""}]\n' +
        'Solo JSON puro, nessun testo aggiuntivo, nessun markdown.';

      var response = await fetch('https://api.anthropic.com/v1/messages', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({
          model: 'claude-sonnet-4-20250514',
          max_tokens: 2000,
          tools: [{ type: 'web_search_20250305', name: 'web_search' }],
          messages: [{ role: 'user', content: prompt }]
        })
      });

      if (!response.ok) throw new Error('API error ' + response.status);
      var data = await response.json();
      
      // Extract text from response
      var text = (data.content || [])
        .filter(function(b) { return b.type === 'text'; })
        .map(function(b) { return b.text; })
        .join('');
      
      // Parse JSON from response
      var jsonMatch = text.match(/\[[\s\S]*\]/);
      if (!jsonMatch) throw new Error('No JSON in response');
      var items = JSON.parse(jsonMatch[0]);
      
      // Validate and sanitize
      items = items.filter(function(it) { return it.titolo && it.data; })
        .slice(0, 6)
        .map(function(it, i) {
          return {
            id: 900 + i,
            slug: it.slug || ('notizia-live-' + Date.now() + '-' + i),
            titolo: String(it.titolo || '').slice(0, 200),
            categoria: it.categoria || 'Cronaca',
            categoriaSlug: it.categoriaSlug || 'cronaca',
            immagine: it.immagine || 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=800&q=80',
            abstract: String(it.abstract || '').slice(0, 400),
            testo: it.abstract || '',
            fonte: it.fonte || 'La Gazzetta di Apricena',
            fonteUrl: it.fonteUrl || '#',
            data: it.data || new Date().toISOString().split('T')[0],
            inEvidenza: false,
            tag: ['notizie-live'],
            isLive: true
          };
        });
      
      setCache(items);
      return items;
    } catch(e) {
      console.warn('TuttoApricena LiveNews: impossibile aggiornare notizie.', e.message);
      return null;
    }
  }

  // Merge live news with static news, no duplicates
  function mergeNews(staticNews, liveNews) {
    if (!liveNews || !liveNews.length) return staticNews;
    var existingSlugs = staticNews.map(function(n) { return n.slug; });
    var newItems = liveNews.filter(function(n) {
      return !existingSlugs.some(function(s) { return s === n.slug || n.titolo === s; });
    });
    // Prepend fresh live news, keep static (non-live) ones after
    return newItems.concat(staticNews).slice(0, 12);
  }

  // Update the TA.notizie array with live news
  async function updateNews() {
    if (typeof TA === 'undefined') return;
    var live = await fetchLiveNews();
    if (live && live.length) {
      TA.notizie = mergeNews(TA.notizie || [], live);
      // Dispatch event so pages can re-render
      window.dispatchEvent(new CustomEvent('ta:newsUpdated', { detail: { live: live } }));
    }
  }

  // Run on load and then every hour
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', updateNews);
  } else {
    updateNews();
  }
  setInterval(updateNews, CACHE_TTL);

  // Expose
  window.TALiveNews = { fetch: fetchLiveNews, update: updateNews, clearCache: function() { localStorage.removeItem(CACHE_KEY); } };
})();
