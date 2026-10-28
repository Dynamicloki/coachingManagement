<?php
include "dbms.php";
require_admin();

$testid         = trim($_POST['testid'] ?? '');
$studentid      = trim($_POST['studentid'] ?? '');
$subjectid      = trim($_POST['subjectid'] ?? '');
$test_name      = trim($_POST['test_name'] ?? '');
$obtained_marks = trim($_POST['obtained_marks'] ?? '');
$max_marks      = trim($_POST['max_marks'] ?? '100');
$exam_date      = trim($_POST['exam_date'] ?? date('Y-m-d'));
$remarks        = trim($_POST['remarks'] ?? '');

if (empty($testid) || empty($studentid) || empty($subjectid) || empty($test_name) || $obtained_marks === '') {
    header("Location: test-result-edit.php?err=1&testid=" . urlencode($testid));
    exit;
}

$stmt = $conn->prepare("
    UPDATE tbltest_results 
    SET studentid = :sid, 
        subjectid = :subid, 
        test_name = :tname, 
        max_marks = :mmarks, 
        obtained_marks = :omarks, 
        exam_date = :edate, 
        remarks = :rem 
    WHERE testid = :id
");
$stmt->bindParam(':sid', $studentid);
$stmt->bindParam(':subid', $subjectid);
$stmt->bindParam(':tname', $test_name);
$stmt->bindParam(':mmarks', $max_marks);
$stmt->bindParam(':omarks', $obtained_marks);
$stmt->bindParam(':edate', $exam_date);
$stmt->bindParam(':rem', $remarks);
$stmt->bindParam(':id', $testid, PDO::PARAM_INT);
$stmt->execute();

// Audit Log: Record update
add_log($conn, 'Admin', $_SESSION['adminid'], $_SESSION['adminusername'], 'UPDATE', "Updated test result #$testid for student #$studentid ($test_name: $obtained_marks/$max_marks)");

header("Location: test-results-table.php?msg=updated");
exit;
?>
