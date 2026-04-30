<?php
require_once __DIR__ . '/includes/config.php';
requireAdmin();
$pageTitle = 'Collaboratori';
$pageSubtitle = 'Gestisci accessi e permessi degli utenti';
$activeSection = 'collaboratori';
$topbarAction = '<button class="btn btn-primary btn-sm" onclick="openNewCollab()">+ Nuovo collaboratore</button>';
require_once __DIR__ . '/includes/layout.php';

$users = loadUsers();
$currentUser = getCurrentUser();
$sezioni = ['notizie','eventi','farmacie','servizi','locali','sponsor','media','pagine'];
?>

<div class="card">
    <div class="table-wrap">
        <table>
            <thead><tr>
                <th>Utente</th><th>Ruolo</th><th>Email</th><th>Permessi</th><th style="text-align:right;">Azioni</th>
            </tr></thead>
            <tbody>
            <?php foreach ($users as $u): ?>
            <tr>
                <td>
                    <div style="display:flex;align-items:center;gap:10px;">
                        <div style="width:36px;height:36px;background:linear-gradient(135deg,var(--gold),var(--gold-dark));border-radius:8px;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;color:#0c0c12;">
                            <?= strtoupper(substr((isset($u['name']) ? $u['name'] : $u['username']),0,1)) ?>
                        </div>
                        <div>
                            <div style="font-weight:600;"><?= htmlspecialchars((isset($u['name']) ? $u['name'] : $u['username'])) ?></div>
                            <div style="font-size:11px;color:var(--muted);">@<?= htmlspecialchars($u['username']) ?></div>
                        </div>
                    </div>
                </td>
                <td>
                    <span class="badge <?= $u['role']==='admin'?'badge-gold':'badge-blue' ?>">
                        <?= $u['role'] === 'admin' ? '👑 Admin' : '✏ Collaboratore' ?>
                    </span>
                </td>
                <td style="font-size:13px;color:var(--text2);"><?= htmlspecialchars((isset($u['email']) ? $u['email'] : '')) ?></td>
                <td>
                    <?php if ($u['role'] === 'admin'): ?>
                    <span class="badge badge-gold">Tutti i permessi</span>
                    <?php else: ?>
                    <div style="display:flex;flex-wrap:wrap;gap:4px;">
                        <?php
                        $perms = (isset($u['permissions']) ? $u['permissions'] : []);
                        if (in_array('all',$perms)) { echo '<span class="badge badge-green">Tutto</span>'; }
                        else foreach ($perms as $p) echo '<span class="badge badge-muted">'.htmlspecialchars($p).'</span>';
                        if (empty($perms)) echo '<span style="color:var(--muted);font-size:12px;">Nessuno</span>';
                        ?>
                    </div>
                    <?php endif; ?>
                </td>
                <td style="text-align:right;">
                    <div style="display:flex;gap:6px;justify-content:flex-end;">
                        <button class="btn btn-secondary btn-xs" onclick="editCollab(<?= htmlspecialchars(json_encode($u)) ?>)">✏ Modifica</button>
                        <?php if ($u['username'] !== $currentUser['username']): ?>
                        <button class="btn btn-danger btn-xs" onclick="deleteCollab('<?= $u['username'] ?>')">🗑</button>
                        <?php endif; ?>
                    </div>
                </td>
            </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- MODAL COLLABORATORE -->
<div class="modal-overlay" id="modal-collab">
<div class="modal">
    <div class="modal-header">
        <span class="modal-title" id="modal-collab-title">Nuovo Collaboratore</span>
        <button class="modal-close" onclick="closeModal('modal-collab')">×</button>
    </div>
    <div class="modal-body">
        <div class="form-row">
            <div class="form-field">
                <label>Username *</label>
                <input type="text" id="c-username" placeholder="nomeutente">
            </div>
            <div class="form-field">
                <label>Nome visualizzato</label>
                <input type="text" id="c-name" placeholder="Mario Rossi">
            </div>
        </div>
        <div class="form-row">
            <div class="form-field">
                <label>Email</label>
                <input type="email" id="c-email" placeholder="email@esempio.it">
            </div>
            <div class="form-field">
                <label>Ruolo</label>
                <select id="c-role" onchange="togglePermissions()">
                    <option value="collaboratore">Collaboratore</option>
                    <option value="admin">Amministratore</option>
                </select>
            </div>
        </div>
        <div class="form-row">
            <div class="form-field">
                <label>Password <span id="pwd-hint" style="font-size:10px;color:var(--muted);">(lascia vuoto per non cambiare)</span></label>
                <input type="password" id="c-password" placeholder="Minimo 6 caratteri">
            </div>
            <div class="form-field">
                <label>Conferma password</label>
                <input type="password" id="c-password2">
            </div>
        </div>

        <div id="permissions-block">
            <label style="font-size:12px;font-weight:600;letter-spacing:.06em;text-transform:uppercase;color:var(--text2);display:block;margin-bottom:12px;">Permessi sezioni</label>
            <label class="toggle-switch" style="margin-bottom:10px;">
                <input type="checkbox" id="p-all" onchange="toggleAllPerms(this)">
                <span class="toggle-track"></span>
                <span class="toggle-label">Accesso completo a tutte le sezioni</span>
            </label>
            <div id="perms-list" style="display:grid;grid-template-columns:1fr 1fr;gap:8px;margin-top:12px;">
                <?php foreach ($sezioni as $s): ?>
                <label style="display:flex;align-items:center;gap:8px;font-size:13px;cursor:pointer;padding:8px 10px;background:var(--dark3);border-radius:8px;">
                    <input type="checkbox" class="perm-check" value="<?= $s ?>" style="accent-color:var(--gold);">
                    <?= ucfirst($s) ?>
                </label>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <div class="modal-footer">
        <button class="btn btn-secondary" onclick="closeModal('modal-collab')">Annulla</button>
        <button class="btn btn-primary" onclick="saveCollab()">💾 Salva</button>
    </div>
