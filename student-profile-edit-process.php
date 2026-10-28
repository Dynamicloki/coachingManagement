<?php
include "dbms.php";
require_student();

$studentId = $_SESSION['studentid'];

$studentname        = trim(strip_tags($_POST["studentname"] ?? ''));
$dob                = trim($_POST["dob"] ?? '');
$gender             = trim($_POST["gender"] ?? '1');
$studentmobile      = trim($_POST["studentmobile"] ?? '');
$schoolname         = trim(strip_tags($_POST["schoolname"] ?? ''));
$studentlogin       = trim($_POST["studentlogin"] ?? '');
$fathername         = trim(strip_tags($_POST["fathername"] ?? ''));
$mothername         = trim(strip_tags($_POST["mothername"] ?? ''));
$fathermobilenumber = trim($_POST["fathermobilenumber"] ?? '');
$motherMobileNumber = trim($_POST["motherMobileNumber"] ?? '');
$parantsmail        = trim($_POST["parantsmail"] ?? '');
$address            = trim(strip_tags($_POST["address"] ?? ''));

if (strlen($studentname) <= 0) {
    header("Location: student-profile-edit.php?err=name");
    exit;
}

if (strlen($studentlogin) < 6) {
    header("Location: student-profile-edit.php?err=pass");
    exit;
}

$stmt = $conn->prepare("
    UPDATE tblstudent2 
    SET studentname = :sname,
        dob = :dob,
        gender = :gender,
        studentmobile = :smobile,
        schoolname = :school,
        studentlogin = :pass,
        fathername = :fname,
        mothername = :mname,
        fathermobilenumber = :fmobile,
        motherMobileNumber = :mmobile,
        parantsmail = :pmail,
        address = :addr
    WHERE studentid = :id
");

$stmt->bindParam(':sname', $studentname);
$stmt->bindParam(':dob', $dob);
$stmt->bindParam(':gender', $gender);
$stmt->bindParam(':smobile', $studentmobile);
$stmt->bindParam(':school', $schoolname);
$stmt->bindParam(':pass', $studentlogin);
$stmt->bindParam(':fname', $fathername);
$stmt->bindParam(':mname', $mothername);
$stmt->bindParam(':fmobile', $fathermobilenumber);
$stmt->bindParam(':mmobile', $motherMobileNumber);
$stmt->bindParam(':pmail', $parantsmail);
$stmt->bindParam(':addr', $address);
$stmt->bindParam(':id', $studentId, PDO::PARAM_INT);
$stmt->execute();

// Update session name
$_SESSION['studentname'] = $studentname;

// Audit Log: Record the update
add_log($conn, 'Student', $studentId, $studentname, 'UPDATE', "Student self-updated profile details (Name: $studentname, Mobile: $studentmobile)");

header("Location: student-dashboard.php?msg=profile_updated");
exit;
?>
