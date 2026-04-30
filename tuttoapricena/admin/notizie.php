<?php
require_once __DIR__ . '/includes/config.php';
$pageTitle     = 'Notizie';
$pageSubtitle  = 'Gestisci tutte le notizie del portale';
$activeSection = 'notizie';
$topbarAction  = '<button class="btn btn-danger btn-sm" id="btn-bulk-delete" onclick="bulkDelete()" style="display:none">🗑 Elimina selezionati (<span id="bulk-count">0</span>)</button> <button class="btn btn-secondary btn-sm" onclick="importaLive()" id="btn-live">📡 Importa live</button> <button class="btn btn-primary btn-sm" onclick="openNewNotizia()">+ Nuova</button>';
require_once __DIR__ . '/includes/layout.php';

$items = loadData('notizie');
usort($items, function($a,$b) { return strcmp(isset($b['data'])?$b['data']:'', isset($a['data'])?$a['data']:''); });
$categorie = ['Cronaca','Cultura','Sport','Economia','Società','Turismo','Politica','Altro'];
?>

<style>
/* ---- responsive mobile ---- */
@media(max-width:768px){
  .news-filters{flex-direction:column!important;}
  .news-filters select,.news-filters .search-bar{width:100%!important;min-width:0!important;}
  .desktop-only{display:none!important;}
  .tbl-notizie td:nth-child(1){width:50px!important;}
  .card-mobile{display:flex!important;flex-direction:column;gap:4px;padding:14px 12px;border-bottom:1px solid var(--border);}
  .hide-mobile{display:none!important;}
  table#tbl-notizie{display:none!important;}
  #mobile-list{display:block!important;}
}
@media(min-width:769px){
  #mobile-list{display:none!important;}
}
.card-mobile{display:none;}
.notizia-row-title{font-weight:600;font-size:14px;line-height:1.3;}
.notizia-row-meta{font-size:11px;color:var(--muted);display:flex;gap:8px;flex-wrap:wrap;align-items:center;margin-top:4px;}
.notizia-row-actions{display:flex;gap:6px;margin-top:8px;}
</style>

<!-- Filtri -->
<div class="news-filters" style="display:flex;gap:12px;align-items:center;margin-bottom:20px;flex-wrap:wrap;">
    <div class="search-bar" style="flex:1;min-width:180px;">
        <span>🔍</span>
        <input type="text" placeholder="Cerca notizie..." oninput="filterTable(this,'tbl-notizie');filterMobile(this.value)">
    </div>
    <select id="filter-cat" onchange="filterCategory()" style="background:var(--dark3);border:1px solid var(--border2);border-radius:8px;padding:9px 14px;color:var(--text);font-size:13px;">
        <option value="">Tutte le categorie</option>
        <?php foreach ($categorie as $c): ?>
        <option value="<?= $c ?>"><?= $c ?></option>
        <?php endforeach; ?>
    </select>
</div>

