<?php
require_once 'includes/auth.php';
require_login();
require_once 'config/db.php';
require_once 'includes/layout.php';

$comments = array();
$cres = mysql_query("SELECT * FROM comments ORDER BY id DESC LIMIT 10");
if ($cres) { while ($r = mysql_fetch_assoc($cres)) { $comments[] = $r; } }

$posts = array();
$pres = mysql_query("SELECT * FROM products ORDER BY id DESC LIMIT 5");
if ($pres) { while ($r = mysql_fetch_assoc($pres)) { $posts[] = $r; } }

render_header('Activity', 'activity');
?>
<div class="card">
  <h1 style="margin-top:0">Activity <span class="pill"><?php echo count($comments) + count($posts); ?> new</span></h1>
  <p class="muted">Replies, new guides, and library drops from people you follow.</p>
</div>

<div class="timeline" style="margin-top:14px">
  <?php foreach ($comments as $c): ?>
  <div class="card timeline-item">
    <span class="timeline-icon">💬</span>
    <div>
      <div><strong><?php echo htmlspecialchars($c['user'], ENT_QUOTES); ?></strong> replied on the wall</div>
      <div class="muted">“<?php echo htmlspecialchars(mb_strimwidth((string) $c['comment'], 0, 120, '…'), ENT_QUOTES); ?>”</div>
      <div class="muted small"><?php echo isset($c['created_at']) ? htmlspecialchars($c['created_at'], ENT_QUOTES) : 'just now'; ?> · <a href="profile.php">View thread</a></div>
    </div>
  </div>
  <?php endforeach; ?>
  <?php foreach ($posts as $p): ?>
  <div class="card timeline-item">
    <span class="timeline-icon">📚</span>
    <div>
      <div>New guide: <strong><a href="post.php?id=<?php echo (int) $p['id']; ?>"><?php echo $p['name']; ?></a></strong></div>
      <div class="muted small">Resource · $<?php echo $p['price']; ?> · <a href="search.php?q=<?php echo urlencode((string) $p['name']); ?>">Find similar</a></div>
    </div>
  </div>
  <?php endforeach; ?>
</div>
<?php render_footer(); ?>
