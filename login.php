<?php
require_once 'includes/auth.php';

if (is_logged_in()) {
    header('Location: profile.php');
    exit;
}

$error = '';

if (isset($_POST['login'])) {
    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');

    if ($username === '' || $password === '') {
        $error = 'Please enter a username and password';
    } elseif (attempt_login($username, $password)) {
        header('Location: profile.php');
        exit;
    } else {
        $error = 'Invalid credentials';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
<h1>Login</h1>
<a href="index.php">Home</a>
<hr>
<?php if ($error) echo '<p style="color:red">' . htmlspecialchars($error, ENT_QUOTES) . '</p>'; ?>
<p>Sign in with your username and password to continue.</p>
<form method="POST" action="login.php">
    <p>Username: <input type="text" name="username" required></p>
    <p>Password: <input type="password" name="password" required></p>
    <p><input type="submit" name="login" value="Login"></p>
</form>
</body>
</html>
