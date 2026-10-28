<?php
include "dbms.php";
require_admin();
include "includes/top.php";
?>
<title>Apex Institute - Monthly Data & Log Backups</title>
<!-- Three.js CDN -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/three.js/r128/three.min.js"></script>

<?php
include "includes/nav.php";
include "includes/left-side.php";

// Fetch all backups ordered by creation date descending
$stmt = $conn->query("SELECT * FROM tblbackups ORDER BY backupid DESC");
$backups = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Metrics
$totalBackups = count($backups);
$totalBytes = 0;
$currentMonth = date('Y-m');
$currentMonthBackedUp = false;

foreach ($backups as $b) {
    $totalBytes += (int)$b['filesize'];
    if ($b['backup_month'] === $currentMonth) {
        $currentMonthBackedUp = true;
    }
}

$formattedSize = $totalBytes > 1048576 
    ? round($totalBytes / 1048576, 2) . ' MB' 
    : round($totalBytes / 1024, 2) . ' KB';
?>

<div class="container-fluid p-0">
    <!-- Three.js 3D Vault Banner -->
    <div class="card border-0 rounded-4 shadow-sm mb-4 text-white overflow-hidden position-relative" style="background: linear-gradient(135deg, #091e3a 0%, #2b1055 50%, #111e38 100%);">
        <canvas id="vaultCanvas" style="position: absolute; right: 0; top: 0; width: 50%; height: 100%; pointer-events: none; opacity: 0.65; z-index: 1;"></canvas>
        <div class="card-body p-4 p-lg-5 position-relative" style="z-index: 2; max-width: 650px;">
            <span class="badge bg-info bg-opacity-25 text-info border border-info border-opacity-25 px-3 py-1 rounded-pill mb-2">
                <i class="bi bi-shield-check me-1"></i> Data Vault & Redundancy
            </span>
            <h2 class="display-6 fw-bold mb-2">Monthly Database & Log Backups</h2>
            <p class="text-white-50 mb-4">
                Automated monthly snapshots of all database tables (students, classes, subjects, test scores) and audit logs. Download snapshots directly to your local workstation anytime.
            </p>
            <div class="d-flex flex-wrap gap-2">
                <button type="button" class="btn btn-primary px-4 py-2 rounded-pill shadow-sm" data-bs-toggle="modal" data-bs-target="#createBackupModal">
                    <i class="bi bi-cloud-arrow-down-fill me-2"></i> Create Backup Snapshot Now
                </button>
                <a href="logs.php" class="btn btn-outline-light px-3 py-2 rounded-pill">
                    <i class="bi bi-card-checklist me-1"></i> View Audit Logs
                </a>
            </div>
        </div>
    </div>

    <!-- Alert Messages -->
    <?php if (isset($_GET['msg'])): ?>
        <?php if ($_GET['msg'] === 'created'): ?>
            <div class="alert alert-success alert-dismissible fade show rounded-3 shadow-sm d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-check-circle-fill fs-5"></i>
                <div><strong>Success!</strong> Monthly backup snapshot generated and saved to storage.</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        <?php elseif ($_GET['msg'] === 'deleted'): ?>
            <div class="alert alert-warning alert-dismissible fade show rounded-3 shadow-sm d-flex align-items-center gap-2" role="alert">
                <i class="bi bi-trash-fill fs-5"></i>
                <div><strong>Removed:</strong> Backup snapshot deleted from disk and catalogue.</div>
                <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert"></button>
            </div>
        <?php endif; ?>
    <?php endif; ?>

    <?php if (isset($_GET['err'])): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-3 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i> Error: <?php echo htmlspecialchars($_GET['err']); ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <!-- KPI Metric Cards -->
    <div class="row g-3 mb-4">
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-primary border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase">Total Backups</span>
                        <h3 class="fw-bold m-0 text-dark"><?php echo $totalBackups; ?></h3>
                    </div>
                    <div class="bg-primary bg-opacity-10 text-primary rounded-3 p-3 fs-4">
                        <i class="bi bi-archive-fill"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-success border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase">Current Month (<?php echo date('M Y'); ?>)</span>
                        <h4 class="fw-bold m-0 <?php echo $currentMonthBackedUp ? 'text-success' : 'text-warning'; ?>">
                            <?php echo $currentMonthBackedUp ? 'Backed Up' : 'Pending'; ?>
                        </h4>
                    </div>
                    <div class="bg-success bg-opacity-10 text-success rounded-3 p-3 fs-4">
                        <i class="bi bi-calendar-check-fill"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-info border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase">Backup Storage</span>
                        <h3 class="fw-bold m-0 text-dark"><?php echo $formattedSize; ?></h3>
                    </div>
                    <div class="bg-info bg-opacity-10 text-info rounded-3 p-3 fs-4">
                        <i class="bi bi-hdd-network-fill"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-3">
            <div class="card border-0 shadow-sm rounded-4 p-3 bg-white h-100 border-start border-warning border-4">
                <div class="d-flex align-items-center justify-content-between">
                    <div>
                        <span class="text-muted small fw-bold text-uppercase">Auto-Schedule</span>
                        <h5 class="fw-bold m-0 text-dark">1st of Month</h5>
                        <small class="text-muted">Autonomous Trigger</small>
                    </div>
                    <div class="bg-warning bg-opacity-10 text-warning rounded-3 p-3 fs-4">
                        <i class="bi bi-clock-history"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Backups Catalogue Table -->
    <div class="card border-0 shadow-sm rounded-4 bg-white overflow-hidden">
        <div class="card-header bg-white py-3 px-4 border-bottom d-flex flex-wrap justify-content-between align-items-center gap-2">
            <div>
                <h5 class="fw-bold m-0 text-dark">Monthly Backup Catalogue</h5>
                <small class="text-muted">All exported database archives & data logs by month</small>
            </div>
            <div class="d-flex align-items-center gap-2">
                <input type="text" id="backupSearch" class="form-control form-control-sm rounded-pill px-3" placeholder="Search by month or filename..." style="width: 250px;">
            </div>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0" id="backupsTable">
                <thead class="table-light">
                    <tr>
                        <th class="ps-4">Month</th>
                        <th>Archive File</th>
                        <th>Format</th>
                        <th>Records</th>
                        <th>Size</th>
                        <th>Triggered By</th>
                        <th>Created At</th>
                        <th class="text-end pe-4">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($backups)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-5 text-muted">
                                <i class="bi bi-cloud-slash display-4 d-block mb-3 text-secondary"></i>
                                <h5>No backup snapshots generated yet</h5>
                                <p class="small">Click the "Create Backup Snapshot Now" button above to generate your first monthly archive.</p>
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($backups as $b): ?>
                            <tr>
                                <td class="ps-4">
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="bg-primary bg-opacity-10 text-primary rounded-circle p-2 d-flex align-items-center justify-content-center" style="width: 38px; height: 38px;">
                                            <i class="bi bi-calendar3"></i>
                                        </div>
                                        <div>
                                            <strong class="text-dark d-block">
                                                <?php 
                                                    $d = DateTime::createFromFormat('Y-m', $b['backup_month']);
                                                    echo $d ? $d->format('F Y') : htmlspecialchars($b['backup_month']);
                                                ?>
                                            </strong>
                                            <small class="text-muted font-monospace"><?php echo htmlspecialchars($b['backup_month']); ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <span class="font-monospace small text-primary fw-semibold">
                                        <?php echo htmlspecialchars($b['filename']); ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($b['filetype'] === 'JSON'): ?>
                                        <span class="badge bg-success bg-opacity-10 text-success border border-success border-opacity-25 px-2 py-1 rounded-pill">
                                            <i class="bi bi-file-earmark-code me-1"></i> JSON Archive
                                        </span>
                                    <?php else: ?>
                                        <span class="badge bg-primary bg-opacity-10 text-primary border border-primary border-opacity-25 px-2 py-1 rounded-pill">
                                            <i class="bi bi-database me-1"></i> SQLite Snapshot
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-secondary bg-opacity-10 text-secondary rounded-pill px-3 py-1">
                                        <?php echo number_format($b['record_count']); ?> rows
                                    </span>
                                </td>
                                <td>
                                    <span class="small fw-semibold text-dark">
                                        <?php 
                                            $sz = (int)$b['filesize'];
                                            echo $sz > 1048576 ? round($sz / 1048576, 2) . ' MB' : round($sz / 1024, 2) . ' KB';
                                        ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge bg-dark bg-opacity-10 text-dark rounded-pill px-2 py-1">
                                        <?php echo htmlspecialchars($b['created_by']); ?>
                                    </span>
                                </td>
                                <td>
                                    <small class="text-muted"><?php echo htmlspecialchars($b['created_at']); ?></small>
                                </td>
                                <td class="text-end pe-4">
                                    <div class="d-inline-flex gap-1">
                                        <a href="backup-download.php?id=<?php echo $b['backupid']; ?>" class="btn btn-sm btn-primary rounded-pill px-3 d-inline-flex align-items-center gap-1 shadow-sm" title="Download to your local system">
                                            <i class="bi bi-download"></i>
                                            <span>Download</span>
                                        </a>
                                        <a href="backup-delete.php?id=<?php echo $b['backupid']; ?>" class="btn btn-sm btn-outline-danger rounded-circle" onclick="return confirm('Are you sure you want to permanently delete this backup snapshot?');" title="Delete Archive" style="width: 32px; height: 32px; padding: 0; display: inline-flex; align-items: center; justify-content: center;">
                                            <i class="bi bi-trash"></i>
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Modal: Create Backup Snapshot -->
<div class="modal fade" id="createBackupModal" tabindex="-1" aria-labelledby="createBackupModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content rounded-4 border-0 shadow">
            <form action="backup-create-process.php" method="POST">
                <div class="modal-header border-bottom py-3">
                    <h5 class="modal-title fw-bold" id="createBackupModalLabel">
                        <i class="bi bi-cloud-arrow-down-fill text-primary me-2"></i> Generate Monthly Backup
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <p class="text-muted small mb-4">
                        This operation aggregates all tables (students, batches, curriculum subjects, examination test scores, administrator records) along with all system activity logs into an archived snapshot.
                    </p>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Target Month</label>
                        <input type="month" name="backup_month" class="form-control rounded-3" value="<?php echo date('Y-m'); ?>" required>
                        <small class="text-muted">The monthly period to tag this backup snapshot under.</small>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold small text-muted text-uppercase">Backup Format</label>
                        <div class="row g-2">
                            <div class="col-6">
                                <label class="border rounded-3 p-3 d-block cursor-pointer h-100 hover-border-primary" style="cursor: pointer;">
                                    <input type="radio" name="format" value="JSON" checked class="form-check-input me-2">
                                    <strong>JSON Archive</strong>
                                    <div class="small text-muted mt-1">Structured JSON with all SQL tables, audit logs & metadata.</div>
                                </label>
                            </div>
                            <div class="col-6">
                                <label class="border rounded-3 p-3 d-block cursor-pointer h-100 hover-border-primary" style="cursor: pointer;">
                                    <input type="radio" name="format" value="SQLITE" class="form-check-input me-2">
                                    <strong>SQLite Copy</strong>
                                    <div class="small text-muted mt-1">Direct snapshot of database.sqlite file ready for instant restore.</div>
                                </label>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top py-3">
                    <button type="button" class="btn btn-light rounded-pill px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary rounded-pill px-4">
                        <i class="bi bi-lightning-charge-fill me-1"></i> Generate Snapshot
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Three.js 3D Holographic Vault Script -->
<script>
document.addEventListener('DOMContentLoaded', function () {
    const canvas = document.getElementById('vaultCanvas');
    if (!canvas || typeof THREE === 'undefined') return;

    const scene = new THREE.Scene();
    const camera = new THREE.PerspectiveCamera(50, canvas.clientWidth / canvas.clientHeight, 0.1, 1000);
    camera.position.z = 7;

    const renderer = new THREE.WebGLRenderer({ canvas: canvas, alpha: true, antialias: true });
    renderer.setSize(canvas.clientWidth, canvas.clientHeight);
    renderer.setPixelRatio(Math.min(window.devicePixelRatio, 2));

    // Outer wireframe octahedron
    const outerGeo = new THREE.OctahedronGeometry(2.5, 1);
    const outerMat = new THREE.MeshBasicMaterial({
        color: 0x38bdf8,
        wireframe: true,
        transparent: true,
        opacity: 0.4
    });
    const outerMesh = new THREE.Mesh(outerGeo, outerMat);
    scene.add(outerMesh);

    // Inner glowing core
    const innerGeo = new THREE.IcosahedronGeometry(1.4, 0);
    const innerMat = new THREE.MeshStandardMaterial({
        color: 0x818cf8,
        wireframe: true,
        emissive: 0x4f46e5,
        emissiveIntensity: 0.6
    });
    const innerMesh = new THREE.Mesh(innerGeo, innerMat);
    scene.add(innerMesh);

    // Orbiting particles ring
    const particleCount = 180;
    const posArray = new Float32Array(particleCount * 3);
    for (let i = 0; i < particleCount; i++) {
        const angle = (i / particleCount) * Math.PI * 2;
        const radius = 3.2 + (Math.random() - 0.5) * 0.4;
        posArray[i * 3] = Math.cos(angle) * radius;
        posArray[i * 3 + 1] = (Math.random() - 0.5) * 1.5;
        posArray[i * 3 + 2] = Math.sin(angle) * radius;
    }
    const partGeo = new THREE.BufferGeometry();
    partGeo.setAttribute('position', new THREE.BufferAttribute(posArray, 3));
    const partMat = new THREE.PointsMaterial({
        size: 0.05,
        color: 0x38bdf8,
        transparent: true,
        opacity: 0.8
    });
    const particleRing = new THREE.Points(partGeo, partMat);
    scene.add(particleRing);

    // Lighting
    const light = new THREE.PointLight(0xffffff, 1, 100);
    light.position.set(5, 5, 5);
    scene.add(light);
    scene.add(new THREE.AmbientLight(0x222244));

    // Animation Loop
    function animate() {
        requestAnimationFrame(animate);
        outerMesh.rotation.y += 0.005;
        outerMesh.rotation.x += 0.003;
        innerMesh.rotation.y -= 0.008;
        innerMesh.rotation.z += 0.004;
        particleRing.rotation.y += 0.003;
        renderer.render(scene, camera);
    }
    animate();

    window.addEventListener('resize', function () {
        if (!canvas) return;
        camera.aspect = canvas.clientWidth / canvas.clientHeight;
        camera.updateProjectionMatrix();
        renderer.setSize(canvas.clientWidth, canvas.clientHeight);
    });

    // Client-side search
    const searchInput = document.getElementById('backupSearch');
    if (searchInput) {
        searchInput.addEventListener('input', function (e) {
            const query = e.target.value.toLowerCase();
            const rows = document.querySelectorAll('#backupsTable tbody tr');
            rows.forEach(row => {
                const text = row.innerText.toLowerCase();
                row.style.display = text.includes(query) ? '' : 'none';
            });
        });
    }
});
</script>

<?php
include "includes/end.php";
?>