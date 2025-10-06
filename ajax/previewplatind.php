<?php

include '../connection.php';

if (isset($_POST['myids'])) {
    $my = $_POST['myids'];
    $sql = "SELECT * FROM candidate where c_id='$my'";
    $rs = $conn->query($sql);
    $row = $rs->fetch_assoc();
    if ($rs->num_rows > 0) {
        echo $row['platform'];
    } else {
        echo "Not set";
    }
}
