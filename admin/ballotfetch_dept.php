<?php
include '../connection.php';
include 'includes/slugify.php';
$acad = (int) $_SESSION['acad'];

$pquery = $conn->query("SELECT * FROM dept_position WHERE acad_id='$acad'");
$total = ($pquery && $pquery->num_rows) ? $pquery->num_rows : 0;

$output = '';
$sql = "SELECT * FROM dept_position WHERE acad_id='$acad' ORDER BY priority ASC";
$query = $conn->query($sql);
if (!$query) {
    echo json_encode('');
    exit;
}
$num = 1;
while ($row = $query->fetch_assoc()) {
    $instruct = ($row['max_vote'] > 1) ? 'You may select up to ' . $row['max_vote'] . ' candidates' : 'Select only one candidate';
    $updisable = (isset($row['priority']) && $row['priority'] == 1) ? 'disabled' : '';
    $downdisable = ($total > 0 && isset($row['priority']) && $row['priority'] == $total) ? 'disabled' : '';

    $candidate = '';
    $cq = $conn->query("SELECT dc.*, dv.fname, dv.lname, dv.mname FROM dept_candidate dc INNER JOIN dept_voters dv ON dc.stud_id = dv.stud_id AND dc.acad_id = dv.acad_id WHERE dc.acad_id='$acad' AND dc.pos_id='" . (int)$row['dp_id'] . "'");
    if ($cq && $cq->num_rows > 0) {
        while ($crow = $cq->fetch_assoc()) {
            $img = !empty($crow['img']) ? $crow['img'] : 'libraries/img/logo.png';
            $m = isset($crow['mname'][0]) ? $crow['mname'][0] . '.' : '';
            $candidate .= '<div class="row mt-2"><ul><li><img src="../' . htmlspecialchars($img) . '" style="width:120px;height:120px;border:2px solid steelblue;border-radius:10px;"></li></ul>';
            $candidate .= '<div class="text-center mt-3 pl-3"><h4 class="text-uppercase font-weight-bold" id="myh3">' . htmlspecialchars($crow['fname'] . ',' . $crow['lname'] . ' ' . $m) . '</h4></div></div>';
        }
    } else {
        $candidate = '<p class="text-muted">No candidates for this position.</p>';
    }

    $output .= '<div class="row"><div class="col-xl-12 col-md-12"><div class="card" id="' . $row['dp_id'] . '">
        <div class="card bg-c-green text-white"><div class="card-block" id="myimgs">
            <div class="row align-items-center">
                <div class="col"><h3 class="m-b-5 text-uppercase"><b>' . htmlspecialchars($row['description']) . '</b></h3><span class="text-white">' . $instruct . '</span></div>
                <div class="col col-auto text-right">
                    <button type="button" class="btn btn-primary btn-sm moveup" data-id="' . $row['dp_id'] . '" ' . $updisable . '><i class="fa fa-arrow-up"></i></button>
                    <button type="button" class="btn btn-warning btn-sm movedown" data-id="' . $row['dp_id'] . '" ' . $downdisable . '><i class="fa fa-arrow-down"></i></button>
                </div>
            </div>
        </div></div>
        <div class="card-block-big">' . $candidate . '</div></div></div></div>';
    $num++;
}
echo json_encode($output);
