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

// Optional: update election date range as well (only if provided)
$date_start_raw = isset($_POST['date_start']) ? trim($_POST['date_start']) : '';
$date_end_raw = isset($_POST['date_end']) ? trim($_POST['date_end']) : '';

$updateDateStart = $date_start_raw !== '';
$updateDateEnd = $date_end_raw !== '';

$date_start_sql = '';
$date_end_sql = '';

if ($updateDateStart) {
    $date_start = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $date_start_raw)));
    $date_start_sql = "'" . $conn->real_escape_string($date_start) . "'";
}

if ($updateDateEnd) {
    $date_end = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $date_end_raw)));
    $date_end_sql = "'" . $conn->real_escape_string($date_end) . "'";
}

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
if ($row_id !== null) {
    $setParts = ["is_finished = 0"];
    if ($updateDateStart) $setParts[] = "date_start=$date_start_sql";
    if ($updateDateEnd) $setParts[] = "date_end=$date_end_sql";

    $sqlUpdate = "UPDATE election_title SET " . implode(',', $setParts) . " WHERE id = '$row_id'";
    $ok = $conn->query($sqlUpdate);

    if ($ok) {
        $updatedDates = ($updateDateStart || $updateDateEnd);
        echo json_encode([
            'success' => true,
            'message' => $updatedDates ? 'Election reopened and dates updated. Voting is now open.' : 'Election reopened. Voting is now open.',
        ]);
        exit;
    }

    // If date columns don't exist yet, fall back to only updating is_finished
    if ((strpos($conn->error ?? '', 'Unknown column') !== false) && ($updateDateStart || $updateDateEnd)) {
        if ($conn->query("UPDATE election_title SET is_finished = 0 WHERE id = '$row_id'")) {
            echo json_encode(['success' => true, 'message' => 'Election reopened. Voting is now open. (Date columns not found in schema)']);
            exit;
        }
    }
}

echo json_encode(['success' => false, 'message' => $row_id === null ? 'No election found for this mode.' : ($conn->error ?? 'Update failed.')]);