<!-- TABELLA DESKTOP -->
<div class="card">
    <div class="table-wrap">
        <?php if (empty($items)): ?>
        <div class="empty-state">
            <div class="icon">📰</div>
            <h3>Nessuna notizia</h3>
            <p>Aggiungi la prima notizia con "+ Nuova" in alto a destra.</p>
        </div>
        <?php else: ?>
        <table id="tbl-notizie">
            <thead><tr>
                <th style="width:32px;"><input type="checkbox" id="chk-all" onchange="toggleAllChk(this)" title="Seleziona tutto"></th>
                <th style="width:60px;" class="desktop-only">Foto</th>
                <th>Titolo</th>
                <th class="desktop-only">Categoria</th>
                <th class="desktop-only">Data</th>
                <th>⭐</th>
                <th style="text-align:right;">Azioni</th>
            </tr></thead>
            <tbody>
            <?php foreach ($items as $n): ?>
            <tr data-cat="<?= htmlspecialchars((isset($n['categoria']) ? $n['categoria'] : '')) ?>">
                <td><input type="checkbox" class="row-chk" value="<?= htmlspecialchars($n['id']) ?>" onchange="updateBulkBtn()"></td>
                <td class="desktop-only">
                    <?php if (!empty($n['foto'])): ?>
                    <img src="<?= htmlspecialchars($n['foto']) ?>" class="img-preview" alt="">
                    <?php else: ?>
                    <div style="width:60px;height:42px;background:var(--dark3);border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:16px;">📰</div>
                    <?php endif; ?>
                </td>
                <td>
                    <div style="font-weight:600;font-size:13px;line-height:1.3;"><?= htmlspecialchars((isset($n['titolo']) ? $n['titolo'] : '')) ?></div>
                    <div style="font-size:11px;color:var(--muted);margin-top:2px;"><?= htmlspecialchars(mb_substr((isset($n['sommario']) ? $n['sommario'] : ''),0,55)) ?>...</div>
                </td>
                <td class="desktop-only"><span class="badge badge-blue"><?= htmlspecialchars((isset($n['categoria']) ? $n['categoria'] : '')) ?></span></td>
                <td class="desktop-only" style="font-size:12px;color:var(--muted);"><?= htmlspecialchars((isset($n['data']) ? $n['data'] : '')) ?></td>
                <td>
                    <button class="star-btn" onclick="toggleEvidenza('notizie','<?= htmlspecialchars($n['id']) ?>',this)">
                        <?= !empty($n['evidenza']) ? '⭐' : '☆' ?>
                    </button>
                </td>
                <td style="text-align:right;">
                    <div style="display:flex;gap:6px;justify-content:flex-end;">
                        <button class="btn btn-secondary btn-xs" onclick="editItem('notizie','<?= htmlspecialchars($n['id']) ?>')">✏</button>
                        <button class="btn btn-danger btn-xs" onclick="deleteItem('notizie','<?= htmlspecialchars($n['id']) ?>',this)">🗑</button>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <!-- LISTA MOBILE -->
        <div id="mobile-list">
        <?php foreach ($items as $n): ?>
        <div class="card-mobile" data-cat="<?= htmlspecialchars((isset($n['categoria']) ? $n['categoria'] : '')) ?>" data-title="<?= htmlspecialchars(strtolower((isset($n['titolo']) ? $n['titolo'] : ''))) ?>">
            <div style="display:flex;gap:10px;align-items:flex-start;">
                <?php if (!empty($n['foto'])): ?>
                <img src="<?= htmlspecialchars($n['foto']) ?>" style="width:52px;height:36px;object-fit:cover;border-radius:6px;flex-shrink:0;">
                <?php endif; ?>
                <div style="flex:1;min-width:0;">
                    <div class="notizia-row-title"><?= htmlspecialchars((isset($n['titolo']) ? $n['titolo'] : '')) ?></div>
                    <div class="notizia-row-meta">
                        <span class="badge badge-blue" style="font-size:10px;"><?= htmlspecialchars((isset($n['categoria']) ? $n['categoria'] : '')) ?></span>
                        <span><?= htmlspecialchars((isset($n['data']) ? $n['data'] : '')) ?></span>
                        <?php if (!empty($n['evidenza'])): ?><span>⭐</span><?php endif; ?>
                    </div>
                </div>
            </div>
            <div class="notizia-row-actions">
                <button class="btn btn-secondary btn-xs" style="flex:1;" onclick="editItem('notizie','<?= htmlspecialchars($n['id']) ?>')">✏ Modifica</button>
                <button class="btn btn-danger btn-xs" onclick="deleteItem('notizie','<?= htmlspecialchars($n['id']) ?>',this)">🗑</button>
                <button class="star-btn" onclick="toggleEvidenza('notizie','<?= htmlspecialchars($n['id']) ?>',this)" style="font-size:16px;"><?= !empty($n['evidenza'])?'⭐':'☆' ?></button>
            </div>
        </div>
        <?php endforeach; ?>
        </div>

        <?php endif; ?>
    </div>
</div>

