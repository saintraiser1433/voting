<?php
include 'connection.php';

$acad = $_SESSION['acad'];
if (!isset($_SESSION['v_id']) && !isset($_SESSION['faceverified'])) {
    header("Location:logout.php");
}
if (isset($_POST['buts'])) {
    $_SESSION['response'] = "You must vote atleast one candidate";
    $_SESSION['type'] = "warning";
}
$sql = "SELECT * FROM election_title where acad_id='$acad'";
$rs = $conn->query($sql);
$row = $rs->fetch_assoc();


?>

<!DOCTYPE html>
<html lang="en">

<?php include 'nav/header.php'; ?>
<!-- Menu sidebar static layout -->

<body>
    <!-- Pre-loader start -->

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
                                <div class="row justify-content-center">
                                    <div class="col-xl-8 col-md-12">
                                        <div class="card bg-c-green text-white">
                                            <div class="card-block">
                                                <div class="row text-center">
                                                    <div class="col">
                                                        <h5 class="m-b-5 text-uppercase text-center"><b><img
                                                                    src="libraries/img/glanlogo.png"
                                                                    style="width:60px; height:60px;">
                                                                <?php echo $row['title'] ?></b></h5>
                                                        <span>Note: Always make sure to click "Preview" Button before
                                                            submitting your votes! Thank you and godbless!</span>
                                                    </div>

                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                </div>

                                <form method="POST" id="ballotForm" action="submit_ballot.php">
                                    <?php
                                    include 'admin/includes/slugify.php';

                                    $candidate = '';
                                    $sql = "SELECT * FROM position where acad_id='$acad' ORDER BY priority ASC";
                                    $query = $conn->query($sql);
                                    while ($row = $query->fetch_assoc()) {
                                        $sql = "SELECT *,candidate.img as im FROM candidate INNER JOIN voters ON candidate.stud_id=voters.stud_id LEFT JOIN partylist ON candidate.p_id=partylist.p_id WHERE candidate.acad_id='$acad' and candidate.pos_id='" . $row['pos_id'] . "'";
                                        $cquery = $conn->query($sql);
                                        while ($crow = $cquery->fetch_assoc()) {
                                            $slug = slugify($row['description']);
                                            $checked = '';
                                            if (isset($_SESSION['post'][$slug])) {
                                                $value = $_SESSION['post'][$slug];

                                                if (is_array($value)) {
                                                    foreach ($value as $val) {
                                                        if ($val == $crow['c_id']) {
                                                            $checked = 'checked';
                                                        }
                                                    }
                                                } else {
                                                    if ($value == $crow['c_id']) {
                                                        $checked = 'checked';
                                                    }
                                                }
                                            }
                                            $input = ($row['max_vote'] > 1) ? ' <div class="checkbox-fade fade-in-success  mt-4" id="chck">
                                            <label>
                                                <input type="checkbox" class="' . $slug . '" name="' . $slug . "[]" . '" value="' . $crow['c_id'] . '" ' . $checked . '>
                                                <span class="cr">
                                                    <i class="cr-icon icofont icofont-ui-check txt-success"></i>
                                                </span>
                                                <span></span>
                                            </label>
                                        </div>' : '<div class="form-radio m-b-30 mt-4">
                                        <div class="radio radiofill radio-success radio-inline">
                                            <label class="col-form-label">
                                                <input type="radio" name="' . $slug . '" class="' . $slug . '"  value="' . $crow['c_id'] . '" ' . $checked . '>
                                                <i class="helper"></i>
                                        
                                            </label>
                                        
                                        </div>
                                        </div>';

                                            $image = $crow['im'];
                                            if (is_null($crow['party_name'])) {
                                                $partyname = "IND";
                                            } else {
                                                $partyname = $crow['party_name'];
                                            }
                                            $candidate .= ' <div class="row mt-2">
                                        ' . $input . '
                                        <ul>
                                        <li><img src="' . $image . '" style="width: 120px; height:120px; border:2px solid steelblue; border-radius:10px;"></li>
                                            </ul>
                                            <div class="text-center mt-3 pl-3">
                                                <h3 class="text-uppercase font-weight-bold" id="myh3">' . $crow['fname'] . ',' . $crow['lname'] . ' ' . $crow['mname'][0] . '.' . ' - <span class="text-warning text-uppercase">' . $partyname . '</span></h3> 
                                            </div></div>
                                            ';
                                        }

                                        $instruct = ($row['max_vote'] > 1) ? 'You may select up to ' . $row['max_vote'] . ' candidates' : 'Select only one candidate';

                                        echo '
                                        <div class="row justify-content-center">
                                        <div class="col-xl-8 col-md-12">
                                        <div class="card" id="' . $row['pos_id'] . '">
                                            <div class="card bg-c-blue text-white">
                                                <div class="card-block">
                                                    <div class="row align-items-center">
                                                        <div class="col">
                                                            <h3 class="m-b-5 text-uppercase"><b>' . $row['description'] . '</b></h3>
                                                            <span class="text-white">' . $instruct . '</span>
                                                        </div>
                                                       
                                                    </div>
                                                </div>
                                            </div>
                            
                                            <div class="card-block-big">
                                                   ' . $candidate . '
                                            </div>
                            
                            
                                        </div>
                                    </div>
                                </div>
                                    ';
                                        $candidate = '';
                                    }

                                    ?>
                                    <input type="hidden" name="voters1">
                                    <div class="text-center">
                                        <button type="button" class="btn btn-success btn-flat" id="preview"><i
                                                class="fa fa-file-text"></i> Preview</button>
                                        <button type="submit" class="btn btn-primary btn-flat" name="vote"
                                            id="btn-submit"><i class="fa fa-check-square-o"></i> Submit</button>
                                    </div>
                                </form>
                                <form action="" method="post">
                                    <button type="submit" name="buts" id="buts" style="display: none;"></button>
                                </form>
                                <br>


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


    <?php include 'nav/script.php'; ?>
    <?php include 'modalplatform.php'; ?>
    <?php
    if (isset($_SESSION['response']) && $_SESSION['response'] != "") {

        foreach ($_SESSION['response'] as $error) {
            echo '
            <script>
            swal({
                title: "' . $error . '",
                icon: "' . $_SESSION['type'] . '",
                button: "Exit!",
            });
        </script>
            
            ';
        }

        ?>

        <?php unset($_SESSION['response']);
    }
    ?>

</body>

</html>
<script>
    $('#btn-submit').on('click', function (e) {
        e.preventDefault();
        swal({
            title: "Are you sure?",
            text: "Once Click 'FINISH' button, your vote will be cast!",
            icon: "info",
            buttons: true,
        })
            .then((willDelete) => {
                if (willDelete) {
                    $('#ballotForm').submit();

                } else {
                    swal("Cancelled", "", "error");
                }
            });
    });

    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
    $('#preview').click(function (e) {
        e.preventDefault();
        var form = $('#ballotForm').serialize();
        if (form == '') {
            $('#buts').click();
        } else {
            $.ajax({
                type: 'POST',
                url: 'ajax/preview.php',
                data: form,
                dataType: 'json',
                success: function (response) {
                    $('#preview1').modal('show');
                    $('#preview2').html(response.list);
                }
            });
        }

    });
</script>
<style>
    @media screen and (max-width:600px) {
        #chck {
            margin-left: 60px;
        }

        #myh3 {
            font-size: 12px;
            margin-left: 90px;
        }
    }
</style>