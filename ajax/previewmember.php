<?php

include '../connection.php';

if (isset($_POST['myids'])) {
    $my = $_POST['myids'];
    $sql = "SELECT * FROM candidate LEFT JOIN voters ON candidate.stud_id=voters.stud_id LEFT JOIN position ON candidate.pos_id=position.pos_id where candidate.p_id='$my' order by position.priority ASC";
    $rs = $conn->query($sql);

    if ($rs->num_rows > 0) {
        while ($row = $rs->fetch_assoc()) {
            echo "
        <div class='row votelist'>
        <span class='col-sm-4 text-uppercase'><span class='pull-right'><b>" . $row['description'] . " :</b></span></span> 
        <span class='col-sm-8 text-uppercase'>" . $row['lname'] . " " . $row['fname'] . "</span>
        </div>
        
        ";
        }
    } else {
        echo "NO RESULT FOUND";
    }
}
