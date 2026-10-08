<?php
require_once 'includes/auth.php';
require_login();
require_once 'config/db.php';

$user = current_user();
$comment = '';
$comments = array();

if (isset($_POST['comment'])) {
    $comment = trim((string) $_POST['comment']);
    if ($comment !== '') {
        $u = mysql_real_escape_string($user);
        $c = mysql_real_escape_string($comment);
        mysql_query("INSERT INTO comments (user, comment) VALUES ('$u', '$c')");
    }
}

$query = "SELECT * FROM comments ORDER BY id DESC";
$result = mysql_query($query);
while ($row = mysql_fetch_assoc($result)) {
    $comments[] = $row;
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Profile</title>
</head>
<body>
<h1>Profile: <?php echo htmlspecialchars($user, ENT_QUOTES); ?></h1>
<nav>
    <a href="index.php">Home</a> |
    <a href="logout.php">Logout</a>
</nav>
<hr>
<h2><?php echo htmlspecialchars($user, ENT_QUOTES); ?></h2>
<h3>Comments</h3>
<form method="POST" action="profile.php">
    <textarea name="comment" rows="4" cols="50"></textarea><br>
    <input type="submit" value="Post Comment">
</form>
<div>
<?php foreach ($comments as $c): ?>
    <p><strong><?php echo htmlspecialchars($c['user'], ENT_QUOTES); ?>:</strong> <?php echo htmlspecialchars($c['comment'], ENT_QUOTES); ?></p>
<?php endforeach; ?>
</div>
</body>
</html>
