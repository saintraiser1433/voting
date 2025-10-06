<?php
include '../../connection.php';

if (isset($_POST['myids'])) {
    $id = $_POST['myids'];
    $sql = "DELETE FROM partylist where p_id='$id'";
    $conn->query($sql);
}
