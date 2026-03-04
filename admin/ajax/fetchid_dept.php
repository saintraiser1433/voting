<?php
include '../../connection.php';
if (isset($_POST['myids'])) {
    $my = $conn->real_escape_string($_POST['myids']);
    $acad = isset($_SESSION['acad']) ? (int) $_SESSION['acad'] : 0;
    $stat = 0;
    // Check in shared voters table for same acad and any department assignment
    $sql = "SELECT 1 FROM voters WHERE stud_id='$my' AND acad_id='$acad' AND department_id IS NOT NULL";
    $res = $conn->query($sql);
    if ($res && $res->num_rows > 0) $stat = 1;
    echo json_encode(array('stat' => $stat));
}
