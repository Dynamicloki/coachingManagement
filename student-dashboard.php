<?php
include "dbms.php";
require_student();

$studentId = $_SESSION['studentid'];

$stmt = $conn->prepare("
    SELECT s.*, c.classname 
    FROM tblstudent2 s 
    LEFT JOIN tblclass c ON s.class = c.classid 
    WHERE s.studentid = :id
");
$stmt->bindParam(':id', $studentId, PDO::PARAM_INT);
$stmt->execute();
$student = $stmt->fetch();

if (!$student) {
    header("Location: logout.php");
    exit;
}

// Fetch test summary
$testStmt = $conn->prepare("
    SELECT COUNT(*) AS total_tests, AVG(obtained_marks * 100.0 / max_marks) AS avg_percent, MAX(obtained_marks * 100.0 / max_marks) AS top_percent
    FROM tbltest_results 
    WHERE studentid = :id
");
$testStmt->bindParam(':id', $studentId, PDO::PARAM_INT);
$testStmt->execute();
$summary = $testStmt->fetch();

// Fetch latest 3 test results for preview
$latestStmt = $conn->prepare("
    SELECT tr.*, sub.subjectname 
    FROM tbltest_results tr
    JOIN tblsubject sub ON tr.subjectid = sub.subjectid
    WHERE tr.studentid = :id
    ORDER BY tr.exam_date DESC, tr.testid DESC
    LIMIT 3
");
$latestStmt->bindParam(':id', $studentId, PDO::PARAM_INT);
$latestStmt->execute();
$latestTests = $latestStmt->fetchAll();

include "includes/top.php";
?>
<title>Student Dashboard - Apex Coaching Institute</title>
<?php
include "includes/student-nav.php";
?>

<div class="container pb-5">

    <!-- Success Message Alert -->
    <?php if (isset($_GET['msg']) && $_GET['msg'] === 'profile_updated'): ?>
        <div class="alert alert-success alert-dismissible fade show shadow-sm border-0 d-flex align-items-center gap-3 p-3 mb-4 rounded-4" role="alert">
            <i class="bi bi-check-circle-fill fs-3 text-success"></i>
            <div>
                <strong class="d-block">Profile Updated Successfully!</strong>
                <span class="small text-muted">Your updated contact and personal details have been safely stored in the database.</span>
            </div>
            <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <!-- 3D Interactive Hero Profile Banner -->
    <div class="student-banner mb-4 position-relative overflow-hidden">
        <!-- Three.js Canvas Container for Student Banner -->
        <div id="three-student-canvas" class="three-canvas-container" style="opacity: 0.7;"></div>

        <div class="position-relative" style="z-index: 2;">
            <div class="row align-items-center g-4">
                <div class="col-md-8">
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <div class="rounded-circle bg-white text-primary d-flex align-items-center justify-content-center fw-bold shadow-lg" style="width: 72px; height: 72px; font-size: 2rem;">
                            <?php echo strtoupper(substr($student['studentname'], 0, 1)); ?>
                        </div>
                        <div>
                            <span class="badge bg-white bg-opacity-20 text-white rounded-pill px-3 py-1 mb-1 small">
                                Student ID #<?php echo htmlspecialchars($student['studentid']); ?>
                            </span>
                            <h2 class="fw-bold mb-0 text-white"><?php echo htmlspecialchars($student['studentname']); ?></h2>
                        </div>
                    </div>
                    <p class="text-white-50 mb-0 fs-6">
                        <i class="bi bi-mortarboard me-1"></i> Class: <strong><?php echo htmlspecialchars($student['classname'] ?: 'Class ' . $student['class']); ?></strong> &bull;
                        <i class="bi bi-calendar3 me-1 ms-2"></i> Session: <strong><?php echo htmlspecialchars($student['season']); ?></strong> &bull;
                        <i class="bi bi-building me-1 ms-2"></i> School: <strong><?php echo htmlspecialchars($student['schoolname'] ?: 'Apex Coaching'); ?></strong>
                    </p>
                </div>
                <div class="col-md-4 text-md-end">
                    <div class="d-flex flex-wrap gap-2 justify-content-md-end">
                        <a href="student-results.php" class="btn btn-light rounded-pill px-4 py-2 fw-semibold text-primary shadow-sm">
                            <i class="bi bi-graph-up-arrow me-1"></i> Test Results
                        </a>
                        <a href="student-profile-edit.php" class="btn btn-outline-light rounded-pill px-4 py-2 fw-semibold">
                            <i class="bi bi-pencil me-1"></i> Edit Profile
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Stats Cards with Motion Hover -->
    <div class="row g-4 mb-4">
        <div class="col-md-4">
            <div class="glass-card p-4 h-100 border">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small text-uppercase fw-bold d-block mb-1">Evaluated Tests</span>
                        <h2 class="fw-bold text-dark m-0"><?php echo (int)($summary['total_tests'] ?? 0); ?></h2>
                        <span class="text-success small fw-semibold"><i class="bi bi-check-all"></i> Up to date</span>
                    </div>
                    <div class="icon-box icon-box-primary m-0">
                        <i class="bi bi-journal-check"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="glass-card p-4 h-100 border">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small text-uppercase fw-bold d-block mb-1">Overall Average</span>
                        <h2 class="fw-bold text-primary m-0">
                            <?php echo $summary['avg_percent'] !== null ? number_format($summary['avg_percent'], 1) . '%' : 'N/A'; ?>
                        </h2>
                        <span class="text-muted small">Peak: <?php echo $summary['top_percent'] !== null ? number_format($summary['top_percent'], 1) . '%' : 'N/A'; ?></span>
                    </div>
                    <div class="icon-box icon-box-success m-0">
                        <i class="bi bi-trophy"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="glass-card p-4 h-100 border">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <span class="text-muted small text-uppercase fw-bold d-block mb-1">Enrollment Status</span>
                        <h4 class="fw-bold text-success m-0 mt-1 d-flex align-items-center gap-2">
                            <span class="p-1 rounded-circle bg-success d-inline-block"></span> Active Enrolled
                        </h4>
                        <span class="text-muted small">Session: <?php echo htmlspecialchars($student['season']); ?></span>
                    </div>
                    <div class="icon-box icon-box-secondary m-0">
                        <i class="bi bi-patch-check"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Details Grid -->
    <div class="row g-4">
        <!-- Left: Student Personal & Family Information -->
        <div class="col-lg-7">
            <div class="glass-card p-4 border h-100">
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <h5 class="fw-bold text-dark m-0"><i class="bi bi-person-lines-fill text-primary me-2"></i> Student & Family Records</h5>
                    <a href="student-profile-edit.php" class="text-decoration-none small text-primary fw-semibold">Edit Details &rarr;</a>
                </div>

                <div class="row g-3">
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Full Legal Name</small>
                        <strong class="text-dark"><?php echo htmlspecialchars($student['studentname']); ?></strong>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Date of Birth</small>
                        <span class="text-dark"><?php echo htmlspecialchars($student['dob'] ?: 'Not provided'); ?></span>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Gender</small>
                        <span class="text-dark"><?php echo ($student['gender'] == '1') ? 'Male' : (($student['gender'] == '2') ? 'Female' : 'Other'); ?></span>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">School Enrolled</small>
                        <span class="text-dark"><?php echo htmlspecialchars($student['schoolname'] ?: 'Not provided'); ?></span>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Student Mobile Number</small>
                        <span class="text-dark"><?php echo htmlspecialchars($student['studentmobile'] ?: 'Not provided'); ?></span>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Student Registered Email</small>
                        <span class="text-dark"><?php echo htmlspecialchars($student['studentmail'] ?: 'Not provided'); ?></span>
                    </div>
                    
                    <div class="col-12 pt-3 border-top">
                        <h6 class="fw-bold text-muted small text-uppercase mb-2">Guardian Contact Information</h6>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Father's Name</small>
                        <strong class="text-dark">Mr. <?php echo htmlspecialchars($student['fathername']); ?></strong>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Mother's Name</small>
                        <strong class="text-dark">Mrs. <?php echo htmlspecialchars($student['mothername']); ?></strong>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Primary Guardian Phone</small>
                        <span class="text-dark"><?php echo htmlspecialchars($student['fathermobilenumber'] ?: 'Not provided'); ?></span>
                    </div>
                    <div class="col-sm-6">
                        <small class="text-muted d-block">Secondary Guardian Phone</small>
                        <span class="text-dark"><?php echo htmlspecialchars($student['motherMobileNumber'] ?: 'Not provided'); ?></span>
                    </div>
                    <div class="col-12">
                        <small class="text-muted d-block">Home / Postal Address</small>
                        <span class="text-dark"><?php echo htmlspecialchars($student['address'] ?: 'Not provided'); ?></span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Right: Latest Examination Scores Preview -->
        <div class="col-lg-5">
            <div class="glass-card p-4 border h-100">
                <div class="d-flex justify-content-between align-items-center mb-3 pb-2 border-bottom">
                    <h5 class="fw-bold text-dark m-0"><i class="bi bi-award text-warning me-2"></i> Recent Exam Scores</h5>
                    <a href="student-results.php" class="text-decoration-none small text-primary fw-semibold">View All &rarr;</a>
                </div>

                <?php if (empty($latestTests)): ?>
                    <div class="text-center py-5 text-muted">
                        <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                        No examination scores available yet.
                    </div>
                <?php else: ?>
                    <div class="d-flex flex-column gap-3">
                        <?php foreach ($latestTests as $test): 
                            $pct = ($test['max_marks'] > 0) ? ($test['obtained_marks'] / $test['max_marks']) * 100 : 0;
                            $badgeColor = ($pct >= 85) ? 'success' : (($pct >= 70) ? 'primary' : 'warning');
                        ?>
                            <div class="p-3 rounded-3 bg-light border">
                                <div class="d-flex justify-content-between align-items-start mb-2">
                                    <div>
                                        <h6 class="fw-bold text-dark mb-0"><?php echo htmlspecialchars($test['subjectname']); ?></h6>
                                        <small class="text-muted"><?php echo htmlspecialchars($test['test_name']); ?> &bull; <?php echo htmlspecialchars($test['exam_date'] ?: 'Recent'); ?></small>
                                    </div>
                                    <span class="badge bg-<?php echo $badgeColor; ?> rounded-pill px-2 py-1">
                                        <?php echo number_format($pct, 1); ?>%
                                    </span>
                                </div>
                                <div class="d-flex justify-content-between small text-muted">
                                    <span>Score: <strong><?php echo $test['obtained_marks']; ?> / <?php echo $test['max_marks']; ?></strong></span>
                                    <span><?php echo htmlspecialchars($test['remarks'] ?: 'Evaluated'); ?></span>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="mt-4 pt-2">
                        <a href="student-results.php" class="btn btn-outline-primary w-100 rounded-pill py-2">
                            Explore Detailed Subject Analytics &rarr;
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

</div>

<!-- Three.js Canvas Background for Student Banner -->
<script>
document.addEventListener("DOMContentLoaded", () => {
    const container = document.getElementById("three-student-canvas");
    if (!container || typeof THREE === "undefined") return;

    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(50, container.clientWidth / container.clientHeight, 0.1, 1000);
    camera.position.z = 20;

    const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
    renderer.setSize(container.clientWidth, container.clientHeight);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    container.appendChild(renderer.domElement);

    // Floating Wireframe Hexagonal Lattice
    const geometry = new THREE.IcosahedronGeometry(12, 1);
    const material = new THREE.MeshBasicMaterial({
        color: 0x818cf8,
        wireframe: true,
        transparent: true,
        opacity: 0.15
    });
    const sphere = new THREE.Mesh(geometry, material);
    sphere.position.set(15, -2, 0);
    scene.add(sphere);

    window.addEventListener("resize", () => {
        camera.aspect = container.clientWidth / container.clientHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(container.clientWidth, container.clientHeight);
    });

    function animate() {
        requestAnimationFrame(animate);
        sphere.rotation.x += 0.002;
        sphere.rotation.y += 0.003;
        renderer.render(scene, camera);
    }
    animate();
});
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
