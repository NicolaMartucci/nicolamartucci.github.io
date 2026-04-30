<?php
// layout.php — include all'inizio di ogni pagina CMS
// Uso: require_once 'includes/layout.php'; con $pageTitle e $activeSection definiti
requireLogin();
$user = getCurrentUser();
$settings = loadData('settings') ?: [];
$siteName = (isset($settings['nome_sito']) ? $settings['nome_sito'] : 'TuttoApricena');

// Conteggi badge
$countNotizie  = count(loadData('notizie'));
$countEventi   = count(loadData('eventi'));
$countLocali   = count(loadData('locali'));
$countSponsor  = count(loadData('sponsor'));
$countFarmacie = count(loadData('farmacie'));
$countServizi  = count(loadData('servizi'));

$nav = [
    ['id'=>'dashboard',  'icon'=>'⊞',  'label'=>'Dashboard',      'href'=>'dashboard.php'],
    ['id'=>'notizie',    'icon'=>'📰', 'label'=>'Notizie',         'href'=>'notizie.php',  'count'=>$countNotizie],
    ['id'=>'eventi',     'icon'=>'📅', 'label'=>'Eventi',          'href'=>'eventi.php',   'count'=>$countEventi],
    ['id'=>'farmacie',   'icon'=>'💊', 'label'=>'Farmacie',        'href'=>'farmacie.php', 'count'=>$countFarmacie],
    ['id'=>'servizi',    'icon'=>'🔧', 'label'=>'Servizi',         'href'=>'servizi.php',  'count'=>$countServizi],
    ['id'=>'locali',     'icon'=>'🏠', 'label'=>'Locali',          'href'=>'locali.php',   'count'=>$countLocali],
    ['id'=>'sponsor',    'icon'=>'⭐', 'label'=>'Sponsor',         'href'=>'sponsor.php',  'count'=>$countSponsor],
    ['id'=>'ordinamento','icon'=>'↕',  'label'=>'Ordinamento',     'href'=>'ordinamento.php'],
    ['id'=>'sincronizza','icon'=>'🚀', 'label'=>'Pubblica sito',   'href'=>'sincronizza.php'],
    ['id'=>'immagini',    'icon'=>'🖼',  'label'=>'Immagini sito',   'href'=>'immagini.php'],
    ['id'=>'media',      'icon'=>'🖼',  'label'=>'Media',           'href'=>'media.php'],
    ['id'=>'pagine',     'icon'=>'📄', 'label'=>'Pagine',          'href'=>'pagine.php'],
    ['id'=>'impostazioni','icon'=>'⚙', 'label'=>'Impostazioni',   'href'=>'impostazioni.php'],
];
if ($user['role'] === 'admin') {
    $nav[] = ['id'=>'collaboratori','icon'=>'👥','label'=>'Collaboratori','href'=>'collaboratori.php'];
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= htmlspecialchars((isset($pageTitle) ? $pageTitle : 'CMS')) ?> — <?= htmlspecialchars($siteName) ?></title>
<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Syne:wght@400;600;700;800&family=Inter:wght@300;400;500;600&display=swap" rel="stylesheet">
<style>
*, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }
:root {
    --gold: #c9a227;
    --gold-light: #e8c84a;
    --gold-dark: #a07d10;
    --dark: #0c0c12;
    --dark2: #13131c;
    --dark3: #1a1a26;
    --dark4: #222230;
    --border: rgba(201,162,39,0.12);
    --border2: rgba(255,255,255,0.06);
    --text: #f0ece0;
    --text2: #b0a898;
    --muted: #666;
    --red: #e05555;
    --green: #4caf7d;
    --blue: #5b9cf6;
    --sidebar-w: 240px;
}

html, body { height: 100%; }

body {
    font-family: 'Inter', sans-serif;
    background: var(--dark);
    color: var(--text);
    display: flex;
    min-height: 100vh;
}

