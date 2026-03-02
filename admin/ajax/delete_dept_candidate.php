<?php
include '../../connection.php';
if (isset($_POST['myids'])) {
    $id = (int) $_POST['myids'];
    $conn->query("DELETE FROM department_vote WHERE candidate_id='$id'");
    $conn->query("DELETE FROM dept_candidate WHERE dc_id='$id'");
}
