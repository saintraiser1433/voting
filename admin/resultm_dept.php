<?php
require_once 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;

$document = new Dompdf();
include '../connection.php';

$acad     = isset($_GET['acad']) ? (int) $_GET['acad'] : 0;
$dept_id  = isset($_GET['dept_id']) ? (int) $_GET['dept_id'] : 0;

// Get academic year description
$sqlps = "SELECT * FROM acad_tbl WHERE acad_id = $acad";
$rsxx  = $conn->query($sqlps);
$rowqq = $rsxx && $rsxx->num_rows > 0 ? $rsxx->fetch_assoc() : ['description' => ''];

// Get department name
$deptName = '';
if ($dept_id > 0) {
    $dres = $conn->query("SELECT department_name FROM departments WHERE department_id = '$dept_id'");
    if ($dres && $dres->num_rows > 0) {
        $drow     = $dres->fetch_assoc();
        $deptName = $drow['department_name'];
    }
}

$output = "
    <html>
    <head>
        <style>
            body { font-family: Arial, sans-serif; font-size: 12px; }
            h4, h5 { margin: 4px 0; }
            .header { margin-top:-100px; text-align:center; line-height:1.4; }
            .section-title { text-transform:uppercase; font-weight:bold; margin-top:15px; margin-bottom:5px; }
            table { border-collapse:collapse; font-size:12px; margin-bottom:5px; width:100%; }
            td { padding:4px; }
        </style>
    </head>
    <body>
        <img src='../libraries/img/glanlogo.png' width='100px' height='100px'>
        <div class='header'>
            <h4 style='margin:0;'>GLAN INSTITUTE OF TECHNOLOGY</h4>
            <p style='margin:0; font-size:12px;'>Sarangani, Philippines</p>
            <h5 style='margin-top:10px;'>SCHOOL YEAR: " . htmlspecialchars($rowqq['description']) . "</h5>
            <h5 style='margin-top:4px;'>DEPARTMENT: " . htmlspecialchars($deptName) . "</h5>
            <h5 style='margin-top:4px;'>OFFICIAL TALLY FOR DEPARTMENT ELECTION " . htmlspecialchars($rowqq['description']) . "</h5>
        </div>
";

$date   = date('Y-m-d');
$output .= "<p style='font-weight:bold'>Date Printed :  $date </p>";

// Tally per department position
$sqlx = "SELECT * FROM dept_position WHERE acad_id='$acad' ORDER BY priority ASC";
$rsx  = $conn->query($sqlx);

if ($rsx && $rsx->num_rows > 0) {
    foreach ($rsx as $rows) {
        $output .= "<h4 class='section-title'>" . htmlspecialchars($rows['description']) . "</h4>";
        $dp_id   = (int) $rows['dp_id'];

        $sqlb = "SELECT dc.dc_id,
                        CONCAT(UPPER(v.lname), ', ', UPPER(v.fname)) AS fname
                 FROM dept_candidate dc
                 INNER JOIN voters v ON dc.stud_id = v.stud_id AND dc.acad_id = v.acad_id
                 WHERE dc.acad_id='$acad' AND dc.department_id='$dept_id' AND dc.pos_id = $dp_id";
        $rxc  = $conn->query($sqlb);

        if ($rxc && $rxc->num_rows > 0) {
            $output .= "<table><tbody>";
            foreach ($rxc as $rowt) {
                $dc_id = (int) $rowt['dc_id'];
                $sqltt = "SELECT COUNT(*) AS votes
                          FROM department_vote
                          WHERE acad_id='$acad' AND department_id='$dept_id' AND candidate_id = $dc_id";
                $rsst  = $conn->query($sqltt);
                $votes = ($rsst && $rowVotes = $rsst->fetch_assoc()) ? (int) $rowVotes['votes'] : 0;

                $output .= "<tr>
                                <td style='padding-left:15px;'>• " . htmlspecialchars($rowt['fname']) . "</td>
                                <td width='60' align='right' style='font-weight:bold;'>" . $votes . "</td>
                            </tr>";
            }
            $output .= "</tbody></table>";
        } else {
            $output .= "<p style='margin-left:15px;'>No candidates for this position in this department.</p>";
        }
    }
} else {
    $output .= "<p>No department positions defined.</p>";
}

// Winners section (top candidates per position in this department)
$sqlt = "SELECT
    CONCAT(UPPER(v.lname), ', ', UPPER(v.fname)) AS fname,
    UPPER(dp.description) AS description,
    vt.totalvote,
    dp.max_vote,
    dc.dc_id
FROM dept_candidate dc
INNER JOIN voters v ON dc.stud_id = v.stud_id AND dc.acad_id = v.acad_id
INNER JOIN dept_position dp ON dc.pos_id = dp.dp_id AND dc.acad_id = dp.acad_id
LEFT JOIN (
    SELECT candidate_id, COUNT(DISTINCT voter_id) AS totalvote
    FROM department_vote
    WHERE acad_id='$acad' AND department_id='$dept_id'
    GROUP BY candidate_id
) vt ON vt.candidate_id = dc.dc_id
WHERE dc.acad_id = '$acad' AND dc.department_id = '$dept_id'
  AND (
        SELECT COUNT(*)
        FROM dept_candidate dc2
        LEFT JOIN (
            SELECT candidate_id, COUNT(DISTINCT voter_id) AS totalvote
            FROM department_vote
            WHERE acad_id='$acad' AND department_id='$dept_id'
            GROUP BY candidate_id
        ) vt2 ON vt2.candidate_id = dc2.dc_id
        WHERE dc2.pos_id = dc.pos_id AND dc2.department_id = dc.department_id AND vt2.totalvote > vt.totalvote
      ) < dp.max_vote
GROUP BY dc.dc_id
ORDER BY dp.priority ASC, vt.totalvote DESC";

$rs = $conn->query($sqlt);

if ($rs && $rs->num_rows > 0) {
    $output .= "
        <hr style='margin-top:20px; margin-bottom:10px;'>
        <center><h4 style='font-family:Arial; margin:0;'>OFFICIAL RESULT FOR DEPARTMENT ELECTION " . htmlspecialchars($rowqq['description']) . " </h4></center>
    ";
    foreach ($rs as $row) {
        $votes = isset($row['totalvote']) ? (int) $row['totalvote'] : 0;
        $output .= "
            <ul class='candidate-list'>
                <li class='text-capitalize'><span style='font-weight:bold'>" . htmlspecialchars($row['description']) . "</span> - " . htmlspecialchars($row['fname']) . " (VOTES: " . $votes . ")</li>
            </ul>
        ";
    }
} else {
    $output .= "<p>No winning candidates found for this department.</p>";
}

$output .= "</body></html>";

$document->loadHtml($output);
$document->setPaper('A4', 'portrait');
$document->render();
$document->stream("department_results", array("Attachment" => 0));
?>
