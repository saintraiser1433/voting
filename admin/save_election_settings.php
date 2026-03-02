<?php
/**
 * Handles Election Settings form submit. Redirects back so the success/error message is shown.
 */
session_start();
include '../connection.php';

if (!isset($_SESSION['at'])) {
    header('Location: logout.php');
    exit;
}

$acad = (int) $_SESSION['acad'];
$admin_mode = isset($_SESSION['admin_mode']) && $_SESSION['admin_mode'] === 'department' ? 'department' : 'general';

$return = isset($_GET['return']) ? $_GET['return'] : 'dashboard.php';
$return = basename($return);
if (!preg_match('/^[a-z0-9_\-\.]+\.php$/i', $return)) {
    $return = 'dashboard.php';
}

if (!isset($_POST['submitqwe'])) {
    header('Location: ' . $return);
    exit;
}

$idhidden = trim($_POST['idhidden'] ?? '');
$title = trim($_POST['titles'] ?? '');

if ($title === '') {
    $_SESSION['response'] = 'Please enter an election title.';
    $_SESSION['type'] = 'warning';
    header('Location: ' . $return);
    exit;
}

$title_esc = $conn->real_escape_string($title);
$election_type_esc = $conn->real_escape_string($admin_mode);

$ok = false;
$error_msg = '';

if ($idhidden === '') {
    // INSERT: try with election_type first (new schema), then without (old schema)
    $sql = "INSERT INTO election_title (title, acad_id, election_type) VALUES ('$title_esc', '$acad', '$election_type_esc')";
    if ($conn->query($sql)) {
        $ok = true;
    } else {
        $err = $conn->error;
        if (strpos($err, 'Unknown column') !== false) {
            $sql2 = "INSERT INTO election_title (title, acad_id) VALUES ('$title_esc', '$acad')";
            if ($conn->query($sql2)) {
                $ok = true;
            } else {
                $error_msg = $conn->error;
            }
        } else {
            $error_msg = $err;
        }
    }
} else {
    $id = (int) $idhidden;
    $sql = "UPDATE election_title SET title='$title_esc' WHERE id='$id' AND acad_id='$acad'";
    if ($conn->query($sql)) {
        $ok = true;
    } else {
        $error_msg = $conn->error;
    }
}

if ($ok) {
    $_SESSION['response'] = $idhidden === '' ? 'Election settings saved successfully.' : 'Election settings updated.';
    $_SESSION['type'] = 'success';
} else {
    $_SESSION['response'] = $error_msg ? 'Error: ' . $error_msg : 'An error has occurred.';
    $_SESSION['type'] = 'warning';
}

header('Location: ' . $return);
exit;