/* ===== SIDEBAR ===== */
.sidebar {
    width: var(--sidebar-w);
    min-height: 100vh;
    background: var(--dark2);
    border-right: 1px solid var(--border);
    display: flex;
    flex-direction: column;
    position: fixed;
    top: 0; left: 0; bottom: 0;
    z-index: 100;
    overflow-y: auto;
}

.sidebar-logo {
    padding: 24px 20px 20px;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    gap: 12px;
}

.logo-mark {
    width: 40px; height: 40px;
    background: linear-gradient(135deg, var(--gold), var(--gold-dark));
    border-radius: 10px;
    display: flex; align-items: center; justify-content: center;
    font-family: 'Syne', sans-serif;
    font-weight: 800;
    font-size: 14px;
    color: #0c0c12;
    flex-shrink: 0;
}

.logo-text strong {
    display: block;
    font-family: 'Syne', sans-serif;
    font-size: 14px;
    font-weight: 700;
}
.logo-text span { font-size: 11px; color: var(--muted); }

.sidebar-nav { flex: 1; padding: 12px 0; }

.nav-group-label {
    font-size: 10px;
    font-weight: 600;
    letter-spacing: 0.1em;
    text-transform: uppercase;
    color: var(--muted);
    padding: 12px 20px 6px;
}

.nav-item {
    display: flex;
    align-items: center;
    gap: 10px;
    padding: 9px 20px;
    font-size: 13.5px;
    font-weight: 500;
    color: var(--text2);
    text-decoration: none;
    transition: all 0.15s;
    position: relative;
    border-radius: 0;
}

.nav-item:hover { color: var(--text); background: rgba(255,255,255,0.04); }

.nav-item.active {
    color: var(--gold);
    background: rgba(201,162,39,0.08);
}
.nav-item.active::before {
    content: '';
    position: absolute;
    left: 0; top: 0; bottom: 0;
    width: 3px;
    background: var(--gold);
    border-radius: 0 2px 2px 0;
}

.nav-icon { font-size: 16px; width: 20px; text-align: center; }
.nav-label { flex: 1; }
.nav-badge {
    background: var(--dark4);
    color: var(--muted);
    font-size: 11px;
    padding: 2px 7px;
    border-radius: 10px;
    font-weight: 600;
}

.sidebar-footer {
    padding: 16px 20px;
    border-top: 1px solid var(--border);
}

.user-info {
    display: flex;
    align-items: center;
    gap: 10px;
    margin-bottom: 10px;
}

.user-avatar {
    width: 32px; height: 32px;
    background: linear-gradient(135deg, var(--gold), var(--gold-dark));
    border-radius: 8px;
    display: flex; align-items: center; justify-content: center;
    font-size: 13px; font-weight: 700;
    color: #0c0c12;
    flex-shrink: 0;
}

.user-details strong { font-size: 13px; display: block; }
.user-details span { font-size: 11px; color: var(--muted); text-transform: capitalize; }

.btn-logout {
    display: block;
    width: 100%;
    background: var(--dark3);
    border: 1px solid var(--border2);
    border-radius: 8px;
    padding: 8px 12px;
    font-size: 12px;
    color: var(--muted);
    text-align: center;
    text-decoration: none;
    transition: all 0.15s;
}
.btn-logout:hover { background: var(--dark4); color: var(--red); }

/* ===== MAIN ===== */
.main {
    margin-left: var(--sidebar-w);
    flex: 1;
    display: flex;
    flex-direction: column;
    min-height: 100vh;
}

.topbar {
    background: var(--dark2);
    border-bottom: 1px solid var(--border);
    padding: 16px 32px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
    position: sticky; top: 0; z-index: 50;
}

.topbar-left h2 {
    font-family: 'Syne', sans-serif;
    font-size: 18px;
    font-weight: 700;
}
.topbar-left p { font-size: 12px; color: var(--muted); margin-top: 2px; }

.topbar-right { display: flex; gap: 10px; align-items: center; }

.page-content {
    flex: 1;
    padding: 32px;
    max-width: 1200px;
}

