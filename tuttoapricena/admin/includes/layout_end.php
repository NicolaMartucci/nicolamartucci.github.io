    </div><!-- /page-content -->
</main><!-- /main -->

<div id="toast"></div>

<script>
// ===== SIDEBAR MOBILE TOGGLE =====
function toggleSidebar() {
    const sb = document.getElementById('sidebar');
    const ov = document.getElementById('sidebar-overlay');
    const open = sb.classList.toggle('open');
    if (ov) ov.classList.toggle('open', open);
    document.body.style.overflow = open ? 'hidden' : '';
}
function closeSidebar() {
    const sb = document.getElementById('sidebar');
    const ov = document.getElementById('sidebar-overlay');
    sb?.classList.remove('open');
    ov?.classList.remove('open');
    document.body.style.overflow = '';
}
// Close sidebar on nav link click (mobile)
document.querySelectorAll('.nav-item').forEach(a => {
    a.addEventListener('click', () => {
        if (window.innerWidth <= 768) closeSidebar();
    });
});


function toast(msg, type = 'success') {
    const t = document.getElementById('toast');
    const el = document.createElement('div');
    el.className = 'toast-msg ' + type;
    el.innerHTML = (type === 'success' ? '✓ ' : '✗ ') + msg;
    t.appendChild(el);
    setTimeout(() => el.remove(), 3500);
}

function confirmDelete(msg) {
    return confirm(msg || 'Sei sicuro di voler eliminare questo elemento?');
}

// API helper
async function apiCall(action, data = {}) {
    const fd = new FormData();
    fd.append('action', action);
    for (const [k, v] of Object.entries(data)) {
        fd.append(k, v);
    }
    const r = await fetch('/admin/api/handler.php', { method: 'POST', body: fd });
    return r.json();
}

// Modal helpers
function openModal(id) {
    document.getElementById(id)?.classList.add('open');
}
function closeModal(id) {
    document.getElementById(id)?.classList.remove('open');
}

// Close modals on overlay click
document.addEventListener('click', e => {
    if (e.target.classList.contains('modal-overlay')) {
        e.target.classList.remove('open');
    }
});

// Search filter
function filterTable(input, tableId) {
    const q = input.value.toLowerCase();
    const rows = document.querySelectorAll('#' + tableId + ' tbody tr');
    rows.forEach(r => {
        r.style.display = r.textContent.toLowerCase().includes(q) ? '' : 'none';
    });
}
</script>
</body>
</html>
