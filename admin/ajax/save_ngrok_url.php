<?php
require_once '../../connection.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ../app_config.php');
    exit;
}

$url = isset($_POST['ngrok_sync_url']) ? trim($_POST['ngrok_sync_url']) : '';

// Basic normalization: remove trailing slash
$url = rtrim($url);

// Ensure app_settings table exists (fallback if migration wasn't run)
$res = $conn->query("SHOW TABLES LIKE 'app_settings'");
if (!$res || $res->num_rows === 0) {
    $conn->query("CREATE TABLE IF NOT EXISTS `app_settings` (
        `setting_key`   VARCHAR(100) NOT NULL PRIMARY KEY,
        `setting_value` TEXT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
}

$safe = $conn->real_escape_string($url);
$conn->query("INSERT INTO app_settings (setting_key, setting_value) VALUES ('ngrok_sync_url', '$safe')
              ON DUPLICATE KEY UPDATE setting_value = VALUES(setting_value)");

$_SESSION['response'] = 'Sync URL updated';
$_SESSION['type'] = 'success';

header('Location: ../app_config.php');
exit;

