<?php
include "dbms.php";
require_admin();
include "includes/top.php";
?>
<title>Add Student - Student Management</title>
<?php
include "includes/nav.php";
include "includes/left-side.php";

$errorMessages = [
    1 => "Invalid student name. Please enter a valid name.",
    2 => "Invalid date of birth. Please provide a date.",
    3 => "Please select a valid class.",
    4 => "Please select a valid academic session.",
    5 => "Invalid student mobile number.",
    6 => "Invalid school name.",
    7 => "Please select a valid gender.",
    8 => "Invalid student email address.",
    9 => "Invalid student password (must be at least 6 characters).",
    10 => "Invalid father's name.",
    11 => "Invalid mother's name.",
    12 => "Invalid father's mobile number.",
    13 => "Invalid mother's mobile number.",
    14 => "Invalid parent email address.",
    15 => "Invalid parent password (must be at least 6 characters).",
    16 => "Invalid address.",
    17 => "Student email is already registered.",
    18 => "Parent email is already registered."
];

$errCode = isset($_GET['err']) ? (int)$_GET['err'] : 0;
?>

<div class="container-fluid p-0">
	<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
		<h2 class="text-primary m-0">Student Registration</h2>
		<a href="table.php" class="btn btn-outline-secondary btn-sm">&larr; Back to Students</a>
	</div>

	<?php if ($errCode > 0 && isset($errorMessages[$errCode])): ?>
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			<strong>Error:</strong> <?php echo htmlspecialchars($errorMessages[$errCode]); ?>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php endif; ?>

	<form action="add-student-process.php" method="post" class="card shadow-sm p-4">
		<div class="row">
			<!-- Student Details Column -->
			<div class="col-md-6 border-end pe-md-4">
				<h4 class="text-secondary mb-3 border-bottom pb-2">Student Information</h4>
				
				<div class="mb-3">
					<label for="studentname" class="form-label fw-semibold">Student Full Name <span class="text-danger">*</span></label>
					<input type="text" class="form-control" name="studentname" id="studentname" value="<?php echo htmlspecialchars($_SESSION["studentname"] ?? ''); ?>" placeholder="e.g. John Doe" required>
				</div>

				<div class="mb-3">
					<label for="studentbirth" class="form-label fw-semibold">Date of Birth <span class="text-danger">*</span></label>
					<input type="date" class="form-control" name="studentbirth" id="studentbirth" value="<?php echo htmlspecialchars($_SESSION["studentbirth"] ?? ''); ?>" required>
				</div>

				<div class="mb-3">
					<label for="selectclass" class="form-label fw-semibold">Select Class <span class="text-danger">*</span></label>
					<select class="form-select" name="selectclass" id="selectclass" required>
						<option value="-1">-- Select Class --</option>
						<?php
							$stmt = $conn->prepare("SELECT * FROM tblclass WHERE status = '1' ORDER BY classid ASC");
							$stmt->execute();
							$savedClass = $_SESSION["selectclass"] ?? '';
							while ($cRow = $stmt->fetch()):
						?>
							<option value="<?php echo htmlspecialchars($cRow["classid"]); ?>" <?php echo ($savedClass == $cRow["classid"]) ? 'selected' : ''; ?>>
								<?php echo htmlspecialchars($cRow["classname"]); ?>
							</option>
						<?php endwhile; ?>
					</select>
				</div>

				<div class="mb-3">
					<label for="selectsession" class="form-label fw-semibold">Academic Session <span class="text-danger">*</span></label>
					<select class="form-select" name="selectsession" id="selectsession" required>
						<?php $savedSession = $_SESSION["selectsession"] ?? ''; ?>
						<option value="-1" <?php echo ($savedSession === '' || $savedSession === '-1') ? 'selected' : ''; ?>>-- Select Session --</option>
						<option value="2023-24" <?php echo ($savedSession === '2023-24' || $savedSession === '1') ? 'selected' : ''; ?>>2023-24</option>
						<option value="2024-25" <?php echo ($savedSession === '2024-25') ? 'selected' : ''; ?>>2024-25</option>
						<option value="2025-26" <?php echo ($savedSession === '2025-26') ? 'selected' : ''; ?>>2025-26</option>
					</select>
				</div>

				<div class="mb-3">
					<label for="studentnumber" class="form-label fw-semibold">Student Mobile <span class="text-muted fw-normal">(Optional)</span></label>
					<input type="tel" class="form-control" name="studentnumber" id="studentnumber" value="<?php echo htmlspecialchars($_SESSION["studentnumber"] ?? ''); ?>" placeholder="e.g. 9876543210">
				</div>

				<div class="mb-3">
					<label for="studentschool" class="form-label fw-semibold">School Name <span class="text-danger">*</span></label>
					<input type="text" class="form-control" name="studentschool" id="studentschool" value="<?php echo htmlspecialchars($_SESSION["studentschool"] ?? ''); ?>" placeholder="e.g. Springfield High" required>
				</div>

				<div class="mb-3">
					<label for="selectgender" class="form-label fw-semibold">Gender <span class="text-danger">*</span></label>
					<select class="form-select" name="selectgender" id="selectgender" required>
						<?php $savedGender = $_SESSION["selectgender"] ?? ''; ?>
						<option value="-1">-- Select Gender --</option>
						<option value="1" <?php echo ($savedGender == '1') ? 'selected' : ''; ?>>Male</option>
						<option value="2" <?php echo ($savedGender == '2') ? 'selected' : ''; ?>>Female</option>
					</select>
				</div>

				<div class="mb-3">
					<label for="studentmail" class="form-label fw-semibold">Student Email <span class="text-muted fw-normal">(Optional)</span></label>
					<input type="email" class="form-control" name="studentmail" id="studentmail" value="<?php echo htmlspecialchars($_SESSION["studentmail"] ?? ''); ?>" placeholder="student@example.com">
				</div>

				<div class="mb-3">
					<label for="studentpassword" class="form-label fw-semibold">Student Login Password <span class="text-danger">*</span></label>
					<small class="text-muted d-block mb-1">Minimum 6 characters</small>
					<input type="password" class="form-control" name="studentpassword" id="studentpassword" value="<?php echo htmlspecialchars($_SESSION["studentpassword"] ?? ''); ?>" placeholder="Create student password" required>
				</div>
			</div>

			<!-- Parents Details Column -->
			<div class="col-md-6 ps-md-4">
				<h4 class="text-secondary mb-3 border-bottom pb-2">Parent / Guardian Information</h4>
				
				<div class="mb-3">
					<label for="fathername" class="form-label fw-semibold">Father's Name <span class="text-danger">*</span></label>
					<div class="input-group">
						<span class="input-group-text">Mr.</span>
						<input type="text" class="form-control" name="fathername" id="fathername" value="<?php echo htmlspecialchars($_SESSION["fathername"] ?? ''); ?>" placeholder="Father's full name" required>
					</div>
				</div>

				<div class="mb-3">
					<label for="mothername" class="form-label fw-semibold">Mother's Name <span class="text-danger">*</span></label>
					<div class="input-group">
						<span class="input-group-text">Mrs.</span>
						<input type="text" class="form-control" name="mothername" id="mothername" value="<?php echo htmlspecialchars($_SESSION["mothername"] ?? ''); ?>" placeholder="Mother's full name" required>
					</div>
				</div>

				<div class="mb-3">
					<label for="fathernumber" class="form-label fw-semibold">Father's Mobile <span class="text-danger">*</span></label>
					<input type="tel" class="form-control" name="fathernumber" id="fathernumber" value="<?php echo htmlspecialchars($_SESSION["fathernumber"] ?? ''); ?>" placeholder="Primary contact number" required>
				</div>

				<div class="mb-3">
					<label for="mothernumber" class="form-label fw-semibold">Mother's Mobile <span class="text-muted fw-normal">(Optional)</span></label>
					<input type="tel" class="form-control" name="mothernumber" id="mothernumber" value="<?php echo htmlspecialchars($_SESSION["mothernumber"] ?? ''); ?>" placeholder="Alternative contact number">
				</div>

				<div class="mb-3">
					<label for="parentmail" class="form-label fw-semibold">Parent Email <span class="text-muted fw-normal">(Optional)</span></label>
					<input type="email" class="form-control" name="parentmail" id="parentmail" value="<?php echo htmlspecialchars($_SESSION["parentmail"] ?? ''); ?>" placeholder="parent@example.com">
				</div>

				<div class="mb-3">
					<label for="parentpassword" class="form-label fw-semibold">Parent Login Password <span class="text-danger">*</span></label>
					<small class="text-muted d-block mb-1">Minimum 6 characters</small>
					<input type="password" class="form-control" name="parentpassword" id="parentpassword" value="<?php echo htmlspecialchars($_SESSION["parentpassword"] ?? ''); ?>" placeholder="Create parent password" required>
				</div>

				<div class="mb-3">
					<label for="address" class="form-label fw-semibold">Residential Address <span class="text-danger">*</span></label>
					<textarea class="form-control" name="address" id="address" rows="3" placeholder="Enter complete home address" required><?php echo htmlspecialchars($_SESSION["address"] ?? ''); ?></textarea>
				</div>

				<div class="d-grid gap-2 mt-4">
					<button type="submit" class="btn btn-primary btn-lg">Register Student</button>
				</div>
			</div>
		</div>	
	</form>
</div>

<?php
include "includes/end.php";
?>
