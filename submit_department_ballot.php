<?php
include 'connection.php';
include 'admin/includes/slugify.php';

$acad = $_SESSION['acad'];
$myid = isset($_SESSION['v_id']) ? (int) $_SESSION['v_id'] : 0;
$dept_id = isset($_SESSION['dept_id']) ? (int) $_SESSION['dept_id'] : 0;
$voting_mode = isset($_SESSION['voting_mode']) ? $_SESSION['voting_mode'] : '';

if ($voting_mode !== 'department' || !$myid || !$dept_id) {
    $_SESSION['response'] = 'Invalid session. Please log in again for department voting.';
    $_SESSION['type'] = 'error';
    header('Location: index.php');
    exit;
}

if (!isset($_POST['voters1'])) {
    $_SESSION['response'] = 'Select candidates to vote first';
    $_SESSION['type'] = 'error';
    header('Location: department_ballot.php');
    exit;
}

$chk = $conn->query("SELECT 1 FROM department_vote WHERE acad_id='$acad' AND voter_id='$myid'");
if ($chk && $chk->num_rows > 0) {
    $_SESSION['response'] = 'You have already voted in this department election.';
    $_SESSION['type'] = 'error';
    header('Location: department_home.php');
    exit;
}

$sql = "SELECT * FROM dept_position WHERE acad_id='$acad'";
$query = $conn->query($sql);
$sql_array = array();
$error = false;

while ($row = $query->fetch_assoc()) {
    $position = slugify($row['description']);
    if (!isset($_POST[$position])) continue;

    $max_vote = (int) $row['max_vote'];
    $vals = $_POST[$position];
    if (!is_array($vals)) $vals = array($vals);
    $vals = array_map('intval', array_filter($vals));

    if (count($vals) > $max_vote) {
        $_SESSION['response'] = 'You can only choose ' . $max_vote . ' candidates for ' . $row['description'];
        $_SESSION['type'] = 'error';
        header('Location: department_ballot.php');
        $error = true;
        break;
    }
    foreach ($vals as $dc_id) {
        if ($dc_id > 0) {
            $sql_array[] = "INSERT INTO department_vote (voter_id, candidate_id, acad_id, department_id) VALUES ('$myid', '$dc_id', '$acad', '$dept_id')";
        }
    }
}

if (!$error && !empty($sql_array)) {
    foreach ($sql_array as $sql_row) {
        $conn->query($sql_row);
    }
    if (isset($_SESSION['dept_post'])) unset($_SESSION['dept_post']);
    $_SESSION['response'] = 'Ballot submitted successfully.';
    $_SESSION['type'] = 'success';
    header('Location: department_home.php');
} elseif (!$error && empty($sql_array)) {
    $_SESSION['response'] = 'Please vote at least one candidate.';
    $_SESSION['type'] = 'error';
    header('Location: department_ballot.php');
}
