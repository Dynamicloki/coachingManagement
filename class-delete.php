<?php
include "dbms.php";
require_admin();

$classid = $_REQUEST['classid'] ?? null;

if (!empty($classid)) {
    try {
        $chk = $conn->prepare("SELECT classname FROM tblclass WHERE classid = :id");
        $chk->execute([':id' => $classid]);
        $cName = $chk->fetchColumn() ?: "Class #$classid";

        $stmt = $conn->prepare("DELETE FROM tblclass WHERE classid = :id");
        $stmt->bindParam(':id', $classid, PDO::PARAM_INT);
        $stmt->execute();

        // Audit Log: Record class deletion
        add_log($conn, 'Admin', $_SESSION['adminid'], $_SESSION['adminusername'], 'DELETE', "Deleted class #$classid ($cName)");

        header("Location: class-table.php?msg=deleted");
        exit;
    } catch (PDOException $e) {
        die("Error deleting class: " . $e->getMessage());
    }
}

header("Location: class-table.php");
exit;
?>
