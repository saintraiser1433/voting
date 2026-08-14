<?php

include '../connection.php';

if (isset($_POST['myids'])) {
    $my = $_POST['myids'];
    $sql = "SELECT * FROM partylist where p_id='$my'";
    $rs = $conn->query($sql);
    if ($rs && $rs->num_rows > 0) {
        $row = $rs->fetch_assoc();
        echo $row['platform'];
    } else {
        echo "NO PLATFORM";
    }
}
