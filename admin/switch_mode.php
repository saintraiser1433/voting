<?php
include '../connection.php';

// Toggle or set admin mode
if (isset($_GET['mode']) && in_array($_GET['mode'], ['general', 'department'])) {
    $_SESSION['admin_mode'] = $_GET['mode'];
} else {
    // Simple toggle if no explicit mode given
    $_SESSION['admin_mode'] = (isset($_SESSION['admin_mode']) && $_SESSION['admin_mode'] === 'department')
        ? 'general'
        : 'department';
}

// Redirect back to previous page or dashboard
$redirect = isset($_SERVER['HTTP_REFERER']) ? $_SERVER['HTTP_REFERER'] : 'dashboard.php';
header("Location: $redirect");
exit;

