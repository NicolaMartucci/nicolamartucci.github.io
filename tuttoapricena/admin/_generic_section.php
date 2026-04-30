<?php
// Generic page — locali, servizi, sponsor
require_once __DIR__ . '/includes/config.php';

$configs = [
    'locali' => [
        'title'      => 'Locali & Attività',
        'subtitle'   => 'Ristoranti, bar, pizzerie e attività commerciali',
        'icon'       => '🏠',
        'tipi'       => ['Ristorante','Bar','Pub','Pizzeria','Alloggio','Negozio','Altro'],
        'tipo_label' => 'Tipo',
        'tipo_field' => 'tipo',
    ],
    'servizi' => [
        'title'      => 'Servizi Utili',
        'subtitle'   => 'Uffici pubblici, associazioni e servizi per i cittadini',
        'icon'       => '🔧',
        'tipi'       => ['Istituzioni','Sanità','Sicurezza','Trasporti','Cultura','Servizi','Altro'],
        'tipo_label' => 'Categoria',
        'tipo_field' => 'categoria',   // servizi usa 'categoria', non 'tipo'
    ],
    'sponsor' => [
        'title'      => 'Sponsor',
        'subtitle'   => 'Gestisci i partner e gli inserzionisti del portale',
        'icon'       => '⭐',
        'tipi'       => ['Gold','Silver','Bronze'],
        'tipo_label' => 'Livello',
        'tipo_field' => 'livello',
    ],
];

$cfg       = $configs[$section];
$pageTitle = $cfg['title'];
$pageSubtitle = $cfg['subtitle'];
$activeSection = $section;
$tipoField = $cfg['tipo_field'];
$topbarAction = '<button class="btn btn-primary btn-sm" onclick="openNew()">+ Nuovo</button>';
require_once __DIR__ . '/includes/layout.php';

$items = loadData($section);
?>

<div style="display:flex;gap:12px;align-items:center;margin-bottom:20px;">
    <div class="search-bar" style="flex:1;">
        <span>🔍</span>
        <input type="text" placeholder="Cerca..." oninput="filterTable(this,'tbl-main')">
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <?php if (empty($items)): ?>
        <div class="empty-state">
            <div class="icon"><?= $cfg['icon'] ?></div>
            <h3>Nessun elemento</h3>
            <p>Aggiungi il primo elemento cliccando "+ Nuovo".</p>
        </div>
        <?php else: ?>
        <table id="tbl-main">
            <thead><tr>
                <th>Nome</th>
                <th><?= $cfg['tipo_label'] ?></th>
                <th>Contatti</th>
                <th>Evidenza</th>
                <th style="text-align:right;">Azioni</th>
            </tr></thead>
            <tbody>
            <?php foreach ($items as $it): ?>
            <tr>
                <td>
                    <?php if (!empty($it['foto'])): ?>
                    <img src="<?= htmlspecialchars($it['foto']) ?>" style="width:50px;height:36px;object-fit:cover;border-radius:6px;margin-right:8px;vertical-align:middle;">
                    <?php endif; ?>
                    <span style="font-weight:600;"><?= htmlspecialchars((isset($it['nome']) ? $it['nome'] : '')) ?></span>
                    <?php if (!empty($it['descrizione'])): ?>
                    <div style="font-size:11px;color:var(--muted);"><?= htmlspecialchars(mb_substr($it['descrizione'],0,70)) ?>...</div>
                    <?php endif; ?>
                </td>
                <td>
                    <?php $tipo = $it[$tipoField] ?: '';
                    $bc = ($tipo==='Gold')?'badge-gold':(($tipo==='Silver')?'badge-muted':'badge-blue'); ?>
                    <span class="badge <?= $bc ?>"><?= htmlspecialchars($tipo) ?></span>
                </td>
                <td style="font-size:12px;color:var(--text2);">
                    <?php if (!empty($it['telefono'])): ?><div>📞 <?= htmlspecialchars($it['telefono']) ?></div><?php endif; ?>
                    <?php if (!empty($it['sito_web'])): ?><a href="<?= htmlspecialchars($it['sito_web']) ?>" target="_blank" style="color:var(--gold);font-size:11px;">🌐 Sito</a><?php endif; ?>
                    <?php if (!empty($it['indirizzo'])): ?><div style="font-size:11px;">📍 <?= htmlspecialchars(mb_substr($it['indirizzo'],0,40)) ?></div><?php endif; ?>
                </td>
                <td>
                    <button class="star-btn" onclick="toggleEv('<?= htmlspecialchars($it['id']) ?>',this)"><?= !empty($it['evidenza'])?'⭐':'☆' ?></button>
                </td>
                <td style="text-align:right;">
                    <div style="display:flex;gap:6px;justify-content:flex-end;">
                        <button class="btn btn-secondary btn-xs" onclick="editItem('<?= htmlspecialchars($it['id']) ?>')">✏ Modifica</button>
                        <button class="btn btn-danger btn-xs" onclick="deleteItem('<?= htmlspecialchars($it['id']) ?>',this)">🗑</button>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<!-- MODAL -->
