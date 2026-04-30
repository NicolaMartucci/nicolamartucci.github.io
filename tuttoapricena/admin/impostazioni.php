<?php
require_once __DIR__ . '/includes/config.php';
requireAdmin();
$pageTitle = 'Impostazioni Sito';
$pageSubtitle = 'Modifica le impostazioni generali del portale';
$activeSection = 'impostazioni';
require_once __DIR__ . '/includes/layout.php';

$s = array_merge([
    'nome_sito'=>'TuttoApricena','tagline'=>'','email'=>'','facebook'=>'#','instagram'=>'#',
    'colore_primario'=>'#c9a227','colore_accent'=>'#1a1a2e','meta_description'=>'','analytics_id'=>'',
    'citta_soprannome'=>'','citta_descrizione'=>'','citta_storia'=>'',
    'sezioni_visibili'=>[]
], loadData('settings') ?: []);
$sv = is_array($s['sezioni_visibili']) ? $s['sezioni_visibili'] : [];
function svChecked($sv, $key) { return (!isset($sv[$key]) || $sv[$key] !== false) ? 'checked' : ''; }
?>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:20px;">

<!-- Informazioni generali -->
<div class="card">
    <div class="card-header"><span class="card-title">🏠 Informazioni sito</span></div>
    <div class="card-body">
        <div class="form-field"><label>Nome sito</label><input type="text" id="s-nome" value="<?= htmlspecialchars($s['nome_sito']) ?>"></div>
        <div class="form-field"><label>Tagline</label><input type="text" id="s-tagline" value="<?= htmlspecialchars($s['tagline']) ?>"></div>
        <div class="form-field"><label>Email contatti</label><input type="email" id="s-email" value="<?= htmlspecialchars($s['email']) ?>"></div>
        <div class="form-field"><label>Facebook URL</label><input type="url" id="s-facebook" value="<?= htmlspecialchars($s['facebook']) ?>"></div>
        <div class="form-field"><label>Instagram URL</label><input type="url" id="s-instagram" value="<?= htmlspecialchars($s['instagram']) ?>"></div>
        <button class="btn btn-primary" onclick="saveSettings()">💾 Salva informazioni</button>
    </div>
</div>

<!-- Colori -->
<div class="card">
    <div class="card-header"><span class="card-title">🎨 Colori del sito</span></div>
    <div class="card-body">
        <div class="form-field">
            <label>Colore primario (oro)</label>
            <div style="display:flex;gap:10px;align-items:center;">
                <input type="color" id="s-colore1" value="<?= htmlspecialchars($s['colore_primario']) ?>" style="width:50px;height:40px;border-radius:8px;border:none;background:none;cursor:pointer;">
                <input type="text" id="s-colore1-hex" value="<?= htmlspecialchars($s['colore_primario']) ?>" oninput="document.getElementById('s-colore1').value=this.value" style="flex:1;">
            </div>
        </div>
        <div class="form-field">
            <label>Colore accent (scuro)</label>
            <div style="display:flex;gap:10px;align-items:center;">
                <input type="color" id="s-colore2" value="<?= htmlspecialchars($s['colore_accent']) ?>" style="width:50px;height:40px;border-radius:8px;border:none;background:none;cursor:pointer;">
                <input type="text" id="s-colore2-hex" value="<?= htmlspecialchars($s['colore_accent']) ?>" oninput="document.getElementById('s-colore2').value=this.value" style="flex:1;">
            </div>
        </div>
        <div style="padding:16px;border-radius:10px;background:var(--dark3);margin-bottom:16px;">
            <div style="font-size:12px;color:var(--muted);margin-bottom:8px;">Anteprima colori:</div>
            <div id="color-preview" style="display:flex;gap:8px;">
                <div id="prev-primary" style="flex:1;height:40px;border-radius:8px;background:<?= $s['colore_primario'] ?>;"></div>
                <div id="prev-accent" style="flex:1;height:40px;border-radius:8px;background:<?= $s['colore_accent'] ?>;"></div>
            </div>
        </div>
        <button class="btn btn-primary" onclick="saveSettings()">💾 Salva colori</button>
    </div>
</div>

