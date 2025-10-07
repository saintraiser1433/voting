<?php
include 'connection.php';

$acad = $_SESSION['acad'];
$voter = $_SESSION['v_id'];
if (!isset($_SESSION['v_id']) && !isset($_SESSION['faceverified'])) {
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

                    <div class="main-body">
                        <div class="page-wrapper">

                            <div class="page-body">
                                <?php
                                $sqlt = "SELECT * FROM election_title where acad_id = '$acad' and is_finished = 0";
                                $rs = $conn->query($sqlt);
                                if ($rs->num_rows > 0) {
                                    ?>
                                    <div class="row">

                                        <!-- statustic-card start -->
                                        <div class="col-xl-12 col-md-12">
                                            <div class="card">
                                                <div class="card-header">

                                                    <div class="card-header-right">
                                                        <ul class="list-unstyled card-option">
                                                            <li><i class="feather icon-maximize full-card"></i></li>

                                                        </ul>
                                                    </div>
                                                </div>
                                                <div class="card-block-big">
                                                    <div class="row">

                                                        <div class="col-lg-5">
                                                            <img src="libraries/img/glanlogo.png" class="img-fluid"
                                                                style="width:250px; height:250px;">
                                                        </div>
                                                        <div class="col-lg-7 justify-content-lg-center">
                                                            <h2>TITLE:
                                                                <?php
                                                                $sql = "SELECT * FROM election_title where acad_id='$acad'";
                                                                $rs = $conn->query($sql);
                                                                $row = $rs->fetch_assoc();
                                                                if ($rs->num_rows > 0) {
                                                                    echo $row['title'];
                                                                } else {
                                                                    echo "Not set";
                                                                }

                                                                ?>

                                                            </h2>
                                                            <span class="text-muted">Session Year: <?php
                                                            $sql = "SELECT * FROM acad_tbl where acad_id='$acad'";
                                                            $rs = $conn->query($sql);
                                                            $row = $rs->fetch_assoc();
                                                            if ($rs->num_rows > 0) {
                                                                echo $row['description'];
                                                            } else {
                                                                echo "Not set";
                                                            }

                                                            ?></span><br><br>
                                                            <?php
                                                            $sql = "SELECT * FROM vote where acad_id='$acad' and voter_id='$voter'";
                                                            $rs = $conn->query($sql);
                                                            if ($rs->num_rows > 0) {
                                                                echo '  <span class="text-center font-weight-bold">You have already voted for this election.</span><br><br>';

                                                            } else {
                                                                echo '  <span class="text-center font-weight-bold">Please click "Start Button" to begin vote!</span><br><br>
                                                            <a href="ballot.php" class="btn btn-success"><i class="fa fa-arrow-right"></i> Start!</a>';
                                                            }
                                                            ?>

                                                        </div>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>

                                    </div>

                                    <h1 class="text-center mb-5">LIST OF PARTYLIST</h1>


                                    <div class="row">
                                        <?php
                                        $sql = "SELECT * FROM partylist where acad_id='$acad'";
                                        $rs = $conn->query($sql);
                                        while ($row = $rs->fetch_assoc()) {
                                            echo '
                                        <div class="col-xl-6 col-md-6">
                                        <div class="card">
                                            <div class="card bg-c-green text-white">
                                                <div class="card-block">
                                                    <div class="row align-items-center">
                                                        <div class="col">
                                                            <h3 class="m-b-5 text-uppercase"><b>' . $row['party_name'] . '</b></h3>
                                                        </div>
                                                        <div class="col col-auto text-right">
                                                            <i class="feather icon-check-circle f-50 text-c-white"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-block-big">
                                                <div class="row justify-content-center">
                                                    <img src="' . $row['img'] . '" class="img-fluid rounded-circle" style="width:250px; height:250px;">

                                                </div><br>
                                                <hr>
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <label class="col-form-label font-weight-bold">PLATFORM:</label>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <a href="#platform1" class="plat badge badge-success p-2" data-id="' . $row['p_id'] . '"><i class="fa fa-book"></i> View</a>
                                                    </div>
                                                </div>
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <label class="col-form-label font-weight-bold">MEMBERS:</label>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <a href="#members" class="member badge badge-primary p-2" data-id="' . $row['p_id'] . '"><i class="fa fa-user"></i> View</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                        ';
                                        }
                                        ?>
                                    </div>

                                    <h1 class="text-center mb-5">INDEPENDENT CANDIDATES</h1>
                                    <div class="row">
                                        <?php
                                        $sql = "SELECT * FROM candidate LEFT JOIN voters ON candidate.stud_id=voters.stud_id LEFT JOIN position ON candidate.pos_id=position.pos_id where candidate.acad_id='$acad' and candidate.p_id='0' order by position.priority ASC";
                                        $rs = $conn->query($sql);
                                        while ($row = $rs->fetch_assoc()) {
                                            echo '
                                        <div class="col-xl-6 col-md-6">
                                        <div class="card">
                                            <div class="card bg-c-green text-white">
                                                <div class="card-block">
                                                    <div class="row align-items-center">
                                                        <div class="col">
                                                            <h3 class="m-b-5 text-uppercase"><b>' . $row['lname'] . ', ' . $row['fname'] . ' ' . $row['mname'][0] . '</b></h3>
                                                        </div>
                                                        <div class="col col-auto text-right">
                                                            <i class="feather icon-check-circle f-50 text-c-white"></i>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="card-block-big">
                                                <div class="row justify-content-center">
                                                    <img src="' . $row['img'] . '" class="img-fluid rounded-circle" style="width:250px; height:250px;">

                                                </div><br>
                                                <hr>
                                                <div class="row">
                                             
                                                <div class="col-lg-6">
                                                    <label class="col-form-label font-weight-bold">RUNNING AS:</label>
                                                </div>
                                                <div class="col-lg-6">
                                                   <label class="text-uppercase">' . $row['description'] . '</label>
                                                </div>
                                        
                                            </div>
                                                <div class="row">
                                                    <div class="col-lg-6">
                                                        <label class="col-form-label font-weight-bold">PLATFORM:</label>
                                                    </div>
                                                    <div class="col-lg-6">
                                                        <a href="#platform1" class="myplat badge badge-success p-2" data-id="' . $row['c_id'] . '"><i class="fa fa-book"></i> View</a>
                                                    </div>
                                                </div>
                                            
                                            </div>
                                        </div>
                                    </div>
                                        ';
                                        }
                                        ?>
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
                                } ?>

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
    <?php include 'modalplatform.php'; ?>

    <script>
        $(document).ready(function () {
            $(document).on('click', '.plat', function (e) {
                $('#platform1').modal('show');
                var id = $(this).data('id');
                $.ajax({
                    method: "POST",
                    url: "ajax/previewplatform.php",
                    data: {
                        myids: id,
                    },
                    dataType: "text",
                    success: function (html) {
                        $('#see').html(html);


                    }

                });

            });

            $(document).on('click', '.myplat', function (e) {
                $('#platform1').modal('show');
                var id = $(this).data('id');
                $.ajax({
                    method: "POST",
                    url: "ajax/previewplatind.php",
                    data: {
                        myids: id,
                    },
                    dataType: "text",
                    success: function (html) {
                        $('#see').html(html);


                    }

                });

            });

            $(document).on('click', '.member', function (e) {
                $('#members').modal('show');
                var id = $(this).data('id');
                $.ajax({
                    method: "POST",
                    url: "ajax/previewmember.php",
                    data: {
                        myids: id,
                    },
                    dataType: "text",
                    success: function (html) {
                        $('#member2').html(html);


                    }

                });

            });
        });


    </script>


</body>

</html>