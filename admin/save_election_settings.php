<?php
/**
 * Handles Election Settings form submit. Redirects back so the success/error message is shown.
 */

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
$reopen_flow = isset($_POST['reopen_flow']) && $_POST['reopen_flow'] === '1';
$title = trim($_POST['titles'] ?? '');
$date_start = trim($_POST['date_start'] ?? '');
$date_end = trim($_POST['date_end'] ?? '');
if ($date_start !== '') {
    $date_start = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $date_start)));
} else {
    $date_start = null;
}
if ($date_end !== '') {
    $date_end = date('Y-m-d H:i:s', strtotime(str_replace('T', ' ', $date_end)));
} else {
    $date_end = null;
}

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
$reopen_skipped_schema = false;

$date_start_sql = $date_start !== null ? "'" . $conn->real_escape_string($date_start) . "'" : 'NULL';
$date_end_sql = $date_end !== null ? "'" . $conn->real_escape_string($date_end) . "'" : 'NULL';

// Reopen voting (is_finished=0) when saving from "Reopen election" flow and end date is in the future
$do_reopen = false;
if ($reopen_flow && $idhidden !== '') {
    if ($date_end !== null && strtotime($date_end) > time()) {
        if ($date_start === null || strtotime($date_start) <= strtotime($date_end)) {
            $do_reopen = true;
        }
    }
}

if ($idhidden === '') {
    // INSERT: new election starts as open (is_finished=0). Try with election_type + is_finished first.
    $sql = "INSERT INTO election_title (title, acad_id, election_type, is_finished, date_start, date_end) VALUES ('$title_esc', '$acad', '$election_type_esc', 0, $date_start_sql, $date_end_sql)";
    if ($conn->query($sql)) {
        $ok = true;
    } else {
        $err = $conn->error;
        if (strpos($err, 'Unknown column') !== false) {
            $sql2 = "INSERT INTO election_title (title, acad_id, election_type, is_finished) VALUES ('$title_esc', '$acad', '$election_type_esc', 0)";
            if ($conn->query($sql2)) {
                $ok = true;
            }
            if (!$ok) {
                $sql2b = "INSERT INTO election_title (title, acad_id, is_finished) VALUES ('$title_esc', '$acad', 0)";
                if ($conn->query($sql2b)) {
                    $ok = true;
                }
            }
            if (!$ok) {
                $sql3 = "INSERT INTO election_title (title, acad_id) VALUES ('$title_esc', '$acad')";
                if ($conn->query($sql3)) {
                    $ok = true;
                } else {
                    $error_msg = $conn->error;
                }
            }
        } else {
            $error_msg = $err;
        }
    }
} else {
    $id = (int) $idhidden;
    $reopen_sql = $do_reopen ? ', is_finished=0' : '';
    $sql = "UPDATE election_title SET title='$title_esc', date_start=$date_start_sql, date_end=$date_end_sql$reopen_sql WHERE id='$id' AND acad_id='$acad'";
    if ($conn->query($sql)) {
        $ok = true;
    } else {
        if (strpos($conn->error ?? '', 'Unknown column') !== false && $do_reopen) {
            $sql2 = "UPDATE election_title SET title='$title_esc', date_start=$date_start_sql, date_end=$date_end_sql WHERE id='$id' AND acad_id='$acad'";
            if ($conn->query($sql2)) {
                $ok = true;
                $reopen_skipped_schema = true;
                $do_reopen = false;
            } else {
                $error_msg = $conn->error;
            }
        } elseif (strpos($conn->error ?? '', 'Unknown column') !== false) {
            if ($conn->query("UPDATE election_title SET title='$title_esc' WHERE id='$id' AND acad_id='$acad'")) {
                $ok = true;
                $do_reopen = false;
            } else {
                $error_msg = $conn->error;
            }
        } else {
            $error_msg = $conn->error;
        }
    }
}

if ($ok) {
    if ($idhidden === '') {
        $_SESSION['response'] = 'Election settings saved successfully.';
    } else {
        $_SESSION['response'] = 'Election settings updated.';
        if ($reopen_flow) {
            if ($do_reopen) {
                $_SESSION['response'] .= ' Voting period is now open.';
            } elseif ($reopen_skipped_schema) {
                $_SESSION['response'] .= ' Dates saved; voting could not be reopened automatically (is_finished column missing).';
            } elseif ($date_end === null || strtotime($date_end) <= time()) {
                $_SESSION['response'] .= ' Voting was not reopened — set a future end date and save again.';
            } elseif ($date_start !== null && $date_end !== null && strtotime($date_start) > strtotime($date_end)) {
                $_SESSION['response'] .= ' Voting was not reopened — start date cannot be after end date.';
            }
        }
    }
    $_SESSION['type'] = 'success';
} else {
    $_SESSION['response'] = $error_msg ? 'Error: ' . $error_msg : 'An error has occurred.';
    $_SESSION['type'] = 'warning';
}

header('Location: ' . $return);
exit;
