<?php
// ============================================================
// CONFIG — percorsi e funzioni condivise dagli endpoint del CMS News
// ============================================================

// Non stampare mai warning/notice PHP grezzi nella risposta: romperebbero il
// JSON che il pannello si aspetta di leggere (causa tipica dell'errore
// "Errore di connessione al server" mostrato dal pannello quando in realtà
// il problema è un permesso di scrittura mancante sull'hosting).
ini_set('display_errors', '0');
error_reporting(E_ALL);
set_error_handler(function ($errno, $errstr) {
    error_log('Studio FParchitetto CMS - PHP warning: ' . $errstr);
    return true; // impedisce la stampa standard dell'errore nella risposta
});
register_shutdown_function(function () {
    $err = error_get_last();
    if ($err && in_array($err['type'], [E_ERROR, E_PARSE, E_CORE_ERROR, E_COMPILE_ERROR], true)) {
        if (!headers_sent()) { header('Content-Type: application/json; charset=utf-8'); }
        echo json_encode(['ok' => false, 'error' => 'Errore interno del server. Controlla la versione PHP e i permessi delle cartelle data/ e uploads/ (vedi README).']);
    }
});

header('Content-Type: application/json; charset=utf-8');

define('NEWS_JSON', __DIR__ . '/../../data/news.json');
define('UPLOAD_DIR', __DIR__ . '/../../uploads/news/');
// Percorso salvato nel JSON: relativo alla ROOT del sito (dove vive news.php).
// Il pannello admin (in /admin/) antepone "../" da solo quando mostra le anteprime.
define('UPLOAD_URL', 'uploads/news/');

function readNews() {
    if (!file_exists(NEWS_JSON)) return [];
    $raw = file_get_contents(NEWS_JSON);
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

function writeNews($news) {
    // riscrittura atomica: scrive su file temporaneo poi rinomina
    $tmp = NEWS_JSON . '.tmp';
    $ok = @file_put_contents($tmp, json_encode(array_values($news), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    if ($ok === false) return false;
    return @rename($tmp, NEWS_JSON);
}

function jsonFail($error, $code = 400) {
    http_response_code($code);
    echo json_encode(['ok' => false, 'error' => $error]);
    exit;
}
