<?php
require_once __DIR__ . '/includes/config.php';
requireLogin();

// ================================================================
// AJAX HANDLERS — rispondono prima di qualsiasi HTML
// ================================================================
$ajax = (isset($_POST['ajax']) ? $_POST['ajax'] : $_GET['ajax']) ?: '';

if ($ajax) {
    header('Content-Type: application/json; charset=utf-8');

    // Trova root sito (sale finché trova assets/js/data.js)
    function siteRoot() {
        $d = dirname(__DIR__);
        for ($i = 0; $i < 8; $i++) {
            if (file_exists($d . '/assets/js/data.js')) return $d;
            $p = dirname($d);
            if ($p === $d) break;
            $d = $p;
        }
        return dirname(__DIR__);
    }

    // Legge e parsa data.js lato PHP — zero JavaScript coinvolto
    function leggiDataJs() {
        $root = siteRoot();
        $path = $root . '/assets/js/data.js';
        if (!file_exists($path)) {
            return ['ok'=>false,'error'=>"data.js non trovato. Root cercata: $root",'path'=>$path];
        }
        $c = file_get_contents($path);
        if ($c === false) return ['ok'=>false,'error'=>'Impossibile leggere data.js (permessi?)'];
        // Estrai JSON tra "const TA = " e "\nTA.getCatColor"
        $start = strpos($c, 'const TA = ');
        $end   = strpos($c, "\nTA.getCatColor");
        if ($start === false || $end === false) {
            return ['ok'=>false,'error'=>'Formato data.js non riconosciuto'];
        }
        $json = substr($c, $start + strlen('const TA = '), $end - $start - strlen('const TA = '));
        $json = trim($json);          // remove whitespace including trailing \n
        $json = rtrim($json, ';');    // now remove the semicolon
        $ta   = json_decode($json, true);
        if (!$ta) return ['ok'=>false,'error'=>'JSON non valido: '.json_last_error_msg()];
        return ['ok'=>true,'ta'=>$ta,'path'=>$path];
    }

    // ---- leggi_sito ----
    if ($ajax === 'leggi_sito') {
        $r = leggiDataJs();
        if (!$r['ok']) { echo json_encode($r); exit; }
        $ta = $r['ta'];
        echo json_encode([
            'ok'      => true,
            'notizie' => count((isset($ta['notizie']) ? $ta['notizie'] : [])),
            'eventi'  => count((isset($ta['eventi']) ? $ta['eventi'] : [])),
            'farmacie'=> count((isset($ta['farmacie']) ? $ta['farmacie'] : [])),
            'servizi' => count((isset($ta['servizi']) ? $ta['servizi'] : [])),
            'locali'  => count((isset($ta['locali']) ? $ta['locali'] : [])),
            'sponsor' => count((isset($ta['sponsor']) ? $ta['sponsor'] : [])),
        ]);
        exit;
    }

    // ---- importa ----
    if ($ajax === 'importa') {
        $r = leggiDataJs();
        if (!$r['ok']) { echo json_encode($r); exit; }
        $ta = $r['ta'];
        $imp = ['notizie'=>0,'eventi'=>0,'farmacie'=>0,'servizi'=>0,'locali'=>0,'sponsor'=>0];

        // NOTIZIE — importa solo dal 2026 in poi e rimuove eventuali già presenti precedenti al 2026
        $list = loadData('notizie') ?: [];
        $list = array_values(array_filter($list, function($n) {
            $anno = (int) substr(isset($n['data']) ? $n['data'] : '', 0, 4);
            return $anno >= 2026;
        }));
        $slugs = array_column($list,'slug');
        foreach ((isset($ta['notizie']) ? $ta['notizie'] : []) as $n) {
            if (!$n['slug'] || in_array($n['slug'],$slugs)) continue;
            $dataNotiza = $n['data'] ?: date('Y-m-d');
            if ((int) substr($dataNotiza, 0, 4) < 2026) continue; // salta notizie precedenti al 2026
            $list[] = ['id'=>generateId(),'slug'=>$n['slug'],'titolo'=>(isset($n['titolo']) ? $n['titolo'] : ''),
                'categoria'=>(isset($n['categoria']) ? $n['categoria'] : 'Cronaca'),'data'=>$dataNotiza,
                'sommario'=>(isset($n['abstract']) ? $n['abstract'] : ''),'testo'=>(isset($n['testo']) ? $n['testo'] : ''),'fonte'=>(isset($n['fonte']) ? $n['fonte'] : ''),
                'foto'=>(isset($n['immagine']) ? $n['immagine'] : ''),'evidenza'=>!empty($n['inEvidenza']),'creato'=>date('Y-m-d H:i:s')];
            $imp['notizie']++;
        }
        saveData('notizie',$list);

        // EVENTI
        $list = loadData('eventi') ?: [];
        $slugs = array_column($list,'slug');
        foreach ((isset($ta['eventi']) ? $ta['eventi'] : []) as $e) {
            if (!$e['slug'] || in_array($e['slug'],$slugs)) continue;
            $list[] = ['id'=>generateId(),'slug'=>$e['slug'],'titolo'=>(isset($e['titolo']) ? $e['titolo'] : ''),
                'categoria'=>(isset($e['categoria']) ? $e['categoria'] : ''),'data'=>(isset($e['dataInizio']) ? $e['dataInizio'] : ''),'ora'=>(isset($e['orario']) ? $e['orario'] : ''),
                'luogo'=>(isset($e['luogo']) ? $e['luogo'] : ''),'descrizione'=>(isset($e['descrizione']) ? $e['descrizione'] : ''),'foto'=>(isset($e['immagine']) ? $e['immagine'] : ''),
                'evidenza'=>!empty($e['inEvidenza']),'link_biglietti'=>(isset($e['ticketUrl']) ? $e['ticketUrl'] : ''),'creato'=>date('Y-m-d H:i:s')];
            $imp['eventi']++;
        }
        saveData('eventi',$list);

        // FARMACIE (importa solo se CMS vuoto)
        $list = loadData('farmacie') ?: [];
        if (empty($list)) {
            foreach ((isset($ta['farmacie']) ? $ta['farmacie'] : []) as $f) {
                $list[] = ['id'=>generateId(),'nome'=>(isset($f['nome']) ? $f['nome'] : ''),'indirizzo'=>(isset($f['indirizzo']) ? $f['indirizzo'] : ''),
                    'telefono'=>(isset($f['telefono']) ? $f['telefono'] : ''),'orario'=>(isset($f['orario']) ? $f['orario'] : ''),'notturna'=>!empty($f['notturno'])];
                $imp['farmacie']++;
            }
            saveData('farmacie',$list);
        }

        // SERVIZI (importa solo se CMS vuoto)
        $list = loadData('servizi') ?: [];
        if (empty($list)) {
            foreach ((isset($ta['servizi']) ? $ta['servizi'] : []) as $s) {
                $list[] = [
                    'id'          => generateId(),
                    'nome'        => (isset($s['nome']) ? $s['nome'] : ''),
                    'categoria'   => (isset($s['categoria']) ? $s['categoria'] : ''),
                    'descrizione' => (isset($s['descrizione']) ? $s['descrizione'] : ''),
                    'indirizzo'   => (isset($s['indirizzo']) ? $s['indirizzo'] : ''),
                    'telefono'    => (isset($s['telefono']) ? $s['telefono'] : ''),
                    'sito_web'    => (isset($s['sitoWeb']) ? $s['sitoWeb'] : $s['sito_web']) ?: '',
                    'orario'      => (isset($s['orari']) ? $s['orari'] : $s['orario']) ?: '',
                    'evidenza'    => false,
                ];
                $imp['servizi']++;
            }
            saveData('servizi', $list);
        }

        // LOCALI
        $list = loadData('locali') ?: [];
        $slugs = array_column($list, 'slug');
        foreach ((isset($ta['locali']) ? $ta['locali'] : []) as $l) {
            $slug = (isset($l['slug']) ? $l['slug'] : '');
            if (!$slug || in_array($slug, $slugs)) continue;
            $list[] = [
                'id'          => generateId(),
                'slug'        => $slug,
                'nome'        => (isset($l['nome']) ? $l['nome'] : ''),
                'tipo'        => (isset($l['tipo']) ? $l['tipo'] : ''),
                'descrizione' => (isset($l['descrizione']) ? $l['descrizione'] : ''),
                'indirizzo'   => (isset($l['indirizzo']) ? $l['indirizzo'] : ''),
                'telefono'    => (isset($l['telefono']) ? $l['telefono'] : ''),
                'sito_web'    => (isset($l['sitoWeb']) ? $l['sitoWeb'] : $l['sito_web']) ?: '',
                'orario'      => (isset($l['orari']) ? $l['orari'] : $l['orario']) ?: '',
                'foto'        => (isset($l['immagine']) ? $l['immagine'] : $l['foto']) ?: '',
                'evidenza'    => !empty($l['inEvidenza']),
                'creato'      => date('Y-m-d H:i:s'),
            ];
            $slugs[] = $slug;
            $imp['locali']++;
        }
        saveData('locali', $list);

        // SPONSOR (importa solo se CMS vuoto e sito ne ha)
        $list = loadData('sponsor') ?: [];
        if (empty($list) && !empty($ta['sponsor'])) {
            foreach ((isset($ta['sponsor']) ? $ta['sponsor'] : []) as $s) {
                if (empty($s['nome'])) continue;
                $list[] = [
                    'id'          => generateId(),
                    'nome'        => (isset($s['nome']) ? $s['nome'] : ''),
                    'livello'     => (isset($s['livello']) ? $s['livello'] : 'Bronze'),
                    'descrizione' => (isset($s['settore']) ? $s['settore'] : $s['descrizione']) ?: '',
                    'sito_web'    => ((isset($s['sito']) && $s['sito']) ? $s['sito'] : (isset($s['sitoWeb']) && $s['sitoWeb'] ? $s['sitoWeb'] : (isset($s['sito_web']) ? $s['sito_web'] : ''))),
                    'telefono'    => (isset($s['telefono']) ? $s['telefono'] : ''),
                    'foto'        => (isset($s['foto']) ? $s['foto'] : ''),
                    'evidenza'    => false,
                ];
                $imp['sponsor']++;
            }
            saveData('sponsor', $list);
        }

        echo json_encode(['ok'=>true,'importati'=>$imp,'messaggio'=>'Importazione completata!']);
        exit;
    }

    // ---- pubblica ----
    if ($ajax === 'pubblica') {
        $root    = siteRoot();
        $dataJs  = $root . '/assets/js/data.js';
        $assetsDir = dirname($dataJs);

        if (!file_exists($dataJs)) {
            echo json_encode(['ok'=>false,'error'=>"data.js non trovato: $dataJs",'root'=>$root]); exit;
        }
        if (!is_writable($dataJs) && !is_writable($assetsDir)) {
            echo json_encode(['ok'=>false,'error'=>'data.js non scrivibile. Imposta permessi 644 su assets/js/data.js via FTP.']); exit;
        }

        // Leggi TA attuale per preservare citta, categorie, ecc.
        $cur = leggiDataJs();
        $old = $cur['ok'] ? $cur['ta'] : [];

        $notizie  = loadData('notizie')  ?: [];
        $eventi   = loadData('eventi')   ?: [];
        $farmacie = loadData('farmacie') ?: [];
        $servizi  = loadData('servizi')  ?: [];
        $locali   = loadData('locali')   ?: [];
        $sponsor  = loadData('sponsor')  ?: [];
        $settings = loadData('settings') ?: [];

        function sl($t){
            $t=mb_strtolower($t,'UTF-8');
            $t=strtr($t,['à'=>'a','á'=>'a','è'=>'e','é'=>'e','ì'=>'i','í'=>'i','ò'=>'o','ó'=>'o','ù'=>'u','ú'=>'u']);
            $t=preg_replace('/[^a-z0-9\s-]/u','',$t);
            return trim(preg_replace('/[\s-]+/','-',$t),'-');
        }

        $nOut=[];
        foreach($notizie as $n){
            if ((int) substr(isset($n['data']) ? $n['data'] : '', 0, 4) < 2026) continue;
            $nOut[]=['id'=>crc32((isset($n['slug']) ? $n['slug'] : $n['id'])),'slug'=>$n['slug']?: sl((isset($n['titolo']) ? $n['titolo'] : '')),
                'titolo'=>(isset($n['titolo']) ? $n['titolo'] : ''),'categoria'=>(isset($n['categoria']) ? $n['categoria'] : 'Cronaca'),
                'categoriaSlug'=>sl((isset($n['categoria']) ? $n['categoria'] : 'cronaca')),'immagine'=>(isset($n['foto']) ? $n['foto'] : ''),
                'abstract'=>(isset($n['sommario']) ? $n['sommario'] : ''),'testo'=>(isset($n['testo']) ? $n['testo'] : ''),'fonte'=>(isset($n['fonte']) ? $n['fonte'] : ''),
                'fonteUrl'=>(isset($n['fonte_url']) ? $n['fonte_url'] : ''),'externalLink'=>(isset($n['fonte_url']) && $n['fonte_url'] ? $n['fonte_url'] : ''),'data'=>$n['data']?: date('Y-m-d'),'inEvidenza'=>!empty($n['evidenza']),'tag'=>[]];
        }
        usort($nOut,function($a,$b) { return strcmp($b['data'],$a['data']); });

        $eOut=[];
        foreach($eventi as $e){
            $eOut[]=['id'=>is_numeric($e['id'])?(int)$e['id']:crc32($e['id']),'slug'=>$e['slug']?: sl((isset($e['titolo']) ? $e['titolo'] : '')),
                'titolo'=>(isset($e['titolo']) ? $e['titolo'] : ''),'categoria'=>(isset($e['categoria']) ? $e['categoria'] : 'Evento'),
                'dataInizio'=>(isset($e['data']) ? $e['data'] : ''),'dataFine'=>(isset($e['data']) ? $e['data'] : ''),'orario'=>(isset($e['ora']) ? $e['ora'] : ''),
                'luogo'=>(isset($e['luogo']) ? $e['luogo'] : ''),'descrizione'=>(isset($e['descrizione']) ? $e['descrizione'] : ''),'immagine'=>(isset($e['foto']) ? $e['foto'] : ''),
                'inEvidenza'=>!empty($e['evidenza']),'ticketUrl'=>(isset($e['link_biglietti']) ? $e['link_biglietti'] : ''),'ordine'=>0];
        }
        usort($eOut,function($a,$b) { return strcmp($a['dataInizio'],$b['dataInizio']); });

        $fOut=[];$i=1;
        foreach($farmacie as $f){$fOut[]=['id'=>$i++,'nome'=>(isset($f['nome']) ? $f['nome'] : ''),'indirizzo'=>(isset($f['indirizzo']) ? $f['indirizzo'] : ''),'lat'=>(isset($f['lat']) && $f['lat'] !== '' ? (float)$f['lat'] : null),'lng'=>(isset($f['lng']) && $f['lng'] !== '' ? (float)$f['lng'] : null),'telefono'=>(isset($f['telefono']) ? $f['telefono'] : ''),'orario'=>(isset($f['orario']) ? $f['orario'] : ''),'notturno'=>!empty($f['notturna'])];}

        $sOut=[];$i=1;$ico=['Istituzioni'=>'building','Sanità'=>'heart','Sicurezza'=>'shield','Trasporti'=>'train','Cultura'=>'book','Servizi'=>'mail'];
        foreach($servizi as $s){$sOut[]=['id'=>$i++,'nome'=>(isset($s['nome']) ? $s['nome'] : ''),'categoria'=>(isset($s['categoria']) ? $s['categoria'] : ''),'categoriaSlug'=>sl((isset($s['categoria']) ? $s['categoria'] : '')),'icona'=>$ico[(isset($s['categoria']) ? $s['categoria'] : '')] ?: 'info','indirizzo'=>(isset($s['indirizzo']) ? $s['indirizzo'] : ''),'lat'=>(isset($s['lat']) && $s['lat'] !== '' ? (float)$s['lat'] : null),'lng'=>(isset($s['lng']) && $s['lng'] !== '' ? (float)$s['lng'] : null),'telefono'=>(isset($s['telefono']) ? $s['telefono'] : ''),'email'=>'','sitoWeb'=>(isset($s['sito_web']) ? $s['sito_web'] : ''),'orari'=>(isset($s['orario']) ? $s['orario'] : ''),'descrizione'=>(isset($s['descrizione']) ? $s['descrizione'] : '')];}

        $lOut=[];
        foreach($locali as $l){$lOut[]=['id'=>is_numeric($l['id'])?(int)$l['id']:crc32($l['id']),'slug'=>$l['slug']?: sl((isset($l['nome']) ? $l['nome'] : '')),'nome'=>(isset($l['nome']) ? $l['nome'] : ''),'tipo'=>(isset($l['tipo']) ? $l['tipo'] : ''),'tipoSlug'=>sl((isset($l['tipo']) ? $l['tipo'] : '')),'descrizione'=>(isset($l['descrizione']) ? $l['descrizione'] : ''),'indirizzo'=>(isset($l['indirizzo']) ? $l['indirizzo'] : ''),'lat'=>(isset($l['lat']) && $l['lat'] !== '' ? (float)$l['lat'] : null),'lng'=>(isset($l['lng']) && $l['lng'] !== '' ? (float)$l['lng'] : null),'telefono'=>(isset($l['telefono']) ? $l['telefono'] : ''),'sitoWeb'=>(isset($l['sito_web']) ? $l['sito_web'] : ''),'orari'=>(isset($l['orario']) ? $l['orario'] : ''),'immagine'=>(isset($l['foto']) ? $l['foto'] : ''),'gallery'=>(isset($l['gallery']) ? $l['gallery'] : []),'inEvidenza'=>!empty($l['evidenza'])];}

        $spOut=[];
        foreach($sponsor as $s){$spOut[]=['id'=>$s['id']?: uniqid(),'nome'=>(isset($s['nome']) ? $s['nome'] : ''),'livello'=>(isset($s['livello']) ? $s['livello'] : 'Bronze'),'descrizione'=>(isset($s['descrizione']) ? $s['descrizione'] : ''),'indirizzo'=>(isset($s['indirizzo']) ? $s['indirizzo'] : ''),'sito_web'=>(isset($s['sito_web']) ? $s['sito_web'] : ''),'sito'=>(isset($s['sito_web']) ? $s['sito_web'] : ''),'sitoWeb'=>(isset($s['sito_web']) ? $s['sito_web'] : ''),'telefono'=>(isset($s['telefono']) ? $s['telefono'] : ''),'orario'=>(isset($s['orario']) ? $s['orario'] : ''),'foto'=>(isset($s['foto']) ? $s['foto'] : ''),'attivo'=>true];}

        // Mergia config: include hero images, logo, colori da settings
        $heroImages = (isset($settings['hero_images']) && $settings['hero_images'] ? $settings['hero_images'] : (isset($old['config']['heroImages']) ? $old['config']['heroImages'] : array('','','')));
        $logoUrl    = $settings['logo_url'] ?: ($old['config']['logoUrl'] ?: '');
        $cfg = array_merge((isset($old['config']) ? $old['config'] : []), [
            'siteName'    => $settings['nome_sito'] ?: ($old['config']['siteName'] ?: 'TuttoApricena'),
            'colorPrimary'=> $settings['colore_primario'] ?: ($old['config']['colorPrimary'] ?: '#1a1a2e'),
            'colorAccent' => $settings['colore_accent'] ?: ($old['config']['colorAccent'] ?: '#e8a838'),
            'heroImages'  => is_array($heroImages) ? $heroImages : ['','',''],
            'logoUrl'     => $logoUrl,
            'sezioniVisibili' => (isset($settings['sezioni_visibili']) && is_array($settings['sezioni_visibili']) ? $settings['sezioni_visibili'] : (object)[]),
        ]);
        // Aggiorna immagine città se impostata nel CMS
        if (!empty($settings['citta_immagine'])) {
            if (!isset($old['citta'])) $old['citta'] = [];
            $old['citta']['immagine'] = $settings['citta_immagine'];
        }

        $ta=['config'=>$cfg,'notizie'=>$nOut,'categorieNotizie'=>$old['categorieNotizie'] ?:[['nome'=>'Cronaca','slug'=>'cronaca','colore'=>'#DC2626'],['nome'=>'Cultura','slug'=>'cultura','colore'=>'#7C3AED'],['nome'=>'Sport','slug'=>'sport','colore'=>'#16A34A'],['nome'=>'Economia','slug'=>'economia','colore'=>'#0284C7'],['nome'=>'Società','slug'=>'societa','colore'=>'#EA580C'],['nome'=>'Turismo','slug'=>'turismo','colore'=>'#E8A838']],'eventi'=>$eOut,'farmacie'=>$fOut,'servizi'=>$sOut,'categorieServizi'=>(isset($old['categorieServizi']) ? $old['categorieServizi'] : []),'locali'=>$lOut,'tipiLocali'=>(isset($old['tipiLocali']) ? $old['tipiLocali'] : []),'sponsor'=>$spOut,'citta'=>(isset($old['citta']) ? $old['citta'] : [])];

        $ts = date('d/m/Y, H:i:s');
        $json = json_encode($ta, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
        $js  = "// TUTTOAPRICENA - DATA FILE\n// CMS Update: $ts\nconst TA = $json;\n\n"
             . 'TA.getCatColor = function(slug) { var c = TA.categorieNotizie.find(function(x){ return x.slug === slug; }); return c ? c.colore : "#E8A838"; };'."\n"
             . 'TA.formatDate = function(d, opts) { return new Date(d).toLocaleDateString("it-IT", opts || { day: "numeric", month: "long", year: "numeric" }); };'."\n"
             . 'TA.getFarmaciaOfDay = function(date) { if(!TA.farmacie||!TA.farmacie.length)return null; var d=date||new Date(); var epoch=new Date("2026-01-01T00:00:00"); var diff=Math.floor((d-epoch)/86400000); var idx=((diff%TA.farmacie.length)+TA.farmacie.length)%TA.farmacie.length; return TA.farmacie[idx]; };'."\n"
             . 'TA.getFarmacieProssimi = function(n) { var r=[]; var t=new Date(); t.setHours(0,0,0,0); for(var i=0;i<n;i++){var d=new Date(t);d.setDate(t.getDate()+i);r.push({data:d,farmacia:TA.getFarmaciaOfDay(d)});}return r; };'."\n"
             . 'if (typeof module !== "undefined") module.exports = TA;'."\n";

        $w = file_put_contents($dataJs, $js);
        if ($w === false) { echo json_encode(['ok'=>false,'error'=>'Scrittura fallita. Permessi 644 su data.js?']); exit; }

        // Crea cartelle slug
        $cr=0;
        function mkSlug($root,$sez,$slug,$titolo,$immagine='',$abstract=''){
            if(!$slug) return 0;
            $d=$root.'/'.$sez.'/'.$slug.'/';
            if(is_dir($d)) return 0;
            if(!@mkdir($d,0755,true)) return 0;
            $h=htmlspecialchars($titolo).' — TuttoApricena';
            $ogImg=($immagine && strpos($immagine,'http')===0) ? $immagine : 'https://www.tuttoapricena.it/images/og/citta-apricena.jpg';
            // Use local image if it's an admin upload path
            if($immagine && strpos($immagine,'/admin/uploads/')===0) {
                $ogImg='https://www.tuttoapricena.it'.str_replace('/admin/uploads/', '/images/og/', $immagine);
            }
            $ogDesc=htmlspecialchars(mb_substr(strip_tags($abstract),0,155));
            $ogUrl='https://www.tuttoapricena.it/'.$sez.'/'.$slug.'/';
            file_put_contents($d.'index.html',"<!DOCTYPE html>\n<html lang=\"it\">\n<head>\n  <meta charset=\"UTF-8\"/>\n  <link rel=\"preconnect\" href=\"https://fonts.googleapis.com\" crossorigin/>\n  <link rel=\"preconnect\" href=\"https://fonts.gstatic.com\" crossorigin/>\n  <link rel=\"stylesheet\" media=\"print\" onload=\"this.media='all'\" href=\"https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,800;0,900;1,700&family=Inter:wght@400;600;700;800&display=swap\"/>\n  <noscript><link rel=\"stylesheet\" href=\"https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,800;0,900;1,700&family=Inter:wght@400;600;700;800&display=swap\"/></noscript>\n  <meta name=\"viewport\" content=\"width=device-width,initial-scale=1.0\"/>\n  <title>$h</title>\n  <meta property=\"og:type\" content=\"article\" />\n  <meta property=\"og:locale\" content=\"it_IT\" />\n  <meta property=\"og:site_name\" content=\"TuttoApricena\" />\n  <meta property=\"og:title\" content=\"$h\" />\n  <meta property=\"og:description\" content=\"$ogDesc\" />\n  <meta property=\"og:url\" content=\"$ogUrl\" />\n  <meta property=\"og:image\" content=\"$ogImg\" />\n  <meta property=\"og:image:width\" content=\"1200\" />\n  <meta property=\"og:image:height\" content=\"630\" />\n  <meta name=\"twitter:card\" content=\"summary_large_image\" />\n  <meta name=\"twitter:title\" content=\"$h\" />\n  <meta name=\"twitter:description\" content=\"$ogDesc\" />\n  <meta name=\"twitter:image\" content=\"$ogImg\" />\n  <link rel=\"stylesheet\" href=\"../../assets/css/style.css\"/>\n  <script src=\"../../assets/js/lucide-mini.js\"></script>\n</head>\n<body>\n<header id=\"navbar\"><div class=\"navbar-inner\"><div class=\"navbar-row\">\n  <a href=\"../../\" class=\"navbar-logo\"><div class=\"navbar-logo-icon\"><span>TA</span></div><span class=\"navbar-logo-text\">Tutto<em>Apricena</em></span></a>\n  <nav class=\"navbar-links\"><a href=\"../../notizie/\">Notizie</a><a href=\"../../eventi/\">Eventi</a><a href=\"../../farmacie/\">Farmacie</a><a href=\"../../servizi/\">Servizi</a><a href=\"../../locali/\">Locali</a><a href=\"../../sponsor/\">Sponsor</a></nav>\n  <div class=\"navbar-actions\"><a href=\"../../chi-siamo/\" class=\"link-plain\">Chi siamo</a><a href=\"../../contatti/\" class=\"link-btn\">Contatti</a></div>\n  <button id=\"mobile-menu-btn\" class=\"mobile-menu-btn\" aria-label=\"Menu\"><i data-lucide=\"menu\" class=\"icon-hamburger\" width=\"24\" height=\"24\"></i><i data-lucide=\"x\" class=\"icon-x\" width=\"24\" height=\"24\" style=\"display:none\"></i></button>\n</div></div>\n<div id=\"mobile-menu\" class=\"mobile-menu\"><div class=\"mobile-menu-inner\">\n  <nav class=\"mobile-nav\"><a href=\"../../notizie/\">Notizie</a><a href=\"../../eventi/\">Eventi</a><a href=\"../../farmacie/\">Farmacie</a><a href=\"../../servizi/\">Servizi</a><a href=\"../../locali/\">Locali</a><a href=\"../../sponsor/\">Sponsor</a></nav>\n  <a href=\"../../contatti/\" class=\"mobile-cta\">Contattaci</a>\n</div></div></header>\n<main id=\"pg-main\" style=\"min-height:80vh\">\n  <div style=\"padding:60px 24px;text-align:center;color:var(--color-text-muted)\"><i data-lucide=\"loader\" width=\"32\" height=\"32\"></i><br>Caricamento...</div>\n</main>\n<footer><div class=\"footer-inner\"><div class=\"footer-grid\">\n  <div class=\"footer-brand\"><a href=\"../../\" class=\"navbar-logo\"><div class=\"navbar-logo-icon\"><span>TA</span></div><span class=\"navbar-logo-text\">Tutto<em>Apricena</em></span></a><p>Il portale informativo di Apricena.</p></div>\n  <div class=\"footer-col\"><h4>Sezioni</h4><ul><li><a href=\"../../notizie/\">Notizie</a></li><li><a href=\"../../eventi/\">Eventi</a></li><li><a href=\"../../locali/\">Locali</a></li><li><a href=\"../../farmacie/\">Farmacie</a></li></ul></div>\n  <div class=\"footer-col\"><h4>Info</h4><ul><li><a href=\"../../chi-siamo/\">Chi Siamo</a></li><li><a href=\"../../contatti/\">Contatti</a></li><li><a href=\"../../privacy/\">Privacy</a></li></ul></div>\n</div><div class=\"footer-bottom\"><p>&copy; <span id=\"year\"></span> TuttoApricena</p></div></div></footer>\n<script src=\"../../assets/js/data.js\"></script>\n<script src=\"../../assets/js/main.js\"></script>\n<script src=\"../../assets/js/slug-renderer.js\"></script>\n<script>document.getElementById('year').textContent=new Date().getFullYear();TARenderer.render('$sez');</script>\n<script src=\"../../assets/js/protect.js\"></script>\n</body>\n</html>");
            return 1;
        }
        foreach($nOut as $n) $cr+=mkSlug($root,'notizie',$n['slug'],$n['titolo'],(isset($n['immagine'])?$n['immagine']:''),(isset($n['abstract'])?$n['abstract']:''));
        foreach($eOut as $e) $cr+=mkSlug($root,'eventi',$e['slug'],$e['titolo'],(isset($e['immagine'])?$e['immagine']:''),(isset($e['descrizione'])?$e['descrizione']:''));
        foreach($lOut as $l) $cr+=mkSlug($root,'locali',$l['slug'],$l['nome'],(isset($l['immagine'])?$l['immagine']:''),(isset($l['descrizione'])?$l['descrizione']:''));

        echo json_encode(['ok'=>true,'messaggio'=>'Sito aggiornato!','timestamp'=>$ts,
            'notizie'=>count($nOut),'eventi'=>count($eOut),'farmacie'=>count($fOut),
            'servizi'=>count($sOut),'locali'=>count($lOut),'sponsor'=>count($spOut),
            'slug_creati'=>$cr,'bytes'=>$w]);
        exit;
    }

    // ---- debug ----
    if ($ajax === 'debug') {
        $root = siteRoot();
        $dataJs = $root.'/assets/js/data.js';
        echo json_encode([
            'ok'=>true,'admin_dir'=>__DIR__,'site_root'=>$root,'data_js'=>$dataJs,
            'exists'=>file_exists($dataJs),'readable'=>is_readable($dataJs),
            'writable'=>is_writable($dataJs),'dir_writable'=>is_writable(dirname($dataJs)),
            'root_files'=>array_slice(@scandir($root)?:[],0,25),
        ],JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE);
        exit;
    }

    echo json_encode(['error'=>'ajax sconosciuto: '.$ajax]);
    exit;
}

// ================================================================
// PAGINA HTML
// ================================================================
$pageTitle = 'Sincronizza Sito';
$pageSubtitle = 'Importa dal sito e pubblica le modifiche';
$activeSection = 'sincronizza';
require_once __DIR__ . '/includes/layout.php';

$cN=count(loadData('notizie'));$cE=count(loadData('eventi'));$cF=count(loadData('farmacie'));
$cS=count(loadData('servizi'));$cL=count(loadData('locali'));$cSp=count(loadData('sponsor'));
?>
<style>
.sc{background:var(--dark2);border:1px solid var(--border);border-radius:16px;padding:24px;margin-bottom:18px;}
.sc h3{font-family:'Syne',sans-serif;font-size:16px;font-weight:700;margin-bottom:8px;}
.sc p{font-size:13px;color:var(--text2);line-height:1.6;margin-bottom:14px;}
.bx{padding:14px 16px;border-radius:10px;font-size:13px;margin-top:12px;line-height:1.8;display:none;}
.bx.show{display:block;}
.bx.ok{background:rgba(76,175,125,.1);border:1px solid rgba(76,175,125,.3);color:#4caf7d;}
.bx.er{background:rgba(224,85,85,.1);border:1px solid rgba(224,85,85,.3);color:#e05555;}
.bx.inf{background:var(--dark3);border:1px solid var(--border);color:var(--text2);}
.sg{display:grid;grid-template-columns:repeat(3,1fr);gap:8px;margin-top:10px;}
.sb{background:var(--dark3);border-radius:8px;padding:10px;text-align:center;}
.sn{font-family:'Syne',sans-serif;font-size:20px;font-weight:800;color:var(--gold);}
.sl{font-size:11px;color:var(--muted);}
.bb{width:100%;padding:13px;font-size:14px;font-weight:700;border-radius:10px;border:none;cursor:pointer;display:flex;align-items:center;justify-content:center;gap:8px;font-family:'Syne',sans-serif;transition:all .2s;}
.bb.g{background:linear-gradient(135deg,var(--gold),var(--gold-dark));color:#0c0c12;}
.bb.b{background:rgba(91,156,246,.1);color:var(--blue);border:1px solid rgba(91,156,246,.2);}
.bb.g:hover{opacity:.9;transform:translateY(-1px);}
.bb.b:hover{background:rgba(91,156,246,.2);}
.bb:disabled{opacity:.4;cursor:not-allowed;transform:none!important;}
@keyframes sp{to{transform:rotate(360deg)}} .sp{animation:sp 1s linear infinite;display:inline-block;}
.warn{background:rgba(255,165,0,.07);border:1px solid rgba(255,165,0,.2);border-radius:8px;padding:12px;font-size:12px;color:#ffaa44;margin-bottom:12px;}
</style>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:18px;margin-bottom:18px;">
<div class="sc">
  <h3>🗃 Nel CMS adesso</h3>
  <div class="sg">
    <div class="sb"><div class="sn"><?=$cN?></div><div class="sl">📰 Notizie</div></div>
    <div class="sb"><div class="sn"><?=$cE?></div><div class="sl">📅 Eventi</div></div>
    <div class="sb"><div class="sn"><?=$cF?></div><div class="sl">💊 Farmacie</div></div>
    <div class="sb"><div class="sn"><?=$cS?></div><div class="sl">🔧 Servizi</div></div>
    <div class="sb"><div class="sn"><?=$cL?></div><div class="sl">🏠 Locali</div></div>
    <div class="sb"><div class="sn"><?=$cSp?></div><div class="sl">⭐ Sponsor</div></div>
  </div>
</div>
<div class="sc">
  <h3>🌐 Nel sito live</h3>
  <p style="margin:0;font-size:12px;color:var(--muted);">Clicca per leggere il sito.</p>
  <button class="bb b" id="btn-leggi" onclick="doLeggi()" style="margin-top:10px;">🔄 Leggi sito live</button>
  <div class="sg" id="live-sg" style="display:none;margin-top:12px;">
    <div class="sb"><div class="sn" id="lv-n">—</div><div class="sl">📰 Notizie</div></div>
    <div class="sb"><div class="sn" id="lv-e">—</div><div class="sl">📅 Eventi</div></div>
    <div class="sb"><div class="sn" id="lv-f">—</div><div class="sl">💊 Farmacie</div></div>
    <div class="sb"><div class="sn" id="lv-s">—</div><div class="sl">🔧 Servizi</div></div>
    <div class="sb"><div class="sn" id="lv-l">—</div><div class="sl">🏠 Locali</div></div>
    <div class="sb"><div class="sn" id="lv-sp">—</div><div class="sl">⭐ Sponsor</div></div>
  </div>
  <div class="bx" id="bx-leggi"></div>
</div>
</div>

<div class="sc" style="border-color:rgba(91,156,246,.2);">
  <h3>⬇️ Importa dal sito nel CMS</h3>
  <p>Legge <code>data.js</code> dal server e salva nel CMS tutto ciò che non è ancora presente. Non sovrascrive nulla di esistente. <strong style="color:var(--gold);">Le notizie vengono importate solo dal 2026 in poi.</strong></p>
  <button class="bb b" id="btn-imp" onclick="doImporta()">⬇️ Importa dal sito</button>
  <div class="bx" id="bx-imp"></div>
</div>

<div class="sc" style="border-color:rgba(201,162,39,.3);">
  <h3>🚀 Pubblica CMS → Sito</h3>
  <p>Riscrive <code>assets/js/data.js</code> con tutti i contenuti del CMS. Il design del sito non viene toccato.</p>
  <div class="warn">⚠️ Assicurati che <code>assets/js/data.js</code> abbia permessi <strong>644</strong> via FTP.</div>
  <button class="bb g" id="btn-pub" onclick="doPubblica()">🚀 Pubblica sul sito</button>
  <div class="bx" id="bx-pub"></div>
</div>

<div class="sc">
  <h3>🔍 Debug percorsi server</h3>
  <button class="bb b" onclick="doDebug()" style="max-width:220px;padding:10px;">🔍 Mostra percorsi</button>
  <div class="bx" id="bx-dbg"></div>
</div>

<script>
async function call(ajax, body) {
  var url = '/admin/sincronizza.php?ajax=' + ajax;
  var opts = body ? {method:'POST',headers:{'Content-Type':'application/json'},body:JSON.stringify(body)} : {method:'GET'};
  var r = await fetch(url, opts);
  var txt = await r.text();
  try { return JSON.parse(txt); }
  catch(e) { return {ok:false, error:'Risposta PHP non valida: ' + txt.substring(0,300)}; }
}

async function doLeggi() {
  var btn=document.getElementById('btn-leggi'), bx=document.getElementById('bx-leggi');
  btn.disabled=true; btn.innerHTML='<span class="sp">⟳</span> Lettura...';
  bx.className='bx inf show'; bx.textContent='Lettura data.js dal server...';
  var d = await call('leggi_sito');
  btn.disabled=false; btn.innerHTML='🔄 Leggi sito live';
  if (!d.ok) { bx.className='bx er show'; bx.textContent='❌ '+d.error; return; }
  bx.className='bx ok show'; bx.textContent='✅ data.js letto correttamente';
  document.getElementById('live-sg').style.display='grid';
  document.getElementById('lv-n').textContent=d.notizie;
  document.getElementById('lv-e').textContent=d.eventi;
  document.getElementById('lv-f').textContent=d.farmacie;
  document.getElementById('lv-s').textContent=d.servizi;
  document.getElementById('lv-l').textContent=d.locali;
  document.getElementById('lv-sp').textContent=d.sponsor;
}

async function doImporta() {
  var btn=document.getElementById('btn-imp'), bx=document.getElementById('bx-imp');
  btn.disabled=true; btn.innerHTML='<span class="sp">⟳</span> Importazione...';
  bx.className='bx inf show'; bx.textContent='Lettura e importazione in corso...';
  var d = await call('importa');
  btn.disabled=false; btn.innerHTML='⬇️ Importa dal sito';
  if (!d.ok) { bx.className='bx er show'; bx.textContent='❌ '+d.error; return; }
  var i=d.importati||{};
  bx.className='bx ok show';
  bx.innerHTML='✅ '+d.messaggio+'<br>'
    +'📰 '+i.notizie+' notizie &nbsp;|&nbsp; 📅 '+i.eventi+' eventi &nbsp;|&nbsp; 💊 '+i.farmacie+' farmacie<br>'
    +'🔧 '+i.servizi+' servizi &nbsp;|&nbsp; 🏠 '+i.locali+' locali &nbsp;|&nbsp; ⭐ '+i.sponsor+' sponsor';
  toast('✅ Importazione completata!');
  setTimeout(function(){location.reload();},2000);
}

async function doPubblica() {
  if (!confirm('Pubblicare tutti i contenuti del CMS sul sito?')) return;
  var btn=document.getElementById('btn-pub'), bx=document.getElementById('bx-pub');
  btn.disabled=true; btn.innerHTML='<span class="sp">⟳</span> Pubblicazione...';
  bx.className='bx inf show'; bx.textContent='Scrittura data.js...';
  var fd=new FormData(); fd.append('ajax','pubblica');
  var r=await fetch('/admin/sincronizza.php?ajax=pubblica',{method:'POST',body:fd});
  var txt=await r.text(); var d;
  try{d=JSON.parse(txt);}catch(e){bx.className='bx er show';bx.innerHTML='❌ Risposta non valida:<br><pre style="font-size:11px;overflow:auto;max-height:100px">'+txt.substring(0,400)+'</pre>';btn.disabled=false;btn.innerHTML='🚀 Pubblica sul sito';return;}
  btn.disabled=false; btn.innerHTML='🚀 Pubblica sul sito';
  if (!d.ok){bx.className='bx er show';bx.innerHTML='❌ '+d.error+(d.root?'<br>Root: '+d.root:'');return;}
  bx.className='bx ok show';
  bx.innerHTML='✅ '+d.messaggio+' ('+d.timestamp+')<br>'
    +'📰 '+d.notizie+' | 📅 '+d.eventi+' | 💊 '+d.farmacie+' | 🔧 '+d.servizi+' | 🏠 '+d.locali+' | ⭐ '+d.sponsor+'<br>'
    +'📁 '+d.slug_creati+' cartelle | '+d.bytes+' bytes scritti';
  toast('✅ Sito aggiornato!');
}

async function doDebug() {
  var bx=document.getElementById('bx-dbg');
  bx.className='bx inf show'; bx.textContent='...';
  var d=await call('debug');
  bx.className=d.exists?'bx ok show':'bx er show';
  bx.innerHTML='<b>admin/:</b> '+d.admin_dir+'<br>'
    +'<b>site_root:</b> '+d.site_root+'<br>'
    +'<b>data.js:</b> '+d.data_js+'<br>'
    +'<b>esiste:</b> '+(d.exists?'✅ Sì':'❌ NON TROVATO')+'<br>'
    +'<b>leggibile:</b> '+(d.readable?'✅':'❌')
    +'  <b>scrivibile:</b> '+(d.writable?'✅':'❌ → 644 via FTP')+'<br>'
    +'<b>cartella scrivibile:</b> '+(d.dir_writable?'✅':'❌ → 755 via FTP')+'<br>'
    +'<b>file in root:</b> '+JSON.stringify(d.root_files);
  if (!d.exists) bx.innerHTML+='<br><br>⚠️ Verifica che admin/ sia nella stessa cartella di assets/';
}
</script>

<?php require_once __DIR__ . '/includes/layout_end.php'; ?>
