<?php
include '../../connection.php';

$stat = null;

if (isset($_POST['myids'])) {
    $my = $_POST['myids'];

    $sql = "SELECT * FROM voters where stud_id='$my'";
    $res = $conn->query($sql);
    if ($res && $res->num_rows > 0) {
        $stat = 1;
    } else {
        $stat = 0;
    }
}


$data = array(
    'stat'   => $stat,



);
echo json_encode($data);