<!-- MODAL NOTIZIA -->
<div class="modal-overlay" id="modal-notizia">
<div class="modal" style="max-width:700px;">
    <div class="modal-header">
        <span class="modal-title" id="modal-notizia-title">Nuova Notizia</span>
        <button class="modal-close" onclick="closeModal('modal-notizia')">×</button>
    </div>
    <div class="modal-body">
        <input type="hidden" id="n-id">
        <div class="form-field">
            <label>Titolo *</label>
            <input type="text" id="n-titolo" placeholder="Titolo della notizia">
        </div>
        <div class="form-row">
            <div class="form-field">
                <label>Categoria</label>
                <select id="n-categoria">
                    <?php foreach ($categorie as $c): ?>
                    <option value="<?= $c ?>"><?= $c ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-field">
                <label>Data</label>
                <input type="date" id="n-data" value="<?= date('Y-m-d') ?>">
            </div>
        </div>
        <div class="form-field">
            <label>Sommario (anteprima)</label>
            <textarea id="n-sommario" rows="2" placeholder="Breve descrizione mostrata in lista"></textarea>
        </div>
        <div class="form-field">
            <label>Testo completo</label>
            <textarea id="n-testo" rows="6" placeholder="Testo completo della notizia..."></textarea>
        </div>
        <div class="form-row">
            <div class="form-field">
                <label>Foto (carica file)</label>
                <input type="file" id="n-foto" accept="image/*" onchange="previewImg(this,'n-foto-prev')">
            </div>
            <div class="form-field">
                <label>oppure URL foto</label>
                <input type="text" id="n-foto-url" placeholder="https://..." oninput="previewFotoUrl(this.value)">
            </div>
        </div>
        <div id="n-foto-prev-wrap" style="display:none;margin-bottom:12px;">
            <img id="n-foto-prev" src="" style="max-width:100%;max-height:140px;border-radius:8px;object-fit:cover;border:2px solid var(--border2);">
            <div style="font-size:11px;color:var(--muted);margin-top:4px;">Anteprima immagine</div>
        </div>
        <div class="form-row">
            <div class="form-field">
                <label>Fonte / Autore</label>
                <input type="text" id="n-fonte" placeholder="es. La Gazzetta di Apricena">
            </div>
            <div class="form-field">
                <label>🔗 Link sito originale <span style="color:var(--gold);font-size:11px;">(se inserito, cliccando la notizia si aprirà direttamente questo sito)</span></label>
                <input type="url" id="n-fonte-url" placeholder="https://www.gazzettadapricena.it/articolo...">
                <small style="color:var(--muted);">Lascia vuoto per mostrare l'anteprima interna. Inserisci l'URL per aprire direttamente la fonte.</small>
            </div>
        </div>
        <label class="toggle-switch">
            <input type="checkbox" id="n-evidenza">
            <span class="toggle-track"></span>
            <span class="toggle-label">Metti in evidenza nella homepage</span>
        </label>
    </div>
    <div class="modal-footer">
        <button class="btn btn-secondary" onclick="closeModal('modal-notizia')">Annulla</button>
        <button class="btn btn-primary" onclick="saveNotizia()">💾 Salva notizia</button>
    </div>
</div>
</div>

<script>
// Mobile filter
function filterMobile(q) {
    q = q.toLowerCase();
    document.querySelectorAll('#mobile-list .card-mobile').forEach(r => {
        r.style.display = r.dataset.title.includes(q) ? '' : 'none';
    });
}
function filterCategory() {
    const cat = document.getElementById('filter-cat').value;
    document.querySelectorAll('#tbl-notizie tbody tr').forEach(r => {
        r.style.display = (!cat || r.dataset.cat === cat) ? '' : 'none';
    });
    document.querySelectorAll('#mobile-list .card-mobile').forEach(r => {
        r.style.display = (!cat || r.dataset.cat === cat) ? '' : 'none';
    });
}

function previewImg(input, previewId) {
    const prev = document.getElementById(previewId);
    const wrap = document.getElementById('n-foto-prev-wrap');
    if (input.files && input.files[0]) {
        const r = new FileReader();
        r.onload = e => {
            prev.src = e.target.result;
            prev.style.display = 'block';
            if (wrap) wrap.style.display = 'block';
        };
        r.readAsDataURL(input.files[0]);
    }
}

function previewFotoUrl(url) {
    const prev = document.getElementById('n-foto-prev');
    const wrap = document.getElementById('n-foto-prev-wrap');
    if (!url || !url.startsWith('http')) {
        if (wrap) wrap.style.display = 'none';
        return;
    }
    if (prev) {
        prev.onload  = () => { if (wrap) wrap.style.display = 'block'; };
        prev.onerror = () => { if (wrap) wrap.style.display = 'none'; };
        prev.src = url;
    }
}

