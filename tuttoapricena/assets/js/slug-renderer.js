(function(){'use strict';function cc(s){return String(s||'').replace(/&/g,'&amp;').replace(/</g,'&lt;').replace(/>/g,'&gt;');}
function fmt(d,opts){try{return new Date(d).toLocaleDateString('it-IT',opts||{day:'numeric',month:'long',year:'numeric'});}
catch(e){return d||'';}}
function getSection(){var path=window.location.pathname;if(path.indexOf('/notizie/')>=0)return'notizie';if(path.indexOf('/eventi/')>=0)return'eventi';if(path.indexOf('/locali/')>=0)return'locali';return'';}
function getSlug(){var qs=new URLSearchParams(window.location.search);if(qs.get('slug'))return qs.get('slug');var parts=window.location.pathname.replace(/\/$/,'').split('/');var s=parts[parts.length-1];return(s==='_slug_'||!s)?'':s;}
function loadSession(){try{var map={notizie:'ta_n',eventi:'ta_e',locali:'ta_l'};Object.keys(map).forEach(function(k){try{var raw=sessionStorage.getItem(map[k]);if(!raw)return;var items=JSON.parse(raw);if(!Array.isArray(items))return;var slugs=(TA[k]||[]).map(function(x){return x.slug;});items.forEach(function(it){var i=slugs.indexOf(it.slug);if(i>=0){TA[k][i]=it;}
else{TA[k]=(TA[k]||[]).concat([it]);slugs.push(it.slug);}});}catch(e){}});}catch(e){}}
function show404(main,section,up){var label=section==='notizie'?'Notizia non trovata':section==='eventi'?'Evento non trovato':'Locale non trovato';var back=section==='notizie'?up+'notizie/':section==='eventi'?up+'eventi/':up+'locali/';main.innerHTML='<div style="padding:80px 24px;text-align:center">'+'<div style="font-size:5rem;font-weight:900;color:var(--color-accent);font-family:var(--font-display)">404</div>'+'<h2 style="font-family:var(--font-display);font-size:1.8rem;color:var(--color-primary);margin:12px 0">'+label+'</h2>'+'<p style="color:var(--color-text-muted);margin-bottom:24px">La pagina cercata non esiste o non &egrave; stata ancora pubblicata.</p>'+'<a href="'+back+'" class="btn-primary">Torna indietro</a>'+'</div>';lucide.createIcons();}
function renderNotizia(main,n,up){document.title=n.titolo+' \u2014 TuttoApricena';var metaDesc=document.querySelector('meta[name="description"]');if(!metaDesc){metaDesc=document.createElement('meta');metaDesc.name='description';document.head.appendChild(metaDesc);}
metaDesc.content=(n.abstract||'').substring(0,155);var canonical=document.querySelector('link[rel="canonical"]');if(!canonical){canonical=document.createElement('link');canonical.rel='canonical';document.head.appendChild(canonical);}
canonical.href='https://www.tuttoapricena.it/notizie/'+n.slug+'/';function setMeta(prop,val,isName){var sel=isName?'meta[name="'+prop+'"]':'meta[property="'+prop+'"]';var el=document.querySelector(sel);if(!el){el=document.createElement('meta');if(isName)el.name=prop;else el.setAttribute('property',prop);document.head.appendChild(el);}el.content=val;}
setMeta('og:title',n.titolo+' — TuttoApricena');setMeta('og:description',(n.abstract||'').substring(0,155));setMeta('og:url','https://www.tuttoapricena.it/notizie/'+n.slug+'/');if(n.immagine)setMeta('og:image',n.immagine);setMeta('og:type','article');var ld={'@context':'https://schema.org','@type':'NewsArticle','headline':n.titolo,'description':n.abstract||'','datePublished':n.data,'publisher':{'@type':'Organization','name':'TuttoApricena','url':'https://www.tuttoapricena.it/'},'url':'https://www.tuttoapricena.it/notizie/'+n.slug+'/'};if(n.immagine)ld.image=n.immagine;var ldEl=document.querySelector('script[type="application/ld+json"]');if(!ldEl){ldEl=document.createElement('script');ldEl.type='application/ld+json';document.head.appendChild(ldEl);}ldEl.textContent=JSON.stringify(ld);var cat=TA.getCatColor?TA.getCatColor(n.categoriaSlug):'#E8A838';var testo=(n.testo||n.abstract||'').split('\n\n').map(function(p){return p.trim()?'<p>'+cc(p)+'</p>':'';}).join('');if(!testo)testo='<p>'+cc(n.abstract||'')+'</p>';main.innerHTML='<div class="article-header"><div class="article-header-inner">'+'<a href="'+up+'notizie/" class="back-link"><i data-lucide="arrow-left" width="16" height="16"></i> Tutte le notizie</a>'+'<div class="article-meta">'+'<span class="cat-badge" style="background:'+cat+'">'+cc(n.categoria||'Notizia')+'</span>'+
(n.inEvidenza?'<span style="background:rgba(232,168,56,.15);color:var(--color-accent);font-size:10px;font-weight:700;padding:3px 10px;border-radius:50px">In evidenza</span>':'')+'</div>'+'<h1 class="article-title">'+cc(n.titolo)+'</h1>'+'<p class="article-abstract">'+cc(n.abstract||'')+'</p>'+'<div class="article-info">'+'<i data-lucide="calendar" width="13" height="13"></i> '+fmt(n.data)+
(n.fonte?' &middot; '+cc(n.fonte):'')+'</div>'+'</div></div>'+'<div class="article-body">'+
(n.immagine?'<img class="article-img" src="'+cc(n.immagine)+'" alt="'+cc(n.titolo)+'" loading="lazy" onerror="this.style.display=\'none\'">':'')+'<div class="prose-content">'+testo+'</div>'+
(n.fonteUrl&&n.fonteUrl!=='#'?'<div class="source-box">'+'<i data-lucide="external-link" width="16" height="16" color="var(--color-accent)"></i>'+'<span>Fonte: <strong>'+cc(n.fonte||'')+'</strong></span>'+'<a href="'+cc(n.fonteUrl)+'" target="_blank" rel="noopener" style="color:var(--color-accent);margin-left:8px">Leggi &rarr;</a>'+'</div>':'')+'</div>';lucide.createIcons();}
function renderEvento(main,e,up){document.title=e.titolo+' \u2014 TuttoApricena';var multi=e.dataFine&&e.dataFine!==e.dataInizio;var dateStr=fmt(e.dataInizio)+(multi?' &ndash; '+fmt(e.dataFine):'');main.innerHTML='<div class="article-header"><div class="article-header-inner">'+'<a href="'+up+'eventi/" class="back-link"><i data-lucide="arrow-left" width="16" height="16"></i> Tutti gli eventi</a>'+'<div class="article-meta"><span class="cat-badge" style="background:var(--color-accent);color:var(--color-primary)">'+cc(e.categoria||'Evento')+'</span></div>'+'<h1 class="article-title">'+cc(e.titolo)+'</h1>'+'<div class="article-info">'+'<i data-lucide="calendar" width="13" height="13"></i> '+dateStr+' &middot; <i data-lucide="clock" width="13" height="13"></i> '+cc(e.orario||'')+' &middot; <i data-lucide="map-pin" width="13" height="13"></i> '+cc(e.luogo||'')+'</div>'+'</div></div>'+'<div class="article-body">'+
(e.immagine?'<img class="article-img" src="'+cc(e.immagine)+'" alt="'+cc(e.titolo)+'" loading="lazy" onerror="this.style.display=\'none\'">':'')+'<div class="prose-content"><p style="font-size:17px;line-height:1.8">'+cc(e.descrizione||'')+'</p></div>'+'<div style="background:#fff;border-radius:16px;padding:24px;margin-top:28px;box-shadow:0 4px 20px rgba(26,26,46,.07);display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:18px">'+'<div><div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--color-text-muted);margin-bottom:4px">Data</div><strong>'+dateStr+'</strong></div>'+'<div><div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--color-text-muted);margin-bottom:4px">Orario</div><strong>'+cc(e.orario||'Da definire')+'</strong></div>'+'<div><div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--color-text-muted);margin-bottom:4px">Luogo</div><strong>'+cc(e.luogo||'Da definire')+'</strong></div>'+'</div>'+
(e.ticketUrl?'<div style="margin-top:24px"><a href="'+cc(e.ticketUrl)+'" rel="noopener noreferrer" class="btn-primary" style="display:inline-flex;align-items:center;gap:8px;font-size:15px;padding:13px 28px" onclick="window.open(this.href,\'_blank\');return false;">'+'<i data-lucide="ticket" width="18" height="18"></i> Acquista biglietti</a></div>':'')+'</div>';lucide.createIcons();}
function renderLocale(main,l,up){
var pageTitle=l.nome+' \u2014 '+(l.tipo||'Locale')+' ad Apricena | TuttoApricena';
document.title=pageTitle;
var desc=(l.descrizione||'').substring(0,155)||(l.tipo+' ad Apricena (FG). '+(l.indirizzo||''));
var metaDesc=document.querySelector('meta[name="description"]');
if(!metaDesc){metaDesc=document.createElement('meta');metaDesc.name='description';document.head.appendChild(metaDesc);}
metaDesc.content=desc;
var canonical=document.querySelector('link[rel="canonical"]');
if(!canonical){canonical=document.createElement('link');canonical.rel='canonical';document.head.appendChild(canonical);}
canonical.href='https://www.tuttoapricena.it/locali/'+l.slug+'/';
function setMeta(prop,val,isName){var sel=isName?'meta[name="'+prop+'"]':'meta[property="'+prop+'"]';var el=document.querySelector(sel);if(!el){el=document.createElement('meta');if(isName)el.name=prop;else el.setAttribute('property',prop);document.head.appendChild(el);}el.content=val;}
setMeta('og:title',l.nome+' \u2014 TuttoApricena');setMeta('og:description',desc);setMeta('og:url','https://www.tuttoapricena.it/locali/'+l.slug+'/');if(l.immagine)setMeta('og:image',l.immagine);setMeta('og:type','website');
var ld={'@context':'https://schema.org','@type':'LocalBusiness','name':l.nome,'description':l.descrizione||'','address':{'@type':'PostalAddress','streetAddress':l.indirizzo||'','addressLocality':'Apricena','addressRegion':'FG','postalCode':'71011','addressCountry':'IT'},'url':'https://www.tuttoapricena.it/locali/'+l.slug+'/'};
if(l.telefono)ld.telephone=l.telefono;if(l.immagine)ld.image=l.immagine;if(l.sitoWeb)ld.sameAs=l.sitoWeb;
var ldEl=document.querySelector('script[type="application/ld+json"]');if(!ldEl){ldEl=document.createElement('script');ldEl.type='application/ld+json';document.head.appendChild(ldEl);}
ldEl.textContent=JSON.stringify(ld);
var _lat=parseFloat(l.lat),_lng=parseFloat(l.lng);
var _hasCoords=(!isNaN(_lat)&&!isNaN(_lng)&&_lat!==0&&_lng!==0);
var localeMapUrl=_hasCoords?'https://www.google.com/maps/search/?api=1&query='+_lat+','+_lng:'https://maps.google.com/?q='+encodeURIComponent((l.indirizzo||l.nome)+', Apricena FG');
var label=function(t){return'<div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--color-text-muted);margin-bottom:4px">'+t+'</div>';};

// Blocco orari strutturati — visibile SOLO nella pagina del singolo locale
function buildOrariBlock(l){
  var GIORNI=[['lun','Lunedì'],['mar','Martedì'],['mer','Mercoledì'],['gio','Giovedì'],['ven','Venerdì'],['sab','Sabato'],['dom','Domenica']];
  var oggi=new Date();
  var dowMap={0:'dom',1:'lun',2:'mar',3:'mer',4:'gio',5:'ven',6:'sab'};
  var oggiKey=dowMap[oggi.getDay()];
  var strutturati=l.orariStrutturati;

  function fmtTime(t){ if(!t)return''; var p=t.split(':'); return p[0]+':'+p[1]; }

  function buildRows(data){
    var html='';
    GIORNI.forEach(function(g){
      var key=g[0],nome=g[1];
      var info=data[key]||{};
      var isOggi=(key===oggiKey);
      var bg=isOggi?'background:rgba(232,168,56,.08);':'';
      var nameStyle='font-size:13px;font-weight:'+(isOggi?'700':'400')+';color:'+(isOggi?'var(--color-accent)':'#555')+';min-width:90px;';
      var badge=isOggi?' <span style="font-size:9px;font-weight:800;background:var(--color-accent);color:#1a1a2e;border-radius:3px;padding:1px 5px;vertical-align:middle;">OGGI</span>':'';

      var orarioHtml='';
      if(info.chiuso){
        orarioHtml='<span style="color:#c0392b;font-size:13px;font-weight:600;">Chiuso</span>';
      } else if(info.ap1&&info.ch1){
        orarioHtml='<span style="font-size:13px;font-weight:600;color:var(--color-primary);">'+fmtTime(info.ap1)+'–'+fmtTime(info.ch1)+'</span>';
        if(info.ap2&&info.ch2){
          orarioHtml+='<span style="color:var(--color-text-muted);margin:0 8px;font-size:12px;">·</span>'
            +'<span style="font-size:13px;font-weight:600;color:var(--color-primary);">'+fmtTime(info.ap2)+'–'+fmtTime(info.ch2)+'</span>';
        }
      } else {
        orarioHtml='<span style="color:var(--color-text-muted);font-size:13px;">—</span>';
      }

      html+='<div style="display:flex;align-items:center;gap:12px;padding:9px 12px;border-radius:8px;'+bg+'">'
        +'<span style="'+nameStyle+'">'+nome+badge+'</span>'
        +'<span>'+orarioHtml+'</span>'
        +'</div>';
    });
    return html;
  }

  // Con orari strutturati → tabella bella
  if(strutturati&&typeof strutturati==='object'&&Object.keys(strutturati).length){
    var rows=buildRows(strutturati);
    var nota=l.orarioNota
      ?'<div style="margin-top:10px;padding:8px 12px;background:rgba(232,168,56,.07);border-radius:8px;border-left:3px solid var(--color-accent);font-size:12px;color:var(--color-text-muted);">'
        +'<i data-lucide="info" width="12" height="12" style="vertical-align:middle;margin-right:4px;"></i>'+cc(l.orarioNota)+'</div>'
      :'';
    return '<div style="background:#fff;border-radius:16px;padding:22px 24px;margin-top:24px;box-shadow:0 4px 20px rgba(26,26,46,.07);">'
      +'<div style="display:flex;align-items:center;gap:8px;margin-bottom:14px;">'
      +'<i data-lucide="clock" width="17" height="17" style="color:var(--color-accent);flex-shrink:0;"></i>'
      +'<span style="font-size:12px;font-weight:800;text-transform:uppercase;letter-spacing:.09em;color:var(--color-text-muted);">Orari di apertura</span>'
      +'</div>'
      +rows+nota+'</div>';
  }

  // Fallback: stringa testuale (locali salvati prima del nuovo sistema)
  if(l.orari){
    // Parsa la stringa "Lun: 09:00-13:00 | Mar: Chiuso | ..." in righe leggibili
    var parti=l.orari.split('|').map(function(s){return s.trim();}).filter(Boolean);
    var parsed={};
    var dayMap={'lun':'lun','mar':'mar','mer':'mer','gio':'gio','ven':'ven','sab':'sab','dom':'dom',
      'lunedì':'lun','martedì':'mar','mercoledì':'mer','giovedì':'gio','venerdì':'ven','sabato':'sab','domenica':'dom'};
    parti.forEach(function(p){
      var m=p.match(/^([A-Za-zèàì]+)[:\s]+(.+)$/i);
      if(!m)return;
      var dk=dayMap[m[1].toLowerCase()];
      if(!dk)return;
      var orario=m[2].trim();
      if(/chiuso/i.test(orario)){parsed[dk]={chiuso:true};return;}
      var slots=orario.split(',').map(function(s){return s.trim();});
      var s1=slots[0]?slots[0].replace(/[–\-]/,'-').split('-'):'';
      var s2=slots[1]?slots[1].replace(/[–\-]/,'-').split('-'):'';
      parsed[dk]={ap1:s1[0]||'',ch1:s1[1]||''};
      if(s2&&s2[0]){parsed[dk].ap2=s2[0];parsed[dk].ch2=s2[1]||'';}
    });
    if(Object.keys(parsed).length){
      return '<div style="background:#fff;border-radius:16px;padding:22px 24px;margin-top:24px;box-shadow:0 4px 20px rgba(26,26,46,.07);">'
        +'<div style="display:flex;align-items:center;gap:8px;margin-bottom:14px;">'
        +'<i data-lucide="clock" width="17" height="17" style="color:var(--color-accent);flex-shrink:0;"></i>'
        +'<span style="font-size:12px;font-weight:800;text-transform:uppercase;letter-spacing:.09em;color:var(--color-text-muted);">Orari di apertura</span>'
        +'</div>'
        +buildRows(parsed)+'</div>';
    }
    // fallback puro testo
    return '<div style="background:#fff;border-radius:16px;padding:20px 24px;margin-top:24px;box-shadow:0 4px 20px rgba(26,26,46,.07);">'
      +'<div style="font-size:12px;font-weight:800;text-transform:uppercase;letter-spacing:.09em;color:var(--color-text-muted);margin-bottom:8px;">Orari</div>'
      +'<p style="font-size:13px;color:var(--color-primary);margin:0;">'+cc(l.orari)+'</p>'
      +'</div>';
  }
  return '';
}

main.innerHTML='<div class="article-header"><div class="article-header-inner">'
+'<a href="'+up+'locali/" class="back-link"><i data-lucide="arrow-left" width="16" height="16"></i> Tutti i locali</a>'
+'<div class="article-meta"><span class="cat-badge" style="background:var(--color-primary)">'+cc(l.tipo||'Locale')+'</span></div>'
+'<h1 class="article-title">'+cc(l.nome)+'</h1>'
+'<div class="article-info">'
+'<i data-lucide="map-pin" width="13" height="13"></i> '+cc(l.indirizzo||'')
+(l.telefono?' &middot; <a href="tel:'+cc(l.telefono)+'" style="color:var(--color-accent);font-weight:700">'+cc(l.telefono)+'</a>':'')
+'</div>'
+'</div></div>'
+'<div class="article-body">'
+(l.immagine?'<img class="article-img" src="'+cc(l.immagine)+'" alt="'+cc(l.nome)+'" loading="lazy" onerror="this.style.display=\'none\'">':'')
+'<div class="prose-content"><p style="font-size:17px;line-height:1.8">'+cc(l.descrizione||'')+'</p></div>'
+(l.gallery&&l.gallery.length?(function(){window._taGalleryImgs=l.gallery.filter(Boolean);return'<div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:12px;margin-top:20px">'+window._taGalleryImgs.map(function(g,gi){return'<img src="'+cc(g)+'" alt="'+cc(l.nome)+' foto '+(gi+1)+'" data-gi="'+gi+'" style="width:100%;height:180px;object-fit:cover;border-radius:12px;cursor:pointer;transition:transform .2s,box-shadow .2s" onmouseover="this.style.transform=\'scale(1.03)\';this.style.boxShadow=\'0 8px 32px rgba(0,0,0,.18)\'" onmouseout="this.style.transform=\'\';this.style.boxShadow=\'\'" loading="lazy" onerror="this.style.display=\'none\'" onclick="TAGallery.open(window._taGalleryImgs,+this.dataset.gi)">';}).join('')+'</div>';})():'')
+buildOrariBlock(l)
+'<div style="background:#fff;border-radius:16px;padding:24px;margin-top:20px;box-shadow:0 4px 20px rgba(26,26,46,.07);display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:20px">'
+(l.indirizzo?'<div>'+label('Indirizzo')+'<strong style="font-size:14px">'+cc(l.indirizzo)+'</strong></div>':'')
+(l.sitoWeb?'<div>'+label('Sito web')+'<a href="'+cc(l.sitoWeb)+'" target="_blank" rel="noopener" style="color:var(--color-accent);font-weight:700">Visita &rarr;</a></div>':'')
+'</div>'
+'<div style="margin-top:20px;display:flex;gap:12px;flex-wrap:wrap">'
+(l.telefono?'<a href="tel:'+cc(l.telefono)+'" class="btn-primary" style="font-size:14px;padding:11px 24px"><i data-lucide="phone" width="15" height="15"></i> Chiama</a>':'')
+'<a href="'+localeMapUrl+'" target="_blank" rel="noopener" class="btn-outline" style="font-size:14px;padding:11px 24px;background:var(--color-primary);border-color:var(--color-primary)" onclick="window.open(this.href,\'_blank\');return false;"><i data-lucide="map-pin" width="15" height="15"></i> Apri in Maps</a>'
+'</div>'
+'</div>';
lucide.createIcons();}

/* ---- TAGallery lightbox ---- */
window.TAGallery=(function(){
  var imgs=[],idx=0,ov=null;
  function build(){
    ov=document.createElement('div');
    ov.id='ta-gallery-ov';
    ov.style.cssText='position:fixed;inset:0;z-index:9999;background:rgba(0,0,0,.88);display:flex;align-items:center;justify-content:center;flex-direction:column;padding:20px;box-sizing:border-box;';
    ov.innerHTML=
      '<button onclick="TAGallery.close()" style="position:absolute;top:16px;right:20px;background:rgba(255,255,255,.15);border:none;color:#fff;font-size:28px;line-height:1;width:44px;height:44px;border-radius:50%;cursor:pointer;display:flex;align-items:center;justify-content:center;">&times;</button>'+
      '<button id="ta-gl-prev" onclick="TAGallery.go(-1)" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);background:rgba(255,255,255,.15);border:none;color:#fff;font-size:26px;width:44px;height:44px;border-radius:50%;cursor:pointer;display:flex;align-items:center;justify-content:center;">&#8249;</button>'+
      '<img id="ta-gl-img" src="" style="max-width:100%;max-height:80vh;object-fit:contain;border-radius:10px;box-shadow:0 8px 48px rgba(0,0,0,.5);transition:opacity .2s;">'+
      '<div id="ta-gl-counter" style="margin-top:12px;color:rgba(255,255,255,.6);font-size:13px;"></div>'+
      '<button id="ta-gl-next" onclick="TAGallery.go(1)" style="position:absolute;right:12px;top:50%;transform:translateY(-50%);background:rgba(255,255,255,.15);border:none;color:#fff;font-size:26px;width:44px;height:44px;border-radius:50%;cursor:pointer;display:flex;align-items:center;justify-content:center;">&#8250;</button>';
    ov.addEventListener('click',function(e){if(e.target===ov)TAGallery.close();});
    document.addEventListener('keydown',function(e){if(!ov||!ov.parentNode)return;if(e.key==='Escape')TAGallery.close();else if(e.key==='ArrowLeft')TAGallery.go(-1);else if(e.key==='ArrowRight')TAGallery.go(1);});
    document.body.appendChild(ov);
  }
  function show(){
    var img=document.getElementById('ta-gl-img');
    var ctr=document.getElementById('ta-gl-counter');
    var prev=document.getElementById('ta-gl-prev');
    var next=document.getElementById('ta-gl-next');
    img.style.opacity='0';
    setTimeout(function(){img.src=imgs[idx];img.style.opacity='1';},100);
    ctr.textContent=(idx+1)+' / '+imgs.length;
    prev.style.display=imgs.length>1?'flex':'none';
    next.style.display=imgs.length>1?'flex':'none';
    document.body.style.overflow='hidden';
  }
  return{
    open:function(arr,i){imgs=arr.filter(Boolean);idx=i||0;if(!imgs.length)return;if(!ov)build();else if(!ov.parentNode)document.body.appendChild(ov);ov.style.display='flex';show();},
    go:function(d){idx=(idx+d+imgs.length)%imgs.length;show();},
    close:function(){if(ov)ov.style.display='none';document.body.style.overflow='';}
  };
})();
window.TARenderer={render:function(sectionOverride){if(typeof TA==='undefined'){console.error('TA not loaded');return;}
loadSession();var section=sectionOverride||getSection();var slug=getSlug();var main=document.getElementById('pg-main');if(!main)return;var path=window.location.pathname;var parts=path.replace(/\/$/,'').split('/').filter(Boolean);var depth=parts.length-1;var up='../../';if(!section||!slug){show404(main,section||'notizie',up);return;}
if(section==='notizie'){var item=(TA.notizie||[]).find(function(x){return x.slug===slug;});item?renderNotizia(main,item,up):show404(main,section,up);}else if(section==='eventi'){var item=(TA.eventi||[]).find(function(x){return x.slug===slug;});item?renderEvento(main,item,up):show404(main,section,up);}else if(section==='locali'){var item=(TA.locali||[]).find(function(x){return x.slug===slug;});item?renderLocale(main,item,up):show404(main,section,up);}else{show404(main,section,up);}}};})();