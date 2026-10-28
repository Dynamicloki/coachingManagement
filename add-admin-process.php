<?php
include "dbms.php";
require_admin();

$adminusername = trim($_REQUEST["adminusername"] ?? '');
$rawPassword   = trim($_REQUEST["adminpassword"] ?? '');

if (empty($adminusername) || empty($rawPassword)) {
    header("Location: add-admin.php?err=1");
    exit;
}

$stmtCheck = $conn->prepare("SELECT adminid FROM tbladmin WHERE adminusername = :username");
$stmtCheck->bindParam(':username', $adminusername);
$stmtCheck->execute();
if ($stmtCheck->fetch()) {
    header("Location: add-admin.php?err=2");
    exit;
}

$adminpassword  = password_hash($rawPassword, PASSWORD_DEFAULT);
$addedDateTime  = date("Y-m-d H:i:s"); 
$addedIPaddress = $_SERVER["REMOTE_ADDR"] ?? '127.0.0.1';

$stmt = $conn->prepare("
    INSERT INTO tbladmin (adminusername, adminpassword, addedDatetime, addedipaddress) 
    VALUES (:adminusername, :adminpassword, :addedDateTime, :addedIPaddress)
");
$stmt->bindParam(':adminusername', $adminusername);
$stmt->bindParam(':adminpassword', $adminpassword); 
$stmt->bindParam(':addedDateTime', $addedDateTime);
$stmt->bindParam(':addedIPaddress', $addedIPaddress);
$stmt->execute();
$newAdminId = $conn->lastInsertId();

// Audit Log: Record admin creation
add_log($conn, 'Admin', $_SESSION['adminid'], $_SESSION['adminusername'], 'INSERT', "Created new admin user #$newAdminId ($adminusername)");

header("Location: add-admin.php?msg=1");
exit;
?>
