<?php
include "dbms.php";
require_admin();
include "includes/top.php";
?>
<title>Admin Management - Register Admin</title>
<?php
include "includes/nav.php";
include "includes/left-side.php";
?>

<div class="container-fluid p-0">
	<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
		<h2 class="text-primary m-0">Admin Registration</h2>
		<a href="login.php" class="btn btn-outline-secondary btn-sm">Go to Login</a>
	</div>

	<?php if (isset($_GET['err']) && $_GET['err'] == 1): ?>
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			Please enter both username and password.
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php elseif (isset($_GET['err']) && $_GET['err'] == 2): ?>
		<div class="alert alert-warning alert-dismissible fade show" role="alert">
			This admin username is already taken. Please choose another.
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php elseif (isset($_GET['msg']) && $_GET['msg'] == 1): ?>
		<div class="alert alert-success alert-dismissible fade show" role="alert">
			Admin user created successfully!
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php endif; ?>

	<div class="row">
		<div class="col-md-6">
			<div class="card shadow-sm">
				<div class="card-body p-4">
					<form action="add-admin-process.php" method="POST">
						<div class="mb-3">
							<label for="adminUsername" class="form-label fw-semibold">Admin Username <span class="text-danger">*</span></label>
							<input type="text" class="form-control" name="adminusername" id="adminUsername" placeholder="e.g. admin_john" required>
						</div> 
						<div class="mb-3">
							<label for="adminPassword" class="form-label fw-semibold">Admin Password <span class="text-danger">*</span></label>
							<input type="password" name="adminpassword" class="form-control" id="adminPassword" placeholder="Enter secure password" required>
						</div>
						<button type="submit" class="btn btn-primary">Create Admin User</button>
					</form>
				</div>
			</div>
		</div>

		<div class="col-md-6">
			<div class="card shadow-sm">
				<div class="card-header bg-dark text-white">
					<h5 class="m-0">Existing Administrators</h5>
				</div>
				<div class="card-body p-0">
					<ul class="list-group list-group-flush">
						<?php
							$stmt = $conn->prepare("SELECT adminid, adminusername, addedDatetime FROM tbladmin ORDER BY adminid ASC");
							$stmt->execute();
							while ($admin = $stmt->fetch()):
						?>
							<li class="list-group-item d-flex justify-content-between align-items-center">
								<div>
									<strong><?php echo htmlspecialchars($admin['adminusername']); ?></strong>
									<br><small class="text-muted">Added: <?php echo htmlspecialchars($admin['addedDatetime'] ?? 'System default'); ?></small>
								</div>
								<span class="badge bg-primary rounded-pill">#<?php echo htmlspecialchars($admin['adminid']); ?></span>
							</li>
						<?php endwhile; ?>
					</ul>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
include "includes/end.php";
?>
