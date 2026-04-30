<?php
require_once __DIR__ . '/includes/config.php';
$pageTitle = 'Farmacie di Turno';
$pageSubtitle = 'Gestisci la rotazione delle farmacie';
$activeSection = 'farmacie';
$topbarAction = '<button class="btn btn-primary btn-sm" onclick="openNewFarmacia()">+ Aggiungi farmacia</button>';
require_once __DIR__ . '/includes/layout.php';
$items = loadData('farmacie');
$oggi = date('z');
$turnoOggi = !empty($items) ? ($oggi % count($items)) : -1;
?>

<div class="card" style="margin-bottom:20px;background:rgba(201,162,39,0.05);border-color:rgba(201,162,39,0.2);">
    <div class="card-body" style="padding:16px 20px;">
        <div style="font-size:13px;color:var(--text2);">
            ℹ️ La rotazione è automatica: la farmacia #1 è di turno il giorno 0 dell'anno, #2 il giorno 1, ecc.
            Riordina l'elenco per cambiare la sequenza. <strong style="color:var(--gold);">Oggi di turno: <?php if (!empty($items)) { $fi = array_values($items); $fi2 = $fi[$turnoOggi >= 0 ? $turnoOggi : 0]; echo htmlspecialchars(isset($fi2['nome']) ? $fi2['nome'] : ''); } else { echo 'nessuna'; } ?></strong>
        </div>
    </div>
</div>

<div class="card">
    <div class="table-wrap">
        <?php if (empty($items)): ?>
        <div class="empty-state"><div class="icon">💊</div><h3>Nessuna farmacia</h3><p>Aggiungi le farmacie.</p></div>
        <?php else: ?>
        <table id="tbl-farmacie">
            <thead><tr>
                <th>#</th><th>Nome</th><th>Indirizzo</th><th>Telefono</th><th>Notturna</th><th style="text-align:right;">Azioni</th>
            </tr></thead>
            <tbody id="tbody-farmacie">
            <?php foreach (array_values($items) as $i => $f): ?>
            <tr data-id="<?= $f['id'] ?>">
                <td>
                    <span style="background:var(--dark3);width:28px;height:28px;border-radius:6px;display:inline-flex;align-items:center;justify-content:center;font-size:12px;font-weight:700;"><?= $i+1 ?></span>
                    <?php if ($i === $turnoOggi): ?><span class="badge badge-gold" style="margin-left:4px;">OGGI</span><?php endif; ?>
                </td>
                <td style="font-weight:600;"><?= htmlspecialchars((isset($f['nome']) ? $f['nome'] : '')) ?></td>
                <td style="font-size:13px;color:var(--text2);"><?= htmlspecialchars((isset($f['indirizzo']) ? $f['indirizzo'] : '')) ?></td>
                <td style="font-size:13px;"><?= htmlspecialchars((isset($f['telefono']) ? $f['telefono'] : '')) ?></td>
                <td><?= !empty($f['notturna']) ? '<span class="badge badge-green">✓ Sì</span>' : '<span class="badge badge-muted">No</span>' ?></td>
                <td style="text-align:right;">
                    <div style="display:flex;gap:6px;justify-content:flex-end;">
                        <button class="btn btn-secondary btn-xs" onclick="editFarmacia('<?= $f['id'] ?>')">✏</button>
                        <button class="btn btn-danger btn-xs" onclick="deleteFarmacia('<?= $f['id'] ?>',this)">🗑</button>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
        <?php endif; ?>
    </div>
</div>

