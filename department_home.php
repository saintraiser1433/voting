<?php
include 'connection.php';

$acad = $_SESSION['acad'];
$voter = isset($_SESSION['v_id']) ? (int) $_SESSION['v_id'] : 0;
$voting_mode = isset($_SESSION['voting_mode']) ? $_SESSION['voting_mode'] : '';

// Always read latest department assignment from voters table (session may be stale)
$dept_id = 0;
if ($voter) {
    $deptRes = $conn->query("SELECT department_id FROM voters WHERE v_id='$voter' AND acad_id='$acad' LIMIT 1");
    if ($deptRes && $deptRes->num_rows > 0) {
        $deptRow = $deptRes->fetch_assoc();
        $dept_id = isset($deptRow['department_id']) ? (int)$deptRow['department_id'] : 0;
        $_SESSION['dept_id'] = $dept_id; // keep session in sync
    }
}

if (!$voter || $voting_mode !== 'department' || !isset($_SESSION['faceverified'])) {
    header("Location: logout.php");
    exit;
}

$sel = "SELECT * FROM acad_tbl WHERE acad_id = $acad";
$rs = $conn->query($sel);
$row = $rs && $rs->num_rows > 0 ? $rs->fetch_assoc() : null;
$acads = $row ? $row['description'] : '';
?>