// ===== SELEZIONE MULTIPLA =====
function toggleAllChk(masterChk) {
    document.querySelectorAll('.row-chk').forEach(c => c.checked = masterChk.checked);
    updateBulkBtn();
}
function updateBulkBtn() {
    const sel = document.querySelectorAll('.row-chk:checked');
    const btn = document.getElementById('btn-bulk-delete');
    const cnt = document.getElementById('bulk-count');
    if (btn) btn.style.display = sel.length > 0 ? '' : 'none';
    if (cnt) cnt.textContent = sel.length;
    const all = document.querySelectorAll('.row-chk');
    const master = document.getElementById('chk-all');
    if (master) master.indeterminate = sel.length > 0 && sel.length < all.length;
    if (master) master.checked = all.length > 0 && sel.length === all.length;
}
async function bulkDelete() {
    const ids = Array.from(document.querySelectorAll('.row-chk:checked')).map(c => c.value);
    if (!ids.length) return;
    if (!confirmDelete('Eliminare ' + ids.length + ' notizie selezionate? Questa azione non è reversibile.')) return;
    let ok = 0;
    for (const id of ids) {
        const fd = new FormData();
        fd.append('action', 'notizie_delete');
        fd.append('id', id);
        const r = await fetch('/admin/api/handler.php', {method:'POST', body:fd});
        const d = await r.json();
        if (d.ok) {
            const row = document.querySelector('#tbl-notizie .row-chk[value="' + id + '"]');
            if (row) row.closest('tr').remove();
            ok++;
        }
    }
    toast('✅ Eliminate ' + ok + ' notizie!');
    updateBulkBtn();
    setTimeout(() => location.reload(), 900);
}

function openNewNotizia() {
    document.getElementById('modal-notizia-title').textContent = 'Nuova Notizia';
    document.getElementById('n-id').value = '';
    ['n-titolo','n-sommario','n-testo','n-fonte','n-fonte-url','n-foto-url'].forEach(id => document.getElementById(id).value = '');
    document.getElementById('n-data').value = '<?= date('Y-m-d') ?>';
    document.getElementById('n-evidenza').checked = false;
    document.getElementById('n-foto-prev').style.display = 'none';
    document.getElementById('n-foto-prev').src = '';
    const wrap = document.getElementById('n-foto-prev-wrap');
    if (wrap) wrap.style.display = 'none';
    openModal('modal-notizia');
}

async function editItem(section, id) {
    const r = await fetch(`/admin/api/handler.php?action=get_item&section=${section}&id=${encodeURIComponent(id)}`);
    const d = await r.json();
    if (!d.ok) { toast('Errore nel caricamento','error'); return; }
    const it = d.item;
    document.getElementById('modal-notizia-title').textContent = 'Modifica Notizia';
    document.getElementById('n-id').value        = it.id;
    document.getElementById('n-titolo').value    = it.titolo   || '';
    document.getElementById('n-categoria').value = it.categoria|| 'Cronaca';
    document.getElementById('n-data').value      = it.data     || '';
    document.getElementById('n-sommario').value  = it.sommario || '';
    document.getElementById('n-testo').value     = it.testo    || '';
    document.getElementById('n-fonte').value     = it.fonte     || '';
    document.getElementById('n-fonte-url').value = it.fonte_url || '';
    document.getElementById('n-foto-url').value  = it.foto     || '';
    document.getElementById('n-evidenza').checked= !!it.evidenza;
    const prev = document.getElementById('n-foto-prev');
    const wrap = document.getElementById('n-foto-prev-wrap');
    if (it.foto) {
        prev.src = it.foto;
        prev.style.display = 'block';
        if (wrap) wrap.style.display = 'block';
    } else {
        prev.src = '';
        prev.style.display = 'none';
        if (wrap) wrap.style.display = 'none';
    }
    openModal('modal-notizia');
}

