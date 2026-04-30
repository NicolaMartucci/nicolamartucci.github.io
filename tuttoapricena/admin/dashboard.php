<?php
require_once __DIR__ . '/includes/config.php';
$pageTitle = 'Dashboard';
$activeSection = 'dashboard';
$topbarAction = '<a href="/admin/sincronizza.php" class="btn btn-primary btn-sm">🚀 Pubblica sito</a>';
require_once __DIR__ . '/includes/layout.php';

$notizie  = loadData('notizie');
$eventi   = loadData('eventi');
$locali   = loadData('locali');
$sponsor  = loadData('sponsor');
$farmacie = loadData('farmacie');
$servizi  = loadData('servizi');

// Prossimi eventi
$oggi = date('Y-m-d');
$prossimiEventi = array_filter($eventi, function($e) { return ((isset($e['data']) ? $e['data'] : '')) >= $oggi; });
usort($prossimiEventi, function($a,$b) { return strcmp((isset($a['data']) ? $a['data'] : ''), (isset($b['data']) ? $b['data'] : '')); });
$prossimiEventi = array_slice(array_values($prossimiEventi), 0, 5);

// Ultime notizie
$ultimeNotizie = array_reverse($notizie);
$ultimeNotizie = array_slice($ultimeNotizie, 0, 5);

// Farmacia di turno oggi
$dayOfYear = (int)date('z'); // 0-based
$farmaciaOggi = null;
if (!empty($farmacie)) {
    $idx = $dayOfYear % count($farmacie);
    $farmaciaOggi = array_values($farmacie)[$idx];
}
?>

<!-- Stats row -->
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(160px,1fr));gap:16px;margin-bottom:28px;">
<?php
$stats = [
    ['📰', count($notizie), 'Notizie', 'notizie.php'],
    ['📅', count($eventi), 'Eventi', 'eventi.php'],
    ['💊', count($farmacie), 'Farmacie', 'farmacie.php'],
    ['🔧', count($servizi), 'Servizi', 'servizi.php'],
    ['🏠', count($locali), 'Locali', 'locali.php'],
    ['⭐', count($sponsor), 'Sponsor', 'sponsor.php'],
];
foreach ($stats as [$icon, $count, $label, $href]):
?>
<a href="/admin/<?= $href ?>" style="background:var(--dark2);border:1px solid var(--border);border-radius:14px;padding:20px;text-decoration:none;transition:border-color 0.2s;" onmouseover="this.style.borderColor='var(--gold)'" onmouseout="this.style.borderColor='var(--border)'">
    <div style="font-size:24px;margin-bottom:8px;"><?= $icon ?></div>
    <div style="font-family:'Syne',sans-serif;font-size:28px;font-weight:800;color:var(--gold);"><?= $count ?></div>
    <div style="font-size:12px;color:var(--muted);margin-top:2px;"><?= $label ?></div>
</a>
<?php endforeach; ?>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;margin-bottom:20px;">

<!-- Farmacia di turno -->
<div class="card">
    <div class="card-header">
        <span class="card-title">💊 Farmacia di turno oggi</span>
        <a href="/admin/farmacie.php" class="btn btn-secondary btn-sm">Gestisci</a>
    </div>
    <div class="card-body">
        <?php if ($farmaciaOggi): ?>
        <div style="font-size:18px;font-weight:700;font-family:'Syne',sans-serif;margin-bottom:6px;">
            <?= htmlspecialchars((isset($farmaciaOggi['nome']) ? $farmaciaOggi['nome'] : '')) ?>
        </div>
        <div style="font-size:13px;color:var(--text2);">📍 <?= htmlspecialchars((isset($farmaciaOggi['indirizzo']) ? $farmaciaOggi['indirizzo'] : '')) ?></div>
        <div style="font-size:13px;color:var(--text2);">📞 <?= htmlspecialchars((isset($farmaciaOggi['telefono']) ? $farmaciaOggi['telefono'] : '')) ?></div>
        <?php else: ?>
        <p style="color:var(--muted);font-size:13px;">Nessuna farmacia configurata.</p>
        <?php endif; ?>
    </div>
</div>

<!-- Prossimi eventi -->
<div class="card">
    <div class="card-header">
        <span class="card-title">📅 Prossimi eventi</span>
        <a href="/admin/eventi.php" class="btn btn-secondary btn-sm">Tutti</a>
    </div>
    <div class="card-body" style="padding:0;">
        <?php if (empty($prossimiEventi)): ?>
        <p style="padding:20px;color:var(--muted);font-size:13px;">Nessun evento in programma.</p>
        <?php else: ?>
        <?php foreach ($prossimiEventi as $ev): ?>
        <div style="display:flex;align-items:center;gap:12px;padding:12px 20px;border-bottom:1px solid var(--border);">
            <div style="background:var(--dark3);border-radius:8px;padding:6px 10px;text-align:center;min-width:44px;">
                <div style="font-size:11px;color:var(--muted);"><?= date('M', strtotime((isset($ev['data']) ? $ev['data'] : 'today'))) ?></div>
                <div style="font-size:16px;font-weight:700;font-family:'Syne',sans-serif;"><?= date('d', strtotime((isset($ev['data']) ? $ev['data'] : 'today'))) ?></div>
            </div>
            <div>
                <div style="font-size:13px;font-weight:600;"><?= htmlspecialchars((isset($ev['titolo']) ? $ev['titolo'] : '')) ?></div>
                <div style="font-size:11px;color:var(--muted);"><?= htmlspecialchars((isset($ev['luogo']) ? $ev['luogo'] : '')) ?></div>
            </div>
            <?php if (!empty($ev['evidenza'])): ?>
            <span class="badge badge-gold" style="margin-left:auto;">⭐</span>
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

</div>

<!-- Ultime notizie -->
<div class="card">
    <div class="card-header">
        <span class="card-title">📰 Ultime notizie</span>
        <a href="/admin/notizie.php" class="btn btn-primary btn-sm">+ Nuova notizia</a>
    </div>
    <div class="table-wrap">
        <?php if (empty($ultimeNotizie)): ?>
        <div class="empty-state"><div class="icon">📰</div><h3>Nessuna notizia</h3><p>Inizia aggiungendo la prima notizia.</p></div>
        <?php else: ?>
        <table>
            <thead><tr>
                <th>Titolo</th><th>Categoria</th><th>Data</th><th>Evidenza</th>
            </tr></thead>
            <tbody>
            <?php foreach ($ultimeNotizie as $n): ?>
            <tr>
                <td style="font-weight:500;"><?= htmlspecialchars((isset($n['titolo']) ? $n['titolo'] : '')) ?></td>
                <td><span class="badge badge-blue"><?= htmlspecialchars((isset($n['categoria']) ? $n['categoria'] : '')) ?></span></td>
                <td style="color:var(--muted);font-size:12px;"><?= htmlspecialchars((isset($n['data']) ? $n['data'] : '')) ?></td>
                <td><?= !empty($n['evidenza']) ? '⭐' : '' ?></td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<?php require_once __DIR__ . '/includes/layout_end.php'; ?>
