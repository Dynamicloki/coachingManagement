<?php
// SQLite Database Configuration, Hardened Security, Auto-Initialization & Audit Logging

// 1. Hardened Session Settings
if (session_status() === PHP_SESSION_NONE) {
    ini_set('session.cookie_httponly', 1);
    ini_set('session.use_only_cookies', 1);
    ini_set('session.cookie_samesite', 'Lax');
    session_start();
}
date_default_timezone_set("Asia/Kolkata");

$db_file = __DIR__ . DIRECTORY_SEPARATOR . 'database.sqlite';

try {
    $conn = new PDO("sqlite:" . $db_file);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $conn->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    $conn->exec("PRAGMA foreign_keys = ON;");

    // 2. Core Tables
    $conn->exec("
        CREATE TABLE IF NOT EXISTS tbladmin (
            adminid INTEGER PRIMARY KEY AUTOINCREMENT,
            adminusername TEXT NOT NULL UNIQUE,
            adminpassword TEXT NOT NULL,
            addedDatetime TEXT,
            addedipaddress TEXT
        );

        CREATE TABLE IF NOT EXISTS tblsubject (
            subjectid INTEGER PRIMARY KEY AUTOINCREMENT,
            subjectname TEXT NOT NULL UNIQUE,
            status INTEGER DEFAULT 1,
            addedipaddress TEXT,
            addedDatetime TEXT
        );

        CREATE TABLE IF NOT EXISTS tblclass (
            classid INTEGER PRIMARY KEY AUTOINCREMENT,
            classname TEXT NOT NULL,
            subject TEXT,
            status INTEGER DEFAULT 1,
            addedDateTime TEXT,
            addedIPaddress TEXT
        );

        CREATE TABLE IF NOT EXISTS tblstudent2 (
            studentid INTEGER PRIMARY KEY AUTOINCREMENT,
            studentname TEXT NOT NULL,
            dob TEXT,
            class TEXT,
            season TEXT,
            studentmobile TEXT,
            schoolname TEXT,
            gender TEXT,
            studentmail TEXT,
            studentlogin TEXT,
            fathername TEXT,
            mothername TEXT,
            fathermobilenumber TEXT,
            motherMobileNumber TEXT,
            parantsmail TEXT,
            parentspassword TEXT,
            address TEXT,
            addedDateTime TEXT,
            addedipaddress TEXT
        );

        CREATE TABLE IF NOT EXISTS tbltest_results (
            testid INTEGER PRIMARY KEY AUTOINCREMENT,
            studentid INTEGER NOT NULL,
            subjectid INTEGER NOT NULL,
            test_name TEXT NOT NULL,
            max_marks REAL NOT NULL DEFAULT 100,
            obtained_marks REAL NOT NULL,
            exam_date TEXT,
            remarks TEXT,
            addedDateTime TEXT,
            FOREIGN KEY (studentid) REFERENCES tblstudent2(studentid) ON DELETE CASCADE,
            FOREIGN KEY (subjectid) REFERENCES tblsubject(subjectid) ON DELETE CASCADE
        );

        CREATE TABLE IF NOT EXISTS tbllogs (
            logid INTEGER PRIMARY KEY AUTOINCREMENT,
            user_type TEXT NOT NULL,
            user_id INTEGER,
            username TEXT NOT NULL,
            action TEXT NOT NULL,
            description TEXT NOT NULL,
            ip_address TEXT,
            timestamp TEXT NOT NULL
        );

        CREATE TABLE IF NOT EXISTS tblbackups (
            backupid INTEGER PRIMARY KEY AUTOINCREMENT,
            backup_month TEXT NOT NULL,
            filename TEXT NOT NULL,
            filetype TEXT NOT NULL,
            filesize INTEGER NOT NULL,
            record_count INTEGER NOT NULL,
            created_by TEXT NOT NULL,
            created_at TEXT NOT NULL
        );
    ");

    // Seed default admin if none exists (admin / admin123)
    $adminCheck = $conn->query("SELECT COUNT(*) AS total FROM tbladmin")->fetch();
    if ($adminCheck && $adminCheck['total'] == 0) {
        $defaultPassword = password_hash('admin123', PASSWORD_DEFAULT);
        $now = date("Y-m-d H:i:s");
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $seedAdmin = $conn->prepare("INSERT INTO tbladmin (adminusername, adminpassword, addedDatetime, addedipaddress) VALUES (:u, :p, :dt, :ip)");
        $seedAdmin->execute([':u' => 'admin', ':p' => $defaultPassword, ':dt' => $now, ':ip' => $ip]);
    }

    // Seed sample subjects if none exist
    $subCheck = $conn->query("SELECT COUNT(*) AS total FROM tblsubject")->fetch();
    if ($subCheck && $subCheck['total'] == 0) {
        $now = date("Y-m-d H:i:s");
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $sampleSubjects = ['Mathematics', 'Science', 'English', 'Social Studies'];
        $seedSub = $conn->prepare("INSERT INTO tblsubject (subjectname, status, addedipaddress, addedDatetime) VALUES (:name, 1, :ip, :dt)");
        foreach ($sampleSubjects as $sub) {
            $seedSub->execute([':name' => $sub, ':ip' => $ip, ':dt' => $now]);
        }
    }

    // Seed sample class if none exists
    $classCheck = $conn->query("SELECT COUNT(*) AS total FROM tblclass")->fetch();
    if ($classCheck && $classCheck['total'] == 0) {
        $now = date("Y-m-d H:i:s");
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $seedClass = $conn->prepare("INSERT INTO tblclass (classname, subject, status, addedDateTime, addedIPaddress) VALUES (:name, :sub, 1, :dt, :ip)");
        $seedClass->execute([':name' => 'Class 10', ':sub' => '1+2+3', ':dt' => $now, ':ip' => $ip]);
    }

    // Seed sample test results if none exist
    $testCheck = $conn->query("SELECT COUNT(*) AS total FROM tbltest_results")->fetch();
    if ($testCheck && $testCheck['total'] == 0) {
        $now = date("Y-m-d H:i:s");
        $sampleTests = [
            ['studentid' => 1, 'subjectid' => 1, 'test_name' => 'Mid-Term Exam', 'max_marks' => 100, 'obtained_marks' => 88.5, 'exam_date' => '2026-09-15', 'remarks' => 'Excellent problem solving'],
            ['studentid' => 1, 'subjectid' => 2, 'test_name' => 'Mid-Term Exam', 'max_marks' => 100, 'obtained_marks' => 92.0, 'exam_date' => '2026-09-18', 'remarks' => 'Outstanding in Physics & Chem'],
            ['studentid' => 1, 'subjectid' => 3, 'test_name' => 'Mid-Term Exam', 'max_marks' => 100, 'obtained_marks' => 78.0, 'exam_date' => '2026-09-20', 'remarks' => 'Good grammar, work on essay'],
            ['studentid' => 1, 'subjectid' => 4, 'test_name' => 'Mid-Term Exam', 'max_marks' => 100, 'obtained_marks' => 85.0, 'exam_date' => '2026-09-22', 'remarks' => 'Very good grasp of History']
        ];
        $stmtTest = $conn->prepare("
            INSERT INTO tbltest_results (studentid, subjectid, test_name, max_marks, obtained_marks, exam_date, remarks, addedDateTime)
            VALUES (:sid, :subid, :tname, :mmarks, :omarks, :edate, :rem, :adt)
        ");
        foreach ($sampleTests as $t) {
            $stmtTest->execute([
                ':sid' => $t['studentid'],
                ':subid' => $t['subjectid'],
                ':tname' => $t['test_name'],
                ':mmarks' => $t['max_marks'],
                ':omarks' => $t['obtained_marks'],
                ':edate' => $t['exam_date'],
                ':rem' => $t['remarks'],
                ':adt' => $now
            ]);
        }
    }

} catch (PDOException $e) {
    die("Database Connection Error: " . $e->getMessage());
}

/**
 * CSRF Protection Helpers
 */
function get_csrf_token() {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function verify_csrf_token($token) {
    if (empty($_SESSION['csrf_token']) || empty($token)) {
        return false;
    }
    return hash_equals($_SESSION['csrf_token'], $token);
}

/**
 * Audit Logging Helper
 */
function add_log($conn, $user_type, $user_id, $username, $action, $description) {
    try {
        $ip = $_SERVER['REMOTE_ADDR'] ?? '127.0.0.1';
        $now = date("Y-m-d H:i:s");
        $stmt = $conn->prepare("
            INSERT INTO tbllogs (user_type, user_id, username, action, description, ip_address, timestamp)
            VALUES (:ut, :uid, :un, :act, :desc, :ip, :ts)
        ");
        $stmt->execute([
            ':ut' => $user_type,
            ':uid' => $user_id ? (int)$user_id : null,
            ':un' => $username ?: 'Anonymous',
            ':act' => strtoupper($action),
            ':desc' => $description,
            ':ip' => $ip,
            ':ts' => $now
        ]);
    } catch (Exception $e) {
        // Silently ignore to avoid breaking user flow
    }
}

/**
 * Access Control: Require Admin Role
 */
function require_admin() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || ($_SESSION['role'] ?? '') !== 'admin') {
        header("Location: login.php?err=admin_required");
        exit;
    }
}

/**
 * Access Control: Require Student Role
 */
function require_student() {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }
    if (!isset($_SESSION['loggedin']) || $_SESSION['loggedin'] !== true || ($_SESSION['role'] ?? '') !== 'student') {
        header("Location: login.php?err=student_required");
        exit;
    }
}

/**
 * Create a snapshot backup of all SQL data and audit logs for a given month
 */
function create_monthly_backup($conn, $month = null, $createdBy = 'SYSTEM_AUTO', $format = 'JSON') {
    if (empty($month)) {
        $month = date('Y-m');
    }
    
    $backupDir = __DIR__ . '/backups';
    if (!is_dir($backupDir)) {
        mkdir($backupDir, 0755, true);
        file_put_contents($backupDir . '/.htaccess', "Deny from all\n");
    }

    $format = strtoupper($format);
    $timestamp = date('Y-m-d_His');
    $cleanMonth = preg_replace('/[^0-9\-]/', '', $month);
    
    // Fetch all database records
    $tables = ['tbladmin', 'tblclass', 'tblsubject', 'tblstudent2', 'tbltest_results', 'tbllogs'];
    $data = [];
    $totalRecords = 0;
    
    foreach ($tables as $table) {
        $stmt = $conn->query("SELECT * FROM $table");
        $rows = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $data[$table] = $rows;
        $totalRecords += count($rows);
    }
    
    if ($format === 'SQLITE') {
        $filename = "backup_{$cleanMonth}_{$timestamp}.sqlite";
        $filePath = $backupDir . '/' . $filename;
        $sourceDb = __DIR__ . '/database.sqlite';
        copy($sourceDb, $filePath);
        $filesize = filesize($filePath);
    } else {
        $format = 'JSON';
        $filename = "backup_{$cleanMonth}_{$timestamp}.json";
        $filePath = $backupDir . '/' . $filename;
        $backupPayload = [
            'meta' => [
                'system' => 'Apex Coaching Institute Management System',
                'backup_month' => $month,
                'created_at' => date('Y-m-d H:i:s'),
                'created_by' => $createdBy,
                'total_records' => $totalRecords,
                'table_counts' => array_map(function($rows) { return count($rows); }, $data)
            ],
            'tables' => $data
        ];
        file_put_contents($filePath, json_encode($backupPayload, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));
        $filesize = filesize($filePath);
    }
    
    $now = date('Y-m-d H:i:s');
    $stmt = $conn->prepare("
        INSERT INTO tblbackups (backup_month, filename, filetype, filesize, record_count, created_by, created_at)
        VALUES (:m, :fn, :ft, :fs, :rc, :cb, :ca)
    ");
    $stmt->execute([
        ':m' => $month,
        ':fn' => $filename,
        ':ft' => $format,
        ':fs' => $filesize,
        ':rc' => $totalRecords,
        ':cb' => $createdBy,
        ':ca' => $now
    ]);
    $newId = $conn->lastInsertId();
    
    add_log($conn, 'System', null, $createdBy, 'BACKUP', "Created monthly backup for $month: $filename ($format, $totalRecords records)");
    return $newId;
}

/**
 * Check if the current month already has a monthly backup. If not, auto-generate it.
 */
function check_and_auto_generate_monthly_backup($conn) {
    try {
        $currentMonth = date('Y-m');
        $stmt = $conn->prepare("SELECT COUNT(*) AS total FROM tblbackups WHERE backup_month = :m");
        $stmt->execute([':m' => $currentMonth]);
        $row = $stmt->fetch();
        if (!$row || (int)$row['total'] === 0) {
            // Auto generate monthly backup for current month
            create_monthly_backup($conn, $currentMonth, 'SYSTEM_AUTO', 'JSON');
        }
    } catch (Exception $e) {
        // Silently skip on initial bootstrap
    }
}

// Automatically ensure current month has a backup snapshot
check_and_auto_generate_monthly_backup($conn);
?>
