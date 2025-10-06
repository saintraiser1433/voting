<?php
include '../../connection.php';

$date = date('Y-m-d');
$acad = $_SESSION['acad'];
$sqls = "SELECT * FROM election_title where acad_id='$acad' and is_finished = 0";
$rs = $conn->query($sqls);
if ($rs->num_rows > 0) {
    $row = $rs->fetch_assoc();
    $dateend = $row['date_end'];
    if ($dateend < $date) {
        $sql = "UPDATE election_title set is_finished = 1 where acad_id='$acad'";
        $conn->query($sql);
    }

}
