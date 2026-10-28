<?php
include "dbms.php";

if (isset($_SESSION['loggedin']) && $_SESSION['loggedin'] === true) {
    $role = $_SESSION['role'] ?? 'User';
    $userId = $_SESSION['adminid'] ?? $_SESSION['studentid'] ?? null;
    $username = $_SESSION['adminusername'] ?? $_SESSION['studentname'] ?? 'User';

    // Audit Log: User Logout
    add_log($conn, ucfirst($role), $userId, $username, 'LOGOUT', "$role ($username) logged out");
}

$_SESSION = [];
if (ini_get("session.use_cookies")) {
    $params = session_get_cookie_params();
    setcookie(session_name(), '', time() - 42000,
        $params["path"], $params["domain"],
        $params["secure"], $params["httponly"]
    );
}
session_destroy();

header("Location: login.php?msg=logout");
exit;
?>
