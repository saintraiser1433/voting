<?php include '../../connection.php';
include '../nav/header.php';

$acad = $_SESSION['acad'];

?>
<div class="row">
    <div class="col-xl-3 col-md-6">
        <div class="card bg-c-yellow text-white">
            <div class="card-block">
                <div class="row align-items-center">
                    <div class="col">
                        <p class="m-b-5">Total Partylist</p>
                        <h4 class="m-b-0">
                            <?php
                            $sql = "SELECT * FROM partylist where acad_id='$acad'";
                            $rs = $conn->query($sql);
                            echo $rs->num_rows;


                            ?>
                        </h4>
                    </div>
                    <div class="col col-auto text-right">
                        <i class="feather icon-user f-50 text-c-yellow"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card bg-c-green text-white">
            <div class="card-block">
                <div class="row align-items-center">
                    <div class="col">
                        <p class="m-b-5">Total Candidates</p>
                        <h4 class="m-b-0">
                            <?php
                            $sql = "SELECT * FROM  candidate where acad_id='$acad'";
                            $rs = $conn->query($sql);
                            echo $rs->num_rows;


                            ?>
                        </h4>
                    </div>
                    <div class="col col-auto text-right">
                        <i class="feather icon-list f-50 text-c-green"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card bg-c-pink text-white">
            <div class="card-block">
                <div class="row align-items-center">
                    <div class="col">
                        <p class="m-b-5">Total Voted</p>
                        <h4 class="m-b-0">
                            <?php
                            $sql = "SELECT DISTINCT voter_id FROM vote where acad_id='$acad'";
                            $rs = $conn->query($sql);
                            echo $rs->num_rows;


                            ?>
                        </h4>
                    </div>
                    <div class="col col-auto text-right">
                        <i class="feather icon-book f-50 text-c-pink"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-3 col-md-6">
        <div class="card bg-c-blue text-white">
            <div class="card-block">
                <div class="row align-items-center">
                    <div class="col">
                        <p class="m-b-5">Total Voters</p>
                        <h4 class="m-b-0">
                            <?php
                            $sql = "SELECT DISTINCT v_id FROM voters where acad_id='$acad'";
                            $rs = $conn->query($sql);
                            echo $rs->num_rows;


                            ?>

                        </h4>
                    </div>
                    <div class="col col-auto text-right">
                        <i class="feather icon-shopping-cart f-50 text-c-blue"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-xl-12 col-md-12">
        <div class="card bg-c-green text-white">
            <div class="card-block">
                <div class="row align-items-center">
                    <div class="col">
                        <h6 class="m-b-5 mb-2"><b> ELECTION RETURN TRANSMITTED</b></h6>
                        <?php

                        $sql123 = "SELECT DISTINCT voter_id FROM vote where acad_id='$acad'";
                        $rs = $conn->query($sql123);
                        $sql12 = "SELECT DISTINCT v_id FROM voters where acad_id='$acad'";
                        $rs12 = $conn->query($sql12);
                        $final = 0;
                        $from = $rs->num_rows;
                        $to = $rs12->num_rows;
                        @$final = $from / $to * 100;
                        $round = round($final, 2);
                        ?>
                        <span class="elec bg-primary p-1" style="border-radius: 50px; border:2px solid white"><?php echo $round; ?>%</span>
                        <span>- <?php echo $rs->num_rows ?> of <?php echo $rs12->num_rows ?> voters are completely voted</span>
                    </div>
                    <div class="col col-auto text-right">

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
                            $sql = "SELECT * FROM candidate LEFT JOIN voters ON candidate.stud_id=voters.stud_id LEFT JOIN partylist ON candidate.p_id=partylist.p_id WHERE candidate.acad_id='$acad' and candidate.pos_id = '" . $row['pos_id'] . "'";
                            $cquery = $conn->query($sql);
                            while ($rowt = $cquery->fetch_assoc()) {
                                if ($rowt['p_id'] == 0) {
                                    $pn = "IND";
                                } else {
                                    $pn = $rowt['party_name'];
                                }
                                echo '<label class="text-primary text-uppercase">' . $rowt['lname'] . ", " . $rowt['fname'] . " " . $rowt['mname'][0] . ". (" . $pn . ')</label>';

                                $sqltt = "SELECT * FROM vote WHERE acad_id='$acad' and candidate_id = '" . $rowt['c_id'] . "'";
                                $rsst = $conn->query($sqltt);
                                $sq = "SELECT * FROM voters where acad_id='$acad'";
                                $rpq = $conn->query($sq);
                                @$finalt = 0;
                                @$from1 = $rsst->num_rows;
                                @$to1 = $rpq->num_rows;
                                @$finalt = $from1 / $to1 * 100;
                                @$roundt = round($finalt);
                                echo '
                                                                    <br><label> ' . @$rsst->num_rows . ' - Votes</label>
                                                                    <div class="progress">
                                                                        <div class="progress-bar progress-bar-striped progress-bar-primary" role="progressbar" style="width: ' . @$roundt . '%" aria-valuenow="' . @$roundt . '" aria-valuemin="0" aria-valuemax="' . @$rpq->num_rows . '"></div>
                                                                    </div><br>';
                            }

                            ?>
                        </div>

                    </div>

                </div>
            </div>
        </div>
    <?php } ?>
</div>