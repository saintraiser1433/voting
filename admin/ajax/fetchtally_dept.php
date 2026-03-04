<?php
/**
 * Returns the department YEAR LEVEL RESULTS card HTML (with radial bars) for AJAX refresh.
 * GET dept_id = selected department. Uses session acad.
 * Ensures radial participation updates when department votes are cast.
 */
include '../../connection.php';
include '../nav/header.php';

if (!isset($_SESSION['at']) || !isset($_SESSION['acad'])) {
    echo '<div class="alert alert-warning">Session required.</div>';
    exit;
}

$acad = (int) $_SESSION['acad'];
$selectedDept = isset($_GET['dept_id']) ? (int) $_GET['dept_id'] : 0;
$deptRes = $conn->query("SELECT department_id, department_name FROM departments WHERE status = 1 ORDER BY department_name ASC");
if ($selectedDept <= 0 && $deptRes && $deptRes->num_rows > 0) {
    $row = $deptRes->fetch_assoc();
    $selectedDept = (int) $row['department_id'];
    $deptRes->data_seek(0);
}

function dept_radial_bar_class($roundt2) {
    if ($roundt2 == 0) return 0;
    if ($roundt2 <= 10.99) return 10;
    if ($roundt2 <= 20.99) return 20;
    if ($roundt2 <= 30.99) return 30;
    if ($roundt2 <= 40.99) return 40;
    if ($roundt2 <= 50.99) return 50;
    if ($roundt2 <= 60.99) return 60;
    if ($roundt2 <= 70.99) return 70;
    if ($roundt2 <= 80.99) return 80;
    if ($roundt2 <= 90.99) return 90;
    return 100;
}

$gr_esc = function($v) use ($conn) { return $conn->real_escape_string($v !== null && $v !== '' ? $v : ''); };