/* ===== COMPONENTS ===== */
.card {
    background: var(--dark2);
    border: 1px solid var(--border);
    border-radius: 16px;
    overflow: hidden;
}

.card-header {
    padding: 20px 24px;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
    gap: 16px;
}

.card-title {
    font-family: 'Syne', sans-serif;
    font-size: 16px;
    font-weight: 700;
}

.card-body { padding: 24px; }

.btn {
    display: inline-flex;
    align-items: center;
    gap: 6px;
    padding: 9px 18px;
    border-radius: 8px;
    font-family: 'Inter', sans-serif;
    font-size: 13px;
    font-weight: 600;
    cursor: pointer;
    border: none;
    transition: all 0.15s;
    text-decoration: none;
}
.btn-primary {
    background: linear-gradient(135deg, var(--gold), var(--gold-dark));
    color: #0c0c12;
}
.btn-primary:hover { opacity: 0.9; transform: translateY(-1px); }
.btn-secondary {
    background: var(--dark3);
    color: var(--text2);
    border: 1px solid var(--border2);
}
.btn-secondary:hover { background: var(--dark4); color: var(--text); }
.btn-danger {
    background: rgba(224,85,85,0.1);
    color: var(--red);
    border: 1px solid rgba(224,85,85,0.2);
}
.btn-danger:hover { background: rgba(224,85,85,0.2); }
.btn-sm { padding: 6px 12px; font-size: 12px; }
.btn-xs { padding: 4px 8px; font-size: 11px; border-radius: 6px; }

.badge {
    display: inline-flex;
    align-items: center;
    padding: 3px 8px;
    border-radius: 6px;
    font-size: 11px;
    font-weight: 600;
    gap: 4px;
}
.badge-gold { background: rgba(201,162,39,0.15); color: var(--gold); }
.badge-green { background: rgba(76,175,125,0.15); color: var(--green); }
.badge-red { background: rgba(224,85,85,0.15); color: var(--red); }
.badge-blue { background: rgba(91,156,246,0.15); color: var(--blue); }
.badge-muted { background: var(--dark3); color: var(--muted); }

.form-field { margin-bottom: 20px; }
.form-field label {
    display: block;
    font-size: 12px;
    font-weight: 600;
    letter-spacing: 0.06em;
    text-transform: uppercase;
    color: var(--text2);
    margin-bottom: 8px;
}
.form-field input,
.form-field select,
.form-field textarea {
    width: 100%;
    background: var(--dark3);
    border: 1px solid var(--border2);
    border-radius: 8px;
    padding: 10px 14px;
    font-family: 'Inter', sans-serif;
    font-size: 14px;
    color: var(--text);
    outline: none;
    transition: border-color 0.2s, box-shadow 0.2s;
    resize: vertical;
}
.form-field input:focus,
.form-field select:focus,
.form-field textarea:focus {
    border-color: var(--gold);
    box-shadow: 0 0 0 3px rgba(201,162,39,0.1);
}
.form-field select option { background: var(--dark3); }
.form-field small { font-size: 11px; color: var(--muted); margin-top: 4px; display: block; }

.form-row { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
.form-row-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px; }

.toggle-switch {
    display: flex;
    align-items: center;
    gap: 10px;
    cursor: pointer;
}
.toggle-switch input { display: none; }
.toggle-track {
    width: 40px; height: 22px;
    background: var(--dark4);
    border-radius: 11px;
    position: relative;
    transition: background 0.2s;
    flex-shrink: 0;
}
.toggle-track::after {
    content: '';
    position: absolute;
    width: 16px; height: 16px;
    background: white;
    border-radius: 50%;
    top: 3px; left: 3px;
    transition: transform 0.2s;
}
.toggle-switch input:checked ~ .toggle-track { background: var(--gold); }
.toggle-switch input:checked ~ .toggle-track::after { transform: translateX(18px); }
.toggle-label { font-size: 13px; color: var(--text2); }

