<?php
include '../connection.php';
$acad = $_SESSION['acad'];
if (!isset($_SESSION['at'])) {
    header("Location:logout.php");
}

$admin_mode = isset($_SESSION['admin_mode']) ? $_SESSION['admin_mode'] : 'general';
$sel = "SELECT * FROM acad_tbl where acad_id = $acad";
$rs = $conn->query($sel);
$row = ($rs && $rs->num_rows > 0) ? $rs->fetch_assoc() : null;
$acads = $row ? $row['description'] : '';


?>

<!DOCTYPE html>
<html lang="en">

<?php include 'nav/header.php'; ?>
<link rel="stylesheet" href="assets/css/dashboard-responsive.css">
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
                                <div class="page-wrapper dashboard-page">

                                    <div class="page-body">
                                        <div class="row">

                                            <!-- statustic-card start -->
                                            <?php
                                            // Separate dashboards: general vs department
                                            if ($admin_mode === 'department') {
                                                // Department voting dashboard (per-department results)
                                                include 'department_dashboard_block.php';
                                            } else {
                                                // General voting dashboard (year-level analytics + officers/results)
                                                // Get current election for this acad + mode (open or closed)
                                                $sql = "SELECT * FROM election_title WHERE acad_id = '$acad' AND (election_type = '" . $conn->real_escape_string($admin_mode) . "' OR election_type IS NULL) ORDER BY id DESC LIMIT 1";
                                                $rss = $conn->query($sql);
                                                if (!$rss && strpos($conn->error, 'Unknown column') !== false) {
                                                    $sql = "SELECT * FROM election_title WHERE acad_id = '$acad' ORDER BY id DESC LIMIT 1";
                                                    $rss = $conn->query($sql);
                                                }
                                                $election_row = ($rss && $rss->num_rows > 0) ? $rss->fetch_assoc() : null;
                                                // Election is "open" when is_finished is 0, '0', or missing (default to open)
                                                $fin = isset($election_row['is_finished']) ? $election_row['is_finished'] : 0;
                                                $election_open = $election_row && ((int)$fin === 0);
                                                if ($election_row && $election_open) {
                                                $date_start = isset($election_row['date_start']) ? $election_row['date_start'] : null;
                                                $date_end = isset($election_row['date_end']) ? $election_row['date_end'] : null;
                                                $label = 'General voting';
                                                include 'includes/election_running_time.php';
                                                ?>
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
                                                                while ($rpq && ($rqq = $rpq->fetch_assoc())) {
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
                                                                while ($rpq1 && ($rqq1 = $rpq1->fetch_assoc())) {
                                                                    $gr = $rqq1['grade_level'];

                                                                    echo ' <div class="tab-pane" id="' . $rqq1['grade_level'] . '" role="tabpanel">';
                                                                    $stq = "SELECT * FROM voters where  acad_id='$acad' and grade_level='$gr' group by section,strand";
                                                                    $ss = $conn->query($stq);
                                                                    while ($ss && ($myrow = $ss->fetch_assoc())) {
                                                                        $sec = $myrow['section'];
                                                                        $str = $myrow['strand'];
                                                                        $sqlt = "SELECT * FROM voters where acad_id='$acad' and grade_level='$gr' and section='$sec' and strand='$str'";
                                                                        $rsqlt = $conn->query($sqlt);
                                                                        $sqlovr = "SELECT * FROM voters INNER JOIN vote ON voters.v_id=vote.voter_id where voters.grade_level='$gr' and voters.acad_id='$acad' and voters.section='$sec' and voters.strand='$str' group by voters.section,voters.strand";
                                                                        $rsqltq = $conn->query($sqlovr);
                                                                        $finalt1 = 0;
                                                                        $from11 = ($rsqltq && $rsqltq->num_rows > 0) ? $rsqltq->num_rows : 0;
                                                                        $to11 = ($rsqlt && $rsqlt->num_rows > 0) ? $rsqlt->num_rows : 1;
                                                                        $finalt1 = $to11 > 0 ? ($from11 / $to11 * 100) : 0;

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
                                                                if ($rpq1 && ($rqq1 = $rpq1->fetch_assoc())) {
                                                                    $gr = $rqq1['grade_level'];

                                                                    echo ' <div class="tab-pane active" id="' . $rqq1['grade_level'] . '" role="tabpanel">';
                                                                    $stq = "SELECT * FROM voters where  acad_id='$acad' and  grade_level='$gr' group by section,strand";
                                                                    $ss = $conn->query($stq);
                                                                    while ($ss && ($myrow = $ss->fetch_assoc())) {
                                                                        $sec = $myrow['section'];
                                                                        $str = $myrow['strand'];
                                                                        $sqlt = "SELECT * FROM voters where acad_id='$acad' and  grade_level='$gr' and section='$sec' and strand='$str'";
                                                                        $rsqlt = $conn->query($sqlt);
                                                                        $sqlovr = "SELECT * FROM voters INNER JOIN vote ON voters.v_id=vote.voter_id where voters.grade_level='$gr' and voters.acad_id='$acad' and voters.section='$sec' and voters.strand='$str' group by voters.section";
                                                                        $rsqltq = $conn->query($sqlovr);
                                                                        $finalt1 = 0;
                                                                        $from11 = ($rsqltq && $rsqltq->num_rows > 0) ? $rsqltq->num_rows : 0;
                                                                        $to11 = ($rsqlt && $rsqlt->num_rows > 0) ? $rsqlt->num_rows : 1;
                                                                        $finalt1 = $to11 > 0 ? ($from11 / $to11 * 100) : 0;
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
                                                <div class="col-12 dashboard-officers-wrap">
                                                <div class="page-header">
                                                    <div class="row align-items-end">
                                                        <div class="col-lg-12">
                                                            <div class="page-header-title">
                                                                <div class="d-inline">
                                                                    <h4>OFFICERS / RESULTS FOR ACADEMIC YEAR :
                                                                        <?php echo $acads ?>
                                                                    </h4>
                                                                    <span><?php echo $election_row ? 'Elected officers (election ended)' : 'No election configured for this year. Open Election Settings to set up.'; ?></span>
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
                                                <div class="row users-card">
                                                    <?php
                                                    if (!$election_row) { ?>
                                                        <div class="col-12"><p class="text-muted">Configure the election title and open voting from <strong>Election Settings</strong> to see live results here.</p></div>
                                                    <?php } else {
                                                    $type_esc = $conn->real_escape_string($admin_mode);
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
                                                c.acad_id = $acad
                                            AND (et.election_type = '$type_esc' OR et.election_type IS NULL)
                                            AND (
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
                                                    if (!$rs && strpos($conn->error, 'Unknown column') !== false) {
                                                        $sqlt = "SELECT CONCAT(UPPER(v.lname), ', ', UPPER(v.fname)) AS fname, pos.description, vt.totalvote, pos.max_vote, c.img FROM candidate c INNER JOIN voters v ON c.stud_id = v.stud_id INNER JOIN partylist p ON c.p_id = p.p_id INNER JOIN POSITION pos ON c.pos_id = pos.pos_id INNER JOIN election_title et ON et.acad_id = c.acad_id LEFT JOIN (SELECT candidate_id, COUNT(DISTINCT voter_id) AS totalvote FROM vote GROUP BY candidate_id) vt ON vt.candidate_id = c.c_id WHERE c.acad_id = $acad AND et.is_finished = 1 AND (SELECT COUNT(*) FROM candidate c2 LEFT JOIN (SELECT candidate_id, COUNT(DISTINCT voter_id) AS totalvote FROM vote GROUP BY candidate_id) vt2 ON vt2.candidate_id = c2.c_id WHERE c2.pos_id = c.pos_id AND vt2.totalvote > vt.totalvote) < pos.max_vote ORDER BY pos.priority ASC, vt.totalvote DESC";
                                                        $rs = $conn->query($sqlt);
                                                    }
                                                    if ($rs && $rs->num_rows > 0) { ?>

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
                                                </div>
                                            <?php }
                                            } // end admin_mode branch
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
                $('.theme-loader').fadeOut(400, function(){ $(this).remove(); });
                var deptId = typeof window.__deptDashboardDeptId !== 'undefined' ? window.__deptDashboardDeptId : 0;
                if (deptId) {
                    setInterval(function () {
                        $.ajax({
                            url: "ajax/fetchtally_dept.php?dept_id=" + deptId,
                            success: function (datas) {
                                $('#ps-dept').html(datas);
                            }
                        });
                    }, 2000);
                } else {
                    setInterval(function () {
                        $.ajax({
                            url: "ajax/fetchtally.php",
                            success: function (datas) {
                                $('#ps').html(datas);
                            }
                        });
                    }, 1000);
                }

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

                getData('<?php echo $acad ?>');

                function pad2(n) { return String(n).padStart(2, '0'); }
                function toLocalDatetimeValue(d) {
                    return d.getFullYear() + '-' + pad2(d.getMonth() + 1) + '-' + pad2(d.getDate()) + 'T' + pad2(d.getHours()) + ':' + pad2(d.getMinutes());
                }
                function parseLocalDatetimeValue(v) {
                    if (!v) return null;
                    var parts = v.split(/[-T:]/);
                    if (parts.length < 5) return null;
                    var y = parseInt(parts[0], 10);
                    var m = parseInt(parts[1], 10) - 1;
                    var day = parseInt(parts[2], 10);
                    var h = parseInt(parts[3], 10);
                    var mi = parseInt(parts[4], 10);
                    return new Date(y, m, day, h, mi, 0, 0);
                }
                function highlightReopenEndDate() {
                    var endInput = $('#mytitleq input[name="date_end"]');
                    if (!endInput.length) return;
                    endInput.css({
                        border: '2px solid #28a745',
                        boxShadow: '0 0 0 0.2rem rgba(40,167,69,.25)'
                    });
                    endInput[0].focus();
                }
                function clearReopenEndHighlight() {
                    $('#mytitleq input[name="date_end"]').css({ border: '', boxShadow: '' });
                }

                var reopenElectionFlowActive = false;

                $('#mytitleq').on('show.bs.modal', function () {
                    if (!reopenElectionFlowActive) {
                        $('#reopen_flow').val('0');
                    } else {
                        $('#reopen_flow').val('1');
                    }
                });
                $('#mytitleq').on('hidden.bs.modal', function () {
                    reopenElectionFlowActive = false;
                    $('#reopen_flow').val('0');
                    clearReopenEndHighlight();
                    $('#reopenElectionBtn').prop('disabled', false).text('Reopen election (voting ongoing)');
                });

                // Opens Election Settings first; user sets a future end date and clicks Save changes to reopen voting.
                $('#reopenElectionBtn').on('click', function () {
                    reopenElectionFlowActive = true;
                    $('#reopen_flow').val('1');
                    $('#mytitleq').modal('show');

                    var endInput = $('#mytitleq input[name="date_end"]');
                    if (endInput.length) {
                        var currentVal = endInput.val();
                        var endDate = parseLocalDatetimeValue(currentVal);
                        var now = new Date();
                        var target = new Date(now.getTime() + (60 * 60 * 1000));
                        if (!endDate || endDate.getTime() <= now.getTime()) {
                            endInput.val(toLocalDatetimeValue(target));
                        }
                    }

                    $('#mytitleq').one('shown.bs.modal', function () {
                        highlightReopenEndDate();
                    });
                });
            });


        </script>


</body>

</html>