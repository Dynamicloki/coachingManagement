<?php
include "dbms.php";
require_admin();
include "includes/top.php";
?>
<title>Class Management - All Classes</title>
<?php
include "includes/nav.php";
include "includes/left-side.php";

$stmt = $conn->prepare("SELECT * FROM tblclass ORDER BY classid DESC");
$stmt->execute();
?>

<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2 flex-wrap gap-2">
    <div>
        <h2 class="text-primary fw-bold m-0">All Classes 🏫</h2>
        <p class="text-muted m-0">Manage batches, assigned subjects, and active status</p>
    </div>
    <a href="add-class2.php" class="btn btn-primary btn-sm rounded-pill px-3">+ Add New Class</a>
</div>

<?php if (isset($_GET['msg'])): ?>
    <?php if ($_GET['msg'] === 'updated'): ?>
        <div class="alert alert-success alert-dismissible fade show rounded-4 border-0 p-3 shadow-sm" role="alert">
            <i class="bi bi-check-circle-fill me-2"></i> Class updated successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php elseif ($_GET['msg'] === 'deleted'): ?>
        <div class="alert alert-warning alert-dismissible fade show rounded-4 border-0 p-3 shadow-sm" role="alert">
            <i class="bi bi-trash-fill me-2"></i> Class deleted successfully!
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>
<?php endif; ?>

<div class="glass-card border shadow-sm rounded-4 overflow-hidden">
    <div class="table-responsive">
        <table class="table table-hover table-striped align-middle mb-0">
            <thead class="table-dark">
                <tr>
                    <th scope="col" style="width: 80px;">ID</th>
                    <th scope="col">Class Name</th>
                    <th scope="col">Assigned Subject IDs</th>
                    <th scope="col">Status</th>
                    <th scope="col">Added On</th>
                    <th scope="col" style="width: 140px;" class="text-center">Actions</th>
                </tr>
            </thead>
            <tbody>
            <?php 
            $count = 0;
            while ($row = $stmt->fetch()): 
                $count++;
            ?>
                <tr>
                    <td class="fw-bold text-muted">#<?php echo htmlspecialchars($row['classid']); ?></td>
                    <td class="fw-bold text-dark"><?php echo htmlspecialchars($row['classname']); ?></td>
                    <td><code><?php echo htmlspecialchars($row['subject'] ?: 'None'); ?></code></td>
                    <td>
                        <?php if ($row['status'] == '1'): ?>
                            <span class="badge bg-success rounded-pill px-3 py-1">Active</span>
                        <?php else: ?>
                            <span class="badge bg-secondary rounded-pill px-3 py-1">Inactive</span>
                        <?php endif; ?>
                    </td>
                    <td><small class="text-muted"><?php echo htmlspecialchars($row['addedDateTime'] ?? 'N/A'); ?></small></td>
                    <td class="text-center">
                        <a href="class-edit.php?classid=<?php echo urlencode($row['classid']); ?>" class="btn btn-outline-primary btn-sm rounded-circle me-1" title="Edit Class">
                            <i class="bi bi-pencil"></i>
                        </a>
                        <a href="class-delete.php?classid=<?php echo urlencode($row['classid']); ?>" class="btn btn-outline-danger btn-sm rounded-circle" onclick="return confirm('Delete this class?');" title="Delete Class">
                            <i class="bi bi-trash"></i>
                        </a>
                    </td>
                </tr>
            <?php endwhile; ?>
            <?php if ($count === 0): ?>
                <tr>
                    <td colspan="6" class="text-center text-muted py-5">No classes registered yet. <a href="add-class2.php">Add one now</a>.</td>
                </tr>
            <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<?php
include "includes/end.php";
?>
