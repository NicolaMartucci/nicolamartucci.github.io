<?php
// ============================================================
// AUTH — login, sessione, "ricordami" e protezione anti brute-force
// per il pannello /admin. Incluso da login.php, logout.php,
// cambia-password.php, index.php e dalle API (list/save/delete).
// ============================================================

define('FPARCHITETTO_ADMIN', true);

define('FPA_DATA_DIR', __DIR__ . '/../../data/');
define('FPA_REMEMBER_FILE', FPA_DATA_DIR . 'remember_tokens.json');
define('FPA_ATTEMPTS_FILE', FPA_DATA_DIR . 'login_attempts.json');
define('FPA_REMEMBER_DAYS', 30);
define('FPA_MAX_ATTEMPTS', 5);
define('FPA_LOCKOUT_SECONDS', 900); // 15 minuti

function fpa_is_https() {
    return !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off';
}

function fpa_start_session() {
    if (session_status() === PHP_SESSION_ACTIVE) return;
    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'domain'   => '',
        'secure'   => fpa_is_https(),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
    session_name('fpa_admin_sess');
    session_start();
}

function fpa_credentials() {
    return require __DIR__ . '/auth-config.php';
}

function fpa_read_json($path) {
    if (!file_exists($path)) return [];
    $raw = @file_get_contents($path);
    $data = json_decode($raw, true);
    return is_array($data) ? $data : [];
}

function fpa_write_json($path, $data) {
    $dir = dirname($path);
    if (!is_dir($dir)) { @mkdir($dir, 0755, true); }
    $tmp = $path . '.tmp';
    if (@file_put_contents($tmp, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) === false) return false;
    return @rename($tmp, $path);
}

// ---- Anti brute-force ----

function fpa_client_ip() {
    return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
}

// Ritorna il numero di secondi residui di blocco, oppure false se non bloccato.
function fpa_login_blocked() {
    $attempts = fpa_read_json(FPA_ATTEMPTS_FILE);
    $ip = fpa_client_ip();
    if (!isset($attempts[$ip])) return false;
    $rec = $attempts[$ip];
    $elapsed = time() - $rec['last'];
    if ($rec['count'] >= FPA_MAX_ATTEMPTS && $elapsed < FPA_LOCKOUT_SECONDS) {
        return FPA_LOCKOUT_SECONDS - $elapsed;
    }
    return false;
}

function fpa_register_failed_login() {
    $attempts = fpa_read_json(FPA_ATTEMPTS_FILE);
    $ip = fpa_client_ip();
    if (!isset($attempts[$ip]) || (time() - $attempts[$ip]['last']) > FPA_LOCKOUT_SECONDS) {
        $attempts[$ip] = ['count' => 0, 'last' => 0];
    }
    $attempts[$ip]['count']++;
    $attempts[$ip]['last'] = time();
    fpa_write_json(FPA_ATTEMPTS_FILE, $attempts);
}

function fpa_clear_failed_logins() {
    $attempts = fpa_read_json(FPA_ATTEMPTS_FILE);
    unset($attempts[fpa_client_ip()]);
    fpa_write_json(FPA_ATTEMPTS_FILE, $attempts);
}

// ---- "Ricordami" (pattern selector/validator, con rotazione ad ogni uso) ----

function fpa_create_remember_token($username) {
    $selector  = bin2hex(random_bytes(9));
    $validator = bin2hex(random_bytes(32));

    $tokens = fpa_read_json(FPA_REMEMBER_FILE);
    $now = time();
    foreach ($tokens as $sel => $rec) {
        if (($rec['expires'] ?? 0) < $now) unset($tokens[$sel]);
    }
    $tokens[$selector] = [
        'username' => $username,
        'hash'     => hash('sha256', $validator),
        'expires'  => $now + (86400 * FPA_REMEMBER_DAYS),
    ];
    fpa_write_json(FPA_REMEMBER_FILE, $tokens);

    setcookie('fpa_remember', $selector . ':' . $validator, [
        'expires'  => $now + (86400 * FPA_REMEMBER_DAYS),
        'path'     => '/',
        'secure'   => fpa_is_https(),
        'httponly' => true,
        'samesite' => 'Lax',
    ]);
}

function fpa_clear_remember_token() {
    if (empty($_COOKIE['fpa_remember'])) return;
    $parts = explode(':', $_COOKIE['fpa_remember'], 2);
    $selector = $parts[0] ?? '';
    if ($selector) {
        $tokens = fpa_read_json(FPA_REMEMBER_FILE);
        if (isset($tokens[$selector])) {
            unset($tokens[$selector]);
            fpa_write_json(FPA_REMEMBER_FILE, $tokens);
        }
    }
    setcookie('fpa_remember', '', ['expires' => time() - 3600, 'path' => '/']);
}

