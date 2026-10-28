<?php
include "dbms.php";

function check_input($data) {
    $data = trim($data ?? '');
    $data = stripslashes($data);
    $data = htmlspecialchars($data, ENT_QUOTES, 'UTF-8');
    return $data;
}

$input = check_input($_REQUEST['email'] ?? '');
$password = trim($_REQUEST['password'] ?? '');

if (empty($input) || empty($password)) {
    header("Location: login.php?err=1");
    exit;
}

// 1. Check in tbladmin
$stmtAdmin = $conn->prepare("SELECT * FROM tbladmin WHERE adminusername = :input");
$stmtAdmin->execute([':input' => $input]);
$adminUser = $stmtAdmin->fetch();

if ($adminUser) {
    $adminHash = $adminUser['adminpassword'];
    $isValidAdmin = password_verify($password, $adminHash) || (md5($password) === $adminHash) || ($password === $adminHash);

    if ($isValidAdmin) {
        session_regenerate_id(true);
        $_SESSION['loggedin'] = true;
        $_SESSION['role'] = 'admin';
        $_SESSION['adminid'] = $adminUser['adminid'];
        $_SESSION['adminusername'] = $adminUser['adminusername'];

        // Audit Log: Admin Login
        add_log($conn, 'Admin', $adminUser['adminid'], $adminUser['adminusername'], 'LOGIN', 'Admin authenticated into Admin Panel');

        header("Location: table.php");
        exit;
    }
}

// 2. Check in tblstudent2
$stmtStudent = $conn->prepare("SELECT * FROM tblstudent2 WHERE (studentmail = :mail OR studentmobile = :mobile OR studentname = :sname)");
$stmtStudent->execute([
    ':mail' => $input,
    ':mobile' => $input,
    ':sname' => $input
]);
$studentUser = $stmtStudent->fetch();

if ($studentUser) {
    $studentPass = $studentUser['studentlogin'];
    $isValidStudent = ($password === $studentPass) || (md5($password) === $studentPass) || password_verify($password, $studentPass);

    if ($isValidStudent) {
        session_regenerate_id(true);
        $_SESSION['loggedin'] = true;
        $_SESSION['role'] = 'student';
        $_SESSION['studentid'] = $studentUser['studentid'];
        $_SESSION['studentname'] = $studentUser['studentname'];
        $_SESSION['studentmail'] = $studentUser['studentmail'];

        // Audit Log: Student Login
        add_log($conn, 'Student', $studentUser['studentid'], $studentUser['studentname'], 'LOGIN', 'Student authenticated into Student Portal');

        header("Location: student-dashboard.php");
        exit;
    }
}

// Failed login log
add_log($conn, 'Guest', null, $input, 'LOGIN_FAILED', "Failed login attempt for identifier '$input'");

header("Location: login.php?err=1");
exit;
?>
