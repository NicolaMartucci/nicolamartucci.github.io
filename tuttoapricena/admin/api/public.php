<?php
/**
 * TuttoApricena — Public API (no login required)
 * Gestisce: form contatti → email a info@tuttoapricena.it
 */
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: https://www.tuttoapricena.it');
header('Access-Control-Allow-Methods: POST, OPTIONS');
if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') { http_response_code(200); exit; }

$action = isset($_POST['action']) ? trim($_POST['action']) : '';

if ($action === 'contatti_send') {
    $nome      = isset($_POST['nome'])      ? trim(strip_tags($_POST['nome']))      : '';
    $email     = isset($_POST['email'])     ? trim(strip_tags($_POST['email']))     : '';
    $oggetto   = isset($_POST['oggetto'])   ? trim(strip_tags($_POST['oggetto']))   : 'Contatto';
    $messaggio = isset($_POST['messaggio']) ? trim(strip_tags($_POST['messaggio'])) : '';

    if (!$nome || !$email || !$messaggio) {
        echo json_encode(array('ok'=>false,'error'=>'Campi obbligatori mancanti')); exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        echo json_encode(array('ok'=>false,'error'=>'Email non valida')); exit;
    }

    $to      = 'info@tuttoapricena.it';
    $subject = '[TuttoApricena] ' . $oggetto . ' - da ' . $nome;
    $body    = "Nuovo messaggio dal form contatti di TuttoApricena\r\n";
    $body   .= "=======================================================\r\n\r\n";
    $body   .= "Nome: " . $nome . "\r\n";
    $body   .= "Email: " . $email . "\r\n";
    $body   .= "Oggetto: " . $oggetto . "\r\n\r\n";
    $body   .= "Messaggio:\r\n" . $messaggio . "\r\n\r\n";
    $body   .= "=======================================================\r\n";
    $body   .= "Inviato il: " . date('d/m/Y H:i') . "\r\n";

    $headers  = "From: noreply@tuttoapricena.it\r\n";
    $headers .= "Reply-To: " . $email . "\r\n";
    $headers .= "MIME-Version: 1.0\r\n";
    $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

    $sent = @mail($to, $subject, $body, $headers);

    // Salva sempre un log nella cartella data
    $logFile = __DIR__ . '/../data/contatti_log.json';
    $log = array();
    if (file_exists($logFile)) {
        $log = json_decode(file_get_contents($logFile), true) ?: array();
    }
    $log[] = array(
        'data'      => date('Y-m-d H:i:s'),
        'nome'      => $nome,
        'email'     => $email,
        'oggetto'   => $oggetto,
        'messaggio' => $messaggio,
        'mail_sent' => $sent ? true : false
    );
    @file_put_contents($logFile, json_encode($log, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

    echo json_encode(array('ok'=>true));
    exit;
}

echo json_encode(array('error' => 'Azione non riconosciuta'));
