<?php
require_once __DIR__ . '/includes/config.php';
requireAdmin();
$pageTitle = 'Editor Pagine';
$pageSubtitle = 'Modifica i contenuti delle pagine statiche';
$activeSection = 'pagine';
require_once __DIR__ . '/includes/layout.php';

$chiSiamo = array_merge([
    'titolo'=>'Chi Siamo','sottotitolo'=>'','immagine'=>'',
    'storia_titolo'=>'La nostra storia','storia_p1'=>'','storia_p2'=>'',
    'val1_titolo'=>'Indipendenti','val1_testo'=>'','val2_titolo'=>'Locali','val2_testo'=>'',
    'val3_titolo'=>'Aggiornati','val3_testo'=>'',
    'collabora_titolo'=>'Vuoi collaborare?','collabora_testo'=>''
], loadData('chiSiamo') ?: []);

$hero = array_merge([
    'eyebrow'=>'Apricena (FG) — Puglia',
    'titolo1'=>'La Città del','titolo2'=>'Marmo e della Pietra',
    'sottotitolo'=>'Notizie, eventi, farmacie di turno, servizi e locali.',
    'stat1_num'=>'12.486','stat1_label'=>'Abitanti',
    'stat2_num'=>'2°','stat2_label'=>'Polo marmifero Italia',
    'stat3_num'=>'73 m','stat3_label'=>'Sul livello del mare',
    'btn1_testo'=>'Ultime notizie','btn1_url'=>'/notizie/',
    'btn2_testo'=>'Scopri gli eventi','btn2_url'=>'/eventi/',
], loadData('headerHero') ?: []);
?>

<div style="display:flex;gap:12px;margin-bottom:24px;border-bottom:1px solid var(--border);padding-bottom:0;">
    <button class="btn btn-secondary" id="tab-hero" onclick="showTab('hero')" style="border-bottom:2px solid var(--gold);border-radius:0;background:none;padding-bottom:12px;">🏠 Header Homepage</button>
    <button class="btn btn-secondary" id="tab-chisiamo" onclick="showTab('chisiamo')" style="border-bottom:2px solid transparent;border-radius:0;background:none;padding-bottom:12px;">👥 Chi Siamo</button>
</div>

<!-- HERO EDITOR -->
<div id="panel-hero">
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

<div class="card">
    <div class="card-header"><span class="card-title">Testo principale</span></div>
    <div class="card-body">
        <div class="form-field"><label>Eyebrow (riga sopra)</label><input type="text" id="h-eyebrow" value="<?= htmlspecialchars($hero['eyebrow']) ?>"></div>
        <div class="form-field"><label>Titolo riga 1</label><input type="text" id="h-titolo1" value="<?= htmlspecialchars($hero['titolo1']) ?>"></div>
        <div class="form-field"><label>Titolo riga 2 (in evidenza oro)</label><input type="text" id="h-titolo2" value="<?= htmlspecialchars($hero['titolo2']) ?>"></div>
        <div class="form-field"><label>Sottotitolo / Descrizione</label><textarea id="h-sottotitolo" rows="2"><?= htmlspecialchars($hero['sottotitolo']) ?></textarea></div>
    </div>
</div>

<div class="card">
    <div class="card-header"><span class="card-title">Statistiche hero (3 numeri)</span></div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-field"><label>Stat 1 — Numero</label><input type="text" id="h-s1n" value="<?= htmlspecialchars($hero['stat1_num']) ?>"></div>
            <div class="form-field"><label>Stat 1 — Etichetta</label><input type="text" id="h-s1l" value="<?= htmlspecialchars($hero['stat1_label']) ?>"></div>
        </div>
        <div class="form-row">
            <div class="form-field"><label>Stat 2 — Numero</label><input type="text" id="h-s2n" value="<?= htmlspecialchars($hero['stat2_num']) ?>"></div>
            <div class="form-field"><label>Stat 2 — Etichetta</label><input type="text" id="h-s2l" value="<?= htmlspecialchars($hero['stat2_label']) ?>"></div>
        </div>
        <div class="form-row">
            <div class="form-field"><label>Stat 3 — Numero</label><input type="text" id="h-s3n" value="<?= htmlspecialchars($hero['stat3_num']) ?>"></div>
            <div class="form-field"><label>Stat 3 — Etichetta</label><input type="text" id="h-s3l" value="<?= htmlspecialchars($hero['stat3_label']) ?>"></div>
        </div>
    </div>
</div>

