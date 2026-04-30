<?php
require_once __DIR__ . '/includes/config.php';
requireAdmin();
$pageTitle = 'Media & Immagini';
$pageSubtitle = 'Libreria delle immagini caricate sul CMS';
$activeSection = 'media';
require_once __DIR__ . '/includes/layout.php';
?>

<div style="display:grid;grid-template-columns:1fr 2fr;gap:20px;">

<!-- Upload -->
<div class="card" style="height:fit-content;">
    <div class="card-header"><span class="card-title">📤 Carica immagini</span></div>
    <div class="card-body">
        <div id="drop-zone" style="border:2px dashed var(--border);border-radius:12px;padding:40px 20px;text-align:center;cursor:pointer;transition:border-color 0.2s;"
             onclick="document.getElementById('media-input').click()"
             ondragover="e=>{e.preventDefault();this.style.borderColor='var(--gold)'}"
             ondragleave="this.style.borderColor='var(--border)'"
             ondrop="handleDrop(event)">
            <div style="font-size:36px;margin-bottom:12px;">🖼</div>
            <div style="font-size:14px;font-weight:600;margin-bottom:4px;">Clicca o trascina le immagini</div>
            <div style="font-size:12px;color:var(--muted);">PNG, JPG, WEBP — max 5MB</div>
        </div>
        <input type="file" id="media-input" accept="image/*" multiple style="display:none" onchange="uploadFiles(this.files)">
        <div id="upload-progress" style="margin-top:12px;"></div>
    </div>
</div>

<!-- Libreria -->
<div class="card">
    <div class="card-header">
        <span class="card-title">📁 Libreria immagini</span>
        <button class="btn btn-secondary btn-sm" onclick="loadMedia()">🔄 Aggiorna</button>
    </div>
    <div class="card-body">
        <div id="media-grid" style="display:grid;grid-template-columns:repeat(auto-fill,minmax(120px,1fr));gap:12px;">
            <div style="text-align:center;color:var(--muted);padding:40px;grid-column:1/-1;">Caricamento...</div>
        </div>
    </div>
</div>
</div>

<script>
async function loadMedia() {
    const fd = new FormData();
    fd.append('action','media_list');
    const r = await fetch('/admin/api/handler.php',{method:'POST',body:fd});
    const d = await r.json();
    const grid = document.getElementById('media-grid');
    if (!d.ok || d.files.length===0) {
        grid.innerHTML = '<div style="text-align:center;color:var(--muted);padding:40px;grid-column:1/-1;">Nessuna immagine. Carica qualcosa!</div>';
        return;
    }
    grid.innerHTML = d.files.map(f => `
        <div style="position:relative;group;">
            <img src="${f.url}" style="width:100%;aspect-ratio:1;object-fit:cover;border-radius:8px;cursor:pointer;" onclick="copyUrl('${f.url}')" title="Clicca per copiare URL">
            <div style="position:absolute;bottom:4px;right:4px;display:flex;gap:4px;">
                <button onclick="copyUrl('${f.url}')" style="background:rgba(0,0,0,0.7);border:none;color:#fff;border-radius:4px;padding:3px 6px;font-size:11px;cursor:pointer;" title="Copia URL">📋</button>
                <button onclick="deleteMedia('${f.name}',this)" style="background:rgba(200,0,0,0.7);border:none;color:#fff;border-radius:4px;padding:3px 6px;font-size:11px;cursor:pointer;" title="Elimina">🗑</button>
            </div>
            <div style="font-size:10px;color:var(--muted);margin-top:4px;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="${f.name}">${f.name}</div>
        </div>
    `).join('');
}

async function uploadFiles(files) {
    const prog = document.getElementById('upload-progress');
    for (const file of files) {
        prog.innerHTML = `<div style="font-size:13px;color:var(--text2);">Caricamento ${file.name}...</div>`;
        const fd = new FormData();
        fd.append('action','media_upload');
        fd.append('file', file);
        const r = await fetch('/admin/api/handler.php',{method:'POST',body:fd});
        const d = await r.json();
        if (d.ok) { toast('Caricato: ' + file.name); }
        else toast('Errore: ' + (d.error||'upload fallito'),'error');
    }
    prog.innerHTML = '';
    loadMedia();
}

function handleDrop(e) {
    e.preventDefault();
    document.getElementById('drop-zone').style.borderColor = 'var(--border)';
    uploadFiles(e.dataTransfer.files);
}

function copyUrl(url) {
    navigator.clipboard.writeText(url).then(()=>toast('URL copiato negli appunti!'));
}

async function deleteMedia(name, btn) {
    if (!confirmDelete('Eliminare questa immagine?')) return;
    const fd = new FormData(); fd.append('action','media_delete'); fd.append('filename',name);
    const r = await fetch('/admin/api/handler.php',{method:'POST',body:fd});
    const d = await r.json();
    if (d.ok) { btn.closest('div[style*=relative]').remove(); toast('Eliminata!'); }
}

// Load on start
loadMedia();
</script>

<?php require_once __DIR__ . '/includes/layout_end.php'; ?>