function fpa_try_remember_login() {
    if (empty($_COOKIE['fpa_remember'])) return false;
    $parts = explode(':', $_COOKIE['fpa_remember'], 2);
    $selector  = $parts[0] ?? '';
    $validator = $parts[1] ?? '';
    if ($selector === '' || $validator === '') return false;

    $tokens = fpa_read_json(FPA_REMEMBER_FILE);
    if (!isset($tokens[$selector])) return false;
    $rec = $tokens[$selector];

    if (($rec['expires'] ?? 0) < time()) {
        unset($tokens[$selector]);
        fpa_write_json(FPA_REMEMBER_FILE, $tokens);
        return false;
    }
    if (!hash_equals($rec['hash'], hash('sha256', $validator))) {
        return false; // possibile furto di cookie: non riautentica
    }

    // Token valido e monouso: lo ruoto per sicurezza (evita replay)
    unset($tokens[$selector]);
    fpa_write_json(FPA_REMEMBER_FILE, $tokens);

    fpa_start_session();
    session_regenerate_id(true);
    $_SESSION['fpa_user'] = $rec['username'];
    fpa_create_remember_token($rec['username']);
    return true;
}

function fpa_is_logged_in() {
    fpa_start_session();
    if (!empty($_SESSION['fpa_user'])) return true;
    return fpa_try_remember_login();
}

function fpa_current_user() {
    fpa_start_session();
    return $_SESSION['fpa_user'] ?? null;
}

function fpa_login($username, $password, $remember = false) {
    $blocked = fpa_login_blocked();
    if ($blocked !== false) {
        return ['ok' => false, 'error' => 'Troppi tentativi falliti. Riprova tra ' . ceil($blocked / 60) . ' minuti.'];
    }

    $creds = fpa_credentials();
    $usernameOk = hash_equals($creds['username'], $username);
    $passwordOk = password_verify($password, $creds['password_hash']);

    if (!$usernameOk || !$passwordOk) {
        fpa_register_failed_login();
        return ['ok' => false, 'error' => 'Nome utente o password non corretti.'];
    }

    fpa_clear_failed_logins();
    fpa_start_session();
    session_regenerate_id(true);
    $_SESSION['fpa_user'] = $creds['username'];
    if ($remember) fpa_create_remember_token($creds['username']);
    return ['ok' => true];
}

function fpa_logout() {
    fpa_start_session();
    $_SESSION = [];
    if (ini_get('session.use_cookies')) {
        $params = session_get_cookie_params();
        setcookie(session_name(), '', time() - 42000, $params['path']);
    }
    session_destroy();
    fpa_clear_remember_token();
}

// Riscrive in modo sicuro auth-config.php con un nuovo hash password.
function fpa_update_password_hash($username, $newHash) {
    $content = "<?php\n"
        . "// ============================================================\n"
        . "// AUTH-CONFIG — credenziali di accesso al pannello admin.\n"
        . "// La password NON è mai salvata in chiaro qui dentro: solo il suo\n"
        . "// hash bcrypt (impossibile da invertire). Per cambiarla non modificare\n"
        . "// questo file a mano: usa la pagina \"Cambia password\" nel pannello\n"
        . "// (admin/cambia-password.php), che lo riscrive da sola in sicurezza.\n"
        . "// ============================================================\n\n"
        . "if (!defined('FPARCHITETTO_ADMIN')) {\n"
        . "    http_response_code(403);\n"
        . "    exit('Accesso diretto non consentito.');\n"
        . "}\n\n"
        . "return [\n"
        . "    'username'      => " . var_export($username, true) . ",\n"
        . "    'password_hash' => " . var_export($newHash, true) . ",\n"
        . "];\n";
    $path = __DIR__ . '/auth-config.php';
    $tmp = $path . '.tmp';
    if (@file_put_contents($tmp, $content) === false) return false;
    return @rename($tmp, $path);
}

// ---- Guardie da richiamare nelle pagine/API protette ----

// Per pagine HTML: se non autenticato, reindirizza al login.
function fpa_require_auth_page() {
    if (!fpa_is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}

// Per endpoint JSON: se non autenticato, risponde 401.
function fpa_require_auth_api() {
    if (!fpa_is_logged_in()) {
        http_response_code(401);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode(['ok' => false, 'error' => 'Sessione scaduta o accesso non autorizzato. Effettua di nuovo il login.']);
        exit;
    }
}
