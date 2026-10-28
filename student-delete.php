<?php
include "dbms.php";
require_admin();

$studentid = $_REQUEST['studentid'] ?? null;

if (!empty($studentid)) {
    try {
        // Fetch student name before delete for logging
        $chk = $conn->prepare("SELECT studentname FROM tblstudent2 WHERE studentid = :id");
        $chk->execute([':id' => $studentid]);
        $sName = $chk->fetchColumn() ?: "ID #$studentid";

        $stmt = $conn->prepare("DELETE FROM tblstudent2 WHERE studentid = :id");
        $stmt->bindParam(':id', $studentid, PDO::PARAM_INT);
        $stmt->execute();

        // Audit Log: Record deletion
        add_log($conn, 'Admin', $_SESSION['adminid'], $_SESSION['adminusername'], 'DELETE', "Deleted student record #$studentid ($sName)");

        header("Location: table.php?msg=deleted");
        exit;
    } catch (PDOException $e) {
        die("Error deleting student: " . $e->getMessage());
    }
}

header("Location: table.php");
exit;
?>
