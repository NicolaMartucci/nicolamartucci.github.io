<?php
// ============================================================
// NEWS ARTICOLO — pagina di dettaglio di una singola news.
// Riceve l'id via query string (?id=...) e mostra la news se pubblicata.
// ============================================================
$jsonPath = __DIR__ . '/data/news.json';
$news = [];
if (file_exists($jsonPath)) {
    $raw = file_get_contents($jsonPath);
    $decoded = json_decode($raw, true);
    if (is_array($decoded)) { $news = $decoded; }
}

$id = $_GET['id'] ?? '';
$articolo = null;
foreach ($news as $n) {
    if (($n['id'] ?? '') === $id && ($n['stato'] ?? '') === 'pubblicato') {
        $articolo = $n;
        break;
    }
}

$mesi = [1=>'gen','feb','mar','apr','mag','giu','lug','ago','set','ott','nov','dic'];
function formatDataIt($dataStr, $mesi) {
    $ts = strtotime($dataStr);
    if (!$ts) return $dataStr;
    return date('d', $ts) . ' ' . $mesi[(int)date('n', $ts)] . ' ' . date('Y', $ts);
}
function escape($s) { return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }
function anteprimaTesto($testo, $len = 150) {
    if (function_exists('mb_strlen')) {
        return mb_strlen($testo) > $len ? mb_substr($testo, 0, $len) . '…' : $testo;
    }
    return strlen($testo) > $len ? substr($testo, 0, $len) . '…' : $testo;
}

// Le anteprime social (Facebook, WhatsApp, Twitter/X, LinkedIn...) richiedono
// URL assoluti per immagini e link: li costruiamo dal dominio corrente, così
// funzionano automaticamente su qualsiasi hosting/dominio senza doverli scrivere a mano.
function baseUrl() {
    $protocollo = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? 'https' : 'http';
    $host = $_SERVER['HTTP_HOST'] ?? 'localhost';
    return $protocollo . '://' . $host . rtrim(dirname($_SERVER['SCRIPT_NAME'] ?? ''), '/');
}
$paginaUrl = baseUrl() . '/news-articolo.php?id=' . urlencode($id);
$immagineOg = $articolo && !empty($articolo['immagine'])
    ? (strpos($articolo['immagine'], 'http') === 0 ? $articolo['immagine'] : baseUrl() . '/' . $articolo['immagine'])
    : baseUrl() . '/assets/img/logo-fparchitetto.jpg';
?>
<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title><?= $articolo ? escape($articolo['titolo']) . ' — News — Studio FParchitetto' : 'News non trovata — Studio FParchitetto' ?></title>
<meta name="description" content="<?= $articolo ? escape(anteprimaTesto($articolo['testo'] ?? '')) : 'Questa news non è più disponibile.' ?>">
<link rel="canonical" href="<?= escape($paginaUrl) ?>">

<!-- Anteprima social (Facebook, WhatsApp, LinkedIn...) -->
<meta property="og:type" content="article">
<meta property="og:locale" content="it_IT">
<meta property="og:site_name" content="Studio FParchitetto">
<meta property="og:title" content="<?= $articolo ? escape($articolo['titolo']) : 'News non trovata' ?>">
<meta property="og:description" content="<?= $articolo ? escape(anteprimaTesto($articolo['testo'] ?? '', 200)) : 'Questa news non è più disponibile.' ?>">
<meta property="og:image" content="<?= escape($immagineOg) ?>">
<meta property="og:image:width" content="1200">
<meta property="og:image:height" content="630">
<meta property="og:url" content="<?= escape($paginaUrl) ?>">
<?php if ($articolo && !empty($articolo['data'])): ?>
<meta property="article:published_time" content="<?= escape(date('c', strtotime($articolo['data']))) ?>">
<?php endif; ?>

<!-- Anteprima social (Twitter/X) -->
<meta name="twitter:card" content="summary_large_image">
<meta name="twitter:title" content="<?= $articolo ? escape($articolo['titolo']) : 'News non trovata' ?>">
<meta name="twitter:description" content="<?= $articolo ? escape(anteprimaTesto($articolo['testo'] ?? '', 200)) : 'Questa news non è più disponibile.' ?>">
<meta name="twitter:image" content="<?= escape($immagineOg) ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@500;600;700&family=Barlow:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
<style>
  .article-hero{ height:46vh; min-height:280px; background-size:cover; background-position:center; margin-top:0; }
  .article-body{ max-width:720px; }
  .article-body p{ margin-bottom:1.3em; line-height:1.75; }
  .back-link{ font-family:var(--font-mono); font-size:.78rem; letter-spacing:.08em; text-transform:uppercase; display:inline-flex; gap:8px; margin-bottom:26px; }
  .attach-download{ margin-top:20px; }
