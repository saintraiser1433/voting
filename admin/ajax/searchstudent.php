<?php
include '../../connection.php';

$fname = '';
$gr = '';
$stat = 0;
$p = 0;

if (isset($_POST['myids'])) {
    $my = $_POST['myids'];

    $sql = "SELECT * FROM voters where stud_id='$my'";
    $res = $conn->query($sql);
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $fname .= $row['lname'] . ", " . $row['fname'] . " " . middle_initial($row['mname'], '');
        $gr .= $row['grade_level'] . " " . $row['strand'] . "-" . $row['section'];
        $stat = 1;
    } else {
        $stat = 0;
    }
    $sqlt = "SELECT * FROM candidate where stud_id='$my'";
    $rea = $conn->query($sqlt);
    if ($rea && $rea->num_rows > 0) {
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
