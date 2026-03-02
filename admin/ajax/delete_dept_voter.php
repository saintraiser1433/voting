<?php
include '../../connection.php';
if (isset($_POST['myids'])) {
    $id = (int) $_POST['myids'];
    $conn->query("DELETE FROM department_vote WHERE voter_id='$id'");
    $conn->query("DELETE FROM dept_voters WHERE dv_id='$id'");
}
