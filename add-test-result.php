<?php
include "dbms.php";
require_admin();

include "includes/top.php";
?>
<title>Admin Panel - Add Test Result</title>
<?php
include "includes/nav.php";
include "includes/left-side.php";

$students = $conn->query("SELECT studentid, studentname FROM tblstudent2 ORDER BY studentname ASC")->fetchAll();
$subjects = $conn->query("SELECT subjectid, subjectname FROM tblsubject WHERE status = 1 ORDER BY subjectname ASC")->fetchAll();
?>

<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
        <h2 class="text-primary m-0">Record Student Test Result 📝</h2>
        <a href="test-results-table.php" class="btn btn-outline-secondary btn-sm">View All Results</a>
    </div>

    <?php if (isset($_GET['err'])): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            Please fill in all required fields properly.
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-7">
            <form action="add-test-result-process.php" method="POST" class="card shadow-sm border-0 p-4">
                <div class="mb-3">
                    <label for="studentid" class="form-label fw-semibold">Select Student <span class="text-danger">*</span></label>
                    <select name="studentid" id="studentid" class="form-select" required>
                        <option value="">-- Choose a Student --</option>
                        <?php foreach ($students as $s): ?>
                            <option value="<?php echo $s['studentid']; ?>">
                                #<?php echo $s['studentid']; ?> - <?php echo htmlspecialchars($s['studentname']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="subjectid" class="form-label fw-semibold">Select Subject <span class="text-danger">*</span></label>
                    <select name="subjectid" id="subjectid" class="form-select" required>
                        <option value="">-- Choose a Subject --</option>
                        <?php foreach ($subjects as $sub): ?>
                            <option value="<?php echo $sub['subjectid']; ?>">
                                <?php echo htmlspecialchars($sub['subjectname']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="mb-3">
                    <label for="test_name" class="form-label fw-semibold">Test / Exam Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control" name="test_name" id="test_name" placeholder="e.g. Unit Test 1, Final Exam, Quiz" required>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-6">
                        <label for="obtained_marks" class="form-label fw-semibold">Marks Obtained <span class="text-danger">*</span></label>
                        <input type="number" step="0.1" class="form-control" name="obtained_marks" id="obtained_marks" placeholder="e.g. 85.5" required>
                    </div>
                    <div class="col-sm-6">
                        <label for="max_marks" class="form-label fw-semibold">Maximum Marks <span class="text-danger">*</span></label>
                        <input type="number" step="0.1" class="form-control" name="max_marks" id="max_marks" value="100" required>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="exam_date" class="form-label fw-semibold">Exam Date</label>
                    <input type="date" class="form-control" name="exam_date" id="exam_date" value="<?php echo date('Y-m-d'); ?>">
                </div>

                <div class="mb-3">
                    <label for="remarks" class="form-label fw-semibold">Remarks</label>
                    <input type="text" class="form-control" name="remarks" id="remarks" placeholder="e.g. Excellent progress, Needs improvement">
                </div>

                <div class="mt-4">
                    <button type="submit" class="btn btn-primary">Save Test Result</button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include "includes/end.php";
?>
