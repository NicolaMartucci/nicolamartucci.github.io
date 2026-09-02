<?php
require __DIR__ . '/api/auth.php';
fpa_logout();
header('Location: login.php');
exit;
