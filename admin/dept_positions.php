<?php
include '../connection.php';
$acad = $_SESSION['acad'];
if (!isset($_SESSION['at'])) {
    header("Location: ../logout.php");
    exit;
}

if (isset($_POST['submits'])) {
    $description = $conn->real_escape_string($_POST['position']);
    $maxvote = (int) $_POST['maxvote'];
    $q = $conn->query("SELECT COALESCE(MAX(priority), 0) + 1 AS next_priority FROM dept_position");
    $priority = $q && $row = $q->fetch_assoc() ? $row['next_priority'] : 1;
    $sql = "INSERT INTO dept_position (description, max_vote, acad_id, priority) VALUES ('$description', '$maxvote', '$acad', '$priority')";
    if ($conn->query($sql)) {
        $_SESSION['response'] = "Department position added.";
        $_SESSION['type'] = "success";
    } else {
        $_SESSION['response'] = "An error has occurred.";
        $_SESSION['type'] = "warning";
    }
}
if (isset($_POST['update'])) {
    $id = (int) $_POST['idhidden'];
    $description = $conn->real_escape_string($_POST['position']);
    $maxvote = (int) $_POST['maxvote'];
    $sql = "UPDATE dept_position SET description='$description', max_vote='$maxvote' WHERE dp_id='$id'";
    if ($conn->query($sql)) {
        $_SESSION['response'] = "Department position updated.";
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
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header"><h4 class="modal-titles">Department Position</h4><button type="button" class="close" data-dismiss="modal" onclick="location.reload();"><span>&times;</span></button></div>
                                <form action="" method="post">
                                    <div class="modal-body">
                                        <input type="hidden" name="idhidden" id="idhidden">
                                        <div class="form-group"><label class="col-form-label">Position</label><input type="text" name="position" class="form-control text-uppercase" id="position"></div>
                                        <div class="form-group"><label class="col-form-label">Maximum Vote</label><input type="number" class="form-control" name="maxvote" id="maxvote" min="1"></div>
                                    </div>
                                    <div class="modal-footer" id="footersa">
                                        <button type="submit" class="btn btn-primary" name="submits" id="submits">Save</button>
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
                                            <div class="col-xl-12 col-md-12">
                                                <div class="card">
                                                    <div class="card bg-c-green text-white">
                                                        <div class="card-block">
                                                            <div class="row align-items-center">
                                                                <div class="col"><h3 class="m-b-5"><b>Department Positions</b></h3></div>
                                                                <div class="col col-auto text-right"><button type="button" class="btn btn-mat btn-warning" data-toggle="modal" data-target="#default-Modal"><i class="fa fa-plus text-white"></i></button></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-block-big">
                                                        <div class="dt-responsive table-responsive">
                                                            <table class="table table-striped table-bordered nowrap w-100" id="simpletable">
                                                                <thead><tr><th>#</th><th style="display:none">dp_id</th><th>Position</th><th>Maximum Vote</th><th>Action</th></tr></thead>
                                                                <tbody>
                                                                <?php
                                                                $sql = "SELECT * FROM dept_position WHERE acad_id='$acad' ORDER BY priority ASC";
                                                                $rs = $conn->query($sql);
                                                                $i = 1;
                                                                while ($row = $rs ? $rs->fetch_assoc() : null) {
                                                                    if (!$row) break;
                                                                    echo '<tr><td>' . $i++ . '</td><td style="display:none">' . $row['dp_id'] . '</td><td class="text-uppercase">' . htmlspecialchars($row['description']) . '</td><td>' . (int)$row['max_vote'] . '</td>';
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
    $(document).ready(function () {
        if (window.history.replaceState) window.history.replaceState(null, null, window.location.href);
        $(document).on('click', '.edit', function () {
            var $tr = $(this).closest('tr');
            var tds = $tr.find('td');
            $('#idhidden').val($.trim(tds.eq(1).text()));
            $('#position').val($.trim(tds.eq(2).text()));
            $('#maxvote').val($.trim(tds.eq(3).text()));
            $('#default-Modal').modal('show');
            $('.modal-titles').html('Update Department Position');
            $('#submits').remove();
            if ($('#update').length === 0) $('#footersa').append('<button type="submit" class="btn btn-primary" name="update" id="update">Update</button>');
        });
        $(document).on('click', '.delete', function (e) {
            e.preventDefault();
            var dp_id = $(this).closest('tr').find('td').eq(1).text();
            swal({ title: "Are you sure?", text: "Delete this department position? (Remove candidates first if any.)", icon: "warning", buttons: true, dangerMode: true })
                .then(function(willDelete) {
                    if (willDelete) {
                        $.ajax({ method: "POST", url: "ajax/delete_dept_position.php", data: { myids: dp_id },
                            success: function () { swal("Deleted.", { icon: "success" }).then(function() { location.reload(); }); }
                        });
                    }
                });
        });
    });
    </script>
</body>
</html>
