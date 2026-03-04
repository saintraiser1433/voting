<?php
include '../../connection.php';

if (isset($_POST['myids'])) {
    $acad_id = (int) $_POST['myids'];
    $admin_mode = isset($_SESSION['admin_mode']) && $_SESSION['admin_mode'] === 'department' ? 'department' : 'general';
    $type_esc = $conn->real_escape_string($admin_mode);

    // Get the single row id for this acad + mode so we only close that election (not the other mode)
    $row_id = null;
    $sel = "SELECT id FROM election_title WHERE acad_id = '$acad_id' AND (election_type = '$type_esc' OR (election_type IS NULL AND '$type_esc' = 'general')) ORDER BY id DESC LIMIT 1";
    $res = $conn->query($sel);
    if (!$res && strpos($conn->error ?? '', 'Unknown column') !== false) {
        $sel = "SELECT id FROM election_title WHERE acad_id = '$acad_id' ORDER BY id DESC LIMIT 1";
        $res = $conn->query($sel);
    }
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $row_id = (int) $row['id'];
    }
    if ($row_id !== null) {
        $conn->query("UPDATE election_title SET is_finished = 1 WHERE id = '$row_id'");
    }
}
