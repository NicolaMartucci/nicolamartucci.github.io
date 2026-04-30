<?php
require_once __DIR__ . '/includes/config.php';
$pageTitle = 'Eventi';
$pageSubtitle = 'Gestisci il calendario eventi';
$activeSection = 'eventi';
$topbarAction = '<button class="btn btn-primary btn-sm" onclick="openNewEvento()">+ Nuovo evento</button>';
require_once __DIR__ . '/includes/layout.php';

$items = loadData('eventi');
usort($items, function($a,$b) { return strcmp((isset($b['data']) ? $b['data'] : ''), (isset($a['data']) ? $a['data'] : '')); });
$categorie = ['Sagra','Concerto','Sport','Cultura','Religioso','Fiera','Teatro','Spettacolo','Altro'];
?>

<div style="display:flex;gap:12px;align-items:center;margin-bottom:20px;">
    <div class="search-bar" style="flex:1;">
        <span>🔍</span>
        <input type="text" placeholder="Cerca eventi..." oninput="filterTable(this,'tbl-eventi')">
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <?php if (empty($items)): ?>
        <div class="empty-state">
            <div class="icon">📅</div>
            <h3>Nessun evento</h3>
            <p>Aggiungi il primo evento cliccando "+ Nuovo evento".</p>
        </div>
        <?php else: ?>
        <table id="tbl-eventi">
            <thead><tr>
                <th style="width:60px;">Foto</th>
                <th>Evento</th>
                <th>Data</th>
                <th>Luogo</th>
                <th>Biglietti</th>
                <th>Evidenza</th>
                <th style="text-align:right;">Azioni</th>
            </tr></thead>
            <tbody>
            <?php foreach ($items as $ev): ?>
            <tr>
                <td>
                    <?php if (!empty($ev['foto'])): ?>
                    <img src="<?= htmlspecialchars($ev['foto']) ?>" class="img-preview">
                    <?php else: ?>
                    <div style="width:80px;height:55px;background:var(--dark3);border-radius:6px;display:flex;align-items:center;justify-content:center;font-size:22px;">📅</div>
                    <?php endif; ?>
                </td>
                <td>
                    <div style="font-weight:600;"><?= htmlspecialchars((isset($ev['titolo']) ? $ev['titolo'] : '')) ?></div>
                    <?php if (!empty($ev['categoria'])): ?>
                    <span class="badge badge-blue" style="margin-top:4px;"><?= htmlspecialchars($ev['categoria']) ?></span>
                    <?php endif; ?>
                </td>
                <td>
                    <div style="font-size:13px;font-weight:600;"><?= $ev['data'] ? date('d/m/Y', strtotime($ev['data'])) : '' ?></div>
                    <div style="font-size:11px;color:var(--muted);"><?= htmlspecialchars((isset($ev['ora']) ? $ev['ora'] : '')) ?></div>
                </td>
                <td style="font-size:13px;color:var(--text2);"><?= htmlspecialchars((isset($ev['luogo']) ? $ev['luogo'] : '')) ?></td>
                <td>
                    <?php if (!empty($ev['link_biglietti'])): ?>
                    <a href="<?= htmlspecialchars($ev['link_biglietti']) ?>" target="_blank" class="badge badge-green">🎟 Link</a>
                    <?php else: ?>
                    <span style="color:var(--muted);font-size:12px;">—</span>
                    <?php endif; ?>
                </td>
                <td>
                    <button class="star-btn" onclick="toggleEvidenza('eventi','<?= $ev['id'] ?>',this)">
                        <?= $ev['evidenza'] ? '⭐' : '☆' ?>
                    </button>
                </td>
                <td style="text-align:right;">
                    <div style="display:flex;gap:6px;justify-content:flex-end;">
                        <button class="btn btn-secondary btn-xs" onclick="editEvento('<?= $ev['id'] ?>')">✏ Modifica</button>
                        <button class="btn btn-danger btn-xs" onclick="deleteItem('eventi','<?= $ev['id'] ?>',this)">🗑</button>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<!-- MODAL EVENTO -->
