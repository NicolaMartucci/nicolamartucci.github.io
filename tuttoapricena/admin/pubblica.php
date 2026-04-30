<?php
// ============================================================
// TuttoApricena — Pubblica/Sincronizza
// Scrive data.js nella posizione corretta sul server
// ============================================================
require_once __DIR__ . '/includes/config.php';
requireLogin();

header('Content-Type: application/json; charset=utf-8');

$action = (isset($_POST['action']) ? $_POST['action'] : $_GET['action']) ?: '';

// ---- Trova root del sito ----
function trovaSiteRoot() {
    // Sali da admin/ finché trovi assets/js/data.js
    $dir = dirname(__DIR__);
    for ($i = 0; $i < 8; $i++) {
        if (file_exists($dir . '/assets/js/data.js')) return $dir;
        if (file_exists($dir . '/assets/css/style.css')) return $dir;
        $parent = dirname($dir);
        if ($parent === $dir) break;
        $dir = $parent;
    }
    return dirname(__DIR__);
}

$SITE_ROOT   = trovaSiteRoot();
$DATA_JS     = $SITE_ROOT . '/assets/js/data.js';

// ---- DEBUG ----
if ($action === 'debug') {
    $listing = [];
    if (is_dir($SITE_ROOT)) {
        $listing = array_slice(scandir($SITE_ROOT), 0, 30);
    }
    echo json_encode([
        'ok'            => true,
        'admin_dir'     => __DIR__,
        'site_root'     => $SITE_ROOT,
        'data_js'       => $DATA_JS,
        'data_js_exists'=> file_exists($DATA_JS),
        'data_js_read'  => is_readable($DATA_JS),
        'data_js_write' => is_writable($DATA_JS),
        'dir_write'     => is_writable(dirname($DATA_JS)),
        'root_files'    => $listing,
    ], JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE);
    exit;
}