<!-- SEO -->
<div class="card">
    <div class="card-header"><span class="card-title">🔍 SEO & Analytics</span></div>
    <div class="card-body">
        <div class="form-field"><label>Meta description</label><textarea id="s-meta" rows="3"><?= htmlspecialchars($s['meta_description']) ?></textarea></div>
        <div class="form-field"><label>Google Analytics ID</label><input type="text" id="s-analytics" value="<?= htmlspecialchars($s['analytics_id']) ?>" placeholder="G-XXXXXXXXXX"></div>
        <button class="btn btn-primary" onclick="saveSettings()">💾 Salva SEO</button>
    </div>
</div>

<!-- Sezione città -->
<div class="card">
    <div class="card-header"><span class="card-title">🏛 Sezione città (homepage)</span></div>
    <div class="card-body">
        <div class="form-field"><label>Soprannome città</label><input type="text" id="s-soprannome" value="<?= htmlspecialchars($s['citta_soprannome']) ?>"></div>
        <div class="form-field"><label>Descrizione breve</label><input type="text" id="s-citta-desc" value="<?= htmlspecialchars($s['citta_descrizione']) ?>"></div>
        <div class="form-field"><label>Testo storia</label><textarea id="s-storia" rows="4"><?= htmlspecialchars($s['citta_storia']) ?></textarea></div>
        <button class="btn btn-primary" onclick="saveSettings()">💾 Salva città</button>
    </div>
</div>

</div><!-- /2-col grid -->

<!-- Visibilità sezioni homepage -->
<div class="card" style="margin-top:20px;">
    <div class="card-header"><span class="card-title">👁 Visibilità sezioni homepage</span></div>
    <div class="card-body">
        <p style="font-size:13px;color:var(--text2);margin-bottom:18px;">Attiva o disattiva le singole sezioni della homepage. Le modifiche sono effettive dopo aver <strong>pubblicato il sito</strong>.</p>
        <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
            <?php
            $sezioniDef = [
                'notizie'    => ['emoji'=>'📰','label'=>'Ultime Notizie'],
                'territorio' => ['emoji'=>'🏛','label'=>'Il Territorio (sezione Apricena)'],
                'eventi'     => ['emoji'=>'📅','label'=>'Prossimi Eventi'],
                'locali'     => ['emoji'=>'🍽','label'=>'Locali & Attività'],
                'servizi'    => ['emoji'=>'🔧','label'=>'Servizi Utili'],
                'sponsor'    => ['emoji'=>'⭐','label'=>'Sezione Sponsor'],
                'cta'        => ['emoji'=>'📢','label'=>'Il tuo spazio pubblicitario (CTA)'],
            ];
            foreach ($sezioniDef as $k => $def):
                $checked = svChecked($sv, $k);
            ?>
            <label style="display:flex;align-items:center;gap:12px;background:var(--dark3);border-radius:10px;padding:12px 16px;cursor:pointer;border:1px solid var(--border2);transition:border-color .2s;" onmouseover="this.style.borderColor='var(--gold)'" onmouseout="this.style.borderColor='var(--border2)'">
                <div style="position:relative;flex-shrink:0;">
                    <input type="checkbox" id="sv-<?= $k ?>" <?= $checked ?> style="width:20px;height:20px;accent-color:var(--gold);cursor:pointer;">
                </div>
                <div>
                    <div style="font-size:14px;font-weight:600;color:var(--text);"><?= $def['emoji'] ?> <?= $def['label'] ?></div>
                    <div style="font-size:11px;color:var(--muted);margin-top:2px;">ID: <code style="background:var(--dark4);padding:1px 6px;border-radius:4px;"><?= $k ?></code></div>
                </div>
            </label>
            <?php endforeach; ?>
        </div>
        <div style="margin-top:18px;display:flex;gap:10px;flex-wrap:wrap;">
            <button class="btn btn-primary" onclick="saveVisibility()">💾 Salva visibilità</button>
            <button class="btn btn-secondary" onclick="toggleAll(true)">✅ Attiva tutto</button>
            <button class="btn btn-secondary" onclick="toggleAll(false)">❌ Nascondi tutto</button>
        </div>
        <div style="margin-top:12px;padding:10px 14px;background:rgba(201,162,39,.08);border-radius:8px;border:1px solid rgba(201,162,39,.2);font-size:12px;color:var(--gold);">
            ⚠️ Ricorda di cliccare <strong>Pubblica sito</strong> nella sezione Pubblica per rendere effettive le modifiche.
        </div>
    </div>
