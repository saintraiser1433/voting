<?php
include '../../connection.php';

if (isset($_POST['myids'])) {
    $id = $_POST['myids'];
    $sql = "UPDATE election_title set is_finished = 1 where acad_id='$id'";
    $conn->query($sql);
}
