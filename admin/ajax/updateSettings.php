<?php
include '../../connection.php';

if (isset($_POST['myids'])) {
    $id = (int) $_POST['myids'];
    $admin_mode = isset($_SESSION['admin_mode']) && $_SESSION['admin_mode'] === 'department' ? 'department' : 'general';
    $type_esc = $conn->real_escape_string($admin_mode);
    // End only the current responsibility's election (General or Department)
    $sql = "UPDATE election_title SET is_finished = 1 WHERE acad_id = '$id' AND (election_type = '$type_esc' OR (election_type IS NULL AND '$type_esc' = 'general'))";
    $conn->query($sql);
}
