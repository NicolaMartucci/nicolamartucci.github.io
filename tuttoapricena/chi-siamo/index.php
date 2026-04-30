<?php
// Legge chiSiamo.json direttamente dal server — nessuna dipendenza da data.js o pubblica.php
$jsonPath = __DIR__ . '/../admin/data/chiSiamo.json';
$cs = [];
if (file_exists($jsonPath)) {
    $decoded = json_decode(file_get_contents($jsonPath), true);
    if (is_array($decoded)) {
        $cs = $decoded;
    }
}

// Helper: restituisce il valore dal JSON oppure il fallback
function cs($key, $fallback = '') {
    global $cs;
    return isset($cs[$key]) && $cs[$key] !== '' ? htmlspecialchars($cs[$key], ENT_QUOTES, 'UTF-8') : $fallback;
}

// Immagine: se salvata dall'admin usa quella, altrimenti il placeholder Unsplash
$immagine = isset($cs['immagine']) && $cs['immagine'] !== ''
    ? htmlspecialchars($cs['immagine'], ENT_QUOTES, 'UTF-8')
    : 'https://images.unsplash.com/photo-1555992336-03a23c7b20ee?w=700&q=80';

// storia_p1 e storia_p2 possono contenere HTML (innerHTML nel JS originale)
function csHTML($key, $fallback = '') {
    global $cs;
    return isset($cs[$key]) && $cs[$key] !== '' ? $cs[$key] : $fallback;
}
?><!DOCTYPE html>
<html lang="it">
<head>
  <meta charset="UTF-8"/>
  <link rel="icon" type="image/x-icon" href="/favicon.ico" />
  <link rel="icon" type="image/svg+xml" href="/images/favicon.svg" />
  <link rel="icon" type="image/png" sizes="32x32" href="/images/icon-32.png" />
  <link rel="icon" type="image/png" sizes="96x96" href="/images/icon-96.png" />
  <link rel="apple-touch-icon" sizes="180x180" href="/images/icon-180.png" />
  <link rel="manifest" href="/site.webmanifest" />
  <meta name="theme-color" content="#E8A838" />
  <meta name="viewport" content="width=device-width,initial-scale=1.0" />
  <title>Chi Siamo — TuttoApricena, il portale di Apricena (FG)</title>
  <link rel="preconnect" href="https://fonts.googleapis.com" crossorigin/>
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin/>
  <link rel="stylesheet" media="print" onload="this.media='all'" href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,800;0,900;1,700&family=Inter:wght@400;600;700;800&display=swap"/>
  <noscript><link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Playfair+Display:ital,wght@0,700;0,800;0,900;1,700&family=Inter:wght@400;600;700;800&display=swap"/></noscript>
  <meta name="description" content="TuttoApricena è il portale informativo indipendente di Apricena (FG). Scopri chi siamo e la nostra missione per la comunità di Apricena." />
  <meta name="keywords" content="TuttoApricena chi siamo, portale Apricena, informazione Apricena" />
  <meta name="robots" content="index, follow" />
  <link rel="canonical" href="https://www.tuttoapricena.it/chi-siamo/" />
  <meta property="og:type" content="website" />
  <meta property="og:title" content="Chi Siamo — TuttoApricena, il portale di Apricena (FG)" />
  <meta property="og:description" content="TuttoApricena è il portale informativo indipendente di Apricena (FG). Scopri chi siamo e la nostra missione per la comunità di Apricena." />
  <meta property="og:url" content="https://www.tuttoapricena.it/chi-siamo/" />
  <meta property="og:image" content="https://www.tuttoapricena.it/images/og/citta-apricena.jpg" />
  <link rel="stylesheet" href="../assets/css/style.css" />
  <style>.about-grid{display:grid;grid-template-columns:1fr 1fr;gap:40px;margin-bottom:48px;align-items:center}.values-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:20px;margin-bottom:48px}.collab-section{background:var(--color-primary);border-radius:20px;padding:48px;display:flex;align-items:center;justify-content:space-between;gap:28px;flex-wrap:wrap;margin-top:48px}.collab-text h2{font-family:var(--font-display);font-size:clamp(1.5rem,3vw,2rem);color:#fff;margin-bottom:8px}.collab-text p{color:rgba(255,255,255,.65);font-size:15px;max-width:480px;line-height:1.7}@media(max-width:768px){.about-grid,.values-grid{grid-template-columns:1fr !important}.collab-section{flex-direction:column}.collab-section a{width:100%;text-align:center;justify-content:center}}</style>
  <link rel="stylesheet" media="print" onload="this.media='all'" href="../assets/css/responsive.css" />
  <noscript><link rel="stylesheet" href="../assets/css/responsive.css" /></noscript>
</head>
<body class="page-chi-siamo">
<header id="navbar">
  <div class="navbar-inner"><div class="navbar-row">
    <a href="../" class="navbar-logo"><div class="navbar-logo-icon" id="header-logo"><span>TA</span></div><span class="navbar-logo-text">Tutto<em>Apricena</em></span></a>
    <nav class="navbar-links"><a href="../notizie/">Notizie</a><a href="../eventi/">Eventi</a><a href="../farmacie/">Farmacie</a><a href="../servizi/">Servizi</a><a href="../locali/">Locali</a><a href="../sponsor/">Sponsor</a></nav>
    <div class="navbar-actions"><a href="../chi-siamo/" class="link-plain" style="color:var(--color-accent)">Chi siamo</a><a href="../contatti/" class="link-btn">Contatti</a></div>
    <button id="mobile-menu-btn" class="mobile-menu-btn" aria-label="Menu"><i data-lucide="menu" class="icon-hamburger" width="24" height="24"></i><i data-lucide="x" class="icon-x" width="24" height="24" style="display:none"></i></button>
  </div></div>
  <div id="mobile-menu" class="mobile-menu"><div class="mobile-menu-inner">
    <nav class="mobile-nav"><a href="../notizie/">Notizie</a><a href="../eventi/">Eventi</a><a href="../farmacie/">Farmacie</a><a href="../servizi/">Servizi</a><a href="../locali/">Locali</a><a href="../sponsor/">Sponsor</a></nav>
    <a href="../contatti/" class="mobile-cta">Contattaci</a>
  </div></div>
</header>
<main class="main-content">
  <div class="page-header"><div class="page-header-inner">
    <em id="cs-eyebrow"><?= cs('eyebrow', 'Il progetto') ?></em>
    <h1 id="cs-title"><?= cs('titolo', 'Chi Siamo') ?></h1>
    <p id="cs-subtitle"><?= cs('sottotitolo', 'Il portale informativo indipendente di Apricena') ?></p>
  </div></div>
  <div class="container-narrow content-section">

    <div class="about-grid">
      <div>
        <!-- ✅ Immagine iniettata direttamente da PHP — nessun JS necessario -->
        <img loading="lazy" id="cs-img"
          src="<?= $immagine ?>"
          alt="<?= cs('immagine_alt', 'Apricena') ?>"
          style="width:100%;height:280px;object-fit:cover;border-radius:16px;" />
      </div>
      <div style="display:flex;flex-direction:column;justify-content:center">
        <span class="section-eyebrow">La nostra storia</span>
        <h2 style="font-family:var(--font-display);font-size:1.8rem;font-weight:800;color:var(--color-primary);margin-bottom:16px" id="cs-story-title"><?= cs('storia_titolo', 'Nasce dalla passione per Apricena') ?></h2>
        <p style="color:var(--color-text-muted);line-height:1.8;margin-bottom:12px" id="cs-story-p1"><?= csHTML('storia_p1', 'TuttoApricena è il portale informativo indipendente dedicato alla città di Apricena (FG) e al suo territorio. Nato dalla passione di un gruppo di cittadini apricenesi, il progetto ha l\'obiettivo di tenere informata la comunità locale su notizie, eventi, servizi e tutto ciò che accade in città.') ?></p>
        <p style="color:var(--color-text-muted);line-height:1.8" id="cs-story-p2"><?= csHTML('storia_p2', 'Non siamo un organo di stampa ufficiale, ma un progetto editoriale indipendente che aggrega e presenta informazioni utili per chi vive, lavora o visita Apricena.') ?></p>
      </div>
    </div>

    <div class="values-grid">
      <div style="background:#fff;border-radius:14px;padding:24px;box-shadow:0 2px 12px rgba(26,26,46,.06);text-align:center">
        <div style="width:52px;height:52px;border-radius:14px;background:rgba(232,168,56,.12);display:flex;align-items:center;justify-content:center;margin:0 auto 14px"><i data-lucide="newspaper" width="24" height="24" color="var(--color-accent)"></i></div>
        <h3 style="font-family:var(--font-display);font-size:17px;color:var(--color-primary);margin-bottom:6px"><?= cs('val1_titolo', 'Informazione locale') ?></h3>
        <p style="color:var(--color-text-muted);font-size:13px;line-height:1.6"><?= cs('val1_testo', 'Notizie, eventi e aggiornamenti rilevanti per la comunità apricenese.') ?></p>
      </div>
      <div style="background:#fff;border-radius:14px;padding:24px;box-shadow:0 2px 12px rgba(26,26,46,.06);text-align:center">
        <div style="width:52px;height:52px;border-radius:14px;background:rgba(232,168,56,.12);display:flex;align-items:center;justify-content:center;margin:0 auto 14px"><i data-lucide="users" width="24" height="24" color="var(--color-accent)"></i></div>
        <h3 style="font-family:var(--font-display);font-size:17px;color:var(--color-primary);margin-bottom:6px"><?= cs('val2_titolo', 'Comunità') ?></h3>
        <p style="color:var(--color-text-muted);font-size:13px;line-height:1.6"><?= cs('val2_testo', 'Un punto di riferimento per tutti i cittadini e per chi ha a cuore Apricena.') ?></p>
      </div>
      <div style="background:#fff;border-radius:14px;padding:24px;box-shadow:0 2px 12px rgba(26,26,46,.06);text-align:center">
        <div style="width:52px;height:52px;border-radius:14px;background:rgba(232,168,56,.12);display:flex;align-items:center;justify-content:center;margin:0 auto 14px"><i data-lucide="shield-check" width="24" height="24" color="var(--color-accent)"></i></div>
        <h3 style="font-family:var(--font-display);font-size:17px;color:var(--color-primary);margin-bottom:6px"><?= cs('val3_titolo', 'Indipendenza') ?></h3>
        <p style="color:var(--color-text-muted);font-size:13px;line-height:1.6"><?= cs('val3_testo', 'Progetto editoriale indipendente, senza affiliazioni politiche o istituzionali.') ?></p>
      </div>
    </div>

    <!-- Collabora con noi -->
    <div class="collab-section">
      <div class="collab-text">
        <h2><?= cs('collabora_titolo', 'Vuoi collaborare con noi?') ?></h2>
        <p><?= csHTML('collabora_testo', 'Se sei un giornalista, un fotoreporter, un blogger o semplicemente un apricenese con voglia di raccontare la tua città, scrivici. TuttoApricena è un progetto aperto alla comunità.') ?></p>
      </div>
      <a href="../contatti/" class="btn-primary" style="flex-shrink:0">Contattaci <i data-lucide="arrow-right" width="17" height="17"></i></a>
    </div>

  </div>
</main>
<footer><div class="footer-inner"><div class="footer-grid">
  <div class="footer-brand"><a href="../" class="navbar-logo"><div class="navbar-logo-icon"><span>TA</span></div><span class="navbar-logo-text">Tutto<em>Apricena</em></span></a><p>Il portale informativo indipendente di Apricena.</p><div class="footer-social"><a href="https://www.facebook.com/profile.php?id=61578750476699" aria-label="Facebook" target="_blank" rel="noopener noreferrer"><svg xmlns="http://www.w3.org/2000/svg" width="15" height="15" viewBox="0 0 24 24" fill="currentColor"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg></a><a href="mailto:info@tuttoapricena.it" aria-label="Email info@tuttoapricena.it"><i data-lucide="mail" width="15" height="15"></i></a></div></div>
  <div class="footer-col"><h4>Sezioni</h4><ul><li><a href="../notizie/">Notizie</a></li><li><a href="../eventi/">Eventi</a></li><li><a href="../farmacie/">Farmacie</a></li><li><a href="../servizi/">Servizi</a></li><li><a href="../locali/">Locali</a></li><li><a href="../sponsor/">Sponsor</a></li></ul></div>
  <div class="footer-col"><h4>Info</h4><ul><li><a href="../chi-siamo/">Chi Siamo</a></li><li><a href="../contatti/">Contatti</a></li><li><a href="../privacy/">Privacy Policy</a></li><li><a href="../cookie/">Cookie Policy</a></li></ul></div>
</div><div class="footer-bottom"><p>© <span id="year"></span> TuttoApricena</p></div></div></footer>

<script src="../assets/js/lucide-mini.js"></script>
<script src="../assets/js/data.js"></script>
<script src="../assets/js/main.js"></script>
<script>
document.getElementById('year').textContent = new Date().getFullYear();
// Logo dinamico dalla config (invariato)
if (TA.config && TA.config.logoUrl) {
    var li = document.getElementById('header-logo');
    if (li) { li.innerHTML = '<img loading="lazy" src="' + TA.config.logoUrl + '" style="width:100%;height:100%;object-fit:contain;padding:3px;border-radius:50%">'; }
}
lucide.createIcons();
</script>
<script src="../assets/js/protect.js"></script>
</body>
</html>