<div class="modal-overlay" id="modal-evento">
<div class="modal" style="max-width:720px;">
    <div class="modal-header">
        <span class="modal-title" id="modal-evento-title">Nuovo Evento</span>
        <button class="modal-close" onclick="closeModal('modal-evento')">×</button>
    </div>
    <div class="modal-body">
        <input type="hidden" id="ev-id">
        <div class="form-field">
            <label>Titolo evento *</label>
            <input type="text" id="ev-titolo" placeholder="Nome dell'evento">
        </div>
        <div class="form-row-3">
            <div class="form-field">
                <label>Categoria</label>
                <select id="ev-categoria">
                    <option value="">Seleziona...</option>
                    <?php foreach ($categorie as $c): ?>
                    <option value="<?= $c ?>"><?= $c ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-field">
                <label>Data</label>
                <input type="date" id="ev-data">
            </div>
            <div class="form-field">
                <label>Orario</label>
                <input type="time" id="ev-ora">
            </div>
        </div>
        <div class="form-field">
            <label>Luogo</label>
            <input type="text" id="ev-luogo" placeholder="Es. Piazza Municipio, Apricena">
        </div>
        <div class="form-field">
            <label>Descrizione</label>
            <textarea id="ev-descrizione" rows="4" placeholder="Descrizione dell'evento..."></textarea>
        </div>
        <input type="hidden" id="ev-foto-existing">
        <div class="form-row">
            <div class="form-field">
                <label>Foto (carica)</label>
                <input type="file" id="ev-foto" accept="image/*" onchange="previewImg(this,'ev-foto-prev')">
            </div>
            <div class="form-field">
                <label>URL foto</label>
                <input type="text" id="ev-foto-url" placeholder="https://...">
                <small>Incolla l'URL di un'immagine online</small>
            </div>
        </div>
        <div id="ev-foto-prev-wrap" style="display:none;margin-bottom:12px;">
            <img id="ev-foto-prev" src="" style="max-width:200px;max-height:120px;border-radius:8px;object-fit:cover;display:block;">
            <button type="button" class="btn btn-danger btn-xs" style="margin-top:6px;" onclick="deleteEvFoto()">🗑 Elimina foto</button>
        </div>
        <div class="form-field">
            <label>🎟 Link biglietti (opzionale)</label>
            <input type="url" id="ev-biglietti" placeholder="https://www.eventbrite.it/...">
            <small>URL per acquistare i biglietti (Eventbrite, TicketOne, ecc.)</small>
        </div>
        <label class="toggle-switch">
            <input type="checkbox" id="ev-evidenza">
            <span class="toggle-track"></span>
            <span class="toggle-label">Metti in evidenza nella homepage</span>
        </label>
    </div>
    <div class="modal-footer">
        <button class="btn btn-secondary" onclick="closeModal('modal-evento')">Annulla</button>
        <button class="btn btn-primary" onclick="saveEvento()">💾 Salva evento</button>
    </div>
</div>
</div>

<script>
function openNewEvento() {
    document.getElementById('modal-evento-title').textContent = 'Nuovo Evento';
    document.getElementById('ev-id').value = '';
    ['ev-titolo','ev-luogo','ev-descrizione','ev-foto-url','ev-biglietti'].forEach(id => document.getElementById(id).value = '');
    document.getElementById('ev-data').value = '';
    document.getElementById('ev-ora').value = '';
    document.getElementById('ev-categoria').value = '';
    document.getElementById('ev-evidenza').checked = false;
    document.getElementById('ev-foto-prev-wrap').style.display = 'none';
    document.getElementById('ev-foto-existing').value = '';
    openModal('modal-evento');
}

