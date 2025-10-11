<?php
include '../connection.php';
$acad = $_SESSION['acad'];
if (!isset($_SESSION['at'])) {
    header("Location:logout.php");
}

$sel = "SELECT * FROM acad_tbl where acad_id = $acad";
$rs = $conn->query($sel);
$row = $rs->fetch_assoc();
$acads = $row['description'];


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
                                            <?php

                                            $sql = "SELECT * FROM election_title where acad_id = '$acad' and is_finished = 0";
                                            $rss = $conn->query($sql);
                                            if ($rss->num_rows > 0) { ?>
                                                <div id="ps"></div>
                                                <div class="col-xl-12 col-md-12">
                                                    <div class="card">
                                                        <div class="card bg-c-green text-white">
                                                            <div class="card-block">
                                                                <div class="row align-items-center">
                                                                    <div class="col">
                                                                        <h3 class="m-b-5"><b>YEAR LEVEL RESULTS</b></h3>
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
                                                                        <a class="nav-link" data-toggle="tab"
                                                                            href="#<?php echo $rqq['grade_level']; ?>"
                                                                            role="tab">Year
                                                                            <?php echo $rqq['grade_level']; ?></a>
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
                                                                    $stq = "SELECT * FROM voters where  acad_id='$acad' and grade_level='$gr' group by section,strand";
                                                                    $ss = $conn->query($stq);
                                                                    while ($myrow = $ss->fetch_assoc()) {
                                                                        $sec = $myrow['section'];
                                                                        $str = $myrow['strand'];
                                                                        $sqlt = "SELECT * FROM voters where acad_id='$acad' and grade_level='$gr' and section='$sec' and strand='$str'";
                                                                        $rsqlt = $conn->query($sqlt);
                                                                        $sqlovr = "SELECT * FROM voters INNER JOIN vote ON voters.v_id=vote.voter_id where voters.grade_level='$gr' and voters.acad_id='$acad' and voters.section='$sec' and voters.strand='$str' group by voters.section,voters.strand";
                                                                        $rsqltq = $conn->query($sqlovr);
                                                                        $finalt1 = 0;
                                                                        $from11 = $rsqltq->num_rows;
                                                                        $to11 = $rsqlt->num_rows;
                                                                        $finalt1 = $from11 / $to11 * 100;

                                                                        $roundt2 = round($finalt1, 2);
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
                                                                <div style="font-size:11px;" data-label="' . $roundt2 . '%" class="radial-bar radial-bar-' . $me . ' radial-bar-md radial-bar-success"><br><br><br><br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <u><a href="myvote.php?gr=' . $gr . '&sec=' . $sec . '&str=' . $str . '" style="margin-top:500px;""><span style="font-size:11px;">' . $myrow['strand'] . " " . $myrow['section'] . '</span></a></u>  </div> 
                                                           
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
                                                                    $stq = "SELECT * FROM voters where  acad_id='$acad' and  grade_level='$gr' group by section,strand";
                                                                    $ss = $conn->query($stq);
                                                                    while ($myrow = $ss->fetch_assoc()) {
                                                                        $sec = $myrow['section'];
                                                                        $str = $myrow['strand'];
                                                                        $sqlt = "SELECT * FROM voters where acad_id='$acad' and  grade_level='$gr' and section='$sec' and strand='$str'";
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
                                                                <div style="font-size:11px;" data-label="' . $roundt2 . '%" class="radial-bar radial-bar-' . $me . ' radial-bar-md radial-bar-success"><br><br><br><br><br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; <u><a href="myvote.php?gr=' . $gr . '&sec=' . $sec . '&str=' . $str . '" style="margin-top:500px;""><span style="font-size:10px;">' . $myrow['strand'] . " " . $myrow['section'] . '</span></a></u>  </div> 
                                                           
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
                                            <?php } else { ?>
                                                <div class="page-header">
                                                    <div class="row align-items-end">
                                                        <div class="col-lg-12">
                                                            <div class="page-header-title">
                                                                <div class="d-inline">
                                                                    <h4>ELECTED OFFICERS FOR ACADEMIC YEAR :
                                                                        <?php echo $acads ?>
                                                                    </h4>
                                                                    <span>This is the list of elected officers</span>
                                                                </div>
                                                            </div>
                                                        </div>


                                                    </div>
                                                </div>
                                                <div class="row users-card">
                                                    <?php

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
                                            INNER JOIN partylist p ON
                                                c.p_id = p.p_id
                                            INNER JOIN POSITION pos ON
                                                c.pos_id = pos.pos_id
                                            INNER JOIN election_title et ON
                                                et.acad_id = c.acad_id

                                            LEFT JOIN(
                                                SELECT candidate_id,
                                                    COUNT(DISTINCT voter_id) AS totalvote
                                                FROM
                                                    vote
                                                GROUP BY
                                                    candidate_id
                                            ) vt
                                            ON
                                                vt.candidate_id = c.c_id
                                            WHERE
                                                c.acad_id = $acad AND(
                                                SELECT
                                                    COUNT(*)
                                                FROM
                                                    candidate c2
                                                LEFT JOIN(
                                                    SELECT candidate_id,
                                                        COUNT(DISTINCT voter_id) AS totalvote
                                                    FROM
                                                        vote
                                                    GROUP BY
                                                        candidate_id
                                                ) vt2
                                            ON
                                                vt2.candidate_id = c2.c_id
                                            WHERE
                                                c2.pos_id = c.pos_id AND vt2.totalvote > vt.totalvote
                                            ) < pos.max_vote
                                            and et.is_finished = 1
                                            ORDER BY
                                                pos.priority ASC,
                                                vt.totalvote
                                            DESC";
                                                    $rs = $conn->query($sqlt);
                                                    if ($rs->num_rows > 0) { ?>

                                                        <?php foreach ($rs as $row) { ?>
                                                            <div class="col-lg-6 col-xl-3 col-md-6">
                                                                <div class="card rounded-card user-card">
                                                                    <div class="card-block">
                                                                        <div class="img-hover">
                                                                            <img class="img-fluid img-radius"
                                                                                src="../<?php echo $row['img'] ?>" alt="round-img">
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
                                            }
                                            ?>








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

        <?php
        if (isset($_SESSION['response']) && $_SESSION['response'] != "") {

            ?>
            <script>
                swal({
                    title: "<?php echo $_SESSION['response']; ?>",
                    icon: "<?php echo $_SESSION['type']; ?>",
                    button: "Exit!",
                })
            </script>
            <?php unset($_SESSION['response']);
        }
        ?>
        <script>
            $(document).ready(function () {
                setInterval(() => {
                    $.ajax({
                        url: "ajax/fetchtally.php",
                        success: function (datas) {
                            $('#ps').html(datas);
                        }
                    });

                }, 1000);

                setInterval(() => {
                    $.ajax({
                        url: "ajax/checkYear.php",
                        success: function (datas) {

                        }
                    });

                }, 1000);


                function getData(id) {
                    $.ajax({
                        method: 'GET',
                        data: {
                            acad: id
                        },
                        url: "ajax/fetchresult.php",
                        success: function (datas) {
                            $('#red').html(datas);
                        }
                    });
                }

                getData('<?php echo $acad ?>')
            });


        </script>


</body>

</html>