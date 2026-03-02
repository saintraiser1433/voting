<?php
/**
 * Switch admin between General Voting and Department Voting mode.
 * Separates responsibility: general = institution-wide, department = per-department.
 */
include '../connection.php';

if (!isset($_SESSION['at'])) {
    header('Location: logout.php');
    exit;
}

$mode = isset($_GET['mode']) ? $_GET['mode'] : '';
if ($mode === 'department') {
    $_SESSION['admin_mode'] = 'department';
} else {
    $_SESSION['admin_mode'] = 'general';
}

$redirect = isset($_SERVER['HTTP_REFERER']) && strpos($_SERVER['HTTP_REFERER'], '/admin/') !== false
    ? $_SERVER['HTTP_REFERER']
    : 'dashboard.php';
header('Location: ' . $redirect);
exit;
