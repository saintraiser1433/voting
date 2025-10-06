<?php
include '../../connection.php';

if (isset($_POST['myids'])) {
    $my = $_POST['myids'];
    $stat = "";

    $sql = "SELECT * FROM voters where stud_id='$my'";
    $res = $conn->query($sql);
    $row = $res->fetch_assoc();
    if ($res->num_rows > 0) {
        $stat = 1;
    } else {
        $stat = 0;
    }
}


$data = array(
    'stat'   => $stat,



);
echo json_encode($data);
