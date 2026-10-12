<?php
require_once 'includes/auth.php';
require_once 'includes/layout.php';

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

render_header('Log in');
?>
<div class="auth-wrap">
  <div class="card auth-card">
    <h1 style="margin-top:0">Welcome back</h1>
    <p class="muted auth-sub">Log in to reply, post, and browse the library.</p>
    <?php if ($error) echo '<p class="error">' . htmlspecialchars($error, ENT_QUOTES) . '</p>'; ?>
    <form method="POST" action="login.php" class="stack">
      <label for="username">Username</label>
      <input id="username" type="text" name="username" required autocomplete="username" placeholder="e.g. alice">
      <label for="password">Password</label>
      <input id="password" type="password" name="password" required autocomplete="current-password" placeholder="••••••••">
      <p style="margin-top:16px"><button class="btn btn-primary" style="width:100%" type="submit" name="login" value="1">Log in</button></p>
    </form>
    <p class="small">New to Tech Wire? <a href="register.php">Create an account</a> · <a href="about.php">What is this?</a></p>
  </div>
</div>
<?php render_footer(); ?>
