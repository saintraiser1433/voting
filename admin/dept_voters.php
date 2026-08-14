<?php
include '../connection.php';
$acad = $_SESSION['acad'];
if (!isset($_SESSION['at'])) {
    header("Location: ../logout.php");
    exit;
}

// Department voters now use shared voters table with department_id and is_verified
if (isset($_POST['submit'])) {
    $studid = $conn->real_escape_string($_POST['studid']);
    $fname = $conn->real_escape_string($_POST['fname']);
    $lname = $conn->real_escape_string($_POST['lname']);
    $mname = $conn->real_escape_string($_POST['mname']);
    $yearlevel = (int) $_POST['yearlevel'];
    $strand = $conn->real_escape_string($_POST['strand']);
    $section = $conn->real_escape_string($_POST['section']);
    $department_id = (int) $_POST['department_id'];

    $sql = "INSERT INTO voters (stud_id, acad_id, fname, lname, mname, grade_level, strand, section, department_id, password, is_verified)
            VALUES ('$studid', '$acad', '$fname', '$lname', '$mname', '$yearlevel', '$strand', '$section', '$department_id', '', 0)";
    if ($conn->query($sql)) {
        $_SESSION['response'] = "Department voter added.";
        $_SESSION['type'] = "success";
    } else {
        $_SESSION['response'] = "An error has occurred.";
        $_SESSION['type'] = "warning";
    }
}
if (isset($_POST['update'])) {
    $vid = (int) $_POST['idhidden'];
    $fname = $conn->real_escape_string($_POST['fname']);
    $lname = $conn->real_escape_string($_POST['lname']);
    $mname = $conn->real_escape_string($_POST['mname']);
    $yearlevel = (int) $_POST['yearlevel'];
    $strand = $conn->real_escape_string($_POST['strand']);
    $section = $conn->real_escape_string($_POST['section']);
    $department_id = (int) $_POST['department_id'];
    $sql = "UPDATE voters SET fname='$fname', lname='$lname', mname='$mname', grade_level='$yearlevel', strand='$strand', section='$section', department_id='$department_id'
            WHERE v_id='$vid'";
    if ($conn->query($sql)) {
        $_SESSION['response'] = "Department voter updated.";
        $_SESSION['type'] = "success";
    } else {
        $_SESSION['response'] = "An error has occurred.";
        $_SESSION['type'] = "warning";
    }
}
if (isset($_GET['re'])) {
    $re = (int) $_GET['re'];
    $conn->query("UPDATE voters SET password='' WHERE v_id='$re'");
    $_SESSION['response'] = "Department voter password reset.";
    $_SESSION['type'] = "success";
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

                    <div class="modal fade" id="delete" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header"><h4 class="modal-title">Confirm</h4><button type="button" class="close" data-dismiss="modal" onclick="location.reload();"><span>&times;</span></button></div>
                                <form action="" method="post">
                                    <div class="modal-body"><input type="hidden" name="deleteid" id="deleteid"><h5 class="text-center text-primary">Delete this department voter?</h5></div>
                                    <div class="modal-footer"><button type="submit" class="btn btn-primary" name="delete">Delete</button></div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="default-Modal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header"><h4 class="modal-titles">Department Voter</h4><button type="button" class="close" data-dismiss="modal" onclick="location.reload();"><span>&times;</span></button></div>
                                <form action="" method="post">
                                    <div class="modal-body">
                                        <input type="hidden" name="idhidden" id="idhidden">
                                        <div class="form-group"><label class="col-form-label">Student ID</label><input type="text" name="studid" id="studid" class="form-control text-uppercase" required><div id="available"></div></div>
                                        <div class="form-group"><label class="col-form-label">First Name</label><input type="text" name="fname" id="fname" class="form-control text-uppercase" required></div>
                                        <div class="form-group"><label class="col-form-label">Last Name</label><input type="text" name="lname" id="lname" class="form-control text-uppercase" required></div>
                                        <div class="form-group"><label class="col-form-label">Middle Name</label><input type="text" name="mname" id="mname" class="form-control text-uppercase" required></div>
                                        <div class="form-group"><label class="col-form-label">Year Level</label>
                                            <select name="yearlevel" class="form-control" id="yearlevel" required>
                                                <option value="">--</option><option value="1">1st Year</option><option value="2">2nd Year</option><option value="3">3rd Year</option><option value="4">4th Year</option>
                                            </select>
                                        </div>
                                        <div class="form-group"><label class="col-form-label">Strand/Course</label><input type="text" name="strand" id="strand" class="form-control text-uppercase"></div>
                                        <div class="form-group"><label class="col-form-label">Section</label><input type="text" name="section" id="section" class="form-control text-uppercase"></div>
                                        <div class="form-group"><label class="col-form-label">Department</label>
                                            <select name="department_id" id="department_id" class="form-control" required>
                                                <option value="">-- Select Department --</option>
                                                <?php
                                                $dq = $conn->query("SELECT department_id, department_name FROM departments WHERE status=1 ORDER BY department_name");
                                                if ($dq && $dq->num_rows > 0) {
                                                    while ($dr = $dq->fetch_assoc()) echo '<option value="' . (int)$dr['department_id'] . '">' . htmlspecialchars($dr['department_name']) . '</option>';
                                                }
                                                ?>
                                            </select>
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
                                            <div class="col-12"><button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#default-Modal">Add Department Voter</button></div>
                                            <div class="col-12">
                                                <div class="card">
                                                    <div class="card bg-c-green text-white"><div class="card-block"><h3 class="m-b-5"><b>DEPARTMENT VOTERS</b></h3></div></div>
                                                    <div class="card-block-big">
                                                        <div class="dt-responsive table-responsive">
                                                            <table class="table table-striped table-bordered nowrap w-100" id="table-verified">
                                                                <thead><tr><th>#</th><th>Student ID</th><th>Full Name</th><th>Year</th><th>Strand</th><th>Section</th><th>Department</th><th>Status</th><th style="display:none">v_id</th><th style="display:none">dept_id</th><th style="display:none">fname</th><th style="display:none">lname</th><th style="display:none">mname</th><th style="display:none">strand</th><th style="display:none">section</th><th style="display:none">yearlevel</th><th>Action</th></tr></thead>
                                                                <tbody>
                                                                <?php
                                                                // Show the same set of voters as General → Verified Voters,
                                                                // just with an extra Department column for assignment.
                                                                $sql = "SELECT v.*, d.department_name
                                                                        FROM voters v
                                                                        LEFT JOIN departments d ON v.department_id = d.department_id
                                                                        WHERE v.acad_id='$acad' AND v.is_verified = 1
                                                                        ORDER BY v.date_issued DESC";
                                                                $res = $conn->query($sql);
                                                                // If department_id column is missing (migration not run yet),
                                                                // fall back to simple voters query without join so table still works.
                                                                if (!$res && strpos($conn->error, 'Unknown column') !== false) {
                                                                    $sql = "SELECT * FROM voters WHERE acad_id='$acad' AND is_verified = 1 ORDER BY date_issued DESC";
                                                                    $res = $conn->query($sql);
                                                                }
                                                                $i = 1;
                                                                $verified_count = 0;
                                                                while ($res && ($row = $res->fetch_assoc())) {
                                                                    $verified_count++;
                                                                    $m = middle_initial($row['mname'], '');
                                                                    $status = ((int)$row['is_verified'] === 1) ? '<span class="badge badge-success">Verified</span>' : '<span class="badge badge-warning">Unverified</span>';
                                                                    echo '<tr><td>' . $i++ . '</td><td>' . htmlspecialchars($row['stud_id']) . '</td><td class="text-uppercase">' . htmlspecialchars($row['lname'] . ', ' . $row['fname'] . ' ' . $m) . '</td><td>' . (int)$row['grade_level'] . '</td><td>' . htmlspecialchars($row['strand']) . '</td><td>' . htmlspecialchars($row['section']) . '</td><td>' . htmlspecialchars($row['department_name'] ?? '-') . '</td><td>' . $status . '</td>';
                                                                    echo '<td style="display:none">' . $row['v_id'] . '</td><td style="display:none">' . $row['department_id'] . '</td><td style="display:none">' . htmlspecialchars($row['fname']) . '</td><td style="display:none">' . htmlspecialchars($row['lname']) . '</td><td style="display:none">' . htmlspecialchars($row['mname']) . '</td><td style="display:none">' . htmlspecialchars($row['strand']) . '</td><td style="display:none">' . htmlspecialchars($row['section']) . '</td><td style="display:none">' . $row['grade_level'] . '</td>';
                                                                    echo '<td><a href="#default-Modal" class="edit badge badge-warning p-2 text-white" title="Edit"><i class="fa fa-edit"></i></a> | <a href="#" class="delete badge badge-danger p-2 text-white" title="Delete"><i class="fa fa-trash"></i></a> | <a href="?re=' . $row['v_id'] . '" class="myd badge badge-info p-2 text-white" title="Reset password"><i class="fa fa-recycle"></i></a></td></tr>';
                                                                }
                                                                if ($verified_count === 0) {
                                                                    echo '<tr><td colspan="16" class="text-center text-muted py-4">No data available in table</td></tr>';
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
    <script>$(document).ready(function(){ $('.theme-loader').fadeOut(400, function(){ $(this).remove(); }); });</script>
    <?php if (isset($_SESSION['response'])) { ?>
    <script>swal({ title: "<?php echo addslashes($_SESSION['response']); ?>", icon: "<?php echo $_SESSION['type']; ?>", button: "OK" });</script>
    <?php unset($_SESSION['response'], $_SESSION['type']); } ?>
    <script>
    $(document).ready(function () {
        if (window.history.replaceState) window.history.replaceState(null, null, window.location.href);
        $("#studid").keyup(function () {
            var p = $(this).val();
            $.ajax({ url: "ajax/fetchid_dept.php", method: "POST", data: { myids: p }, dataType: "json",
                success: function (html) {
                    if (html.stat == 1) { $('#available').html('<span class="text-danger">Already registered in department voters</span>'); $('#submits').attr('disabled', true); }
                    else { $('#available').html('<span class="text-success">Available</span>'); $('#submits').attr('disabled', false); }
                }
            });
        });
        $(document).on('click', '.edit', function () {
            var $tr = $(this).closest('tr');
            var tds = $tr.find('td');
            // Column indices (including hidden):
            // 0:#, 1:stud_id, 2:full name, 3:year, 4:strand, 5:section, 6:department, 7:status,
            // 8:v_id, 9:dept_id, 10:fname, 11:lname, 12:mname, 13:strand, 14:section, 15:yearlevel, 16:action
            $('#idhidden').val($.trim(tds.eq(8).text()));          // v_id
            $('#studid').val($.trim(tds.eq(1).text())).attr('readonly', true);
            $('#department_id').val($.trim(tds.eq(9).text()));
            $('#fname').val($.trim(tds.eq(10).text()));
            $('#lname').val($.trim(tds.eq(11).text()));
            $('#mname').val($.trim(tds.eq(12).text()));
            $('#strand').val($.trim(tds.eq(13).text()));
            $('#section').val($.trim(tds.eq(14).text()));
            $('#yearlevel').val($.trim(tds.eq(15).text()));
            $('#default-Modal').modal('show');
            $('.modal-titles').html('Update Department Voter');
            $('#submits').remove();
            if ($('#update').length === 0) $('#footersa').append('<button type="submit" class="btn btn-primary" name="update" id="update">Update</button>');
        });
        $(document).on('click', '.delete', function (e) {
            e.preventDefault();
            var currentRow = $(this).closest("tr");
            // Hidden v_id is in column index 8
            var vId = $.trim(currentRow.find("td:eq(8)").text());
            swal({
                title: "Are you sure?",
                text: "Once deleted, you will be able to recover this department voter in Archives.",
                icon: "warning",
                buttons: true,
                dangerMode: true,
            }).then(function (willDelete) {
                if (willDelete) {
                    $.ajax({
                        method: "POST",
                        url: "ajax/delete_dept_voter.php",
                        data: { myids: vId },
                        success: function () {
                            swal("Poof! The department voter has been archived.", {
                                icon: "success",
                            }).then(function () {
                                location.reload();
                            });
                        }
                    });
                } else {
                    swal("The department voter is safe!");
                }
            });
        });
    });
    </script>
</body>
</html>