<div class="modal-overlay" id="modal-main">
<div class="modal" style="max-width:660px;">
    <div class="modal-header">
        <span class="modal-title" id="modal-main-title">Nuovo elemento</span>
        <button class="modal-close" onclick="closeModal('modal-main')">×</button>
    </div>
    <div class="modal-body">
        <input type="hidden" id="it-id">
        <div class="form-row">
            <div class="form-field" style="grid-column:span 2">
                <label>Nome *</label>
                <input type="text" id="it-nome" placeholder="Nome">
            </div>
        </div>
        <div class="form-row">
            <div class="form-field">
                <label><?= $cfg['tipo_label'] ?></label>
                <select id="it-tipo">
                    <?php foreach ($cfg['tipi'] as $t): ?>
                    <option value="<?= $t ?>"><?= $t ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-field">
                <label>Telefono</label>
                <input type="tel" id="it-telefono" placeholder="0882 12345">
            </div>
        </div>
        <div class="form-field">
            <label>Descrizione</label>
            <textarea id="it-descrizione" rows="3"></textarea>
        </div>
        <?php if ($section === 'locali'): ?>
        <div class="form-field" id="gallery-section">
            <label>Galleria foto <span style="font-size:11px;color:var(--muted);">(opzionale, max 4 foto)</span></label>
            <div id="gallery-slots" style="display:grid;grid-template-columns:1fr 1fr;gap:10px;margin-top:6px;">
                <?php for ($gi = 1; $gi <= 4; $gi++): ?>
                <div class="gallery-slot" data-slot="<?= $gi ?>" style="background:var(--dark3);border:1px dashed var(--border);border-radius:10px;padding:10px;position:relative;">
                    <div style="font-size:11px;color:var(--muted);margin-bottom:6px;font-weight:600;">Foto <?= $gi ?></div>
                    <input type="file" id="gallery-file-<?= $gi ?>" accept="image/*" style="font-size:11px;width:100%;" onchange="previewGallery(<?= $gi ?>)">
                    <div style="margin-top:6px;font-size:11px;color:var(--muted);">oppure URL:</div>
                    <input type="text" id="gallery-url-<?= $gi ?>" placeholder="https://..." style="font-size:12px;margin-top:4px;width:100%;box-sizing:border-box;background:var(--dark2);border:1px solid var(--border);border-radius:6px;padding:5px 8px;color:var(--text);">
                    <img id="gallery-prev-<?= $gi ?>" src="" style="display:none;width:100%;max-height:80px;object-fit:cover;border-radius:6px;margin-top:6px;">
                    <button type="button" onclick="clearGallerySlot(<?= $gi ?>)" style="position:absolute;top:6px;right:6px;background:none;border:none;color:var(--muted);cursor:pointer;font-size:14px;" title="Rimuovi">×</button>
                </div>
                <?php endfor; ?>
            </div>
        </div>
        <?php endif; ?>
        <div class="form-row">
            <div class="form-field">
                <label>Indirizzo</label>
                <input type="text" id="it-indirizzo">
            </div>
            <div class="form-field">
                <label>Sito web</label>
                <input type="url" id="it-sito" placeholder="https://...">
            </div>
        </div>
        <div class="form-row">
            <div class="form-field">
                <label>Latitudine <span style="font-size:11px;color:var(--muted);">(opzionale, es. 41.78)</span></label>
                <input type="number" step="any" id="it-lat" placeholder="41.7830">
            </div>
            <div class="form-field">
                <label>Longitudine <span style="font-size:11px;color:var(--muted);">(opzionale, es. 15.33)</span></label>
                <input type="number" step="any" id="it-lng" placeholder="15.3334">
            </div>
        </div>
        <?php if ($section === 'locali'): ?>
        <div class="form-field" id="orari-strutturati-section">
            <label>Orari di apertura <span style="font-size:11px;color:var(--muted);">(mattina e/o pomeriggio per ogni giorno)</span></label>
            <div style="margin-top:10px;">
                <?php
                $giorni = ['lun'=>'Lunedì','mar'=>'Martedì','mer'=>'Mercoledì','gio'=>'Giovedì','ven'=>'Venerdì','sab'=>'Sabato','dom'=>'Domenica'];
                foreach ($giorni as $key => $label): ?>
                <div id="orari-row-<?= $key ?>" style="display:grid;grid-template-columns:90px 1fr auto;gap:8px;align-items:start;padding:8px 0;border-bottom:1px solid var(--border);">
                    <!-- Giorno + chiuso -->
                    <div style="padding-top:6px;">
                        <div style="font-size:13px;font-weight:700;color:var(--text);"><?= $label ?></div>
                        <label style="display:flex;align-items:center;gap:4px;margin-top:6px;cursor:pointer;font-size:11px;color:var(--muted);">
                            <input type="checkbox" id="orari-<?= $key ?>-chiuso" onchange="toggleGiorno('<?= $key ?>')" style="accent-color:var(--gold);width:14px;height:14px;">
                            Chiuso
                        </label>
                    </div>
                    <!-- Slot orari -->
                    <div id="orari-<?= $key ?>-slots">
                        <div style="display:grid;grid-template-columns:1fr 16px 1fr;gap:4px;align-items:center;margin-bottom:4px;">
                            <input type="time" id="orari-<?= $key ?>-ap1" placeholder="--:--" style="background:var(--dark2);border:1px solid var(--border);border-radius:6px;padding:5px 8px;color:var(--text);font-size:13px;width:100%;box-sizing:border-box;">
                            <span style="text-align:center;color:var(--muted);font-size:11px;">–</span>
                            <input type="time" id="orari-<?= $key ?>-ch1" placeholder="--:--" style="background:var(--dark2);border:1px solid var(--border);border-radius:6px;padding:5px 8px;color:var(--text);font-size:13px;width:100%;box-sizing:border-box;">
                        </div>
                        <div id="orari-<?= $key ?>-slot2" style="display:none;grid-template-columns:1fr 16px 1fr;gap:4px;align-items:center;">
                            <input type="time" id="orari-<?= $key ?>-ap2" placeholder="--:--" style="background:var(--dark2);border:1px solid var(--border);border-radius:6px;padding:5px 8px;color:var(--text);font-size:13px;width:100%;box-sizing:border-box;">
                            <span style="text-align:center;color:var(--muted);font-size:11px;">–</span>
                            <input type="time" id="orari-<?= $key ?>-ch2" placeholder="--:--" style="background:var(--dark2);border:1px solid var(--border);border-radius:6px;padding:5px 8px;color:var(--text);font-size:13px;width:100%;box-sizing:border-box;">
                        </div>
                    </div>
                    <!-- Bottone +pomeriggio -->
                    <div style="padding-top:4px;">
                        <button type="button" id="orari-<?= $key ?>-btn" onclick="toggleSlot2('<?= $key ?>')" title="Aggiungi orario pomeridiano" style="background:var(--dark3);border:1px solid var(--border);border-radius:6px;color:var(--gold);font-size:16px;width:30px;height:30px;cursor:pointer;line-height:1;">+</button>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
            <div style="margin-top:10px;">
                <label style="font-size:12px;color:var(--muted);">Note aggiuntive (es: chiuso festivi, solo su prenotazione...)</label>
                <input type="text" id="it-orario-nota" placeholder="Es: chiuso nei giorni festivi" style="margin-top:4px;font-size:12px;color:var(--text);background:var(--dark2);border:1px solid var(--border);border-radius:6px;padding:6px 10px;width:100%;box-sizing:border-box;">
            </div>
        </div>
        <?php else: ?>
        <div class="form-field">
            <label>Orario</label>
            <input type="text" id="it-orario" placeholder="Lun-Sab 8:30-20:00">
        </div>
        <?php endif; ?>
        <div class="form-row">
            <div class="form-field">
                <label>Foto (carica file)</label>
                <?php if ($section === 'sponsor'): ?>
                <div style="font-size:11px;color:var(--muted);margin-bottom:6px;">
                    📐 Dimensioni consigliate per gli sponsor:<br>
                    &nbsp;• <strong>Gold</strong>: 800×450 px (formato 16:9)<br>
                    &nbsp;• <strong>Silver</strong>: 200×200 px (formato quadrato)<br>
                    &nbsp;• <strong>Bronze</strong>: 120×120 px (logo quadrato)
                </div>
                <?php endif; ?>
                <input type="file" id="it-foto" accept="image/*" onchange="previewImg(this,'it-foto-prev')">
            </div>
            <div class="form-field">
                <label>oppure URL foto</label>
                <input type="text" id="it-foto-url" placeholder="https://...">
            </div>
        </div>
        <img id="it-foto-prev" src="" style="display:none;max-width:200px;max-height:110px;border-radius:8px;margin-bottom:4px;object-fit:cover;">
        <div id="it-foto-del-wrap" style="display:none;margin-bottom:12px;">
            <button type="button" onclick="deleteFotoCorrente()" style="font-size:12px;color:#c0392b;background:none;border:1px solid #c0392b;border-radius:6px;padding:4px 12px;cursor:pointer;">🗑 Elimina foto caricata</button>
        </div>
        <label class="toggle-switch">
            <input type="checkbox" id="it-evidenza">
            <span class="toggle-track"></span>
            <span class="toggle-label">Metti in evidenza nella homepage</span>
        </label>
    </div>
    <div class="modal-footer">
        <button class="btn btn-secondary" onclick="closeModal('modal-main')">Annulla</button>
        <button class="btn btn-primary" onclick="saveItem()">💾 Salva</button>
    </div>
