<?php
include "dbms.php";
require_admin();
include "includes/top.php";
?>
<title>Admin Details</title>
<?php
include "includes/nav.php";
include "includes/left-side.php";

$adminId = $_REQUEST["adminid"] ?? ($_SESSION['adminid'] ?? 1);

$stmt = $conn->prepare("SELECT * FROM tbladmin WHERE adminid = :adminid");
$stmt->bindParam(":adminid", $adminId, PDO::PARAM_INT);
$stmt->execute();
$admin = $stmt->fetch();
?>

<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
        <h2 class="text-primary m-0">Administrator Profile</h2>
        <a href="table.php" class="btn btn-outline-secondary btn-sm">&larr; Back to Dashboard</a>
    </div>

    <div class="row">
        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-dark text-white">
                    <h5 class="m-0">Admin Information</h5>
                </div>
                <div class="card-body">
                    <?php if ($admin): ?>
                        <p class="card-text"><strong>Admin ID:</strong> #<?php echo htmlspecialchars($admin["adminid"]); ?></p>
                        <p class="card-text"><strong>Username:</strong> <?php echo htmlspecialchars($admin["adminusername"]); ?></p>
                        <p class="card-text"><strong>Created Date:</strong> <?php echo htmlspecialchars($admin["addedDatetime"] ?? 'N/A'); ?></p>
                        <p class="card-text"><strong>IP Address:</strong> <?php echo htmlspecialchars($admin["addedipaddress"] ?? 'N/A'); ?></p>
                    <?php else: ?>
                        <p class="text-danger">Admin user not found.</p>
                    <?php endif; ?>
                    <a href="add-admin.php" class="btn btn-primary btn-sm">Create New Admin</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-4">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h5 class="m-0">System Summary</h5>
                </div>
                <div class="card-body">
                    <?php
                        $studentCount = $conn->query("SELECT COUNT(*) FROM tblstudent2")->fetchColumn();
                        $classCount = $conn->query("SELECT COUNT(*) FROM tblclass")->fetchColumn();
                        $subjectCount = $conn->query("SELECT COUNT(*) FROM tblsubject")->fetchColumn();
                    ?>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            Total Students: <strong><?php echo $studentCount; ?></strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            Total Classes: <strong><?php echo $classCount; ?></strong>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            Total Subjects: <strong><?php echo $subjectCount; ?></strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

<?php
include "includes/end.php";
?>
