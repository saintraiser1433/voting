<?php
include '../../connection.php';
if (isset($_POST['myids'])) {
    $my = $conn->real_escape_string($_POST['myids']);
    $acad = isset($_SESSION['acad']) ? (int) $_SESSION['acad'] : 0;
    $stat = 0;
    $sql = "SELECT 1 FROM dept_voters WHERE stud_id='$my' AND acad_id='$acad'";
    $res = $conn->query($sql);
    if ($res && $res->num_rows > 0) $stat = 1;
    echo json_encode(array('stat' => $stat));
}
