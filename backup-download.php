<?php
include "dbms.php";
require_admin();

$backupId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$backupId) {
    header("Location: backups.php?err=" . urlencode("Invalid backup ID requested."));
    exit;
}

$stmt = $conn->prepare("SELECT * FROM tblbackups WHERE backupid = :id");
$stmt->execute([':id' => $backupId]);
$backup = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$backup) {
    header("Location: backups.php?err=" . urlencode("Backup record not found."));
    exit;
}

$backupDir = realpath(__DIR__ . '/backups');
$filePath = realpath($backupDir . DIRECTORY_SEPARATOR . $backup['filename']);

// Security: Prevent Directory Traversal
if (!$filePath || strpos($filePath, $backupDir) !== 0 || !file_exists($filePath)) {
    header("Location: backups.php?err=" . urlencode("Backup file does not exist on disk or path is invalid."));
    exit;
}

$filename = basename($filePath);
$mimeType = ($backup['filetype'] === 'JSON') ? 'application/json' : 'application/octet-stream';

// Audit Log: Record download activity
$adminId = $_SESSION['adminid'] ?? null;
$adminUsername = $_SESSION['adminusername'] ?? 'admin';
add_log($conn, 'Admin', $adminId, $adminUsername, 'DOWNLOAD', "Downloaded monthly backup file: $filename (" . $backup['backup_month'] . ")");

// Send download headers
if (ob_get_level()) {
    ob_end_clean();
}

header('Content-Description: File Transfer');
header('Content-Type: ' . $mimeType);
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Content-Transfer-Encoding: binary');
header('Expires: 0');
header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
header('Pragma: public');
header('Content-Length: ' . filesize($filePath));

readfile($filePath);
exit;
?>