<?php
include '../../connection.php';

if (isset($_POST['myids']) && isset($_POST['status'])) {
    $id = $_POST['myids'];
    $status = $_POST['status'];
    $sql = "UPDATE voters set is_verified = $status where v_id = $id";
    $conn->query($sql);
}
