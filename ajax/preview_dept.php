<?php
header('Content-Type: application/json');
include '../connection.php';
include '../admin/includes/slugify.php';

$acad = isset($_SESSION['acad']) ? (int) $_SESSION['acad'] : 0;
$output = "";
$sql = "SELECT * FROM dept_position WHERE acad_id='$acad' ORDER BY priority ASC";
$query = $conn->query($sql);
if ($query) {
    while ($row = $query->fetch_assoc()) {
        $position = slugify($row['description']);
        if (isset($_POST[$position])) {
            if ($row['max_vote'] > 1 && is_array($_POST[$position])) {
                if (count($_POST[$position]) > $row['max_vote']) {
                    $_SESSION['response'] = 'You can only choose ' . $row['max_vote'] . ' candidates for ' . $row['description'];
                    $_SESSION['type'] = "warning";
                } else {
                    foreach ($_POST[$position] as $values) {
                        $values = (int) $values;
                        $sql2 = "SELECT v.lname, v.fname, v.mname FROM dept_candidate dc INNER JOIN voters v ON dc.stud_id = v.stud_id AND dc.acad_id = v.acad_id WHERE dc.dc_id = '$values'";
                        $cmquery = $conn->query($sql2);
                        if ($cmquery && $cmquery->num_rows > 0) {
                            $cmrow = $cmquery->fetch_assoc();
                            $m = middle_initial($cmrow['mname']);
                            $output .= "<div class='row votelist'><span class='col-sm-4 col-4 text-uppercase'><span class='pull-right'><b>" . htmlspecialchars($row['description']) . " :</b></span></span><span class='col-sm-8 col-8 text-uppercase'>" . htmlspecialchars($cmrow['lname'] . ", " . $cmrow['fname'] . " " . $m) . "</span></div>";
                        }
                    }
                }
            } else {
                $candidate = is_array($_POST[$position]) ? (int) ($_POST[$position][0] ?? 0) : (int) $_POST[$position];
                $sql2 = "SELECT v.lname, v.fname, v.mname FROM dept_candidate dc INNER JOIN voters v ON dc.stud_id = v.stud_id AND dc.acad_id = v.acad_id WHERE dc.dc_id = '$candidate'";
                $csquery = $conn->query($sql2);
                if ($csquery && $csquery->num_rows > 0) {
                    $csrow = $csquery->fetch_assoc();
                    $m = middle_initial($csrow['mname']);
                    $output .= "<div class='row votelist'><span class='col-sm-4 col-4 text-uppercase'><span class='pull-right'><b>" . htmlspecialchars($row['description']) . " :</b></span></span><span class='col-sm-8 col-8 text-uppercase'>" . htmlspecialchars($csrow['lname'] . ", " . $csrow['fname'] . " " . $m) . "</span></div>";
                }
            }
        }
    }
}
echo json_encode(array('list' => $output));
