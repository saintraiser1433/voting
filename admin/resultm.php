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
        <img src='../libraries/img/glanlogo.png' width='100px;' height='100px;'>
        <div style='margin-top:-100px; text-align:center; font-family:Arial, sans-serif; line-height:1.4'>
            <h4 style='margin:0;'>GLAN INSTITUTE OF TECHNOLOGY</h4>
            <p style='margin:0; font-size:12px;'>Sarangani, Philippines</p>
            <h5 style='margin-top:10px;'>SCHOOL YEAR: " . htmlspecialchars($rowqq['description']) . "</h5>
            <h5 style='margin-top:4px;'>OFFICIAL TALLY FOR GIT ELECTION " . htmlspecialchars($rowqq['description']) . "</h5>
        </div>

";
$date = date('Y-m-d');
$output .= "<p style='font-weight:bold'>Date Printed :  $date </p>";
$sqlx = "SELECT * FROM position WHERE acad_id='$acad' ORDER BY priority ASC";

$rsx = $conn->query($sqlx);
if (!$rsx) {
    die("Query Error: " . $conn->error); // Log or display the SQL error
}

if ($rsx->num_rows > 0) {
    $i = 1;

    foreach ($rsx as $rows) {
        $output .= "<h4 style='text-transform:uppercase; font-weight:bold; margin-top:15px; margin-bottom:5px;'>" . htmlspecialchars($rows['description']) . "</h4>";
        $dt = $rows['pos_id'];
        $sqlb = "SELECT CONCAT(UPPER(voters.lname), ', ', UPPER(voters.fname)) AS fname, c_id
                 FROM candidate
                 LEFT JOIN voters ON candidate.stud_id = voters.stud_id
                 LEFT JOIN partylist ON candidate.p_id = partylist.p_id
                 WHERE candidate.acad_id='$acad' AND candidate.pos_id = $dt";
        $rxc = $conn->query($sqlb);
        if ($rxc->num_rows > 0) {
            $output .= "<table width='100%' cellpadding='4' cellspacing='0' style='border-collapse:collapse; font-size:12px; margin-bottom:5px;'>
                            <tbody>";
            foreach ($rxc as $rowt) {
                $id = $rowt['c_id'];
                $sqltt = "SELECT COUNT(*) AS votes FROM vote WHERE acad_id='$acad' AND candidate_id = $id";
                $rsst = $conn->query($sqltt);
                $votes = ($rsst && $rowVotes = $rsst->fetch_assoc()) ? (int)$rowVotes['votes'] : 0;
                $output .= "<tr>
                                <td style='padding-left:15px;'>• " . htmlspecialchars($rowt['fname']) . "</td>
                                <td width='60' align='right' style='font-weight:bold;'>" . $votes . "</td>
                            </tr>";
            }
            $output .= "    </tbody>
                        </table>";
        }

    }
} else {
    $output .= "<p>No results found.</p>";
}


$sqlt = "SELECT
    CONCAT(UPPER(v.lname), ', ', UPPER(v.fname)) AS fname,
    UPPER(pos.description) AS description,
    vt.totalvote,
    pos.max_vote,
    UPPER(p.party_name) AS party_name,
    c.p_id,
    c.c_id
FROM candidate c
INNER JOIN voters v ON c.stud_id = v.stud_id
INNER JOIN partylist p ON c.p_id = p.p_id
INNER JOIN position pos ON c.pos_id = pos.pos_id
INNER JOIN election_title et ON et.acad_id = c.acad_id
LEFT JOIN (
    SELECT candidate_id, COUNT(DISTINCT voter_id) AS totalvote
    FROM vote
    GROUP BY candidate_id
) vt ON vt.candidate_id = c.c_id
WHERE c.acad_id = $acad
  AND (
        SELECT COUNT(*)
        FROM candidate c2
        LEFT JOIN (
            SELECT candidate_id, COUNT(DISTINCT voter_id) AS totalvote
            FROM vote
            GROUP BY candidate_id
        ) vt2 ON vt2.candidate_id = c2.c_id
        WHERE c2.pos_id = c.pos_id AND vt2.totalvote > vt.totalvote
      ) < pos.max_vote
  AND et.is_finished = 1
GROUP BY c.c_id
ORDER BY pos.priority ASC, vt.totalvote DESC";

$rs = $conn->query($sqlt);

if (!$rs) {
    die("Query Error: " . $conn->error); // Log or display the SQL error
}

if ($rs->num_rows > 0) {
    $output .= "
        <hr style='margin-top:20px; margin-bottom:10px;'>
        <center><h4 style='font-family:Arial; margin:0;'>OFFICIAL RESULT FOR GIT ELECTION " . htmlspecialchars($rowqq['description']) . " </h4></center> 
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