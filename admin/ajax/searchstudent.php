<?php
include '../../connection.php';

if (isset($_POST['myids'])) {
    $my = $_POST['myids'];
    $fname = "";
    $gr = "";
    $strand = "";
    $deptName = "";

    $sql = "SELECT * FROM voters where stud_id='$my'";
    $res = $conn->query($sql);
    $row = $res->fetch_assoc();
    if ($res->num_rows > 0) {
        $fname .= $row['lname'] . ", " . $row['fname'] . " " . $row['mname'][0];
        $gr .= $row['grade_level'] . " " . $row['strand'] . "-" . $row['section'];
        $strand = $row['strand'];

        // Resolve department name from course/strand if possible
        $deptSql = "SELECT d.department_name 
                    FROM courses c 
                    LEFT JOIN departments d ON c.department_id = d.department_id 
                    WHERE c.course_code = '" . $conn->real_escape_string($strand) . "' 
                    LIMIT 1";
        $deptRes = $conn->query($deptSql);
        if ($deptRes && $deptRes->num_rows > 0) {
            $deptRow = $deptRes->fetch_assoc();
            $deptName = $deptRow['department_name'];
        }

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

    $data = array(
        'fname'   => $fname,
        'gr'      => $gr,
        'strand'  => $strand,
        'department' => $deptName,
        'stat'   => $stat,
        'res'    => $p
    );
    echo json_encode($data);
}
