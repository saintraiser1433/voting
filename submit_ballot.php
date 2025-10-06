<?php
include 'connection.php';
include 'admin/includes/slugify.php';
$acad = $_SESSION['acad'];


if (isset($_POST['voters1'])) {
    $myid =  $_SESSION['v_id'];
    if (count($_POST) == 1) {
        $_SESSION['response'][] = 'Please vote atleast one candidate';
        $_SESSION['type'] = 'error';
        header('Location:ballot.php');
    } else {
        $_SESSION['post'] = $_POST;
        $sql = "SELECT * FROM position where acad_id='$acad'";
        $query = $conn->query($sql);
        $sql_array = array();
        while ($row = $query->fetch_assoc()) {
            $position = slugify($row['description']);
            $pos_id = $row['pos_id'];
            if (isset($_POST[$position])) {
                if ($row['max_vote'] > 1) {
                    if (count($_POST[$position]) > $row['max_vote']) {
                        $error = true;
                        $_SESSION['response'][] = 'You can only choose ' . $row['max_vote'] . ' candidates for ' . $row['description'];
                        $_SESSION['type'] = 'error';
                        header('Location:ballot.php');
                    } else {
                        foreach ($_POST[$position] as $key => $values) {
                            $sql_array[] = "INSERT INTO vote (voter_id, candidate_id, acad_id) VALUES ('" . $myid . "', '$values', '$acad')";
                        }
                    }
                } else {
                    $candidate = $_POST[$position];
                    $sql_array[] = "INSERT INTO vote (voter_id, candidate_id, acad_id) VALUES ('" . $myid . "', '$candidate', '$acad')";
                }
            }
        }

        if (!$error) {
            foreach ($sql_array as $sql_row) {
                $conn->query($sql_row);
            }

            unset($_SESSION['post']);
            $_SESSION['response'] = 'Ballot Submitted';
            $_SESSION['type'] = 'success';
            header('Location:home.php');
        }
    }
} else {
    $_SESSION['response'][] = 'Select candidates to vote first';
    $_SESSION['type'] = 'error';
    header('Location:ballot.php');
}
