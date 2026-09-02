<?php
// ============================================================
// AUTH-CONFIG — credenziali di accesso al pannello admin.
// La password NON è mai salvata in chiaro qui dentro: solo il suo
// hash bcrypt (impossibile da invertire). Per cambiarla non modificare
// questo file a mano: usa la pagina "Cambia password" nel pannello
// (admin/cambia-password.php), che lo riscrive da sola in sicurezza.
// ============================================================

if (!defined('FPARCHITETTO_ADMIN')) {
    http_response_code(403);
    exit('Accesso diretto non consentito.');
}

return [
    'username'      => 'Francesco',
    'password_hash' => '$2b$12$trNlG13VZJMvlWbwDrty4OKAGT2NXgQ3bIWTPLFQR7bUrFS44WGFC',
];