.table-wrap { overflow-x: auto; }
table { width: 100%; border-collapse: collapse; }
th {
    text-align: left;
    font-size: 11px;
    font-weight: 600;
    letter-spacing: 0.08em;
    text-transform: uppercase;
    color: var(--muted);
    padding: 12px 16px;
    border-bottom: 1px solid var(--border);
}
td {
    padding: 14px 16px;
    border-bottom: 1px solid rgba(255,255,255,0.04);
    font-size: 13.5px;
    vertical-align: middle;
}
tr:last-child td { border-bottom: none; }
tr:hover td { background: rgba(255,255,255,0.02); }

.empty-state {
    text-align: center;
    padding: 60px 20px;
    color: var(--muted);
}
.empty-state .icon { font-size: 48px; margin-bottom: 16px; }
.empty-state h3 { font-family: 'Syne', sans-serif; font-size: 16px; color: var(--text2); margin-bottom: 8px; }
.empty-state p { font-size: 13px; }

.alert {
    padding: 12px 16px;
    border-radius: 10px;
    font-size: 13px;
    margin-bottom: 20px;
    display: flex;
    align-items: center;
    gap: 8px;
}
.alert-success { background: rgba(76,175,125,0.1); border: 1px solid rgba(76,175,125,0.25); color: var(--green); }
.alert-error { background: rgba(224,85,85,0.1); border: 1px solid rgba(224,85,85,0.25); color: var(--red); }

/* Modal */
.modal-overlay {
    display: none;
    position: fixed; inset: 0; z-index: 1000;
    background: rgba(0,0,0,0.7);
    backdrop-filter: blur(4px);
    align-items: center;
    justify-content: center;
    padding: 20px;
}
.modal-overlay.open { display: flex; }
.modal {
    background: var(--dark2);
    border: 1px solid var(--border);
    border-radius: 20px;
    width: 100%;
    max-width: 640px;
    max-height: 90vh;
    overflow-y: auto;
    animation: modalIn 0.2s ease;
}
@keyframes modalIn {
    from { opacity: 0; transform: scale(0.95) translateY(10px); }
    to { opacity: 1; transform: scale(1) translateY(0); }
}
.modal-header {
    padding: 24px 28px 20px;
    border-bottom: 1px solid var(--border);
    display: flex;
    align-items: center;
    justify-content: space-between;
}
.modal-title { font-family: 'Syne', sans-serif; font-size: 17px; font-weight: 700; }
.modal-close {
    width: 32px; height: 32px;
    background: var(--dark3);
    border: none;
    border-radius: 8px;
    color: var(--muted);
    cursor: pointer;
    font-size: 18px;
    display: flex; align-items: center; justify-content: center;
    transition: all 0.15s;
}
.modal-close:hover { background: var(--dark4); color: var(--text); }
.modal-body { padding: 24px 28px; }
.modal-footer {
    padding: 16px 28px 24px;
    display: flex;
    gap: 10px;
    justify-content: flex-end;
}

/* Immagine preview */
.img-preview {
    width: 80px; height: 55px;
    object-fit: cover;
    border-radius: 6px;
    background: var(--dark3);
}

/* Evidenza star */
.star-btn {
    background: none; border: none;
    font-size: 18px;
    cursor: pointer;
    transition: transform 0.2s;
    padding: 4px;
}
.star-btn:hover { transform: scale(1.2); }

/* Search */
.search-bar {
    display: flex;
    align-items: center;
    gap: 8px;
    background: var(--dark3);
    border: 1px solid var(--border2);
    border-radius: 8px;
    padding: 8px 14px;
    min-width: 220px;
}
.search-bar input {
    background: none;
    border: none;
    outline: none;
    font-size: 13px;
    color: var(--text);
    width: 100%;
}
.search-bar input::placeholder { color: var(--muted); }
.search-bar span { color: var(--muted); }