</div>

<!-- Cambio password -->
<div class="card" style="margin-top:20px;max-width:500px;">
    <div class="card-header"><span class="card-title">🔒 Cambio password</span></div>
    <div class="card-body">
        <div class="form-field"><label>Password attuale</label><input type="password" id="pwd-old"></div>
        <div class="form-row">
            <div class="form-field"><label>Nuova password</label><input type="password" id="pwd-new"></div>
            <div class="form-field"><label>Conferma</label><input type="password" id="pwd-confirm"></div>
        </div>
        <button class="btn btn-primary" onclick="changePassword()">🔒 Aggiorna password</button>
    </div>
</div>

<script>
document.getElementById('s-colore1').addEventListener('input', function() {
    document.getElementById('s-colore1-hex').value = this.value;
    document.getElementById('prev-primary').style.background = this.value;
});
document.getElementById('s-colore2').addEventListener('input', function() {
    document.getElementById('s-colore2-hex').value = this.value;
    document.getElementById('prev-accent').style.background = this.value;
});

async function saveSettings() {
    const fd = new FormData();
    fd.append('action','settings_save');
    fd.append('nome_sito', document.getElementById('s-nome').value);
    fd.append('tagline', document.getElementById('s-tagline').value);
    fd.append('email', document.getElementById('s-email').value);
    fd.append('facebook', document.getElementById('s-facebook').value);
    fd.append('instagram', document.getElementById('s-instagram').value);
    fd.append('colore_primario', document.getElementById('s-colore1').value);
    fd.append('colore_accent', document.getElementById('s-colore2').value);
    fd.append('meta_description', document.getElementById('s-meta').value);
    fd.append('analytics_id', document.getElementById('s-analytics').value);
    fd.append('citta_soprannome', document.getElementById('s-soprannome').value);
    fd.append('citta_descrizione', document.getElementById('s-citta-desc').value);
    fd.append('citta_storia', document.getElementById('s-storia').value);
    const r = await fetch('/admin/api/handler.php',{method:'POST',body:fd});
    const d = await r.json();
    if (d.ok) toast('Impostazioni salvate!'); else toast(d.error||'Errore','error');
}

async function saveVisibility() {
    const sezioni = ['notizie','territorio','eventi','locali','servizi','sponsor','cta'];
    const sv = {};
    sezioni.forEach(function(k) {
        sv[k] = document.getElementById('sv-'+k).checked;
    });
    const fd = new FormData();
    fd.append('action','settings_visibility_save');
    fd.append('sezioni_visibili', JSON.stringify(sv));
    const r = await fetch('/admin/api/handler.php',{method:'POST',body:fd});
    const d = await r.json();
    if (d.ok) toast('Visibilità salvata! Ricorda di pubblicare il sito.'); else toast(d.error||'Errore','error');
}

function toggleAll(val) {
    ['notizie','territorio','eventi','locali','servizi','sponsor','cta'].forEach(function(k) {
        document.getElementById('sv-'+k).checked = val;
    });
}

async function changePassword() {
    const old_pwd = document.getElementById('pwd-old').value;
    const new_pwd = document.getElementById('pwd-new').value;
    const confirm = document.getElementById('pwd-confirm').value;
    const fd = new FormData();
    fd.append('action','change_password');
    fd.append('old_password', old_pwd);
    fd.append('new_password', new_pwd);
    fd.append('confirm_password', confirm);
    const r = await fetch('/admin/api/handler.php',{method:'POST',body:fd});
    const d = await r.json();
    if (d.ok) { toast('Password aggiornata!'); ['pwd-old','pwd-new','pwd-confirm'].forEach(id=>document.getElementById(id).value=''); }
    else toast(d.error||'Errore','error');
}
</script>

<?php require_once __DIR__ . '/includes/layout_end.php'; ?>
