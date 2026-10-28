<?php
// Forwarder for compatibility
header("Location: add-class2.php" . (!empty($_SERVER['QUERY_STRING']) ? '?' . $_SERVER['QUERY_STRING'] : ''));
exit;
?>
