<?php
require_once __DIR__ . '/includes/config.php';
$pageTitle    = 'Ordinamento';
$pageSubtitle = 'Scegli l\'ordine degli elementi in evidenza';
$activeSection = 'ordinamento';
$topbarAction  = '';
require_once __DIR__ . '/includes/layout.php';

$locali  = loadData('locali')  ?: [];
$eventi  = loadData('eventi')  ?: [];
$sponsor = loadData('sponsor') ?: [];

// Separa in evidenza e non, per mostrare prima quelli in evidenza
function splitEvidenza($items) {
    $ev  = array_values(array_filter($items, function($x){ return !empty($x['evidenza']); }));
    $non = array_values(array_filter($items, function($x){ return empty($x['evidenza']); }));
    return [$ev, $non];
}

[$localiEv,  $localiNon]  = splitEvidenza($locali);
[$eventiEv,  $eventiNon]  = splitEvidenza($eventi);
[$sponsorEv, $sponsorNon] = splitEvidenza($sponsor);
?>

<style>
.ord-section { margin-bottom: 48px; }
.ord-section h2 { font-size: 16px; font-weight: 700; color: var(--text); margin-bottom: 4px; display:flex;align-items:center;gap:8px; }
.ord-section p  { font-size: 12px; color: var(--muted); margin-bottom: 14px; }

.ord-list { list-style: none; padding: 0; margin: 0; max-width: 580px; }

.ord-group-label {
    font-size: 10px; font-weight: 800; text-transform: uppercase; letter-spacing: .1em;
    color: var(--muted); padding: 6px 0 6px 4px; margin-top: 8px;
}

.ord-item {
    display: flex;
    align-items: center;
    gap: 12px;
    background: var(--dark2);
    border: 1px solid var(--border);
    border-radius: 10px;
    padding: 10px 14px;
    margin-bottom: 8px;
    cursor: grab;
    transition: box-shadow .15s, border-color .15s;
    user-select: none;
}
.ord-item.not-ev {
    opacity: .6;
}
.ord-item:active { cursor: grabbing; }
.ord-item.dragging { opacity:.4; box-shadow:0 8px 32px rgba(0,0,0,.35); border-color:var(--gold); }
.ord-item.drag-over { border-color:var(--gold); background:rgba(232,168,56,.08); }

