<?php
declare(strict_types=1);
require_once __DIR__ . '/../includes/bootstrap.php';
unset($_SESSION['hb5_admin']);
session_regenerate_id(true);
header('Location: /admin/login.php');
exit;
?>