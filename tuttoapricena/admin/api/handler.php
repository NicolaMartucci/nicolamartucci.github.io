<?php
require_once __DIR__ . '/../includes/config.php';
header('Content-Type: application/json');

if (!isLoggedIn()) {
    echo json_encode(array('error' => 'Non autenticato')); exit;
}

$action = isset($_POST['action']) ? $_POST['action'] : (isset($_GET['action']) ? $_GET['action'] : '');

// Helper: ottieni vecchia foto di un item
function getOldFoto($items, $id) {
    if (!$id) return '';
    $idx = array_search($id, array_column($items, 'id'));
    if ($idx === false) return '';
    return isset($items[$idx]['foto']) ? $items[$idx]['foto'] : '';
}

// Helper: ottieni campo 'creato' di un item esistente
function getCreato($items, $id) {
    if (!$id) return date('Y-m-d H:i:s');
    $idx = array_search($id, array_column($items, 'id'));
    if ($idx === false) return date('Y-m-d H:i:s');
    return isset($items[$idx]['creato']) ? $items[$idx]['creato'] : date('Y-m-d H:i:s');
}

// Helper: ottieni slug esistente o genera nuovo da titolo
function getOldSlug($items, $id, $titolo) {
    if (!$id) return slugify($titolo);
    $idx = array_search($id, array_column($items, 'id'));
    if ($idx !== false && !empty($items[$idx]['slug'])) return $items[$idx]['slug'];
    return slugify($titolo);
}

// Helper: salva o aggiorna item
function upsertItem(&$items, $id, $record) {
    if ($id) {
        $idx = array_search($id, array_column($items, 'id'));
        if ($idx !== false) $items[$idx] = $record;
        else $items[] = $record;
    } else {
        $items[] = $record;
    }
}

// Helper: foto finale (upload > post url > vecchia foto)
function fotoFinale($imgUrl, $postFotoUrl, $oldFoto) {
    if ($imgUrl) return $imgUrl;
    if ($postFotoUrl) return $postFotoUrl;
    return $oldFoto;
}

// =============================================
// NOTIZIE
// =============================================
if ($action === 'notizie_save') {
    if (!hasPermission('notizie')) { echo json_encode(array('error'=>'Permesso negato')); exit; }
    $items  = loadData('notizie');
    $id     = isset($_POST['id']) ? $_POST['id'] : '';
    $imgUrl = (!empty($_FILES['foto']['name'])) ? handleUpload('foto', 'notizie') : null;
    $record = array(
        'id'        => $id ? $id : generateId(),
        'titolo'    => trim(isset($_POST['titolo'])    ? $_POST['titolo']    : ''),
        'categoria' => trim(isset($_POST['categoria']) ? $_POST['categoria'] : 'Cronaca'),
        'data'      => trim(isset($_POST['data'])      ? $_POST['data']      : date('Y-m-d')),
        'testo'     => trim(isset($_POST['testo'])     ? $_POST['testo']     : ''),
        'sommario'  => trim(isset($_POST['sommario'])  ? $_POST['sommario']  : ''),
        'fonte'     => trim(isset($_POST['fonte'])     ? $_POST['fonte']     : ''),
        'fonte_url' => trim(isset($_POST['fonte_url']) ? $_POST['fonte_url'] : ''),
        'foto'      => fotoFinale($imgUrl, isset($_POST['foto_url']) ? $_POST['foto_url'] : '', getOldFoto($items, $id)),
        'evidenza'  => isset($_POST['evidenza']) ? true : false,
        'creato'    => getCreato($items, $id),
    );
    upsertItem($items, $id, $record);
    saveData('notizie', $items);
    echo json_encode(array('ok'=>true, 'id'=>$record['id']));
}

elseif ($action === 'notizie_delete') {
    if (!hasPermission('notizie')) { echo json_encode(array('error'=>'Permesso negato')); exit; }
    $id    = isset($_POST['id']) ? $_POST['id'] : '';
    $items = loadData('notizie');
    $items = array_values(array_filter($items, function($x) use ($id) { return $x['id'] !== $id; }));
    saveData('notizie', $items);
    echo json_encode(array('ok'=>true));
}

elseif ($action === 'notizie_evidenza') {
    if (!hasPermission('notizie')) { echo json_encode(array('error'=>'Permesso negato')); exit; }
    $id    = isset($_POST['id']) ? $_POST['id'] : '';
    $items = loadData('notizie');
    foreach ($items as &$n) {
        if ($n['id'] === $id) $n['evidenza'] = !(isset($n['evidenza']) ? $n['evidenza'] : false);
    }
    saveData('notizie', $items);
    echo json_encode(array('ok'=>true));
}

// =============================================
// EVENTI
// =============================================
elseif ($action === 'eventi_save') {
    if (!hasPermission('eventi')) { echo json_encode(array('error'=>'Permesso negato')); exit; }
    $items  = loadData('eventi');
    $id     = isset($_POST['id']) ? $_POST['id'] : '';
    $imgUrl = (!empty($_FILES['foto']['name'])) ? handleUpload('foto', 'eventi') : null;
    $titolo_ev = trim(isset($_POST['titolo']) ? $_POST['titolo'] : '');
    $record = array(
        'id'             => $id ? $id : generateId(),
        'titolo'         => $titolo_ev,
        'slug'           => getOldSlug($items, $id, $titolo_ev),
        'categoria'      => trim(isset($_POST['categoria'])       ? $_POST['categoria']       : ''),
        'data'           => trim(isset($_POST['data'])            ? $_POST['data']            : ''),
        'ora'            => trim(isset($_POST['ora'])             ? $_POST['ora']             : ''),
        'luogo'          => trim(isset($_POST['luogo'])           ? $_POST['luogo']           : ''),
        'descrizione'    => trim(isset($_POST['descrizione'])     ? $_POST['descrizione']     : ''),
        'foto'           => fotoFinale($imgUrl, isset($_POST['foto_url']) ? $_POST['foto_url'] : '', getOldFoto($items, $id)),
        'link_biglietti' => trim(isset($_POST['link_biglietti'])  ? $_POST['link_biglietti']  : ''),
        'evidenza'       => isset($_POST['evidenza']) ? true : false,
        'creato'         => getCreato($items, $id),
    );
    upsertItem($items, $id, $record);
    saveData('eventi', $items);
    echo json_encode(array('ok'=>true, 'id'=>$record['id']));
}

