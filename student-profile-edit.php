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

include "includes/top.php";
?>
<title>Edit My Profile - Apex Student Portal</title>
<?php
include "includes/student-nav.php";
?>

<div class="container pb-5">
    <div class="d-flex justify-content-between align-items-center mb-4 flex-wrap gap-2">
        <div>
            <h2 class="text-primary fw-bold m-0">Edit My Profile ✏️</h2>
            <p class="text-muted m-0">Update your contact information, residential address, and login credentials</p>
        </div>
        <a href="student-dashboard.php" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i> Cancel & Return
        </a>
    </div>

    <?php if (isset($_GET['err'])): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 p-3 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?php
                if ($_GET['err'] === 'name') echo "Please provide a valid full name.";
                elseif ($_GET['err'] === 'pass') echo "Password must be at least 6 characters long.";
                else echo "Please fill in all required fields properly.";
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form action="student-profile-edit-process.php" method="POST" class="glass-card p-4 p-md-5 border shadow-sm rounded-4">
        <div class="row g-4">
            
            <!-- Left Column: Student Details -->
            <div class="col-lg-6 border-end-lg pe-lg-4">
                <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                    <span class="fs-5">👤</span>
                    <h5 class="fw-bold text-dark m-0">Personal Information</h5>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted small mb-1">Enrolled Class & Session <span class="badge bg-secondary-subtle text-secondary ms-1">Admin Controlled</span></label>
                    <input type="text" class="form-control bg-light border-0" value="<?php echo htmlspecialchars($student['classname'] ?: 'Class ' . $student['class']); ?> (Academic Session: <?php echo htmlspecialchars($student['season']); ?>)" readonly>
                    <small class="text-muted">Contact institute administration to change your enrolled class or batch.</small>
                </div>

                <div class="mb-3">
                    <label for="studentname" class="form-label fw-semibold small">Full Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-lg fs-6" name="studentname" id="studentname" value="<?php echo htmlspecialchars($student['studentname']); ?>" required>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-6">
                        <label for="dob" class="form-label fw-semibold small">Date of Birth</label>
                        <input type="date" class="form-control" name="dob" id="dob" value="<?php echo htmlspecialchars($student['dob'] ?? ''); ?>">
                    </div>
                    <div class="col-sm-6">
                        <label for="gender" class="form-label fw-semibold small">Gender</label>
                        <select class="form-select" name="gender" id="gender">
                            <option value="1" <?php if ($student['gender'] == '1') echo 'selected'; ?>>Male</option>
                            <option value="2" <?php if ($student['gender'] == '2') echo 'selected'; ?>>Female</option>
                        </select>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="studentmobile" class="form-label fw-semibold small">Student Mobile Number</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-phone"></i></span>
                        <input type="tel" class="form-control" name="studentmobile" id="studentmobile" value="<?php echo htmlspecialchars($student['studentmobile'] ?? ''); ?>" placeholder="10-digit mobile number">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="schoolname" class="form-label fw-semibold small">School / College Name</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-building"></i></span>
                        <input type="text" class="form-control" name="schoolname" id="schoolname" value="<?php echo htmlspecialchars($student['schoolname'] ?? ''); ?>" placeholder="Current attending school">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="studentlogin" class="form-label fw-semibold small">Login Password <span class="text-danger">*</span></label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-lock"></i></span>
                        <input type="text" class="form-control" name="studentlogin" id="studentlogin" value="<?php echo htmlspecialchars($student['studentlogin'] ?? ''); ?>" required>
                    </div>
                    <small class="text-muted">Password used to authenticate into your Student Portal.</small>
                </div>
            </div>

            <!-- Right Column: Family & Guardian Contact Details -->
            <div class="col-lg-6 ps-lg-4">
                <div class="d-flex align-items-center gap-2 mb-3 pb-2 border-bottom">
                    <span class="fs-5">🏡</span>
                    <h5 class="fw-bold text-dark m-0">Family & Guardian Contact</h5>
                </div>

                <div class="mb-3">
                    <label for="fathername" class="form-label fw-semibold small">Father's Name</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">Mr.</span>
                        <input type="text" class="form-control" name="fathername" id="fathername" value="<?php echo htmlspecialchars($student['fathername'] ?? ''); ?>" placeholder="Father's full name">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="mothername" class="form-label fw-semibold small">Mother's Name</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light">Mrs.</span>
                        <input type="text" class="form-control" name="mothername" id="mothername" value="<?php echo htmlspecialchars($student['mothername'] ?? ''); ?>" placeholder="Mother's full name">
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-6">
                        <label for="fathermobilenumber" class="form-label fw-semibold small">Father's Mobile</label>
                        <input type="tel" class="form-control" name="fathermobilenumber" id="fathermobilenumber" value="<?php echo htmlspecialchars($student['fathermobilenumber'] ?? ''); ?>" placeholder="Primary guardian phone">
                    </div>
                    <div class="col-sm-6">
                        <label for="motherMobileNumber" class="form-label fw-semibold small">Mother's Mobile</label>
                        <input type="tel" class="form-control" name="motherMobileNumber" id="motherMobileNumber" value="<?php echo htmlspecialchars($student['motherMobileNumber'] ?? ''); ?>" placeholder="Secondary phone">
                    </div>
                </div>

                <div class="mb-3">
                    <label for="parantsmail" class="form-label fw-semibold small">Parent Email Address</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light"><i class="bi bi-envelope"></i></span>
                        <input type="email" class="form-control" name="parantsmail" id="parantsmail" value="<?php echo htmlspecialchars($student['parantsmail'] ?? ''); ?>" placeholder="parent@example.com">
                    </div>
                </div>

                <div class="mb-4">
                    <label for="address" class="form-label fw-semibold small">Residential / Postal Address</label>
                    <textarea class="form-control" name="address" id="address" rows="3" placeholder="Enter complete address"><?php echo htmlspecialchars($student['address'] ?? ''); ?></textarea>
                </div>

                <div class="d-grid pt-2">
                    <button type="submit" class="btn btn-gradient-primary btn-lg rounded-pill fw-bold py-3">
                        <i class="bi bi-save2 me-2"></i> Save Updated Profile
                    </button>
                </div>
            </div>

        </div>
    </form>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
