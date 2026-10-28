<?php
include "dbms.php";
require_admin();

$subjectid   = trim($_POST["subjectid"] ?? '');
$subjectname = trim($_POST["subjectname"] ?? '');
$status      = $_POST["status"] ?? '1';

if (empty($subjectid) || strlen($subjectname) <= 0) {
    header("Location: subject-edit.php?err=1&subjectid=" . urlencode($subjectid));
    exit;
}

// Check if another subject already has this name
$stmtCheck = $conn->prepare("SELECT subjectid FROM tblsubject WHERE subjectname = :sname AND subjectid != :id");
$stmtCheck->bindParam(':sname', $subjectname);
$stmtCheck->bindParam(':id', $subjectid, PDO::PARAM_INT);
$stmtCheck->execute();
if ($stmtCheck->fetch()) {
    header("Location: subject-edit.php?err=2&subjectid=" . urlencode($subjectid));
    exit;
}

$stmt = $conn->prepare("
    UPDATE tblsubject 
    SET subjectname = :sname, 
        status = :status 
    WHERE subjectid = :id
");
$stmt->bindParam(':sname', $subjectname);
$stmt->bindParam(':status', $status);
$stmt->bindParam(':id', $subjectid, PDO::PARAM_INT);
$stmt->execute();

// Audit Log: Record subject update
add_log($conn, 'Admin', $_SESSION['adminid'], $_SESSION['adminusername'], 'UPDATE', "Updated subject #$subjectid ($subjectname)");

header("Location: subjects-table.php?msg=updated");
exit;
?>