elseif ($action === 'eventi_delete') {
    if (!hasPermission('eventi')) { echo json_encode(array('error'=>'Permesso negato')); exit; }
    $id    = isset($_POST['id']) ? $_POST['id'] : '';
    $items = array_values(array_filter(loadData('eventi'), function($x) use ($id) { return $x['id'] !== $id; }));
    saveData('eventi', $items);
    echo json_encode(array('ok'=>true));
}

elseif ($action === 'eventi_evidenza') {
    if (!hasPermission('eventi')) { echo json_encode(array('error'=>'Permesso negato')); exit; }
    $id    = isset($_POST['id']) ? $_POST['id'] : '';
    $items = loadData('eventi');
    foreach ($items as &$n) {
        if ($n['id'] === $id) $n['evidenza'] = !(isset($n['evidenza']) ? $n['evidenza'] : false);
    }
    saveData('eventi', $items);
    echo json_encode(array('ok'=>true));
}

// =============================================
// FARMACIE
// =============================================
elseif ($action === 'farmacie_save') {
    if (!hasPermission('farmacie')) { echo json_encode(array('error'=>'Permesso negato')); exit; }
    $items  = loadData('farmacie');
    $id     = isset($_POST['id']) ? $_POST['id'] : '';
    $record = array(
        'id'        => $id ? $id : generateId(),
        'nome'      => trim(isset($_POST['nome'])      ? $_POST['nome']      : ''),
        'indirizzo' => trim(isset($_POST['indirizzo']) ? $_POST['indirizzo'] : ''),
        'telefono'  => trim(isset($_POST['telefono'])  ? $_POST['telefono']  : ''),
        'orario'    => trim(isset($_POST['orario'])    ? $_POST['orario']    : ''),
        'lat'       => trim(isset($_POST['lat'])       ? $_POST['lat']       : ''),
        'lng'       => trim(isset($_POST['lng'])       ? $_POST['lng']       : ''),
        'notturna'  => isset($_POST['notturna']) ? true : false,
    );
    upsertItem($items, $id, $record);
    saveData('farmacie', $items);
    echo json_encode(array('ok'=>true));
}

elseif ($action === 'farmacie_delete') {
    if (!hasPermission('farmacie')) { echo json_encode(array('error'=>'Permesso negato')); exit; }
    $id    = isset($_POST['id']) ? $_POST['id'] : '';
    $items = array_values(array_filter(loadData('farmacie'), function($x) use ($id) { return $x['id'] !== $id; }));
    saveData('farmacie', $items);
    echo json_encode(array('ok'=>true));
}

elseif ($action === 'farmacie_reorder') {
    if (!hasPermission('farmacie')) { echo json_encode(array('error'=>'Permesso negato')); exit; }
    $order   = json_decode(isset($_POST['order']) ? $_POST['order'] : '[]', true);
    $items   = loadData('farmacie');
    $indexed = array();
    foreach ($items as $it) $indexed[$it['id']] = $it;
    $newItems = array();
    foreach ($order as $oid) if (isset($indexed[$oid])) $newItems[] = $indexed[$oid];
    saveData('farmacie', $newItems);
    echo json_encode(array('ok'=>true));
}

elseif ($action === 'reorder_evidenza') {
    if (!isLoggedIn()) { echo json_encode(array('error'=>'Non autenticato')); exit; }
    // Riordina solo gli elementi in evidenza, mantiene gli altri in coda
    $section = isset($_POST['section']) ? preg_replace('/[^a-z]/','',$_POST['section']) : '';
    $order   = json_decode(isset($_POST['order']) ? $_POST['order'] : '[]', true);
    if (!$section || !$order) { echo json_encode(array('ok'=>false,'error'=>'Dati mancanti')); exit; }
    $items   = loadData($section);
    $indexed = array();
    foreach ($items as $it) $indexed[strval($it['id'])] = $it;
    // Prima: tutti gli evidenza nell'ordine richiesto
    $newItems = array();
    foreach ($order as $oid) {
        $oid = strval($oid);
        if (isset($indexed[$oid])) { $newItems[] = $indexed[$oid]; unset($indexed[$oid]); }
    }
    // Poi: tutti gli altri (non in evidenza) nell'ordine originale
    foreach ($indexed as $it) $newItems[] = $it;
    saveData($section, $newItems);
    echo json_encode(array('ok'=>true));
}

// =============================================
// SERVIZI
// =============================================
elseif ($action === 'servizi_save') {
    if (!hasPermission('servizi')) { echo json_encode(array('error'=>'Permesso negato')); exit; }
    $items  = loadData('servizi');
    $id     = isset($_POST['id']) ? $_POST['id'] : '';
    $imgUrl = (!empty($_FILES['foto']['name'])) ? handleUpload('foto', 'servizi') : null;
    $record = array(
        'id'          => $id ? $id : generateId(),
        'nome'        => trim(isset($_POST['nome'])        ? $_POST['nome']        : ''),
        'categoria'   => trim(isset($_POST['categoria'])   ? $_POST['categoria']   : ''),
        'descrizione' => trim(isset($_POST['descrizione']) ? $_POST['descrizione'] : ''),
        'indirizzo'   => trim(isset($_POST['indirizzo'])   ? $_POST['indirizzo']   : ''),
        'lat'         => trim(isset($_POST['lat'])         ? $_POST['lat']         : ''),
        'lng'         => trim(isset($_POST['lng'])         ? $_POST['lng']         : ''),
        'telefono'    => trim(isset($_POST['telefono'])    ? $_POST['telefono']    : ''),
        'sito_web'    => trim(isset($_POST['sito_web'])    ? $_POST['sito_web']    : ''),
        'orario'      => trim(isset($_POST['orario'])      ? $_POST['orario']      : ''),
        'foto'        => fotoFinale($imgUrl, isset($_POST['foto_url']) ? $_POST['foto_url'] : '', getOldFoto($items, $id)),
        'evidenza'    => isset($_POST['evidenza']) ? true : false,
    );
    upsertItem($items, $id, $record);
    saveData('servizi', $items);
    echo json_encode(array('ok'=>true));
}

