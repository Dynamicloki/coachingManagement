<?php
include "dbms.php";
require_admin();

include "includes/top.php";
?>
<title>Admin Panel - Manage Test Results</title>
<?php
include "includes/nav.php";
include "includes/left-side.php";

$stmt = $conn->prepare("
    SELECT tr.*, s.studentname, sub.subjectname
    FROM tbltest_results tr
    JOIN tblstudent2 s ON tr.studentid = s.studentid
    JOIN tblsubject sub ON tr.subjectid = sub.subjectid
    ORDER BY tr.testid DESC
");
$stmt->execute();
$results = $stmt->fetchAll();
?>

<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2 flex-wrap gap-2">
    <div>
        <h2 class="text-primary fw-bold m-0">Student Test Results 📊</h2>
        <p class="text-muted m-0">Evaluate, edit scores, and manage examination records</p>
    </div>
    <a href="add-test-result.php" class="btn btn-primary btn-sm rounded-pill px-3">+ Add New Test Result</a>
</div>

<?php if (isset($_GET['msg'])): ?>
    <?php if ($_GET['msg'] === 'added'): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 p-3 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> Test result added successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php elseif ($_GET['msg'] === 'updated'): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 p-3 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> Test result updated successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php elseif ($_GET['msg'] === 'deleted'): ?>
        <div class="alert alert-warning alert-dismissible fade show rounded-4 border-0 p-3 shadow-sm" role="alert">
            <i class="bi bi-trash-fill me-2"></i> Test result deleted successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
<?php endif; ?>

<div class="glass-card border shadow-sm rounded-4 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover table-striped align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th scope="col" style="width: 70px;">#ID</th>
                    <th scope="col">Student Name</th>
                    <th scope="col">Subject</th>
                    <th scope="col">Test Name</th>
                    <th scope="col" class="text-center">Score</th>
                    <th scope="col" class="text-center">Percentage</th>
                    <th scope="col">Date</th>
                    <th scope="col">Remarks</th>
                    <th scope="col" style="width: 140px;" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php if (empty($results)): ?>
                <tr>
                    <td colspan="9" class="text-center text-muted py-5">No test results found. <a href="add-test-result.php">Add one now</a>.</td>
                </tr>
            <?php else: ?>
                <?php foreach ($results as $row): 
                    $pct = ($row['max_marks'] > 0) ? ($row['obtained_marks'] / $row['max_marks']) * 100 : 0;
                ?>
                    <tr>
                        <td class="text-muted fw-bold">#<?php echo $row['testid']; ?></td>
                        <td>
                            <strong class="text-dark"><?php echo htmlspecialchars($row['studentname']); ?></strong>
                            <small class="text-muted d-block">ID: #<?php echo $row['studentid']; ?></small>
                        </td>
                        <td><span class="badge bg-secondary px-2 py-1"><?php echo htmlspecialchars($row['subjectname']); ?></span></td>
                        <td class="fw-bold text-dark"><?php echo htmlspecialchars($row['test_name']); ?></td>
                        <td class="text-center fw-bold fs-6 text-primary">
                            <?php echo htmlspecialchars($row['obtained_marks']); ?> / <?php echo htmlspecialchars($row['max_marks']); ?>
                        </td>
                        <td class="text-center">
                            <span class="badge bg-<?php echo ($pct >= 75) ? 'success' : (($pct >= 50) ? 'primary' : 'danger'); ?> px-3 py-1 rounded-pill">
                                <?php echo number_format($pct, 1); ?>%
                            </span>
                        </td>
                        <td><small class="text-muted"><?php echo htmlspecialchars($row['exam_date'] ?: 'N/A'); ?></small></td>
                        <td><small class="text-muted"><?php echo htmlspecialchars($row['remarks'] ?: '-'); ?></small></td>
                        <td class="text-center">
                            <a href="test-result-edit.php?testid=<?php echo urlencode($row['testid']); ?>" class="btn btn-outline-primary btn-sm rounded-circle me-1" title="Edit Test Result">
                                <i class="bi bi-pencil"></i>
                            </a>
                            <a href="test-result-delete.php?testid=<?php echo urlencode($row['testid']); ?>" class="btn btn-outline-danger btn-sm rounded-circle" onclick="return confirm('Delete this test score?');" title="Delete Test Score">
                                <i class="bi bi-trash"></i>
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
include "includes/end.php";
?>
