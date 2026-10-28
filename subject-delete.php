<?php
include "dbms.php";
require_admin();

$subjectid = $_REQUEST['subjectid'] ?? null;

if (!empty($subjectid)) {
    try {
        $chk = $conn->prepare("SELECT subjectname FROM tblsubject WHERE subjectid = :id");
        $chk->execute([':id' => $subjectid]);
        $subName = $chk->fetchColumn() ?: "Subject #$subjectid";

        $stmt = $conn->prepare("DELETE FROM tblsubject WHERE subjectid = :id");
        $stmt->bindParam(':id', $subjectid, PDO::PARAM_INT);
        $stmt->execute();

        // Audit Log: Record subject deletion
        add_log($conn, 'Admin', $_SESSION['adminid'], $_SESSION['adminusername'], 'DELETE', "Deleted subject #$subjectid ($subName)");

        header("Location: subjects-table.php?msg=deleted");
        exit;
    } catch (PDOException $e) {
        die("Error deleting subject: " . $e->getMessage());
    }
}

header("Location: subjects-table.php");
exit;
?>