<!DOCTYPE html>
<html lang="en">
<?php include 'nav/header.php'; ?>
<body>
    <div class="theme-loader"><div class="ball-scale"><div class="contain"><div class="ring"><div class="frame"></div></div></div></div></div>
    <div id="pcoded" class="pcoded">
        <div class="pcoded-overlay-box"></div>
        <div class="pcoded-container navbar-wrapper">
            <?php include 'nav/topbar.php'; ?>
            <div class="pcoded-main-container">
                <div class="pcoded-wrapper">
                    <div class="main-body">
                        <div class="page-wrapper">
                            <div class="page-body">
                                <!-- Offline sync button (always visible; sync only pending DEPARTMENT mode) -->
                                <div class="mb-3" id="offline-sync-container">
                                    <button type="button" class="btn btn-warning btn-sm" id="btn-sync-offline-dept">
                                        <i class="fa fa-cloud-upload"></i> Sync Offline Vote
                                    </button>
                                </div>
                                <script>
                                    (function () {
                                        try {
                                            var host = window.location.hostname || '';
                                            var isLocal = (host === 'localhost' || host === '127.0.0.1' || host === '::1');
                                            if (!isLocal) {
                                                var el = document.getElementById('offline-sync-container');
                                                if (el) el.style.display = 'none';
                                            }
                                        } catch (e) { }
                                    })();
                                </script>

                                <?php
                                // Department election: must have a row with election_type='department' and is_finished=0
                                $sqlt = "SELECT * FROM election_title WHERE acad_id = '$acad' AND election_type = 'department' AND is_finished = 0";
                                $rs = $conn->query($sqlt);
                                // If no row, try fallback: old schema without election_type, or auto-create department election when candidates exist
                                if (!$rs || $rs->num_rows === 0) {
                                    if ($rs && strpos($conn->error ?? '', 'Unknown column') !== false) {
                                        $rs = $conn->query("SELECT * FROM election_title WHERE acad_id = '$acad' AND is_finished = 0 LIMIT 1");
                                    }
                                    if (!$rs || $rs->num_rows === 0) {
                                        // Auto-create department election for this year if there are department candidates (so voting can open)
                                        $chk = $conn->query("SELECT 1 FROM dept_candidate WHERE acad_id = '$acad' LIMIT 1");
                                        if ($chk && $chk->num_rows > 0) {
                                            if (!$conn->query("INSERT INTO election_title (title, acad_id, election_type, is_finished) VALUES ('Department Election', '$acad', 'department', 0)")) {
                                                $conn->query("INSERT INTO election_title (title, acad_id, is_finished) VALUES ('Department Election', '$acad', 0)");
                                            }
                                            $rs = $conn->query($sqlt);
                                            if (!$rs || $rs->num_rows === 0) {
                                                $rs = $conn->query("SELECT * FROM election_title WHERE acad_id = '$acad' AND is_finished = 0 ORDER BY id DESC LIMIT 1");
                                            }
                                        }
                                    }
                                }
                                if ($rs && $rs->num_rows > 0) {
                                    $et = $rs->fetch_assoc();
                                    $date_start = isset($et['date_start']) ? $et['date_start'] : null;
                                    $date_end = isset($et['date_end']) ? $et['date_end'] : null;
                                    $label = 'Department voting';
                                    $running_time_id = 'dept-voter';
                                    include 'admin/includes/election_running_time.php';
                                    ?>
                                    <div class="row">
                                        <div class="col-xl-12 col-md-12">
                                            <div class="card">
                                                
                                                <div class="card-block-big">
                                                    <div class="row">
                                                        <div class="col-lg-5">
                                                            <img src="libraries/img/glanlogo.png" class="img-fluid" style="width:250px; height:250px;">
                                                        </div>
                                                        <div class="col-lg-7 justify-content-lg-center">
                                                            <h2>Department Election</h2>
                                                            <span class="text-muted">Session Year: <?php echo htmlspecialchars($acads); ?></span><br><br>
                                                            <?php
                                                            // Only allow department voting if the voter has an assigned department
                                                            if ($dept_id <= 0) {
                                                                echo '<span class="text-center font-weight-bold text-danger">No department assigned to your account. Department voting is not available. Please contact the system administrator.</span><br><br>';
                                                            } else {
                                                                $chk = $conn->query("SELECT 1 FROM department_vote WHERE acad_id='$acad' AND voter_id='$voter'");
                                                                if ($chk && $chk->num_rows > 0) {
                                                                    echo '<span class="text-center font-weight-bold">You have already voted for this department election.</span><br><br>';
                                                                    echo '<a href="my_ballot.php?type=department" class="btn btn-outline-primary btn-sm mr-1"><i class="fa fa-file-text-o"></i> View My Ballot</a>';
                                                                    echo '<a href="my_ballot.php?type=department" class="btn btn-outline-secondary btn-sm mr-1" target="_blank"><i class="fa fa-print"></i> Print Ballot</a>';
                                                                    echo '<a href="switch_voting_mode.php?mode=general" class="btn btn-outline-primary btn-sm">General Voting</a>';
                                                                } else {
                                                                    echo '<span class="text-center font-weight-bold">Please click \"Start\" to begin department vote.</span><br><br>';
                                                                    echo '<a href="department_ballot.php" class="btn btn-success mr-1"><i class="fa fa-arrow-right"></i> Start</a>';
                                                                    echo '<a href="switch_voting_mode.php?mode=general" class="btn btn-outline-primary btn-sm">General Voting</a>';
                                                                }
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <?php
                                    // Live department results (exclusive to this voter's department)
                                    $w = "SELECT CONCAT(UPPER(v.lname), ', ', UPPER(v.fname)) AS fname,
                                                  dp.description,
                                                  (SELECT COUNT(*) FROM department_vote dvo
                                                    WHERE dvo.candidate_id = dc.dc_id
                                                      AND dvo.acad_id = dc.acad_id
                                                      AND dvo.department_id = dc.department_id) AS totalvote,
                                                  dp.max_vote, dc.img
                                          FROM dept_candidate dc
                                          INNER JOIN voters v ON dc.stud_id = v.stud_id AND dc.acad_id = v.acad_id
                                          INNER JOIN dept_position dp ON dc.pos_id = dp.dp_id AND dc.acad_id = dp.acad_id
                                          WHERE dc.acad_id = '$acad' AND dc.department_id = '$dept_id'
                                          ORDER BY dp.priority ASC, totalvote DESC";
                                    $resLive = $conn->query($w);
                                    ?>
                                    <div class="row users-card mt-3">
                                        <div class="col-12">
                                            <h5 class="m-b-10">Live Department Results (Your Department Only)</h5>
                                            <p class="text-muted m-b-20">This shows current votes for candidates in your department.</p>
                                        </div>
                                        <?php
                                        if ($resLive && $resLive->num_rows > 0) {
                                            while ($r = $resLive->fetch_assoc()) {
                                                echo '<div class="col-lg-6 col-xl-3 col-md-6"><div class="card rounded-card user-card"><div class="card-block">';
                                                echo '<div class="img-hover"><img class="img-fluid img-radius" src="' . htmlspecialchars($r['img']) . '" alt=""></div>';
                                                echo '<div class="user-content"><h4>' . htmlspecialchars($r['fname']) . '</h4>';
                                                echo '<p class="m-b-0 text-muted">VOTES - ' . (int)$r['totalvote'] . '</p>';
                                                echo '<p class="m-b-0 text-muted">' . htmlspecialchars($r['description']) . '</p></div></div></div></div>';
                                            }
                                        } else {
                                            echo '<div class="col-12"><p class="text-muted">No votes recorded yet for your department.</p></div>';
                                        }
                                        ?>
                                    </div>
                                <?php } else {
                                    $rs2 = $conn->query("SELECT * FROM election_title WHERE acad_id = '$acad' AND election_type = 'department' AND is_finished = 1");
                                    if ($rs2 && $rs2->num_rows > 0) {
                                        ?>
                                        <div class="page-header">
                                            <div class="row align-items-end">
                                                <div class="col-lg-12">
                                                    <div class="page-header-title">
                                                        <h4>Department Elected Officers - <?php echo htmlspecialchars($acads); ?></h4>
                                                        <span>Winners for your department</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row users-card">
                                            <?php
                                            $w = "SELECT CONCAT(UPPER(v.lname), ', ', UPPER(v.fname)) AS fname, dp.description,
                                                  (SELECT COUNT(*) FROM department_vote dvo WHERE dvo.candidate_id = dc.dc_id AND dvo.acad_id = dc.acad_id) AS totalvote,
                                                  dp.max_vote, dc.img
                                                  FROM dept_candidate dc
                                                  INNER JOIN voters v ON dc.stud_id = v.stud_id AND dc.acad_id = v.acad_id
                                                  INNER JOIN dept_position dp ON dc.pos_id = dp.dp_id AND dc.acad_id = dp.acad_id
                                                  WHERE dc.acad_id = '$acad' AND dc.department_id = '$dept_id'
                                                  ORDER BY dp.priority ASC, totalvote DESC";
                                            $res = $conn->query($w);
                                            if ($res && $res->num_rows > 0) {
                                                while ($r = $res->fetch_assoc()) {
                                                    echo '<div class="col-lg-6 col-xl-3 col-md-6"><div class="card rounded-card user-card"><div class="card-block">';
                                                    echo '<div class="img-hover"><img class="img-fluid img-radius" src="' . htmlspecialchars($r['img']) . '" alt=""></div>';
                                                    echo '<div class="user-content"><h4>' . htmlspecialchars($r['fname']) . '</h4>';
                                                    echo '<p class="m-b-0 text-muted">VOTES - ' . (int)$r['totalvote'] . '</p>';
                                                    echo '<p class="m-b-0 text-muted">' . htmlspecialchars($r['description']) . '</p></div></div></div></div>';
                                                }
                                            } else {
                                                echo '<p class="col-12">No department winners data yet.</p>';
                                            }
                                            ?>
                                        </div>
                                    <?php } else {
                                        echo '<div class="col-12"><div class="card"><div class="card-block"><p>No department election configured for this year.</p><a href="switch_voting_mode.php?mode=general" class="btn btn-outline-primary">General Voting</a></div></div></div>';
                                    }
                                }
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php
    $flashMsg = '';
    $flashType = 'success';
    if (isset($_SESSION['response']) && $_SESSION['response'] != "") {
        $flashMsg = is_array($_SESSION['response']) ? implode(' ', $_SESSION['response']) : $_SESSION['response'];
        $flashType = isset($_SESSION['type']) ? $_SESSION['type'] : 'success';
        unset($_SESSION['response'], $_SESSION['type']);
    }
    ?>
    <?php
    // Load sync URL for offline voting
    $syncUrl = '';
    $resSync = $conn->query("SHOW TABLES LIKE 'app_settings'");
    if ($resSync && $resSync->num_rows > 0) {
        $cfgRes = $conn->query("SELECT setting_value FROM app_settings WHERE setting_key='ngrok_sync_url' LIMIT 1");
        if ($cfgRes && $cfgRes->num_rows > 0) {
            $cfgRow = $cfgRes->fetch_assoc();
            $syncUrl = trim($cfgRow['setting_value']);
        }
    }
    ?>
    <script>
        window.__voterId = <?php echo json_encode($voter); ?>;
        window.__acadId = <?php echo json_encode($acad); ?>;
        window.__deptId = <?php echo json_encode($dept_id); ?>;
        window.__mode = 'department';
        window.__syncUrl = <?php echo json_encode($syncUrl); ?>;
    </script>
    <script src="js/offline-vote.js"></script>
    <?php include 'nav/script.php'; ?>
    <?php if ($flashMsg !== ''): ?>
    <script>
        $(document).ready(function () {
            var flashMsg = <?php echo json_encode($flashMsg); ?>;
            var flashType = <?php echo json_encode($flashType); ?>;
            $('.theme-loader').fadeOut(200, function () {
                $(this).remove();
                if (flashMsg) {
                    swal({ title: flashMsg, icon: flashType, button: "OK" });
                }

                // After loader, check for pending offline DEPARTMENT vote only
                try {
                    var pending = localStorage.getItem('pending_vote');
                    if (pending && typeof window.syncVote === 'function') {
                        var payload = null;
                        try { payload = JSON.parse(pending); } catch (e) { payload = null; }
                        if (!payload || payload.mode !== 'department') {
                            return;
                        }
                        swal({
                            title: 'Offline vote detected',
                            text: 'You have an unsynced vote on this device. Do you want to sync it now?',
                            icon: 'info',
                            buttons: {
                                cancel: 'Later',
                                confirm: {
                                    text: 'Sync Now',
                                    value: true
                                }
                            }
                        }).then(function (ok) {
                            if (ok) {
                                window.syncVote();
                            }
                        });
                    }
                } catch (e) { }
            });
            $('#btn-sync-offline-dept').on('click', function () {
                try {
                    var pending = localStorage.getItem('pending_vote');
                    var payload = pending ? JSON.parse(pending) : null;
                    if (!payload || payload.mode !== 'department') {
                        swal({ title: 'No offline department vote to sync', icon: 'info', button: 'OK' });
                        return;
                    }
                } catch (e) {
                    swal({ title: 'No offline department vote to sync', icon: 'info', button: 'OK' });
                    return;
                }
                if (typeof window.syncVote === 'function') {
                    window.syncVote();
                }
            });
        });
    </script>
    <?php else: ?>
    <script>
        $(document).ready(function () {
            $('.theme-loader').fadeOut(200, function () {
                $(this).remove();

                // Also check for pending offline DEPARTMENT vote when there is no flash message
                try {
                    var pending = localStorage.getItem('pending_vote');
                    if (pending && typeof window.syncVote === 'function') {
                        var payload = null;
                        try { payload = JSON.parse(pending); } catch (e) { payload = null; }
                        if (!payload || payload.mode !== 'department') {
                            return;
                        }
                        swal({
                            title: 'Offline vote detected',
                            text: 'You have an unsynced vote on this device. Do you want to sync it now?',
                            icon: 'info',
                            buttons: {
                                cancel: 'Later',
                                confirm: {
                                    text: 'Sync Now',
                                    value: true
                                }
                            }
                        }).then(function (ok) {
                            if (ok) {
                                window.syncVote();
                            }
                        });
                    }
                } catch (e) { }
            });
            $('#btn-sync-offline-dept').on('click', function () {
                try {
                    var pending = localStorage.getItem('pending_vote');
                    var payload = pending ? JSON.parse(pending) : null;
                    if (!payload || payload.mode !== 'department') {
                        swal({ title: 'No offline department vote to sync', icon: 'info', button: 'OK' });
                        return;
                    }
                } catch (e) {
                    swal({ title: 'No offline department vote to sync', icon: 'info', button: 'OK' });
                    return;
                }
                if (typeof window.syncVote === 'function') {
                    window.syncVote();
                }
            });
        });
    </script>
    <?php endif; ?>
</body>
</html>
