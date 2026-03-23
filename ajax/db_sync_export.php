<?php
/**
 * Remote endpoint: returns JSON snapshot of all syncable tables (excludes `admin`).
 * POST: sync_key — must match app_settings.db_sync_api_key on THIS server.
 */

header('Content-Type: application/json; charset=utf-8');

require_once __DIR__ . '/../connection.php';
require_once __DIR__ . '/../includes/db_sync_lib.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['ok' => false, 'message' => 'POST required']);
    exit;
}

$key = isset($_POST['sync_key']) ? trim((string)$_POST['sync_key']) : '';
$expected = db_sync_get_setting($conn, 'db_sync_api_key', '');

if ($expected === '') {
    echo json_encode([
        'ok' => false,
        'message' => 'Database sync is not configured on this server. Set a sync API key in Admin → Database sync (or App Config).',
    ]);
    exit;
}

if (!hash_equals($expected, $key)) {
    echo json_encode(['ok' => false, 'message' => 'Invalid sync key.']);
    exit;
}

try {
    $tables = db_sync_export_payload($conn);
    $flags = defined('JSON_INVALID_UTF8_SUBSTITUTE') ? JSON_UNESCAPED_UNICODE | JSON_INVALID_UTF8_SUBSTITUTE : JSON_UNESCAPED_UNICODE;
    $json = json_encode([
        'ok' => true,
        'message' => 'OK',
        'tables' => $tables,
        'exported_at' => gmdate('c'),
    ], $flags);
    if ($json === false) {
        echo json_encode(['ok' => false, 'message' => 'Could not encode database snapshot (too large or invalid UTF-8).']);
        exit;
    }
    echo $json;
} catch (Throwable $e) {
    echo json_encode(['ok' => false, 'message' => 'Export error: ' . $e->getMessage()]);
}
