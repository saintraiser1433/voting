<?php
include '../../connection.php';
if (isset($_POST['myids']) && isset($_POST['status'])) {
    $id = (int) $_POST['myids'];
    $status = (int) $_POST['status'];
    $conn->query("UPDATE dept_voters SET is_verified = $status WHERE dv_id = $id");
}
