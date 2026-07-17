<?php
// ============================================================
// LIST — restituisce tutte le news (bozze e pubblicate), più recenti in cima.
// Usato dal pannello /admin per popolare la tabella di gestione.
// ============================================================
require __DIR__ . '/config.php';

$news = readNews();
usort($news, function($a, $b) {
    return strcmp($b['data'] ?? '', $a['data'] ?? '');
});

echo json_encode(array_values($news));