// ---- IMPORTA DAL SITO: riceve il JSON dal browser ----
// Il browser carica data.js, lo parsa, e manda il JSON qui
if ($action === 'import_json') {
    $raw = file_get_contents('php://input');
    $ta  = json_decode($raw, true);
    if (!$ta) {
        echo json_encode(['ok'=>false,'error'=>'JSON non valido ricevuto']); exit;
    }

    $importati = ['notizie'=>0,'eventi'=>0,'farmacie'=>0,'servizi'=>0,'locali'=>0,'sponsor'=>0];

    // NOTIZIE
    $cmsList = loadData('notizie') ?: [];
    $esistenti = array_column($cmsList, 'slug');
    foreach ((isset($ta['notizie']) ? $ta['notizie'] : []) as $n) {
        $slug = (isset($n['slug']) ? $n['slug'] : '');
        if (!$slug || in_array($slug, $esistenti)) continue;
        $cmsList[] = [
            'id'       => generateId(),
            'slug'     => $slug,
            'titolo'   => (isset($n['titolo']) ? $n['titolo'] : ''),
            'categoria'=> (isset($n['categoria']) ? $n['categoria'] : 'Cronaca'),
            'data'     => (isset($n['data']) ? $n['data'] : date('Y-m-d')),
            'sommario' => (isset($n['abstract']) ? $n['abstract'] : ''),
            'testo'    => (isset($n['testo']) ? $n['testo'] : ''),
            'fonte'    => (isset($n['fonte']) ? $n['fonte'] : ''),
            'foto'     => (isset($n['immagine']) ? $n['immagine'] : ''),
            'evidenza' => !empty($n['inEvidenza']),
            'creato'   => date('Y-m-d H:i:s'),
        ];
        $importati['notizie']++;
    }
    saveData('notizie', $cmsList);

    // EVENTI
    $cmsList = loadData('eventi') ?: [];
    $esistenti = array_column($cmsList, 'slug');
    foreach ((isset($ta['eventi']) ? $ta['eventi'] : []) as $e) {
        $slug = (isset($e['slug']) ? $e['slug'] : '');
        if (!$slug || in_array($slug, $esistenti)) continue;
        $cmsList[] = [
            'id'             => generateId(),
            'slug'           => $slug,
            'titolo'         => (isset($e['titolo']) ? $e['titolo'] : ''),
            'categoria'      => (isset($e['categoria']) ? $e['categoria'] : ''),
            'data'           => (isset($e['dataInizio']) ? $e['dataInizio'] : ''),
            'ora'            => (isset($e['orario']) ? $e['orario'] : ''),
            'luogo'          => (isset($e['luogo']) ? $e['luogo'] : ''),
            'descrizione'    => (isset($e['descrizione']) ? $e['descrizione'] : ''),
            'foto'           => (isset($e['immagine']) ? $e['immagine'] : ''),
            'evidenza'       => !empty($e['inEvidenza']),
            'link_biglietti' => (isset($e['ticketUrl']) ? $e['ticketUrl'] : ''),
            'creato'         => date('Y-m-d H:i:s'),
        ];
        $importati['eventi']++;
    }
    saveData('eventi', $cmsList);

    // FARMACIE — sostituisce se CMS vuoto
    $cmsList = loadData('farmacie') ?: [];
    if (empty($cmsList)) {
        foreach ((isset($ta['farmacie']) ? $ta['farmacie'] : []) as $f) {
            $cmsList[] = [
                'id'       => generateId(),
                'nome'     => (isset($f['nome']) ? $f['nome'] : ''),
                'indirizzo'=> (isset($f['indirizzo']) ? $f['indirizzo'] : ''),
                'telefono' => (isset($f['telefono']) ? $f['telefono'] : ''),
                'orario'   => (isset($f['orario']) ? $f['orario'] : ''),
                'notturna' => !empty($f['notturno']),
            ];
            $importati['farmacie']++;
        }
        saveData('farmacie', $cmsList);
    }

    // SERVIZI
    $cmsList = loadData('servizi') ?: [];
    if (empty($cmsList)) {
        foreach ((isset($ta['servizi']) ? $ta['servizi'] : []) as $s) {
            $cmsList[] = [
                'id'          => generateId(),
                'nome'        => (isset($s['nome']) ? $s['nome'] : ''),
                'categoria'   => (isset($s['categoria']) ? $s['categoria'] : ''),
                'descrizione' => (isset($s['descrizione']) ? $s['descrizione'] : ''),
                'indirizzo'   => (isset($s['indirizzo']) ? $s['indirizzo'] : ''),
                'telefono'    => (isset($s['telefono']) ? $s['telefono'] : ''),
                'sito_web'    => (isset($s['sitoWeb']) ? $s['sitoWeb'] : ''),
                'orario'      => (isset($s['orari']) ? $s['orari'] : ''),
                'evidenza'    => false,
            ];
            $importati['servizi']++;
        }
        saveData('servizi', $cmsList);
    }

    // LOCALI
    $cmsList = loadData('locali') ?: [];
    $esistenti = array_column($cmsList, 'slug');
    foreach ((isset($ta['locali']) ? $ta['locali'] : []) as $l) {
        $slug = (isset($l['slug']) ? $l['slug'] : '');
        if (!$slug || in_array($slug, $esistenti)) continue;
        $cmsList[] = [
            'id'          => generateId(),
            'slug'        => $slug,
            'nome'        => (isset($l['nome']) ? $l['nome'] : ''),
            'tipo'        => (isset($l['tipo']) ? $l['tipo'] : ''),
            'descrizione' => (isset($l['descrizione']) ? $l['descrizione'] : ''),
            'indirizzo'   => (isset($l['indirizzo']) ? $l['indirizzo'] : ''),
            'telefono'    => (isset($l['telefono']) ? $l['telefono'] : ''),
            'sito_web'    => (isset($l['sitoWeb']) ? $l['sitoWeb'] : ''),
            'orario'      => (isset($l['orari']) ? $l['orari'] : ''),
            'foto'        => (isset($l['immagine']) ? $l['immagine'] : ''),
            'evidenza'    => !empty($l['inEvidenza']),
            'creato'      => date('Y-m-d H:i:s'),
        ];
        $importati['locali']++;
    }
    saveData('locali', $cmsList);

    // SPONSOR
    $cmsList = loadData('sponsor') ?: [];
    if (empty($cmsList)) {
        foreach ((isset($ta['sponsor']) ? $ta['sponsor'] : []) as $s) {
            if (empty($s['nome'])) continue;
            $cmsList[] = [
                'id'          => generateId(),
                'nome'        => (isset($s['nome']) ? $s['nome'] : ''),
                'livello'     => (isset($s['livello']) ? $s['livello'] : 'Bronze'),
                'descrizione' => (isset($s['settore']) ? $s['settore'] : ''),
                'sito_web'    => (isset($s['sito']) ? $s['sito'] : $s['sitoWeb']) ?: '',
                'telefono'    => (isset($s['telefono']) ? $s['telefono'] : ''),
                'foto'        => (isset($s['foto']) ? $s['foto'] : ''),
                'evidenza'    => false,
            ];
            $importati['sponsor']++;
        }
        saveData('sponsor', $cmsList);
    }

    echo json_encode(['ok'=>true, 'importati'=>$importati, 'messaggio'=>'Importazione completata!']);
    exit;
}

