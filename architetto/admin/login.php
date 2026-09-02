<?php
require __DIR__ . '/api/auth.php';

if (fpa_is_logged_in()) {
    header('Location: index.php');
    exit;
}

$errore = '';
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $password = (string) ($_POST['password'] ?? '');
    $remember = !empty($_POST['remember']);

    $esito = fpa_login($username, $password, $remember);
    if ($esito['ok']) {
        header('Location: index.php');
        exit;
    }
    $errore = $esito['error'];
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Accesso — Gestione News · Studio FParchitetto</title>
<meta name="robots" content="noindex, nofollow">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@500;600;700&family=Barlow:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  :root{
    --c-base:#EAE6DC; --c-white:#FBFAF6; --c-ink:#1B1A17; --c-ink-soft:#55514A; --c-line:#C6BFAE;
    --c-tecnica:#2C4A63; --c-tecnica-dk:#1C3145; --c-err:#9A3B1F;
    --font-display:'Barlow Condensed', sans-serif; --font-body:'Barlow', Arial, sans-serif; --font-mono:'Barlow Condensed', sans-serif;
  }
  *,*::before,*::after{ box-sizing:border-box; }
  body{
    margin:0; min-height:100vh; background:var(--c-base); color:var(--c-ink); font-family:var(--font-body);
    display:flex; align-items:center; justify-content:center; padding:24px;
  }
  .card{
    background:var(--c-white); border:1px solid var(--c-line); border-radius:8px;
    padding:40px 36px; width:100%; max-width:380px;
  }
  .logo{ font-family:var(--font-display); font-size:1.2rem; text-transform:uppercase; letter-spacing:.04em; margin-bottom:4px; }
  .logo b{ color:var(--c-tecnica); }
  h1{ font-family:var(--font-display); font-size:1.6rem; text-transform:uppercase; margin:0 0 8px; }
  .sub{ color:var(--c-ink-soft); margin:0 0 28px; font-size:.92rem; }
  .field{ margin-bottom:18px; display:flex; flex-direction:column; gap:8px; }
  .field label{ font-family:var(--font-mono); font-size:.72rem; letter-spacing:.08em; text-transform:uppercase; color:var(--c-ink-soft); }
  .field input[type=text], .field input[type=password]{
    font-family:var(--font-body); font-size:1rem; padding:12px 14px; border:1px solid var(--c-line);
    border-radius:4px; background:#fff; color:var(--c-ink); width:100%;
  }
  .remember{ display:flex; align-items:center; gap:8px; margin:6px 0 22px; font-size:.88rem; color:var(--c-ink-soft); }
  .remember input{ accent-color:var(--c-tecnica); width:16px; height:16px; }
  button{
    width:100%; font-family:var(--font-mono); font-size:.8rem; letter-spacing:.08em; text-transform:uppercase;
    padding:14px 20px; border-radius:4px; border:1px solid var(--c-ink); background:var(--c-ink); color:#fff; cursor:pointer;
  }
  button:hover{ background:var(--c-tecnica); border-color:var(--c-tecnica); }
  .msg-err{ margin-bottom:18px; font-family:var(--font-mono); font-size:.85rem; padding:12px 14px; border-radius:4px; background:#F6E4E0; color:var(--c-err); }
  .back{ display:block; text-align:center; margin-top:22px; font-family:var(--font-mono); font-size:.75rem; color:var(--c-ink-soft); text-decoration:none; letter-spacing:.05em; }
  .back:hover{ color:var(--c-ink); }
</style>
</head>
<body>
  <div class="card">
    <div class="logo">Studio <b>FP</b>architetto</div>
    <h1>Accesso pannello</h1>
    <p class="sub">Inserisci le credenziali per gestire le news del sito.</p>

    <?php if ($errore): ?>
      <div class="msg-err"><?= htmlspecialchars($errore, ENT_QUOTES, 'UTF-8') ?></div>
    <?php endif; ?>

    <form method="post" autocomplete="off">
      <div class="field">
        <label for="username">Nome utente</label>
        <input type="text" id="username" name="username" required autocomplete="username" autofocus>
      </div>
      <div class="field">
        <label for="password">Password</label>
        <input type="password" id="password" name="password" required autocomplete="current-password">
      </div>
      <label class="remember">
        <input type="checkbox" name="remember" value="1">
        Ricorda le mie credenziali su questo dispositivo
      </label>
      <button type="submit">Accedi</button>
    </form>
    <a class="back" href="../index.html">← Torna al sito</a>
  </div>
</body>
</html>
