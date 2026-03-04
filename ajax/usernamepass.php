<?php

include '../connection.php';

if (isset($_POST['myids'])) {
    $my = $conn->real_escape_string($_POST['myids']);
    $mode = isset($_POST['mode']) && $_POST['mode'] === 'department' ? 'department' : 'general';
    $acad = isset($_SESSION['acad']) ? (int) $_SESSION['acad'] : 0;

    if ($mode === 'department') {
        $sql = "SELECT password, department_id FROM voters WHERE stud_id='$my' AND acad_id='$acad'";
    } else {
        $sql = "SELECT password FROM voters WHERE stud_id='$my' AND acad_id='$acad'";
    }
    $res = $conn->query($sql);
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        if ($mode === 'department') {
            // If no department assigned, return special marker
            if (empty($row['department_id'])) {
                echo "NO_DEPARTMENT";
                exit;
            }
        }
        echo $row['password'] !== null && $row['password'] !== '' ? $row['password'] : '';
    } else {
        echo "";
    }
}
