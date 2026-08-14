<?php
include '../../connection.php';

$stat = null;

if (isset($_POST['id'])) {
    $id = $_POST['id'];
    $sql = "SELECT * FROM acad_tbl where description ='$id'";
    $rs = $conn->query($sql);
    if ($rs && $rs->num_rows > 0) {
        $stat = 1;
    } else {
        $stat = 0;
    }
}






$data = array(
    'stat'   => $stat,

);
echo json_encode($data);