// ---- PUBBLICA: CMS → data.js ----
if ($action === 'pubblica') {
    if (!file_exists($DATA_JS)) {
        echo json_encode(['ok'=>false,'error'=>"data.js non trovato in: $DATA_JS",'site_root'=>$SITE_ROOT]); exit;
    }
    if (!is_writable($DATA_JS) && !is_writable(dirname($DATA_JS))) {
        echo json_encode(['ok'=>false,'error'=>'data.js non scrivibile. Imposta permessi 644 su assets/js/data.js via FTP.']); exit;
    }

    // Leggi data.js attuale per preservare citta, categorie, ecc.
    $attuale = leggiDataJsAttuale($DATA_JS);

    $notizie  = loadData('notizie')  ?: [];
    $eventi   = loadData('eventi')   ?: [];
    $farmacie = loadData('farmacie') ?: [];
    $servizi  = loadData('servizi')  ?: [];
    $locali   = loadData('locali')   ?: [];
    $sponsor  = loadData('sponsor')  ?: [];
    $settings = loadData('settings') ?: [];
    $chiSiamo = loadData('chiSiamo'); if(!is_array($chiSiamo)||isset($chiSiamo[0]))$chiSiamo=[];

    // Converti formati — compatibile PHP 7.0+
    $notizieOut = [];
    foreach ($notizie as $n) {
        $notizieOut[] = [
            'id'           => crc32(isset($n['slug']) ? $n['slug'] : $n['id']),
            'slug'         => isset($n['slug']) ? $n['slug'] : slugify(isset($n['titolo']) ? $n['titolo'] : ''),
            'titolo'       => isset($n['titolo']) ? $n['titolo'] : '',
            'categoria'    => isset($n['categoria']) ? $n['categoria'] : 'Cronaca',
            'categoriaSlug'=> strtolower(str_replace(array('à','è','é','ì','ò','ù',' '), array('a','e','e','i','o','u','-'), isset($n['categoria']) ? $n['categoria'] : 'cronaca')),
            'immagine'     => isset($n['foto']) ? $n['foto'] : '',
            'abstract'     => isset($n['sommario']) ? $n['sommario'] : '',
            'testo'        => isset($n['testo']) ? $n['testo'] : '',
            'fonte'        => isset($n['fonte']) ? $n['fonte'] : '',
            'fonteUrl'     => isset($n['fonte_url']) ? $n['fonte_url'] : '',
            'externalLink' => (isset($n['fonte_url']) && $n['fonte_url']) ? $n['fonte_url'] : '',
            'data'         => isset($n['data']) ? $n['data'] : date('Y-m-d'),
            'inEvidenza'   => !empty($n['evidenza']),
            'tag'          => array(),
        ];
    }
    usort($notizieOut, function($a, $b) { return strcmp($b['data'], $a['data']); });

    $eventiOut = [];
    foreach ($eventi as $e) {
        $eventiOut[] = [
            'id'          => is_numeric($e['id']) ? (int)$e['id'] : crc32($e['id']),
            'slug'        => isset($e['slug']) ? $e['slug'] : slugify(isset($e['titolo']) ? $e['titolo'] : ''),
            'titolo'      => isset($e['titolo']) ? $e['titolo'] : '',
            'categoria'   => isset($e['categoria']) ? $e['categoria'] : 'Evento',
            'dataInizio'  => isset($e['data']) ? $e['data'] : '',
            'dataFine'    => isset($e['data']) ? $e['data'] : '',
            'orario'      => isset($e['ora']) ? $e['ora'] : '',
            'luogo'       => isset($e['luogo']) ? $e['luogo'] : '',
            'descrizione' => isset($e['descrizione']) ? $e['descrizione'] : '',
            'immagine'    => isset($e['foto']) ? $e['foto'] : '',
            'inEvidenza'  => !empty($e['evidenza']),
            'ticketUrl'   => isset($e['link_biglietti']) ? $e['link_biglietti'] : '',
            'ordine'      => 0,
        ];
    }
    usort($eventiOut, function($a, $b) { return strcmp($a['dataInizio'], $b['dataInizio']); });

    $farmacieOut = [];
    $idx = 1;
    foreach ($farmacie as $f) {
        $farmacieOut[] = array('id'=>$idx++,'nome'=>isset($f['nome'])?$f['nome']:'','indirizzo'=>isset($f['indirizzo'])?$f['indirizzo']:'','telefono'=>isset($f['telefono'])?$f['telefono']:'','orario'=>isset($f['orario'])?$f['orario']:'','notturno'=>!empty($f['notturna']));
    }

    $icoMap = array('Istituzioni'=>'building','Sanità'=>'heart','Sicurezza'=>'shield','Trasporti'=>'train','Cultura'=>'book','Servizi'=>'mail');
    $serviziOut = [];
    $idx = 1;
    foreach ($servizi as $s) {
        $cat = isset($s['categoria']) ? $s['categoria'] : '';
        $serviziOut[] = array('id'=>$idx++,'nome'=>isset($s['nome'])?$s['nome']:'','categoria'=>$cat,'categoriaSlug'=>slugify($cat),'icona'=>isset($icoMap[$cat])?$icoMap[$cat]:'info','indirizzo'=>isset($s['indirizzo'])?$s['indirizzo']:'','telefono'=>isset($s['telefono'])?$s['telefono']:'','email'=>'','sitoWeb'=>isset($s['sito_web'])?$s['sito_web']:'','orari'=>isset($s['orario'])?$s['orario']:'','descrizione'=>isset($s['descrizione'])?$s['descrizione']:'');
    }

    $localiOut = [];
    foreach ($locali as $l) {
        $localiOut[] = [
            'id'          => is_numeric($l['id']) ? (int)$l['id'] : crc32($l['id']),
            'slug'        => isset($l['slug']) ? $l['slug'] : slugify(isset($l['nome']) ? $l['nome'] : ''),
            'nome'        => isset($l['nome']) ? $l['nome'] : '',
            'tipo'        => isset($l['tipo']) ? $l['tipo'] : '',
            'tipoSlug'    => slugify(isset($l['tipo']) ? $l['tipo'] : ''),
            'descrizione' => isset($l['descrizione']) ? $l['descrizione'] : '',
            'indirizzo'   => isset($l['indirizzo']) ? $l['indirizzo'] : '',
            'telefono'    => isset($l['telefono']) ? $l['telefono'] : '',
            'sitoWeb'     => isset($l['sito_web']) ? $l['sito_web'] : '',
            'orari'            => isset($l['orario']) ? $l['orario'] : '',
            'orarioNota'       => isset($l['orario_nota']) ? $l['orario_nota'] : '',
            'orariStrutturati' => isset($l['orari_strutturati']) ? $l['orari_strutturati'] : null,
            'immagine'         => isset($l['foto']) ? $l['foto'] : '',
            'gallery'          => isset($l['gallery']) ? $l['gallery'] : [],
            'inEvidenza'       => !empty($l['evidenza']),
        ];
    }

    $sponsorOut = [];
    foreach ($sponsor as $s) {
        $sponsorOut[] = [
            'id'          => isset($s['id']) ? $s['id'] : uniqid(),
            'nome'        => isset($s['nome']) ? $s['nome'] : '',
            'livello'     => isset($s['livello']) ? $s['livello'] : 'Bronze',
            'descrizione' => isset($s['descrizione']) ? $s['descrizione'] : '',
            'indirizzo'   => isset($s['indirizzo']) ? $s['indirizzo'] : '',
            'sito_web'    => isset($s['sito_web']) ? $s['sito_web'] : '',
            'sito'        => isset($s['sito_web']) ? $s['sito_web'] : '',
            'sitoWeb'     => isset($s['sito_web']) ? $s['sito_web'] : '',
            'telefono'    => isset($s['telefono']) ? $s['telefono'] : '',
            'orario'      => isset($s['orario']) ? $s['orario'] : '',
            'foto'        => isset($s['foto']) ? $s['foto'] : '',
            'attivo'      => true,
        ];
    }

    $ta = [
        'config' => array_merge((isset($attuale['config']) ? $attuale['config'] : []), [
            'siteName'    => (isset($settings['nome_sito']) && $settings['nome_sito'] ? $settings['nome_sito'] : (isset($attuale['config']['siteName']) ? $attuale['config']['siteName'] : 'TuttoApricena')),
            'colorPrimary'=> (isset($settings['colore_primario']) && $settings['colore_primario'] ? $settings['colore_primario'] : (isset($attuale['config']['colorPrimary']) ? $attuale['config']['colorPrimary'] : '#1a1a2e')),
            'colorAccent' => (isset($settings['colore_accent']) && $settings['colore_accent'] ? $settings['colore_accent'] : (isset($attuale['config']['colorAccent']) ? $attuale['config']['colorAccent'] : '#e8a838')),
            'sezioniVisibili' => (isset($settings['sezioni_visibili']) && is_array($settings['sezioni_visibili']) ? $settings['sezioni_visibili'] : (object)[]),
            'facebook'    => (isset($settings['facebook'])  ? $settings['facebook']  : (isset($attuale['config']['facebook'])  ? $attuale['config']['facebook']  : '')),
            'instagram'   => (isset($settings['instagram']) ? $settings['instagram'] : (isset($attuale['config']['instagram']) ? $attuale['config']['instagram'] : '')),
            'chiSiamo'    => (object)(is_array($chiSiamo) ? $chiSiamo : []),
        ]),
        'notizie'          => $notizieOut,
        'categorieNotizie' => (isset($attuale['categorieNotizie']) && $attuale['categorieNotizie'] ? $attuale['categorieNotizie'] : defaultCategorie()),
        'eventi'           => $eventiOut,
        'farmacie'         => $farmacieOut,
        'servizi'          => $serviziOut,
        'categorieServizi' => (isset($attuale['categorieServizi']) ? $attuale['categorieServizi'] : []),
        'locali'           => $localiOut,
        'tipiLocali'       => (isset($attuale['tipiLocali']) ? $attuale['tipiLocali'] : []),
        'sponsor'          => $sponsorOut,
        'citta'            => (isset($attuale['citta']) ? $attuale['citta'] : []),
    ];

    $ts  = date('d/m/Y, H:i:s');
    $json = json_encode($ta, JSON_PRETTY_PRINT|JSON_UNESCAPED_UNICODE|JSON_UNESCAPED_SLASHES);
    $js  = "// TUTTOAPRICENA - DATA FILE\n// CMS Update: $ts\nconst TA = $json;\n\n";
    $js .= 'TA.getCatColor = function(slug) { var c = TA.categorieNotizie.find(function(x){ return x.slug === slug; }); return c ? c.colore : "#E8A838"; };'."\n";
    $js .= 'TA.formatDate = function(d, opts) { return new Date(d).toLocaleDateString("it-IT", opts || { day: "numeric", month: "long", year: "numeric" }); };'."\n";
    // Rotazione settimanale sabato/domenica: ogni settimana una farmacia diversa fa il turno festivo
    // Epoch = lunedì 5 gennaio 2026 (primo lunedì dell'anno, settimana 0)
    // La settimana si calcola in base a quante settimane sono passate dall'epoch
    // Sabato e domenica della stessa settimana = stessa farmacia di turno
    $js .= 'TA.getSettimanaFestiva = function(date) {'."\n";
    $js .= '  if(!TA.farmacie||!TA.farmacie.length) return null;'."\n";
    $js .= '  var d = date ? new Date(date) : new Date();'."\n";
    $js .= '  d.setHours(0,0,0,0);'."\n";
    $js .= '  // Calcola il lunedì della settimana corrente'."\n";
    $js .= '  var dow = d.getDay(); // 0=dom, 1=lun, ...6=sab'."\n";
    $js .= '  var diffToMon = (dow === 0) ? -6 : 1 - dow;'."\n";
    $js .= '  var lun = new Date(d); lun.setDate(d.getDate() + diffToMon);'."\n";
    $js .= '  // Settimana 0 = 5 gennaio 2026'."\n";
    $js .= '  var epoch = new Date("2026-01-05T00:00:00");'."\n";
    $js .= '  var weekNum = Math.round((lun - epoch) / (7 * 86400000));'."\n";
    $js .= '  var idx = ((weekNum % TA.farmacie.length) + TA.farmacie.length) % TA.farmacie.length;'."\n";
    $js .= '  // Sabato e domenica della settimana'."\n";
    $js .= '  var sab = new Date(lun); sab.setDate(lun.getDate() + 5);'."\n";
    $js .= '  var dom = new Date(lun); dom.setDate(lun.getDate() + 6);'."\n";
    $js .= '  return { farmacia: TA.farmacie[idx], sabato: sab, domenica: dom, settimana: weekNum };'."\n";
    $js .= '};'."\n";
    $js .= 'TA.getTurniProssimeSettimane = function(n) {'."\n";
    $js .= '  var risultati = []; var oggi = new Date(); oggi.setHours(0,0,0,0);'."\n";
    $js .= '  for (var i = 0; i < (n || 8); i++) {'."\n";
    $js .= '    var dataRef = new Date(oggi); dataRef.setDate(oggi.getDate() + i * 7);'."\n";
    $js .= '    // Porta alla settimana i-esima dal prossimo sabato'."\n";
    $js .= '    var dow = dataRef.getDay();'."\n";
    $js .= '    var diffToSab = dow <= 6 ? 6 - dow : 0;'."\n";
    $js .= '    var sabRef = new Date(dataRef); sabRef.setDate(dataRef.getDate() + diffToSab - (dow===0?1:0)*7);'."\n";
    $js .= '    // Usa lun della settimana del sabato'."\n";
    $js .= '    risultati.push(TA.getSettimanaFestiva(sabRef));'."\n";
    $js .= '  }'."\n";
    $js .= '  // Dedup per settimana'."\n";
    $js .= '  var seen = {}; return risultati.filter(function(r){ if(!r||seen[r.settimana]) return false; seen[r.settimana]=true; return true; });'."\n";
    $js .= '};'."\n";
    // Mantieni getFarmaciaOfDay come fallback compatibile
    $js .= 'TA.getFarmaciaOfDay = function(date) { return TA.getSettimanaFestiva(date) ? TA.getSettimanaFestiva(date).farmacia : null; };'."\n";
    $js .= 'if (typeof module !== "undefined") module.exports = TA;'."\n";

    $written = file_put_contents($DATA_JS, $js);
    if ($written === false) {
        echo json_encode(['ok'=>false,'error'=>'Scrittura fallita. Imposta permessi 644 su assets/js/data.js via FTP.']); exit;
    }

    // ── AGGIORNA FOTO CHI SIAMO in chi-siamo/index.html ──────────────────
    $DEFAULT_IMG = 'https://images.unsplash.com/photo-1555992336-03a23c7b20ee?w=700&q=80';
    $newImg      = (!empty($chiSiamo['immagine'])) ? $chiSiamo['immagine'] : $DEFAULT_IMG;
    $csHtmlPath  = $SITE_ROOT . '/chi-siamo/index.html';

    if (!file_exists($csHtmlPath)) {
        error_log('TuttoApricena: chi-siamo/index.html non trovato in ' . $csHtmlPath);
    } elseif (!is_writable($csHtmlPath)) {
        error_log('TuttoApricena: chi-siamo/index.html non scrivibile — esegui chmod 644 chi-siamo/index.html');
    } else {
        $csHtml    = file_get_contents($csHtmlPath);
        $escapedImg = htmlspecialchars($newImg, ENT_QUOTES, 'UTF-8');

        // Strategia robusta: callback che sostituisce src SOLO nel tag con id="cs-img"
        // Gestisce qualunque ordine di attributi (loading, id, src, alt, style…)
        $csHtmlNew = preg_replace_callback(
            '/<img\b[^>]*>/s',
            function ($m) use ($escapedImg) {
                if (strpos($m[0], 'id="cs-img"') === false) return $m[0];
                // Sostituisce src="..." esistente
                $replaced = preg_replace('/\bsrc="[^"]*"/', 'src="' . $escapedImg . '"', $m[0]);
                // Se src non era presente, lo aggiunge prima della chiusura del tag
                if ($replaced === $m[0]) {
                    $replaced = preg_replace('/(\s*\/?>)$/', ' src="' . $escapedImg . '"$1', $m[0]);
                }
                return $replaced;
            },
            $csHtml
        );

        if ($csHtmlNew !== null && $csHtmlNew !== $csHtml) {
            file_put_contents($csHtmlPath, $csHtmlNew);
        }
    }

            // Crea cartelle slug
    $creati = 0;
    foreach ($notizieOut as $n) { $creati += creaSlugDir($SITE_ROOT, 'notizie', $n['slug'], $n['titolo']); }
    foreach ($eventiOut  as $e) { $creati += creaSlugDir($SITE_ROOT, 'eventi',  $e['slug'], $e['titolo']); }
    foreach ($localiOut  as $l) { $creati += creaSlugDir($SITE_ROOT, 'locali',  $l['slug'], $l['nome']); }

    echo json_encode(['ok'=>true,'messaggio'=>'Sito aggiornato!','timestamp'=>$ts,'notizie'=>count($notizieOut),'eventi'=>count($eventiOut),'farmacie'=>count($farmacieOut),'servizi'=>count($serviziOut),'locali'=>count($localiOut),'sponsor'=>count($sponsorOut),'slug_creati'=>$creati,'bytes'=>$written]);
    exit;
}

