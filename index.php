<?php
require_once 'includes/auth.php';
$message = '';
if (isset($_GET['msg'])) {
    $message = $_GET['msg'];
}
$logged_in = is_logged_in();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Vulnerable PHP App</title>
</head>
<body>
<h1>Vulnerable PHP Test Application</h1>
<nav>
    <a href="index.php">Home</a> |
    <?php if ($logged_in): ?>
        <a href="profile.php">Profile</a> |
        <a href="upload.php">Upload</a> |
        <a href="logout.php">Logout</a>
    <?php else: ?>
        <a href="login.php">Login</a>
    <?php endif; ?>
</nav>
<hr>
<?php
echo '<div>' . htmlspecialchars($message, ENT_QUOTES) . '</div>';
?>
<h2>Welcome<?php echo $logged_in ? ' ' . htmlspecialchars(current_user(), ENT_QUOTES) : ''; ?></h2>
<p>This is a deliberately vulnerable application for penetration testing.</p>
<p>Profile, search, and upload features require login.</p>
<?php if (!$logged_in): ?>
<p><a href="login.php">&rarr; Log in to continue</a></p>
<?php endif; ?>
</body>
</html>
