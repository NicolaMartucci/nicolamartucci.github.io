<?php
// ============================================================
// NEWS — pagina pubblica, legge data/news.json (scritto dal CMS in /admin)
// Mostra solo le news con stato "pubblicato", più recenti in cima.
// ============================================================
$jsonPath = __DIR__ . '/data/news.json';
$news = [];
if (file_exists($jsonPath)) {
    $raw = file_get_contents($jsonPath);
    $decoded = json_decode($raw, true);
    if (is_array($decoded)) { $news = $decoded; }
}

// solo pubblicate, ordinate per data decrescente
$news = array_filter($news, fn($n) => ($n['stato'] ?? '') === 'pubblicato');
usort($news, fn($a, $b) => strcmp($b['data'] ?? '', $a['data'] ?? ''));

$mesi = [1=>'gen','feb','mar','apr','mag','giu','lug','ago','set','ott','nov','dic'];
function formatDataIt($dataStr, $mesi) {
    $ts = strtotime($dataStr);
    if (!$ts) return $dataStr;
    return date('d', $ts) . ' ' . $mesi[(int)date('n', $ts)] . ' ' . date('Y', $ts);
}
function escape($s) { return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }
function anteprimaTesto(string $testo, int $len = 140): string {
    if (function_exists('mb_strlen')) {
        return mb_strlen($testo) > $len ? mb_substr($testo, 0, $len) . '…' : $testo;
    }
    return strlen($testo) > $len ? substr($testo, 0, $len) . '…' : $testo;
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>News — Studio Arké</title>
<meta name="description" content="Novità dai cantieri e dallo studio tecnico di Studio Arké.">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Barlow+Condensed:wght@500;600;700&family=Barlow:wght@400;500;600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>

<header class="site-header -solid">
  <a href="index.html" class="logo">ARK<span>É</span></a>
  <nav class="main-nav">
    <ul>
      <li><a href="impresa.html">Impresa</a></li>
      <li><a href="tecnica.html">Parte Tecnica</a></li>
      <li><a href="news.php" class="-active">News</a></li>
      <li><a href="lavora-con-noi.html">Lavora con noi</a></li>
      <li><a href="preventivo.html">Preventivo</a></li>
      <li><a href="contatti.html">Contatti</a></li>
    </ul>
  </nav>
  <button class="nav-toggle" aria-label="Apri menu"><span></span><span></span><span></span></button>
</header>

<section class="section" style="padding-top:170px;">
  <div class="container">
    <div class="eyebrow">News</div>
    <h1 class="section-title">Dal cantiere e dallo studio</h1>
    <p class="section-lead">Aggiornamenti su opere in corso, ipotesi di progetto e vita dello studio.</p>

    <?php if (empty($news)): ?>
      <div class="news-empty">Nessuna news pubblicata al momento. Torna presto a trovarci.</div>
    <?php else: ?>
      <div class="news-grid">
        <?php foreach ($news as $n): ?>
          <article class="news-card">
            <div class="news-thumb" style="background-image:url('<?= escape($n['immagine'] ?? '') ?>');"></div>
            <div class="news-body">
              <div class="news-date"><?= escape(formatDataIt($n['data'] ?? '', $mesi)) ?></div>
              <h3><?= escape($n['titolo'] ?? '') ?></h3>
              <p><?= escape(anteprimaTesto($n['testo'] ?? '')) ?></p>
            </div>
          </article>
        <?php endforeach; ?>
      </div>
    <?php endif; ?>
  </div>
</section>

<footer class="site-footer">
  <div class="container">
    <div class="footer-grid">
      <div>
        <h4>STUDIO ARKÉ</h4>
        <p style="color:var(--c-base-2); max-width:280px; font-size:.92rem;">Un'unica realtà, due anime: l'impresa che costruisce e lo studio tecnico che progetta.</p>
      </div>
      <div>
        <h4>REPARTI</h4>
        <a href="impresa.html">Impresa</a>
        <a href="tecnica.html">Parte Tecnica</a>
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
        <a href="mailto:info@studioarke.it">info@studioarke.it</a>
        <a href="tel:+390000000000">+39 000 000 0000</a>
        <a href="contatti.html">Vieni a trovarci →</a>
      </div>
    </div>
    <div class="footer-bottom">
      <span>© 2026 Studio Arké — P.IVA 00000000000</span>
      <span>Contenuti e foto di esempio, da sostituire</span>
    </div>
  </div>
</footer>

<script src="assets/js/main.js"></script>
</body>
</html>
