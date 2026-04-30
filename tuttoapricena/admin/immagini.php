<?php
require_once __DIR__ . '/includes/config.php';
requireAdmin();
$pageTitle    = 'Immagini Homepage';
$pageSubtitle = 'Modifica le immagini delle sezioni del sito';
$activeSection = 'immagini';
require_once __DIR__ . '/includes/layout.php';

// Leggi config attuale dal data.js
function leggiConfig() {
    $root = dirname(__DIR__);
    for ($i=0;$i<8;$i++) {
        if (file_exists($root.'/assets/js/data.js')) break;
        $p = dirname($root); if ($p===$root) break; $root=$p;
    }
    $path = $root.'/assets/js/data.js';
    if (!file_exists($path)) return [];
    $c = file_get_contents($path);
    $start = strpos($c,'const TA = ');
    $end   = strpos($c,"\nTA.getCatColor");
    if ($start===false||$end===false) return [];
    $json = trim(substr($c,$start+strlen('const TA = '),$end-$start-strlen('const TA = ')));
    $json = rtrim($json,';');
    $ta = json_decode($json,true);
    return $ta ?: [];
}

$ta = leggiConfig();
$heroImages = (isset($ta['config']['heroImages']) ? $ta['config']['heroImages'] : array('','',''));
$citImg     = $ta['citta']['immagine'] ?: '';
?>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

<!-- HERO SLIDER (3 immagini) -->
<div class="card" style="grid-column:span 2;">
    <div class="card-header"><span class="card-title">🖼 Immagini Hero (slider homepage)</span></div>
    <div class="card-body">
        <p style="font-size:13px;color:var(--text2);margin-bottom:20px;">Le 3 immagini che scorrono nella parte alta della homepage. Puoi caricare un file o incollare un URL.</p>
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:20px;" id="hero-slots">
        <?php for ($i=0;$i<3;$i++): ?>
        <div>
            <div style="font-size:12px;font-weight:600;color:var(--text2);margin-bottom:8px;text-transform:uppercase;letter-spacing:.06em;">Slide <?= $i+1 ?></div>
            <?php $img = $heroImages[$i] ?: ''; ?>
            <div style="height:140px;border-radius:10px;overflow:hidden;background:var(--dark3);margin-bottom:10px;position:relative;">
                <img id="hero-prev-<?= $i ?>" src="<?= htmlspecialchars($img) ?>"
                     style="width:100%;height:100%;object-fit:cover;<?= $img?'':'display:none' ?>"
                     onerror="this.style.display='none'">
                <?php if (!$img): ?>
                <div style="display:flex;align-items:center;justify-content:center;height:100%;color:var(--muted);font-size:13px;">Nessuna immagine</div>
                <?php endif; ?>
            </div>
            <div class="form-field" style="margin-bottom:8px;">
                <label>URL immagine</label>
                <input type="text" id="hero-url-<?= $i ?>" value="<?= htmlspecialchars($img) ?>"
                       placeholder="https://..." oninput="prevUrl(<?= $i ?>)">
            </div>
            <div class="form-field">
                <label>oppure carica file</label>
                <input type="file" id="hero-file-<?= $i ?>" accept="image/*" onchange="uploadHero(<?= $i ?>, this)">
            </div>
        </div>
        <?php endfor; ?>
        </div>
        <button class="btn btn-primary" style="margin-top:20px;" onclick="salvaHero()">💾 Salva immagini hero</button>
        <div id="hero-msg" style="display:none;margin-top:12px;padding:12px;border-radius:8px;font-size:13px;"></div>
    </div>
</div>

<!-- IMMAGINE SEZIONE CITTÀ -->
<div class="card">
    <div class="card-header"><span class="card-title">🏛 Immagine sezione "Apricena"</span></div>
    <div class="card-body">
        <p style="font-size:13px;color:var(--text2);margin-bottom:16px;">L'immagine nella sezione "La Città della Pietra" in homepage.</p>
        <div style="height:160px;border-radius:10px;overflow:hidden;background:var(--dark3);margin-bottom:14px;">
            <img id="citta-prev" src="<?= htmlspecialchars($citImg) ?>"
                 style="width:100%;height:100%;object-fit:cover;<?= $citImg?'':'display:none' ?>"
                 onerror="this.style.display='none'">
        </div>
        <div class="form-field">
            <label>URL immagine</label>
            <input type="text" id="citta-url" value="<?= htmlspecialchars($citImg) ?>"
                   placeholder="https://..." oninput="document.getElementById('citta-prev').src=this.value;document.getElementById('citta-prev').style.display='block'">
        </div>
        <div class="form-field">
            <label>oppure carica file</label>
            <input type="file" id="citta-file" accept="image/*" onchange="uploadCitta(this)">
        </div>
        <button class="btn btn-primary" onclick="salvaCitta()">💾 Salva immagine città</button>
        <div id="citta-msg" style="display:none;margin-top:12px;padding:12px;border-radius:8px;font-size:13px;"></div>
    </div>
</div>

