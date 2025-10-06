<?php
include '../../connection.php';

if (isset($_POST['myids'])) {
    $id = $_POST['myids'];
    $sql = "DELETE FROM position where pos_id='$id'";
    $conn->query($sql);
}
