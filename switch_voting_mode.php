<?php
/**
 * Switch voter between General and Department voting mode, then redirect to the right home.
 * Stops "General Voting" link from just refreshing (home.php was redirecting department users back).
 */
session_start();
include 'connection.php';

$mode = isset($_GET['mode']) ? trim($_GET['mode']) : '';

if ($mode === 'general') {
    $_SESSION['voting_mode'] = 'general';
    header('Location: home.php');
    exit;
}

if ($mode === 'department') {
    $_SESSION['voting_mode'] = 'department';
    header('Location: department_home.php');
    exit;
}

// Invalid or missing mode: go to login
header('Location: index.php');
exit;