async function saveNotizia() {
    const titolo = document.getElementById('n-titolo').value.trim();
    if (!titolo) { toast('Il titolo è obbligatorio','error'); return; }
    const fd = new FormData();
    fd.append('action',   'notizie_save');
    fd.append('id',       document.getElementById('n-id').value);
    fd.append('titolo',   titolo);
    fd.append('categoria',document.getElementById('n-categoria').value);
    fd.append('data',     document.getElementById('n-data').value);
    fd.append('sommario', document.getElementById('n-sommario').value);
    fd.append('testo',    document.getElementById('n-testo').value);
    fd.append('fonte',     document.getElementById('n-fonte').value);
    fd.append('fonte_url', document.getElementById('n-fonte-url').value);
    fd.append('foto_url', document.getElementById('n-foto-url').value);
    if (document.getElementById('n-evidenza').checked) fd.append('evidenza','1');
    const fotoFile = document.getElementById('n-foto');
    if (fotoFile.files[0]) fd.append('foto', fotoFile.files[0]);
    const r = await fetch('/admin/api/handler.php',{method:'POST',body:fd});
    const d = await r.json();
    if (d.ok) { toast('Notizia salvata!'); closeModal('modal-notizia'); setTimeout(()=>location.reload(),800); }
    else toast(d.error||'Errore','error');
}

async function deleteItem(section, id, btn) {
    if (!confirmDelete('Eliminare questa notizia?')) return;
    const fd = new FormData();
    fd.append('action', section+'_delete');
    fd.append('id', id);
    const r = await fetch('/admin/api/handler.php',{method:'POST',body:fd});
    const d = await r.json();
    if (d.ok) {
        // Remove from both table and mobile list
        btn.closest('tr')?.remove();
        btn.closest('.card-mobile')?.remove();
        toast('Eliminata!');
    } else toast(d.error||'Errore','error');
}

async function toggleEvidenza(section, id, btn) {
    const fd = new FormData();
    fd.append('action', section+'_evidenza');
    fd.append('id', id);
    const r = await fetch('/admin/api/handler.php',{method:'POST',body:fd});
    const d = await r.json();
    if (d.ok) {
        const isOn = btn.textContent.trim() === '⭐';
        btn.textContent = isOn ? '☆' : '⭐';
    }
}

// ===== IMPORTA LIVE con ANTEPRIMA =====
const LIVE_CACHE_KEY = 'ta_live_v5';
const LIVE_CACHE_TTL = 15 * 60 * 1000; // 15 minuti

async function importaLive() {
    const btn = document.getElementById('btn-live');
    let liveItems = [];

    // Pulisci qualsiasi cache vecchia (ta_live_v1..v4)
    ['ta_live_v1','ta_live_v2','ta_live_v3','ta_live_v4'].forEach(k => localStorage.removeItem(k));

    // Controlla cache con scadenza
    try {
        const cached = localStorage.getItem(LIVE_CACHE_KEY);
        if (cached) {
            const parsed = JSON.parse(cached);
            const age = Date.now() - (parsed.ts || 0);
            if (age < LIVE_CACHE_TTL) {
                liveItems = parsed.items || [];
            } else {
                localStorage.removeItem(LIVE_CACHE_KEY); // cache scaduta
            }
        }
    } catch(e) { localStorage.removeItem(LIVE_CACHE_KEY); }

    if (!liveItems.length) {
        // Fetch live on-demand
        btn.disabled = true; btn.innerHTML = '⟳ Caricamento...';
        try {
            await loadLiveForPreview();
            const cached2 = localStorage.getItem(LIVE_CACHE_KEY);
            if (cached2) liveItems = JSON.parse(cached2).items || [];
        } catch(e) {
            btn.disabled = false; btn.innerHTML = '📡 Importa live';
            toast('Errore connessione: ' + e.message, 'error');
            return;
        }
        btn.disabled = false; btn.innerHTML = '📡 Importa live';
    }

    if (!liveItems.length) {
        toast('Nessuna notizia live trovata. Assicurati che il sito principale sia stato visitato di recente.','error');
        return;
    }

    // Mostra modal con anteprime
    showLivePreviewModal(liveItems);
}

// Estrae la migliore immagine disponibile da un item (già parsificato lato PHP)
function extractBestImage(item) {
    let img = item.immagine || '';
    if (img && (img.includes('pixel') || img.includes('1x1') || img.length < 10)) img = '';
    return img;
}

