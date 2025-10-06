<?php
include '../../connection.php';

if (isset($_POST['myids'])) {
    $id = $_POST['myids'];
    $sql = "DELETE FROM acad_tbl where acad_id='$id'";
    $conn->query($sql);
}
