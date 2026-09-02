<?php
// ============================================================
// PREVENTIVO — riceve il form della pagina preventivo.html, salva la
// richiesta in data/preventivi.json insieme all'eventuale allegato.
// In produzione: aggiungi qui l'invio email al referente commerciale.
// ============================================================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../preventivo.html');
    exit;
}

$dataDir = __DIR__ . '/../../data/';
$allegatiDir = __DIR__ . '/../../uploads/preventivi/';
$jsonPath = $dataDir . 'preventivi.json';

$nome      = trim($_POST['nome'] ?? '');
$telefono  = trim($_POST['telefono'] ?? '');
$email     = trim($_POST['email'] ?? '');
$comune    = trim($_POST['comune'] ?? '');
$tipo      = trim($_POST['tipo_intervento'] ?? '');
$reparto   = trim($_POST['reparto'] ?? '');
$messaggio = trim($_POST['messaggio'] ?? '');

$allegatoPath = '';
if (!empty($_FILES['allegato']['name']) && $_FILES['allegato']['error'] === UPLOAD_ERR_OK) {
    $ext = strtolower(pathinfo($_FILES['allegato']['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg','jpeg','png','pdf','webp'];
    if (in_array($ext, $allowed, true) && $_FILES['allegato']['size'] <= 8 * 1024 * 1024) {
        if (!is_dir($allegatiDir)) { mkdir($allegatiDir, 0755, true); }
        $safeName = 'prev-' . time() . '-' . substr(md5(uniqid('', true)), 0, 8) . '.' . $ext;
        if (move_uploaded_file($_FILES['allegato']['tmp_name'], $allegatiDir . $safeName)) {
            $allegatoPath = 'uploads/preventivi/' . $safeName;
        }
    }
}

$richieste = [];
if (file_exists($jsonPath)) {
    $richieste = json_decode(file_get_contents($jsonPath), true) ?: [];
}
$richieste[] = [
    'id'              => (string) time(),
    'nome'            => $nome,
    'telefono'        => $telefono,
    'email'           => $email,
    'comune'          => $comune,
    'tipo_intervento' => $tipo,
    'reparto'         => $reparto,
    'messaggio'       => $messaggio,
    'allegato'        => $allegatoPath,
    'data'            => date('Y-m-d H:i'),
];
if (!is_dir($dataDir)) { mkdir($dataDir, 0755, true); }
file_put_contents($jsonPath, json_encode($richieste, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
?>
<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<title>Richiesta inviata — Studio FParchitetto</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
  body{ font-family: Arial, sans-serif; background:#fff; color:#1A1A18; display:flex; align-items:center; justify-content:center; height:100vh; margin:0; text-align:center; }
  .box{ max-width:440px; padding:40px; }
  a{ color:#1A1A18; }
</style>
</head>
<body>
  <div class="box">
    <h1>Grazie, <?= htmlspecialchars($nome ?: '') ?>!</h1>
    <p>Abbiamo ricevuto la tua richiesta di preventivo<?= $comune ? ' per ' . htmlspecialchars($comune) : '' ?>. Ti ricontattiamo entro 24 ore lavorative.</p>
    <p><a href="../../index.html">← Torna al sito</a></p>
  </div>
</body>
</html>