<div class="card" style="grid-column:span 2">
    <div class="card-header"><span class="card-title">Bottoni homepage</span></div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr 1fr;gap:12px;">
            <div class="form-field"><label>Bottone 1 — Testo</label><input type="text" id="h-b1t" value="<?= htmlspecialchars($hero['btn1_testo']) ?>"></div>
            <div class="form-field"><label>Bottone 1 — URL</label><input type="text" id="h-b1u" value="<?= htmlspecialchars($hero['btn1_url']) ?>"></div>
            <div class="form-field"><label>Bottone 2 — Testo</label><input type="text" id="h-b2t" value="<?= htmlspecialchars($hero['btn2_testo']) ?>"></div>
            <div class="form-field"><label>Bottone 2 — URL</label><input type="text" id="h-b2u" value="<?= htmlspecialchars($hero['btn2_url']) ?>"></div>
        </div>
        <button class="btn btn-primary" onclick="saveHero()">💾 Salva header homepage</button>
    </div>
</div>
</div>
</div>

<!-- CHI SIAMO EDITOR -->
<div id="panel-chisiamo" style="display:none;">
<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

<div class="card">
    <div class="card-header"><span class="card-title">Intestazione pagina</span></div>
    <div class="card-body">
        <div class="form-field"><label>Titolo</label><input type="text" id="cs-titolo" value="<?= htmlspecialchars($chiSiamo['titolo']) ?>"></div>
        <div class="form-field"><label>Sottotitolo</label><input type="text" id="cs-sottotitolo" value="<?= htmlspecialchars($chiSiamo['sottotitolo']) ?>"></div>
        <div class="form-field"><label>URL immagine header</label><input type="text" id="cs-immagine" value="<?= htmlspecialchars($chiSiamo['immagine']) ?>" placeholder="https://... oppure carica sotto"></div>
        <div class="form-field">
            <label>Carica immagine header (sostituisce l'URL sopra)</label>
            <div style="font-size:11px;color:var(--muted);margin-bottom:6px;">📐 Dimensione consigliata: <strong>1200 × 600 px</strong> — JPG o PNG, max 1 MB</div>
            <input type="file" id="cs-foto-file" accept="image/*" onchange="previewChiSiamoImg(this)">
            <div id="cs-foto-current" style="margin-top:10px;<?= empty($chiSiamo['immagine']) ? 'display:none;' : '' ?>">
              <img id="cs-foto-prev" src="<?= htmlspecialchars($chiSiamo['immagine'] ?? '') ?>" style="max-width:300px;max-height:140px;object-fit:cover;border-radius:8px;display:block;">
              <button type="button" onclick="deleteChiSiamoFoto()" style="margin-top:8px;background:#dc2626;color:#fff;border:none;border-radius:6px;padding:6px 14px;cursor:pointer;font-size:13px;font-weight:600;">🗑 Elimina foto</button>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header"><span class="card-title">Sezione storia</span></div>
    <div class="card-body">
        <div class="form-field"><label>Titolo storia</label><input type="text" id="cs-st" value="<?= htmlspecialchars($chiSiamo['storia_titolo']) ?>"></div>
        <div class="form-field"><label>Paragrafo 1</label><textarea id="cs-p1" rows="3"><?= htmlspecialchars($chiSiamo['storia_p1']) ?></textarea></div>
        <div class="form-field"><label>Paragrafo 2</label><textarea id="cs-p2" rows="3"><?= htmlspecialchars($chiSiamo['storia_p2']) ?></textarea></div>
    </div>
</div>

<div class="card" style="grid-column:span 2">
    <div class="card-header"><span class="card-title">Valori (3 riquadri)</span></div>
    <div class="card-body">
        <div style="display:grid;grid-template-columns:1fr 1fr 1fr;gap:16px;">
            <?php for ($i=1;$i<=3;$i++): ?>
            <div>
                <div class="form-field"><label>Valore <?= $i ?> — Titolo</label><input type="text" id="cs-v<?= $i ?>t" value="<?= htmlspecialchars($chiSiamo["val{$i}_titolo"]) ?>"></div>
                <div class="form-field"><label>Valore <?= $i ?> — Testo</label><textarea id="cs-v<?= $i ?>d" rows="2"><?= htmlspecialchars($chiSiamo["val{$i}_testo"]) ?></textarea></div>
            </div>
            <?php endfor; ?>
        </div>
    </div>
</div>

<div class="card" style="grid-column:span 2">
    <div class="card-header"><span class="card-title">Sezione Collabora</span></div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-field"><label>Titolo</label><input type="text" id="cs-ct" value="<?= htmlspecialchars($chiSiamo['collabora_titolo']) ?>"></div>
            <div class="form-field"><label>Testo</label><input type="text" id="cs-cd" value="<?= htmlspecialchars($chiSiamo['collabora_testo']) ?>"></div>
        </div>
        <button class="btn btn-primary" onclick="saveChiSiamo()">💾 Salva pagina Chi Siamo</button>
    </div>
</div>
</div>
</div>

<script>
function showTab(tab) {
    ['hero','chisiamo'].forEach(t => {
        document.getElementById('panel-'+t).style.display = t===tab?'block':'none';
        const btn = document.getElementById('tab-'+t);
        btn.style.borderBottomColor = t===tab?'var(--gold)':'transparent';
    });
}

async function saveHero() {
    const fd = new FormData();
    fd.append('action','headerHero_save');
    const fields = {
        eyebrow:'h-eyebrow',titolo1:'h-titolo1',titolo2:'h-titolo2',sottotitolo:'h-sottotitolo',
        stat1_num:'h-s1n',stat1_label:'h-s1l',stat2_num:'h-s2n',stat2_label:'h-s2l',
        stat3_num:'h-s3n',stat3_label:'h-s3l',
        btn1_testo:'h-b1t',btn1_url:'h-b1u',btn2_testo:'h-b2t',btn2_url:'h-b2u'
    };
    for (const [k,id] of Object.entries(fields)) fd.append(k, document.getElementById(id).value);
    const r = await fetch('/admin/api/handler.php',{method:'POST',body:fd});
    const d = await r.json();
    if (d.ok) toast('Homepage aggiornata!'); else toast(d.error||'Errore','error');
}

async function saveChiSiamo() {
    const fd = new FormData();
    fd.append('action','chiSiamo_save');
    const map = {
        titolo:'cs-titolo',sottotitolo:'cs-sottotitolo',immagine:'cs-immagine',
        storia_titolo:'cs-st',storia_p1:'cs-p1',storia_p2:'cs-p2',
        val1_titolo:'cs-v1t',val1_testo:'cs-v1d',val2_titolo:'cs-v2t',val2_testo:'cs-v2d',
        val3_titolo:'cs-v3t',val3_testo:'cs-v3d',
        collabora_titolo:'cs-ct',collabora_testo:'cs-cd'
    };
    for (const [k,id] of Object.entries(map)) fd.append(k, document.getElementById(id).value);

    // Handle file upload for chi-siamo header image
    const fotoFile = document.getElementById('cs-foto-file');
    if (fotoFile && fotoFile.files[0]) {
        // Controlla dimensione prima di inviare (max 5MB)
        if (fotoFile.files[0].size > 5 * 1024 * 1024) {
            toast('Immagine troppo grande (max 5 MB)', 'error');
            return;
        }
        toast('Caricamento immagine in corso…');
        try {
            const uploadFd = new FormData();
            uploadFd.append('action', 'upload_file');
            uploadFd.append('subdir', 'chisiamo');
            uploadFd.append('file', fotoFile.files[0]);
            const upR = await fetch('/admin/api/handler.php', {method:'POST', body:uploadFd});
            if (!upR.ok) throw new Error('HTTP ' + upR.status);
            const upD = await upR.json();
            if (upD.url) {
                fd.set('immagine', upD.url);
                document.getElementById('cs-immagine').value = upD.url;
                // Resetta il campo file per evitare upload doppio
                fotoFile.value = '';
            } else {
                alert('❌ UPLOAD FALLITO\n\n' + (upD.error || 'errore sconosciuto') + '\n\nSoluzione: vai sul server FTP e imposta chmod 775 sulla cartella admin/uploads/chisiamo/');
                return; // blocca salvataggio se upload fallisce
            }
        } catch (e) {
            toast('Errore di rete durante il caricamento immagine: ' + e.message, 'error');
            return;
        }
    }

    const r = await fetch('/admin/api/handler.php',{method:'POST',body:fd});
    const d = await r.json();
    if (d.ok) {
        toast('✅ Pagina Chi Siamo salvata! Vai su Pubblica per aggiornare il sito.');
        // Aggiorna anteprima immagine se presente
        const imgUrl = fd.get('immagine');
        if (imgUrl) {
            const prev = document.getElementById('cs-foto-prev');
            const wrap = document.getElementById('cs-foto-current');
            if (prev && imgUrl) { prev.src = imgUrl; wrap.style.display = 'block'; }
            document.getElementById('cs-immagine').value = imgUrl;
        }
    } else {
        toast(d.error||'Errore','error');
    }
}

async function deleteChiSiamoFoto() {
    if (!confirm('Eliminare la foto? L\'operazione non può essere annullata.')) return;
    const r = await fetch('/admin/api/handler.php', {method:'POST', body: new URLSearchParams({action:'chiSiamo_delete_foto'})});
    const d = await r.json();
    if (d.ok) {
        document.getElementById('cs-immagine').value = '';
        document.getElementById('cs-foto-current').style.display = 'none';
        document.getElementById('cs-foto-prev').src = '';
        document.getElementById('cs-foto-file').value = '';
        toast('Foto eliminata. Ricorda di pubblicare per aggiornare il sito.');
    } else {
        toast(d.error || 'Errore durante l\'eliminazione', 'error');
    }
}

function previewChiSiamoImg(input) {
    const prev = document.getElementById('cs-foto-prev');
    const wrap = document.getElementById('cs-foto-current');
    if (input.files && input.files[0]) {
        const rd = new FileReader();
        rd.onload = e => {
            prev.src = e.target.result;
            wrap.style.display = 'block';
        };
        rd.readAsDataURL(input.files[0]);
    }
}
</script>

<?php require_once __DIR__ . '/includes/layout_end.php'; ?>