<!-- LOGO -->
<div class="card">
    <div class="card-header"><span class="card-title">🔷 Logo sito</span></div>
    <div class="card-body">
        <p style="font-size:13px;color:var(--text2);margin-bottom:16px;">Il logo nella navbar. Se vuoto, mostra le iniziali "TA".</p>
        <?php $logoUrl = $ta['config']['logoUrl'] ?: ''; ?>
        <div style="height:80px;border-radius:10px;overflow:hidden;background:var(--dark3);margin-bottom:14px;display:flex;align-items:center;justify-content:center;">
            <?php if ($logoUrl): ?>
            <img id="logo-prev" src="<?= htmlspecialchars($logoUrl) ?>" style="max-height:70px;max-width:200px;object-fit:contain;">
            <?php else: ?>
            <div id="logo-prev" style="font-family:'Syne',sans-serif;font-size:28px;font-weight:800;color:var(--gold);">TA</div>
            <?php endif; ?>
        </div>
        <div class="form-field">
            <label>URL logo</label>
            <input type="text" id="logo-url" value="<?= htmlspecialchars($logoUrl) ?>" placeholder="https://...">
        </div>
        <div class="form-field">
            <label>oppure carica file</label>
            <input type="file" id="logo-file" accept="image/*" onchange="uploadLogo(this)">
        </div>
        <button class="btn btn-primary" onclick="salvaLogo()">💾 Salva logo</button>
        <div id="logo-msg" style="display:none;margin-top:12px;padding:12px;border-radius:8px;font-size:13px;"></div>
    </div>
</div>

</div>

<script>
function prevUrl(i) {
    const url = document.getElementById('hero-url-'+i).value;
    const img = document.getElementById('hero-prev-'+i);
    img.src = url; img.style.display = url ? 'block' : 'none';
}

async function uploadFile(fileInput, previewId, urlInputId) {
    const file = fileInput.files[0];
    if (!file) return null;
    const fd = new FormData();
    fd.append('action','media_upload');
    fd.append('file', file);
    const r = await fetch('/admin/api/handler.php',{method:'POST',body:fd});
    const d = await r.json();
    if (d.ok) {
        document.getElementById(urlInputId).value = d.url;
        const prev = document.getElementById(previewId);
        prev.src = d.url; prev.style.display = 'block';
        return d.url;
    } else {
        toast('Upload fallito: '+(d.error||'errore'),'error');
        return null;
    }
}

async function uploadHero(i, input) {
    await uploadFile(input, 'hero-prev-'+i, 'hero-url-'+i);
}
async function uploadCitta(input) {
    await uploadFile(input, 'citta-prev', 'citta-url');
}
async function uploadLogo(input) {
    await uploadFile(input, 'logo-prev', 'logo-url');
}

async function salvaImmagini(tipo, data) {
    const fd = new FormData();
    fd.append('action', 'immagini_save');
    fd.append('tipo', tipo);
    fd.append('data', JSON.stringify(data));
    const r = await fetch('/admin/api/handler.php',{method:'POST',body:fd});
    return r.json();
}

async function salvaHero() {
    const urls = [0,1,2].map(i => document.getElementById('hero-url-'+i).value.trim());
    const fd = new FormData();
    fd.append('action','immagini_save');
    fd.append('tipo','hero');
    fd.append('data', JSON.stringify(urls));
    const r = await fetch('/admin/api/handler.php',{method:'POST',body:fd});
    const d = await r.json();
    mostraMsg('hero-msg', d.ok, d.ok ? '✅ Immagini hero salvate! Pubblica il sito per applicarle.' : '❌ '+d.error);
}

async function salvaCitta() {
    const url = document.getElementById('citta-url').value.trim();
    const fd = new FormData();
    fd.append('action','immagini_save');
    fd.append('tipo','citta');
    fd.append('data', JSON.stringify(url));
    const r = await fetch('/admin/api/handler.php',{method:'POST',body:fd});
    const d = await r.json();
    mostraMsg('citta-msg', d.ok, d.ok ? '✅ Immagine città salvata! Pubblica il sito per applicarla.' : '❌ '+d.error);
}

async function salvaLogo() {
    const url = document.getElementById('logo-url').value.trim();
    const fd = new FormData();
    fd.append('action','immagini_save');
    fd.append('tipo','logo');
    fd.append('data', JSON.stringify(url));
    const r = await fetch('/admin/api/handler.php',{method:'POST',body:fd});
    const d = await r.json();
    mostraMsg('logo-msg', d.ok, d.ok ? '✅ Logo salvato! Pubblica il sito per applicarlo.' : '❌ '+d.error);
}

function mostraMsg(id, ok, msg) {
    const el = document.getElementById(id);
    el.style.display = 'block';
    el.style.background = ok ? 'rgba(76,175,125,.1)' : 'rgba(224,85,85,.1)';
    el.style.border = ok ? '1px solid rgba(76,175,125,.3)' : '1px solid rgba(224,85,85,.3)';
    el.style.color = ok ? '#4caf7d' : '#e05555';
    el.textContent = msg;
    toast(msg);
}
</script>

<?php require_once __DIR__ . '/includes/layout_end.php'; ?>
