<?php
require __DIR__ . '/api/auth.php';
fpa_require_auth_page();

$errore = '';
$successo = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $attuale = (string) ($_POST['attuale'] ?? '');
    $nuova   = (string) ($_POST['nuova'] ?? '');
    $conferma = (string) ($_POST['conferma'] ?? '');

    $creds = fpa_credentials();

    if (!password_verify($attuale, $creds['password_hash'])) {
        $errore = 'La password attuale non è corretta.';
    } elseif (strlen($nuova) < 8) {
        $errore = 'La nuova password deve avere almeno 8 caratteri.';
    } elseif ($nuova !== $conferma) {
        $errore = 'La conferma non coincide con la nuova password.';
    } else {
        $nuovoHash = password_hash($nuova, PASSWORD_DEFAULT);
        if (fpa_update_password_hash($creds['username'], $nuovoHash)) {
            $successo = 'Password aggiornata correttamente.';
        } else {
            $errore = 'Impossibile scrivere il file delle credenziali: controlla i permessi della cartella admin/api/.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Cambia password — Gestione News · Studio FParchitetto</title>
<meta name="robots" content="noindex, nofollow">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@500;600;700&family=Barlow:wght@400;500;600&display=swap" rel="stylesheet">
<style>
  :root{
    --c-base:#EAE6DC; --c-white:#FBFAF6; --c-ink:#1B1A17; --c-ink-soft:#55514A; --c-line:#C6BFAE;
    --c-tecnica:#2C4A63; --c-ok:#2F6E4E; --c-err:#9A3B1F;
    --font-display:'Barlow Condensed', sans-serif; --font-body:'Barlow', Arial, sans-serif; --font-mono:'Barlow Condensed', sans-serif;
  }
  *,*::before,*::after{ box-sizing:border-box; }
  body{ margin:0; background:var(--c-base); color:var(--c-ink); font-family:var(--font-body); line-height:1.5; }
  header.admin-top{ background:var(--c-ink); color:#fff; padding:20px 32px; display:flex; align-items:center; justify-content:space-between; flex-wrap:wrap; gap:10px; }
  header.admin-top .logo{ font-family:var(--font-display); font-size:1.3rem; text-transform:uppercase; letter-spacing:.04em; }
  header.admin-top .logo b{ color:var(--c-tecnica); }
  header.admin-top nav a{ font-family:var(--font-mono); font-size:.75rem; color:#cfcabf; letter-spacing:.05em; text-decoration:none; margin-left:18px; }
  header.admin-top nav a:hover{ color:#fff; }
  .wrap{ max-width:480px; margin:0 auto; padding:50px 24px 90px; }
  h1{ font-family:var(--font-display); font-size:2rem; text-transform:uppercase; margin:0 0 6px; }
  .sub{ color:var(--c-ink-soft); margin-bottom:30px; }
  .panel{ background:var(--c-white); border:1px solid var(--c-line); border-radius:6px; padding:28px; }
  .field{ margin-bottom:18px; display:flex; flex-direction:column; gap:8px; }
  .field label{ font-family:var(--font-mono); font-size:.72rem; letter-spacing:.08em; text-transform:uppercase; color:var(--c-ink-soft); }
  .field input{ font-family:var(--font-body); font-size:1rem; padding:12px 14px; border:1px solid var(--c-line); border-radius:4px; background:#fff; color:var(--c-ink); width:100%; }
  button{ font-family:var(--font-mono); font-size:.78rem; letter-spacing:.08em; text-transform:uppercase; padding:14px 24px; border-radius:4px; border:1px solid var(--c-ink); background:var(--c-ink); color:#fff; cursor:pointer; margin-top:8px; }
  button:hover{ background:var(--c-tecnica); border-color:var(--c-tecnica); }
  .msg{ margin-bottom:18px; font-family:var(--font-mono); font-size:.85rem; padding:12px 14px; border-radius:4px; }
  .msg.ok{ background:#E4F1E9; color:var(--c-ok); }
  .msg.err{ background:#F6E4E0; color:var(--c-err); }
  .hint{ font-size:.8rem; color:var(--c-ink-soft); margin-top:-8px; margin-bottom:18px; }
</style>
</head>
<body>

<header class="admin-top">
  <div class="logo">Studio <b>FP</b>architetto · Admin</div>
  <nav>
    <a href="index.php">← Gestione news</a>
    <a href="logout.php">Esci</a>
  </nav>
</header>

<div class="wrap">
  <h1>Cambia password</h1>
  <p class="sub">Aggiorna la password di accesso al pannello. Verrà salvata solo in forma cifrata.</p>

  <div class="panel">
    <?php if ($errore): ?><div class="msg err"><?= htmlspecialchars($errore, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>
    <?php if ($successo): ?><div class="msg ok"><?= htmlspecialchars($successo, ENT_QUOTES, 'UTF-8') ?></div><?php endif; ?>

    <form method="post" autocomplete="off">
      <div class="field">
        <label for="attuale">Password attuale</label>
        <input type="password" id="attuale" name="attuale" required autocomplete="current-password">
      </div>
      <div class="field">
        <label for="nuova">Nuova password</label>
        <input type="password" id="nuova" name="nuova" required autocomplete="new-password" minlength="8">
      </div>
      <p class="hint">Almeno 8 caratteri. Meglio se mix di lettere, numeri e simboli.</p>
      <div class="field">
        <label for="conferma">Conferma nuova password</label>
        <input type="password" id="conferma" name="conferma" required autocomplete="new-password" minlength="8">
      </div>
      <button type="submit">Aggiorna password</button>
    </form>
  </div>
</div>
</body>
</html>
