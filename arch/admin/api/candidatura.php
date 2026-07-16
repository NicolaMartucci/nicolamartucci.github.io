<?php
// ============================================================
// CANDIDATURA — riceve il form "Lavora con noi", salva il CV e i dati
// in data/candidature.json, poi reindirizza a una pagina di conferma.
// In produzione: sostituisci/aggiungi qui l'invio email a HR con mail() o PHPMailer.
// ============================================================
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../../lavora-con-noi.html');
    exit;
}

$dataDir = __DIR__ . '/../../data/';
$cvDir   = __DIR__ . '/../../uploads/candidature/';
$jsonPath = $dataDir . 'candidature.json';

$nome      = trim($_POST['nome'] ?? '');
$email     = trim($_POST['email'] ?? '');
$reparto   = trim($_POST['reparto'] ?? '');
$messaggio = trim($_POST['messaggio'] ?? '');

$cvPath = '';
if (!empty($_FILES['cv']['name']) && $_FILES['cv']['error'] === UPLOAD_ERR_OK) {
    $ext = strtolower(pathinfo($_FILES['cv']['name'], PATHINFO_EXTENSION));
    if ($ext === 'pdf' && $_FILES['cv']['size'] <= 8 * 1024 * 1024) {
        if (!is_dir($cvDir)) { mkdir($cvDir, 0755, true); }
        $safeName = 'cv-' . time() . '-' . substr(md5(uniqid('', true)), 0, 8) . '.pdf';
        if (move_uploaded_file($_FILES['cv']['tmp_name'], $cvDir . $safeName)) {
            $cvPath = 'uploads/candidature/' . $safeName;
        }
    }
}

$candidature = [];
if (file_exists($jsonPath)) {
    $candidature = json_decode(file_get_contents($jsonPath), true) ?: [];
}
$candidature[] = [
    'id'        => (string) time(),
    'nome'      => $nome,
    'email'     => $email,
    'reparto'   => $reparto,
    'messaggio' => $messaggio,
    'cv'        => $cvPath,
    'data'      => date('Y-m-d H:i'),
];
if (!is_dir($dataDir)) { mkdir($dataDir, 0755, true); }
file_put_contents($jsonPath, json_encode($candidature, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// Pagina di conferma minimale (nessuna dipendenza da style.css necessaria)
?>
<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<title>Candidatura inviata — Studio Arké</title>
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<style>
  body{ font-family: Arial, sans-serif; background:#EAE6DC; color:#1B1A17; display:flex; align-items:center; justify-content:center; height:100vh; margin:0; text-align:center; }
  .box{ max-width:420px; padding:40px; }
  a{ color:#2C4A63; }
</style>
</head>
<body>
  <div class="box">
    <h1>Grazie, <?= htmlspecialchars($nome ?: 'candidato/a') ?>!</h1>
    <p>Abbiamo ricevuto la tua candidatura per il reparto <strong><?= htmlspecialchars($reparto ?: 'Studio Arké') ?></strong>. Ti risponderemo al più presto.</p>
    <p><a href="../../index.html">← Torna al sito</a></p>
  </div>
</body>
</html>
