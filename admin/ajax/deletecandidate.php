<?php
include '../../connection.php';

if (isset($_POST['myids'])) {
    $id = $_POST['myids'];
    $sql = "DELETE FROM candidate where c_id='$id'";
    $conn->query($sql);
}
