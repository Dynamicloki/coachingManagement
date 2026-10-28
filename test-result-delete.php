<?php
include "dbms.php";
require_admin();

$testid = $_REQUEST['testid'] ?? null;

if (!empty($testid)) {
    try {
        $stmt = $conn->prepare("DELETE FROM tbltest_results WHERE testid = :id");
        $stmt->bindParam(':id', $testid, PDO::PARAM_INT);
        $stmt->execute();

        // Audit Log: Record deletion
        add_log($conn, 'Admin', $_SESSION['adminid'], $_SESSION['adminusername'], 'DELETE', "Deleted test result #$testid");

        header("Location: test-results-table.php?msg=deleted");
        exit;
    } catch (PDOException $e) {
        die("Error deleting test result: " . $e->getMessage());
    }
}

header("Location: test-results-table.php");
exit;
?>
