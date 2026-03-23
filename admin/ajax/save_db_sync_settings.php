<?php
require_once __DIR__ . '/../../connection.php';
require_once __DIR__ . '/../../includes/db_sync_lib.php';

if (!isset($_SESSION['at'])) {
    header('Location: ../logout.php');
    exit;
}

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../db_sync.php');
    exit;
}

db_sync_ensure_app_settings($conn);

function db_sync_save_kv(mysqli $conn, string $key, string $value): void
{
    $k = $conn->real_escape_string($key);
    $v = $conn->real_escape_string($value);
    $conn->query("INSERT INTO app_settings (setting_key, setting_value) VALUES ('$k', '$v')
        ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");
}

$remote = isset($_POST['db_sync_remote_url']) ? trim((string)$_POST['db_sync_remote_url']) : '';
$remote = rtrim($remote);
$key = isset($_POST['db_sync_api_key']) ? trim((string)$_POST['db_sync_api_key']) : '';
$allow = isset($_POST['allow_voter_db_pull']) ? '1' : '0';

db_sync_save_kv($conn, 'db_sync_remote_url', $remote);
if ($key !== '') {
    db_sync_save_kv($conn, 'db_sync_api_key', $key);
}
db_sync_save_kv($conn, 'allow_voter_db_pull', $allow);

$_SESSION['response'] = 'Database sync settings saved. Set the same API key on the remote (ngrok) server.';
$_SESSION['type'] = 'success';

header('Location: ../db_sync.php');
exit;
