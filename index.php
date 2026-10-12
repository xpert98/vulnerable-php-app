<?php
require_once 'includes/auth.php';
require_once 'includes/layout.php';
$message = '';
if (isset($_GET['msg'])) {
    $message = $_GET['msg'];
}
$logged_in = is_logged_in();

render_header('Home', 'home');
?>

<div class="hero">
  <h1>Ship things. Share how you built them.</h1>
  <p>Tech Wire is a community for developers and tech folks — build guides, homelab write-ups, tool reviews, and honest Q&amp;A.</p>
  <div class="hero-actions">
    <?php if ($logged_in): ?>
      <a class="btn btn-secondary" href="search.php?q=">Explore posts</a>
      <a class="btn btn-ghost" href="upload.php">Share a file</a>
    <?php else: ?>
      <a class="btn btn-secondary" href="register.php">Join the community</a>
      <a class="btn btn-ghost" href="login.php">Log in</a>
    <?php endif; ?>
  </div>
</div>

<?php
// Preserved sink: ?msg= reflected (same escaping semantics as before).
if ($message !== '') {
    echo '<div class="notice">' . htmlspecialchars($message, ENT_QUOTES) . '</div>';
}
?>

<div class="grid grid-2" style="margin-top:16px">
  <section>
    <h2 style="margin:4px 0 12px">Trending this week</h2>
    <div class="post-list">
      <article class="post-row">
        <span class="avatar">M</span>
        <div class="post-main">
          <h3><a href="post.php?id=1">Home Lab Setup Guide — Proxmox on a mini PC</a></h3>
          <div class="post-meta"><span>by maya</span><span>214 replies</span><span class="pill">Guide</span></div>
          <p class="muted small">Hardware picks, VLAN basics, and the $20 cable tester everyone swears by.</p>
        </div>
      </article>
      <article class="post-row">
        <span class="avatar">J</span>
        <div class="post-main">
          <h3><a href="post.php?id=2">How I finally got good at debugging Python</a></h3>
          <div class="post-meta"><span>by jonah</span><span>96 replies</span><span class="pill">Dev</span></div>
          <p class="muted small">Traceback reading, logging setups, and a breakpoint workflow.</p>
        </div>
      </article>
      <article class="post-row">
        <span class="avatar">P</span>
        <div class="post-main">
          <h3><a href="post.php?id=3">Self-hosted RSS — escape the algorithm</a></h3>
          <div class="post-meta"><span>by priya</span><span>187 replies</span><span class="pill">Self-host</span></div>
          <p class="muted small">FreshRSS vs Miniflux, OPML starter packs, and phone sync.</p>
        </div>
      </article>
    </div>

    <div class="card" style="margin-top:16px">
      <h3 style="margin-top:0">New here?</h3>
      <p class="muted">Create an account to reply, post resources, and upload files to the shared library. It takes ten seconds.</p>
      <?php if (!$logged_in): ?>
      <p><a class="btn btn-primary" href="register.php">Create account</a>
      <a class="btn btn-ghost" href="about.php">How it works</a></p>
      <?php else: ?>
      <p><a class="btn btn-primary" href="profile.php">Go to your profile</a>
      <a class="btn btn-ghost" href="members.php">Meet members</a></p>
      <p class="muted small">Profile, search, and uploads require login — you’re in<?php echo ' ' . htmlspecialchars(current_user(), ENT_QUOTES); ?>.</p>
      <?php endif; ?>
    </div>
  </section>

  <aside class="grid" style="align-content:start">
    <div class="card">
      <h3 style="margin-top:0">Community stats</h3>
      <p class="muted small">8,412 members · 1,204 guides · 96 online now</p>
      <p><span class="pill">Dev</span> <span class="pill">Homelab</span> <span class="pill">Self-host</span> <span class="pill">Projects</span></p>
      <form method="GET" action="search.php" class="stack">
        <label for="home-q">Try search</label>
        <input id="home-q" type="search" name="q" placeholder="e.g. keyboard, docker, homelab">
        <p><button class="btn btn-secondary" type="submit">Search</button></p>
      </form>
    </div>
    <div class="card">
      <h3 style="margin-top:0">House rules</h3>
      <ul class="muted small">
        <li>Be kind. No spam, no hate.</li>
        <li>Give credit for shared files.</li>
        <li>Use the library for course-safe uploads.</li>
      </ul>
      <p><a href="about.php#guidelines">Read guidelines →</a></p>
    </div>
  </aside>
</div>

<?php render_footer(); ?>