echo json_encode(['error'=>'Azione non riconosciuta: '.$action]);

// ============================================================
function leggiDataJsAttuale($path) {
    $c = @file_get_contents($path);
    if (!$c) return [];
    if (preg_match('/const TA\s*=\s*(\{.+?\});\s*\nTA\./s', $c, $m)) {
        return json_decode($m[1], true) ?: [];
    }
    return [];
}
function slugify($t) {
    $t = mb_strtolower($t,'UTF-8');
    $t = strtr($t,['à'=>'a','á'=>'a','è'=>'e','é'=>'e','ì'=>'i','í'=>'i','ò'=>'o','ó'=>'o','ù'=>'u','ú'=>'u','ç'=>'c','ñ'=>'n']);
    $t = preg_replace('/[^a-z0-9\s-]/u','',$t);
    $t = preg_replace('/[\s-]+/','-',$t);
    return trim($t,'-');
}
function creaSlugDir($root, $sezione, $slug, $titolo) {
    if (!$slug) return 0;
    $dir = $root.'/'.$sezione.'/'.$slug.'/';
    if (is_dir($dir)) return 0;
    if (!@mkdir($dir, 0755, true)) return 0;
    $h = htmlspecialchars($titolo).' — TuttoApricena';
    $tpl = <<<HTML
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8"/><meta name="viewport" content="width=device-width,initial-scale=1.0"/>
  <title>$h</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link rel="stylesheet" media="print" onload="this.media='all'" href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,800;0,900;1,700&family=Inter:wght@400;600;700;800&display=swap"/>
  <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,800;0,900;1,700&family=Inter:wght@400;600;700;800&display=swap"/></noscript>
  <link rel="stylesheet" href="../../assets/css/style.css"/>
  <script src="../../assets/js/lucide-mini.js"></script>
</head>
<body>
<header id="navbar">
  <div class="navbar-inner"><div class="navbar-row">
    <a href="../../" class="navbar-logo"><div class="navbar-logo-icon"><span>TA</span></div><span class="navbar-logo-text">Tutto<em>Apricena</em></span></a>
    <nav class="navbar-links"><a href="../../notizie/">Notizie</a><a href="../../eventi/">Eventi</a><a href="../../farmacie/">Farmacie</a><a href="../../servizi/">Servizi</a><a href="../../locali/">Locali</a><a href="../../sponsor/">Sponsor</a></nav>
    <div class="navbar-actions"><a href="../../chi-siamo/" class="link-plain">Chi siamo</a><a href="../../contatti/" class="link-btn">Contatti</a></div>
    <button id="mobile-menu-btn" class="mobile-menu-btn" aria-label="Menu"><i data-lucide="menu" class="icon-hamburger" width="24" height="24"></i><i data-lucide="x" class="icon-x" width="24" height="24" style="display:none"></i></button>
  </div></div>
  <div id="mobile-menu" class="mobile-menu"><div class="mobile-menu-inner">
    <nav class="mobile-nav"><a href="../../notizie/">Notizie</a><a href="../../eventi/">Eventi</a><a href="../../farmacie/">Farmacie</a><a href="../../servizi/">Servizi</a><a href="../../locali/">Locali</a><a href="../../sponsor/">Sponsor</a></nav>
    <a href="../../contatti/" class="mobile-cta">Contattaci</a>
  </div></div>
</header>
<main id="pg-main" style="padding-top:72px;min-height:80vh">
  <div style="padding:60px 24px;text-align:center;color:var(--color-text-muted)"><i data-lucide="loader" width="32" height="32"></i><br>Caricamento...</div>
</main>
<footer><div class="footer-inner"><div class="footer-grid">
  <div class="footer-brand"><a href="../../" class="navbar-logo"><div class="navbar-logo-icon"><span>TA</span></div><span class="navbar-logo-text">Tutto<em>Apricena</em></span></a><p>Il portale informativo di Apricena.</p></div>
  <div class="footer-col"><h4>Sezioni</h4><ul><li><a href="../../notizie/">Notizie</a></li><li><a href="../../eventi/">Eventi</a></li><li><a href="../../locali/">Locali</a></li></ul></div>
  <div class="footer-col"><h4>Info</h4><ul><li><a href="../../chi-siamo/">Chi Siamo</a></li><li><a href="../../contatti/">Contatti</a></li><li><a href="../../privacy/">Privacy</a></li></ul></div>
</div><div class="footer-bottom"><p>&copy; <span id="year"></span> TuttoApricena</p></div></div></footer>
<script src="../../assets/js/data.js"></script>
<script src="../../assets/js/main.js"></script>
<script src="../../assets/js/slug-renderer.js"></script>
<script>document.getElementById('year').textContent=new Date().getFullYear();TARenderer.render('$sezione');</script>
</body>
</html>
HTML;
    file_put_contents($dir.'index.html', $tpl);
    return 1;
}
function defaultCategorie() {
    return [['nome'=>'Cronaca','slug'=>'cronaca','colore'=>'#DC2626'],['nome'=>'Cultura','slug'=>'cultura','colore'=>'#7C3AED'],['nome'=>'Sport','slug'=>'sport','colore'=>'#16A34A'],['nome'=>'Economia','slug'=>'economia','colore'=>'#0284C7'],['nome'=>'Società','slug'=>'societa','colore'=>'#EA580C'],['nome'=>'Turismo','slug'=>'turismo','colore'=>'#E8A838']];
}
