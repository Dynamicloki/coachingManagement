<?php
include "dbms.php";
require_admin();

$classid = $_REQUEST["classid"] ?? null;
if (empty($classid)) {
    header("Location: class-table.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM tblclass WHERE classid = :id");
$stmt->bindParam(':id', $classid, PDO::PARAM_INT);
$stmt->execute();
$class = $stmt->fetch();

if (!$class) {
    header("Location: class-table.php");
    exit;
}

// Explode currently assigned subjects
$assignedSubjects = !empty($class['subject']) ? explode('+', $class['subject']) : [];

include "includes/top.php";
?>
<title>Edit Class - Admin Panel</title>
<?php
include "includes/nav.php";
include "includes/left-side.php";
?>

<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2 flex-wrap gap-2">
        <div>
            <h2 class="text-primary fw-bold m-0">Edit Class: <?php echo htmlspecialchars($class['classname']); ?> ✏️</h2>
            <p class="text-muted m-0">Modify class name, status, and assigned curriculum subjects</p>
        </div>
        <a href="class-table.php" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i> Back to Classes
        </a>
    </div>

    <?php if (isset($_GET['err'])): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 p-3 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?php
                if ($_GET['err'] == 1) echo "Please enter a valid class name.";
                elseif ($_GET['err'] == 2) echo "A class with this name already exists.";
                else echo "An error occurred while updating the class.";
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <form action="class-edit-process.php" method="POST" class="glass-card p-4 p-md-5 border shadow-sm rounded-4">
        <input type="hidden" name="classid" value="<?php echo htmlspecialchars($class['classid']); ?>">

        <div class="row g-4">
            <div class="col-md-6 border-end-md pe-md-4">
                <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                    <i class="bi bi-mortarboard text-primary me-2"></i> Class Details
                </h5>

                <div class="mb-3">
                    <label for="classNameInput" class="form-label fw-semibold">Class Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-lg fs-6" id="classNameInput" name="class" value="<?php echo htmlspecialchars($class['classname']); ?>" required>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-semibold d-block">Status</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" id="statusActive" value="1" <?php if ($class['status'] == '1') echo 'checked'; ?>>
                        <label class="form-check-label fw-medium" for="statusActive">Active</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" id="statusInactive" value="0" <?php if ($class['status'] == '0') echo 'checked'; ?>>
                        <label class="form-check-label fw-medium" for="statusInactive">Inactive</label>
                    </div>
                </div>

                <div class="pt-3">
                    <button type="submit" class="btn btn-gradient-primary btn-lg rounded-pill px-4 fw-bold">
                        <i class="bi bi-save2 me-2"></i> Update Class
                    </button>
                </div>
            </div>

            <div class="col-md-6 ps-md-4">
                <h5 class="fw-bold text-dark mb-3 pb-2 border-bottom">
                    <i class="bi bi-book text-primary me-2"></i> Assign Subjects
                </h5>
                <p class="text-muted small">Select all subjects applicable to this class:</p>

                <div class="row g-2">
                <?php
                    $stmt = $conn->prepare("SELECT * FROM tblsubject WHERE status = '1' ORDER BY subjectname ASC");
                    $stmt->execute();
                    $hasSubjects = false;
                    while ($sub = $stmt->fetch()):
                        $hasSubjects = true;
                        $isChecked = in_array((string)$sub['subjectid'], $assignedSubjects) ? 'checked' : '';
                ?>
                    <div class="col-sm-6">
                        <div class="p-2 border rounded-3 bg-light d-flex align-items-center">
                            <input class="form-check-input me-2" type="checkbox" name="subject[]" value="<?php echo htmlspecialchars($sub['subjectid']); ?>" id="sub_<?php echo htmlspecialchars($sub['subjectid']); ?>" <?php echo $isChecked; ?>>
                            <label class="form-check-label small fw-semibold text-dark m-0" for="sub_<?php echo htmlspecialchars($sub['subjectid']); ?>">
                                <?php echo htmlspecialchars($sub['subjectname']); ?>
                            </label>
                        </div>
                    </div>
                <?php endwhile; ?>
                </div>

                <?php if (!$hasSubjects): ?>
                    <p class="text-warning small mt-2">No active subjects available. <a href="add-subject.php">Add a subject</a>.</p>
                <?php endif; ?>
            </div>
        </div>
    </form>
</div>

<?php
include "includes/end.php";
?>
