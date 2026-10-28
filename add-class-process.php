<?php
include "dbms.php";
require_admin();

$classname      = trim($_REQUEST["class"] ?? '');
$status         = $_REQUEST["status"] ?? '1';
$subject        = $_REQUEST["subject"] ?? [];
$addedDateTime  = date("Y-m-d H:i:s"); 
$addedIPaddress = $_SERVER["REMOTE_ADDR"] ?? '127.0.0.1';

$_SESSION["class"]  = $classname;
$_SESSION["status"] = $status;

if (strlen($classname) <= 0) {
    header("Location: add-class2.php?err=1");
    exit;
}

$stmt = $conn->prepare("SELECT classid FROM tblclass WHERE classname = :classname");
$stmt->bindParam(":classname", $classname);
$stmt->execute();
if ($stmt->fetch()) {
    header("Location: add-class2.php?err=2");
    exit;
}

$arr = is_array($subject) ? implode("+", $subject) : '';

$stmt = $conn->prepare("
    INSERT INTO tblclass (classname, subject, status, addedDateTime, addedIPaddress)
    VALUES (:classname, :subject, :status, :addedDateTime, :addedIPaddress)
");

$stmt->bindParam(":classname", $classname);
$stmt->bindParam(":subject", $arr);
$stmt->bindParam(":status", $status);
$stmt->bindParam(":addedDateTime", $addedDateTime);
$stmt->bindParam(":addedIPaddress", $addedIPaddress);	
$stmt->execute();

// Audit Log: Record class creation
add_log($conn, 'Admin', $_SESSION['adminid'], $_SESSION['adminusername'], 'INSERT', "Created new class '$classname'");

unset($_SESSION["class"]);
unset($_SESSION["status"]);

header("Location: add-class2.php?msg=1");
exit;
?>
