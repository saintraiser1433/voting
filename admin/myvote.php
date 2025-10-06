<?php
include '../connection.php';
$acad = $_SESSION['acad'];
if (!isset($_GET['gr'])) {
    header("Location:dashboard.php");
}
if (!isset($_SESSION['at'])) {
    header("Location:logout.php");
}



?>

<!DOCTYPE html>
<html lang="en">

<?php include 'nav/header.php'; ?>
<!-- Menu sidebar static layout -->

<body>
    <!-- Pre-loader start -->
    <div class="theme-loader">
        <div class="ball-scale">
            <div class='contain'>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Pre-loader end -->
    <div id="pcoded" class="pcoded">
        <div class="pcoded-overlay-box"></div>
        <div class="pcoded-container navbar-wrapper">

            <?php include 'nav/topbar.php'; ?>

            <div class="pcoded-main-container">
                <div class="pcoded-wrapper">
                    <?php include 'nav/sidebar.php'; ?>
                    <div class="pcoded-content">
                        <div class="pcoded-inner-content">
                            <div class="main-body">
                                <div class="page-wrapper">

                                    <div class="page-body">
                                        <div class="row">

                                            <!-- statustic-card start -->

                                            <div class="col-xl-12 col-md-12">
                                                <div class="card bg-c-green text-white">
                                                    <div class="card-block">
                                                        <div class="row align-items-center">
                                                            <div class="col">

                                                                <?php
                                                                $gr = $_GET['gr'];
                                                                $sec = $_GET['sec'];
                                                                $str = $_GET['str'];
                                                                $sql123 = "SELECT * FROM voters where acad_id='$acad' and grade_level='$gr' and section='$sec' and strand='$str'";
                                                                $rs = $conn->query($sql123);
                                                                $sql12 = "SELECT * FROM voters INNER JOIN vote ON voters.v_id=vote.voter_id where voters.grade_level='$gr' and voters.acad_id='$acad' and voters.section='$sec' and voters.strand='$str' group by voters.section";
                                                                $rs12 = $conn->query($sql12);
                                                                $final = 0;
                                                                $from = $rs->num_rows;
                                                                $to = $rs12->num_rows;
                                                                $final = $to / $from * 100;
                                                                $round = round($final, 2);
                                                                ?>
                                                                <h6 class="m-b-5 mb-2"><b> GRADE LEVEL: GRADE <span><?php echo $gr; ?></span> | SECTION: <span><?php echo $sec; ?></span> <?php if ($str == "undefined") {
                                                                                                                                                                                                echo "";
                                                                                                                                                                                            } else {
                                                                                                                                                                                                echo "| Strand " . "<span>$str</span> ";
                                                                                                                                                                                            } ?> (ELECTION RETURN SUBMITTED) </b></h6>
                                                                <span class="elec bg-primary p-1" style="border-radius: 50px; border:2px solid white"><?php echo $round; ?>%</span>
                                                                <span>- <?php echo $rs12->num_rows ?> of <?php echo $rs->num_rows ?> voters are completely voted</span>
                                                            </div>
                                                            <div class="col col-auto text-right">
                                                                <i class="feather icon-book f-50 text-c-white"></i>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php
                                            $sql = "SELECT * FROM position where acad_id='$acad' ORDER BY priority ASC";
                                            $rt = $conn->query($sql);
                                            while ($row = $rt->fetch_assoc()) {

                                            ?>
                                                <div class="col-xl-6 col-md-12">
                                                    <div class="card">
                                                        <div class="card-header">
                                                            <div class="card-header-left ">
                                                                <h5 class="text-uppercase"><?php echo $row['description'] ?></h5>
                                                                <span class="text-muted">This panel represent the list of candidate in <?php echo $row['description'] ?></span>
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
                                                                    $sql = "SELECT * FROM candidate LEFT JOIN voters ON candidate.stud_id=voters.stud_id LEFT JOIN partylist ON candidate.p_id=partylist.p_id WHERE candidate.pos_id = '" . $row['pos_id'] . "'";
                                                                    $cquery = $conn->query($sql);
                                                                    while ($rowt = $cquery->fetch_assoc()) {
                                                                        if ($rowt['p_id'] == 0) {
                                                                            $pn = "IND";
                                                                        } else {
                                                                            $pn = $rowt['party_name'];
                                                                        }
                                                                        echo '<label class="text-primary text-uppercase">' . $rowt['lname'] . ", " . $rowt['fname'] . " " . $rowt['mname'][0] . ". (" . $pn . ')</label>';

                                                                        $sqltt = "SELECT * FROM vote INNER JOIN voters ON vote.voter_id=voters.v_id WHERE vote.acad_id='$acad' and voters.grade_level='$gr' and voters.section='$sec' and voters.strand='$str' and vote.candidate_id = '" . $rowt['c_id'] . "'";
                                                                        $rsst = $conn->query($sqltt);
                                                                        $sq = "SELECT * FROM voters where voters.acad_id='$acad' and voters.grade_level='$gr' and voters.section='$sec' and voters.strand='$str'";
                                                                        $rpq = $conn->query($sq);
                                                                        $finalt = 0;
                                                                        $from1 = $rsst->num_rows;
                                                                        $to1 = $rpq->num_rows;
                                                                        $finalt = $from1 / $to1 * 100;
                                                                        $roundt = round($finalt);
                                                                        echo '
                                                                    <br><label> ' . $rsst->num_rows . ' - Votes</label>
                                                                    <div class="progress">
                                                                        <div class="progress-bar progress-bar-striped progress-bar-primary" role="progressbar" style="width: ' . $roundt . '%" aria-valuenow="' . $roundt . '" aria-valuemin="0" aria-valuemax="' . $rpq->num_rows . '"></div>
                                                                    </div><br>';
                                                                    }

                                                                    ?>
                                                                </div>

                                                            </div>

                                                        </div>
                                                    </div>
                                                </div>
                                            <?php } ?>



                                            <div class="col-xl-12 col-md-12">
                                                <div class="card">
                                                    <div class="card bg-c-green text-white">
                                                        <div class="card-block">
                                                            <div class="row align-items-center">
                                                                <div class="col">
                                                                    <h3 class="m-b-5"><b>GRADE LEVEL RESULTS</b></h3>
                                                                </div>
                                                                <div class="col col-auto text-right">
                                                                    <i class="feather icon-book f-50 text-c-white"></i>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="card-block-big">
                                                        <ul class="nav nav-tabs  tabs" role="tablist">
                                                            <?php
                                                            $stq = "SELECT * FROM voters where acad_id='$acad' group by grade_level order by grade_level asc";
                                                            $rpq = $conn->query($stq);
                                                            while ($rqq = $rpq->fetch_assoc()) {
                                                            ?>
                                                                <li class="nav-item">
                                                                    <a class="nav-link" data-toggle="tab" href="#<?php echo $rqq['grade_level']; ?>" role="tab">Grade <?php echo $rqq['grade_level']; ?></a>
                                                                </li>

                                                            <?php } ?>
                                                        </ul>
                                                        <!-- Tab panes -->
                                                        <div class="tab-content tabs card-block">
                                                            <?php
                                                            $stq1 = "SELECT * FROM voters where acad_id='$acad' group by grade_level order by grade_level asc";
                                                            $rpq1 = $conn->query($stq1);
                                                            while ($rqq1 = $rpq1->fetch_assoc()) {
                                                                $gr = $rqq1['grade_level'];

                                                                echo ' <div class="tab-pane" id="' . $rqq1['grade_level'] . '" role="tabpanel">';
                                                                $stq = "SELECT * FROM voters where acad_id='$acad' and grade_level='$gr' group by section,strand";
                                                                $ss = $conn->query($stq);
                                                                while ($myrow = $ss->fetch_assoc()) {
                                                                    $sec = $myrow['section'];
                                                                    $str = $myrow['strand'];
                                                                    $sqlt = "SELECT * FROM voters where acad_id='$acad' and grade_level='$gr' and section='$sec' and strand='$str'";
                                                                    $rsqlt = $conn->query($sqlt);
                                                                    $sqlovr = "SELECT * FROM voters INNER JOIN vote ON voters.v_id=vote.voter_id where voters.grade_level='$gr' and voters.acad_id='$acad' and voters.section='$sec' and voters.strand='$str' group by voters.section";
                                                                    $rsqltq = $conn->query($sqlovr);
                                                                    $finalt1 = 0;
                                                                    $from11 = $rsqltq->num_rows;
                                                                    $to11 = $rsqlt->num_rows;
                                                                    $finalt1 = $from11 / $to11 * 100;
                                                                    $roundt2 = round($finalt1);
                                                                    if ($roundt2 == 0) {
                                                                        $me = 0;
                                                                    } else if ($roundt2 >= 0 && $roundt2 <= 10.99) {
                                                                        $me = 10;
                                                                    } else if ($roundt2 >= 11.00 && $roundt2 <= 20.99) {
                                                                        $me = 20;
                                                                    } else if ($roundt2 >= 21.00 && $roundt2 <= 30.99) {
                                                                        $me = 30;
                                                                    } else if ($roundt2 >= 31.00 && $roundt2 <= 40.99) {
                                                                        $me = 40;
                                                                    } else if ($roundt2 >= 41.00 && $roundt2 <= 50.99) {
                                                                        $me = 50;
                                                                    } else if ($roundt2 >= 51.00 && $roundt2 <= 60.99) {
                                                                        $me = 60;
                                                                    } else if ($roundt2 >= 61.00 && $roundt2 <= 70.99) {
                                                                        $me = 70;
                                                                    } else if ($roundt2 >= 71.00 && $roundt2 <= 80.99) {
                                                                        $me = 80;
                                                                    } else if ($roundt2 >= 81.00 && $roundt2 <= 90.99) {
                                                                        $me = 90;
                                                                    } else if ($roundt2 >= 91.00 && $roundt2 <= 100.00) {
                                                                        $me = 100;
                                                                    }


                                                                    echo '
                                                                <div style="font-size:11px;" data-label="' . $roundt2 . '%" class="radial-bar radial-bar-' .  $me . ' radial-bar-md radial-bar-success"><br><br><br><br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <u><a href="" style="margin-top:500px;""><span style="font-size:10px;">' . $myrow['strand'] . " " . $myrow['section'] . '</span></a></u>  </div> 
                                                           
                                                                ';
                                                                }
                                                                echo '
                                                            </div>';
                                                            }
                                                            ?>

                                                            <?php
                                                            $stq1 = "SELECT * FROM voters where acad_id='$acad' group by grade_level order by grade_level asc";
                                                            $rpq1 = $conn->query($stq1);
                                                            if ($rqq1 = $rpq1->fetch_assoc()) {
                                                                $gr = $rqq1['grade_level'];

                                                                echo ' <div class="tab-pane active" id="' . $rqq1['grade_level'] . '" role="tabpanel">';
                                                                $stq = "SELECT * FROM voters where acad_id='$acad' and grade_level='$gr' group by section,strand";
                                                                $ss = $conn->query($stq);
                                                                while ($myrow = $ss->fetch_assoc()) {
                                                                    $sec = $myrow['section'];
                                                                    $str = $myrow['strand'];
                                                                    $sqlt = "SELECT * FROM voters where acad_id='$acad' and grade_level='$gr' and section='$sec' and strand='$str'";
                                                                    $rsqlt = $conn->query($sqlt);
                                                                    $sqlovr = "SELECT * FROM voters INNER JOIN vote ON voters.v_id=vote.voter_id where voters.grade_level='$gr' and voters.acad_id='$acad' and voters.section='$sec' and voters.strand='$str' group by voters.section";
                                                                    $rsqltq = $conn->query($sqlovr);
                                                                    $finalt1 = 0;
                                                                    $from11 = $rsqltq->num_rows;
                                                                    $to11 = $rsqlt->num_rows;
                                                                    $finalt1 = $from11 / $to11 * 100;
                                                                    $roundt2 = round($finalt1);
                                                                    if ($roundt2 == 0) {
                                                                        $me = 0;
                                                                    } else if ($roundt2 >= 0 && $roundt2 <= 10.99) {
                                                                        $me = 10;
                                                                    } else if ($roundt2 >= 11.00 && $roundt2 <= 20.99) {
                                                                        $me = 20;
                                                                    } else if ($roundt2 >= 21.00 && $roundt2 <= 30.99) {
                                                                        $me = 30;
                                                                    } else if ($roundt2 >= 31.00 && $roundt2 <= 40.99) {
                                                                        $me = 40;
                                                                    } else if ($roundt2 >= 41.00 && $roundt2 <= 50.99) {
                                                                        $me = 50;
                                                                    } else if ($roundt2 >= 51.00 && $roundt2 <= 60.99) {
                                                                        $me = 60;
                                                                    } else if ($roundt2 >= 61.00 && $roundt2 <= 70.99) {
                                                                        $me = 70;
                                                                    } else if ($roundt2 >= 71.00 && $roundt2 <= 80.99) {
                                                                        $me = 80;
                                                                    } else if ($roundt2 >= 81.00 && $roundt2 <= 90.99) {
                                                                        $me = 90;
                                                                    } else if ($roundt2 >= 91.00 && $roundt2 <= 100.00) {
                                                                        $me = 100;
                                                                    }


                                                                    echo '
                                                                <div style="font-size:11px;" data-label="' . $roundt2 . '%" class="radial-bar radial-bar-' .  $me . ' radial-bar-md radial-bar-success"><br><br><br><br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <u><a href="myvote.php?gr=' . $gr . '&sec=' . $sec . '&str=' . $str . '" style="margin-top:500px;""><span style="font-size:10px;">' . $myrow['strand'] . " " . $myrow['section'] . '</span></a></u>  </div> 
                                                           
                                                                ';
                                                                }
                                                                echo '
                                                            </div>';
                                                            }
                                                            ?>




                                                        </div>
                                                    </div>
                                                </div>
                                            </div>




                                        </div>



                                        <div id="styleSelector">

                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


    <!-- Required Jquery -->
    <?php include 'nav/script.php'; ?>
    <?php include 'modalelection.php'; ?>
    <script>
        $(document).ready(function() {

        });
    </script>


</body>

</html>