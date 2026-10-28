<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<div class="container-fluid p-0">
	<div class="row g-0">
		<!-- Modern Dark Glassmorphic Sidebar -->
		<div class="col-md-3 col-lg-2 side-bar p-3 d-flex flex-column justify-content-between">
			<div>
				<!-- Sidebar Header / Brand -->
				<div class="d-flex align-items-center gap-2 pb-3 mb-3 border-bottom border-secondary border-opacity-25 px-2">
					<div class="bg-primary text-white rounded-3 p-2 d-flex align-items-center justify-content-center shadow-sm">
						<i class="bi bi-speedometer2 fs-5"></i>
					</div>
					<div>
						<h6 class="fw-bold text-white m-0">Admin Suite</h6>
						<small class="text-white-50" style="font-size: 0.75rem;">Management v2.0</small>
					</div>
				</div>

				<!-- Navigation Links -->
				<ul class="nav flex-column gap-1">
					<li class="nav-item">
						<span class="text-uppercase text-white-50 px-2 small fw-bold" style="font-size: 0.7rem;">Academic Core</span>
					</li>
					<li class="nav-item">
						<a class="menu1 <?php echo in_array($currentPage, ['table.php', 'add-student.php', 'student-edit.php']) ? 'active bg-primary text-white' : ''; ?>" href="table.php">
							<i class="bi bi-mortarboard-fill me-2"></i> Students
						</a>
					</li>
					<li class="nav-item">
						<a class="menu1 <?php echo in_array($currentPage, ['class-table.php', 'add-class2.php', 'class-edit.php']) ? 'active bg-primary text-white' : ''; ?>" href="class-table.php">
							<i class="bi bi-diagram-3-fill me-2"></i> Classes & Batches
						</a>
					</li>
					<li class="nav-item">
						<a class="menu1 <?php echo in_array($currentPage, ['subjects-table.php', 'add-subject.php', 'subject-edit.php']) ? 'active bg-primary text-white' : ''; ?>" href="subjects-table.php">
							<i class="bi bi-journal-bookmark-fill me-2"></i> Subjects
						</a>
					</li>
					
					<li class="nav-item mt-3">
						<span class="text-uppercase text-white-50 px-2 small fw-bold" style="font-size: 0.7rem;">Examinations</span>
					</li>
					<li class="nav-item">
						<a class="menu1 <?php echo in_array($currentPage, ['test-results-table.php', 'add-test-result.php', 'test-result-edit.php']) ? 'active bg-primary text-white' : ''; ?>" href="test-results-table.php">
							<i class="bi bi-trophy-fill me-2"></i> Test Results
						</a>
					</li>

					<li class="nav-item mt-3">
						<span class="text-uppercase text-white-50 px-2 small fw-bold" style="font-size: 0.7rem;">Security & Logs</span>
					</li>
					<li class="nav-item">
						<a class="menu1 <?php echo ($currentPage === 'logs.php') ? 'active bg-primary text-white' : ''; ?>" href="logs.php">
							<i class="bi bi-card-checklist me-2"></i> Activity Logs
						</a>
					</li>
					<li class="nav-item">
						<a class="menu1 <?php echo ($currentPage === 'backups.php') ? 'active bg-primary text-white' : ''; ?>" href="backups.php">
							<i class="bi bi-cloud-arrow-down-fill me-2"></i> Monthly Backups
						</a>
					</li>
					<li class="nav-item">
						<a class="menu1 <?php echo in_array($currentPage, ['add-admin.php', 'admin-premative.php']) ? 'active bg-primary text-white' : ''; ?>" href="add-admin.php">
							<i class="bi bi-shield-lock-fill me-2"></i> Admin Team
						</a>
					</li>
				</ul>
			</div>

			<!-- Quick Info at bottom of sidebar -->
			<div class="pt-3 border-top border-secondary border-opacity-25 px-2">
				<div class="d-flex align-items-center gap-2 mb-2">
					<span class="p-1 rounded-circle bg-success"></span>
					<small class="text-white-50">System: <strong>Online</strong></small>
				</div>
				<a href="logout.php" class="btn btn-outline-danger btn-sm w-100 rounded-pill py-1">
					<i class="bi bi-box-arrow-right me-1"></i> Sign Out
				</a>
			</div>
		</div>		

		<!-- Main Content Area -->
		<div class="col-md-9 col-lg-10 p-4 p-xl-5" style="background-color: #f8fafc; min-height: 100vh;">
