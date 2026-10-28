<?php
include "dbms.php";
require_admin();

$studentid      = trim($_POST['studentid'] ?? '');
$subjectid      = trim($_POST['subjectid'] ?? '');
$test_name      = trim($_POST['test_name'] ?? '');
$obtained_marks = trim($_POST['obtained_marks'] ?? '');
$max_marks      = trim($_POST['max_marks'] ?? '100');
$exam_date      = trim($_POST['exam_date'] ?? date('Y-m-d'));
$remarks        = trim($_POST['remarks'] ?? '');
$addedDateTime  = date("Y-m-d H:i:s");

if (empty($studentid) || empty($subjectid) || empty($test_name) || $obtained_marks === '') {
    header("Location: add-test-result.php?err=1");
    exit;
}

$stmt = $conn->prepare("
    INSERT INTO tbltest_results (studentid, subjectid, test_name, max_marks, obtained_marks, exam_date, remarks, addedDateTime)
    VALUES (:sid, :subid, :tname, :mmarks, :omarks, :edate, :rem, :adt)
");
$stmt->bindParam(':sid', $studentid);
$stmt->bindParam(':subid', $subjectid);
$stmt->bindParam(':tname', $test_name);
$stmt->bindParam(':mmarks', $max_marks);
$stmt->bindParam(':omarks', $obtained_marks);
$stmt->bindParam(':edate', $exam_date);
$stmt->bindParam(':rem', $remarks);
$stmt->bindParam(':adt', $addedDateTime);
$stmt->execute();

// Audit Log: Record insertion
add_log($conn, 'Admin', $_SESSION['adminid'], $_SESSION['adminusername'], 'INSERT', "Added test score '$test_name' ($obtained_marks/$max_marks) for student #$studentid");

header("Location: test-results-table.php?msg=added");
exit;
?>
