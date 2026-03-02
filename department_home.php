<?php
include 'connection.php';

$acad = $_SESSION['acad'];
$voter = isset($_SESSION['v_id']) ? (int) $_SESSION['v_id'] : 0;
$voting_mode = isset($_SESSION['voting_mode']) ? $_SESSION['voting_mode'] : '';
$dept_id = isset($_SESSION['dept_id']) ? (int) $_SESSION['dept_id'] : 0;

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
                                <?php
                                $sqlt = "SELECT * FROM election_title WHERE acad_id = '$acad' AND election_type = 'department' AND is_finished = 0";
                                $rs = $conn->query($sqlt);
                                if ($rs && $rs->num_rows > 0) {
                                    $et = $rs->fetch_assoc();
                                    ?>
                                    <div class="row">
                                        <div class="col-xl-12 col-md-12">
                                            <div class="card">
                                                <div class="card-header d-flex justify-content-between align-items-center">
                                                    <h5 class="mb-0 text-uppercase">Department Voting</h5>
                                                    <a href="home.php" class="btn btn-outline-primary btn-sm">General Voting</a>
                                                </div>
                                                <div class="card-block-big">
                                                    <div class="row">
                                                        <div class="col-lg-5">
                                                            <img src="libraries/img/glanlogo.png" class="img-fluid" style="width:250px; height:250px;">
                                                        </div>
                                                        <div class="col-lg-7 justify-content-lg-center">
                                                            <h2>Department Election</h2>
                                                            <span class="text-muted">Session Year: <?php echo htmlspecialchars($acads); ?></span><br><br>
                                                            <?php
                                                            $chk = $conn->query("SELECT 1 FROM department_vote WHERE acad_id='$acad' AND voter_id='$voter'");
                                                            if ($chk && $chk->num_rows > 0) {
                                                                echo '<span class="text-center font-weight-bold">You have already voted for this department election.</span><br><br>';
                                                            } else {
                                                                echo '<span class="text-center font-weight-bold">Please click "Start" to begin department vote.</span><br><br>';
                                                                echo '<a href="department_ballot.php" class="btn btn-success"><i class="fa fa-arrow-right"></i> Start</a>';
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
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
                                            $w = "SELECT CONCAT(UPPER(dv.lname), ', ', UPPER(dv.fname)) AS fname, dp.description,
                                                  (SELECT COUNT(*) FROM department_vote dvo WHERE dvo.candidate_id = dc.dc_id AND dvo.acad_id = dc.acad_id) AS totalvote,
                                                  dp.max_vote, dc.img
                                                  FROM dept_candidate dc
                                                  INNER JOIN dept_voters dv ON dc.stud_id = dv.stud_id AND dc.acad_id = dv.acad_id
                                                  INNER JOIN dept_position dp ON dc.pos_id = dp.dp_id AND dc.acad_id = dp.acad_id
                                                  WHERE dc.acad_id = '$acad' AND dc.department_id = '$dept_id'
                                                  ORDER BY dp.priority ASC, totalvote DESC";
                                            $res = $conn->query($w);
                                            if ($res && $res->num_rows > 0) {
                                                while ($r = $res->fetch_assoc()) {
                                                    echo '<div class="col-lg-6 col-xl-3 col-md-6"><div class="card rounded-card user-card"><div class="card-block">';
                                                    echo '<div class="img-hover"><img class="img-fluid img-radius" src="../' . htmlspecialchars($r['img']) . '" alt=""></div>';
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
                                        echo '<div class="col-12"><div class="card"><div class="card-block"><p>No department election configured for this year.</p><a href="home.php" class="btn btn-outline-primary">General Voting</a></div></div></div>';
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
    <?php include 'nav/script.php'; ?>
</body>
</html>
