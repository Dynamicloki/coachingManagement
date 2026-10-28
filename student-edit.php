<?php
include "dbms.php";
require_admin();
include "includes/top.php";
?>
<title>Edit Student - Student Management</title>
<?php
include "includes/nav.php";
include "includes/left-side.php";

$studentid = $_REQUEST["studentid"] ?? null;
if (empty($studentid)) {
    header("Location: table.php");
    exit;
}

$stmt = $conn->prepare("SELECT * FROM tblstudent2 WHERE studentid = :studentid");
$stmt->bindParam(":studentid", $studentid, PDO::PARAM_INT);
$stmt->execute();
$result = $stmt->fetch();

if (!$result) {
    header("Location: table.php");
    exit;
}

$errorMessages = [
    1 => "Invalid student name. Please enter a valid name.",
    2 => "Invalid date of birth.",
    3 => "Please select a valid class.",
    4 => "Please select a valid session.",
    5 => "Invalid student mobile number.",
    6 => "Invalid school name.",
    7 => "Please select a valid gender.",
    8 => "Invalid student email.",
    9 => "Invalid student password.",
    10 => "Invalid father name.",
    11 => "Invalid mother name.",
    12 => "Invalid father mobile number.",
    13 => "Invalid mother mobile number.",
    14 => "Invalid parent email.",
    15 => "Invalid parent password.",
    16 => "Invalid address."
];

$errCode = isset($_GET['err']) ? (int)$_GET['err'] : 0;
?>

