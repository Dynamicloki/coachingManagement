<?php
include "dbms.php";
require_admin();

$classid   = trim($_POST["classid"] ?? '');
$classname = trim($_POST["class"] ?? '');
$status    = $_POST["status"] ?? '1';
$subject   = $_POST["subject"] ?? [];

if (empty($classid) || strlen($classname) <= 0) {
    header("Location: class-edit.php?err=1&classid=" . urlencode($classid));
    exit;
}

// Check if another class already has this name
$stmtCheck = $conn->prepare("SELECT classid FROM tblclass WHERE classname = :cname AND classid != :id");
$stmtCheck->bindParam(':cname', $classname);
$stmtCheck->bindParam(':id', $classid, PDO::PARAM_INT);
$stmtCheck->execute();
if ($stmtCheck->fetch()) {
    header("Location: class-edit.php?err=2&classid=" . urlencode($classid));
    exit;
}

$arr = is_array($subject) ? implode("+", $subject) : '';

$stmt = $conn->prepare("
    UPDATE tblclass 
    SET classname = :cname, 
        subject = :sub, 
        status = :status 
    WHERE classid = :id
");
$stmt->bindParam(':cname', $classname);
$stmt->bindParam(':sub', $arr);
$stmt->bindParam(':status', $status);
$stmt->bindParam(':id', $classid, PDO::PARAM_INT);
$stmt->execute();

// Audit Log: Record class update
add_log($conn, 'Admin', $_SESSION['adminid'], $_SESSION['adminusername'], 'UPDATE', "Updated class #$classid ($classname)");

header("Location: class-table.php?msg=updated");
exit;
?>
