<?php
require_once 'includes/auth.php';
require_login();
require_once 'includes/layout.php';

$message = '';
$message_ok = false;
if (isset($_FILES['file'])) {
    // Preserved behavior: no type validation, original filename, web-accessible.
    $target = 'uploads/' . $_FILES['file']['name'];
    if (move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
        $message = 'File uploaded successfully! Access at: ' . $target;
        $message_ok = true;
    } else {
        $message = 'Upload failed';
    }
}

render_header('Shared library', 'library');
?>
<div class="card">
  <h1 style="margin-top:0">Shared library</h1>
  <p class="muted">Uploading as <strong><?php echo htmlspecialchars(current_user(), ENT_QUOTES); ?></strong> — configs, templates, screenshots, project zips. Everything here is visible to the community.</p>
  <?php if ($message) echo '<p class="' . ($message_ok ? 'success' : 'error') . '">' . htmlspecialchars($message, ENT_QUOTES) . '</p>'; ?>
  <form method="POST" enctype="multipart/form-data" class="stack">
    <label for="file">Choose a file</label>
    <input id="file" type="file" name="file">
    <p><button class="btn btn-primary" type="submit">Upload to library</button></p>
  </form>
  <p class="muted small">Tip: PDF, PNG, ZIP under 25 MB work best. Please only share files you have the right to post.</p>
</div>

<h2>Fresh uploads</h2>
<div class="file-grid">
<?php
$files = @scandir('uploads');
if ($files) {
    $shown = 0;
    foreach ($files as $file) {
        if ($file != '.' && $file != '..' && $file !== '.gitkeep') {
            $shown++;
            echo '<div class="card file-card">';
            echo '<div style="font-size:22px">📄</div>';
            echo '<div><a href="uploads/' . htmlspecialchars($file, ENT_QUOTES) . '">' . htmlspecialchars($file, ENT_QUOTES) . '</a></div>';
            echo '<div class="muted small">Shared with community</div>';
            echo '</div>';
        }
    }
    if ($shown === 0) {
        echo '<div class="card"><p class="muted">Nothing here yet — upload the first guide.</p></div>';
    }
}
?>
</div>
<?php render_footer(); ?>
