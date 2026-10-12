<?php
require_once 'includes/auth.php';
require_login();
require_once 'includes/layout.php';

if (!isset($_SESSION['cw_settings'])) {
    $_SESSION['cw_settings'] = array('display_name' => '', 'bio' => '', 'accent' => 'indigo', 'digest' => 'daily');
}
$saved = false;
if (isset($_POST['save'])) {
    $_SESSION['cw_settings']['display_name'] = trim((string) ($_POST['display_name'] ?? ''));
    $_SESSION['cw_settings']['bio'] = trim((string) ($_POST['bio'] ?? ''));
    $_SESSION['cw_settings']['accent'] = (string) ($_POST['accent'] ?? 'indigo');
    $_SESSION['cw_settings']['digest'] = (string) ($_POST['digest'] ?? 'daily');
    $saved = true;
}
$s = $_SESSION['cw_settings'];

render_header('Settings');
?>
<div class="card" style="max-width:640px">
  <h1 style="margin-top:0">Profile settings</h1>
  <p class="muted">Signed in as <strong><?php echo htmlspecialchars(current_user(), ENT_QUOTES); ?></strong>. Preferences are kept for this demo session.</p>
  <?php if ($saved) echo '<p class="success">Saved. Looking sharp.</p>'; ?>
  <form method="POST" action="settings.php" class="stack">
    <label for="display_name">Display name</label>
    <input id="display_name" type="text" name="display_name" value="<?php echo htmlspecialchars($s['display_name'], ENT_QUOTES); ?>" placeholder="e.g. Maya R.">
    <label for="bio">Bio</label>
    <textarea id="bio" name="bio" rows="3" placeholder="Year, major, interests…"><?php echo htmlspecialchars($s['bio'], ENT_QUOTES); ?></textarea>
    <label for="accent">Accent</label>
    <select id="accent" name="accent">
      <?php foreach (array('indigo' => 'Indigo', 'teal' => 'Teal', 'rose' => 'Rose') as $k => $label): ?>
        <option value="<?php echo $k; ?>" <?php echo $s['accent'] === $k ? 'selected' : ''; ?>><?php echo $label; ?></option>
      <?php endforeach; ?>
    </select>
    <label for="digest">Email digest</label>
    <select id="digest" name="digest">
      <?php foreach (array('daily' => 'Daily', 'weekly' => 'Weekly', 'off' => 'Off') as $k => $label): ?>
        <option value="<?php echo $k; ?>" <?php echo $s['digest'] === $k ? 'selected' : ''; ?>><?php echo $label; ?></option>
      <?php endforeach; ?>
    </select>
    <p style="margin-top:14px"><button class="btn btn-primary" type="submit" name="save" value="1">Save changes</button>
    <a class="btn btn-ghost" href="profile.php">View profile</a></p>
  </form>
</div>
<?php render_footer(); ?>