</div>
</div>

<script>
const SECTION    = '<?= $section ?>';
const TIPO_FIELD = '<?= $tipoField ?>';

function openNew() {
    document.getElementById('modal-main-title').textContent = 'Nuovo elemento';
    ['it-id','it-nome','it-telefono','it-descrizione','it-indirizzo','it-sito','it-foto-url','it-lat','it-lng']
        .forEach(id => document.getElementById(id).value = '');
    if (document.getElementById('it-orario')) document.getElementById('it-orario').value = '';
    document.getElementById('it-evidenza').checked = false;
    document.getElementById('it-foto-prev').style.display = 'none';
    document.getElementById('it-foto-del-wrap').style.display = 'none';
    if (SECTION === 'locali') { clearAllGallery(); clearOrariStrutturati(); }
    openModal('modal-main');
}

async function editItem(id) {
    const r = await fetch(`/admin/api/handler.php?action=get_item&section=${SECTION}&id=${encodeURIComponent(id)}`);
    const d = await r.json();
    if (!d.ok) { toast('Errore caricamento','error'); return; }
    const it = d.item;
    document.getElementById('modal-main-title').textContent = 'Modifica elemento';
    document.getElementById('it-id').value           = it.id;
    document.getElementById('it-nome').value         = it.nome || '';
    document.getElementById('it-tipo').value         = it[TIPO_FIELD] || '';
    document.getElementById('it-telefono').value     = it.telefono || '';
    document.getElementById('it-descrizione').value  = it.descrizione || '';
    document.getElementById('it-indirizzo').value    = it.indirizzo || '';
    document.getElementById('it-sito').value         = it.sito_web || '';
    if (document.getElementById('it-orario')) document.getElementById('it-orario').value = it.orario || '';
    document.getElementById('it-foto-url').value     = it.foto || '';
    document.getElementById('it-lat').value           = it.lat || '';
    document.getElementById('it-lng').value           = it.lng || '';
    document.getElementById('it-evidenza').checked   = !!it.evidenza;
    if (SECTION === 'locali') {
        clearAllGallery();
        clearOrariStrutturati();
        const gallery = it.gallery || [];
        for (let i = 0; i < Math.min(gallery.length, 4); i++) {
            if (gallery[i]) {
                document.getElementById('gallery-url-' + (i+1)).value = gallery[i];
                const prev = document.getElementById('gallery-prev-' + (i+1));
                prev.src = gallery[i]; prev.style.display = 'block';
            }
        }
        // Carica orari strutturati
        if (it.orari_strutturati && typeof it.orari_strutturati === 'object') {
            loadOrariStrutturati(it.orari_strutturati);
        }
        if (document.getElementById('it-orario-nota')) {
            document.getElementById('it-orario-nota').value = it.orario_nota || '';
        }
    }
    const prev = document.getElementById('it-foto-prev');
    const delWrap = document.getElementById('it-foto-del-wrap');
    if (it.foto) { prev.src = it.foto; prev.style.display = 'block'; delWrap.style.display = 'block'; }
    else { prev.style.display = 'none'; delWrap.style.display = 'none'; }
    openModal('modal-main');
}