<!-- MODAL FARMACIA -->
<div class="modal-overlay" id="modal-farmacia">
<div class="modal">
    <div class="modal-header">
        <span class="modal-title" id="modal-farmacia-title">Nuova Farmacia</span>
        <button class="modal-close" onclick="closeModal('modal-farmacia')">×</button>
    </div>
    <div class="modal-body">
        <input type="hidden" id="f-id">
        <div class="form-field"><label>Nome farmacia *</label><input type="text" id="f-nome" placeholder="Es. Farmacia Centrale"></div>
        <div class="form-row">
            <div class="form-field"><label>Indirizzo</label><input type="text" id="f-indirizzo" placeholder="Via Roma 1"></div>
            <div class="form-field"><label>Telefono</label><input type="tel" id="f-telefono" placeholder="0882 12345"></div>
        </div>
        <div class="form-row">
            <div class="form-field">
                <label>Latitudine <span style="font-size:11px;color:var(--muted);">(opzionale, es. 41.78)</span></label>
                <input type="number" step="any" id="f-lat" placeholder="41.7830">
            </div>
            <div class="form-field">
                <label>Longitudine <span style="font-size:11px;color:var(--muted);">(opzionale, es. 15.33)</span></label>
                <input type="number" step="any" id="f-lng" placeholder="15.3334">
            </div>
        </div>
        <div class="form-field"><label>Orario</label><input type="text" id="f-orario" placeholder="Lun-Sab 8:30-13 / 16-20"></div>
        <label class="toggle-switch">
            <input type="checkbox" id="f-notturna">
            <span class="toggle-track"></span>
            <span class="toggle-label">Servizio notturno disponibile</span>
        </label>
    </div>
    <div class="modal-footer">
        <button class="btn btn-secondary" onclick="closeModal('modal-farmacia')">Annulla</button>
        <button class="btn btn-primary" onclick="saveFarmacia()">💾 Salva</button>
    </div>
</div>
</div>

<script>
function openNewFarmacia() {
    document.getElementById('modal-farmacia-title').textContent = 'Nuova Farmacia';
    ['f-id','f-nome','f-indirizzo','f-telefono','f-orario','f-lat','f-lng'].forEach(id=>document.getElementById(id).value='');
    document.getElementById('f-notturna').checked = false;
    openModal('modal-farmacia');
}

async function editFarmacia(id) {
    const r = await fetch(`/admin/api/handler.php?action=get_item&section=farmacie&id=${id}`);
    const d = await r.json();
    if (!d.ok) return;
    const it = d.item;
    document.getElementById('modal-farmacia-title').textContent = 'Modifica Farmacia';
    document.getElementById('f-id').value = it.id;
    document.getElementById('f-nome').value = it.nome||'';
    document.getElementById('f-indirizzo').value = it.indirizzo||'';
    document.getElementById('f-telefono').value = it.telefono||'';
    document.getElementById('f-orario').value = it.orario||'';
    document.getElementById('f-notturna').checked = !!it.notturna;
    document.getElementById('f-lat').value = it.lat || '';
    document.getElementById('f-lng').value = it.lng || '';
    openModal('modal-farmacia');
}

async function saveFarmacia() {
    const nome = document.getElementById('f-nome').value.trim();
    if (!nome) { toast('Nome obbligatorio','error'); return; }
    const fd = new FormData();
    fd.append('action','farmacie_save');
    fd.append('id', document.getElementById('f-id').value);
    fd.append('nome', nome);
    fd.append('indirizzo', document.getElementById('f-indirizzo').value);
    fd.append('telefono', document.getElementById('f-telefono').value);
    fd.append('orario', document.getElementById('f-orario').value);
    fd.append('lat', document.getElementById('f-lat').value);
    fd.append('lng', document.getElementById('f-lng').value);
    if (document.getElementById('f-notturna').checked) fd.append('notturna','1');
    const r = await fetch('/admin/api/handler.php',{method:'POST',body:fd});
    const d = await r.json();
    if (d.ok) { toast('Salvato!'); closeModal('modal-farmacia'); setTimeout(()=>location.reload(),800); }
    else toast(d.error||'Errore','error');
}

async function deleteFarmacia(id, btn) {
    if (!confirmDelete('Eliminare questa farmacia?')) return;
    const fd = new FormData(); fd.append('action','farmacie_delete'); fd.append('id',id);
    const r = await fetch('/admin/api/handler.php',{method:'POST',body:fd});
    const d = await r.json();
    if (d.ok) { btn.closest('tr').remove(); toast('Eliminata!'); }
}
</script>

<?php require_once __DIR__ . '/includes/layout_end.php'; ?>
