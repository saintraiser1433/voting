<?php
require_once 'dompdf/autoload.inc.php';
use Dompdf\Dompdf;

$document = new Dompdf();
include '../connection.php';

$acad = $_GET['acad'];
$sqlps = "SELECT * FROM acad_tbl where acad_id = $acad";
$rsxx = $conn->query($sqlps);
$rowqq = $rsxx->fetch_assoc();
$output = "
    <html>
    <head>
        <link rel='stylesheet' type='text/css' href='libraries\bower_components\bootstrap\css\bootstraps.min.css'>
    </head>
    <body>
        <img src='../libraries/img/logo.png' width='100px;' height='100px;'>
        <div style='margin-top:-100px; text-align:center; font-family:arial; line-height:3px'>
            <h6>SOUTH EAST ASIAN INSTITUTE OF TECHNOLOGY INC.</h6>
            <h6>BRGY. CROSSING RUBBER , TUPI , SOUTH COTABATO</h6>
             <center><h3 style='font-family:Arial;'>SCHOOL YEAR: " . $rowqq['description'] . " </h3></center>
             <center><h3 style='font-family:Arial;'>OFFICAL TALLY FOR SEAIT ELECTION " . $rowqq['description'] . " </h3></center> 
             
        </div>

";
$date = date('Y-m-d');
$output .= "<p style='font-weight:bold'>Date Printed :  $date </p>";
$sqlx = "SELECT * FROM position where acad_id='$acad' ORDER BY priority ASC";

$rsx = $conn->query($sqlx);
if (!$rsx) {
    die("Query Error: " . $conn->error); // Log or display the SQL error
}

if ($rsx->num_rows > 0) {
    $i = 1;

    foreach ($rsx as $rows) {
        $output .= "<p style='text-transform:uppercase; font-weight:bold'>" . $rows['description'] . "</p>";
        $dt = $rows['pos_id'];
        $sqlb = "SELECT  CONCAT(
        UPPER(voters.lname),
        ', ',
        UPPER(voters.fname)
    ) AS fname,
    c_id
         FROM candidate LEFT JOIN voters ON candidate.stud_id=voters.stud_id LEFT JOIN partylist ON candidate.p_id=partylist.p_id WHERE candidate.acad_id='$acad' and candidate.pos_id = $dt";
        $rxc = $conn->query($sqlb);
        if ($rxc->num_rows > 0) {
            foreach ($rxc as $rowt) {

                $id = $rowt['c_id'];
                $sqltt = "SELECT * FROM vote WHERE acad_id='$acad' and candidate_id = $id";
                $rsst = $conn->query($sqltt);
                $output .= "
            <ul class='candidate-list'>
                <li class='text-capitalize'>" . htmlspecialchars($rowt['fname']) . " - <span style='font-weight:bold'>" . @$rsst->num_rows . "</span></li>
            </ul>
        ";
            }
        }

    }
} else {
    $output .= "<p>No results found.</p>";
}


$sqlt = "SELECT
    CONCAT(
        UPPER(v.lname),
        ', ',
        UPPER(v.fname)
    ) AS fname,
    UPPER(pos.description) as description,
    vt.totalvote,
    pos.max_vote,
    UPPER(p.party_name) as party_name,
    c.p_id
FROM
    candidate c
INNER JOIN voters v ON
    c.stud_id = v.stud_id
INNER JOIN partylist p ON
    c.p_id = p.p_id
INNER JOIN POSITION pos ON
    c.pos_id = pos.pos_id
INNER JOIN election_title et ON
    et.acad_id = c.acad_id
LEFT JOIN (
    SELECT 
        candidate_id,
        COUNT(DISTINCT voter_id) AS totalvote
    FROM
        vote
    GROUP BY
        candidate_id
) vt ON
    vt.candidate_id = c.c_id
WHERE
    c.acad_id = $acad AND (
        SELECT
            COUNT(*)
        FROM
            candidate c2
        LEFT JOIN (
            SELECT 
                candidate_id,
                COUNT(DISTINCT voter_id) AS totalvote
            FROM
                vote
            GROUP BY
                candidate_id
        ) vt2 ON
            vt2.candidate_id = c2.c_id
        WHERE
            c2.pos_id = c.pos_id AND vt2.totalvote > vt.totalvote
    ) < pos.max_vote
AND et.is_finished = 1
ORDER BY
    pos.priority ASC,
    vt.totalvote DESC";

$rs = $conn->query($sqlt);

if (!$rs) {
    die("Query Error: " . $conn->error); // Log or display the SQL error
}

if ($rs->num_rows > 0) {
    $i = 1;
    $output .= "
        <center><h3 style='font-family:Arial;'>OFFICAL RESULT FOR SEAIT ELECTION 2024 </h3></center> 
    ";
    foreach ($rs as $row) {
        if ($row['p_id'] == 0) {
            $pn = "IND";
        } else {
            $pn = $row['party_name'];
        }
        $output .= "
            <ul class='candidate-list'>
                <li class='text-capitalize'><span style='font-weight:bold'>" . htmlspecialchars($row['description']) . "</span> - " . htmlspecialchars($row['fname']) . " (" . $pn . ")</li>
            </ul>
        ";
    }
} else {
    $output .= "<p>No results found.</p>";
}

$output .= "</body></html>";

$document->loadHtml($output);
$document->setPaper('A4', 'portrait');
$document->render();
$document->stream("results", array("Attachment" => 0));
?>