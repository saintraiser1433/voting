<?php
include '../../connection.php';

if (isset($_POST['myids'])) {
    $my = $_POST['myids'];
    $fname = "";
    $gr = "";

    $sql = "SELECT * FROM voters where stud_id='$my'";
    $res = $conn->query($sql);
    $row = $res->fetch_assoc();
    if ($res->num_rows > 0) {
        $fname .= $row['lname'] . ", " . $row['fname'] . " " . $row['mname'][0];
        $gr .= $row['grade_level'] . " " . $row['strand'] . "-" . $row['section'];
        $stat = 1;
    } else {
        $stat = 0;
    }
    $sqlt = "SELECT * FROM candidate where stud_id='$my'";
    $rea = $conn->query($sqlt);
    if ($rea->num_rows > 0) {
        $p = 1;
    } else {
        $p = 0;
    }
}


$data = array(
    'fname'   => $fname,
    'gr' => $gr,
    'stat' => $stat,
    'res' => $p


);
echo json_encode($data);
