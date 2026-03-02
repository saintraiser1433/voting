<?php
include '../../connection.php';

$date = date('Y-m-d');
$acad = (int) $_SESSION['acad'];
$sqls = "SELECT id, date_end FROM election_title WHERE acad_id = '$acad' AND is_finished = 0";
$rs = $conn->query($sqls);
if ($rs && $rs->num_rows > 0) {
    while ($row = $rs->fetch_assoc()) {
        $dateend = isset($row['date_end']) ? $row['date_end'] : null;
        if ($dateend && $dateend < $date) {
            $eid = (int) $row['id'];
            $conn->query("UPDATE election_title SET is_finished = 1 WHERE id = '$eid'");
        }
    }
}
