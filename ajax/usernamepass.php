<?php
include '../connection.php';

if (isset($_POST['myids'])) {
    $my = $conn->real_escape_string($_POST['myids']);
    $mode = isset($_POST['mode']) && $_POST['mode'] === 'department' ? 'department' : 'general';

    if ($mode === 'department') {
        $sql = "SELECT password FROM dept_voters WHERE stud_id='$my'";
    } else {
        $sql = "SELECT password FROM voters WHERE stud_id='$my'";
    }
    $res = $conn->query($sql);
    if ($res && $res->num_rows > 0) {
        $row = $res->fetch_assoc();
        echo $row['password'] !== null && $row['password'] !== '' ? $row['password'] : '';
    } else {
        echo "";
    }
}