async function saveItem() {
    const nome = document.getElementById('it-nome').value.trim();
    if (!nome) { toast('Nome obbligatorio','error'); return; }
    const fd = new FormData();
    fd.append('action',    SECTION + '_save');
    fd.append('id',        document.getElementById('it-id').value);
    fd.append('nome',      nome);
    fd.append(TIPO_FIELD,  document.getElementById('it-tipo').value);
    fd.append('telefono',  document.getElementById('it-telefono').value);
    fd.append('descrizione', document.getElementById('it-descrizione').value);
    fd.append('indirizzo', document.getElementById('it-indirizzo').value);
    fd.append('sito_web',  document.getElementById('it-sito').value);
    if (SECTION === 'locali') {
        const orariObj = raccogliOrariStrutturati();
        fd.append('orari_strutturati', JSON.stringify(orariObj));
        fd.append('orario_nota', document.getElementById('it-orario-nota') ? document.getElementById('it-orario-nota').value : '');
        // Genera stringa orario leggibile come fallback
        fd.append('orario', generaStringaOrario(orariObj));
    } else {
        fd.append('orario', document.getElementById('it-orario') ? document.getElementById('it-orario').value : '');
    }
    fd.append('lat',       document.getElementById('it-lat').value);
    fd.append('lng',       document.getElementById('it-lng').value);
    fd.append('foto_url',  document.getElementById('it-foto-url').value);
    if (SECTION === 'locali') {
        for (let i = 1; i <= 4; i++) {
            const gFile = document.getElementById('gallery-file-' + i);
            if (gFile && gFile.files[0]) fd.append('gallery_file_' + i, gFile.files[0]);
            const gUrl = document.getElementById('gallery-url-' + i);
            fd.append('gallery_url_' + i, gUrl ? gUrl.value : '');
        }
    }
    if (document.getElementById('it-evidenza').checked) fd.append('evidenza','1');
    const fotoFile = document.getElementById('it-foto');
    if (fotoFile.files[0]) fd.append('foto', fotoFile.files[0]);
    const r = await fetch('/admin/api/handler.php', {method:'POST', body:fd});
    const d = await r.json();
    if (d.ok) { toast('Salvato!'); closeModal('modal-main'); setTimeout(()=>location.reload(), 800); }
    else toast(d.error || 'Errore','error');
}

