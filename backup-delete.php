<?php
include "dbms.php";
require_admin();

$backupId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if ($backupId) {
    $stmt = $conn->prepare("SELECT * FROM tblbackups WHERE backupid = :id");
    $stmt->execute([':id' => $backupId]);
    $backup = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($backup) {
        $backupDir = realpath(__DIR__ . '/backups');
        $filePath = realpath($backupDir . DIRECTORY_SEPARATOR . $backup['filename']);

        // Remove file from disk if it exists safely within backups directory
        if ($filePath && strpos($filePath, $backupDir) === 0 && file_exists($filePath)) {
            unlink($filePath);
        }

        // Remove database record
        $delStmt = $conn->prepare("DELETE FROM tblbackups WHERE backupid = :id");
        $delStmt->execute([':id' => $backupId]);

        // Audit Log: Record deletion
        $adminId = $_SESSION['adminid'] ?? null;
        $adminUsername = $_SESSION['adminusername'] ?? 'admin';
        add_log($conn, 'Admin', $adminId, $adminUsername, 'DELETE', "Deleted monthly backup snapshot: " . $backup['filename']);

        header("Location: backups.php?msg=deleted");
        exit;
    }
}

header("Location: backups.php");
exit;
?>