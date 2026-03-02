<?php
include '../connection.php';
$acad = $_SESSION['acad'];
if (!isset($_SESSION['at'])) {
    header("Location: ../logout.php");
    exit;
}

if (isset($_POST['submit'])) {
    $stud = $conn->real_escape_string($_POST['studid']);
    $pos_id = (int) $_POST['position'];
    $department_id = (int) $_POST['department_id'];
    $platform = $conn->real_escape_string($_POST['platform'] ?? '');
    if ($_FILES['files']['name'] == '') {
        $dirs = "libraries/img/logo.png";
    } else {
        $imgfile = $_FILES["files"]["name"];
        $ext = substr($imgfile, -4);
        if (in_array(strtolower($ext), array('.jpg', 'jpeg', '.png', '.gif'))) {
            $imgnewfile = md5($imgfile) . $ext;
            $dir = "../candidatephoto/" . $imgnewfile;
            $dirs = "candidatephoto/" . $imgnewfile;
            move_uploaded_file($_FILES['files']['tmp_name'], $dir);
        } else {
            $dirs = "libraries/img/logo.png";
        }
    }
    $sql = "INSERT INTO dept_candidate (acad_id, stud_id, pos_id, department_id, img, platform) VALUES ('$acad', '$stud', '$pos_id', '$department_id', '$dirs', '$platform')";
    if ($conn->query($sql)) {
        $_SESSION['response'] = "Department candidate added.";
        $_SESSION['type'] = "success";
    } else {
        $_SESSION['response'] = "An error has occurred.";
        $_SESSION['type'] = "warning";
    }
}
if (isset($_POST['update'])) {
    $dc_id = (int) $_POST['idhidden'];
    $pos_id = (int) $_POST['position'];
    $platform = $conn->real_escape_string($_POST['platform'] ?? '');
    if ($_FILES['files']['name'] == '') {
        $sql = "UPDATE dept_candidate SET pos_id='$pos_id', platform='$platform' WHERE dc_id='$dc_id'";
    } else {
        $imgfile = $_FILES["files"]["name"];
        $ext = substr($imgfile, -4);
        $dirs = "libraries/img/logo.png";
        if (in_array(strtolower($ext), array('.jpg', 'jpeg', '.png', '.gif'))) {
            $imgnewfile = md5($imgfile) . $ext;
            $dir = "../candidatephoto/" . $imgnewfile;
            $dirs = "candidatephoto/" . $imgnewfile;
            move_uploaded_file($_FILES['files']['tmp_name'], $dir);
        }
        $sql = "UPDATE dept_candidate SET pos_id='$pos_id', img='$dirs', platform='$platform' WHERE dc_id='$dc_id'";
    }
    if ($conn->query($sql)) {
        $_SESSION['response'] = "Department candidate updated.";
        $_SESSION['type'] = "success";
    } else {
        $_SESSION['response'] = "An error has occurred.";
        $_SESSION['type'] = "warning";
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'nav/header.php'; ?>
<body>
    <div class="theme-loader"><div class="ball-scale"><div class="contain"><div class="ring"><div class="frame"></div></div></div></div></div>
    <div id="pcoded" class="pcoded">
        <div class="pcoded-overlay-box"></div>
        <div class="pcoded-container navbar-wrapper">
            <?php include 'nav/topbar.php'; ?>
            <div class="pcoded-main-container">
                <div class="pcoded-wrapper">
                    <?php include 'nav/sidebar.php'; ?>

                    <div class="modal fade" id="default-Modal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header"><h4 class="modal-titles">Department Candidate</h4><button type="button" class="close" data-dismiss="modal" onclick="location.reload();"><span>&times;</span></button></div>
                                <form action="" method="post" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="card p-2 text-center font-weight-bold">PHOTO</div>
                                                <center><img id="ImdID" class="img-fluid rounded-circle" src="../libraries/img/glanlogo.png" alt="Image" style="width:200px;height:200px;"></center>
                                                <br><div class="form-group"><input type="file" name="files" class="form-control" onchange="readURL(this);" /></div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Student ID</label>
                                                    <div class="row">
                                                        <div class="col-9"><input type="text" name="studid" class="form-control" id="studid" required><input type="hidden" name="idhidden" id="idhidden"><input type="hidden" name="department_id" id="department_id" value=""></div>
                                                        <div class="col"><button type="button" class="btn btn-primary" id="searchz"><i class="fa fa-search"></i></button></div>
                                                    </div>
                                                    <div id="availability"></div>
                                                </div>
                                                <div class="form-group"><label class="col-form-label">Full Name</label><input type="text" class="form-control text-uppercase" id="fname" readonly></div>
                                                <div class="form-group"><label class="col-form-label">Year / Strand / Section</label><input type="text" class="form-control" id="yearlevel" readonly></div>
                                                <div class="form-group"><label class="col-form-label">Department</label><input type="text" class="form-control" id="department_name" readonly></div>
                                                <div class="form-group"><label class="col-form-label">Position</label>
                                                    <select name="position" class="form-control" id="position" required>
                                                        <option value="">-- Select Position --</option>
                                                        <?php
                                                        $pq = $conn->query("SELECT dp_id, description FROM dept_position WHERE acad_id='$acad' ORDER BY priority ASC");
                                                        while ($pr = $pq->fetch_assoc()) echo '<option value="' . (int)$pr['dp_id'] . '">' . htmlspecialchars($pr['description']) . '</option>';
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group"><label class="col-form-label">Platform</label><textarea name="platform" class="form-control" id="platform" rows="3"></textarea></div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer" id="footersa">
                                        <button type="submit" class="btn btn-primary" name="submit" id="submits">Save</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="pcoded-content">
                        <div class="pcoded-inner-content">
                            <div class="main-body">
                                <div class="page-wrapper">
                                    <div class="page-body">
                                        <div class="row">
                                            <div class="col-12"><button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#default-Modal">Add Department Candidate</button></div>
                                            <div class="col-xl-12 col-md-12">
                                                <div class="card">
                                                    <div class="card bg-c-green text-white"><div class="card-block"><h3 class="m-b-5 text-uppercase"><b>Department Candidates</b></h3></div></div>
                                                    <div class="card-block-big">
                                                        <div class="dt-responsive table-responsive">
                                                            <table class="table table-striped table-bordered nowrap w-100" id="simpletable">
                                                                <thead><tr><th>#</th><th>Img</th><th>Candidate</th><th>Department</th><th>Position</th><th style="display:none">dc_id</th><th style="display:none">pos_id</th><th style="display:none">stud_id</th><th style="display:none">img</th><th style="display:none">platform</th><th>Action</th></tr></thead>
                                                                <tbody>
                                                                <?php
                                                                $sql = "SELECT dc.*, dv.fname, dv.lname, dv.mname, dp.description AS pos_desc, d.department_name FROM dept_candidate dc INNER JOIN dept_voters dv ON dc.stud_id = dv.stud_id AND dc.acad_id = dv.acad_id INNER JOIN dept_position dp ON dc.pos_id = dp.dp_id AND dc.acad_id = dp.acad_id LEFT JOIN departments d ON dc.department_id = d.department_id WHERE dc.acad_id='$acad' ORDER BY dp.priority, dv.lname";
                                                                $res = $conn->query($sql);
                                                                $i = 1;
                                                                while ($row = $res ? $res->fetch_assoc() : null) {
                                                                    if (!$row) break;
                                                                    $m = isset($row['mname'][0]) ? $row['mname'][0] . '.' : '';
                                                                    $name = $row['lname'] . ', ' . $row['fname'] . ' ' . $m;
                                                                    echo '<tr><td>' . $i++ . '</td><td><img src="../' . htmlspecialchars($row['img']) . '" class="rounded-circle" style="width:30px;height:30px;"></td><td class="text-uppercase">' . htmlspecialchars($name) . '</td><td>' . htmlspecialchars($row['department_name'] ?? '-') . '</td><td>' . htmlspecialchars($row['pos_desc']) . '</td>';
                                                                    echo '<td style="display:none">' . $row['dc_id'] . '</td><td style="display:none">' . $row['pos_id'] . '</td><td style="display:none">' . htmlspecialchars($row['stud_id']) . '</td><td style="display:none">' . htmlspecialchars($row['img']) . '</td><td style="display:none">' . htmlspecialchars($row['platform'] ?? '') . '</td>';
                                                                    echo '<td><a href="#default-Modal" class="edit badge badge-warning p-2 text-white" title="Edit"><i class="fa fa-edit"></i></a> | <a href="#" class="delete badge badge-danger p-2 text-white" title="Delete"><i class="fa fa-trash"></i></a></td></tr>';
                                                                }
                                                                ?></tbody>
                                                            </table>
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
                </div>
            </div>
        </div>
    </div>
    <?php include 'nav/script.php'; ?>
    <?php if (isset($_SESSION['response'])) { ?>
    <script>swal({ title: "<?php echo addslashes($_SESSION['response']); ?>", icon: "<?php echo $_SESSION['type']; ?>", button: "OK" });</script>
    <?php unset($_SESSION['response'], $_SESSION['type']); } ?>
    <script>
    function readURL(input) { if (input.files && input.files[0]) { var r = new FileReader(); r.onload = function(e) { $('#ImdID').attr('src', e.target.result); }; r.readAsDataURL(input.files[0]); } }
    $(document).ready(function () {
        if (window.history.replaceState) window.history.replaceState(null, null, window.location.href);
        $('#searchz').on('click', function () {
            var s = $('#studid').val();
            $.ajax({ url: "ajax/searchstudent_dept.php", method: "POST", data: { myids: s }, dataType: "json",
                success: function (d) {
                    if (d.stat == 1) {
                        $('#fname').val(d.fname); $('#yearlevel').val(d.gr); $('#department_name').val(d.department_name); $('#department_id').val(d.department_id);
                        $('#availability').html('<span class="text-success">Verified department voter</span>');
                        if (d.res == 1) $('#availability').html('<span class="text-danger">Already added as candidate</span>');
                    } else {
                        $('#fname').val(''); $('#yearlevel').val(''); $('#department_name').val(''); $('#department_id').val('');
                        $('#availability').html('<span class="text-danger">Not found or not verified in department voters</span>');
                    }
                }
            });
        });
        $(document).on('click', '.edit', function () {
            var $tr = $(this).closest('tr');
            var tds = $tr.find('td');
            $('#idhidden').val($.trim(tds.eq(5).text()));
            $('#studid').val($.trim(tds.eq(7).text())).attr('readonly', true);
            $('#position').val($.trim(tds.eq(6).text()));
            $('#platform').val($.trim(tds.eq(9).text()));
            $('#ImdID').attr('src', '../' + tds.eq(8).text());
            $('#fname').val($.trim(tds.eq(2).text()));
            $('#department_name').val($.trim(tds.eq(3).text()));
            $('#yearlevel').val('');
            $('#default-Modal').modal('show');
            $('.modal-titles').html('Update Department Candidate');
            $('#submits').remove();
            if ($('#update').length === 0) $('#footersa').append('<button type="submit" class="btn btn-primary" name="update" id="update">Update</button>');
        });
        $(document).on('click', '.delete', function (e) {
            e.preventDefault();
            var dc_id = $(this).closest('tr').find('td').eq(5).text();
            swal({ title: "Are you sure?", text: "Delete this department candidate?", icon: "warning", buttons: true, dangerMode: true })
                .then(function(willDelete) {
                    if (willDelete) {
                        $.ajax({ method: "POST", url: "ajax/delete_dept_candidate.php", data: { myids: dc_id },
                            success: function () { swal("Deleted.", { icon: "success" }).then(function() { location.reload(); }); }
                        });
                    }
                });
        });
    });
    </script>
</body>
</html>