async function deleteItem(id, btn) {
    if (!confirmDelete()) return;
    const fd = new FormData();
    fd.append('action', SECTION + '_delete');
    fd.append('id', id);
    const r = await fetch('/admin/api/handler.php', {method:'POST', body:fd});
    const d = await r.json();
    if (d.ok) { btn.closest('tr').remove(); toast('Eliminato!'); }
    else toast(d.error || 'Errore','error');
}

async function toggleEv(id, btn) {
    const fd = new FormData();
    fd.append('action', SECTION + '_evidenza');
    fd.append('id', id);
    const r = await fetch('/admin/api/handler.php', {method:'POST', body:fd});
    const d = await r.json();
    if (d.ok) btn.textContent = btn.textContent.trim() === '⭐' ? '☆' : '⭐';
}

function previewImg(input, previewId) {
    const prev = document.getElementById(previewId);
    if (input.files && input.files[0]) {
        const rd = new FileReader();
        rd.onload = e => { prev.src = e.target.result; prev.style.display = 'block'; };
        rd.readAsDataURL(input.files[0]);
    }
}

function previewGallery(slot) {
    const input = document.getElementById('gallery-file-' + slot);
    const prev  = document.getElementById('gallery-prev-' + slot);
    if (input.files && input.files[0]) {
        const rd = new FileReader();
        rd.onload = e => { prev.src = e.target.result; prev.style.display = 'block'; };
        rd.readAsDataURL(input.files[0]);
    }
}

