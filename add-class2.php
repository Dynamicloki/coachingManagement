<?php
include "dbms.php";
require_admin();
include "includes/top.php";
?>
<title>Class Management - Add Class</title>
<?php
include "includes/nav.php";
include "includes/left-side.php";
?>

<div class="container-fluid p-0">
	<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
		<h2 class="text-primary m-0">Add New Class</h2>
		<a href="class-table.php" class="btn btn-outline-secondary btn-sm">View All Classes</a>
	</div>

	<?php if (isset($_GET['err']) && $_GET['err'] == 1): ?>
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			Please enter a valid class name.
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php elseif (isset($_GET['err']) && $_GET['err'] == 2): ?>
		<div class="alert alert-warning alert-dismissible fade show" role="alert">
			This class name already exists in the system.
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php elseif (isset($_GET['msg']) && $_GET['msg'] == 1): ?>
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			Class added successfully!
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php endif; ?>

	<form action="add-class-process.php" method="post" class="card shadow-sm p-4">
		<div class="row">
			<div class="col-md-6 border-end pe-md-4">
				<h4 class="text-secondary mb-3 border-bottom pb-2">Class Details</h4>
				
				<div class="mb-3">
					<label for="classNameInput" class="form-label fw-semibold">Class Name <span class="text-danger">*</span></label>
					<input type="text" class="form-control" value="<?php echo htmlspecialchars($_SESSION["class"] ?? ''); ?>" id="classNameInput" name="class" placeholder="e.g. Class 11 - Science" required>
				</div>
				  
				<div class="mb-3">
					<label class="form-label fw-semibold d-block">Status</label>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="status" id="statusActive" value="1" checked>
						<label class="form-check-label" for="statusActive">Active</label>
					</div>
					<div class="form-check form-check-inline">
						<input class="form-check-input" type="radio" name="status" id="statusInactive" value="0">
						<label class="form-check-label" for="statusInactive">Inactive</label>
					</div>
				</div>

				<div class="mt-4">
					<button class="btn btn-primary" type="submit">Add Class</button>
				</div>
			</div>
		
			<div class="col-md-6 ps-md-4">
				<h4 class="text-secondary mb-3 border-bottom pb-2">Assign Subjects</h4>
				<p class="text-muted small">Select subjects associated with this class:</p>
				
				<div class="row">
				<?php
					$stmt = $conn->prepare("SELECT * FROM tblsubject WHERE status = '1' ORDER BY subjectname ASC");
					$stmt->execute();
					$hasSubjects = false;
					while ($row = $stmt->fetch()):
						$hasSubjects = true;
				?>
					<div class="col-sm-6 mb-2">
						<div class="form-check">
							<input class="form-check-input" type="checkbox" name="subject[]" value="<?php echo htmlspecialchars($row["subjectid"]); ?>" id="sub_<?php echo htmlspecialchars($row["subjectid"]); ?>">
							<label class="form-check-label" for="sub_<?php echo htmlspecialchars($row["subjectid"]); ?>">
								<?php echo htmlspecialchars($row["subjectname"]); ?>
							</label>
						</div>
					</div>
				<?php endwhile; ?>
				</div>

				<?php if (!$hasSubjects): ?>
					<p class="text-warning">No subjects found. <a href="add-subject.php">Add subjects first</a>.</p>
				<?php endif; ?>
			</div>
		</div>
	</form>
</div>

<?php
include "includes/end.php";
?>
