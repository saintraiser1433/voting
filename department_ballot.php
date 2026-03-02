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
$et = $rs && $rs->num_rows > 0 ? $rs->fetch_assoc() : null;
$title = $et ? htmlspecialchars($et['title']) : 'Department Election';
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
                                <div class="row justify-content-center">
                                    <div class="col-xl-8 col-md-12">
                                        <div class="card bg-c-green text-white">
                                            <div class="card-block">
                                                <div class="row text-center">
                                                    <div class="col">
                                                        <h5 class="m-b-5 text-uppercase text-center"><b><img src="libraries/img/glanlogo.png" style="width:60px; height:60px;"> <?php echo $title; ?> - Department</b></h5>
                                                        <span>Note: Click "Preview" before submitting your votes.</span>
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
                                    if ($query) {
                                        while ($row = $query->fetch_assoc()) {
                                            $dp_id = (int) $row['dp_id'];
                                            $cquery = $conn->query("SELECT dc.dc_id, dc.img AS im, dv.fname, dv.lname, dv.mname FROM dept_candidate dc INNER JOIN dept_voters dv ON dc.stud_id = dv.stud_id AND dc.acad_id = dv.acad_id WHERE dc.acad_id='$acad' AND dc.department_id='$dept_id' AND dc.pos_id='$dp_id'");
                                            if ($cquery) {
                                                while ($crow = $cquery->fetch_assoc()) {
                                                    $slug = slugify($row['description']);
                                                    $checked = '';
                                                    if (isset($_SESSION['dept_post'][$slug])) {
                                                        $value = $_SESSION['dept_post'][$slug];
                                                        if (is_array($value)) { $checked = in_array($crow['dc_id'], $value) ? 'checked' : ''; }
                                                        else { $checked = ($value == $crow['dc_id']) ? 'checked' : ''; }
                                                    }
                                                    $input = ($row['max_vote'] > 1)
                                                        ? '<div class="checkbox-fade fade-in-success mt-4"><label><input type="checkbox" class="' . $slug . '" name="' . $slug . '[]" value="' . $crow['dc_id'] . '" ' . $checked . '><span class="cr"><i class="cr-icon icofont icofont-ui-check txt-success"></i></span></label></div>'
                                                        : '<div class="form-radio m-b-30 mt-4"><div class="radio radiofill radio-success radio-inline"><label><input type="radio" name="' . $slug . '" class="' . $slug . '" value="' . $crow['dc_id'] . '" ' . $checked . '><i class="helper"></i></label></div></div>';
                                                    $m = isset($crow['mname'][0]) ? $crow['mname'][0] . '.' : '';
                                                    $candidate .= '<div class="row mt-2">' . $input . '<ul><li><img src="' . htmlspecialchars($crow['im']) . '" style="width:120px;height:120px;border:2px solid steelblue;border-radius:10px;"></li></ul>';
                                                    $candidate .= '<div class="text-center mt-3 pl-3"><h3 class="text-uppercase font-weight-bold">' . htmlspecialchars($crow['fname'] . ', ' . $crow['lname'] . ' ' . $m) . '</h3></div></div>';
                                                }
                                            }
                                            $instruct = ($row['max_vote'] > 1) ? 'You may select up to ' . $row['max_vote'] . ' candidates' : 'Select only one candidate';
                                            echo '<div class="row justify-content-center"><div class="col-xl-8 col-md-12"><div class="card" id="' . $row['dp_id'] . '">';
                                            echo '<div class="card bg-c-blue text-white"><div class="card-block"><div class="row align-items-center"><div class="col">';
                                            echo '<h3 class="m-b-5 text-uppercase"><b>' . htmlspecialchars($row['description']) . '</b></h3><span class="text-white">' . $instruct . '</span></div></div></div>';
                                            echo '<div class="card-block-big">' . $candidate . '</div></div></div></div>';
                                            $candidate = '';
                                        }
                                    }
                                    ?>
                                    <input type="hidden" name="voters1">
                                    <div class="text-center">
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