elseif ($action === 'servizi_delete') {
    if (!hasPermission('servizi')) { echo json_encode(array('error'=>'Permesso negato')); exit; }
    $id    = isset($_POST['id']) ? $_POST['id'] : '';
    $items = array_values(array_filter(loadData('servizi'), function($x) use ($id) { return $x['id'] !== $id; }));
    saveData('servizi', $items);
    echo json_encode(array('ok'=>true));
}

elseif ($action === 'servizi_evidenza') {
    if (!hasPermission('servizi')) { echo json_encode(array('error'=>'Permesso negato')); exit; }
    $id    = isset($_POST['id']) ? $_POST['id'] : '';
    $items = loadData('servizi');
    foreach ($items as &$n) {
        if ($n['id'] === $id) $n['evidenza'] = !(isset($n['evidenza']) ? $n['evidenza'] : false);
    }
    saveData('servizi', $items);
    echo json_encode(array('ok'=>true));
}

// =============================================
// LOCALI
// =============================================
elseif ($action === 'locali_save') {
    if (!hasPermission('locali')) { echo json_encode(array('error'=>'Permesso negato')); exit; }
    $items  = loadData('locali');
    $id     = isset($_POST['id']) ? $_POST['id'] : '';
    $imgUrl = (!empty($_FILES['foto']['name'])) ? handleUpload('foto', 'locali') : null;

    // Gestione galleria (max 4 foto)
    $oldItem = null;
    if ($id) {
        $idx = array_search($id, array_column($items, 'id'));
        if ($idx !== false) $oldItem = $items[$idx];
    }
    $oldGallery = ($oldItem && isset($oldItem['gallery'])) ? $oldItem['gallery'] : [];
    $gallery = [];
    for ($gi = 1; $gi <= 4; $gi++) {
        $gUrl = null;
        if (!empty($_FILES['gallery_file_'.$gi]['name'])) {
            $gUrl = handleUpload('gallery_file_'.$gi, 'locali');
        }
        if ($gUrl) {
            $gallery[] = $gUrl;
        } elseif (!empty($_POST['gallery_url_'.$gi])) {
            $gallery[] = trim($_POST['gallery_url_'.$gi]);
        } elseif (isset($oldGallery[$gi-1]) && $oldGallery[$gi-1]) {
            // mantieni vecchia foto solo se il campo url non è stato svuotato
            // (se gallery_url_X è presente ma vuoto, lo slot è stato pulito)
            if (isset($_POST['gallery_url_'.$gi])) {
                // campo presente ma vuoto → slot svuotato
            } else {
                $gallery[] = $oldGallery[$gi-1];
            }
        }
    }
    $gallery = array_values(array_filter($gallery));

    // Preserva slug esistente o genera dal nome
    $oldSlug = '';
    if ($id) {
        $oidx = array_search($id, array_column($items, 'id'));
        if ($oidx !== false && !empty($items[$oidx]['slug'])) $oldSlug = $items[$oidx]['slug'];
    }
    $nomeTrim = trim(isset($_POST['nome']) ? $_POST['nome'] : '');
    $recordSlug = $oldSlug ?: slugify($nomeTrim);

    $record = array(
        'id'          => $id ? $id : generateId(),
        'slug'        => $recordSlug,
        'nome'        => $nomeTrim,
        'tipo'        => trim(isset($_POST['tipo'])        ? $_POST['tipo']        : ''),
        'descrizione' => trim(isset($_POST['descrizione']) ? $_POST['descrizione'] : ''),
        'indirizzo'   => trim(isset($_POST['indirizzo'])   ? $_POST['indirizzo']   : ''),
        'lat'         => trim(isset($_POST['lat'])         ? $_POST['lat']         : ''),
        'lng'         => trim(isset($_POST['lng'])         ? $_POST['lng']         : ''),
        'telefono'    => trim(isset($_POST['telefono'])    ? $_POST['telefono']    : ''),
        'sito_web'    => trim(isset($_POST['sito_web'])    ? $_POST['sito_web']    : ''),
        'orario'      => trim(isset($_POST['orario'])      ? $_POST['orario']      : ''),
        'orario_nota' => trim(isset($_POST['orario_nota']) ? $_POST['orario_nota'] : ''),
        'orari_strutturati' => (function() { $raw = isset($_POST['orari_strutturati']) ? $_POST['orari_strutturati'] : ''; if (!$raw) return null; $d = json_decode($raw, true); return is_array($d) ? $d : null; })(),
        'foto'        => fotoFinale($imgUrl, isset($_POST['foto_url']) ? $_POST['foto_url'] : '', getOldFoto($items, $id)),
        'gallery'     => $gallery,
        'evidenza'    => isset($_POST['evidenza']) ? true : false,
    );
    upsertItem($items, $id, $record);
    saveData('locali', $items);
    echo json_encode(array('ok'=>true));
}