function clearGallerySlot(slot) {
    const fi = document.getElementById('gallery-file-' + slot);
    const ui = document.getElementById('gallery-url-' + slot);
    const pr = document.getElementById('gallery-prev-' + slot);
    if (fi) fi.value = '';
    if (ui) ui.value = '';
    if (pr) { pr.src = ''; pr.style.display = 'none'; }
}

function clearAllGallery() {
    for (let i = 1; i <= 4; i++) clearGallerySlot(i);
}

// ---- Orari strutturati (solo locali) ----
const GIORNI_KEYS = ['lun','mar','mer','gio','ven','sab','dom'];

function toggleGiorno(key) {
    const chiuso = document.getElementById('orari-' + key + '-chiuso').checked;
    ['ap1','ch1','ap2','ch2'].forEach(id => {
        const el = document.getElementById('orari-' + key + '-' + id);
        if (el) { el.disabled = chiuso; if (chiuso) el.value = ''; }
    });
    const btn = document.getElementById('orari-' + key + '-btn');
    if (btn) btn.disabled = chiuso;
    const slot2 = document.getElementById('orari-' + key + '-slot2');
    if (chiuso && slot2) slot2.style.display = 'none';
}

function toggleSlot2(key) {
    const slot2 = document.getElementById('orari-' + key + '-slot2');
    const btn   = document.getElementById('orari-' + key + '-btn');
    if (!slot2) return;
    const visible = slot2.style.display === 'grid';
    slot2.style.display = visible ? 'none' : 'grid';
    btn.textContent = visible ? '+' : '−';
    if (visible) {
        const ap2 = document.getElementById('orari-' + key + '-ap2');
        const ch2 = document.getElementById('orari-' + key + '-ch2');
        if (ap2) ap2.value = '';
        if (ch2) ch2.value = '';
    }
}

function clearOrariStrutturati() {
    GIORNI_KEYS.forEach(k => {
        ['ap1','ch1','ap2','ch2'].forEach(id => {
            const el = document.getElementById('orari-' + k + '-' + id);
            if (el) { el.value = ''; el.disabled = false; }
        });
        const ch = document.getElementById('orari-' + k + '-chiuso');
        if (ch) ch.checked = false;
        const slot2 = document.getElementById('orari-' + k + '-slot2');
        if (slot2) slot2.style.display = 'none';
        const btn = document.getElementById('orari-' + k + '-btn');
        if (btn) { btn.textContent = '+'; btn.disabled = false; }
    });
    const nota = document.getElementById('it-orario-nota');
    if (nota) nota.value = '';
}

