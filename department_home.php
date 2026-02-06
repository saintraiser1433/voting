<?php
include 'connection.php';

if (!isset($_SESSION['v_id']) && !isset($_SESSION['faceverified'])) {
    header("Location:logout.php");
}

$acad = $_SESSION['acad'];
$voter = $_SESSION['v_id'];

// Get acad description
$sel = "SELECT * FROM acad_tbl where acad_id = $acad";
$rs = $conn->query($sel);
$row = $rs->fetch_assoc();
$acads = $row['description'];
?>

<!DOCTYPE html>
<html lang="en">

<?php include 'nav/header.php'; ?>

<body>
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
                                // Use same election flag but department votes are tracked separately
                                $sqlt = "SELECT * FROM election_title where acad_id = '$acad' and is_finished = 0";
                                $rs = $conn->query($sqlt);
                                if ($rs->num_rows > 0) {
                                    ?>
                                    <div class="row">
                                        <div class="col-xl-12 col-md-12">
                                            <div class="card">
                                                <div class="card-header">
                                                    <div class="d-flex justify-content-between align-items-center w-100">
                                                        <div>
                                                            <h5 class="mb-0 text-uppercase">Department Voting</h5>
                                                            <small class="text-muted">Vote for your department-level candidates</small>
                                                        </div>
                                                        <div>
                                                            <a href="home.php" class="btn btn-outline-primary btn-sm">
                                                                General Voting
                                                            </a>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="card-block-big">
                                                    <div class="row">
                                                        <div class="col-lg-5">
                                                            <img src="libraries/img/glanlogo.png" class="img-fluid"
                                                                style="width:250px; height:250px;">
                                                        </div>
                                                        <div class="col-lg-7 justify-content-lg-center">
                                                            <h2>DEPARTMENT ELECTION</h2>
                                                            <span class="text-muted">Session Year:
                                                                <?php echo $acads; ?>
                                                            </span>
                                                            <br><br>
                                                            <?php
                                                            // Check if voter already cast department ballot
                                                            $check = "SELECT * FROM department_vote WHERE acad_id='$acad' AND voter_id='$voter'";
                                                            $rsVote = $conn->query($check);
                                                            if ($rsVote->num_rows > 0) {
                                                                echo '<span class="text-center font-weight-bold">You have already voted for your department election.</span><br><br>';
                                                            } else {
                                                                echo '<span class="text-center font-weight-bold">Please click \"Start Button\" to begin department vote!</span><br><br>
                                                                <a href=\"department_ballot.php\" class=\"btn btn-success\"><i class=\"fa fa-arrow-right\"></i> Start!</a>';
                                                            }
                                                            ?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?php } else { ?>
                                    <div class="page-header">
                                        <div class="row align-items-end">
                                            <div class="col-lg-12">
                                                <div class="page-header-title">
                                                    <div class="d-inline">
                                                        <h4>DEPARTMENT ELECTED OFFICERS FOR ACADEMIC YEAR :
                                                            <?php echo $acads ?>
                                                        </h4>
                                                        <span>This is the list of department elected officers</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="row users-card">
                                        <?php
                                        // Winners based on department_vote
                                        $sqlt = "SELECT
                                                CONCAT(
                                                    UPPER(v.lname),
                                                    ', ',
                                                    UPPER(v.fname)
                                                ) AS fname,
                                                pos.description,
                                                vt.totalvote,
                                                pos.max_vote,
                                                c.img
                                            FROM
                                                candidate c
                                            INNER JOIN voters v ON
                                                c.stud_id = v.stud_id
                                            INNER JOIN POSITION pos ON
                                                c.pos_id = pos.pos_id
                                            INNER JOIN election_title et ON
                                                et.acad_id = c.acad_id
                                            LEFT JOIN(
                                                SELECT candidate_id,
                                                    COUNT(DISTINCT voter_id) AS totalvote
                                                FROM
                                                    department_vote
                                                GROUP BY
                                                    candidate_id
                                            ) vt
                                            ON
                                                vt.candidate_id = c.c_id
                                            WHERE
                                                c.acad_id = $acad
                                                AND c.election_type = 'department'
                                                AND (
                                                    SELECT COUNT(*)
                                                    FROM candidate c2
                                                    LEFT JOIN(
                                                        SELECT candidate_id,
                                                            COUNT(DISTINCT voter_id) AS totalvote
                                                        FROM department_vote
                                                        GROUP BY candidate_id
                                                    ) vt2
                                                    ON vt2.candidate_id = c2.c_id
                                                    WHERE c2.pos_id = c.pos_id AND vt2.totalvote > vt.totalvote
                                                ) < pos.max_vote
                                                AND et.is_finished = 1
                                            ORDER BY
                                                pos.priority ASC,
                                                vt.totalvote DESC";
                                        $rs = $conn->query($sqlt);
                                        if ($rs && $rs->num_rows > 0) {
                                            foreach ($rs as $row) { ?>
                                                <div class="col-lg-6 col-xl-3 col-md-6">
                                                    <div class="card rounded-card user-card">
                                                        <div class="card-block">
                                                            <div class="img-hover">
                                                                <img class="img-fluid img-radius" src="<?php echo $row['img'] ?>"
                                                                    alt="round-img">
                                                            </div>
                                                            <div class="user-content">
                                                                <h4 class=""><?php echo $row['fname'] ?></h4>
                                                                <p class="m-b-0 text-muted text-capitalize">
                                                                    VOTES - <?php echo $row['totalvote'] ?>
                                                                </p>
                                                                <p class="m-b-0 text-muted text-capitalize">
                                                                    <?php echo $row['description'] ?>
                                                                </p>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php }
                                        }
                                        ?>
                                    </div>
                                <?php } ?>
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