elseif ($action === 'locali_delete') {
    if (!hasPermission('locali')) { echo json_encode(array('error'=>'Permesso negato')); exit; }
    $id    = isset($_POST['id']) ? $_POST['id'] : '';
    $items = array_values(array_filter(loadData('locali'), function($x) use ($id) { return $x['id'] !== $id; }));
    saveData('locali', $items);
    echo json_encode(array('ok'=>true));
}

elseif ($action === 'locali_evidenza') {
    if (!hasPermission('locali')) { echo json_encode(array('error'=>'Permesso negato')); exit; }
    $id    = isset($_POST['id']) ? $_POST['id'] : '';
    $items = loadData('locali');
    foreach ($items as &$n) {
        if ($n['id'] === $id) $n['evidenza'] = !(isset($n['evidenza']) ? $n['evidenza'] : false);
    }
    saveData('locali', $items);
    echo json_encode(array('ok'=>true));
}

// =============================================
// SPONSOR
// =============================================
elseif ($action === 'sponsor_save') {
    if (!hasPermission('sponsor')) { echo json_encode(array('error'=>'Permesso negato')); exit; }
    $items  = loadData('sponsor');
    $id     = isset($_POST['id']) ? $_POST['id'] : '';
    $imgUrl = (!empty($_FILES['foto']['name'])) ? handleUpload('foto', 'sponsor') : null;
    $record = array(
        'id'          => $id ? $id : generateId(),
        'nome'        => trim(isset($_POST['nome'])        ? $_POST['nome']        : ''),
        'livello'     => trim(isset($_POST['livello'])     ? $_POST['livello']     : 'Bronze'),
        'descrizione' => trim(isset($_POST['descrizione']) ? $_POST['descrizione'] : ''),
        'indirizzo'   => trim(isset($_POST['indirizzo'])   ? $_POST['indirizzo']   : ''),
        'sito_web'    => trim(isset($_POST['sito_web'])    ? $_POST['sito_web']    : ''),
        'telefono'    => trim(isset($_POST['telefono'])    ? $_POST['telefono']    : ''),
        'orario'      => trim(isset($_POST['orario'])      ? $_POST['orario']      : ''),
        'foto'        => fotoFinale($imgUrl, isset($_POST['foto_url']) ? $_POST['foto_url'] : '', getOldFoto($items, $id)),
        'evidenza'    => isset($_POST['evidenza']) ? true : false,
    );
    upsertItem($items, $id, $record);
    saveData('sponsor', $items);
    echo json_encode(array('ok'=>true));
}

elseif ($action === 'sponsor_delete') {
    if (!hasPermission('sponsor')) { echo json_encode(array('error'=>'Permesso negato')); exit; }
    $id    = isset($_POST['id']) ? $_POST['id'] : '';
    $items = array_values(array_filter(loadData('sponsor'), function($x) use ($id) { return $x['id'] !== $id; }));
    saveData('sponsor', $items);
    echo json_encode(array('ok'=>true));
}

// =============================================
// SETTINGS
// =============================================
elseif ($action === 'settings_save') {
    requireAdmin();
    $settings = loadData('settings');
    if (!is_array($settings)) $settings = array();
    $fields = array('nome_sito','tagline','email','facebook','instagram','colore_primario','colore_accent',
                    'meta_description','analytics_id','citta_soprannome','citta_descrizione','citta_storia');
    foreach ($fields as $f) {
        if (isset($_POST[$f])) $settings[$f] = trim($_POST[$f]);
    }
    saveData('settings', $settings);
    echo json_encode(array('ok'=>true));
}

elseif ($action === 'settings_visibility_save') {
    requireAdmin();
    $settings = loadData('settings');
    if (!is_array($settings)) $settings = array();
    $raw = isset($_POST['sezioni_visibili']) ? $_POST['sezioni_visibili'] : '{}';
    $sv  = json_decode($raw, true);
    if (!is_array($sv)) $sv = array();
    // Sanifica: solo chiavi permesse, solo bool
    $allowed = array('notizie','territorio','eventi','locali','servizi','sponsor','cta');
    $clean   = array();
    foreach ($allowed as $k) {
        $clean[$k] = isset($sv[$k]) ? (bool)$sv[$k] : true;
    }
    $settings['sezioni_visibili'] = $clean;
    saveData('settings', $settings);
    echo json_encode(array('ok'=>true));
}

