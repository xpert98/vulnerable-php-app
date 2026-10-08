<?php
require_once 'includes/auth.php';
require_login();
require_once 'config/db.php';
$q = isset($_GET['q']) ? $_GET['q'] : '';
?>
<!DOCTYPE html>
<html>
<head>
    <title>Search Results</title>
</head>
<body>
<h1>Search Results for: <?php echo htmlspecialchars($q, ENT_QUOTES); ?></h1>
<nav>
    <a href="index.php">Home</a> |
    <a href="logout.php">Logout</a>
</nav>
<hr>
<?php
$query = "SELECT * FROM products WHERE name LIKE '%$q%'";
$result = mysql_query($query);
echo '<p>Results:</p>';
while ($row = mysql_fetch_assoc($result)) {
    echo '<p>' . $row['name'] . ' - $' . $row['price'] . '</p>';
}
?>
</body>
</html>