.ord-handle { color:var(--muted); font-size:18px; flex-shrink:0; cursor:grab; }
.ord-thumb  { width:44px; height:44px; border-radius:8px; object-fit:cover; flex-shrink:0; background:var(--dark3); }
.ord-thumb-empty { width:44px;height:44px;border-radius:8px;background:var(--dark3);flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:20px; }
.ord-info  { flex:1; min-width:0; }
.ord-name  { font-size:14px; font-weight:600; color:var(--text); white-space:nowrap;overflow:hidden;text-overflow:ellipsis; }
.ord-sub   { font-size:11px; color:var(--muted); margin-top:2px; }
.ord-badge { font-size:9px;font-weight:800;background:var(--gold);color:#1a1a2e;border-radius:3px;padding:1px 6px;margin-left:6px;vertical-align:middle; }

.ord-arrows { display:flex;flex-direction:column;gap:2px;flex-shrink:0; }
.ord-arrows button {
    background:var(--dark3); border:1px solid var(--border); border-radius:5px;
    color:var(--text2); width:26px;height:22px; font-size:12px;line-height:1;
    cursor:pointer; display:flex;align-items:center;justify-content:center;
    transition:background .1s,color .1s;
}
.ord-arrows button:hover { background:var(--gold);color:#1a1a2e;border-color:var(--gold); }
.ord-arrows button:disabled { opacity:.3;cursor:default; }

.ord-pos { font-size:11px;font-weight:700;color:var(--muted);min-width:18px;text-align:center; }
</style>

<?php
function renderList($listId, $section, $evItems, $nonEvItems, $emptyIcon) {
    $all = array_merge($evItems, $nonEvItems);
    if (empty($all)) {
        echo '<p style="color:var(--muted);font-size:13px;font-style:italic;">Nessun elemento trovato.</p>';
        return;
    }
    echo '<ul class="ord-list" id="list-'.$listId.'" data-section="'.$section.'">';

    if (!empty($evItems)) {
        echo '<li class="ord-group-label">⭐ In evidenza (appaiono in homepage)</li>';
        foreach ($evItems as $i => $item) {
            renderItem($item, $i+1, $emptyIcon, true);
        }
    }
    if (!empty($nonEvItems)) {
        echo '<li class="ord-group-label">— Non in evidenza</li>';
        foreach ($nonEvItems as $i => $item) {
            renderItem($item, count($evItems)+$i+1, $emptyIcon, false);
        }
    }
    echo '</ul>';
    echo '<button class="btn btn-primary btn-sm" style="margin-top:10px;" onclick="saveOrder(\''.$listId.'\',\''.$section.'\')">💾 Salva ordine</button>';
}

function renderItem($item, $pos, $emptyIcon, $inEv) {
    $id   = htmlspecialchars($item['id'] ?? '');
    $nome = htmlspecialchars($item['nome'] ?? $item['titolo'] ?? '');
    $foto = $item['foto'] ?? '';
    $cls  = $inEv ? '' : ' not-ev';
    echo '<li class="ord-item'.$cls.'" draggable="true" data-id="'.$id.'">';
    echo '<span class="ord-handle">⠿</span>';
    echo '<span class="ord-pos">'.$pos.'</span>';
    if ($foto) echo '<img class="ord-thumb" src="'.htmlspecialchars($foto).'" alt="">';
    else       echo '<div class="ord-thumb-empty">'.$emptyIcon.'</div>';
    echo '<div class="ord-info"><div class="ord-name">'.$nome;
    if ($inEv) echo '<span class="ord-badge">IN EVIDENZA</span>';
    echo '</div>';
    // Sottotitolo
    $sub = '';
    if (isset($item['tipo']))      $sub = $item['tipo'];
    if (isset($item['categoria'])) $sub = $item['categoria'];
    if (isset($item['livello']))   $sub = $item['livello'];
    if (isset($item['data']) && $item['data']) $sub .= ($sub?' · ':'').date('d/m/Y', strtotime($item['data']));
    if ($sub) echo '<div class="ord-sub">'.htmlspecialchars($sub).'</div>';
    echo '</div>';
    echo '<div class="ord-arrows"><button onclick="moveUp(this)">▲</button><button onclick="moveDown(this)">▼</button></div>';
    echo '</li>';
}
?>

<div class="ord-section">
    <h2>🏠 Locali &amp; Attività</h2>
    <p>Trascina o usa le frecce. I primi 3 <strong>in evidenza</strong> appaiono in homepage.</p>
    <?php renderList('locali', 'locali', $localiEv, $localiNon, '🏠'); ?>
</div>

<div class="ord-section">
    <h2>📅 Eventi</h2>
    <p>Trascina o usa le frecce. I primi 3 <strong>in evidenza</strong> appaiono in homepage.</p>
    <?php renderList('eventi', 'eventi', $eventiEv, $eventiNon, '📅'); ?>
</div>

<div class="ord-section">
    <h2>⭐ Sponsor</h2>
    <p>Trascina o usa le frecce per cambiare l'ordine di visualizzazione.</p>
    <?php renderList('sponsor', 'sponsor', $sponsorEv, $sponsorNon, '⭐'); ?>
</div>

<script>
// ---- Frecce su/giù ----
function moveUp(btn) {
    const li = btn.closest('.ord-item');
    const prev = li.previousElementSibling;
    if (prev && prev.classList.contains('ord-item')) {
        li.parentNode.insertBefore(li, prev);
        refreshPositions(li.parentNode);
    }
}
function moveDown(btn) {
    const li = btn.closest('.ord-item');
    const next = li.nextElementSibling;
    if (next && next.classList.contains('ord-item')) {
        li.parentNode.insertBefore(next, li);
        refreshPositions(li.parentNode);
    }
}
function refreshPositions(list) {
    let pos = 1;
    list.querySelectorAll('.ord-item').forEach(el => {
        const posEl = el.querySelector('.ord-pos');
        if (posEl) posEl.textContent = pos++;
    });
}

// ---- Drag & drop ----
let dragSrc = null;
document.querySelectorAll('.ord-list').forEach(list => {
    list.addEventListener('dragstart', e => {
        dragSrc = e.target.closest('.ord-item');
        if (!dragSrc) return;
        setTimeout(() => dragSrc.classList.add('dragging'), 0);
        e.dataTransfer.effectAllowed = 'move';
    });
    list.addEventListener('dragend', () => {
        document.querySelectorAll('.ord-item').forEach(el => el.classList.remove('dragging','drag-over'));
        if (dragSrc) refreshPositions(dragSrc.parentNode);
        dragSrc = null;
    });
    list.addEventListener('dragover', e => {
        e.preventDefault();
        const target = e.target.closest('.ord-item');
        if (!target || target === dragSrc) return;
        document.querySelectorAll('.ord-item').forEach(el => el.classList.remove('drag-over'));
        target.classList.add('drag-over');
        const rect = target.getBoundingClientRect();
        if (e.clientY < rect.top + rect.height / 2) target.parentNode.insertBefore(dragSrc, target);
        else target.parentNode.insertBefore(dragSrc, target.nextSibling);
    });
    list.addEventListener('drop', e => e.preventDefault());
});

// ---- Salva ----
async function saveOrder(listId, section) {
    const list = document.getElementById('list-' + listId);
    if (!list) return;
    const ids = Array.from(list.querySelectorAll('.ord-item')).map(li => li.dataset.id);
    const fd = new FormData();
    fd.append('action', 'reorder_evidenza');
    fd.append('section', section);
    fd.append('order', JSON.stringify(ids));
    try {
        const r = await fetch('/admin/api/handler.php', { method:'POST', body:fd });
        const d = await r.json();
        if (d.ok) toast('Ordine salvato! Ricorda di cliccare Pubblica per aggiornare il sito.');
        else toast(d.error || 'Errore nel salvataggio', 'error');
    } catch(e) {
        toast('Errore di rete', 'error');
    }
}
</script>

<?php require_once __DIR__ . '/includes/layout_end.php'; ?>
