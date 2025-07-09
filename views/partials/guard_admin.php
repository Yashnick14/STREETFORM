<?php
// views/partials/guard_admin.php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (empty($_SESSION['admin_logged_in']) || $_SESSION['admin_logged_in'] !== true) {
    // not an authenticated admin? bounce them back to the admin‐login form
    header('Location: index.php?action=adminlogin');
    exit;
}