function loadOrariStrutturati(orari) {
    GIORNI_KEYS.forEach(k => {
        const g = orari[k] || {};
        const chiuso = !!g.chiuso;
        const ch = document.getElementById('orari-' + k + '-chiuso');
        if (ch) ch.checked = chiuso;
        ['ap1','ch1','ap2','ch2'].forEach(id => {
            const el = document.getElementById('orari-' + k + '-' + id);
            if (el) el.disabled = chiuso;
        });
        if (!chiuso) {
            const ap1 = document.getElementById('orari-' + k + '-ap1');
            const ch1 = document.getElementById('orari-' + k + '-ch1');
            const ap2 = document.getElementById('orari-' + k + '-ap2');
            const ch2 = document.getElementById('orari-' + k + '-ch2');
            if (ap1) ap1.value = g.ap1 || '';
            if (ch1) ch1.value = g.ch1 || '';
            // Secondo slot
            if (g.ap2 && g.ch2) {
                const slot2 = document.getElementById('orari-' + k + '-slot2');
                const btn   = document.getElementById('orari-' + k + '-btn');
                if (slot2) slot2.style.display = 'grid';
                if (btn)   btn.textContent = '−';
                if (ap2) ap2.value = g.ap2;
                if (ch2) ch2.value = g.ch2;
            }
        }
    });
}

function raccogliOrariStrutturati() {
    const obj = {};
    GIORNI_KEYS.forEach(k => {
        const chiuso = document.getElementById('orari-' + k + '-chiuso').checked;
        if (chiuso) { obj[k] = { chiuso: true }; return; }
        const ap1 = (document.getElementById('orari-' + k + '-ap1') || {}).value || '';
        const ch1 = (document.getElementById('orari-' + k + '-ch1') || {}).value || '';
        const ap2 = (document.getElementById('orari-' + k + '-ap2') || {}).value || '';
        const ch2 = (document.getElementById('orari-' + k + '-ch2') || {}).value || '';
        const slot2Visible = (document.getElementById('orari-' + k + '-slot2') || {}).style?.display === 'grid';
        obj[k] = { ap1, ch1 };
        if (slot2Visible && ap2 && ch2) { obj[k].ap2 = ap2; obj[k].ch2 = ch2; }
    });
    return obj;
}

function generaStringaOrario(orari) {
    const nomi = { lun:'Lun', mar:'Mar', mer:'Mer', gio:'Gio', ven:'Ven', sab:'Sab', dom:'Dom' };
    const parti = [];
    GIORNI_KEYS.forEach(k => {
        const g = orari[k] || {};
        if (g.chiuso) { parti.push(nomi[k] + ': Chiuso'); return; }
        const s1 = (g.ap1 && g.ch1) ? g.ap1 + '-' + g.ch1 : '';
        const s2 = (g.ap2 && g.ch2) ? g.ap2 + '-' + g.ch2 : '';
        const slot = [s1, s2].filter(Boolean).join(', ');
        if (slot) parti.push(nomi[k] + ': ' + slot);
    });
    return parti.join(' | ');
}

async function deleteFotoCorrente() {
    const fotoUrl = document.getElementById('it-foto-url').value;
    if (!fotoUrl) { toast('Nessuna foto da eliminare','error'); return; }
    if (!confirm('Eliminare definitivamente la foto caricata?')) return;
    const fd = new FormData();
    fd.append('action', 'foto_delete');
    fd.append('foto_url', fotoUrl);
    const r = await fetch('/admin/api/handler.php', { method:'POST', body:fd });
    const d = await r.json();
    if (d.ok) {
        document.getElementById('it-foto-url').value = '';
        document.getElementById('it-foto-prev').style.display = 'none';
        document.getElementById('it-foto-del-wrap').style.display = 'none';
        toast('Foto eliminata con successo');
    } else {
        toast('Errore durante l\'eliminazione','error');
    }
}
</script>

<?php require_once __DIR__ . '/includes/layout_end.php'; ?>