// =============================================
// PAGINE
// =============================================
elseif ($action === 'chiSiamo_save') {
    requireAdmin();
    // Leggi dati esistenti per preservare campi non inviati (es. immagine già salvata)
    $existing = loadData('chiSiamo');
    if (!is_array($existing) || isset($existing[0])) $existing = array(); // reset se array vuoto []
    $data   = $existing;
    $fields = array('titolo','sottotitolo','immagine','storia_titolo','storia_p1','storia_p2',
                    'val1_titolo','val1_testo','val2_titolo','val2_testo','val3_titolo','val3_testo',
                    'collabora_titolo','collabora_testo');
    foreach ($fields as $f) { if (isset($_POST[$f])) $data[$f] = trim($_POST[$f]); }
    // Salva sempre come oggetto associativo (non array) per garantire JSON object {}
    $path = DATA_DIR . 'chiSiamo.json';
    file_put_contents($path, json_encode((object)$data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo json_encode(array('ok'=>true));
}

elseif ($action === 'chiSiamo_delete_foto') {
    requireAdmin();
    $existing = loadData('chiSiamo');
    if (!is_array($existing) || isset($existing[0])) $existing = array();
    // Elimina il file fisico se è un upload locale
    if (!empty($existing['immagine'])) {
        $rel = str_replace('/admin/uploads/', '', $existing['immagine']);
        $physPath = UPLOAD_DIR . $rel;
        if (file_exists($physPath) && strpos(realpath($physPath), realpath(UPLOAD_DIR)) === 0) {
            @unlink($physPath);
        }
    }
    $existing['immagine'] = '';
    $path = DATA_DIR . 'chiSiamo.json';
    file_put_contents($path, json_encode((object)$existing, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    echo json_encode(array('ok'=>true));
}

elseif ($action === 'headerHero_save') {
    requireAdmin();
    $data   = array();
    $fields = array('eyebrow','titolo1','titolo2','sottotitolo',
                    'stat1_num','stat1_label','stat2_num','stat2_label','stat3_num','stat3_label',
                    'btn1_testo','btn1_url','btn2_testo','btn2_url');
    foreach ($fields as $f) { if (isset($_POST[$f])) $data[$f] = trim($_POST[$f]); }
    saveData('headerHero', $data);
    echo json_encode(array('ok'=>true));
}

// =============================================
// COLLABORATORI
// =============================================
elseif ($action === 'collaboratori_save') {
    requireAdmin();
    $users  = loadUsers();
    $id     = trim(isset($_POST['username']) ? $_POST['username'] : '');
    if (!$id) { echo json_encode(array('error'=>'Username obbligatorio')); exit; }
    $isNew    = !isset($users[$id]);
    $password = trim(isset($_POST['password']) ? $_POST['password'] : '');
    $perms    = isset($_POST['permissions']) ? $_POST['permissions'] : array();
    if (!is_array($perms)) $perms = array($perms);
    $oldPwd   = isset($users[$id]['password']) ? $users[$id]['password'] : password_hash('changeme123', PASSWORD_DEFAULT);
    $record   = array(
        'username'    => $id,
        'password'    => ($isNew && $password) ? password_hash($password, PASSWORD_DEFAULT) : $oldPwd,
        'role'        => trim(isset($_POST['role'])  ? $_POST['role']  : 'collaboratore'),
        'name'        => trim(isset($_POST['name'])  ? $_POST['name']  : $id),
        'email'       => trim(isset($_POST['email']) ? $_POST['email'] : ''),
        'permissions' => $perms,
    );
    if (!$isNew && $password) {
        $record['password'] = password_hash($password, PASSWORD_DEFAULT);
    }
    $users[$id] = $record;
    saveUsers($users);
    echo json_encode(array('ok'=>true));
}

elseif ($action === 'collaboratori_delete') {
    requireAdmin();
    $id          = isset($_POST['username']) ? $_POST['username'] : '';
    $currentUser = getCurrentUser();
    if ($id === $currentUser['username']) { echo json_encode(array('error'=>'Non puoi eliminare te stesso')); exit; }
    $users = loadUsers();
    unset($users[$id]);
    saveUsers($users);
    echo json_encode(array('ok'=>true));
}

// =============================================
// MEDIA
// =============================================
elseif ($action === 'upload_file') {
    requireAdmin();
    $allowed_subdirs = array('chisiamo', 'media', 'notizie', 'eventi', 'sponsor');
    $subdir = isset($_POST['subdir']) ? trim($_POST['subdir']) : 'media';
    if (!in_array($subdir, $allowed_subdirs)) $subdir = 'media';

    $targetDir = UPLOAD_DIR . $subdir . '/';

    // Crea cartella se non esiste e imposta permessi corretti
    if (!is_dir($targetDir)) {
        @mkdir($targetDir, 0775, true);
    }
    // Forza permessi scrivibili
    if (is_dir($targetDir)) {
        @chmod($targetDir, 0775);
    }

    // Diagnostica dettagliata
    if (!is_dir($targetDir)) {
        echo json_encode(array('error' => 'Cartella non creabile: ' . $targetDir . ' — vai sul server e crea admin/uploads/chisiamo/ con chmod 775'));
        exit;
    }
    if (!is_writable($targetDir)) {
        echo json_encode(array('error' => 'Cartella non scrivibile: ' . $targetDir . ' — esegui: chmod 775 admin/uploads/chisiamo/'));
        exit;
    }

    if (!isset($_FILES['file']) || empty($_FILES['file']['name'])) {
        echo json_encode(array('error' => 'Nessun file ricevuto dal browser'));
        exit;
    }
    if ($_FILES['file']['error'] !== UPLOAD_ERR_OK) {
        $phpErrors = array(
            1 => 'File supera upload_max_filesize in php.ini',
            2 => 'File supera MAX_FILE_SIZE del form',
            3 => 'Upload parziale — riprova',
            4 => 'Nessun file selezionato',
            6 => 'Cartella temporanea PHP mancante',
            7 => 'PHP non riesce a scrivere su disco',
        );
        $code = $_FILES['file']['error'];
        echo json_encode(array('error' => isset($phpErrors[$code]) ? $phpErrors[$code] : 'Errore PHP upload #' . $code));
        exit;
    }

    $url = handleUpload('file', $subdir);
    if ($url) {
        echo json_encode(array('ok' => true, 'url' => $url));
        exit;
    }
    // handleUpload ha fallito — diagnostica
    $file = $_FILES['file'];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    if (!in_array($ext, ['jpg','jpeg','png','webp','gif'])) {
        echo json_encode(array('error' => 'Formato non supportato: .' . $ext . ' — usa jpg, png, webp'));
    } elseif ($file['size'] > 5 * 1024 * 1024) {
        echo json_encode(array('error' => 'File troppo grande (' . round($file['size']/1048576,1) . ' MB) — max 5 MB'));
    } else {
        echo json_encode(array('error' => 'move_uploaded_file fallito — controlla permessi di ' . $targetDir));
    }
}

elseif ($action === 'media_upload') {
    requireAdmin();
    if (!empty($_FILES['file']['name'])) {
        $url = handleUpload('file', 'media');
        if ($url) { echo json_encode(array('ok'=>true, 'url'=>$url)); exit; }
    }
    echo json_encode(array('error'=>'Upload fallito'));
}

elseif ($action === 'media_list') {
    $dir   = UPLOAD_DIR . 'media/';
    $files = array();
    if (is_dir($dir)) {
        foreach (glob($dir . '*.{jpg,jpeg,png,webp,gif}', GLOB_BRACE) as $f) {
            $files[] = array('url' => UPLOAD_URL . 'media/' . basename($f), 'name' => basename($f), 'size' => filesize($f));
        }
    }
    echo json_encode(array('ok'=>true, 'files'=>$files));
}

elseif ($action === 'media_delete') {
    requireAdmin();
    $filename = basename(isset($_POST['filename']) ? $_POST['filename'] : '');
    if ($filename) {
        $path = UPLOAD_DIR . 'media/' . $filename;
        if (file_exists($path)) unlink($path);
    }
    echo json_encode(array('ok'=>true));
}

elseif ($action === 'foto_delete') {
    requireLogin(); // Verifica autenticazione
    // Elimina una foto caricata da qualsiasi sottocartella uploads/
    $foto_url = isset($_POST['foto_url']) ? $_POST['foto_url'] : '';
    // Accetta sia URL relativo (/admin/uploads/sponsor/xxx.jpg) che solo il path
    $foto_url = preg_replace('#^https?://[^/]+#', '', $foto_url);
    $foto_url = ltrim($foto_url, '/');
    // Costruisce path assoluto sicuro
    $rel = str_replace('admin/uploads/', '', $foto_url);
    $rel = ltrim($rel, '/');
    $path = realpath(UPLOAD_DIR . $rel);
    $base = realpath(UPLOAD_DIR);
    $deleted = false;
    if ($path && $base && strpos($path, $base) === 0 && file_exists($path)) {
        unlink($path);
        $deleted = true;
    }
    echo json_encode(array('ok'=>true, 'deleted'=>$deleted));
}

// =============================================
// CHANGE PASSWORD
// =============================================
elseif ($action === 'change_password') {
    requireLogin();
    $currentUser = getCurrentUser();
    $users       = loadUsers();
    $user        = isset($users[$currentUser['username']]) ? $users[$currentUser['username']] : null;
    if (!$user) { echo json_encode(array('error'=>'Utente non trovato')); exit; }
    $old     = isset($_POST['old_password'])     ? $_POST['old_password']     : '';
    $new     = isset($_POST['new_password'])     ? $_POST['new_password']     : '';
    $confirm = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : '';
    if (!password_verify($old, $user['password'])) { echo json_encode(array('error'=>'Password attuale non corretta')); exit; }
    if (strlen($new) < 8) { echo json_encode(array('error'=>'La nuova password deve avere almeno 8 caratteri')); exit; }
    if ($new !== $confirm) { echo json_encode(array('error'=>'Le password non coincidono')); exit; }
    $users[$currentUser['username']]['password'] = password_hash($new, PASSWORD_DEFAULT);
    saveUsers($users);
    $_SESSION['ta_user']['password'] = $users[$currentUser['username']]['password'];
    echo json_encode(array('ok'=>true));
}

// =============================================
// GET ITEM
// =============================================
elseif ($action === 'get_item') {
    requireLogin(); // Solo utenti autenticati possono leggere dati
    $section = isset($_GET['section']) ? $_GET['section'] : '';
    $id      = isset($_GET['id'])      ? $_GET['id']      : '';
    $items   = loadData($section);
    $idx     = array_search($id, array_column($items, 'id'));
    if ($idx !== false) echo json_encode(array('ok'=>true, 'item'=>$items[$idx]));
    else echo json_encode(array('error'=>'Non trovato'));
}

// =============================================
// IMMAGINI HOMEPAGE
// =============================================
elseif ($action === 'immagini_save') {
    requireAdmin();
    $tipo     = isset($_POST['tipo']) ? $_POST['tipo'] : '';
    $data     = json_decode(isset($_POST['data']) ? $_POST['data'] : 'null', true);
    $settings = loadData('settings');
    if (!is_array($settings)) $settings = array();
    if ($tipo === 'hero') {
        $settings['hero_images'] = is_array($data) ? array_slice($data, 0, 3) : array('','','');
        saveData('settings', $settings);
        echo json_encode(array('ok'=>true));
    } elseif ($tipo === 'citta') {
        $settings['citta_immagine'] = (string)$data;
        saveData('settings', $settings);
        echo json_encode(array('ok'=>true));
    } elseif ($tipo === 'logo') {
        $settings['logo_url'] = (string)$data;
        saveData('settings', $settings);
        echo json_encode(array('ok'=>true));
    } else {
        echo json_encode(array('ok'=>false, 'error'=>'Tipo immagine non riconosciuto'));
    }
}

// =============================================
// LIVE FEED RSS — proxy server-side
// =============================================
elseif ($action === 'live_feed') {
    if (!isLoggedIn()) { echo json_encode(array('error' => 'Non autenticato')); exit; }
    if (!function_exists('curl_init')) {
        echo json_encode(array('error' => 'cURL non disponibile sul server. Abilitalo in php.ini oppure contatta il tuo hosting.'));
        exit;
    }

    define('LIVE_MIN_YEAR', 2026); // ignora notizie precedenti a questo anno

    // ── Feed dedicati ad Apricena (tag/categoria specifica — nessun filtro testo) ──
    $feeds_diretti = array(
        array('name' => 'La Gazzetta di Apricena',            'url' => 'https://www.lagazzettadiapricena.it/feed/'),
        array('name' => "l'Immediato – Apricena",             'url' => 'https://www.immediato.net/tag/apricena/feed/'),
        array('name' => 'Noi Notizie – Apricena',             'url' => 'https://www.noinotizie.it/tag/apricena/feed/'),
        array('name' => 'Puglia Press – Apricena',            'url' => 'https://www.pugliapress.org/tag/apricena/feed/'),
        array('name' => 'Foggia Repubblica – Apricena',       'url' => 'https://www.foggiarepubblica.it/tag/apricena/feed/'),
        array('name' => 'Vognews – Apricena',                 'url' => 'https://www.vognews.it/tag/apricena/feed/'),
        array('name' => 'Daunia News – Apricena',             'url' => 'https://www.daunianews.it/tag/apricena/feed/'),
        array('name' => 'Foggia Today – Apricena',            'url' => 'https://www.foggiatoday.it/rss/notizie/apricena.xml'),
        array('name' => 'Stato Quotidiano – Apricena',        'url' => 'https://www.statoquotidiano.it/category/capitanata_01/apricena/feed/'),
        array('name' => 'Il Sipontino – Apricena',            'url' => 'https://www.ilsipontino.net/tag/apricena/feed/'),
        array('name' => 'Gargano Notizie – Apricena',         'url' => 'https://www.garganotizie.it/tag/apricena/feed/'),
        array('name' => 'Foggia Today – Capitanata',          'url' => 'https://www.foggiatoday.it/rss/notizie/capitanata.xml'),
        array('name' => 'Teleblu – Apricena',                 'url' => 'https://www.teleblu.it/tag/apricena/feed/'),
        array('name' => 'Norbaonline – Apricena',             'url' => 'https://www.norbaonline.it/tag/apricena/feed/'),
        array('name' => 'Gazzetta del Mezzogiorno – Apricena','url' => 'https://www.lagazzettadelmezzogiorno.it/tag/apricena/feed/'),
    );

    // ── Feed zona Gargano/Foggia/Puglia — filtriamo per "apricena" nel testo ──
    $feeds_zona = array(
        array('name' => 'Manfredonia News',         'url' => 'https://www.manfredonianews.it/feed/'),
        array('name' => 'Gargano News',             'url' => 'https://www.garganonews.it/feed/'),
        array('name' => 'Gargano Press',            'url' => 'https://www.garganopress.it/feed/'),
        array('name' => 'TeleRadioStudio',          'url' => 'https://www.teleradiostudio.it/feed/'),
        array('name' => 'Otto Channel',             'url' => 'https://www.otto.tv/feed/'),
        array('name' => 'Telesveva',                'url' => 'https://www.telesveva.it/feed/'),
        array('name' => 'Antenna Sud',              'url' => 'https://www.antennasud.com/feed/'),
        array('name' => 'Daunia News',              'url' => 'https://www.daunianews.it/feed/'),
        array('name' => 'Vognews',                  'url' => 'https://www.vognews.it/feed/'),
        array('name' => 'Foggia Repubblica',        'url' => 'https://www.foggiarepubblica.it/feed/'),
        array('name' => 'Foggia Today – Gargano',   'url' => 'https://www.foggiatoday.it/rss/notizie/gargano.xml'),
        array('name' => 'Il Sipontino',             'url' => 'https://www.ilsipontino.net/feed/'),
        array('name' => 'Immediato.net',            'url' => 'https://www.immediato.net/feed/'),
        array('name' => 'FoggiaReport',             'url' => 'https://www.foggiareport.it/feed/'),
        array('name' => 'PugliaLive',              'url' => 'https://www.puglialive.net/feed/'),
        array('name' => 'Puglia24',                 'url' => 'https://www.puglia24news.it/feed/'),
        array('name' => 'Corriere del Mezzogiorno', 'url' => 'https://corrieredelmezzogiorno.corriere.it/rss/puglia.xml'),
        array('name' => 'Quotidiano di Puglia',     'url' => 'https://www.quotidianodipuglia.it/rss/puglia.xml'),
    );

    // ── Helper cURL (funziona anche con allow_url_fopen disabilitato) ──
    function curlGet($url, $timeout = 10) {
        if (!function_exists('curl_init')) return false;
        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL            => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_TIMEOUT        => $timeout,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_MAXREDIRS      => 5,
            CURLOPT_SSL_VERIFYPEER => false,
            CURLOPT_ENCODING       => '',
            CURLOPT_HTTPHEADER     => array(
                'User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Safari/537.36',
                'Accept: text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                'Accept-Language: it-IT,it;q=0.9,en-US;q=0.8,en;q=0.7',
                'Accept-Encoding: gzip, deflate, br',
                'Cache-Control: no-cache',
                'Pragma: no-cache',
                'Referer: https://www.google.it/',
                'Connection: keep-alive',
            ),
        ));
        $body = curl_exec($ch);
        $code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        curl_close($ch);
        return ($body && $code === 200) ? $body : false;
    }

    // ── Estrai la migliore immagine dall'item RSS ──
    function extractRssImage($item, $rawBlock = '') {
        $img = '';
        // 1. enclosure standard RSS (solo se URL immagine)
        if (!$img && isset($item->enclosure) && !empty((string)$item->enclosure['url'])) {
            $u = (string)$item->enclosure['url'];
            if (preg_match('/\.(jpe?g|png|webp|gif)(\?.*)?$/i', $u)) $img = $u;
        }
        // 2. media_content / media_thumbnail (namespace sostituito)
        if (!$img && isset($item->media_content) && !empty((string)$item->media_content['url']))
            $img = (string)$item->media_content['url'];
        if (!$img && isset($item->media_thumbnail) && !empty((string)$item->media_thumbnail['url']))
            $img = (string)$item->media_thumbnail['url'];
        // 3. Prima <img> nel corpo HTML
        if (!$img) {
            $html = (string)(isset($item->content_encoded) ? $item->content_encoded : (isset($item->description) ? $item->description : ''));
            if (preg_match('/<img[^>]+src=["\']([^"\']+)["\']/', $html, $m)) $img = $m[1];
        }
        // 4. media: / itunes: nel blocco XML grezzo (namespace non parsati)
        if (!$img && $rawBlock) {
            if (preg_match('/media:(?:content|thumbnail)[^>]+url=["\']([^"\']+)["\']/', $rawBlock, $m)) $img = $m[1];
            if (!$img && preg_match('/itunes:image[^>]+href=["\']([^"\']+)["\']/', $rawBlock, $m))      $img = $m[1];
        }
        // Scarta pixel di tracciamento e URL troppo corti
        if ($img && (strpos($img,'1x1')!==false || strpos($img,'pixel')!==false
                  || strpos($img,'spacer')!==false || strlen($img)<15)) $img = '';
        return $img;
    }

    // ── Fetch + parse di un feed RSS ──
    function fetchFeed($url) {
        $rawXml = curlGet($url, 10);
        if (!$rawXml) return array();
        // Sostituisci namespace problematici per SimpleXML
        $xmlClean = preg_replace('/(<\/?)(\w+):(\w)/', '$1$2_$3', $rawXml);
        libxml_use_internal_errors(true);
        $feed = @simplexml_load_string($xmlClean);
        libxml_clear_errors();
        if (!$feed) return array();
        $channel = isset($feed->channel) ? $feed->channel : $feed;
        // Estrai blocchi <item> grezzi per cercare namespace non parsati
        preg_match_all('/<item[^>]*>(.*?)<\/item>/s', $rawXml, $rawItems);
        $items = array();
        $i = 0;
        foreach ($channel->item as $item) {
            $rawBlock = isset($rawItems[1][$i]) ? $rawItems[1][$i] : '';
            $i++;
            $img     = extractRssImage($item, $rawBlock);
            $pubDate = (string)$item->pubDate;
            $ts      = $pubDate ? strtotime($pubDate) : time();
            if (!$ts || $ts <= 0) $ts = time();
            // ── FILTRO ANNO: salta notizie precedenti al 2026 ──
            if ((int)date('Y', $ts) < LIVE_MIN_YEAR) continue;
            $desc_raw   = (string)(isset($item->content_encoded) ? $item->content_encoded : (isset($item->description) ? $item->description : ''));
            $desc_plain = html_entity_decode(preg_replace('/\s+/', ' ', strip_tags($desc_raw)), ENT_QUOTES, 'UTF-8');
            $desc_plain = trim(mb_substr($desc_plain, 0, 350));
            $items[] = array(
                'titolo'   => trim(strip_tags(html_entity_decode((string)$item->title, ENT_QUOTES, 'UTF-8'))),
                'immagine' => $img,
                'abstract' => $desc_plain,
                'fonteUrl' => trim((string)$item->link),
                'data'     => date('Y-m-d', $ts),
                'ts'       => $ts,
            );
        }
        return $items;
    }

    // ── Recupera og:image / twitter:image dalla pagina HTML ──
    function fetchOgImage($url) {
        if (empty($url) || $url === '#') return '';
        $html = curlGet($url, 7);
        if (!$html) return '';
        $head = substr($html, 0, 25000);
        if (preg_match('/<meta[^>]+property=["\']og:image["\'][^>]+content=["\']([^"\']+)["\']/', $head, $m)) return $m[1];
        if (preg_match('/<meta[^>]+content=["\']([^"\']+)["\'][^>]+property=["\']og:image["\']/', $head, $m)) return $m[1];
        if (preg_match('/<meta[^>]+name=["\']twitter:image["\'][^>]+content=["\']([^"\']+)["\']/', $head, $m)) return $m[1];
        if (preg_match('/<meta[^>]+content=["\']([^"\']+)["\'][^>]+name=["\']twitter:image["\']/', $head, $m)) return $m[1];
        if (preg_match('/"image"\s*:\s*"([^"]+)"/', $head, $m)) return $m[1];
        return '';
    }

    // ── Raccolta ──
    $all        = array();
    $seenTitles = array();

    foreach ($feeds_diretti as $feed) {
        $items = fetchFeed($feed['url']);
        foreach ($items as $it) {
            if (empty($it['titolo']) || mb_strlen($it['titolo']) < 5) continue;
            $key = mb_substr(mb_strtolower(preg_replace('/\s+/', '', $it['titolo'])), 0, 40);
            if (isset($seenTitles[$key])) continue;
            $seenTitles[$key] = true;
            $it['fonte'] = $feed['name'];
            $all[] = $it;
        }
    }

    foreach ($feeds_zona as $feed) {
        $items = fetchFeed($feed['url']);
        foreach ($items as $it) {
            if (empty($it['titolo']) || mb_strlen($it['titolo']) < 5) continue;
            $fullText = mb_strtolower($it['titolo'] . ' ' . $it['abstract']);
            if (strpos($fullText, 'apricena') === false && strpos($fullText, 'la prucin') === false) continue;
            $key = mb_substr(mb_strtolower(preg_replace('/\s+/', '', $it['titolo'])), 0, 40);
            if (isset($seenTitles[$key])) continue;
            $seenTitles[$key] = true;
            $it['fonte'] = $feed['name'];
            $all[] = $it;
        }
    }

    // ── Ordina per data decrescente ──
    usort($all, function($a, $b) { return $b['ts'] - $a['ts']; });
    foreach ($all as &$it) unset($it['ts']);
    unset($it);

    // ── Per notizie senza immagine: recupera og:image dalla pagina (max 40) ──
    $fetchCount = 0;
    foreach ($all as &$it) {
        if (empty($it['immagine']) && !empty($it['fonteUrl']) && $it['fonteUrl'] !== '#') {
            if ($fetchCount >= 40) break;
            $ogImg = fetchOgImage($it['fonteUrl']);
            if ($ogImg) $it['immagine'] = $ogImg;
            $fetchCount++;
        }
    }
    unset($it);

    echo json_encode(array('ok' => true, 'items' => array_values($all)));
}

else {
    echo json_encode(array('error' => 'Azione non riconosciuta: ' . $action));
}
