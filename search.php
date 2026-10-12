<?php
require_once 'includes/auth.php';
require_login();
require_once 'config/db.php';
require_once 'includes/layout.php';

$q = isset($_GET['q']) ? $_GET['q'] : '';
render_header('Search for: ' . $q, 'home');
?>
<div class="card">
  <h1 style="margin-top:0">Search the community</h1>
  <form method="GET" action="search.php" class="header-search" style="margin:0">
    <input type="search" name="q" placeholder="Try “keyboard”, “hub”, “mouse”…" value="<?php echo htmlspecialchars($q, ENT_QUOTES); ?>" style="flex:1">
    <button type="submit">Search</button>
  </form>
  <p class="muted small">Posts, guides, and shared resources. Results update as the community posts.</p>
</div>

<h2>Results for “<?php echo htmlspecialchars($q, ENT_QUOTES); ?>”</h2>
<div class="post-list">
<?php
// Preserved sink: interpolated LIKE query (intentionally vulnerable testbed).
$query = "SELECT * FROM products WHERE name LIKE '%$q%'";
$result = mysql_query($query);
if ($result) {
    $count = 0;
    while ($row = mysql_fetch_assoc($result)) {
        $count++;
        $id = isset($row['id']) ? (int) $row['id'] : 0;
        // Preserved behavior: name/price echoed raw (same as before).
        echo '<article class="post-row">';
        echo '<span class="avatar">' . htmlspecialchars(strtoupper(substr((string) $row['name'], 0, 1)), ENT_QUOTES) . '</span>';
        echo '<div class="post-main">';
        echo '<h3><a href="post.php?id=' . $id . '">' . $row['name'] . '</a></h3>';
        echo '<div class="post-meta"><span>Resource</span><span>$' . $row['price'] . '</span><span>Posted by community</span></div>';
        echo '</div></article>';
    }
    if ($count === 0) {
        echo '<div class="card"><p class="muted">No matches yet. Try “mouse”, “keyboard”, or “hub” — or <a href="upload.php">share a file</a> to start the thread.</p></div>';
    }
} else {
    echo '<div class="card"><p class="error">Search is having a moment. Try again.</p></div>';
}
?>
</div>
<?php render_footer(); ?>
