<?php
include '../connection.php';

if (isset($_POST['myids'])) {
    $my = $_POST['myids'];

    $sql = "SELECT * FROM voters where stud_id='$my'";
    $res = $conn->query($sql);
    $row = $res->fetch_assoc();
    if ($res->num_rows > 0) {
        echo $row['password'];
    } else {
        echo "";
    }
}
