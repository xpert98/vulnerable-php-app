<?php
$dbhost = 'localhost';
$dbuser = 'root';
$dbpass = '';
$dbname = 'vulnapp';

$conn = mysql_connect($dbhost, $dbuser, $dbpass);
if (!$conn) {
    die('Could not connect: ' . mysql_error());
}

$db_selected = mysql_select_db($dbname, $conn);
if (!$db_selected) {
    die ('Can\'t use db : ' . mysql_error());
}
?>