</div>
</div>

<script>
let isEditMode = false;

function openNewCollab() {
    isEditMode = false;
    document.getElementById('modal-collab-title').textContent = 'Nuovo Collaboratore';
    ['c-username','c-name','c-email','c-password','c-password2'].forEach(id=>document.getElementById(id).value='');
    document.getElementById('c-role').value = 'collaboratore';
    document.getElementById('c-username').disabled = false;
    document.getElementById('pwd-hint').textContent = '';
    document.getElementById('p-all').checked = false;
    document.querySelectorAll('.perm-check').forEach(c=>c.checked=false);
    togglePermissions();
    openModal('modal-collab');
}

function editCollab(u) {
    isEditMode = true;
    document.getElementById('modal-collab-title').textContent = 'Modifica ' + (u.name||u.username);
    document.getElementById('c-username').value = u.username;
    document.getElementById('c-username').disabled = true;
    document.getElementById('c-name').value = u.name||'';
    document.getElementById('c-email').value = u.email||'';
    document.getElementById('c-role').value = u.role||'collaboratore';
    document.getElementById('c-password').value = '';
    document.getElementById('c-password2').value = '';
    document.getElementById('pwd-hint').textContent = '(lascia vuoto per non cambiare)';
    const perms = u.permissions||[];
    document.getElementById('p-all').checked = perms.includes('all');
    document.querySelectorAll('.perm-check').forEach(c=>{c.checked=perms.includes(c.value);});
    togglePermissions();
    openModal('modal-collab');
}

function togglePermissions() {
    const isAdmin = document.getElementById('c-role').value === 'admin';
    document.getElementById('permissions-block').style.display = isAdmin ? 'none' : 'block';
}

function toggleAllPerms(cb) {
    document.getElementById('perms-list').style.opacity = cb.checked ? '0.4' : '1';
    document.getElementById('perms-list').style.pointerEvents = cb.checked ? 'none' : '';
}

async function saveCollab() {
    const username = document.getElementById('c-username').value.trim();
    if (!username) { toast('Username obbligatorio','error'); return; }
    const pwd = document.getElementById('c-password').value;
    const pwd2 = document.getElementById('c-password2').value;
    if (pwd && pwd !== pwd2) { toast('Le password non coincidono','error'); return; }
    if (!isEditMode && !pwd) { toast('Inserisci una password per il nuovo utente','error'); return; }

    const fd = new FormData();
    fd.append('action','collaboratori_save');
    fd.append('username', username);
    fd.append('name', document.getElementById('c-name').value);
    fd.append('email', document.getElementById('c-email').value);
    fd.append('role', document.getElementById('c-role').value);
    if (pwd) fd.append('password', pwd);

    const role = document.getElementById('c-role').value;
    if (role !== 'admin') {
        if (document.getElementById('p-all').checked) {
            fd.append('permissions[]','all');
        } else {
            document.querySelectorAll('.perm-check:checked').forEach(c=>fd.append('permissions[]',c.value));
        }
    }

    const r = await fetch('/admin/api/handler.php',{method:'POST',body:fd});
    const d = await r.json();
    if (d.ok) { toast('Collaboratore salvato!'); closeModal('modal-collab'); setTimeout(()=>location.reload(),800); }
    else toast(d.error||'Errore','error');
}

async function deleteCollab(username) {
    if (!confirmDelete('Eliminare questo collaboratore?')) return;
    const fd = new FormData(); fd.append('action','collaboratori_delete'); fd.append('username',username);
    const r = await fetch('/admin/api/handler.php',{method:'POST',body:fd});
    const d = await r.json();
    if (d.ok) { toast('Eliminato!'); setTimeout(()=>location.reload(),800); }
    else toast(d.error||'Errore','error');
}
</script>

<?php require_once __DIR__ . '/includes/layout_end.php'; ?>
