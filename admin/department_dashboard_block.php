<?php
// Department admin dashboard – same structure as general: open = YEAR LEVEL RESULTS (tabs + radial bars), closed = OFFICERS/RESULTS + Reopen + winner cards.
// Expects: $acad, $conn, $acads already defined.

// Department election status
// Use only department row – do not fall back to general row so close/open stay separate
$sqlDeptElection = "SELECT * FROM election_title WHERE acad_id = '$acad' AND election_type = 'department' ORDER BY id DESC LIMIT 1";
$rssDept = $conn->query($sqlDeptElection);
if (!$rssDept && strpos($conn->error ?? '', 'Unknown column') !== false) {
    $rssDept = null;
    $election_row = null;
}
$election_row = ($rssDept && $rssDept->num_rows > 0) ? $rssDept->fetch_assoc() : null;
$fin = isset($election_row['is_finished']) ? $election_row['is_finished'] : 0;
$election_open = $election_row && ((int)$fin === 0);

// Departments list and selected department
$deptSql = "SELECT department_id, department_name FROM departments WHERE status = 1 ORDER BY department_name ASC";
$deptRes = $conn->query($deptSql);
$selectedDept = isset($_GET['dept_id']) ? (int)$_GET['dept_id'] : 0;
$firstDeptId = 0;
if ($deptRes && $deptRes->num_rows > 0) {
    $firstRow = $deptRes->fetch_assoc();
    $firstDeptId = (int)$firstRow['department_id'];
    if ($selectedDept === 0) {
        $selectedDept = $firstDeptId;
    }
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
?>

<?php if ($election_row && $election_open) {
$date_start = isset($election_row['date_start']) ? $election_row['date_start'] : null;
$date_end = isset($election_row['date_end']) ? $election_row['date_end'] : null;
$label = 'Department voting';
$running_time_id = 'dept';
include 'includes/election_running_time.php';
?>
<div id="ps"></div>
<div id="ps-dept" class="col-xl-12 col-md-12">
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
                    <select id="adminDeptSelect" class="form-control">
                        <?php while ($d = $deptRes->fetch_assoc()) {
                            $did = (int)$d['department_id'];
                            ?>
                            <option value="<?php echo $did; ?>" <?php echo $did === $selectedDept ? 'selected' : ''; ?>>
                                <?php echo htmlspecialchars($d['department_name']); ?>
                            </option>
                        <?php } ?>
                    </select>
                </div>
            </div>
            <?php
            $deptRes->data_seek(0);
            if ($selectedDept > 0) {
                $stq = "SELECT grade_level FROM voters WHERE acad_id='$acad' AND department_id='$selectedDept' AND grade_level IS NOT NULL AND grade_level != '' GROUP BY grade_level ORDER BY grade_level ASC";
                $rpq = $conn->query($stq);
            } else {
                $rpq = false;
            }
            ?>
            <?php if ($rpq && $rpq->num_rows > 0) { ?>
            <ul class="nav nav-tabs tabs" role="tablist">
                <?php while ($rqq = $rpq->fetch_assoc()) { ?>
                <li class="nav-item">
                    <a class="nav-link" data-toggle="tab" href="#dept_<?php echo (int)$rqq['grade_level']; ?>" role="tab">Year <?php echo htmlspecialchars($rqq['grade_level']); ?></a>
                </li>
                <?php } ?>
            </ul>
            <div class="tab-content tabs card-block">
                <?php
                $stq1 = "SELECT grade_level FROM voters WHERE acad_id='$acad' AND department_id='$selectedDept' AND grade_level IS NOT NULL AND grade_level != '' GROUP BY grade_level ORDER BY grade_level ASC";
                $rpq1 = $conn->query($stq1);
                $first = true;
                while ($rpq1 && ($rqq1 = $rpq1->fetch_assoc())) {
                    $gr = $rqq1['grade_level'];
                    $activeClass = $first ? ' active' : '';
                    $first = false;
                    echo '<div class="tab-pane' . $activeClass . '" id="dept_' . htmlspecialchars($gr) . '" role="tabpanel">';
                    $gr_s = $conn->real_escape_string($gr);
                    $stq = "SELECT section, strand FROM voters WHERE acad_id='$acad' AND department_id='$selectedDept' AND grade_level='" . $gr_s . "' GROUP BY section, strand";
                    $ss = $conn->query($stq);
                    if ($ss) {
                        while ($myrow = $ss->fetch_assoc()) {
                            $sec = isset($myrow['section']) ? $myrow['section'] : '';
                            $str = isset($myrow['strand']) ? $myrow['strand'] : '';
                            $sec_s = $conn->real_escape_string($sec);
                            $str_s = $conn->real_escape_string($str);
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
                            $strDisplay = trim((isset($myrow['strand']) ? $myrow['strand'] : '') . ' ' . (isset($myrow['section']) ? $myrow['section'] : ''));
                            echo '<div style="font-size:11px;" data-label="' . $roundt2 . '%" class="radial-bar radial-bar-' . $me . ' radial-bar-md radial-bar-success"><br><br><br><br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <span style="font-size:11px;">' . htmlspecialchars($strDisplay) . '</span></div>';
                        }
                    }
                    echo '</div>';
                }
                ?>
            </div>
            <?php } else { ?>
            <p class="text-muted m-b-0">No voters in this department with year level data, or no department selected.</p>
            <?php } ?>
            <?php } else { ?>
            <p class="text-muted m-b-0">No departments found. Add departments in the Department module.</p>
            <?php } ?>
        </div>
    </div>
</div>
<script>window.__deptDashboardDeptId = <?php echo (int)$selectedDept; ?>;</script>
<?php } else { ?>
<div class="col-12 dashboard-officers-wrap">
<div class="page-header">
    <div class="row align-items-end">
        <div class="col-lg-12">
            <div class="page-header-title">
                <div class="d-inline">
                    <h4>OFFICERS / RESULTS FOR ACADEMIC YEAR : <?php echo htmlspecialchars($acads); ?></h4>
                    <span><?php echo $election_row ? 'Elected officers (election ended)' : 'No department election configured for this year. Open Election Settings to set up.'; ?></span>
                    <?php if ($election_row && !$election_open) { ?>
                    <div class="mt-2">
                        <button type="button" class="btn btn-success btn-sm" id="reopenElectionBtn">Reopen election (voting ongoing)</button>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>
<?php if ($deptRes && $deptRes->num_rows > 0 && !$election_open) {
    $deptRes->data_seek(0);
?>
<div class="row m-b-20">
    <div class="col-md-4">
        <label for="adminDeptSelectClosed"><strong>Department:</strong></label>
        <select id="adminDeptSelectClosed" class="form-control">
            <?php while ($d = $deptRes->fetch_assoc()) {
                $did = (int)$d['department_id'];
                ?>
                <option value="<?php echo $did; ?>" <?php echo $did === $selectedDept ? 'selected' : ''; ?>>
                    <?php echo htmlspecialchars($d['department_name']); ?>
                </option>
            <?php } ?>
        </select>
    </div>
</div>
<?php } ?>
<div class="row users-card">
    <?php
    if (!$election_row) {
        echo '<div class="col-12"><p class="text-muted">Configure the department election and open voting from <strong>Election Settings</strong> to see results here.</p></div>';
    } else {
        if ($deptRes && $deptRes->num_rows > 0 && $selectedDept > 0) {
            $wSql = "SELECT CONCAT(UPPER(v.lname), ', ', UPPER(v.fname)) AS fname, dp.description,
                (SELECT COUNT(*) FROM department_vote dvo WHERE dvo.candidate_id = dc.dc_id AND dvo.acad_id = dc.acad_id AND dvo.department_id = dc.department_id) AS totalvote,
                dp.max_vote, dc.img
                FROM dept_candidate dc
                INNER JOIN voters v ON dc.stud_id = v.stud_id AND dc.acad_id = v.acad_id
                INNER JOIN dept_position dp ON dc.pos_id = dp.dp_id AND dc.acad_id = dp.acad_id
                WHERE dc.acad_id = '$acad' AND dc.department_id = '$selectedDept'
                ORDER BY dp.priority ASC, totalvote DESC";
            $wRes = $conn->query($wSql);
            if ($wRes && $wRes->num_rows > 0) {
                while ($r = $wRes->fetch_assoc()) {
                    echo '<div class="col-lg-6 col-xl-3 col-md-6">';
                    echo '<div class="card rounded-card user-card"><div class="card-block">';
                    echo '<div class="img-hover"><img class="img-fluid img-radius" src="../' . htmlspecialchars($r['img']) . '" alt=""></div>';
                    echo '<div class="user-content"><h4>' . htmlspecialchars($r['fname']) . '</h4>';
                    echo '<p class="m-b-0 text-muted text-capitalize">VOTES - ' . (int)$r['totalvote'] . '</p>';
                    echo '<p class="m-b-0 text-muted text-capitalize">' . htmlspecialchars($r['description']) . '</p></div></div></div></div>';
                }
            } else {
                echo '<div class="col-12"><p class="text-muted m-b-0">No department votes recorded yet for this department.</p></div>';
            }
        } else {
            echo '<div class="col-12"><p class="text-muted m-b-0">Select a department above to view elected officers. No departments found.</p></div>';
        }
    }
    ?>
</div>
</div>
<?php } ?>

<script>
(function() {
    var sel = document.getElementById('adminDeptSelect') || document.getElementById('adminDeptSelectClosed');
    if (!sel) return;
    sel.addEventListener('change', function () {
        var url = new URL(window.location.href);
        url.searchParams.set('dept_id', this.value || '');
        window.location.href = url.toString();
    });
})();
</script>
