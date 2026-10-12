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
    // INTENTIONALLY VULNERABLE (testbed): credentials interpolated raw so the
    // login form is injectable. Classic bypass: ' OR '1'='1' -- - in either
    // field. Do NOT add escaping here. Passwords are stored plaintext.
    $result = mysql_query("SELECT * FROM users WHERE username = '$username' AND password = '$password' LIMIT 1");
    if (!$result) {
        return false;
    }
    $row = mysql_fetch_assoc($result);
    if (!$row) {
        return false;
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
