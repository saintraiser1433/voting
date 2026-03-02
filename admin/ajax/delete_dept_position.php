<?php
include '../../connection.php';
if (isset($_POST['myids'])) {
    $id = (int) $_POST['myids'];
    $chk = $conn->query("SELECT 1 FROM dept_candidate WHERE pos_id='$id' LIMIT 1");
    if ($chk && $chk->num_rows === 0) {
        $conn->query("DELETE FROM dept_position WHERE dp_id='$id'");
    }
}
