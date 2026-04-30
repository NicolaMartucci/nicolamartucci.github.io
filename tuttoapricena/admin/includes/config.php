<?php
// =============================================
// TuttoApricena CMS — Config & Auth
// =============================================

// Header di sicurezza HTTP (evitano clickjacking, XSS, sniffing MIME)
if (!headers_sent()) {
    header('X-Frame-Options: DENY');
    header('X-Content-Type-Options: nosniff');
    header('X-XSS-Protection: 1; mode=block');
    header('Referrer-Policy: strict-origin-when-cross-origin');
}

// Compatibilità PHP: richiede almeno PHP 7.0
if (version_compare(PHP_VERSION, '7.0.0', '<')) {
    http_response_code(500);
    die('<h2>Errore: PHP ' . PHP_VERSION . ' non supportato. Richiesto PHP 7.0+. Contatta il tuo hosting.</h2>');
}

// Gestione errori — mostra errori solo in debug locale
if (isset($_SERVER['HTTP_HOST']) && ($_SERVER['HTTP_HOST'] === 'localhost' || $_SERVER['HTTP_HOST'] === '127.0.0.1')) {
    ini_set('display_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(0);
    // Log errori su file invece di mostrarli
    ini_set('log_errors', 1);
}

define('CMS_VERSION', '2.0');
define('DATA_DIR', __DIR__ . '/../data/');
define('UPLOAD_DIR', __DIR__ . '/../uploads/');
define('UPLOAD_URL', '/admin/uploads/');
define('SESSION_NAME', 'ta_cms_session');

session_name(SESSION_NAME);

// Cookie di sessione sicuri: HttpOnly + Secure (richiede HTTPS) + SameSite=Strict
$cookieParams = session_get_cookie_params();
session_set_cookie_params([
    'lifetime' => $cookieParams['lifetime'],
    'path'     => '/',
    'domain'   => $cookieParams['domain'],
    'secure'   => true,          // Solo HTTPS
    'httponly' => true,          // Non accessibile da JavaScript
    'samesite' => 'Strict',      // Protezione CSRF di base
]);

session_start();

// ---- Carica utenti ----
function loadUsers() {
    $file = DATA_DIR . 'users.json';
    if (!file_exists($file)) {
        // Utente admin di default
        $default = [
            'admin' => [
                'username' => 'admin',
                'password' => password_hash('admin123', PASSWORD_DEFAULT),
                'role' => 'admin',
                'name' => 'Amministratore',
                'email' => '',
                'permissions' => ['all']
            ]
        ];
        file_put_contents($file, json_encode($default, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        return $default;
    }
    return json_decode(file_get_contents($file), true) ?: [];
}

function saveUsers($users) {
    return file_put_contents(DATA_DIR . 'users.json', json_encode($users, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}

// ---- Auth ----
function isLoggedIn() {
    return isset($_SESSION['ta_user']) && !empty($_SESSION['ta_user']);
}

function getCurrentUser() {
    return (isset($_SESSION['ta_user']) ? $_SESSION['ta_user'] : null);
}

function hasPermission($section) {
    $user = getCurrentUser();
    if (!$user) return false;
    if ($user['role'] === 'admin') return true;
    $perms = (isset($user['permissions']) ? $user['permissions'] : []);
    return in_array('all', $perms) || in_array($section, $perms);
}

function requireLogin() {
    if (!isLoggedIn()) {
        header('Location: /admin/index.php');
        exit;
    }
}

function requireAdmin() {
    requireLogin();
    $user = getCurrentUser();
    if ($user['role'] !== 'admin') {
        http_response_code(403);
        die(json_encode(['error' => 'Accesso negato']));
    }
}

// ---- Rate limiting login (brute force protection) ----
function loginAttemptKey() {
    $ip = isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : 'unknown';
    return sys_get_temp_dir() . '/ta_login_' . md5($ip) . '.json';
}

function checkLoginRateLimit() {
    $file = loginAttemptKey();
    $data = array('attempts' => 0, 'window_start' => time());
    if (file_exists($file)) {
        $stored = json_decode(file_get_contents($file), true);
        if ($stored && (time() - $stored['window_start']) < 300) { // finestra 5 min
            $data = $stored;
        }
    }
    if ($data['attempts'] >= 10) { // max 10 tentativi in 5 minuti
        return false;
    }
    return true;
}

function recordLoginAttempt() {
    $file = loginAttemptKey();
    $data = array('attempts' => 1, 'window_start' => time());
    if (file_exists($file)) {
        $stored = json_decode(file_get_contents($file), true);
        if ($stored && (time() - $stored['window_start']) < 300) {
            $data = array('attempts' => $stored['attempts'] + 1, 'window_start' => $stored['window_start']);
        }
    }
    file_put_contents($file, json_encode($data));
}

function clearLoginAttempts() {
    $file = loginAttemptKey();
    if (file_exists($file)) @unlink($file);
}

// ---- Data helpers ----
// File salvati come oggetto JSON {} (non lista [])
define('OBJECT_FILES', ['chiSiamo', 'settings', 'headerHero']);

function loadData($file) {
    $path = DATA_DIR . $file . '.json';
    if (!file_exists($path)) return [];
    $data = json_decode(file_get_contents($path), true);
    return ($data !== null) ? $data : [];
}

function saveData($file, $data) {
    $path = DATA_DIR . $file . '.json';
    $flags = JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE;
    if (in_array($file, OBJECT_FILES)) {
        return file_put_contents($path, json_encode((object)$data, $flags));
    }
    return file_put_contents($path, json_encode($data, $flags));
}

function generateId() {
    return uniqid('', true);
}

function slugify($text) {
    $text = mb_strtolower($text, 'UTF-8');
    $text = preg_replace('/[àáâãäå]/u', 'a', $text);
    $text = preg_replace('/[èéêë]/u', 'e', $text);
    $text = preg_replace('/[ìíîï]/u', 'i', $text);
    $text = preg_replace('/[òóôõö]/u', 'o', $text);
    $text = preg_replace('/[ùúûü]/u', 'u', $text);
    $text = preg_replace('/[^a-z0-9\s-]/u', '', $text);
    $text = preg_replace('/[\s-]+/', '-', $text);
    return trim($text, '-');
}

// ---- Upload helper ----
function handleUpload($fileKey, $subdir = '') {
    if (!isset($_FILES[$fileKey]) || $_FILES[$fileKey]['error'] !== UPLOAD_ERR_OK) {
        return null;
    }
    $file = $_FILES[$fileKey];
    $ext = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
    $allowed = ['jpg', 'jpeg', 'png', 'webp', 'gif'];
    if (!in_array($ext, $allowed)) return null;
    if ($file['size'] > 5 * 1024 * 1024) return null;

    $dir = UPLOAD_DIR . ($subdir ? $subdir . '/' : '');
    if (!is_dir($dir)) mkdir($dir, 0755, true);

    $filename = uniqid() . '.' . $ext;
    $dest = $dir . $filename;
    if (move_uploaded_file($file['tmp_name'], $dest)) {
        return UPLOAD_URL . ($subdir ? $subdir . '/' : '') . $filename;
    }
    return null;
}

// ---- Init data files ----
$dataFiles = ['notizie', 'eventi', 'farmacie', 'servizi', 'locali', 'sponsor', 'settings', 'chiSiamo', 'headerHero'];
foreach ($dataFiles as $f) {
    $path = DATA_DIR . $f . '.json';
    if (!file_exists($path)) {
        file_put_contents($path, json_encode([], JSON_PRETTY_PRINT));
    }
}

// Settings default
$settingsPath = DATA_DIR . 'settings.json';
$settings = json_decode(file_get_contents($settingsPath), true);
if (empty($settings)) {
    $defaultSettings = [
        'nome_sito' => 'TuttoApricena',
        'tagline' => 'Il portale informativo di Apricena (FG)',
        'email' => '',
        'facebook' => '#',
        'instagram' => '#',
        'colore_primario' => '#b8960c',
        'colore_accent' => '#1a1a2e',
        'meta_description' => 'Portale informativo di Apricena',
        'analytics_id' => '',
        'citta_soprannome' => 'La Città del Marmo e della Pietra',
        'citta_descrizione' => 'Apricena (FG) — Puglia',
        'citta_storia' => ''
    ];
    file_put_contents($settingsPath, json_encode($defaultSettings, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
}
