<?php
require_once 'includes/auth.php';
require_login();
$message = '';
if (isset($_FILES['file'])) {
    $target = 'uploads/' . $_FILES['file']['name'];
    if (move_uploaded_file($_FILES['file']['tmp_name'], $target)) {
        $message = 'File uploaded successfully! Access at: ' . $target;
    } else {
        $message = 'Upload failed';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>File Upload</title>
</head>
<body>
<h1>File Upload</h1>
<p>Logged in as <strong><?php echo htmlspecialchars(current_user(), ENT_QUOTES); ?></strong></p>
<nav>
    <a href="index.php">Home</a> |
    <a href="logout.php">Logout</a>
</nav>
<hr>
<?php if ($message) echo '<p>' . $message . '</p>'; ?>
<form method="POST" enctype="multipart/form-data">
    <input type="file" name="file"><br><br>
    <input type="submit" value="Upload">
</form>
<h3>Uploaded Files</h3>
<ul>
<?php
$files = scandir('uploads');
foreach ($files as $file) {
    if ($file != '.' && $file != '..') {
        echo '<li><a href="uploads/' . $file . '">' . $file . '</a></li>';
    }
}
?>
</ul>
</body>
</html>