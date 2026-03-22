<?php
require_once __DIR__ . '/ajax_cors_headers.php';
require_once '../connection.php';

header('Content-Type: application/json');
echo json_encode(['ok' => true]);

