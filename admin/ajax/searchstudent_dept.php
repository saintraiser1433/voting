<?php
include '../../connection.php';
if (isset($_POST['myids'])) {
    $my = $conn->real_escape_string($_POST['myids']);
    $acad = isset($_SESSION['acad']) ? (int) $_SESSION['acad'] : 0;
    $fname = $gr = $department_name = '';
    $department_id = 0;
    $stat = 0;
    $sql = "SELECT v.*, d.department_name FROM voters v LEFT JOIN departments d ON v.department_id = d.department_id WHERE v.stud_id='$my' AND v.acad_id='$acad' AND v.department_id IS NOT NULL AND v.is_verified=1";
    $res = $conn->query($sql);
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        $m = isset($row['mname'][0]) ? $row['mname'][0] . '.' : '';
        $fname = $row['lname'] . ", " . $row['fname'] . " " . $m;
        $gr = $row['grade_level'] . " " . $row['strand'] . "-" . $row['section'];
        $department_id = (int) $row['department_id'];
        $department_name = $row['department_name'] ?? '';
        $stat = 1;
    }
    $p = 0;
    if ($stat === 1) {
        $chk = $conn->query("SELECT 1 FROM dept_candidate WHERE stud_id='$my' AND acad_id='$acad'");
        if ($chk && $chk->num_rows > 0) $p = 1;
    }
    echo json_encode(array('fname' => $fname, 'gr' => $gr, 'stat' => $stat, 'res' => $p, 'department_id' => $department_id, 'department_name' => $department_name));
}