async function loadLiveForPreview() {
    // Chiama il proxy PHP lato server — niente CORS, niente servizi terzi
    let r;
    try {
        r = await fetch('/admin/api/handler.php?action=live_feed', {
            signal: AbortSignal.timeout(90000)
        });
    } catch(e) {
        throw new Error('Impossibile raggiungere il server: ' + e.message);
    }
    if (!r.ok) throw new Error('Il server ha risposto con errore HTTP ' + r.status);
    let data;
    try {
        data = await r.json();
    } catch(e) {
        throw new Error('Risposta del server non valida (non è JSON)');
    }
    if (!data.ok) throw new Error('Errore dal server: ' + (data.error || 'sconosciuto'));
    const all = data.items || [];
    if (!all.length) throw new Error('Il server ha risposto correttamente ma non ha trovato notizie (0 articoli). Controlla che il server possa fare richieste HTTP esterne (allow_url_fopen abilitato).');
    try { localStorage.setItem(LIVE_CACHE_KEY, JSON.stringify({ ts: Date.now(), items: all })); } catch(e) {}
}

function showLivePreviewModal(items) {
    // Crea o recupera il modal
    let modal = document.getElementById('modal-live-preview');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'modal-live-preview';
        modal.className = 'modal-overlay';
        modal.innerHTML = `
<div class="modal" style="max-width:800px;max-height:85vh;display:flex;flex-direction:column;">
  <div class="modal-header">
    <span class="modal-title">📡 Caricamento notizie live...</span>
    <button class="modal-close" onclick="document.getElementById('modal-live-preview').classList.remove('open')">×</button>
  </div>
  <div class="modal-body" style="overflow-y:auto;flex:1;" id="live-preview-list"></div>
  <div class="modal-footer" style="gap:10px;flex-wrap:wrap;">
    <button class="btn btn-secondary" onclick="document.getElementById('modal-live-preview').classList.remove('open')">Annulla</button>
    <button class="btn btn-secondary btn-sm" onclick="liveSelectAll(true)">Seleziona tutte</button>
    <button class="btn btn-secondary btn-sm" onclick="liveSelectAll(false)">Deseleziona tutte</button>
    <button class="btn btn-secondary btn-sm" id="btn-live-refresh" onclick="aggiornaLive()">🔄 Aggiorna</button>
    <button class="btn btn-primary" onclick="importaLiveSelezionate()">💾 Importa selezionate</button>
  </div>
</div>`;
        document.body.appendChild(modal);
    }

    // Popola la lista
    const list = document.getElementById('live-preview-list');
    list.innerHTML = items.map((n, i) => {
        const imgSrc = n.immagine || '';
        const imgHtml = imgSrc
            ? `<img src="${imgSrc}" alt="" style="width:100px;height:68px;object-fit:cover;border-radius:6px;flex-shrink:0;border:1px solid var(--border);" onerror="this.style.display='none';this.nextElementSibling.style.display='flex'">`
              + `<div style="display:none;width:100px;height:68px;background:var(--dark3);border-radius:6px;align-items:center;justify-content:center;font-size:22px;flex-shrink:0;">📰</div>`
            : `<div style="width:100px;height:68px;background:var(--dark3);border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:22px;flex-shrink:0;">📰</div>`;
        return `
<div style="display:flex;align-items:flex-start;gap:12px;padding:12px 0;border-bottom:1px solid var(--border);cursor:pointer;" onclick="liveToggleItem(${i},this)" id="live-item-${i}" data-selected="1" data-idx="${i}">
  <input type="checkbox" checked style="margin-top:6px;flex-shrink:0;width:16px;height:16px;" onclick="event.stopPropagation();liveToggleItem(${i},this.closest('[data-idx]'))">
  <div style="display:flex;gap:0;flex-shrink:0;">${imgHtml}</div>
  <div style="flex:1;min-width:0;">
    <div style="font-weight:600;font-size:13px;line-height:1.4;color:var(--text);">${n.titolo}</div>
    <div style="font-size:11px;color:var(--muted);margin-top:3px;display:flex;gap:8px;flex-wrap:wrap;align-items:center;">
      <span style="background:var(--dark3);border-radius:4px;padding:1px 6px;">${n.fonte}</span>
      <span>${n.data}</span>
      ${n.fonteUrl && n.fonteUrl !== '#' ? `<a href="${n.fonteUrl}" target="_blank" onclick="event.stopPropagation()" style="color:var(--gold);text-decoration:none;">↗ Apri</a>` : ''}
    </div>
    <div style="font-size:11px;color:var(--muted);margin-top:4px;line-height:1.4;overflow:hidden;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;">${(n.abstract||'').slice(0,160)}</div>
  </div>
</div>`;
    }).join('');

    // Salva items in window per accedervi dopo
    window._livePreviewItems = items;
    // Aggiorna titolo con contatore
    modal.querySelector('.modal-title').textContent = `📡 Notizie live trovate: ${items.length} — seleziona quelle da importare`;
    modal.classList.add('open');
}

