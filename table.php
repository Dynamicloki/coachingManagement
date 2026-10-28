<?php
include "dbms.php";
require_admin();
include "includes/top.php";
?>
<title>Admin Dashboard & Student Management - Apex Coaching</title>
<?php
include "includes/nav.php";
include "includes/left-side.php";

// Fetch counts for KPI cards
$totalStudents = $conn->query("SELECT COUNT(*) FROM tblstudent2")->fetchColumn() ?: 0;
$totalClasses = $conn->query("SELECT COUNT(*) FROM tblclass WHERE status = '1'")->fetchColumn() ?: 0;
$totalSubjects = $conn->query("SELECT COUNT(*) FROM tblsubject WHERE status = '1'")->fetchColumn() ?: 0;
$totalTests = $conn->query("SELECT COUNT(*) FROM tbltest_results")->fetchColumn() ?: 0;

// Fetch all students with class names
$stmt = $conn->prepare("
    SELECT s.*, c.classname 
    FROM tblstudent2 s 
    LEFT JOIN tblclass c ON s.class = c.classid 
    ORDER BY s.studentid DESC
");
$stmt->execute();
$students = $stmt->fetchAll();
?>

<!-- 3D Three.js Admin Command Center Banner -->
<div class="card border-0 rounded-4 overflow-hidden mb-4 position-relative text-white shadow-lg" style="background: radial-gradient(circle at 10% 20%, #0f172a 0%, #1e1b4b 60%, #312e81 100%); min-height: 180px;">
    <!-- Three.js Canvas Container -->
    <div id="three-admin-canvas" class="three-canvas-container" style="opacity: 0.65;"></div>

    <div class="card-body p-4 position-relative" style="z-index: 2;">
        <div class="row align-items-center g-3">
            <div class="col-lg-8">
                <span class="badge bg-primary bg-opacity-25 text-info px-3 py-1 rounded-pill small fw-semibold border border-primary border-opacity-50 mb-2">
                    <i class="bi bi-shield-lock-fill me-1"></i> Executive Control Center
                </span>
                <h2 class="fw-bold mb-1 text-white">Administrator Dashboard ⚡</h2>
                <p class="text-white-50 m-0 small" style="max-width: 580px;">
                    Monitor live student enrollments, manage curriculum classes, evaluate subject examination scores, and track institutional audit logs in real-time.
                </p>
            </div>
            <div class="col-lg-4 text-lg-end">
                <div class="d-flex flex-wrap gap-2 justify-content-lg-end">
                    <a href="add-student.php" class="btn btn-gradient-primary btn-sm rounded-pill px-3 py-2 fw-semibold">
                        <i class="bi bi-person-plus-fill me-1"></i> Add Student
                    </a>
                    <a href="add-test-result.php" class="btn btn-outline-light btn-sm rounded-pill px-3 py-2 fw-semibold">
                        <i class="bi bi-plus-circle me-1"></i> Post Score
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- KPI Metric Cards with Motion Graphics Hover -->
<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-3">
        <div class="glass-card p-3 p-xl-4 border h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small text-uppercase fw-bold d-block mb-1">Enrolled Students</span>
                    <h2 class="fw-bold text-dark m-0"><?php echo $totalStudents; ?></h2>
                    <small class="text-success fw-semibold"><i class="bi bi-arrow-up-right me-1"></i>Active Cohort</small>
                </div>
                <div class="icon-box icon-box-primary m-0">
                    <i class="bi bi-mortarboard-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="glass-card p-3 p-xl-4 border h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small text-uppercase fw-bold d-block mb-1">Active Batches</span>
                    <h2 class="fw-bold text-primary m-0"><?php echo $totalClasses; ?></h2>
                    <small class="text-muted"><a href="class-table.php" class="text-decoration-none">Manage &rarr;</a></small>
                </div>
                <div class="icon-box icon-box-secondary m-0">
                    <i class="bi bi-diagram-3-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="glass-card p-3 p-xl-4 border h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small text-uppercase fw-bold d-block mb-1">Subjects Taught</span>
                    <h2 class="fw-bold text-success m-0"><?php echo $totalSubjects; ?></h2>
                    <small class="text-muted"><a href="subjects-table.php" class="text-decoration-none">Curriculum &rarr;</a></small>
                </div>
                <div class="icon-box icon-box-success m-0">
                    <i class="bi bi-journal-bookmark-fill"></i>
                </div>
            </div>
        </div>
    </div>

    <div class="col-sm-6 col-xl-3">
        <div class="glass-card p-3 p-xl-4 border h-100">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <span class="text-muted small text-uppercase fw-bold d-block mb-1">Test Scores Posted</span>
                    <h2 class="fw-bold text-warning m-0"><?php echo $totalTests; ?></h2>
                    <small class="text-muted"><a href="test-results-table.php" class="text-decoration-none">View tests &rarr;</a></small>
                </div>
                <div class="icon-box icon-box-accent m-0">
                    <i class="bi bi-trophy-fill"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Alert Banners -->
<?php if (isset($_GET['msg'])): ?>
    <?php if ($_GET['msg'] == 1): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 p-3 shadow-sm mb-4" role="alert">
            <i class="bi bi-check-circle-fill me-2 text-success fs-5"></i> Student registered successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php elseif ($_GET['msg'] == 2): ?>
        <div class="alert alert-info alert-dismissible fade show rounded-4 border-0 p-3 shadow-sm mb-4" role="alert">
            <i class="bi bi-info-circle-fill me-2 text-info fs-5"></i> Student record updated successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php elseif ($_GET['msg'] === 'deleted'): ?>
        <div class="alert alert-warning alert-dismissible fade show rounded-4 border-0 p-3 shadow-sm mb-4" role="alert">
            <i class="bi bi-trash-fill me-2 text-warning fs-5"></i> Student record deleted successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
<?php endif; ?>

<!-- Students Table Card with Live Search Filter -->
<div class="glass-card border shadow-sm rounded-4 overflow-hidden mb-4">
    <div class="card-header bg-white py-3 px-4 d-flex justify-content-between align-items-center flex-wrap gap-3">
        <div>
            <h5 class="fw-bold text-dark m-0"><i class="bi bi-people-fill text-primary me-2"></i> Registered Students Directory</h5>
            <small class="text-muted">Total of <?php echo count($students); ?> student profile records</small>
        </div>
        
        <div class="d-flex align-items-center gap-2">
            <div class="input-group input-group-sm" style="max-width: 260px;">
                <span class="input-group-text bg-light border-end-0"><i class="bi bi-search text-muted"></i></span>
                <input type="text" id="studentSearchInput" class="form-control border-start-0" placeholder="Search student or class..." onkeyup="filterStudentTable()">
            </div>
            <a href="add-student.php" class="btn btn-primary btn-sm rounded-pill px-3">
                <i class="bi bi-plus-lg me-1"></i> Add Student
            </a>
        </div>
    </div>

    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover table-striped align-middle mb-0" id="studentTable">
                <thead class="table-dark">
                    <tr>
                        <th scope="col" style="width: 80px;">ID</th>
                        <th scope="col">Student Name</th>
                        <th scope="col">Date of Birth</th>
                        <th scope="col">Class Batch</th>
                        <th scope="col">Contact Phone</th>
                        <th scope="col">Email</th>
                        <th scope="col" style="width: 140px;" class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                <?php if (empty($students)): ?>
                    <tr>
                        <td colspan="7" class="text-center text-muted py-5">No student records found. <a href="add-student.php">Register a student now</a>.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($students as $result): 
                        $studentid = $result['studentid'];
                        $displayClass = !empty($result['classname']) ? $result['classname'] : (!empty($result['class']) ? $result['class'] : 'N/A');
                    ?>
                        <tr>
                            <td class="fw-bold text-muted">#<?php echo htmlspecialchars($studentid); ?></td>
                            <td>
                                <strong class="text-dark d-block"><?php echo htmlspecialchars($result['studentname'] ?? ''); ?></strong>
                                <small class="text-muted"><?php echo htmlspecialchars($result['schoolname'] ?: 'Enrolled'); ?></small>
                            </td>
                            <td><small class="text-muted"><?php echo htmlspecialchars($result['dob'] ?: 'N/A'); ?></small></td>
                            <td><span class="badge bg-secondary rounded-pill px-3 py-1"><?php echo htmlspecialchars($displayClass); ?></span></td>
                            <td><small class="font-monospace text-dark"><?php echo htmlspecialchars($result['studentmobile'] ?: '-'); ?></small></td>
                            <td><small class="text-muted"><?php echo htmlspecialchars($result['studentmail'] ?: '-'); ?></small></td>
                            <td class="text-center">
                                <a href="student-edit.php?studentid=<?php echo urlencode($studentid); ?>" class="btn btn-outline-primary btn-sm rounded-circle me-1" title="Edit Student">
                                    <i class="bi bi-pencil"></i>
                                </a>
                                <a href="student-delete.php?studentid=<?php echo urlencode($studentid); ?>" class="btn btn-outline-danger btn-sm rounded-circle" onclick="return confirm('Are you sure you want to delete this student record?');" title="Delete Student">
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
</div>

<!-- Three.js 3D Ambient Mesh Script for Admin Command Center -->
<script>
document.addEventListener("DOMContentLoaded", () => {
    const container = document.getElementById("three-admin-canvas");
    if (!container || typeof THREE === "undefined") return;

    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(50, container.clientWidth / container.clientHeight, 0.1, 1000);
    camera.position.z = 22;

    const renderer = new THREE.WebGLRenderer({ alpha: true, antialias: true });
    renderer.setSize(container.clientWidth, container.clientHeight);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));
    container.appendChild(renderer.domElement);

    // Glowing Wireframe Dodecahedron
    const geom1 = new THREE.DodecahedronGeometry(7, 1);
    const mat1 = new THREE.MeshBasicMaterial({
        color: 0x6366f1,
        wireframe: true,
        transparent: true,
        opacity: 0.22
    });
    const mesh1 = new THREE.Mesh(geom1, mat1);
    mesh1.position.set(12, 0, -2);
    scene.add(mesh1);

    // Orbiting Geometric Ring
    const ringGeom = new THREE.TorusGeometry(10, 0.2, 16, 80);
    const ringMat = new THREE.MeshBasicMaterial({
        color: 0x38bdf8,
        wireframe: true,
        transparent: true,
        opacity: 0.15
    });
    const ring = new THREE.Mesh(ringGeom, ringMat);
    ring.rotation.x = Math.PI / 3;
    ring.position.set(12, 0, -2);
    scene.add(ring);

    // Orbiting particle points
    const pCount = 80;
    const pGeom = new THREE.BufferGeometry();
    const pPos = new Float32Array(pCount * 3);
    for (let i = 0; i < pCount * 3; i += 3) {
        pPos[i] = 12 + (Math.random() - 0.5) * 20;
        pPos[i + 1] = (Math.random() - 0.5) * 15;
        pPos[i + 2] = (Math.random() - 0.5) * 15;
    }
    pGeom.setAttribute('position', new THREE.BufferAttribute(pPos, 3));
    const pMat = new THREE.PointsMaterial({
        color: 0xa5b4fc,
        size: 0.8,
        transparent: true,
        opacity: 0.6
    });
    const points = new THREE.Points(pGeom, pMat);
    scene.add(points);

    // Mouse Tracking for dynamic tilt
    let mouseX = 0, mouseY = 0;
    window.addEventListener("mousemove", (e) => {
        mouseX = (e.clientX - window.innerWidth / 2) * 0.005;
        mouseY = (e.clientY - window.innerHeight / 2) * 0.005;
    });

    window.addEventListener("resize", () => {
        if (!container) return;
        camera.aspect = container.clientWidth / container.clientHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(container.clientWidth, container.clientHeight);
    });

    function loop() {
        requestAnimationFrame(loop);
        mesh1.rotation.x += 0.003;
        mesh1.rotation.y += 0.004;

        ring.rotation.z += 0.002;
        points.rotation.y -= 0.001;

        mesh1.position.x += (12 + mouseX * 2 - mesh1.position.x) * 0.05;
        mesh1.position.y += (-mouseY * 2 - mesh1.position.y) * 0.05;

        renderer.render(scene, camera);
    }
    loop();
});

// Client-side search filter for students table
function filterStudentTable() {
    const input = document.getElementById("studentSearchInput").value.toLowerCase();
    const rows = document.querySelectorAll("#studentTable tbody tr");

    rows.forEach(row => {
        const text = row.innerText.toLowerCase();
        row.style.display = text.includes(input) ? "" : "none";
    });
}
</script>

<?php
include "includes/end.php";
?>
