<?php
include "dbms.php";
require_admin();

$testid = $_REQUEST["testid"] ?? null;
if (empty($testid)) {
    header("Location: test-results-table.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM tbltest_results WHERE testid = :id");
$stmt->bindParam(':id', $testid, PDO::PARAM_INT);
$stmt->execute();
$test = $stmt->fetch();

if (!$test) {
    header("Location: test-results-table.php");
    exit;
}

$students = $conn->query("SELECT studentid, studentname FROM tblstudent2 ORDER BY studentname ASC")->fetchAll();
$subjects = $conn->query("SELECT subjectid, subjectname FROM tblsubject WHERE status = 1 ORDER BY subjectname ASC")->fetchAll();

include "includes/top.php";
?>
<title>Edit Test Result - Admin Panel</title>
<?php
include "includes/nav.php";
include "includes/left-side.php";
?>

<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2 flex-wrap gap-2">
        <div>
            <h2 class="text-primary fw-bold m-0">Edit Test Result #<?php echo htmlspecialchars($test['testid']); ?> ✏️</h2>
            <p class="text-muted m-0">Modify test score, subject, student, and evaluation remarks</p>
        </div>
        <a href="test-results-table.php" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i> Back to Test Results
        </a>
    </div>

    <?php if (isset($_GET['err'])): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 p-3 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            Please fill in all required fields properly.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-7">
            <form action="test-result-edit-process.php" method="POST" class="glass-card p-4 p-md-5 border shadow-sm rounded-4">
                <input type="hidden" name="testid" value="<?php echo htmlspecialchars($test['testid']); ?>">

                <div class="mb-3">
                    <label for="studentid" class="form-label fw-semibold">Select Student <span class="text-danger">*</span></label>
                    <select name="studentid" id="studentid" class="form-select form-select-lg fs-6" required>
                        <option value="">-- Choose a Student --</option>
                        <?php foreach ($students as $s): ?>
                            <option value="<?php echo $s['studentid']; ?>" <?php if ($test['studentid'] == $s['studentid']) echo 'selected'; ?>>
                                #<?php echo $s['studentid']; ?> - <?php echo htmlspecialchars($s['studentname']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="subjectid" class="form-label fw-semibold">Select Subject <span class="text-danger">*</span></label>
                    <select name="subjectid" id="subjectid" class="form-select form-select-lg fs-6" required>
                        <option value="">-- Choose a Subject --</option>
                        <?php foreach ($subjects as $sub): ?>
                            <option value="<?php echo $sub['subjectid']; ?>" <?php if ($test['subjectid'] == $sub['subjectid']) echo 'selected'; ?>>
                                <?php echo htmlspecialchars($sub['subjectname']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="test_name" class="form-label fw-semibold">Test / Exam Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-lg fs-6" name="test_name" id="test_name" value="<?php echo htmlspecialchars($test['test_name']); ?>" required>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-6">
                        <label for="obtained_marks" class="form-label fw-semibold">Marks Obtained <span class="text-danger">*</span></label>
                        <input type="number" step="0.1" class="form-control form-control-lg fs-6" name="obtained_marks" id="obtained_marks" value="<?php echo htmlspecialchars($test['obtained_marks']); ?>" required>
                    </div>
                    <div class="col-sm-6">
                        <label for="max_marks" class="form-label fw-semibold">Maximum Marks <span class="text-danger">*</span></label>
                        <input type="number" step="0.1" class="form-control form-control-lg fs-6" name="max_marks" id="max_marks" value="<?php echo htmlspecialchars($test['max_marks']); ?>" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="exam_date" class="form-label fw-semibold">Exam Date</label>
                    <input type="date" class="form-control" name="exam_date" id="exam_date" value="<?php echo htmlspecialchars($test['exam_date'] ?? date('Y-m-d')); ?>">
                </div>

                <div class="mb-4">
                    <label for="remarks" class="form-label fw-semibold">Remarks</label>
                    <input type="text" class="form-control" name="remarks" id="remarks" value="<?php echo htmlspecialchars($test['remarks'] ?? ''); ?>" placeholder="e.g. Improved score, Good conceptual understanding">
                </div>

                <div>
                    <button type="submit" class="btn btn-gradient-primary btn-lg rounded-pill px-4 fw-bold">
                        <i class="bi bi-save2 me-2"></i> Update Test Score
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include "includes/end.php";
?>
