<?php
include "dbms.php";
require_admin();

// Sanitize inputs
$studentname     = trim(strip_tags($_REQUEST["studentname"] ?? ''));
$studentbirth    = trim($_REQUEST["studentbirth"] ?? '');
$selectclass     = trim($_REQUEST["selectclass"] ?? '-1');
$selectsession   = trim($_REQUEST["selectsession"] ?? '-1');
$studentnumber   = trim($_REQUEST["studentnumber"] ?? '');
$studentschool   = trim(strip_tags($_REQUEST["studentschool"] ?? ''));
$selectgender    = trim($_REQUEST["selectgender"] ?? '-1');
$studentmail     = trim($_REQUEST["studentmail"] ?? '');
$studentpassword = trim($_REQUEST["studentpassword"] ?? '');
$fathername      = trim(strip_tags($_REQUEST["fathername"] ?? ''));
$mothername      = trim(strip_tags($_REQUEST["mothername"] ?? ''));
$fathernumber    = trim($_REQUEST["fathernumber"] ?? '');
$mothernumber    = trim($_REQUEST["mothernumber"] ?? '');
$parentmail      = trim($_REQUEST["parentmail"] ?? '');
$parentpassword  = trim($_REQUEST["parentpassword"] ?? '');
$address         = trim(strip_tags($_REQUEST["address"] ?? ''));

// Save to session for repopulating form on error
$_SESSION["studentname"]     = $studentname;
$_SESSION["studentbirth"]    = $studentbirth;
$_SESSION["selectclass"]     = $selectclass;
$_SESSION["selectsession"]   = $selectsession;
$_SESSION["studentnumber"]   = $studentnumber;
$_SESSION["studentschool"]   = $studentschool;
$_SESSION["selectgender"]    = $selectgender;
$_SESSION["studentmail"]     = $studentmail;
$_SESSION["studentpassword"] = $studentpassword;
$_SESSION["fathername"]      = $fathername;
$_SESSION["mothername"]      = $mothername;
$_SESSION["fathernumber"]    = $fathernumber;
$_SESSION["mothernumber"]    = $mothernumber;
$_SESSION["parentmail"]      = $parentmail;
$_SESSION["parentpassword"]  = $parentpassword;
$_SESSION["address"]         = $address;

// Validations
if (strlen($studentname) <= 0) {
    header("Location: add-student.php?err=1");
    exit;
}
if (strlen($studentbirth) <= 0) {
    header("Location: add-student.php?err=2");
    exit;
}
if ($selectclass === '-1' || empty($selectclass)) {
    header("Location: add-student.php?err=3");
    exit;
}
if ($selectsession === '-1' || empty($selectsession)) {
    header("Location: add-student.php?err=4");
    exit;
}
if (strlen($studentschool) <= 0) {
    header("Location: add-student.php?err=6");
    exit;
}
if ($selectgender === '-1' || empty($selectgender)) {
    header("Location: add-student.php?err=7");
    exit;
}
if (strlen($studentpassword) < 6) {
    header("Location: add-student.php?err=9");
    exit;
}
if (strlen($fathername) <= 0) {
    header("Location: add-student.php?err=10");
    exit;
}
if (strlen($mothername) <= 0) {
    header("Location: add-student.php?err=11");
    exit;
}
if (strlen($fathernumber) <= 0) {
    header("Location: add-student.php?err=12");
    exit;
}
if (strlen($parentpassword) < 6) {
    header("Location: add-student.php?err=15");
    exit;
}
if (strlen($address) <= 0) {
    header("Location: add-student.php?err=16");
    exit;
}

// Check email uniqueness if email provided
if (!empty($studentmail)) {
    $stmtCheck = $conn->prepare("SELECT studentid FROM tblstudent2 WHERE studentmail = :studentmail");
    $stmtCheck->bindParam(":studentmail", $studentmail);
    $stmtCheck->execute();
    if ($stmtCheck->fetch()) {
        header("Location: add-student.php?err=17");
        exit;
    }
}

$addedDateTime  = date("Y-m-d H:i:s");
$addedIPaddress = $_SERVER["REMOTE_ADDR"] ?? '127.0.0.1';

// Insert into SQLite
$stmt = $conn->prepare("
    INSERT INTO tblstudent2 (
        studentname, dob, class, season, studentmobile, schoolname, gender, 
        studentmail, studentlogin, fathername, mothername, fathermobilenumber, 
        motherMobileNumber, parantsmail, parentspassword, address, addedDateTime, addedipaddress
    ) VALUES (
        :studentname, :studentbirth, :selectclass, :selectsession, :studentnumber, :studentschool, :selectgender, 
        :studentmail, :studentpassword, :fathername, :mothername, :fathernumber, 
        :mothernumber, :parentmail, :parentpassword, :address, :addedDateTime, :addedIPaddress
    )
");

$stmt->bindParam(":studentname", $studentname);
$stmt->bindParam(":studentbirth", $studentbirth);
$stmt->bindParam(":selectclass", $selectclass);  
$stmt->bindParam(":selectsession", $selectsession);
$stmt->bindParam(":studentnumber", $studentnumber);
$stmt->bindParam(":studentschool", $studentschool);
$stmt->bindParam(":selectgender", $selectgender);
$stmt->bindParam(":studentmail", $studentmail);
$stmt->bindParam(":studentpassword", $studentpassword);
$stmt->bindParam(":fathername", $fathername);
$stmt->bindParam(":mothername", $mothername);
$stmt->bindParam(":fathernumber", $fathernumber);
$stmt->bindParam(":mothernumber", $mothernumber);
$stmt->bindParam(":parentmail", $parentmail);
$stmt->bindParam(":parentpassword", $parentpassword);
$stmt->bindParam(":address", $address);
$stmt->bindParam(":addedDateTime", $addedDateTime);
$stmt->bindParam(":addedIPaddress", $addedIPaddress);

$stmt->execute();
$newStudentId = $conn->lastInsertId();

// Audit Log: Record student creation
add_log($conn, 'Admin', $_SESSION['adminid'], $_SESSION['adminusername'], 'INSERT', "Registered new student #$newStudentId ($studentname)");

// Clear session draft values
unset($_SESSION["studentname"]);
unset($_SESSION["studentbirth"]);
unset($_SESSION["selectclass"]);
unset($_SESSION["selectsession"]);
unset($_SESSION["studentnumber"]);
unset($_SESSION["studentschool"]);
unset($_SESSION["selectgender"]);
unset($_SESSION["studentmail"]);
unset($_SESSION["studentpassword"]);
unset($_SESSION["fathername"]);
unset($_SESSION["mothername"]);
unset($_SESSION["fathernumber"]);
unset($_SESSION["mothernumber"]);
unset($_SESSION["parentmail"]);
unset($_SESSION["parentpassword"]);
unset($_SESSION["address"]);

header("Location: table.php?msg=1");
exit;
?>
