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
    $target = 'dept_voters.php';
} else {
    $_SESSION['admin_mode'] = 'general';
    $target = 'voters.php';
}

// After switching mode, direct to the appropriate voters page.
header('Location: ' . $target);
exit;
