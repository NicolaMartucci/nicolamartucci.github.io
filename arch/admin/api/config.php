<?php
// ============================================================
// CONFIG — percorsi e funzioni condivise dagli endpoint del CMS News
// ============================================================
header('Content-Type: application/json; charset=utf-8');

define('NEWS_JSON', __DIR__ . '/../../data/news.json');
define('UPLOAD_DIR', __DIR__ . '/../../uploads/news/');
// Percorso salvato nel JSON: relativo alla ROOT del sito (dove vive news.php).
// Il pannello admin (in /admin/) antepone "../" da solo quando mostra le anteprime.
define('UPLOAD_URL', 'uploads/news/');

function readNews(): array {
    if (!file_exists(NEWS_JSON)) return [];
    $raw = file_get_contents(NEWS_JSON);
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

function writeNews(array $news): bool {
    // riscrittura atomica: scrive su file temporaneo poi rinomina
    $tmp = NEWS_JSON . '.tmp';
    $ok = file_put_contents($tmp, json_encode(array_values($news), JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
    if ($ok === false) return false;
    return rename($tmp, NEWS_JSON);
}

function jsonFail(string $error, int $code = 400): void {
    http_response_code($code);
    echo json_encode(['ok' => false, 'error' => $error]);
    exit;
}
