<?php
/**
 * Sets the current election back to open (is_finished = 0) for the current acad + admin mode.
 */

include '../../connection.php';

header('Content-Type: application/json');

if (!isset($_SESSION['at'])) {
    echo json_encode(['success' => false, 'message' => 'Not authorized']);
    exit;
}

$acad = (int) $_SESSION['acad'];
$admin_mode = isset($_SESSION['admin_mode']) && $_SESSION['admin_mode'] === 'department' ? 'department' : 'general';
$type_esc = $conn->real_escape_string($admin_mode);

// Update only the row for this mode (by id), so we don't reopen the other mode's election
$row_id = null;
$sel = "SELECT id FROM election_title WHERE acad_id = '$acad' AND (election_type = '$type_esc' OR (election_type IS NULL AND '$type_esc' = 'general')) ORDER BY id DESC LIMIT 1";
$res = $conn->query($sel);
if (!$res && strpos($conn->error ?? '', 'Unknown column') !== false) {
    $sel = "SELECT id FROM election_title WHERE acad_id = '$acad' ORDER BY id DESC LIMIT 1";
    $res = $conn->query($sel);
}
if ($res && $res->num_rows > 0) {
    $row = $res->fetch_assoc();
    $row_id = (int) $row['id'];
}
if ($row_id !== null && $conn->query("UPDATE election_title SET is_finished = 0 WHERE id = '$row_id'")) {
    echo json_encode(['success' => true, 'message' => 'Election reopened. Voting is now open.']);
} else {
    echo json_encode(['success' => false, 'message' => $row_id === null ? 'No election found for this mode.' : ($conn->error ?? 'Update failed.')]);
}
