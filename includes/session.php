<?php
if (session_status() === PHP_SESSION_NONE) {
    session_name('vulnapp_session');
    session_start();
}
?>
