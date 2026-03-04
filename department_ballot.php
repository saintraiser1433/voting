<?php
include 'connection.php';

$acad = $_SESSION['acad'];
$dept_id = isset($_SESSION['dept_id']) ? (int) $_SESSION['dept_id'] : 0;
if (!isset($_SESSION['v_id']) || !isset($_SESSION['faceverified']) || (isset($_SESSION['voting_mode']) && $_SESSION['voting_mode'] !== 'department') || !$dept_id) {
    header("Location: logout.php");
    exit;
}
if (isset($_POST['buts'])) {
    $_SESSION['response'] = "You must vote at least one candidate";
    $_SESSION['type'] = "warning";
}

$rs = $conn->query("SELECT * FROM election_title WHERE acad_id='$acad' AND election_type = 'department' LIMIT 1");
if (!$rs || $rs->num_rows === 0) {
    if ($rs && strpos($conn->error ?? '', 'Unknown column') !== false) {
        $rs = $conn->query("SELECT * FROM election_title WHERE acad_id='$acad' LIMIT 1");
    }
}
$et = $rs && $rs->num_rows > 0 ? $rs->fetch_assoc() : null;
$title = $et ? htmlspecialchars($et['title']) : 'Department Election';

// Check if general voting is ended (button disabled if so)
$general_ended = false;
$chk = $conn->query("SELECT 1 FROM election_title WHERE acad_id='$acad' AND (election_type = 'general' OR election_type IS NULL) AND is_finished = 1 LIMIT 1");
if ($chk && $chk->num_rows > 0) {
    $general_ended = true;
}
if (!$chk && strpos($conn->error ?? '', 'Unknown column') !== false) {
    $general_ended = false;
}
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
            <div class="pcoded-main-container">
                <div class="pcoded-wrapper">
                    <div class="main-body">
                        <div class="page-wrapper">
                            <div class="page-body">
                                <div class="row justify-content-center">
                                    <div class="col-xl-8 col-md-12">
                                        <div class="card bg-c-green text-white ballot-title-card">
                                            <div class="card-block">
                                                <div class="row text-center align-items-center">
                                                    <div class="col">
                                                        <h5 class="m-b-0 text-uppercase"><b><img src="libraries/img/glanlogo.png" alt=""> <?php echo $title; ?> - Department</b></h5>
                                                        <span>Note: Click "Preview" before submitting your votes.</span>
                                                    </div>
                                                    <div class="col-auto">
                                                        <?php if ($general_ended) { ?>
                                                            <button type="button" class="btn btn-outline-light btn-sm" disabled title="General voting has ended">General Voting</button>
                                                        <?php } else { ?>
                                                            <a href="switch_voting_mode.php?mode=general" class="btn btn-outline-light btn-sm">General Voting</a>
                                                        <?php } ?>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <form method="POST" id="ballotForm" action="submit_department_ballot.php">
                                    <?php
                                    include 'admin/includes/slugify.php';
                                    $candidate = '';
                                    $sql = "SELECT * FROM dept_position WHERE acad_id='$acad' ORDER BY priority ASC";
                                    $query = $conn->query($sql);
                                    if ($query && $query->num_rows > 0) {
                                        while ($row = $query->fetch_assoc()) {
                                            $dp_id = (int) $row['dp_id'];
                                            // Use shared voters table for candidate details (only candidates in voter's department)
                                            $cquery = $conn->query("SELECT dc.dc_id, dc.img AS im, v.fname, v.lname, v.mname
                                                                    FROM dept_candidate dc
                                                                    INNER JOIN voters v ON dc.stud_id = v.stud_id AND dc.acad_id = v.acad_id
                                                                    WHERE dc.acad_id='$acad' AND dc.department_id='$dept_id' AND dc.pos_id='$dp_id'");
                                            if ($cquery && $cquery->num_rows > 0) {
                                                while ($crow = $cquery->fetch_assoc()) {
                                                    $slug = slugify($row['description']);
                                                    $checked = '';
                                                    if (isset($_SESSION['dept_post'][$slug])) {
                                                        $value = $_SESSION['dept_post'][$slug];
                                                        if (is_array($value)) { $checked = in_array($crow['dc_id'], $value) ? 'checked' : ''; }
                                                        else { $checked = ($value == $crow['dc_id']) ? 'checked' : ''; }
                                                    }
                                                    $input = ($row['max_vote'] > 1)
                                                        ? '<div class="checkbox-fade fade-in-success"><label><input type="checkbox" class="' . $slug . '" name="' . $slug . '[]" value="' . $crow['dc_id'] . '" ' . $checked . '><span class="cr"><i class="cr-icon icofont icofont-ui-check txt-success"></i></span></label></div>'
                                                        : '<div class="form-radio"><div class="radio radiofill radio-success radio-inline"><label><input type="radio" name="' . $slug . '" class="' . $slug . '" value="' . $crow['dc_id'] . '" ' . $checked . '><i class="helper"></i></label></div></div>';
                                                    $m = isset($crow['mname'][0]) ? $crow['mname'][0] . '.' : '';
                                                    $candidate .= '<div class="candidate-row">' . $input . '<ul><li><img src="' . htmlspecialchars($crow['im']) . '" alt=""></li></ul>';
                                                    $candidate .= '<div class="candidate-name"><h3 class="text-uppercase font-weight-bold m-0">' . htmlspecialchars($crow['fname'] . ', ' . $crow['lname'] . ' ' . $m) . '</h3></div></div>';
                                                }
                                            }
                                            if ($candidate === '') {
                                                $candidate = '<p class="text-white p-3">No candidates in your department for this position.</p>';
                                            }
                                            $instruct = ($row['max_vote'] > 1) ? 'You may select up to ' . $row['max_vote'] . ' candidates' : 'Select only one candidate';
                                            echo '<div class="row justify-content-center"><div class="col-xl-8 col-md-12"><div class="card"  id="' . $row['dp_id'] . '">';
                                            echo '<div class="bg-c-blue text-white"><div class="card-block"><div class="row align-items-center"><div class="col"><h3 class="m-b-0 text-uppercase"><b>' . htmlspecialchars($row['description']) . '</b></h3><span class="text-white small">' . $instruct . '</span></div></div></div>';
                                            echo '<div class="card-block-big">' . $candidate . '</div></div></div></div>';
                                            $candidate = '';
                                        }
                                    } else {
                                        echo '<div class="row justify-content-center"><div class="col-xl-8 col-md-12"><div class="card"><div class="card-block"><p class="text-muted mb-0">No positions configured for the department election. Ask admin to add positions under Department Voting.</p></div></div></div>';
                                    }
                                    ?>
                                    <input type="hidden" name="voters1">
                                    <div class="ballot-actions text-center">
                                        <button type="button" class="btn btn-success btn-flat" id="preview"><i class="fa fa-file-text"></i> Preview</button>
                                        <button type="submit" class="btn btn-primary btn-flat" name="vote" id="btn-submit"><i class="fa fa-check-square-o"></i> Submit</button>
                                    </div>
                                </form>
                                <form action="" method="post">
                                    <button type="submit" name="buts" id="buts" style="display: none;"></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php include 'nav/script.php'; ?>
    <?php include 'modalplatform.php'; ?>
</body>
</html>
<script>
$('#btn-submit').on('click', function (e) {
    e.preventDefault();
    swal({ title: "Are you sure?", text: "Once you click OK, your vote will be cast!", icon: "info", buttons: true })
        .then(function(willDelete) { if (willDelete) $('#ballotForm').submit(); else swal("Cancelled", "", "error"); });
});
if (window.history.replaceState) window.history.replaceState(null, null, window.location.href);
$('#preview').click(function (e) {
    e.preventDefault();
    var form = $('#ballotForm').serialize();
    if (!form) { $('#buts').click(); return; }
    $.ajax({ type: 'POST', url: 'ajax/preview_dept.php', data: form, dataType: 'json',
        success: function (response) { $('#preview1').modal('show'); $('#preview2').html(response.list || ''); }
    });
});
</script>
