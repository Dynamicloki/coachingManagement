<?php
include "dbms.php";
require_admin();

$subjectname   = trim($_REQUEST["subjectname"] ?? '');
$status        = $_REQUEST["status"] ?? '1';
$addedDatetime = date("Y-m-d H:i:s"); 
$addedipaddress= $_SERVER["REMOTE_ADDR"] ?? '127.0.0.1';

$_SESSION["subjectname"] = $subjectname;
$_SESSION["status"]      = $status;

if (strlen($subjectname) <= 0) {
    header("Location: add-subject.php?err=1");
    exit;
}

$stmt = $conn->prepare("SELECT subjectid FROM tblsubject WHERE subjectname = :subjectname");
$stmt->bindParam(':subjectname', $subjectname);
$stmt->execute();

if ($stmt->fetch()) {
    header("Location: add-subject.php?err=2");
    exit;
}

$stmt = $conn->prepare("
    INSERT INTO tblsubject (subjectname, status, addedipaddress, addedDatetime) 
    VALUES (:subjectname, :status, :addedipaddress, :addedDatetime)
");
$stmt->bindParam(':subjectname', $subjectname);
$stmt->bindParam(':status', $status);
$stmt->bindParam(':addedDatetime', $addedDatetime);
$stmt->bindParam(':addedipaddress', $addedipaddress);
$stmt->execute();

// Audit Log: Record subject creation
add_log($conn, 'Admin', $_SESSION['adminid'], $_SESSION['adminusername'], 'INSERT', "Created new subject '$subjectname'");

unset($_SESSION["subjectname"]);
unset($_SESSION["status"]);

header("Location: add-subject.php?msg=1");
exit;
?>
