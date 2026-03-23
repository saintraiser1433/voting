<?php
include 'connection.php';

$acad = $_SESSION['acad'];
$voterId = isset($_SESSION['v_id']) ? (int)$_SESSION['v_id'] : 0;
if (!isset($_SESSION['v_id']) && !isset($_SESSION['faceverified'])) {
    header("Location: logout.php");
    exit;
}
if (isset($_SESSION['voting_mode']) && $_SESSION['voting_mode'] === 'department') {
    header("Location: department_home.php");
    exit;
}
if (isset($_POST['buts'])) {
    $_SESSION['response'] = "You must vote atleast one candidate";
    $_SESSION['type'] = "warning";
}
$sql = "SELECT * FROM election_title WHERE acad_id='$acad' AND (election_type = 'general' OR election_type IS NULL) LIMIT 1";
$rs = $conn->query($sql);
$row = ($rs && $rs->num_rows > 0) ? $rs->fetch_assoc() : null;


?>

<!DOCTYPE html>
<html lang="en">
<?php include 'nav/header.php'; ?>
<link rel="stylesheet" href="libraries/assets/css/ballot-responsive.css">
<body>
    <div id="pcoded" class="pcoded ballot-page">
        <div class="pcoded-overlay-box"></div>
        <div class="pcoded-container navbar-wrapper">

            <?php include 'nav/topbar.php'; ?>

            <div id="offline-banner" class="alert alert-warning text-center m-0" style="display:none;">
                You are currently offline. Your vote will be stored on this device and synced when you go online.
            </div>
            <div class="row justify-content-center m-0">
                <div class="col-xl-8 col-md-12 px-2 px-md-3">
                    <?php include 'nav/voter_offline_sync_config.php'; ?>
                    <?php include 'nav/voter_db_sync_config.php'; ?>
                </div>
            </div>
            <div class="pcoded-main-container">
                <div class="pcoded-wrapper">

                    <div class="main-body">
                        <div class="page-wrapper">

                            <div class="page-body">
                                <div class="row justify-content-center">
                                    <div class="col-xl-8 col-md-12">
                                        <div class="card bg-c-green text-white ballot-title-card">
                                            <div class="card-block">
                                                <div class="row text-center">
                                                    <div class="col">
                                                        <h5 class="m-b-0 text-uppercase"><b><img src="libraries/img/glanlogo.png" alt=""> <?php echo ($row && isset($row['title'])) ? htmlspecialchars($row['title']) : 'Election'; ?></b></h5>
                                                        <span>Note: Click "Preview" before submitting your votes.</span>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <form method="POST" id="ballotForm" action="submit_ballot.php">
                                    <?php
                                    include 'admin/includes/slugify.php';

                                    $sql = "SELECT * FROM position where acad_id='$acad' ORDER BY priority ASC";
                                    $query = $conn->query($sql);
                                    if ($query) {
                                    while ($row = $query->fetch_assoc()) {
                                        $candidate = '';
                                        $sql2 = "SELECT *,candidate.img as im FROM candidate INNER JOIN voters ON candidate.stud_id=voters.stud_id LEFT JOIN partylist ON candidate.p_id=partylist.p_id WHERE candidate.acad_id='$acad' and candidate.pos_id='" . $row['pos_id'] . "'";
                                        $cquery = $conn->query($sql2);
                                        if ($cquery && $cquery->num_rows > 0) {
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
                                            $input = ($row['max_vote'] > 1) ? '<div class="checkbox-fade fade-in-success" id="chck"><label><input type="checkbox" class="' . $slug . '" name="' . $slug . '[]" value="' . $crow['c_id'] . '" ' . $checked . '><span class="cr"><i class="cr-icon icofont icofont-ui-check txt-success"></i></span></label></div>' : '<div class="form-radio"><div class="radio radiofill radio-success radio-inline"><label class="col-form-label"><input type="radio" name="' . $slug . '" class="' . $slug . '" value="' . $crow['c_id'] . '" ' . $checked . '><i class="helper"></i></label></div></div>';
                                            $image = $crow['im'];
                                            $partyname = (isset($crow['party_name']) && $crow['party_name'] !== null) ? $crow['party_name'] : 'IND';
                                            $m = isset($crow['mname'][0]) ? $crow['mname'][0] . '.' : '';
                                            $candidate .= '<div class="candidate-row">' . $input . '<ul><li><img src="' . htmlspecialchars($image) . '" alt=""></li></ul><div class="candidate-name"><h3 class="text-uppercase font-weight-bold m-0" id="myh3">' . htmlspecialchars($crow['fname'] . ', ' . $crow['lname'] . ' ' . $m) . ' - <span class="text-warning text-uppercase">' . htmlspecialchars($partyname) . '</span></h3></div></div>';
                                        }
                                        }
                                        if ($candidate === '') {
                                            $candidate = '<p class="text-white p-3 m-0">No candidates for this position.</p>';
                                        }

                                        $instruct = ($row['max_vote'] > 1) ? 'You may select up to ' . $row['max_vote'] . ' candidates' : 'Select only one candidate';

                                        echo '<div class="row justify-content-center"><div class="col-xl-8 col-md-12"><div class="card" id="' . $row['pos_id'] . '"><div class="bg-c-blue text-white"><div class="card-block"><div class="row align-items-center"><div class="col"><h3 class="m-b-0 text-uppercase"><b>' . htmlspecialchars($row['description']) . '</b></h3><span class="text-white small">' . htmlspecialchars($instruct) . '</span></div></div></div><div class="card-block-big">' . $candidate . '</div></div></div></div>';
                                    }
                                    } else {
                                        echo '<div class="row justify-content-center"><div class="col-xl-8 col-md-12"><div class="card"><div class="card-block"><p class="text-muted mb-0">No positions configured for this election. Ask admin to add positions.</p></div></div></div>';
                                    }

                                    ?>
                                    <input type="hidden" name="voters1">
                                    <div class="ballot-actions text-center">
                                        <button type="button" class="btn btn-success btn-flat" id="preview"><i class="fa fa-file-text"></i> Preview</button>
                                        <button type="button" class="btn btn-warning btn-flat" id="btn-save-offline" title="Use if the connection is bad or you will sync from another network">
                                            <i class="fa fa-mobile"></i> Save on device (sync later)
                                        </button>
                                        <button type="submit" class="btn btn-primary btn-flat" name="vote" id="btn-submit"><i class="fa fa-check-square-o"></i> Submit</button>
                                    </div>
                                </form>
                                <form action="" method="post">
                                    <button type="submit" name="buts" id="buts" style="display: none;"></button>
                                </form>
                                <div id="styleSelector"></div>
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
    // Load sync URL from app_settings if available
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
        window.__voterId = <?php echo json_encode($voterId); ?>;
        window.__acadId = <?php echo json_encode($acad); ?>;
        window.__deptId = 0;
        window.__mode = 'general';
        window.__syncUrl = <?php echo json_encode($syncUrl); ?>;
    </script>
    <script src="js/offline-vote.js"></script>
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
                    var bf = document.getElementById('ballotForm');
                    var sub = document.getElementById('btn-submit');
                    if (typeof window.trySubmitBallotAfterConfirm === 'function') {
                        window.trySubmitBallotAfterConfirm(bf, sub);
                    } else if (bf && typeof bf.requestSubmit === 'function') {
                        bf.requestSubmit(sub || undefined);
                    } else {
                        $('#ballotForm').submit();
                    }
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

    $('#btn-save-offline').on('click', function () {
        var bf = document.getElementById('ballotForm');
        if (!bf) return;
        swal({
            title: 'Save on this device?',
            text: 'Your choices will be stored in this browser only (' + (window.location.origin || '') + '). Use "Sync Offline Vote" on the home page when the server is reachable through your sync URL. If the server already recorded your vote, do not sync again.',
            icon: 'info',
            buttons: true
        }).then(function (ok) {
            if (ok && typeof window.persistBallotOffline === 'function') {
                window.persistBallotOffline(bf);
            }
        });
    });
</script>