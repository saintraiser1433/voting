<?php
include '../../connection.php';
if (isset($_POST['myids']) && isset($_POST['status'])) {
    $id = (int) $_POST['myids'];
    $status = (int) $_POST['status'];
    // Shared voters table: toggle is_verified for this department voter
    $conn->query("UPDATE voters SET is_verified = $status WHERE v_id = $id");
}
