// ============================================================
// TuttoApricena — Live News (RSS, deduplication robusta)
// Fonti: Gazzetta di Apricena, FoggiaToday, l'Immediato, StatoQuotidiano
// ============================================================
(function () {
  'use strict';

  var CACHE_KEY   = 'ta_live_v3';
  var SEEN_KEY    = 'ta_live_seen';
  var CACHE_TTL   = 3600000; // 1 ora

  var RSS_FEEDS = [
    { name: 'La Gazzetta di Apricena',  url: 'https://www.lagazzettadiapricena.it/feed/' },
    { name: 'FoggiaToday',             url: 'https://www.foggiatoday.it/rss/notizie/apricena.xml' },
    { name: "l'Immediato",             url: 'https://www.immediato.net/tag/apricena/feed/' },
    { name: 'Stato Quotidiano',        url: 'https://www.statoquotidiano.it/category/capitanata_01/apricena/feed/' },
    { name: 'Civico93 Apricena',       url: 'https://www.civico93.it/tag/apricena/feed/' }
  ];

  var PROXIES = [
    'https://api.allorigins.win/raw?url=',
    'https://corsproxy.io/?'
  ];

  // ---- Helpers ----
  function norm(s) {
    // Normalize title for dedup: lowercase, strip punctuation, collapse spaces
    return (s || '').toLowerCase()
      .replace(/[àáâ]/g,'a').replace(/[èéê]/g,'e').replace(/[ìí]/g,'i')
      .replace(/[òó]/g,'o').replace(/[ùú]/g,'u')
      .replace(/[^a-z0-9\s]/g,' ').replace(/\s+/g,' ').trim()
      .slice(0, 60);
  }

  function titleHash(title) {
    // Simple hash from normalized title - same title = same hash
    var n = norm(title);
    var h = 0;
    for (var i = 0; i < n.length; i++) {
      h = ((h << 5) - h) + n.charCodeAt(i);
      h |= 0;
    }
    return Math.abs(h).toString(36);
  }

  function getSeenHashes() {
    try { return JSON.parse(localStorage.getItem(SEEN_KEY) || '[]'); } catch(e) { return []; }
  }
  function addSeenHashes(hashes) {
    var seen = getSeenHashes();
    hashes.forEach(function(h){ if(seen.indexOf(h)<0) seen.push(h); });
    // Keep only last 500
    if(seen.length > 500) seen = seen.slice(-500);
    try { localStorage.setItem(SEEN_KEY, JSON.stringify(seen)); } catch(e) {}
  }

  function getCached() {
    try {
      var raw = localStorage.getItem(CACHE_KEY);
      if (!raw) return null;
      var obj = JSON.parse(raw);
      if (Date.now() - obj.ts < CACHE_TTL) return obj.items;
    } catch (e) {}
    return null;
  }
  function setCache(items) {
    try { localStorage.setItem(CACHE_KEY, JSON.stringify({ ts: Date.now(), items: items })); } catch (e) {}
  }

  function slugify(s) {
    return (s || '').toLowerCase()
      .replace(/[àá]/g,'a').replace(/[èé]/g,'e').replace(/[ìí]/g,'i')
      .replace(/[òó]/g,'o').replace(/[ùú]/g,'u')
      .replace(/[^a-z0-9]+/g,'-').replace(/^-|-$/g,'').slice(0, 70);
  }

  function getCategory(text) {
    var t = (text || '').toLowerCase();
    if (/sport|calcio|atletico|basket|tennis/.test(t)) return { nome:'Sport', slug:'sport' };
    if (/cultur|arte|mostr|carnevale|festival|concert|musik|teatro/.test(t)) return { nome:'Cultura', slug:'cultura' };
    if (/econom|lavor|marmo|cava|aziend|impres|commerc/.test(t)) return { nome:'Economia', slug:'economia' };
    if (/turis|gargano|beach|vacanz/.test(t)) return { nome:'Turismo', slug:'turismo' };
    if (/scuol|sanit|medic|social|comuni|polic|municipio|sindac/.test(t)) return { nome:'Società', slug:'societa' };
    return { nome:'Cronaca', slug:'cronaca' };
  }

  function stripHtml(html) {
    return (html || '').replace(/<[^>]*>/g,'')
      .replace(/&amp;/g,'&').replace(/&lt;/g,'<').replace(/&gt;/g,'>')
      .replace(/&nbsp;/g,' ').replace(/&quot;/g,'"').replace(/&#39;/g,"'")
      .replace(/\s+/g,' ').trim();
  }

  function parseRSS(xmlText, sourceName) {
    var items = [];
    try {
      var parser = new DOMParser();
      var doc = parser.parseFromString(xmlText, 'text/xml');
      var entries = doc.querySelectorAll('item');
      entries.forEach(function(item) {
        var titleEl = item.querySelector('title');
        var descEl  = item.querySelector('description') || item.querySelector('summary');
        var linkEl  = item.querySelector('link');
        var dateEl  = item.querySelector('pubDate') || item.querySelector('published');

        var title = titleEl ? stripHtml(titleEl.textContent) : '';
        var desc  = descEl  ? stripHtml(descEl.textContent)  : '';
        var link  = linkEl  ? (linkEl.textContent || linkEl.getAttribute('href') || '#') : '#';
        var date  = dateEl  ? dateEl.textContent : '';

        if (!title || title.length < 5) return;

        // Keep only Apricena-related
        var fullText = (title + ' ' + desc).toLowerCase();
        if (!/apricena|la prucin/.test(fullText)) return;

        // Extract image from content or enclosure
        var img = '';
        var enclosure = item.querySelector('enclosure');
        if (enclosure && enclosure.getAttribute('url') && /\.(jpg|jpeg|png|webp)/i.test(enclosure.getAttribute('url'))) {
          img = enclosure.getAttribute('url');
        }
        if (!img) {
          var imgMatch = (descEl ? descEl.textContent : '').match(/src="([^"]+\.(jpg|jpeg|png|webp)[^"]*)"/i);
          if (imgMatch) img = imgMatch[1];
        }

        var dateObj = date ? new Date(date) : new Date();
        var dateStr = isNaN(dateObj) ? new Date().toISOString().split('T')[0] : dateObj.toISOString().split('T')[0];
        var cat = getCategory(fullText);
        var abstract = desc.length > 250 ? desc.slice(0, 250) + '…' : desc;

        items.push({
          _hash: titleHash(title),
          _sourceSlug: slugify(title),
          titolo: title,
          categoria: cat.nome,
          categoriaSlug: cat.slug,
          immagine: img || '',
          abstract: abstract || title,
          testo: desc || title,
          fonte: sourceName,
          fonteUrl: link.trim(),
          data: dateStr,
          inEvidenza: false,
          isLive: true
        });
      });
    } catch (e) {
      console.warn('TuttoApricena RSS parse error (' + sourceName + '):', e.message);
    }
    return items;
  }

  async function fetchFeed(feed) {
    for (var i = 0; i < PROXIES.length; i++) {
      try {
        var url = PROXIES[i] + encodeURIComponent(feed.url);
        var ctrl = new AbortController();
        var timer = setTimeout(function(){ ctrl.abort(); }, 8000);
        var resp = await fetch(url, { signal: ctrl.signal });
        clearTimeout(timer);
        if (!resp.ok) continue;
        var text = await resp.text();
        var items = parseRSS(text, feed.name);
        if (items.length) return items;
      } catch (e) { /* try next proxy */ }
    }
    return [];
  }

  async function fetchAll() {
    var cached = getCached();
    if (cached && cached.length) return cached;

    // Fetch all feeds in parallel
    var results = await Promise.allSettled(RSS_FEEDS.map(fetchFeed));
    var all = [];
    results.forEach(function(r) {
      if (r.status === 'fulfilled') all = all.concat(r.value);
    });

    // Global dedup by title hash
    var seenHashes = new Set();
    all = all.filter(function(item) {
      if (seenHashes.has(item._hash)) return false;
      seenHashes.add(item._hash);
      return true;
    });

    // Sort by date desc
    all.sort(function(a, b) { return new Date(b.data) - new Date(a.data); });
    all = all.slice(0, 12);

    if (all.length) setCache(all);
    return all;
  }

  async function updateNews() {
    if (typeof TA === 'undefined') return;
    try {
      var live = await fetchAll();
      if (!live || !live.length) return;

      // Get hashes of items we've already shown (ever)
      var seenHashes = getSeenHashes();
      
      // Get normalized titles of existing static news
      var staticNorms = (TA.notizie || []).map(function(n) { return norm(n.titolo); });

      // Filter: skip already-seen hashes AND skip if title too similar to existing
      var newItems = live.filter(function(item) {
        if (seenHashes.indexOf(item._hash) >= 0) return false;
        // Skip if very similar to an existing static news
        var itemNorm = norm(item.titolo);
        var tooSimilar = staticNorms.some(function(sn) {
          if (!sn || !itemNorm) return false;
          // Check if first 40 chars overlap
          return sn.slice(0,40) === itemNorm.slice(0,40);
        });
        return !tooSimilar;
      });

      if (!newItems.length) return;

      // Mark as seen so they won't be duplicated on next run
      addSeenHashes(newItems.map(function(i){ return i._hash; }));

      // Assign final IDs and slugs
      // IMPORTANT: live news link to their fonte URL directly (no internal page = no 404)
      newItems = newItems.map(function(item, idx) {
        return {
          id: 10000 + idx + Date.now() % 1000,
          // slug for internal routing: we use a special prefix
          // BUT we make the card open the fonte URL directly
          slug: 'live--' + item._sourceSlug,
          titolo: item.titolo,
          categoria: item.categoria,
          categoriaSlug: item.categoriaSlug,
          immagine: item.immagine || 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=800&q=80',
          abstract: item.abstract,
          testo: item.testo,
          fonte: item.fonte,
          fonteUrl: item.fonteUrl,
          data: item.data,
          inEvidenza: false,
          tag: ['live'],
          isLive: true,
          // Flag: clicking this opens fonteUrl directly, not an internal page
          externalLink: item.fonteUrl
        };
      });

      // Prepend to TA.notizie
      TA.notizie = newItems.concat(TA.notizie || []);

      window.dispatchEvent(new CustomEvent('ta:newsUpdated', {
        detail: { count: newItems.length }
      }));

      console.log('TuttoApricena LiveNews: +' + newItems.length + ' notizie live');
    } catch (e) {
      console.warn('TuttoApricena LiveNews:', e.message);
    }
  }

  // Init
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function(){ setTimeout(updateNews, 1500); });
  } else {
    setTimeout(updateNews, 1500);
  }
  setInterval(updateNews, CACHE_TTL);

  window.TALiveNews = {
    update: updateNews,
    clearCache: function(){ localStorage.removeItem(CACHE_KEY); localStorage.removeItem(SEEN_KEY); },
    forceUpdate: function(){ localStorage.removeItem(CACHE_KEY); localStorage.removeItem(SEEN_KEY); return updateNews(); }
  };
})();