function liveToggleItem(idx, row) {
    const isSelected = row.dataset.selected === '1';
    row.dataset.selected = isSelected ? '0' : '1';
    row.style.opacity = isSelected ? '0.4' : '1';
    const chk = row.querySelector('input[type=checkbox]');
    if (chk) chk.checked = !isSelected;
}

function liveSelectAll(sel) {
    document.querySelectorAll('#live-preview-list [data-idx]').forEach(row => {
        row.dataset.selected = sel ? '1' : '0';
        row.style.opacity = sel ? '1' : '0.4';
        const chk = row.querySelector('input[type=checkbox]');
        if (chk) chk.checked = sel;
    });
}

async function aggiornaLive() {
    const btn = document.getElementById('btn-live-refresh');
    const list = document.getElementById('live-preview-list');
    // Svuota cache e ricarica
    localStorage.removeItem(LIVE_CACHE_KEY);
    btn.disabled = true;
    btn.innerHTML = '⟳ Aggiornamento...';
    list.innerHTML = '<div style="padding:40px;text-align:center;color:var(--muted);">⟳ Scaricamento notizie in corso...<br><small>Attendi 20-30 secondi</small></div>';
    try {
        await loadLiveForPreview();
        const cached = localStorage.getItem(LIVE_CACHE_KEY);
        const items = cached ? (JSON.parse(cached).items || []) : [];
        if (!items.length) {
            list.innerHTML = '<div style="padding:40px;text-align:center;color:var(--muted);">Nessuna notizia trovata.</div>';
        } else {
            window._livePreviewItems = items;
            // Riutilizza showLivePreviewModal per ri-popolare la lista
            const modal = document.getElementById('modal-live-preview');
            showLivePreviewModal(items);
        }
    } catch(e) {
        list.innerHTML = '<div style="padding:40px;text-align:center;color:var(--muted);">Errore durante il caricamento. Riprova.</div>';
    }
    btn.disabled = false;
    btn.innerHTML = '🔄 Aggiorna';
}

async function importaLiveSelezionate() {
    const items = window._livePreviewItems || [];
    const selected = [];
    document.querySelectorAll('#live-preview-list [data-idx]').forEach(row => {
        if (row.dataset.selected === '1') {
            selected.push(items[parseInt(row.dataset.idx)]);
        }
    });
    if (!selected.length) { toast('Seleziona almeno una notizia','error'); return; }

    document.getElementById('modal-live-preview').classList.remove('open');
    toast('⟳ Importazione in corso...');

    let ok = 0;
    for (const n of selected) {
        const fd = new FormData();
        fd.append('action',    'notizie_save');
        fd.append('id',        '');
        fd.append('titolo',    n.titolo    || '');
        fd.append('categoria', n.categoria || 'Cronaca');
        fd.append('data',      n.data      || new Date().toISOString().split('T')[0]);
        fd.append('sommario',  n.abstract  || '');
        fd.append('testo',     n.testo || n.abstract || '');
        fd.append('fonte',     n.fonte     || '');
        fd.append('fonte_url', n.fonteUrl  || n.externalLink || '');
        fd.append('foto_url',  n.immagine  || '');
        const r = await fetch('/admin/api/handler.php',{method:'POST',body:fd});
        const d = await r.json();
        if (d.ok) ok++;
    }
    toast('✅ ' + ok + ' notizie live salvate nel CMS!');
    setTimeout(()=>location.reload(), 1200);
}
</script>

<?php require_once __DIR__ . '/includes/layout_end.php'; ?>
