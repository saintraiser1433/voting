<?php
include '../connection.php';
$acad = (int) $_SESSION['acad'];
if (!isset($_SESSION['at'])) {
    header("Location: ../logout.php");
    exit;
}

if (isset($_POST['submit'])) {
    $name = $conn->real_escape_string(trim($_POST['department_name'] ?? ''));
    if ($name !== '') {
        $sql = "INSERT INTO departments (department_name, status) VALUES ('$name', 1)";
        if ($conn->query($sql)) {
            $_SESSION['response'] = "Department added.";
            $_SESSION['type'] = "success";
        } else {
            $_SESSION['response'] = "An error has occurred.";
            $_SESSION['type'] = "warning";
        }
    }
}
if (isset($_POST['update'])) {
    $id = (int) $_POST['idhidden'];
    $name = $conn->real_escape_string(trim($_POST['department_name'] ?? ''));
    if ($name !== '' && $id > 0) {
        $sql = "UPDATE departments SET department_name='$name' WHERE department_id='$id'";
        if ($conn->query($sql)) {
            $_SESSION['response'] = "Department updated.";
            $_SESSION['type'] = "success";
        } else {
            $_SESSION['response'] = "An error has occurred.";
            $_SESSION['type'] = "warning";
        }
    }
}
if (isset($_POST['delete']) && isset($_POST['deleteid'])) {
    $id = (int) $_POST['deleteid'];
    if ($id > 0) {
        $sql = "UPDATE departments SET status=0 WHERE department_id='$id'";
        if ($conn->query($sql)) {
            $_SESSION['response'] = "Department archived.";
            $_SESSION['type'] = "success";
        } else {
            $_SESSION['response'] = "An error has occurred.";
            $_SESSION['type'] = "warning";
        }
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

                    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header"><h4 class="modal-title">Confirm</h4><button type="button" class="close" data-dismiss="modal" onclick="location.reload();"><span>&times;</span></button></div>
                                <form action="" method="post">
                                    <div class="modal-body"><input type="hidden" name="deleteid" id="deleteid"><p class="text-center">Archive this department?</p></div>
                                    <div class="modal-footer"><button type="submit" class="btn btn-primary" name="delete">Archive</button></div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <div class="modal fade" id="default-Modal" tabindex="-1" role="dialog" data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header"><h4 class="modal-titles">Department</h4><button type="button" class="close" data-dismiss="modal" onclick="location.reload();"><span>&times;</span></button></div>
                                <form action="" method="post">
                                    <div class="modal-body">
                                        <input type="hidden" name="idhidden" id="idhidden">
                                        <div class="form-group"><label class="col-form-label">Department Name</label><input type="text" name="department_name" id="department_name" class="form-control" required></div>
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
                                            <div class="col-12"><button type="button" class="btn btn-primary mb-3" data-toggle="modal" data-target="#default-Modal">Add Department</button></div>
                                            <div class="col-12">
                                                <div class="card">
                                                    <div class="card bg-c-green text-white"><div class="card-block"><h3 class="m-b-5"><b>Departments</b></h3></div></div>
                                                    <div class="card-block-big">
                                                        <div class="dt-responsive table-responsive">
                                                            <table class="table table-striped table-bordered nowrap w-100" id="simpletable">
                                                                <thead><tr><th>#</th><th style="display:none">id</th><th>Department Name</th><th>Action</th></tr></thead>
                                                                <tbody>
                                                                <?php
                                                                $sql = "SELECT department_id, department_name FROM departments WHERE status=1 ORDER BY department_name";
                                                                $rs = $conn->query($sql);
                                                                $i = 1;
                                                                if ($rs) {
                                                                    while ($row = $rs->fetch_assoc()) {
                                                                        echo '<tr><td>' . $i++ . '</td><td style="display:none">' . (int)$row['department_id'] . '</td><td>' . htmlspecialchars($row['department_name']) . '</td>';
                                                                        echo '<td><a href="#default-Modal" class="edit badge badge-warning p-2 text-white" title="Edit"><i class="fa fa-edit"></i></a> | <a href="#" class="del badge badge-danger p-2 text-white" title="Archive"><i class="fa fa-trash"></i></a></td></tr>';
                                                                    }
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
        $('.theme-loader').fadeOut(400, function () { $(this).remove(); });
        if (window.history.replaceState) window.history.replaceState(null, null, window.location.href);
        $(document).on('click', '.edit', function () {
            var $tr = $(this).closest('tr');
            $('#idhidden').val($tr.find('td').eq(1).text());
            $('#department_name').val($.trim($tr.find('td').eq(2).text()));
            $('#default-Modal').modal('show');
            $('.modal-titles').html('Update Department');
            $('#submits').remove();
            if ($('#update').length === 0) $('#footersa').append('<button type="submit" class="btn btn-primary" name="update" id="update">Update</button>');
        });
        $(document).on('click', '.del', function (e) {
            e.preventDefault();
            var id = $(this).closest('tr').find('td').eq(1).text();
            $('#deleteid').val(id);
            $('#deleteModal').modal('show');
        });
    });
    </script>
</body>
</html>
