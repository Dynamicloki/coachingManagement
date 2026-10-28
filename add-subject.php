<?php
include "dbms.php";
require_admin();
include "includes/top.php";
?>
<title>Subject Management - Add Subject</title>
<?php
include "includes/nav.php";
include "includes/left-side.php";
?>

<div class="container-fluid p-0">
	<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
		<h2 class="text-primary m-0">Add New Subject</h2>
		<a href="subjects-table.php" class="btn btn-outline-secondary btn-sm">View All Subjects</a>
	</div>

	<?php if (isset($_GET['err']) && $_GET['err'] == 1): ?>
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			Please enter a valid subject name.
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php elseif (isset($_GET['err']) && $_GET['err'] == 2): ?>
		<div class="alert alert-warning alert-dismissible fade show" role="alert">
			This subject already exists in the system.
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php elseif (isset($_GET['msg']) && $_GET['msg'] == 1): ?>
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			Subject added successfully!
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php endif; ?>

	<div class="row">
		<div class="col-md-6">
			<form action="add-subject-process.php" method="post" class="card shadow-sm p-4">
				<div class="mb-3">
					<label for="subjectNameInput" class="form-label fw-semibold">Subject Name <span class="text-danger">*</span></label>
					<input type="text" class="form-control" value="<?php echo htmlspecialchars($_SESSION["subjectname"] ?? ''); ?>" id="subjectNameInput" name="subjectname" placeholder="e.g. Physics" required>
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

				<div class="mt-3">
					<button class="btn btn-primary" type="submit">Add Subject</button>
				</div>
			</form>
		</div>
	</div>
</div>

<?php
include "includes/end.php";
?>
