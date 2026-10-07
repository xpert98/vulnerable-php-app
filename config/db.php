<?php
require_once __DIR__ . '/../includes/mysql_compat.php';

$dbhost = getenv('MYSQL_HOST') ?: 'localhost';
$dbuser = getenv('MYSQL_USER') ?: 'root';
$dbpass = getenv('MYSQL_PASS') ?: '';
$dbname = getenv('MYSQL_DB') ?: 'vulnapp';

$conn = mysql_connect($dbhost, $dbuser, $dbpass);
if (!$conn) {
    die('Could not connect: ' . mysql_error());
}

$db_selected = mysql_select_db($dbname);
if (!$db_selected) {
    die('Can\'t use db : ' . mysql_error());
}
?>
