<?php
require_once 'includes/auth.php';
require_login();
require_once 'config/db.php';
require_once 'includes/layout.php';

$members = array();
$res = mysql_query("SELECT * FROM users ORDER BY id ASC");
if ($res) {
    while ($row = mysql_fetch_assoc($res)) { $members[] = $row; }
}

render_header('Members', 'members');
?>
<div class="card">
  <h1 style="margin-top:0">Members</h1>
  <p class="muted"><?php echo count($members); ?> friendly humans. Say hi on someone’s wall.</p>
  <input type="search" id="member-filter" placeholder="Filter by name…" style="max-width:320px">
</div>

<div class="member-grid" id="member-grid" style="margin-top:14px">
  <?php foreach ($members as $i => $m): ?>
  <div class="card member-card member-item">
    <div class="member-top">
      <span class="avatar"><?php echo htmlspecialchars(strtoupper(substr((string) $m['username'], 0, 1)), ENT_QUOTES); ?></span>
      <div>
        <strong><a href="profile.php?user=<?php echo urlencode((string) $m['username']); ?>">@<?php echo htmlspecialchars($m['username'], ENT_QUOTES); ?></a></strong>
        <div class="muted small"><span class="dot <?php echo ($i % 3 === 2) ? 'off' : ''; ?>"></span> <?php echo ($i % 3 === 2) ? 'away' : 'online now'; ?> · joined 2023</div>
      </div>
    </div>
    <p class="muted small member-name">Builder · guides + Q&amp;A</p>
    <p><a class="btn btn-ghost" href="profile.php?user=<?php echo urlencode((string) $m['username']); ?>">View profile</a></p>
  </div>
  <?php endforeach; ?>
</div>

<script>
// Client-side filter only — no new queries.
document.getElementById('member-filter').addEventListener('input', function (e) {
  var needle = e.target.value.toLowerCase();
  document.querySelectorAll('.member-item').forEach(function (el) {
    el.style.display = el.textContent.toLowerCase().indexOf(needle) === -1 ? 'none' : '';
  });
});
</script>
<?php render_footer(); ?>