// Department stats (only when a department is selected)
$totalDeptVoters = 0;
$totalDeptVoted = 0;
if ($selectedDept > 0) {
    $rx = $conn->query("SELECT COUNT(DISTINCT v_id) AS c FROM voters WHERE acad_id='$acad' AND department_id='$selectedDept'");
    if ($rx && $rowx = $rx->fetch_assoc()) $totalDeptVoters = (int)$rowx['c'];
    $rx = $conn->query("SELECT COUNT(DISTINCT voter_id) AS c FROM department_vote WHERE acad_id='$acad' AND department_id='$selectedDept'");
    if ($rx && $rowx = $rx->fetch_assoc()) $totalDeptVoted = (int)$rowx['c'];
}
?>
<div class="row">
<?php if ($selectedDept > 0) { ?>
    <div class="col-xl-3 col-md-6">
        <div class="card bg-c-yellow text-white">
            <div class="card-block">
                <div class="row align-items-center">
                    <div class="col">
                        <p class="m-b-5">Dept. Positions</p>
                        <h4 class="m-b-0"><?php
                            $rp = $conn->query("SELECT COUNT(*) AS c FROM dept_position WHERE acad_id='$acad'");
                            echo ($rp && $rowp = $rp->fetch_assoc()) ? (int)$rowp['c'] : 0;
                        ?></h4>
                    </div>
                    <div class="col col-auto text-right"><i class="feather icon-list f-50 text-c-yellow"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card bg-c-green text-white">
            <div class="card-block">
                <div class="row align-items-center">
                    <div class="col">
                        <p class="m-b-5">Dept. Candidates</p>
                        <h4 class="m-b-0"><?php
                            $rc = $conn->query("SELECT COUNT(*) AS c FROM dept_candidate WHERE acad_id='$acad' AND department_id='$selectedDept'");
                            echo ($rc && $rowc = $rc->fetch_assoc()) ? (int)$rowc['c'] : 0;
                        ?></h4>
                    </div>
                    <div class="col col-auto text-right"><i class="feather icon-user f-50 text-c-green"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card bg-c-pink text-white">
            <div class="card-block">
                <div class="row align-items-center">
                    <div class="col">
                        <p class="m-b-5">Total Voted (Dept)</p>
                        <h4 class="m-b-0"><?php echo $totalDeptVoted; ?></h4>
                    </div>
                    <div class="col col-auto text-right"><i class="feather icon-book f-50 text-c-pink"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card bg-c-blue text-white">
            <div class="card-block">
                <div class="row align-items-center">
                    <div class="col">
                        <p class="m-b-5">Dept. Voters</p>
                        <h4 class="m-b-0"><?php echo $totalDeptVoters; ?></h4>
                    </div>
                    <div class="col col-auto text-right"><i class="feather icon-shopping-cart f-50 text-c-blue"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-12 col-md-12">
        <div class="card bg-c-green text-white">
            <div class="card-block">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="m-b-5 mb-2"><b>ELECTION RETURN TRANSMITTED (Department)</b></h6>
                        <?php
                        $pct = ($totalDeptVoters > 0) ? round($totalDeptVoted / $totalDeptVoters * 100, 2) : 0;
                        ?>
                        <span class="elec bg-primary p-1" style="border-radius: 50px; border:2px solid white"><?php echo $pct; ?>%</span>
                        <span>- <?php echo $totalDeptVoted; ?> of <?php echo $totalDeptVoters; ?> department voters have voted</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    // Participant tally: one card per department position
    $posRes = $conn->query("SELECT * FROM dept_position WHERE acad_id='$acad' ORDER BY priority ASC");
    if ($posRes && $posRes->num_rows > 0) {
        while ($posRow = $posRes->fetch_assoc()) {
            $dp_id = (int)$posRow['dp_id'];
            $desc = htmlspecialchars($posRow['description']);
            ?>
    <div class="col-xl-6 col-md-12">
        <div class="card">
            <div class="card-header">
                <div class="card-header-left">
                    <h5 class="text-uppercase"><?php echo $desc; ?></h5>
                    <span class="text-muted">Candidates for <?php echo $desc; ?> in this department</span>
                </div>
                <div class="card-header-right">
                    <ul class="list-unstyled card-option">
                        <li><i class="feather icon-maximize full-card"></i></li>
                    </ul>
                </div>
            </div>
            <div class="card-block-big">
                <div class="row">
                    <div class="col-md-12">
            <?php
            $candRes = $conn->query("SELECT dc.dc_id, dc.stud_id, v.lname, v.fname, v.mname FROM dept_candidate dc INNER JOIN voters v ON dc.stud_id = v.stud_id AND dc.acad_id = v.acad_id WHERE dc.acad_id='$acad' AND dc.department_id='$selectedDept' AND dc.pos_id='$dp_id'");
            if ($candRes && $candRes->num_rows > 0) {
                while ($crow = $candRes->fetch_assoc()) {
                    $mname_display = !empty($crow['mname']) ? substr($crow['mname'], 0, 1) . '.' : '';
                    echo '<label class="text-primary text-uppercase">' . htmlspecialchars($crow['lname']) . ', ' . htmlspecialchars($crow['fname']) . ' ' . $mname_display . '</label>';
                    $voteRes = $conn->query("SELECT COUNT(*) AS c FROM department_vote WHERE acad_id='$acad' AND department_id='$selectedDept' AND candidate_id='" . (int)$crow['dc_id'] . "'");
                    $voteCount = ($voteRes && $vr = $voteRes->fetch_assoc()) ? (int)$vr['c'] : 0;
                    $pct = $totalDeptVoters > 0 ? round($voteCount / $totalDeptVoters * 100) : 0;
                    echo '<br><label>' . $voteCount . ' - Votes</label>';
                    echo '<div class="progress"><div class="progress-bar progress-bar-striped progress-bar-primary" role="progressbar" style="width:' . $pct . '%" aria-valuenow="' . $pct . '" aria-valuemin="0" aria-valuemax="100"></div></div><br>';
                }
            } else {
                echo '<p class="text-muted m-b-0">No candidates for this position in this department.</p>';
            }
            ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
        }
    }
}
?>
<div class="col-xl-12 col-md-12">
    <div class="card">
        <div class="card bg-c-green text-white">
            <div class="card-block">
                <div class="row align-items-center">
                    <div class="col">
                        <h3 class="m-b-5"><b>YEAR LEVEL RESULTS</b></h3>
                        <span class="small">Department voting – participation by year level</span>
                    </div>
                    <div class="col col-auto text-right">
                        <i class="feather icon-book f-50 text-c-white"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="card-block">
            <?php if ($deptRes && $deptRes->num_rows > 0) { ?>
            <div class="row m-b-20">
                <div class="col-md-4">
                    <label for="adminDeptSelect"><strong>Department:</strong></label>
                    <select id="adminDeptSelect" class="form-control" onchange="var v=this.value; if(v) window.location.href='dashboard.php?dept_id='+v;">
                        <?php $deptRes->data_seek(0); while ($d = $deptRes->fetch_assoc()) {
                            $did = (int)$d['department_id'];
                            ?>
                            <option value="<?php echo $did; ?>" <?php echo $did === $selectedDept ? 'selected' : ''; ?>><?php echo htmlspecialchars($d['department_name']); ?></option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <?php } ?>
            <?php
            if ($selectedDept <= 0) {
                echo '<p class="text-muted m-b-0">Select a department.</p>';
            } else {
                $stq = "SELECT grade_level FROM voters WHERE acad_id='$acad' AND department_id='$selectedDept' AND grade_level IS NOT NULL AND grade_level != '' GROUP BY grade_level ORDER BY grade_level ASC";
                $rpq = $conn->query($stq);
                if (!$rpq || $rpq->num_rows === 0) {
                    echo '<p class="text-muted m-b-0">No voters in this department with year level data.</p>';
                } else {
            ?>
            <ul class="nav nav-tabs tabs" role="tablist">
                <?php $rpq->data_seek(0); while ($rqq = $rpq->fetch_assoc()) { ?>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#dept_<?php echo (int)$rqq['grade_level']; ?>" role="tab">Year <?php echo htmlspecialchars($rqq['grade_level']); ?></a>
                </li>
                <?php } ?>
            </ul>
            <div class="tab-content tabs card-block">
                <?php
                $rpq->data_seek(0);
                $first = true;
                while ($rpq && ($rqq1 = $rpq->fetch_assoc())) {
                    $gr = $rqq1['grade_level'];
                    $activeClass = $first ? ' active' : '';
                    $first = false;
                    $gr_s = $gr_esc($gr);
                    echo '<div class="tab-pane' . $activeClass . '" id="dept_' . htmlspecialchars($gr) . '" role="tabpanel">';
                    $stq = "SELECT section, strand FROM voters WHERE acad_id='$acad' AND department_id='$selectedDept' AND grade_level='" . $gr_s . "' GROUP BY section, strand";
                    $ss = $conn->query($stq);
                    if ($ss) {
                        while ($myrow = $ss->fetch_assoc()) {
                            $sec = isset($myrow['section']) ? $myrow['section'] : '';
                            $str = isset($myrow['strand']) ? $myrow['strand'] : '';
                            $sec_s = $gr_esc($sec);
                            $str_s = $gr_esc($str);
                            $sqlt = "SELECT COUNT(*) AS cnt FROM voters WHERE acad_id='$acad' AND department_id='$selectedDept' AND grade_level='" . $gr_s . "' AND COALESCE(section,'')='$sec_s' AND COALESCE(strand,'')='$str_s'";
                            $rsqlt = $conn->query($sqlt);
                            $to11 = 1;
                            if ($rsqlt && $rowt = $rsqlt->fetch_assoc()) {
                                $to11 = (int)$rowt['cnt'];
                            }
                            $sqlovr = "SELECT COUNT(DISTINCT v.v_id) AS cnt FROM voters v INNER JOIN department_vote dv ON v.v_id = dv.voter_id AND dv.acad_id = v.acad_id AND dv.department_id = v.department_id WHERE v.acad_id='$acad' AND v.department_id='$selectedDept' AND v.grade_level='" . $gr_s . "' AND COALESCE(v.section,'')='$sec_s' AND COALESCE(v.strand,'')='$str_s'";
                            $rsqltq = $conn->query($sqlovr);
                            $from11 = 0;
                            if ($rsqltq && $rowq = $rsqltq->fetch_assoc()) {
                                $from11 = (int)$rowq['cnt'];
                            }
                            $finalt1 = $to11 > 0 ? ($from11 / $to11 * 100) : 0;
                            $roundt2 = round($finalt1, 2);
                            $me = dept_radial_bar_class($roundt2);
                            $strDisplay = $str . ' ' . $sec;
                            echo '<div style="font-size:11px;" data-label="' . $roundt2 . '%" class="radial-bar radial-bar-' . $me . ' radial-bar-md radial-bar-success"><br><br><br><br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span style="font-size:11px;">' . htmlspecialchars($strDisplay) . '</span></div>';
                        }
                    }
                    echo '</div>';
                }
                ?>
            </div>
            <?php
                }
            }
            ?>
        </div>
    </div>
</div>
</div>
