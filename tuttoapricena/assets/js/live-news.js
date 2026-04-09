// ============================================================
// TuttoApricena — Live News Fetcher
// Aggiorna le notizie da RSS feeds ogni ora
// Fonti: FoggiaToday, La Gazzetta di Apricena, l'Immediato
// ============================================================
(function () {
  'use strict';

  var CACHE_KEY = 'ta_live_v2';
  var CACHE_TTL = 3600000; // 1 ora

  // RSS feeds che parlano di Apricena
  var RSS_FEEDS = [
    {
      name: 'La Gazzetta di Apricena',
      url: 'https://www.lagazzettadiapricena.it/feed/',
      slug: 'gazzetta-apricena'
    },
    {
      name: 'FoggiaToday - Apricena',
      url: 'https://www.foggiatoday.it/rss/notizie/apricena.xml',
      slug: 'foggiatoday'
    },
    {
      name: "l'Immediato",
      url: 'https://www.immediato.net/tag/apricena/feed/',
      slug: 'immediato'
    },
    {
      name: 'Stato Quotidiano',
      url: 'https://www.statoquotidiano.it/category/capitanata_01/apricena/feed/',
      slug: 'statoquotidiano'
    }
  ];

  // CORS proxy pubblici gratuiti
  var CORS_PROXIES = [
    'https://api.allorigins.win/raw?url=',
    'https://corsproxy.io/?'
  ];

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
    try {
      localStorage.setItem(CACHE_KEY, JSON.stringify({ ts: Date.now(), items: items }));
    } catch (e) {}
  }

  function slugify(s) {
    return (s || '').toLowerCase()
      .replace(/[àá]/g, 'a').replace(/[èéê]/g, 'e')
      .replace(/[ìí]/g, 'i').replace(/[òó]/g, 'o')
      .replace(/[ùú]/g, 'u')
      .replace(/[^a-z0-9]+/g, '-')
      .replace(/^-|-$/g, '')
      .slice(0, 60);
  }

  function getCategory(text) {
    var t = (text || '').toLowerCase();
    if (t.includes('sport') || t.includes('calcio') || t.includes('atletico')) return { nome: 'Sport', slug: 'sport' };
    if (t.includes('cultura') || t.includes('arte') || t.includes('mostra') || t.includes('carnevale')) return { nome: 'Cultura', slug: 'cultura' };
    if (t.includes('econom') || t.includes('lavoro') || t.includes('marmo') || t.includes('cava') || t.includes('aziend')) return { nome: 'Economia', slug: 'economia' };
    if (t.includes('turis') || t.includes('gargano') || t.includes('festival')) return { nome: 'Turismo', slug: 'turismo' };
    if (t.includes('scuol') || t.includes('sanit') || t.includes('medic') || t.includes('social')) return { nome: 'Società', slug: 'societa' };
    return { nome: 'Cronaca', slug: 'cronaca' };
  }

  function stripHtml(html) {
    return (html || '').replace(/<[^>]*>/g, '').replace(/&amp;/g, '&').replace(/&lt;/g, '<').replace(/&gt;/g, '>').replace(/&nbsp;/g, ' ').replace(/&quot;/g, '"').trim();
  }

  function parseRSS(xmlText, sourceName) {
    var items = [];
    try {
      var parser = new DOMParser();
      var doc = parser.parseFromString(xmlText, 'text/xml');
      var entries = doc.querySelectorAll('item');
      entries.forEach(function (item) {
        var title = stripHtml(item.querySelector('title') ? item.querySelector('title').textContent : '');
        var desc = stripHtml(item.querySelector('description') ? item.querySelector('description').textContent : '');
        var link = item.querySelector('link') ? item.querySelector('link').textContent : '#';
        var pubDate = item.querySelector('pubDate') ? item.querySelector('pubDate').textContent : '';
        var imgMatch = (item.querySelector('description') ? item.querySelector('description').textContent : '').match(/src="([^"]+\.(jpg|jpeg|png|webp)[^"]*)"/i);
        var img = imgMatch ? imgMatch[1] : '';

        // Filter: only Apricena-related
        var fullText = (title + ' ' + desc).toLowerCase();
        if (!fullText.includes('apricena') && !fullText.includes('gargan')) return;

        var dateObj = pubDate ? new Date(pubDate) : new Date();
        var dateStr = dateObj.toISOString().split('T')[0];
        var cat = getCategory(fullText);
        var abstract = desc.slice(0, 200) + (desc.length > 200 ? '…' : '');

        items.push({
          id: Date.now() + Math.random(),
          slug: slugify(title) + '-live',
          titolo: title,
          categoria: cat.nome,
          categoriaSlug: cat.slug,
          immagine: img || 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=800&q=80',
          abstract: abstract,
          testo: desc,
          fonte: sourceName,
          fonteUrl: link,
          data: dateStr,
          inEvidenza: false,
          tag: ['live', 'apricena'],
          isLive: true
        });
      });
    } catch (e) {
      console.warn('TuttoApricena: RSS parse error', e);
    }
    return items;
  }

  async function fetchFeed(feed) {
    for (var i = 0; i < CORS_PROXIES.length; i++) {
      try {
        var proxyUrl = CORS_PROXIES[i] + encodeURIComponent(feed.url);
        var resp = await fetch(proxyUrl, { signal: AbortSignal.timeout(8000) });
        if (!resp.ok) continue;
        var text = await resp.text();
        var items = parseRSS(text, feed.name);
        if (items.length) return items;
      } catch (e) {
        // try next proxy
      }
    }
    return [];
  }

  async function fetchAllNews() {
    var cached = getCached();
    if (cached && cached.length) return cached;

    var allItems = [];
    var promises = RSS_FEEDS.map(function (feed) { return fetchFeed(feed); });
    var results = await Promise.allSettled(promises);
    results.forEach(function (r) {
      if (r.status === 'fulfilled' && r.value) {
        allItems = allItems.concat(r.value);
      }
    });

    // Sort by date descending, deduplicate by title similarity
    allItems.sort(function (a, b) { return new Date(b.data) - new Date(a.data); });
    var seen = new Set();
    allItems = allItems.filter(function (item) {
      var key = item.titolo.slice(0, 40).toLowerCase();
      if (seen.has(key)) return false;
      seen.add(key);
      return true;
    }).slice(0, 10);

    if (allItems.length) setCache(allItems);
    return allItems;
  }

  async function updateNews() {
    if (typeof TA === 'undefined') return;
    try {
      var live = await fetchAllNews();
      if (!live || !live.length) return;

      // Merge: live news first, then static (no duplicates by slug)
      var staticSlugs = (TA.notizie || []).map(function (n) { return n.slug; });
      var newLive = live.filter(function (n) {
        return !staticSlugs.some(function (s) { return s === n.slug || n.titolo.slice(0,30) === s.slice(0,30); });
      });

      if (newLive.length) {
        TA.notizie = newLive.concat(TA.notizie || []);
        window.dispatchEvent(new CustomEvent('ta:newsUpdated', { detail: { count: newLive.length } }));
        console.log('TuttoApricena: ' + newLive.length + ' nuove notizie live caricate');
      }
    } catch (e) {
      console.warn('TuttoApricena LiveNews error:', e);
    }
  }

  // Run after page load
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function () { setTimeout(updateNews, 2000); });
  } else {
    setTimeout(updateNews, 2000);
  }

  // Refresh every hour
  setInterval(updateNews, CACHE_TTL);

  window.TALiveNews = {
    update: updateNews,
    clearCache: function () { localStorage.removeItem(CACHE_KEY); },
    forceUpdate: function () { localStorage.removeItem(CACHE_KEY); return updateNews(); }
  };
})();
