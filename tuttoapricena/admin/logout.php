<?php
require_once __DIR__ . '/includes/config.php';
session_destroy();
setcookie('ta_remember', '', time()-3600, '/');
header('Location: /admin/index.php');
exit;
