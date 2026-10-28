<?php
include "dbms.php";
require_admin();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: backups.php");
    exit;
}

$month = trim($_POST['backup_month'] ?? '');
$format = strtoupper(trim($_POST['format'] ?? 'JSON'));

if (!in_array($format, ['JSON', 'SQLITE'])) {
    $format = 'JSON';
}

// Basic format validation for YYYY-MM
if (!preg_match('/^\d{4}-\d{2}$/', $month)) {
    $month = date('Y-m');
}

$adminUsername = $_SESSION['adminusername'] ?? 'admin';

try {
    $backupId = create_monthly_backup($conn, $month, $adminUsername, $format);
    header("Location: backups.php?msg=created");
    exit;
} catch (Exception $e) {
    header("Location: backups.php?err=" . urlencode($e->getMessage()));
    exit;
}
?>