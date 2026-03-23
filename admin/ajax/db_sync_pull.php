<?php
/**
 * Admin: pull database from remote (ngrok) into local MySQL.
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../../connection.php';
require_once __DIR__ . '/../../includes/db_sync_lib.php';

if (!isset($_SESSION['at'])) {
    echo json_encode(['ok' => false, 'message' => 'Not authorized']);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok' => false, 'message' => 'POST required']);
    exit;
}

$remote = isset($_POST['remote_url']) ? trim((string)$_POST['remote_url']) : '';
$key = isset($_POST['sync_key']) ? trim((string)$_POST['sync_key']) : '';

if ($remote === '') {
    $remote = db_sync_get_setting($conn, 'ngrok_sync_url', '');
}
if ($remote === '') {
    $remote = db_sync_get_setting($conn, 'db_sync_remote_url', '');
}

if ($key === '') {
    $key = db_sync_get_setting($conn, 'db_sync_api_key', '');
}

$result = db_sync_pull_from_remote($conn, $remote, $key);
echo json_encode($result);
exit;