/* Toast */
#toast {
    position: fixed;
    bottom: 24px; right: 24px;
    z-index: 9999;
    display: flex;
    flex-direction: column;
    gap: 8px;
    pointer-events: none;
}
.toast-msg {
    background: var(--dark2);
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 12px 18px;
    font-size: 13px;
    min-width: 240px;
    box-shadow: 0 8px 32px rgba(0,0,0,0.4);
    animation: toastIn 0.3s ease;
    display: flex;
    align-items: center;
    gap: 8px;
}
.toast-msg.success { border-color: rgba(76,175,125,0.4); color: var(--green); }
.toast-msg.error { border-color: rgba(224,85,85,0.4); color: var(--red); }
@keyframes toastIn {
    from { opacity:0; transform: translateX(20px); }
    to { opacity:1; transform: translateX(0); }
}

/* ===== MOBILE HAMBURGER BUTTON ===== */
.hamburger-btn {
    display: none;
    background: var(--dark3);
    border: 1px solid var(--border2);
    border-radius: 8px;
    width: 36px; height: 36px;
    align-items: center; justify-content: center;
    cursor: pointer;
    flex-shrink: 0;
    color: var(--text2);
    font-size: 18px;
    transition: all 0.15s;
}
.hamburger-btn:hover { background: var(--dark4); color: var(--text); }

/* Overlay behind sidebar on mobile */
.sidebar-overlay {
    display: none;
    position: fixed; inset: 0; z-index: 99;
    background: rgba(0,0,0,0.6);
    backdrop-filter: blur(2px);
}
.sidebar-overlay.open { display: block; }

/* ===== RESPONSIVE TOPBAR title truncation ===== */
.topbar-left h2 { white-space: nowrap; overflow: hidden; text-overflow: ellipsis; }

/* ===== TABLE: horizontal scroll on small screens ===== */
.table-wrap { overflow-x: auto; -webkit-overflow-scrolling: touch; }

/* ===== RESPONSIVE SEARCH BAR ===== */
.search-bar { min-width: 0; flex: 1; }

/* ===== CARD HEADER wrapping ===== */
.card-header { flex-wrap: wrap; }

@media (max-width: 900px) {
    /* Narrow sidebar */
    :root { --sidebar-w: 200px; }
    .page-content { padding: 24px 20px; }
}

@media (max-width: 768px) {
    /* Slide-out sidebar */
    .sidebar {
        transform: translateX(-100%);
        transition: transform 0.28s cubic-bezier(0.4,0,0.2,1);
        width: 260px;
        box-shadow: 8px 0 32px rgba(0,0,0,0.5);
    }
    .sidebar.open { transform: translateX(0); }
    .main { margin-left: 0; }
    .hamburger-btn { display: flex; }
    .page-content { padding: 16px 14px; }
    .form-row, .form-row-3 { grid-template-columns: 1fr; }
    .topbar { padding: 12px 14px; gap: 10px; }
    .topbar-left h2 { font-size: 15px; max-width: 150px; }
    .topbar-right { gap: 6px; }
    .topbar-right .btn { padding: 7px 10px; font-size: 12px; }

    /* Card body padding */
    .card-body { padding: 16px; }
    .card-header { padding: 14px 16px; gap: 10px; }
    .card-title { font-size: 14px; }

    /* Tables */
    table { font-size: 12.5px; }
    th, td { padding: 10px 10px; }
    /* Hide less important columns on small screens */
    .col-hide-sm { display: none !important; }

    /* Buttons smaller */
    .btn { padding: 8px 14px; font-size: 12px; }
    .btn-sm { padding: 5px 10px; font-size: 11px; }

    /* Modal full-screen on mobile */
    .modal-overlay { padding: 12px; align-items: flex-end; }
    .modal {
        border-radius: 20px 20px 12px 12px;
        max-height: 92vh;
        width: 100%;
        max-width: 100%;
    }
    .modal-header { padding: 18px 18px 14px; }
    .modal-body { padding: 16px 18px; }
    .modal-footer { padding: 12px 18px 18px; flex-wrap: wrap; }
    .modal-footer .btn { flex: 1; justify-content: center; }

    /* Form fields bigger touch target */
    .form-field input,
    .form-field select,
    .form-field textarea {
        font-size: 16px; /* prevents iOS zoom */
        padding: 12px 14px;
    }

    /* Search bar */
    .search-bar { min-width: 0; width: 100%; }

    /* Toast bottom-center */
    #toast { bottom: 16px; right: 12px; left: 12px; }
    .toast-msg { min-width: 0; width: 100%; }

    /* Image preview smaller */
    .img-preview { width: 60px; height: 42px; }

    /* Topbar right: hide text of secondary buttons */
    .topbar-right .btn-secondary .btn-label { display: none; }
}

