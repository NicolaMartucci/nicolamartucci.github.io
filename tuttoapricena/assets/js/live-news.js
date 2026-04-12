// ============================================================
// TuttoApricena — Live News via RSS2JSON
// Aggiorna notizie ogni ora da FoggiaToday, Gazzetta, Immediato
// ============================================================
(function () {
  'use strict';

  var CACHE_KEY = 'ta_live_v4';
  var SEEN_KEY  = 'ta_live_seen';
  var CACHE_TTL = 3600000; // 1 ora

  // RSS feeds su Apricena - usiamo rss2json.com (gratuito, CORS ok)
  var RSS_FEEDS = [
    { name: 'La Gazzetta di Apricena',  url: 'https://www.lagazzettadiapricena.it/feed/' },
    { name: 'FoggiaToday',             url: 'https://www.foggiatoday.it/rss/notizie/apricena.xml' },
    { name: "l'Immediato",             url: 'https://www.immediato.net/tag/apricena/feed/' },
    { name: 'Stato Quotidiano',        url: 'https://www.statoquotidiano.it/category/capitanata_01/apricena/feed/' }
  ];

  // Proxies in ordine di affidabilità
  var PROXIES = [
    function(url){ return 'https://api.rss2json.com/v1/api.json?rss_url=' + encodeURIComponent(url); },
    function(url){ return 'https://api.allorigins.win/raw?url=' + encodeURIComponent(url); }
  ];

  // ---- Helpers ----
  function norm(s) {
    return (s||'').toLowerCase()
      .replace(/[àáâ]/g,'a').replace(/[èéê]/g,'e').replace(/[ìí]/g,'i')
      .replace(/[òó]/g,'o').replace(/[ùú]/g,'u')
      .replace(/[^a-z0-9\s]/g,' ').replace(/\s+/g,' ').trim().slice(0,60);
  }
  function titleHash(t) {
    var n=norm(t), h=0;
    for(var i=0;i<n.length;i++){h=((h<<5)-h)+n.charCodeAt(i);h|=0;}
    return Math.abs(h).toString(36);
  }
  function getSeenHashes(){try{return JSON.parse(localStorage.getItem(SEEN_KEY)||'[]');}catch(e){return[];}}
  function addSeenHashes(hashes){
    var s=getSeenHashes();
    hashes.forEach(function(h){if(s.indexOf(h)<0)s.push(h);});
    if(s.length>500)s=s.slice(-500);
    try{localStorage.setItem(SEEN_KEY,JSON.stringify(s));}catch(e){}
  }
  function getCached(){try{var r=localStorage.getItem(CACHE_KEY);if(!r)return null;var o=JSON.parse(r);if(Date.now()-o.ts<CACHE_TTL)return o.items;}catch(e){}return null;}
  function setCache(items){try{localStorage.setItem(CACHE_KEY,JSON.stringify({ts:Date.now(),items:items}));}catch(e){}}

  function slugify(s){
    return(s||'').toLowerCase()
      .replace(/[àá]/g,'a').replace(/[èé]/g,'e').replace(/[ìí]/g,'i')
      .replace(/[òó]/g,'o').replace(/[ùú]/g,'u')
      .replace(/[^a-z0-9]+/g,'-').replace(/^-|-$/g,'').slice(0,70);
  }
  function getCategory(text){
    var t=(text||'').toLowerCase();
    if(/sport|calcio|atletico/.test(t))return{nome:'Sport',slug:'sport'};
    if(/cultur|arte|carnevale|festival/.test(t))return{nome:'Cultura',slug:'cultura'};
    if(/econom|lavoro|marmo|cava|aziend/.test(t))return{nome:'Economia',slug:'economia'};
    if(/turis|gargano/.test(t))return{nome:'Turismo',slug:'turismo'};
    if(/scuol|sanit|medic|social|sindac/.test(t))return{nome:'Società',slug:'societa'};
    return{nome:'Cronaca',slug:'cronaca'};
  }
  function strip(html){
    return(html||'').replace(/<[^>]*>/g,'')
      .replace(/&amp;/g,'&').replace(/&lt;/g,'<').replace(/&gt;/g,'>')
      .replace(/&nbsp;/g,' ').replace(/&quot;/g,'"').replace(/&#39;/g,"'")
      .replace(/\s+/g,' ').trim();
  }

  // Parse rss2json.com response format
  function parseRss2Json(data, sourceName) {
    var items = [];
    try {
      var feed = data.items || [];
      feed.forEach(function(item) {
        var title = strip(item.title||'');
        var desc  = strip(item.description||item.content||'');
        var link  = item.link||item.guid||'#';
        var date  = item.pubDate||'';
        var img   = item.enclosure||(item.thumbnail)||'';
        if(img && typeof img === 'object') img = img.link||img.url||'';

        if(!title || title.length < 5) return;
        var full = (title+' '+desc).toLowerCase();
        if(!/apricena|la prucin/.test(full)) return;

        var d = date ? new Date(date) : new Date();
        var ds = isNaN(d) ? new Date().toISOString().split('T')[0] : d.toISOString().split('T')[0];
        var cat = getCategory(full);
        items.push({
          _hash: titleHash(title),
          _slug: slugify(title),
          titolo: title,
          categoria: cat.nome, categoriaSlug: cat.slug,
          immagine: (typeof img==='string'&&img&&!img.includes('pixel'))?img:'',
          abstract: desc.slice(0,250)+(desc.length>250?'…':''),
          testo: desc, fonte: sourceName,
          fonteUrl: typeof link==='string'?link.trim():'#',
          data: ds, inEvidenza: false, isLive: true
        });
      });
    } catch(e) { console.warn('rss2json parse error:', e.message); }
    return items;
  }

  // Parse raw XML (allorigins fallback)
  function parseXML(xmlText, sourceName) {
    var items = [];
    try {
      var parser = new DOMParser();
      var doc = parser.parseFromString(xmlText, 'text/xml');
      doc.querySelectorAll('item').forEach(function(item) {
        var title = strip(item.querySelector('title') ? item.querySelector('title').textContent : '');
        var desc  = strip(item.querySelector('description') ? item.querySelector('description').textContent : '');
        var link  = item.querySelector('link') ? item.querySelector('link').textContent : '#';
        var date  = item.querySelector('pubDate') ? item.querySelector('pubDate').textContent : '';
        if(!title||title.length<5) return;
        var full=(title+' '+desc).toLowerCase();
        if(!/apricena/.test(full)) return;
        var d=date?new Date(date):new Date();
        var ds=isNaN(d)?new Date().toISOString().split('T')[0]:d.toISOString().split('T')[0];
        var cat=getCategory(full);
        items.push({
          _hash:titleHash(title),_slug:slugify(title),
          titolo:title,categoria:cat.nome,categoriaSlug:cat.slug,
          immagine:'',abstract:desc.slice(0,250),testo:desc,
          fonte:sourceName,fonteUrl:link.trim(),data:ds,
          inEvidenza:false,isLive:true
        });
      });
    } catch(e) {}
    return items;
  }

  async function fetchFeed(feed) {
    // Try rss2json first (designed for RSS, no CORS issues)
    try {
      var url = PROXIES[0](feed.url);
      var ctrl = new AbortController();
      var t = setTimeout(function(){ctrl.abort();}, 8000);
      var r = await fetch(url, {signal: ctrl.signal});
      clearTimeout(t);
      if (r.ok) {
        var data = await r.json();
        if (data.status === 'ok' || data.items) {
          var items = parseRss2Json(data, feed.name);
          if (items.length) return items;
        }
      }
    } catch(e) {}
    // Fallback: allorigins raw XML
    try {
      var url2 = PROXIES[1](feed.url);
      var ctrl2 = new AbortController();
      var t2 = setTimeout(function(){ctrl2.abort();}, 8000);
      var r2 = await fetch(url2, {signal: ctrl2.signal});
      clearTimeout(t2);
      if (r2.ok) {
        var text = await r2.text();
        return parseXML(text, feed.name);
      }
    } catch(e) {}
    return [];
  }

  async function fetchAll() {
    var cached = getCached();
    if (cached && cached.length) return cached;
    var all = [];
    var results = await Promise.allSettled(RSS_FEEDS.map(fetchFeed));
    results.forEach(function(r){ if(r.status==='fulfilled') all=all.concat(r.value||[]); });
    // Dedup by title hash
    var seen = new Set();
    all = all.filter(function(i){ if(seen.has(i._hash))return false; seen.add(i._hash); return true; });
    all.sort(function(a,b){ return new Date(b.data)-new Date(a.data); });
    all = all.slice(0,10);
    if(all.length) setCache(all);
    return all;
  }

  async function updateNews() {
    if (typeof TA === 'undefined') return;
    try {
      var live = await fetchAll();
      if (!live||!live.length) return;
      var seenHashes = getSeenHashes();
      var staticNorms = (TA.notizie||[]).map(function(n){ return norm(n.titolo); });
      var newItems = live.filter(function(item) {
        if (seenHashes.indexOf(item._hash) >= 0) return false;
        return !staticNorms.some(function(sn){ return sn&&sn.slice(0,40)===norm(item.titolo).slice(0,40); });
      });
      if (!newItems.length) return;
      addSeenHashes(newItems.map(function(i){ return i._hash; }));
      var final = newItems.map(function(item, idx) {
        return {
          id: 10000 + idx,
          slug: 'live--' + item._slug,
          titolo: item.titolo,
          categoria: item.categoria, categoriaSlug: item.categoriaSlug,
          immagine: item.immagine || 'https://images.unsplash.com/photo-1504711434969-e33886168f5c?w=800&q=80',
          abstract: item.abstract, testo: item.testo,
          fonte: item.fonte, fonteUrl: item.fonteUrl,
          data: item.data, inEvidenza: false, tag: ['live'],
          isLive: true, externalLink: item.fonteUrl
        };
      });
      TA.notizie = final.concat(TA.notizie||[]);
      window.dispatchEvent(new CustomEvent('ta:newsUpdated', {detail:{count:final.length}}));
      console.log('TuttoApricena LiveNews: +'+final.length+' notizie');
    } catch(e) { console.warn('LiveNews error:', e.message); }
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function(){ setTimeout(updateNews, 2000); });
  } else {
    setTimeout(updateNews, 2000);
  }
  setInterval(updateNews, CACHE_TTL);

  window.TALiveNews = {
    update: updateNews,
    clearCache: function(){ localStorage.removeItem(CACHE_KEY); localStorage.removeItem(SEEN_KEY); },
    forceUpdate: function(){ localStorage.removeItem(CACHE_KEY); localStorage.removeItem(SEEN_KEY); return updateNews(); }
  };
})();
