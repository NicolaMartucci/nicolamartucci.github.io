<?php
// ============================================================
// DELETE — elimina definitivamente una news (e i file immagine/allegato associati).
// ============================================================
require __DIR__ . '/auth.php';
fpa_require_auth_api();
require __DIR__ . '/config.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    jsonFail('Metodo non consentito', 405);
}

$id = trim($_POST['id'] ?? '');
if ($id === '') { jsonFail('ID mancante.'); }

$news = readNews();
$toDelete = null;
$rest = [];
foreach ($news as $n) {
    if ($n['id'] === $id) { $toDelete = $n; } else { $rest[] = $n; }
}

if (!$toDelete) { jsonFail('News non trovata.', 404); }

if (!writeNews($rest)) {
    jsonFail('Impossibile aggiornare il file delle news.', 500);
}

// rimuove il file immagine associato, se presente e locale
if (!empty($toDelete['immagine']) && strpos($toDelete['immagine'], UPLOAD_URL) === 0) {
    $file = __DIR__ . '/../../' . $toDelete['immagine'];
    if (is_file($file)) { @unlink($file); }
}

// rimuove il documento allegato associato, se presente e locale
if (!empty($toDelete['allegato']) && strpos($toDelete['allegato'], ALLEGATI_URL) === 0) {
    $file = __DIR__ . '/../../' . $toDelete['allegato'];
    if (is_file($file)) { @unlink($file); }
}

echo json_encode(['ok' => true]);