<div class="container-fluid p-0">
	<div class="d-flex justify-content-between align-items-center mb-4 border-bottom pb-2">
		<h2 class="text-primary m-0">Edit Student Record: <?php echo htmlspecialchars($result["studentname"]); ?></h2>
		<a href="table.php" class="btn btn-outline-secondary btn-sm">&larr; Back to Students</a>
	</div>

	<?php if ($errCode > 0 && isset($errorMessages[$errCode])): ?>
		<div class="alert alert-danger alert-dismissible fade show" role="alert">
			<strong>Error:</strong> <?php echo htmlspecialchars($errorMessages[$errCode]); ?>
			<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
		</div>
	<?php endif; ?>

	<form action="student-edit-process.php" method="post" class="card shadow-sm p-4">
		<input type="hidden" name="studentid" value="<?php echo htmlspecialchars($result["studentid"]); ?>">

		<div class="row">
			<!-- Student Details -->
			<div class="col-md-6 border-end pe-md-4">
				<h4 class="text-secondary mb-3 border-bottom pb-2">Student Information</h4>

				<div class="mb-3">
					<label for="studentname" class="form-label fw-semibold">Student Full Name <span class="text-danger">*</span></label>
					<input type="text" class="form-control" id="studentname" name="studentname" value="<?php echo htmlspecialchars($result["studentname"] ?? ''); ?>" required>
				</div>

				<div class="mb-3">
					<label for="dob" class="form-label fw-semibold">Date of Birth <span class="text-danger">*</span></label>
					<input type="date" class="form-control" id="dob" name="dob" value="<?php echo htmlspecialchars($result["dob"] ?? ''); ?>" required>
				</div>

				<div class="mb-3">
					<label for="classSelect" class="form-label fw-semibold">Class <span class="text-danger">*</span></label>
					<select class="form-select" name="class" id="classSelect" required>
						<option value="-1">-- Select Class --</option>
						<?php
							$cStmt = $conn->prepare("SELECT * FROM tblclass WHERE status = '1' ORDER BY classid ASC");
							$cStmt->execute();
							while ($cRow = $cStmt->fetch()):
								$selected = ($result["class"] == $cRow["classid"] || $result["class"] == $cRow["classname"]) ? 'selected' : '';
						?>
							<option value="<?php echo htmlspecialchars($cRow["classid"]); ?>" <?php echo $selected; ?>>
								<?php echo htmlspecialchars($cRow["classname"]); ?>
							</option>
						<?php endwhile; ?>
					</select>
				</div>

				<div class="mb-3">
					<label for="seasonSelect" class="form-label fw-semibold">Academic Session <span class="text-danger">*</span></label>
					<select class="form-select" name="season" id="seasonSelect" required>
						<option value="-1">-- Select Session --</option>
						<option value="2023-24" <?php if ($result["season"] === '2023-24' || $result["season"] == '1') echo "selected"; ?>>2023-24</option>
						<option value="2024-25" <?php if ($result["season"] === '2024-25') echo "selected"; ?>>2024-25</option>
						<option value="2025-26" <?php if ($result["season"] === '2025-26') echo "selected"; ?>>2025-26</option>
					</select>
				</div>

				<div class="mb-3">
					<label for="studentmobile" class="form-label fw-semibold">Student Mobile <span class="text-muted fw-normal">(Optional)</span></label>
					<input type="tel" class="form-control" id="studentmobile" name="studentmobile" value="<?php echo htmlspecialchars($result["studentmobile"] ?? ''); ?>">
				</div>

				<div class="mb-3">
					<label for="schoolname" class="form-label fw-semibold">School Name <span class="text-danger">*</span></label>
					<input type="text" class="form-control" id="schoolname" name="schoolname" value="<?php echo htmlspecialchars($result["schoolname"] ?? ''); ?>" required>
				</div>

				<div class="mb-3">
					<label for="genderSelect" class="form-label fw-semibold">Gender <span class="text-danger">*</span></label>
					<select class="form-select" name="gender" id="genderSelect" required>
						<option value="-1">-- Select Gender --</option>
						<option value="1" <?php if ($result["gender"] == "1") echo "selected"; ?>>Male</option>
						<option value="2" <?php if ($result["gender"] == "2") echo "selected"; ?>>Female</option>
					</select>
				</div>

				<div class="mb-3">
					<label for="studentmail" class="form-label fw-semibold">Student Email <span class="text-muted fw-normal">(Optional)</span></label>
					<input type="email" class="form-control" id="studentmail" name="studentmail" value="<?php echo htmlspecialchars($result["studentmail"] ?? ''); ?>">
				</div>

				<div class="mb-3">
					<label for="studentlogin" class="form-label fw-semibold">Student Password <span class="text-danger">*</span></label>
					<input type="text" class="form-control" id="studentlogin" name="studentlogin" value="<?php echo htmlspecialchars($result["studentlogin"] ?? ''); ?>" required>
				</div>
			</div>

			<!-- Parents Details -->
			<div class="col-md-6 ps-md-4">
				<h4 class="text-secondary mb-3 border-bottom pb-2">Parent / Guardian Information</h4>

				<div class="mb-3">
					<label for="fathername" class="form-label fw-semibold">Father's Name <span class="text-danger">*</span></label>
					<div class="input-group">
						<span class="input-group-text">Mr.</span>
						<input type="text" class="form-control" id="fathername" name="fathername" value="<?php echo htmlspecialchars($result["fathername"] ?? ''); ?>" required>
					</div>
				</div>

				<div class="mb-3">
					<label for="mothername" class="form-label fw-semibold">Mother's Name <span class="text-danger">*</span></label>
					<div class="input-group">
						<span class="input-group-text">Mrs.</span>
						<input type="text" class="form-control" id="mothername" name="mothername" value="<?php echo htmlspecialchars($result["mothername"] ?? ''); ?>" required>
					</div>
				</div>

				<div class="mb-3">
					<label for="fathermobilenumber" class="form-label fw-semibold">Father's Mobile <span class="text-danger">*</span></label>
					<input type="tel" class="form-control" id="fathermobilenumber" name="fathermobilenumber" value="<?php echo htmlspecialchars($result["fathermobilenumber"] ?? ''); ?>" required>
				</div>

				<div class="mb-3">
					<label for="motherMobileNumber" class="form-label fw-semibold">Mother's Mobile <span class="text-muted fw-normal">(Optional)</span></label>
					<input type="tel" class="form-control" id="motherMobileNumber" name="motherMobileNumber" value="<?php echo htmlspecialchars($result["motherMobileNumber"] ?? ''); ?>">
				</div>

				<div class="mb-3">
					<label for="parentsmail" class="form-label fw-semibold">Parents Email <span class="text-muted fw-normal">(Optional)</span></label>
					<input type="email" class="form-control" id="parentsmail" name="parentsmail" value="<?php echo htmlspecialchars($result["parantsmail"] ?? ''); ?>">
				</div>

				<div class="mb-3">
					<label for="parentsmailpassword" class="form-label fw-semibold">Parents Password <span class="text-danger">*</span></label>
					<input type="text" class="form-control" id="parentsmailpassword" name="parentsmailpassword" value="<?php echo htmlspecialchars($result["parentspassword"] ?? ''); ?>" required>
				</div>

				<div class="mb-3">
					<label for="address" class="form-label fw-semibold">Address <span class="text-danger">*</span></label>
					<textarea class="form-control" name="address" id="address" rows="3" required><?php echo htmlspecialchars($result["address"] ?? ''); ?></textarea>
				</div>

				<div class="d-grid gap-2 mt-4">
					<button type="submit" class="btn btn-success btn-lg">Update Student Information</button>
				</div>
			</div>
		</div>
	</form>
</div>

<?php
include "includes/end.php";
?>
