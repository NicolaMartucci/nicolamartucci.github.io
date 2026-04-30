<?php
require_once __DIR__ . '/includes/config.php';

$error = '';
$remember = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim((isset($_POST['username']) ? $_POST['username'] : ''));
    $password = (isset($_POST['password']) ? $_POST['password'] : '');
    $remember = isset($_POST['remember']);

    // Rate limiting: blocca dopo 10 tentativi falliti in 5 minuti
    if (!checkLoginRateLimit()) {
        $error = 'Troppi tentativi di accesso. Riprova tra qualche minuto.';
    } else {
        $users = loadUsers();
        if (isset($users[$username]) && password_verify($password, $users[$username]['password'])) {
            clearLoginAttempts(); // Reset tentativi su login OK
            $_SESSION['ta_user'] = $users[$username];
            session_regenerate_id(true); // Previeni session fixation
            if ($remember) {
                $token = bin2hex(random_bytes(32));
                setcookie('ta_remember', $token, time() + 30 * 86400, '/', '', true, true);
            }
            header('Location: /admin/dashboard.php');
            exit;
        } else {
            recordLoginAttempt(); // Registra tentativo fallito
            $error = 'Username o password non corretti.';
        }
    }
}

// Auto-login da cookie (feature non ancora implementata in modo sicuro)
// Per implementarla correttamente usare token sicuri salvati in DB, non solo cookie

if (isLoggedIn()) {
    header('Location: /admin/dashboard.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Accesso — CMS TuttoApricena</title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=Inter:wght@300;400;500&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

:root {
    --gold: #c9a227;
    --gold-dark: #a07d10;
    --dark: #0e0e14;
    --dark2: #16161f;
    --dark3: #1e1e2a;
    --border: rgba(201,162,39,0.15);
    --text: #f0ece0;
    --muted: #888;
}

body {
    font-family: 'Inter', sans-serif;
    background: var(--dark);
    color: var(--text);
    min-height: 100vh;
    display: flex;
    align-items: center;
    justify-content: center;
    overflow: hidden;
}

.bg {
    position: fixed; inset: 0;
    background:
        radial-gradient(ellipse 80% 60% at 20% 50%, rgba(201,162,39,0.07) 0%, transparent 60%),
        radial-gradient(ellipse 60% 80% at 80% 20%, rgba(201,162,39,0.04) 0%, transparent 60%);
    pointer-events: none;
}

.grid-overlay {
    position: fixed; inset: 0;
    background-image:
        linear-gradient(rgba(201,162,39,0.04) 1px, transparent 1px),
        linear-gradient(90deg, rgba(201,162,39,0.04) 1px, transparent 1px);
    background-size: 60px 60px;
    pointer-events: none;
}

.login-wrap {
    position: relative;
    width: 100%;
    max-width: 440px;
    padding: 20px;
}

.logo {
    text-align: center;
    margin-bottom: 40px;
}

.logo-mark {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 64px; height: 64px;
    background: linear-gradient(135deg, var(--gold), var(--gold-dark));
    border-radius: 16px;
    font-family: 'Syne', sans-serif;
    font-weight: 800;
    font-size: 22px;
    color: #0e0e14;
    margin-bottom: 16px;
    box-shadow: 0 8px 32px rgba(201,162,39,0.3);
}

.logo h1 {
    font-family: 'Syne', sans-serif;
    font-size: 22px;
    font-weight: 700;
    color: var(--text);
    letter-spacing: -0.02em;
}

.logo h1 span { color: var(--gold); }
.logo p { font-size: 13px; color: var(--muted); margin-top: 4px; }

.card {
    background: var(--dark2);
    border: 1px solid var(--border);
    border-radius: 20px;
    padding: 40px;
    box-shadow: 0 24px 80px rgba(0,0,0,0.5);
}

.card h2 {
    font-family: 'Syne', sans-serif;
    font-size: 18px;
    font-weight: 700;
    margin-bottom: 8px;
}

.card p { font-size: 13px; color: var(--muted); margin-bottom: 28px; }

.field {
    margin-bottom: 18px;
}

.field label {
    display: block;
    font-size: 12px;
    font-weight: 500;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--muted);
    margin-bottom: 8px;
}

.field input {
    width: 100%;
    background: var(--dark3);
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 13px 16px;
    font-family: 'Inter', sans-serif;
    font-size: 15px;
    color: var(--text);
    transition: border-color 0.2s, box-shadow 0.2s;
    outline: none;
}

.field input:focus {
    border-color: var(--gold);
    box-shadow: 0 0 0 3px rgba(201,162,39,0.1);
}

.remember {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 24px;
    font-size: 14px;
    color: var(--muted);
    cursor: pointer;
}

.remember input[type="checkbox"] {
    width: 18px; height: 18px;
    accent-color: var(--gold);
    cursor: pointer;
}

.btn-login {
    width: 100%;
    background: linear-gradient(135deg, var(--gold), var(--gold-dark));
    color: #0e0e14;
    border: none;
    border-radius: 10px;
    padding: 14px;
    font-family: 'Syne', sans-serif;
    font-size: 15px;
    font-weight: 700;
    cursor: pointer;
    transition: opacity 0.2s, transform 0.1s;
    letter-spacing: 0.02em;
}

.btn-login:hover { opacity: 0.9; transform: translateY(-1px); }
.btn-login:active { transform: translateY(0); }

.error {
    background: rgba(220,53,69,0.1);
    border: 1px solid rgba(220,53,69,0.3);
    border-radius: 8px;
    padding: 12px 16px;
    font-size: 13px;
    color: #ff6b7a;
    margin-bottom: 20px;
}

@keyframes fadeUp {
    from { opacity: 0; transform: translateY(20px); }
    to { opacity: 1; transform: translateY(0); }
}
.login-wrap { animation: fadeUp 0.5s ease; }
</style>
</head>
<body>
<div class="bg"></div>
<div class="grid-overlay"></div>

<div class="login-wrap">
    <div class="logo">
        <div class="logo-mark">TA</div>
        <h1>Tutto<span>Apricena</span></h1>
        <p>Pannello di gestione contenuti</p>
    </div>

    <div class="card">
        <h2>Accesso CMS</h2>
        <p>Inserisci le tue credenziali per continuare</p>

        <?php if ($error): ?>
        <div class="error">⚠ <?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="">
            <div class="field">
                <label>Username</label>
                <input type="text" name="username" autocomplete="username" required
                       value="<?= htmlspecialchars((isset($_POST['username']) ? $_POST['username'] : '')) ?>">
            </div>
            <div class="field">
                <label>Password</label>
                <input type="password" name="password" autocomplete="current-password" required>
            </div>
            <label class="remember">
                <input type="checkbox" name="remember" <?= $remember ? 'checked' : '' ?>>
                Ricorda le credenziali
            </label>
            <button type="submit" class="btn-login">Accedi</button>
        </form>
    </div>
</div>
</body>
</html>
