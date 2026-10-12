<?php
// Shared layout for Tech Wire. No logic changes — presentation only.
require_once __DIR__ . '/auth.php';

function tw_initial($name) {
    $name = (string) $name;
    return $name !== '' ? strtoupper(substr($name, 0, 1)) : '?';
}

function render_header($title = 'Tech Wire', $active = '') {
    $logged_in = function_exists('is_logged_in') ? is_logged_in() : false;
    $user = $logged_in && function_exists('current_user') ? current_user() : null;
    $q = isset($_GET['q']) ? (string) $_GET['q'] : '';
    ?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?php echo htmlspecialchars($title, ENT_QUOTES); ?> · Tech Wire</title>
<link rel="stylesheet" href="assets/css/style.css">
</head>
<body>
<header class="site-header">
  <div class="container header-inner">
    <a class="brand" href="index.php">
      <span class="brand-mark">TW</span>
      <span class="brand-name">Tech Wire</span>
    </a>
    <nav class="main-nav">
      <a href="index.php" class="<?php echo $active === 'home' ? 'active' : ''; ?>">Home</a>
      <a href="members.php" class="<?php echo $active === 'members' ? 'active' : ''; ?>">Members</a>
      <a href="upload.php" class="<?php echo $active === 'library' ? 'active' : ''; ?>">Library</a>
      <a href="about.php" class="<?php echo $active === 'about' ? 'active' : ''; ?>">About</a>
      <?php if ($logged_in): ?>
      <a href="notifications.php" class="<?php echo $active === 'activity' ? 'active' : ''; ?>">Activity</a>
      <?php endif; ?>
    </nav>
    <form class="header-search" method="GET" action="search.php" role="search">
      <input type="search" name="q" placeholder="Search posts, resources…" value="<?php echo htmlspecialchars($q, ENT_QUOTES); ?>">
      <button type="submit">Search</button>
    </form>
    <div class="header-actions">
      <?php if ($logged_in): ?>
        <a class="avatar-chip" href="profile.php" title="Your profile">
          <span class="avatar"><?php echo htmlspecialchars(tw_initial($user), ENT_QUOTES); ?></span>
          <span class="username"><?php echo htmlspecialchars($user, ENT_QUOTES); ?></span>
        </a>
        <a class="btn btn-ghost" href="settings.php">Settings</a>
        <a class="btn btn-secondary" href="logout.php">Log out</a>
      <?php else: ?>
        <a class="btn btn-ghost" href="login.php">Log in</a>
        <a class="btn btn-primary" href="register.php">Join</a>
      <?php endif; ?>
    </div>
  </div>
</header>
<main class="container page">
    <?php
}

function render_footer() {
    ?>
</main>
<footer class="site-footer">
  <div class="container footer-inner">
    <div>
      <strong>Tech Wire</strong>
      <p class="muted">A community hub for developers and tech folks. Share guides, ask questions, post resources.</p>
    </div>
    <nav class="footer-nav">
      <a href="about.php">About</a>
      <a href="about.php#guidelines">Guidelines</a>
      <a href="about.php#safety">Safety</a>
      <a href="about.php#contact">Contact</a>
      <a href="members.php">Members</a>
    </nav>
  </div>
  <div class="container footer-base muted">© <?php echo date('Y'); ?> Tech Wire · Demo community · Built for security training</div>
</footer>
</body>
</html>
    <?php
}
