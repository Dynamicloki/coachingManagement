<?php
// Handy CLI Database Inspector
$dbFile = __DIR__ . DIRECTORY_SEPARATOR . "database.sqlite";

if (!file_exists($dbFile)) {
    die("Database file not found at: $dbFile\n");
}

$db = new PDO("sqlite:" . $dbFile);
$db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);

$tables = $db->query("SELECT name FROM sqlite_master WHERE type='table' AND name != 'sqlite_sequence'")->fetchAll(PDO::FETCH_COLUMN);

$tableArg = $argv[1] ?? null;

if (!$tableArg) {
    echo "========================================\n";
    echo "       DATABASE TABLES & COUNTS         \n";
    echo "========================================\n";
    foreach ($tables as $table) {
        $count = $db->query("SELECT COUNT(*) FROM \"$table\"")->fetchColumn();
        echo " - " . str_pad($table, 15) . " : $count row(s)\n";
    }
    echo "========================================\n";
    echo "To inspect a specific table, run:\n";
    echo "  php view-db.php <table_name>\n";
    echo "Examples:\n";
    echo "  php view-db.php tblstudent2\n";
    echo "  php view-db.php tbladmin\n";
    echo "  php view-db.php tblclass\n";
    echo "  php view-db.php tblsubject\n";
    exit;
}

if (!in_array($tableArg, $tables)) {
    echo "Error: Table '$tableArg' does not exist.\n";
    echo "Available tables: " . implode(", ", $tables) . "\n";
    exit(1);
}

$rows = $db->query("SELECT * FROM \"$tableArg\"")->fetchAll();
echo "========================================\n";
echo " Table: $tableArg (" . count($rows) . " row(s))\n";
echo "========================================\n";

if (empty($rows)) {
    echo "No records found in this table.\n";
} else {
    print_r($rows);
}
?>
