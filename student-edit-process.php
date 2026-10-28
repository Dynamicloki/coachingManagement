<?php
include "dbms.php";
require_admin();

$studentid = $_REQUEST["studentid"] ?? null;
if (empty($studentid)) {
    header("Location: table.php");
    exit;
}

$studentname        = trim(strip_tags($_REQUEST["studentname"] ?? ''));
$dob                = trim($_REQUEST["dob"] ?? '');
$class              = trim($_REQUEST["class"] ?? '-1');
$season             = trim($_REQUEST["season"] ?? '-1');
$studentmobile      = trim($_REQUEST["studentmobile"] ?? '');
$schoolname         = trim(strip_tags($_REQUEST["schoolname"] ?? ''));
$gender             = trim($_REQUEST["gender"] ?? '-1');
$studentmail        = trim($_REQUEST["studentmail"] ?? '');
$studentlogin       = trim($_REQUEST["studentlogin"] ?? '');
$fathername         = trim(strip_tags($_REQUEST["fathername"] ?? ''));
$mothername         = trim(strip_tags($_REQUEST["mothername"] ?? ''));
$fathermobilenumber = trim($_REQUEST["fathermobilenumber"] ?? '');
$motherMobileNumber = trim($_REQUEST["motherMobileNumber"] ?? '');
$parantsmail        = trim($_REQUEST["parentsmail"] ?? '');
$parentspassword    = trim($_REQUEST["parentsmailpassword"] ?? '');
$address            = trim(strip_tags($_REQUEST["address"] ?? ''));

// Validations
if (strlen($studentname) <= 0) {
    header("Location: student-edit.php?err=1&studentid=" . urlencode($studentid));
    exit;
}
if (strlen($dob) <= 0) {
    header("Location: student-edit.php?err=2&studentid=" . urlencode($studentid));
    exit;
}
if ($class === '-1' || empty($class)) {
    header("Location: student-edit.php?err=3&studentid=" . urlencode($studentid));
    exit;
}
if ($season === '-1' || empty($season)) {
    header("Location: student-edit.php?err=4&studentid=" . urlencode($studentid));
    exit;
}
if (strlen($schoolname) <= 0) {
    header("Location: student-edit.php?err=6&studentid=" . urlencode($studentid));
    exit;
}
if ($gender === '-1' || empty($gender)) {
    header("Location: student-edit.php?err=7&studentid=" . urlencode($studentid));
    exit;
}
if (strlen($studentlogin) <= 0) {
    header("Location: student-edit.php?err=9&studentid=" . urlencode($studentid));
    exit;
}
if (strlen($fathername) <= 0) {
    header("Location: student-edit.php?err=10&studentid=" . urlencode($studentid));
    exit;
}
if (strlen($mothername) <= 0) {
    header("Location: student-edit.php?err=11&studentid=" . urlencode($studentid));
    exit;
}
if (strlen($fathermobilenumber) <= 0) {
    header("Location: student-edit.php?err=12&studentid=" . urlencode($studentid));
    exit;
}
if (strlen($parentspassword) <= 0) {
    header("Location: student-edit.php?err=15&studentid=" . urlencode($studentid));
    exit;
}
if (strlen($address) <= 0) {
    header("Location: student-edit.php?err=16&studentid=" . urlencode($studentid));
    exit;
}

$stmt = $conn->prepare("
    UPDATE tblstudent2 
    SET studentname = :studentname, 
        dob = :dob, 
        class = :class, 
        season = :season, 
        studentmobile = :studentmobile, 
        schoolname = :schoolname, 
        gender = :gender, 
        studentmail = :studentmail, 
        studentlogin = :studentlogin, 
        fathername = :fathername, 
        mothername = :mothername, 
        fathermobilenumber = :fathermobilenumber, 
        motherMobileNumber = :motherMobileNumber, 
        parantsmail = :parantsmail, 
        parentspassword = :parentspassword, 
        address = :address 
    WHERE studentid = :studentid
");

$stmt->bindParam(":studentid", $studentid, PDO::PARAM_INT);
$stmt->bindParam(":studentname", $studentname);
$stmt->bindParam(":dob", $dob); 
$stmt->bindParam(":class", $class); 
$stmt->bindParam(":season", $season); 
$stmt->bindParam(":studentmobile", $studentmobile);
$stmt->bindParam(":schoolname", $schoolname);
$stmt->bindParam(":gender", $gender);
$stmt->bindParam(":studentmail", $studentmail);
$stmt->bindParam(":studentlogin", $studentlogin);
$stmt->bindParam(":fathername", $fathername);
$stmt->bindParam(":mothername", $mothername);
$stmt->bindParam(":fathermobilenumber", $fathermobilenumber);
$stmt->bindParam(":motherMobileNumber", $motherMobileNumber);
$stmt->bindParam(":parantsmail", $parantsmail);
$stmt->bindParam(":parentspassword", $parentspassword);
$stmt->bindParam(":address", $address);
$stmt->execute();

// Audit Log: Record update
add_log($conn, 'Admin', $_SESSION['adminid'], $_SESSION['adminusername'], 'UPDATE', "Updated details for student #$studentid ($studentname)");

header("Location: table.php?msg=2");
exit;
?>