async function editEvento(id) {
    const r = await fetch(`/admin/api/handler.php?action=get_item&section=eventi&id=${id}`);
    const d = await r.json();
    if (!d.ok) { toast('Errore','error'); return; }
    const it = d.item;
    document.getElementById('modal-evento-title').textContent = 'Modifica Evento';
    document.getElementById('ev-id').value = it.id;
    document.getElementById('ev-titolo').value = it.titolo||'';
    document.getElementById('ev-categoria').value = it.categoria||'';
    document.getElementById('ev-data').value = it.data||'';
    document.getElementById('ev-ora').value = it.ora||'';
    document.getElementById('ev-luogo').value = it.luogo||'';
    document.getElementById('ev-descrizione').value = it.descrizione||'';
    document.getElementById('ev-foto-url').value = it.foto||'';
    document.getElementById('ev-foto-existing').value = it.foto||'';
    document.getElementById('ev-biglietti').value = it.link_biglietti||'';
    document.getElementById('ev-evidenza').checked = !!it.evidenza;
    const prev = document.getElementById('ev-foto-prev');
    const prevWrap = document.getElementById('ev-foto-prev-wrap');
    if (it.foto) { prev.src=it.foto; prevWrap.style.display='block'; } else prevWrap.style.display='none';
    openModal('modal-evento');
}

async function saveEvento() {
    const titolo = document.getElementById('ev-titolo').value.trim();
    if (!titolo) { toast('Il titolo è obbligatorio','error'); return; }
    const fd = new FormData();
    fd.append('action','eventi_save');
    fd.append('id', document.getElementById('ev-id').value);
    fd.append('titolo', titolo);
    fd.append('categoria', document.getElementById('ev-categoria').value);
    fd.append('data', document.getElementById('ev-data').value);
    fd.append('ora', document.getElementById('ev-ora').value);
    fd.append('luogo', document.getElementById('ev-luogo').value);
    fd.append('descrizione', document.getElementById('ev-descrizione').value);
    fd.append('foto_url', document.getElementById('ev-foto-url').value);
    fd.append('link_biglietti', document.getElementById('ev-biglietti').value);
    if (document.getElementById('ev-evidenza').checked) fd.append('evidenza','1');
    const fotoFile = document.getElementById('ev-foto');
    if (fotoFile.files[0]) fd.append('foto', fotoFile.files[0]);
    const r = await fetch('/admin/api/handler.php',{method:'POST',body:fd});
    const d = await r.json();
    if (d.ok) { toast('Evento salvato!'); closeModal('modal-evento'); setTimeout(()=>location.reload(),800); }
    else toast(d.error||'Errore','error');
}

function previewImg(input, previewId) {
    const prev = document.getElementById(previewId);
    const wrap = document.getElementById('ev-foto-prev-wrap');
    if (input.files && input.files[0]) {
        const r = new FileReader(); r.onload = e => { prev.src=e.target.result; wrap.style.display='block'; };
        r.readAsDataURL(input.files[0]);
    }
}

async function deleteEvFoto() {
    const existing = document.getElementById('ev-foto-existing').value;
    if (existing) {
        const fd = new FormData();
        fd.append('action','foto_delete');
        fd.append('foto_url', existing);
        await fetch('/admin/api/handler.php', {method:'POST', body:fd});
    }
    document.getElementById('ev-foto-existing').value = '';
    document.getElementById('ev-foto-url').value = '';
    document.getElementById('ev-foto').value = '';
    document.getElementById('ev-foto-prev').src = '';
    document.getElementById('ev-foto-prev-wrap').style.display = 'none';
    toast('Foto eliminata');
}

async function deleteItem(section, id, btn) {
    if (!confirmDelete()) return;
    const fd = new FormData(); fd.append('action',section+'_delete'); fd.append('id',id);
    const r = await fetch('/admin/api/handler.php',{method:'POST',body:fd});
    const d = await r.json();
    if (d.ok) { btn.closest('tr').remove(); toast('Eliminato!'); }
    else toast(d.error||'Errore','error');
}

async function toggleEvidenza(section, id, btn) {
    const fd = new FormData(); fd.append('action',section+'_evidenza'); fd.append('id',id);
    const r = await fetch('/admin/api/handler.php',{method:'POST',body:fd});
    const d = await r.json();
    if (d.ok) { btn.textContent = btn.textContent.trim()==='⭐'?'☆':'⭐'; }
}
</script>

<?php require_once __DIR__ . '/includes/layout_end.php'; ?>