</style>
</head>
<body>

<header class="site-header -solid">
  <a href="index.html" class="logo">architettopotenza.it</a>
  <nav class="main-nav">
    <ul>
      <li><a href="index.html">Home</a></li>
      <li><a href="impresa.html">Azienda</a></li>
      <li><a href="tecnica.html">Ufficio Tecnico</a></li>
      <li><a href="news.php" class="-active">News</a></li>
      <li><a href="lavora-con-noi.html">Lavora con noi</a></li>
      <li><a href="preventivo.html">Preventivo</a></li>
      <li><a href="contatti.html">Contatti</a></li>
    </ul>
  </nav>
  <button class="nav-toggle" aria-label="Apri menu"><span></span><span></span><span></span></button>
</header>

<?php if ($articolo): ?>

  <div class="article-hero" style="background-image:url('<?= escape($articolo['immagine'] ?? '') ?>');"></div>

  <section class="section">
    <div class="container article-body">
      <a class="back-link" href="news.php">← Tutte le news</a>
      <div class="news-date"><?= escape(formatDataIt($articolo['data'] ?? '', $mesi)) ?></div>
      <h1 class="section-title" style="margin-top:8px;"><?= escape($articolo['titolo']) ?></h1>
      <div style="margin-top:30px; font-size:1.05rem; color:var(--c-ink);">
        <?php foreach (preg_split('/\r\n|\r|\n/', trim($articolo['testo'] ?? '')) as $paragrafo): ?>
          <?php if (trim($paragrafo) !== ''): ?>
            <p><?= escape($paragrafo) ?></p>
          <?php endif; ?>
        <?php endforeach; ?>
      </div>

      <?php if (!empty($articolo['allegato'])): ?>
        <a class="btn attach-download" href="<?= escape($articolo['allegato']) ?>" download>
          📎 Scarica <?= escape($articolo['allegato_nome'] ?: 'il documento allegato') ?>
        </a>
      <?php endif; ?>
    </div>
  </section>

<?php else: ?>

  <section class="section" style="padding-top:170px;">
    <div class="container">
      <div class="eyebrow">News</div>
      <h1 class="section-title">News non trovata</h1>
      <p class="section-lead">Questa news potrebbe essere stata rimossa o rimessa in bozza.</p>
      <a class="btn" href="news.php" style="margin-top:26px;">← Torna a tutte le news</a>
    </div>
  </section>

<?php endif; ?>

<footer class="site-footer">
  <div class="container">
    <div class="footer-grid">
      <div>
        <h4>STUDIO FPARCHITETTO</h4>
        <p style="color:var(--c-base-2); max-width:280px; font-size:.92rem;">Un'unica realtà, due anime: l'azienda che costruisce e l'ufficio tecnico che progetta.</p>
      </div>
      <div>
        <h4>REPARTI</h4>
        <a href="impresa.html">Azienda</a>
        <a href="tecnica.html">Ufficio Tecnico</a>
        <a href="news.php">News</a>
      </div>
      <div>
        <h4>STUDIO</h4>
        <a href="lavora-con-noi.html">Lavora con noi</a>
        <a href="preventivo.html">Richiedi un preventivo</a>
        <a href="impresa.html#chi-siamo">Chi siamo</a>
      </div>
      <div>
        <h4>CONTATTI</h4>
        <a href="mailto:info@fparchitetto.it">info@fparchitetto.it</a>
        <a href="tel:+390882421153">0882 421153</a>
        <a href="contatti.html">Vieni a trovarci →</a>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© 2026 Studio FParchitetto — Power Building P.IVA 03977340714 · Fp Architetto P.IVA 04065840714 — Corso Garibaldi, 20, 71011 Apricena (FG)</span>
      <span>Contenuti e foto di esempio, da sostituire</span>
    </div>
  </div>
</footer>

<script src="assets/js/main.js"></script>
</body>
</html>
