<?php
require_once 'includes/auth.php';
require_once 'includes/layout.php';

if (is_logged_in()) {
    header('Location: profile.php');
    exit;
}

$error = '';
if (isset($_POST['register'])) {
    require_once 'config/db.php';
    $username = trim((string) ($_POST['username'] ?? ''));
    $password = (string) ($_POST['password'] ?? '');
    $confirm = (string) ($_POST['confirm'] ?? '');

    if ($username === '' || $password === '') {
        $error = 'Pick a username and password to continue';
    } elseif ($password !== $confirm) {
        $error = 'Passwords do not match';
    } else {
        // Same testbed style: escaped + interpolated (intentionally vulnerable family).
        $u = mysql_real_escape_string($username);
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $h = mysql_real_escape_string($hash);
        $ok = mysql_query("INSERT INTO users (username, password) VALUES ('$u', '$h')");
        if ($ok) {
            $res = mysql_query("SELECT * FROM users WHERE username = '$u' LIMIT 1");
            $row = $res ? mysql_fetch_assoc($res) : null;
            if ($row) {
                $_SESSION['user_id'] = (int) $row['id'];
                $_SESSION['username'] = $row['username'];
                header('Location: profile.php');
                exit;
            }
            $error = 'Account created — please log in';
        } else {
            $error = 'That username is taken. Try another.';
        }
    }
}

render_header('Join Tech Wire');
?>
<div class="auth-wrap">
  <div class="card auth-card">
    <h1 style="margin-top:0">Join Tech Wire</h1>
    <p class="muted auth-sub">One account for replies, posts, and the library.</p>
    <?php if ($error) echo '<p class="error">' . htmlspecialchars($error, ENT_QUOTES) . '</p>'; ?>
    <form method="POST" action="register.php" class="stack">
      <label for="username">Username</label>
      <input id="username" type="text" name="username" required placeholder="e.g. maya_27">
      <label for="password">Password</label>
      <input id="password" type="password" name="password" required placeholder="Make it a good one">
      <label for="confirm">Confirm password</label>
      <input id="confirm" type="password" name="confirm" required placeholder="One more time">
      <p style="margin-top:16px"><button class="btn btn-primary" style="width:100%" type="submit" name="register" value="1">Create account</button></p>
    </form>
    <p class="small">Already a member? <a href="login.php">Log in</a></p>
  </div>
</div>
<?php render_footer(); ?>
