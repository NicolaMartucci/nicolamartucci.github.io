<?php
/**
 * genera_pagine_statiche.php
 * ============================================================
 * Genera pagine HTML pre-renderizzate per notizie, eventi e locali
 * di tuttoapricena.it, in modo che Google possa indicizzarle
 * senza dipendere da JavaScript.
 *
 * UTILIZZO:
 *   php genera_pagine_statiche.php
 *
 * Esegui dalla root del sito (dove si trova la cartella admin/).
 * Lo script sovrascrive i file index.html esistenti con versioni
 * che contengono il contenuto completo nel DOM (SEO-friendly).
 * Il JavaScript di rendering rimane incluso per il funzionamento
 * lato utente (aggiornamenti live, interattività).
 * ============================================================
 */

define('BASE_URL', 'https://www.tuttoapricena.it');
define('DATA_DIR',  __DIR__ . '/admin/data');
define('ROOT_DIR',  __DIR__);

// ── Carica i dati JSON ────────────────────────────────────────

function loadJson(string $file): array {
    $path = DATA_DIR . '/' . $file;
    if (!file_exists($path)) {
        echo "⚠️  File non trovato: $path\n";
        return [];
    }
    $data = json_decode(file_get_contents($path), true);
    return is_array($data) ? $data : [];
}

$notizie = loadJson('notizie.json');
$eventi  = loadJson('eventi.json');
$locali  = loadJson('locali.json');

// ── Utilità ───────────────────────────────────────────────────

