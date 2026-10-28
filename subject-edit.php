<?php
include "dbms.php";
require_admin();

$subjectid = $_REQUEST["subjectid"] ?? null;
if (empty($subjectid)) {
    header("Location: subjects-table.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM tblsubject WHERE subjectid = :id");
$stmt->bindParam(':id', $subjectid, PDO::PARAM_INT);
$stmt->execute();
$subject = $stmt->fetch();

if (!$subject) {
    header("Location: subjects-table.php");
    exit;
}

include "includes/top.php";
?>
<title>Edit Subject - Admin Panel</title>
<?php
include "includes/nav.php";
include "includes/left-side.php";
?>

<div class="container-fluid p-0">
    <div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2 flex-wrap gap-2">
        <div>
            <h2 class="text-primary fw-bold m-0">Edit Subject: <?php echo htmlspecialchars($subject['subjectname']); ?> ✏️</h2>
            <p class="text-muted m-0">Update subject title and active status</p>
        </div>
        <a href="subjects-table.php" class="btn btn-outline-secondary btn-sm rounded-pill px-3">
            <i class="bi bi-arrow-left me-1"></i> Back to Subjects
        </a>
    </div>

    <?php if (isset($_GET['err'])): ?>
        <div class="alert alert-danger alert-dismissible fade show rounded-4 border-0 p-3 shadow-sm" role="alert">
            <i class="bi bi-exclamation-triangle-fill me-2"></i>
            <?php
                if ($_GET['err'] == 1) echo "Please enter a valid subject name.";
                elseif ($_GET['err'] == 2) echo "A subject with this name already exists.";
                else echo "An error occurred while updating the subject.";
            ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="row">
        <div class="col-md-6">
            <form action="subject-edit-process.php" method="POST" class="glass-card p-4 p-md-5 border shadow-sm rounded-4">
                <input type="hidden" name="subjectid" value="<?php echo htmlspecialchars($subject['subjectid']); ?>">

                <div class="mb-3">
                    <label for="subjectNameInput" class="form-label fw-semibold">Subject Name <span class="text-danger">*</span></label>
                    <input type="text" class="form-control form-control-lg fs-6" id="subjectNameInput" name="subjectname" value="<?php echo htmlspecialchars($subject['subjectname']); ?>" required>
                </div>
                  
                <div class="mb-4">
                    <label class="form-label fw-semibold d-block">Status</label>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" id="statusActive" value="1" <?php if ($subject['status'] == '1') echo 'checked'; ?>>
                        <label class="form-check-label fw-medium" for="statusActive">Active</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="status" id="statusInactive" value="0" <?php if ($subject['status'] == '0') echo 'checked'; ?>>
                        <label class="form-check-label fw-medium" for="statusInactive">Inactive</label>
                    </div>
                </div>

                <div>
                    <button type="submit" class="btn btn-gradient-primary btn-lg rounded-pill px-4 fw-bold">
                        <i class="bi bi-save2 me-2"></i> Update Subject
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<?php
include "includes/end.php";
?>
