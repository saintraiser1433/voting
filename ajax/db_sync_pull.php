<?php
/**
 * Voter (localhost only): pull DB from remote if admin enabled allow_voter_db_pull.
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../connection.php';
require_once __DIR__ . '/../includes/db_sync_lib.php';

if (!isset($_SESSION['v_id'])) {
    echo json_encode(['ok' => false, 'message' => 'Please log in.']);
    exit;
}

if (!db_sync_is_localhost()) {
    echo json_encode(['ok' => false, 'message' => 'Database pull is only allowed from localhost / LAN dev.']);
    exit;
}

if (db_sync_get_setting($conn, 'allow_voter_db_pull', '0') !== '1') {
    echo json_encode(['ok' => false, 'message' => 'Voter database pull is disabled. Ask an admin to enable it in Admin → Database sync.']);
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
if ($key === '') {
    echo json_encode(['ok' => false, 'message' => 'Sync API key is required.']);
    exit;
}

$result = db_sync_pull_from_remote($conn, $remote, $key);
echo json_encode($result);
exit;
