<?php
require_once __DIR__ . '/session.php';

function is_logged_in() {
    return isset($_SESSION['user_id']);
}

function current_user() {
    return isset($_SESSION['username']) ? $_SESSION['username'] : null;
}

function attempt_login($username, $password) {
    require_once dirname(__DIR__) . '/config/db.php';
    $username = mysql_real_escape_string($username);
    $result = mysql_query("SELECT * FROM users WHERE username = '$username' LIMIT 1");
    if (!$result) {
        return false;
    }
    $row = mysql_fetch_assoc($result);
    if (!$row) {
        return false;
    }

    $stored = $row['password'];
    $is_hash = password_verify($password, $stored);
    if (!$is_hash) {
        // Fall back to plaintext for legacy rows, then upgrade to a hash.
        if (!hash_equals($stored, $password)) {
            return false;
        }
        $hash = password_hash($password, PASSWORD_DEFAULT);
        mysql_query("UPDATE users SET password = '" . mysql_real_escape_string($hash) . "' WHERE id = " . (int) $row['id']);
    }

    $_SESSION['user_id'] = (int) $row['id'];
    $_SESSION['username'] = $row['username'];
    return true;
}

function logout() {
    unset($_SESSION['user_id'], $_SESSION['username']);
    if (session_status() === PHP_SESSION_ACTIVE) {
        session_destroy();
    }
}

function require_login() {
    if (!is_logged_in()) {
        header('Location: login.php');
        exit;
    }
}
?>
