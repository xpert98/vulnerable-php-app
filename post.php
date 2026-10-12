<?php
require_once 'includes/auth.php';
require_login();
require_once 'config/db.php';
require_once 'includes/layout.php';

$id = isset($_GET['id']) ? (string) $_GET['id'] : '1';
if ($id === '') { $id = '1'; }

// Intentionally interpolated numeric lookup (same testbed style as search.php).
$query = "SELECT * FROM products WHERE id = $id";
$result = mysql_query($query);
$post = $result ? mysql_fetch_assoc($result) : null;

$related = array();
$rres = mysql_query("SELECT * FROM products LIMIT 4");
if ($rres) {
    while ($r = mysql_fetch_assoc($rres)) {
        if ($post && isset($post['id']) && $r['id'] == $post['id']) { continue; }
        $related[] = $r;
        if (count($related) >= 3) { break; }
    }
}

$replies = array();
$cres = mysql_query("SELECT * FROM comments ORDER BY id DESC LIMIT 5");
if ($cres) {
    while ($c = mysql_fetch_assoc($cres)) { $replies[] = $c; }
}

render_header($post ? $post['name'] : 'Post', 'home');
?>

<p><a href="index.php">← Back to feed</a></p>

<?php if ($post): ?>
<div class="card">
  <p><span class="pill">Guide</span> <span class="muted small">Posted by community · 3 min read</span></p>
  <h1 style="margin:6px 0"><?php echo $post['name']; ?></h1>
  <p class="muted">Reference price: $<?php echo $post['price']; ?> · <a href="search.php?q=<?php echo urlencode((string) $post['name']); ?>">More like this</a></p>
  <div class="member-top" style="margin-top:12px">
    <span class="avatar">M</span>
    <div><strong>maya</strong><div class="muted small">Backend dev · homelab + keyboards · 128 posts</div></div>
  </div>
  <hr style="border:0;border-top:1px solid var(--line);margin:16px 0">
  <p>This is the community write-up for <strong><?php echo $post['name']; ?></strong>. Members use this page to swap tips, corrections, and cheaper alternatives in the replies. If you grabbed this from the library, drop what worked for you below.</p>
  <ul>
    <li>What to buy (and what to skip)</li>
    <li>How to set it up in under an hour</li>
    <li>Common mistakes + fixes</li>
  </ul>
  <p><a class="btn btn-primary" href="profile.php">Join the discussion</a> <a class="btn btn-ghost" href="upload.php">Share your version</a></p>
</div>

<div class="grid grid-2" style="margin-top:16px">
  <section>
    <h3>Latest replies</h3>
    <div class="post-list">
      <?php foreach ($replies as $c): ?>
      <article class="post-row">
        <span class="avatar"><?php echo htmlspecialchars(strtoupper(substr((string) $c['user'], 0, 1)), ENT_QUOTES); ?></span>
        <div class="post-main">
          <div class="post-meta"><strong><?php echo htmlspecialchars($c['user'], ENT_QUOTES); ?></strong></div>
          <p style="margin:6px 0 0"><?php echo htmlspecialchars($c['comment'], ENT_QUOTES); ?></p>
        </div>
      </article>
      <?php endforeach; ?>
      <?php if (empty($replies)): ?>
        <div class="card"><p class="muted">No replies yet.</p></div>
      <?php endif; ?>
    </div>
  </section>
  <aside>
    <div class="card">
      <h3 style="margin-top:0">Related posts</h3>
      <?php foreach ($related as $rel): ?>
        <p><a href="post.php?id=<?php echo (int) $rel['id']; ?>"><?php echo $rel['name']; ?></a><br><span class="muted small">$<?php echo $rel['price']; ?></span></p>
      <?php endforeach; ?>
    </div>
  </aside>
</div>

<?php else: ?>
<div class="card"><h1>Post not found</h1><p class="muted">Try the <a href="index.php">feed</a> or <a href="search.php?q=">search</a>.</p></div>
<?php endif; ?>

<?php render_footer(); ?>
