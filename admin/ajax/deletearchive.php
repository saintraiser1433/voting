<?php
include '../../connection.php';

if (isset($_POST['myids'])) {
    $id = $_POST['myids'];
    $sql = "DELETE FROM archives where v_id='$id'";
    $conn->query($sql);
}