@media (max-width: 480px) {
    .page-content { padding: 12px 10px; }
    .topbar { padding: 10px 12px; }
    .topbar-left h2 { font-size: 14px; max-width: 120px; }
    .card-body { padding: 12px; }
    .card-header { padding: 12px 14px; }

    /* Stack topbar actions vertically if needed */
    .topbar-right .btn { padding: 6px 8px; }

    /* nav badges: hidden on very small */
    .nav-badge { display: none; }
}
</style>
</head>
<body>

<aside class="sidebar" id="sidebar">
    <div class="sidebar-logo">
        <div class="logo-mark">TA</div>
        <div class="logo-text">
            <strong><?= htmlspecialchars($siteName) ?></strong>
            <span>CMS v<?= CMS_VERSION ?></span>
        </div>
    </div>

    <nav class="sidebar-nav">
        <div class="nav-group-label">Menu</div>
        <?php foreach ($nav as $item): ?>
        <?php if (!hasPermission($item['id']) && $item['id'] !== 'dashboard') continue; ?>
        <a href="/admin/<?= $item['href'] ?>"
           class="nav-item <?= ((isset($activeSection) ? $activeSection : '')) === $item['id'] ? 'active' : '' ?>">
            <span class="nav-icon"><?= $item['icon'] ?></span>
            <span class="nav-label"><?= $item['label'] ?></span>
            <?php if (!empty($item['count'])): ?>
            <span class="nav-badge"><?= $item['count'] ?></span>
            <?php endif; ?>
        </a>
        <?php endforeach; ?>
    </nav>

    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar"><?= strtoupper(substr((isset($user['name']) ? $user['name'] : $user['username']), 0, 1)) ?></div>
            <div class="user-details">
                <strong><?= htmlspecialchars((isset($user['name']) ? $user['name'] : $user['username'])) ?></strong>
                <span><?= $user['role'] === 'admin' ? 'Amministratore' : 'Collaboratore' ?></span>
            </div>
        </div>
        <a href="/admin/logout.php" class="btn-logout">← Esci</a>
    </div>
</aside>

<!-- Overlay behind sidebar on mobile -->
<div class="sidebar-overlay" id="sidebar-overlay" onclick="closeSidebar()"></div>

<main class="main">
    <div class="topbar">
        <div class="topbar-left" style="display:flex;align-items:center;gap:12px;">
            <button class="hamburger-btn" id="hamburger-btn" onclick="toggleSidebar()" aria-label="Menu">
                ☰
            </button>
            <div>
                <h2><?= htmlspecialchars((isset($pageTitle) ? $pageTitle : '')) ?></h2>
                <?php if (!empty($pageSubtitle)): ?>
                <p><?= htmlspecialchars($pageSubtitle) ?></p>
                <?php endif; ?>
            </div>
        </div>
        <div class="topbar-right">
            <a href="https://www.tuttoapricena.it" target="_blank" class="btn btn-secondary btn-sm">
                🌐 <span class="btn-label">Vedi sito</span>
            </a>
            <?php if (!empty($topbarAction)): echo $topbarAction; endif; ?>
        </div>
    </div>

    <div class="page-content">
<?php // Content starts here ?>
