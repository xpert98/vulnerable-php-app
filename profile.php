<?php
require_once 'includes/auth.php';
require_login();
require_once 'config/db.php';
require_once 'includes/layout.php';

$user = current_user();
// Display-only variant for member directory links (?user=). No new SQL —
// the wall query below is unchanged.
$view_user = isset($_GET['user']) && $_GET['user'] !== '' ? (string) $_GET['user'] : $user;

$comment = '';
$comments = array();

if (isset($_POST['comment'])) {
    $comment = trim((string) $_POST['comment']);
    if ($comment !== '') {
        // INTENTIONALLY VULNERABLE (testbed): interpolated raw so the wall
        // INSERT is injectable. Do NOT add escaping here.
        $u = $user;
        $c = $comment;
        mysql_query("INSERT INTO comments (user, comment) VALUES ('$u', '$c')");
    }
}

$query = "SELECT * FROM comments ORDER BY id DESC";
$result = mysql_query($query);
if ($result) {
    while ($row = mysql_fetch_assoc($result)) {
        $comments[] = $row;
    }
}

render_header('@' . $view_user, 'home');
?>

<div class="card">
  <div class="member-top">
    <span class="avatar lg"><?php echo htmlspecialchars(strtoupper(substr($view_user, 0, 1)), ENT_QUOTES); ?></span>
    <div>
      <h1 style="margin:0">@<?php echo htmlspecialchars($view_user, ENT_QUOTES); ?></h1>
      <p class="muted small" style="margin:2px 0">Member since 2023 · 128 posts · Dev + Homelab</p>
      <?php if ($view_user !== $user): ?>
        <p class="small muted">You’re viewing a fellow member. You’re signed in as <strong><?php echo htmlspecialchars($user, ENT_QUOTES); ?></strong>.</p>
      <?php else: ?>
        <p class="small muted">This is your corner of Tech Wire. Say hi below.</p>
      <?php endif; ?>
    </div>
  </div>
</div>

<div class="grid grid-2" style="margin-top:16px">
  <section>
    <div class="card">
      <h3 style="margin-top:0">Drop a reply</h3>
      <form method="POST" action="profile.php<?php echo $view_user !== $user ? '?user=' . urlencode($view_user) : ''; ?>" class="stack">
        <textarea name="comment" rows="4" placeholder="Share a tip, ask a question, thank someone…"></textarea>
        <p><button class="btn btn-primary" type="submit">Post reply</button></p>
      </form>
      <p class="muted small">Posting as <strong><?php echo htmlspecialchars($user, ENT_QUOTES); ?></strong>. Be kind.</p>
    </div>

    <h3>Community wall</h3>
    <div class="post-list">
    <?php foreach ($comments as $c): ?>
      <article class="post-row">
        <span class="avatar"><?php echo htmlspecialchars(strtoupper(substr((string) $c['user'], 0, 1)), ENT_QUOTES); ?></span>
        <div class="post-main">
          <div class="post-meta"><strong><?php echo htmlspecialchars($c['user'], ENT_QUOTES); ?></strong><span><?php echo isset($c['created_at']) ? htmlspecialchars($c['created_at'], ENT_QUOTES) : ''; ?></span></div>
          <p style="margin:6px 0 0"><?php echo $c['comment']; ?></p>
        </div>
      </article>
    <?php endforeach; ?>
    <?php if (empty($comments)): ?>
      <div class="card"><p class="muted">No replies yet — be the first to break the ice.</p></div>
    <?php endif; ?>
    </div>
  </section>

  <aside class="grid" style="align-content:start">
    <div class="card">
      <h3 style="margin-top:0">About</h3>
      <p class="muted small">Backend dev · homelab + keyboards. Into budget builds, dotfiles, and late-night deploys.</p>
      <p><span class="pill">Guides</span> <span class="pill">Q&amp;A</span></p>
      <p><a class="btn btn-ghost" href="upload.php">View shared files</a></p>
    </div>
    <div class="card">
      <h3 style="margin-top:0">More members</h3>
      <p class="muted small">Find study buddies and collaborators.</p>
      <p><a href="members.php">Browse directory →</a></p>
    </div>
  </aside>
</div>

<?php render_footer(); ?>
