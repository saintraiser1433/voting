<?php
include 'connection.php';
include 'admin/includes/slugify.php';

$acad = $_SESSION['acad'];

if (isset($_POST['voters1'])) {
    $myid = $_SESSION['v_id'];

    // Resolve voter's department
    $deptRes = $conn->query("
        SELECT d.department_id
        FROM voters v
        LEFT JOIN courses c ON v.strand = c.course_code AND c.acad_id = v.acad_id
        LEFT JOIN departments d ON c.department_id = d.department_id
        WHERE v.v_id = '$myid'
        LIMIT 1
    ");
    $deptRow = $deptRes ? $deptRes->fetch_assoc() : null;
    $department_id = $deptRow ? $deptRow['department_id'] : null;

    if (empty($department_id)) {
        $_SESSION['response'][] = 'Your course is not mapped to any department. Please contact the administrator.';
        $_SESSION['type'] = 'error';
        header('Location:department_ballot.php');
        exit;
    }

    if (count($_POST) == 1) {
        $_SESSION['response'][] = 'Please vote atleast one candidate';
        $_SESSION['type'] = 'error';
        header('Location:department_ballot.php');
    } else {
        // Prevent double-voting for department election
        $check = $conn->query("SELECT * FROM department_vote WHERE acad_id='$acad' AND voter_id='$myid' LIMIT 1");
        if ($check && $check->num_rows > 0) {
            $_SESSION['response'][] = 'You have already submitted your department ballot.';
            $_SESSION['type'] = 'error';
            header('Location:department_home.php');
            exit;
        }

        $_SESSION['dept_post'] = $_POST;
        $sql = "SELECT * FROM position where acad_id='$acad'";
        $query = $conn->query($sql);
        $sql_array = array();
        $error = false;

        while ($row = $query->fetch_assoc()) {
            $position = slugify($row['description']);
            $pos_id = $row['pos_id'];
            if (isset($_POST[$position])) {
                if ($row['max_vote'] > 1) {
                    if (count($_POST[$position]) > $row['max_vote']) {
                        $error = true;
                        $_SESSION['response'][] = 'You can only choose ' . $row['max_vote'] . ' candidates for ' . $row['description'];
                        $_SESSION['type'] = 'error';
                        header('Location:department_ballot.php');
                        break;
                    } else {
                        foreach ($_POST[$position] as $values) {
                            $sql_array[] = "INSERT INTO department_vote (voter_id, candidate_id, acad_id, department_id)
                                            VALUES ('$myid', '$values', '$acad', '$department_id')";
                        }
                    }
                } else {
                    $candidate = $_POST[$position];
                    $sql_array[] = "INSERT INTO department_vote (voter_id, candidate_id, acad_id, department_id)
                                    VALUES ('$myid', '$candidate', '$acad', '$department_id')";
                }
            }
        }

        if (!$error) {
            foreach ($sql_array as $sql_row) {
                $conn->query($sql_row);
            }

            unset($_SESSION['dept_post']);
            $_SESSION['response'] = 'Department ballot submitted';
            $_SESSION['type'] = 'success';
            header('Location:department_home.php');
        }
    }
} else {
    $_SESSION['response'][] = 'Select candidates to vote first';
    $_SESSION['type'] = 'error';
    header('Location:department_ballot.php');
}

