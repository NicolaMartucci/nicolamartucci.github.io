<?php
// ============================================================
// SAVE — crea o aggiorna una news. Usato dal pannello /admin.
// Campi attesi (multipart/form-data):
//   id               (vuoto = nuova news, valorizzato = modifica)
//   titolo           (obbligatorio)
//   testo            (obbligatorio)
//   stato            "bozza" | "pubblicato"
//   immagine         (file, opzionale: se assente in modifica si mantiene quella esistente)
//   allegato         (file, opzionale: documento scaricabile, es. PDF/Word)
//   rimuovi_allegato ("1" per rimuovere l'allegato attuale senza caricarne uno nuovo)
// ============================================================
require __DIR__ . '/auth.php';
fpa_require_auth_api();
require __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonFail('Metodo non consentito', 405);
}

$id     = trim($_POST['id'] ?? '');
$titolo = trim($_POST['titolo'] ?? '');
$testo  = trim($_POST['testo'] ?? '');
$stato  = ($_POST['stato'] ?? 'bozza') === 'pubblicato' ? 'pubblicato' : 'bozza';
$rimuoviAllegato = ($_POST['rimuovi_allegato'] ?? '') === '1';

if ($titolo === '' || $testo === '') {
    jsonFail('Titolo e testo sono obbligatori.');
}

$news = readNews();
$isNew = $id === '';
$existing = null;
if (!$isNew) {
    foreach ($news as $n) {
        if ($n['id'] === $id) { $existing = $n; break; }
    }
    if (!$existing) { $isNew = true; } // id non trovato: crea comunque una nuova news
}

$immaginePath  = $existing['immagine'] ?? '';
$allegatoPath  = $existing['allegato'] ?? '';
$allegatoNome  = $existing['allegato_nome'] ?? '';

// Upload immagine (opzionale)
if (!empty($_FILES['immagine']['name']) && $_FILES['immagine']['error'] === UPLOAD_ERR_OK) {
    $tmp = $_FILES['immagine']['tmp_name'];
    $size = $_FILES['immagine']['size'];
    $origName = $_FILES['immagine']['name'];
    $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','webp','gif'];

    if ($size > 5 * 1024 * 1024) {
        jsonFail('Immagine troppo grande: massimo 5MB.');
    }
    if (!in_array($ext, $allowed, true)) {
        jsonFail('Formato immagine non supportato. Usa JPG, PNG, WEBP o GIF.');
    }
    if (!is_dir(UPLOAD_DIR)) { mkdir(UPLOAD_DIR, 0755, true); }

    $safeName = 'news-' . time() . '-' . substr(md5(uniqid('', true)), 0, 8) . '.' . $ext;
    $dest = UPLOAD_DIR . $safeName;
    if (!move_uploaded_file($tmp, $dest)) {
        jsonFail('Errore durante il salvataggio del file immagine.');
    }
    $immaginePath = UPLOAD_URL . $safeName;
}

// Upload documento allegato (opzionale): PDF, Word, ODT, RTF, TXT
if (!empty($_FILES['allegato']['name']) && $_FILES['allegato']['error'] === UPLOAD_ERR_OK) {
    $tmp = $_FILES['allegato']['tmp_name'];
    $size = $_FILES['allegato']['size'];
    $origName = $_FILES['allegato']['name'];
    $ext = strtolower(pathinfo($origName, PATHINFO_EXTENSION));
    $allowed = ['pdf','doc','docx','odt','rtf','txt'];

    if ($size > 15 * 1024 * 1024) {
        jsonFail('Documento troppo grande: massimo 15MB.');
    }
    if (!in_array($ext, $allowed, true)) {
        jsonFail('Formato documento non supportato. Usa PDF, DOC, DOCX, ODT, RTF o TXT.');
    }
    if (!is_dir(ALLEGATI_DIR)) { mkdir(ALLEGATI_DIR, 0755, true); }

    // rimuove l'eventuale allegato precedente, essendo sostituito da uno nuovo
    if ($allegatoPath && strpos($allegatoPath, ALLEGATI_URL) === 0) {
        $vecchio = __DIR__ . '/../../' . $allegatoPath;
        if (is_file($vecchio)) { @unlink($vecchio); }
    }

    $safeName = 'doc-' . time() . '-' . substr(md5(uniqid('', true)), 0, 8) . '.' . $ext;
    $dest = ALLEGATI_DIR . $safeName;
    if (!move_uploaded_file($tmp, $dest)) {
        jsonFail('Errore durante il salvataggio del documento allegato.');
    }
    $allegatoPath = ALLEGATI_URL . $safeName;
    $allegatoNome = $origName;
} elseif ($rimuoviAllegato && $allegatoPath) {
    // rimozione esplicita senza caricare un sostituto
    if (strpos($allegatoPath, ALLEGATI_URL) === 0) {
        $vecchio = __DIR__ . '/../../' . $allegatoPath;
        if (is_file($vecchio)) { @unlink($vecchio); }
    }
    $allegatoPath = '';
    $allegatoNome = '';
}

if ($isNew) {
    $entry = [
        'id'            => (string) time() . rand(100, 999),
        'titolo'        => $titolo,
        'testo'         => $testo,
        'immagine'      => $immaginePath,
        'allegato'      => $allegatoPath,
        'allegato_nome' => $allegatoNome,
        'data'          => date('Y-m-d'),
        'stato'         => $stato,
    ];
    $news[] = $entry;
} else {
    foreach ($news as &$n) {
        if ($n['id'] === $id) {
            $n['titolo']        = $titolo;
            $n['testo']         = $testo;
            $n['immagine']      = $immaginePath;
            $n['allegato']      = $allegatoPath;
            $n['allegato_nome'] = $allegatoNome;
            $n['stato']         = $stato;
            // la data di prima pubblicazione resta invariata
            break;
        }
    }
    unset($n);
}

if (!writeNews($news)) {
    jsonFail('Impossibile scrivere il file delle news: controlla i permessi della cartella /data.', 500);
}

echo json_encode(['ok' => true]);
