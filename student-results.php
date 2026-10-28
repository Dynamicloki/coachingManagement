<?php
include "dbms.php";
require_student();

$studentId = $_SESSION['studentid'];

// Fetch test results for this student
$stmt = $conn->prepare("
    SELECT tr.*, sub.subjectname 
    FROM tbltest_results tr
    JOIN tblsubject sub ON tr.subjectid = sub.subjectid
    WHERE tr.studentid = :id
    ORDER BY tr.exam_date DESC, tr.testid DESC
");
$stmt->bindParam(':id', $studentId, PDO::PARAM_INT);
$stmt->execute();
$results = $stmt->fetchAll();

// Calculate totals
$totalMax = 0;
$totalObtained = 0;
$subjectStats = [];

foreach ($results as $r) {
    $totalMax += $r['max_marks'];
    $totalObtained += $r['obtained_marks'];
    
    $subName = $r['subjectname'];
    if (!isset($subjectStats[$subName])) {
        $subjectStats[$subName] = ['max' => 0, 'obtained' => 0, 'tests' => 0];
    }
    $subjectStats[$subName]['max'] += $r['max_marks'];
    $subjectStats[$subName]['obtained'] += $r['obtained_marks'];
    $subjectStats[$subName]['tests'] += 1;
}
$overallPercent = ($totalMax > 0) ? ($totalObtained / $totalMax) * 100 : 0;

function get_grade($pct) {
    if ($pct >= 90) return ['grade' => 'A+', 'color' => 'success', 'desc' => 'Outstanding'];
    if ($pct >= 80) return ['grade' => 'A', 'color' => 'primary', 'desc' => 'Excellent'];
    if ($pct >= 70) return ['grade' => 'B', 'color' => 'info', 'desc' => 'Very Good'];
    if ($pct >= 60) return ['grade' => 'C', 'color' => 'warning', 'desc' => 'Good'];
    if ($pct >= 50) return ['grade' => 'D', 'color' => 'secondary', 'desc' => 'Needs Work'];
    return ['grade' => 'F', 'color' => 'danger', 'desc' => 'Retake Recommended'];
}

$overallGrade = get_grade($overallPercent);

include "includes/top.php";
?>
<title>My Academic Test Results - Apex Student Portal</title>
<?php
include "includes/student-nav.php";
?>

<div class="container pb-5">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h2 class="text-primary fw-bold m-0">Academic Performance & Test Analytics 📊</h2>
            <p class="text-muted m-0">Detailed breakdown of scores across all subject assessments</p>
        </div>
        <a href="student-dashboard.php" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i> Back to Profile
        </a>
    </div>

    <!-- Overall Standing Banner -->
    <div class="card border-0 shadow-sm rounded-4 p-4 mb-4 text-white" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);">
        <div class="row align-items-center g-4">
            <div class="col-md-3 text-center border-end border-white-10">
                <span class="text-white-50 small text-uppercase fw-bold d-block mb-1">Overall Grade</span>
                <div class="display-3 fw-bold text-warning mb-1"><?php echo $overallGrade['grade']; ?></div>
                <span class="badge bg-warning bg-opacity-25 text-warning px-3 py-1 rounded-pill small">
                    <?php echo $overallGrade['desc']; ?>
                </span>
            </div>
            
            <div class="col-md-9">
                <div class="row g-3 text-center text-md-start">
                    <div class="col-sm-4">
                        <span class="text-white-50 small d-block mb-1">Total Marks Scored</span>
                        <h3 class="fw-bold m-0"><?php echo number_format($totalObtained, 1); ?> <small class="text-white-50 fs-6">/ <?php echo number_format($totalMax, 0); ?></small></h3>
                        <div class="progress mt-2" style="height: 6px; background: rgba(255,255,255,0.1);">
                            <div class="progress-bar bg-info" style="width: <?php echo min(100, $overallPercent); ?>%"></div>
                        </div>
                    </div>
                    
                    <div class="col-sm-4">
                        <span class="text-white-50 small d-block mb-1">Cumulative Average</span>
                        <h3 class="fw-bold m-0 text-success"><?php echo number_format($overallPercent, 1); ?>%</h3>
                        <small class="text-white-50">Across <?php echo count($results); ?> evaluations</small>
                    </div>

                    <div class="col-sm-4">
                        <span class="text-white-50 small d-block mb-1">Subjects Enrolled</span>
                        <h3 class="fw-bold m-0 text-white"><?php echo count($subjectStats); ?> Subjects</h3>
                        <small class="text-white-50">Full semester curriculum</small>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Subject Performance Progress Cards -->
    <h5 class="fw-bold text-dark mb-3"><i class="bi bi-pie-chart text-primary me-2"></i> Subject Mastery Breakdown</h5>
    <div class="row g-3 mb-4">
        <?php foreach ($subjectStats as $subName => $data): 
            $subPct = ($data['max'] > 0) ? ($data['obtained'] / $data['max']) * 100 : 0;
            $subGrd = get_grade($subPct);
        ?>
            <div class="col-md-3 col-sm-6">
                <div class="glass-card p-3 border h-100">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <h6 class="fw-bold text-dark m-0"><?php echo htmlspecialchars($subName); ?></h6>
                        <span class="badge bg-<?php echo $subGrd['color']; ?> rounded-pill"><?php echo $subGrd['grade']; ?></span>
                    </div>
                    <div class="d-flex justify-content-between text-muted small mb-1">
                        <span>Score: <strong><?php echo $data['obtained']; ?>/<?php echo $data['max']; ?></strong></span>
                        <span class="fw-bold text-dark"><?php echo number_format($subPct, 1); ?>%</span>
                    </div>
                    <div class="progress" style="height: 6px;">
                        <div class="progress-bar bg-<?php echo $subGrd['color']; ?>" style="width: <?php echo min(100, $subPct); ?>%"></div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>

    <!-- Detailed Examination Logs Table -->
    <div class="card shadow-sm border-0 rounded-4 overflow-hidden">
        <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center">
            <h5 class="fw-bold text-dark m-0"><i class="bi bi-table text-primary me-2"></i> Complete Examination History</h5>
            <span class="badge bg-light text-muted border"><?php echo count($results); ?> Total Records</span>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover table-striped align-middle mb-0">
                    <thead class="table-dark">
                        <tr>
                            <th scope="col" style="width: 60px;">#</th>
                            <th scope="col">Subject</th>
                            <th scope="col">Test / Examination Name</th>
                            <th scope="col" class="text-center">Max Marks</th>
                            <th scope="col" class="text-center">Obtained Marks</th>
                            <th scope="col" class="text-center">Percentage</th>
                            <th scope="col" class="text-center">Grade</th>
                            <th scope="col">Exam Date</th>
                            <th scope="col">Teacher Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (empty($results)): ?>
                        <tr>
                            <td colspan="9" class="text-center text-muted py-5">
                                No test results found for your account. Please check back after your exams.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php 
                        $i = 1;
                        foreach ($results as $row): 
                            $pct = ($row['max_marks'] > 0) ? ($row['obtained_marks'] / $row['max_marks']) * 100 : 0;
                            $grd = get_grade($pct);
                        ?>
                            <tr>
                                <td class="text-muted fw-bold">#<?php echo $i++; ?></td>
                                <td><span class="badge bg-secondary px-2 py-1"><?php echo htmlspecialchars($row['subjectname']); ?></span></td>
                                <td class="fw-bold text-dark"><?php echo htmlspecialchars($row['test_name']); ?></td>
                                <td class="text-center"><?php echo htmlspecialchars($row['max_marks']); ?></td>
                                <td class="text-center fw-bold fs-6 text-primary"><?php echo htmlspecialchars($row['obtained_marks']); ?></td>
                                <td class="text-center">
                                    <span class="fw-semibold"><?php echo number_format($pct, 1); ?>%</span>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-<?php echo $grd['color']; ?> px-3 py-1 rounded-pill"><?php echo $grd['grade']; ?></span>
                                </td>
                                <td><small class="text-muted"><?php echo htmlspecialchars($row['exam_date'] ?: 'N/A'); ?></small></td>
                                <td>
                                    <span class="text-muted small"><?php echo htmlspecialchars($row['remarks'] ?: 'No comments'); ?></span>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