function e(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

function formatDate(string $date): string {
    $ts = strtotime($date);
    if (!$ts) return $date;
    $mesi = ['','gennaio','febbraio','marzo','aprile','maggio','giugno',
             'luglio','agosto','settembre','ottobre','novembre','dicembre'];
    return intval(date('j', $ts)) . ' ' . $mesi[intval(date('n', $ts))] . ' ' . date('Y', $ts);
}

function textToParagraphs(string $text): string {
    $paras = preg_split('/\n\n+/', trim($text));
    $html  = '';
    foreach ($paras as $p) {
        $p = trim($p);
        if ($p !== '') $html .= '<p>' . e($p) . '</p>';
    }
    return $html ?: '<p>' . e($text) . '</p>';
}

function writeFile(string $path, string $content): void {
    $dir = dirname($path);
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
    file_put_contents($path, $content);
    echo "✅  Generato: " . str_replace(ROOT_DIR . '/', '', $path) . "\n";
}

// ── Template HTML comune ──────────────────────────────────────

function htmlShell(
    string $title,
    string $description,
    string $canonical,
    string $ogImage,
    string $ogType,
    string $ldJson,
    string $bodyContent,
    string $assetsBase,    // es. "../../"
    string $jsSection,     // 'notizie' | 'eventi' | 'locali'
    string $schemaScript = ''
): string {
    $ogImageMeta = $ogImage
        ? '<meta property="og:image" content="' . e($ogImage) . '"/>' . "\n  "
        : '';

    return <<<HTML
<!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8"/>
  <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link rel="stylesheet" media="print" onload="this.media='all'" href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,800;0,900;1,700&family=Inter:wght@400;600;700;800&display=swap"/>
  <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,800;0,900;1,700&family=Inter:wght@400;600;700;800&display=swap"/></noscript>
  <meta name="viewport" content="width=device-width,initial-scale=1.0"/>
  <title>{$title}</title>
  <meta name="description" content="{$description}"/>
  <link rel="canonical" href="{$canonical}"/>
  <meta property="og:title" content="{$title}"/>
  <meta property="og:description" content="{$description}"/>
  <meta property="og:url" content="{$canonical}"/>
  <meta property="og:type" content="{$ogType}"/>
  {$ogImageMeta}<meta property="og:site_name" content="TuttoApricena"/>
  <script type="application/ld+json">{$ldJson}</script>
  <link rel="stylesheet" href="{$assetsBase}assets/css/style.css"/>
  <script src="{$assetsBase}assets/js/lucide-mini.js"></script>
  <link rel="stylesheet" href="{$assetsBase}assets/css/responsive.css"/>
</head>
<body>
<header id="navbar">
  <div class="navbar-inner"><div class="navbar-row">
    <a href="{$assetsBase}" class="navbar-logo"><div class="navbar-logo-icon"><span>TA</span></div><span class="navbar-logo-text">Tutto<em>Apricena</em></span></a>
    <nav class="navbar-links">
      <a href="{$assetsBase}notizie/">Notizie</a>
      <a href="{$assetsBase}eventi/">Eventi</a>
      <a href="{$assetsBase}farmacie/">Farmacie</a>
      <a href="{$assetsBase}servizi/">Servizi</a>
      <a href="{$assetsBase}locali/">Locali</a>
      <a href="{$assetsBase}sponsor/">Sponsor</a>
    </nav>
    <div class="navbar-actions">
      <a href="{$assetsBase}chi-siamo/" class="link-plain">Chi siamo</a>
      <a href="{$assetsBase}contatti/" class="link-btn">Contatti</a>
    </div>
    <button id="mobile-menu-btn" class="mobile-menu-btn" aria-label="Menu">
      <i data-lucide="menu" class="icon-hamburger" width="24" height="24"></i>
      <i data-lucide="x" class="icon-x" width="24" height="24" style="display:none"></i>
    </button>
  </div></div>
  <div id="mobile-menu" class="mobile-menu"><div class="mobile-menu-inner">
    <nav class="mobile-nav">
      <a href="{$assetsBase}notizie/">Notizie</a><a href="{$assetsBase}eventi/">Eventi</a>
      <a href="{$assetsBase}farmacie/">Farmacie</a><a href="{$assetsBase}servizi/">Servizi</a>
      <a href="{$assetsBase}locali/">Locali</a><a href="{$assetsBase}sponsor/">Sponsor</a>
    </nav>
    <a href="{$assetsBase}contatti/" class="mobile-cta">Contattaci</a>
  </div></div>
</header>
<main id="pg-main" style="min-height:80vh">
{$bodyContent}
</main>
<footer>
  <div class="footer-inner"><div class="footer-grid">
    <div class="footer-brand"><a href="{$assetsBase}" class="navbar-logo"><div class="navbar-logo-icon"><span>TA</span></div><span class="navbar-logo-text">Tutto<em>Apricena</em></span></a><p>Il portale informativo di Apricena.</p></div>
    <div class="footer-col"><h4>Sezioni</h4><ul><li><a href="{$assetsBase}notizie/">Notizie</a></li><li><a href="{$assetsBase}eventi/">Eventi</a></li><li><a href="{$assetsBase}locali/">Locali</a></li></ul></div>
    <div class="footer-col"><h4>Info</h4><ul><li><a href="{$assetsBase}chi-siamo/">Chi Siamo</a></li><li><a href="{$assetsBase}contatti/">Contatti</a></li><li><a href="{$assetsBase}privacy/">Privacy</a></li></ul></div>
  </div><div class="footer-bottom"><p>&copy; <span id="year"></span> TuttoApricena</p></div></div>
</footer>
<script src="{$assetsBase}assets/js/data.js"></script>
<script src="{$assetsBase}assets/js/main.js"></script>
<script src="{$assetsBase}assets/js/slug-renderer.js"></script>
<script>
  document.getElementById('year').textContent = new Date().getFullYear();
  // Il renderer JS aggiorna la pagina lato client se necessario
  if (typeof TARenderer !== 'undefined') TARenderer.render('{$jsSection}');
  lucide.createIcons();
</script>
<script src="{$assetsBase}assets/js/protect.js"></script>
</body>
</html>
HTML;
}

// ── Genera pagine NOTIZIE ─────────────────────────────────────

echo "\n📰 Generazione pagine NOTIZIE...\n";

foreach ($notizie as $n) {
    $slug = trim($n['slug'] ?? '');
    if ($slug === '') {
        echo "⏭️  Notizia senza slug, saltata: " . ($n['titolo'] ?? '?') . "\n";
        continue;
    }

    $titolo      = $n['titolo']    ?? '';
    $sommario    = $n['sommario']  ?? '';
    $testo       = $n['testo']     ?? $sommario;
    $data        = $n['data']      ?? '';
    $categoria   = $n['categoria'] ?? 'Notizia';
    $fonte       = $n['fonte']     ?? '';
    $foto        = $n['foto']      ?? '';
    $evidenza    = !empty($n['evidenza']);

    $canonical   = BASE_URL . '/notizie/' . $slug . '/';
    $description = mb_substr($sommario, 0, 155);

    $ld = json_encode([
        '@context'    => 'https://schema.org',
        '@type'       => 'NewsArticle',
        'headline'    => $titolo,
        'description' => $sommario,
        'datePublished' => $data,
        'image'       => $foto ?: null,
        'publisher'   => ['@type' => 'Organization', 'name' => 'TuttoApricena', 'url' => BASE_URL],
        'url'         => $canonical,
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    $testoHtml   = textToParagraphs($testo);
    $dataFmt     = $data ? formatDate($data) : '';
    $evidenzaBadge = $evidenza
        ? '<span style="background:rgba(232,168,56,.15);color:var(--color-accent);font-size:10px;font-weight:700;padding:3px 10px;border-radius:50px">In evidenza</span>'
        : '';
    $fonteBlock  = $fonte
        ? '<div class="source-box"><i data-lucide="external-link" width="16" height="16" color="var(--color-accent)"></i><span>Fonte: <strong>' . e($fonte) . '</strong></span></div>'
        : '';
    $imgBlock    = $foto
        ? '<img class="article-img" src="' . e($foto) . '" alt="' . e($titolo) . '" loading="lazy" onerror="this.style.display=\'none\'">'
        : '';

    $body = <<<HTML
<div class="article-header"><div class="article-header-inner">
  <a href="../../notizie/" class="back-link"><i data-lucide="arrow-left" width="16" height="16"></i> Tutte le notizie</a>
  <div class="article-meta">
    <span class="cat-badge">{$categoria}</span>
    {$evidenzaBadge}
  </div>
  <h1 class="article-title">{$titolo}</h1>
  <p class="article-abstract">{$sommario}</p>
  <div class="article-info"><i data-lucide="calendar" width="13" height="13"></i> {$dataFmt}· {$fonte}</div>
</div></div>
<div class="article-body">
  {$imgBlock}
  <div class="prose-content">{$testoHtml}</div>
  {$fonteBlock}
</div>
HTML;

    $html = htmlShell(
        e($titolo) . ' — TuttoApricena',
        e($description),
        $canonical,
        $foto,
        'article',
        $ld,
        $body,
        '../../',
        'notizie'
    );

    writeFile(ROOT_DIR . '/notizie/' . $slug . '/index.html', $html);
}

// ── Genera pagine EVENTI ──────────────────────────────────────

echo "\n🎉 Generazione pagine EVENTI...\n";

foreach ($eventi as $ev) {
    $slug = trim($ev['slug'] ?? '');
    if ($slug === '') {
        echo "⏭️  Evento senza slug, saltato: " . ($ev['titolo'] ?? '?') . "\n";
        continue;
    }

    $titolo      = $ev['titolo']      ?? '';
    $descrizione = $ev['descrizione'] ?? '';
    $dataInizio  = $ev['data']        ?? '';
    $ora         = $ev['ora']         ?? '';
    $luogo       = $ev['luogo']       ?? '';
    $categoria   = $ev['categoria']   ?? 'Evento';
    $foto        = $ev['foto']        ?? '';
    $linkBiglietti = $ev['link_biglietti'] ?? '';

    $canonical   = BASE_URL . '/eventi/' . $slug . '/';
    $description = mb_substr($descrizione, 0, 155);
    $dataFmt     = $dataInizio ? formatDate($dataInizio) : '';

    $ld = json_encode([
        '@context'  => 'https://schema.org',
        '@type'     => 'Event',
        'name'      => $titolo,
        'description' => $descrizione,
        'startDate' => $dataInizio,
        'location'  => ['@type' => 'Place', 'name' => $luogo, 'address' => $luogo . ', Apricena (FG)'],
        'image'     => $foto ?: null,
        'url'       => $canonical,
        'organizer' => ['@type' => 'Organization', 'name' => 'TuttoApricena', 'url' => BASE_URL],
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    $imgBlock    = $foto
        ? '<img class="article-img" src="' . e($foto) . '" alt="' . e($titolo) . '" loading="lazy" onerror="this.style.display=\'none\'">'
        : '';
    $ticketBlock = $linkBiglietti
        ? '<div style="margin-top:24px"><a href="' . e($linkBiglietti) . '" rel="noopener noreferrer" class="btn-primary" style="display:inline-flex;align-items:center;gap:8px;font-size:15px;padding:13px 28px"><i data-lucide="ticket" width="18" height="18"></i> Acquista biglietti</a></div>'
        : '';
    $descHtml = textToParagraphs($descrizione);

    $body = <<<HTML
<div class="article-header"><div class="article-header-inner">
  <a href="../../eventi/" class="back-link"><i data-lucide="arrow-left" width="16" height="16"></i> Tutti gli eventi</a>
  <div class="article-meta"><span class="cat-badge" style="background:var(--color-accent);color:var(--color-primary)">{$categoria}</span></div>
  <h1 class="article-title">{$titolo}</h1>
  <div class="article-info">
    <i data-lucide="calendar" width="13" height="13"></i> {$dataFmt}
    &middot; <i data-lucide="clock" width="13" height="13"></i> {$ora}
    &middot; <i data-lucide="map-pin" width="13" height="13"></i> {$luogo}
  </div>
</div></div>
<div class="article-body">
  {$imgBlock}
  <div class="prose-content">{$descHtml}</div>
  <div style="background:#fff;border-radius:16px;padding:24px;margin-top:28px;box-shadow:0 4px 20px rgba(26,26,46,.07);display:grid;grid-template-columns:repeat(auto-fit,minmax(150px,1fr));gap:18px">
    <div><div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--color-text-muted);margin-bottom:4px">Data</div><strong>{$dataFmt}</strong></div>
    <div><div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--color-text-muted);margin-bottom:4px">Orario</div><strong>{$ora}</strong></div>
    <div><div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--color-text-muted);margin-bottom:4px">Luogo</div><strong>{$luogo}</strong></div>
  </div>
  {$ticketBlock}
</div>
HTML;

    $html = htmlShell(
        e($titolo) . ' — TuttoApricena',
        e($description),
        $canonical,
        $foto,
        'event',
        $ld,
        $body,
        '../../',
        'eventi'
    );

    writeFile(ROOT_DIR . '/eventi/' . $slug . '/index.html', $html);
}

// ── Genera pagine LOCALI ──────────────────────────────────────

echo "\n🏪 Generazione pagine LOCALI...\n";

foreach ($locali as $l) {
    $slug = trim($l['slug'] ?? '');
    if ($slug === '') {
        echo "⏭️  Locale senza slug, saltato: " . ($l['nome'] ?? '?') . "\n";
        continue;
    }

    $nome        = $l['nome']        ?? '';
    $tipo        = $l['tipo']        ?? 'Locale';
    $descrizione = $l['descrizione'] ?? '';
    $indirizzo   = $l['indirizzo']   ?? '';
    $telefono    = $l['telefono']    ?? '';
    $sitoWeb     = $l['sito_web']    ?? '';
    $orario      = $l['orario']      ?? '';
    $foto        = $l['foto']        ?? '';

    $canonical   = BASE_URL . '/locali/' . $slug . '/';
    $description = $descrizione
        ? mb_substr($descrizione, 0, 155)
        : $tipo . ' ad Apricena (FG). ' . $indirizzo;

    $ld = json_encode([
        '@context'    => 'https://schema.org',
        '@type'       => 'LocalBusiness',
        'name'        => $nome,
        'description' => $descrizione,
        'address'     => [
            '@type'           => 'PostalAddress',
            'streetAddress'   => $indirizzo,
            'addressLocality' => 'Apricena',
            'addressRegion'   => 'FG',
            'postalCode'      => '71011',
            'addressCountry'  => 'IT',
        ],
        'telephone' => $telefono ?: null,
        'image'     => $foto ?: null,
        'sameAs'    => $sitoWeb ?: null,
        'url'       => $canonical,
    ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

    $imgBlock = $foto
        ? '<img class="article-img" src="' . e($foto) . '" alt="' . e($nome) . '" loading="lazy" onerror="this.style.display=\'none\'">'
        : '';
    $telBlock = $telefono
        ? '<a href="tel:' . e($telefono) . '" class="btn-primary" style="font-size:14px;padding:11px 24px"><i data-lucide="phone" width="15" height="15"></i> Chiama</a>'
        : '';
    $mapsUrl  = 'https://maps.google.com/?q=' . urlencode($nome . ', Apricena FG');
    $infoGrid = '';
    if ($indirizzo) $infoGrid .= '<div><div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--color-text-muted);margin-bottom:4px">Indirizzo</div><strong>' . e($indirizzo) . '</strong></div>';
    if ($orario)    $infoGrid .= '<div><div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--color-text-muted);margin-bottom:4px">Orari</div><strong>' . e($orario) . '</strong></div>';
    if ($telefono)  $infoGrid .= '<div><div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--color-text-muted);margin-bottom:4px">Telefono</div><a href="tel:' . e($telefono) . '" style="color:var(--color-accent);font-weight:700;font-size:15px">' . e($telefono) . '</a></div>';
    if ($sitoWeb)   $infoGrid .= '<div><div style="font-size:10px;font-weight:700;text-transform:uppercase;letter-spacing:.1em;color:var(--color-text-muted);margin-bottom:4px">Sito web</div><a href="' . e($sitoWeb) . '" target="_blank" rel="noopener" style="color:var(--color-accent);font-weight:700">Visita &rarr;</a></div>';

    $body = <<<HTML
<div class="article-header"><div class="article-header-inner">
  <a href="../../locali/" class="back-link"><i data-lucide="arrow-left" width="16" height="16"></i> Tutti i locali</a>
  <div class="article-meta"><span class="cat-badge" style="background:var(--color-primary)">{$tipo}</span></div>
  <h1 class="article-title">{$nome}</h1>
  <div class="article-info"><i data-lucide="map-pin" width="13" height="13"></i> {$indirizzo}</div>
</div></div>
<div class="article-body">
  {$imgBlock}
  <div class="prose-content"><p style="font-size:17px;line-height:1.8">{$descrizione}</p></div>
  <div style="background:#fff;border-radius:16px;padding:24px;margin-top:28px;box-shadow:0 4px 20px rgba(26,26,46,.07);display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:20px">
    {$infoGrid}
  </div>
  <div style="margin-top:20px;display:flex;gap:12px;flex-wrap:wrap">
    {$telBlock}
    <a href="{$mapsUrl}" target="_blank" rel="noopener" class="btn-outline" style="font-size:14px;padding:11px 24px;background:var(--color-primary);border-color:var(--color-primary)"><i data-lucide="map-pin" width="15" height="15"></i> Apri in Maps</a>
  </div>
</div>
HTML;

    $html = htmlShell(
        e($nome) . ' — ' . e($tipo) . ' ad Apricena | TuttoApricena',
        e($description),
        $canonical,
        $foto,
        'website',
        $ld,
        $body,
        '../../',
        'locali'
    );

    writeFile(ROOT_DIR . '/locali/' . $slug . '/index.html', $html);
}

// ── Riepilogo ─────────────────────────────────────────────────

echo "\n✅ Completato! Esegui di nuovo dopo ogni modifica ai dati JSON.\n";
echo "   Ricorda di eseguire dalla root del sito: php genera_pagine_statiche.php\n\n";
