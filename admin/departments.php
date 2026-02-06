<?php
include '../connection.php';
$acad = $_SESSION['acad'];
if (!isset($_SESSION['at'])) {
    header("Location:logout.php");
}

// Helper to sync selected courses for a department
function sync_department_courses($conn, $acad, $departmentId, $selectedCourseIds) {
    // Clear this department from all its current courses
    $conn->query("UPDATE courses SET department_id = NULL WHERE acad_id = '$acad' AND department_id = '$departmentId'");

    if (!empty($selectedCourseIds) && is_array($selectedCourseIds)) {
        $ids = array_map('intval', $selectedCourseIds);
        $idList = implode(',', $ids);
        $conn->query("UPDATE courses SET department_id = '$departmentId' WHERE course_id IN ($idList) AND acad_id = '$acad'");
    }
}

// Create
if (isset($_POST['submit'])) {
    $code = $_POST['department_code'];
    $name = $_POST['department_name'];
    $courses = isset($_POST['courses']) ? $_POST['courses'] : [];

    // Only block duplicates among ACTIVE departments
    $check = $conn->query("SELECT * FROM departments WHERE department_code='$code' AND acad_id='$acad' AND status=1");
    if ($check && $check->num_rows > 0) {
        $_SESSION['response'] = "Department code already exists";
        $_SESSION['type'] = "warning";
    } else {
        $sql = "INSERT INTO departments (department_code, department_name, acad_id, status)
                VALUES ('$code', '$name', '$acad', 1)";
        if ($conn->query($sql)) {
            $departmentId = $conn->insert_id;
            sync_department_courses($conn, $acad, $departmentId, $courses);
            $_SESSION['response'] = "Department successfully added";
            $_SESSION['type'] = "success";
        } else {
            $_SESSION['response'] = "An error has occurred";
            $_SESSION['type'] = "warning";
        }
    }
}

// Update
if (isset($_POST['update'])) {
    $id = $_POST['department_id'];
    $code = $_POST['department_code'];
    $name = $_POST['department_name'];
    $courses = isset($_POST['courses']) ? $_POST['courses'] : [];

    // Only block duplicates among ACTIVE departments
    $check = $conn->query("SELECT * FROM departments WHERE department_code='$code' AND acad_id='$acad' AND status=1 AND department_id != '$id'");
    if ($check && $check->num_rows > 0) {
        $_SESSION['response'] = "Department code already exists";
        $_SESSION['type'] = "warning";
    } else {
        $sql = "UPDATE departments
                SET department_code='$code', department_name='$name'
                WHERE department_id='$id'";
        if ($conn->query($sql)) {
            sync_department_courses($conn, $acad, $id, $courses);
            $_SESSION['response'] = "Department successfully updated";
            $_SESSION['type'] = "success";
        } else {
            $_SESSION['response'] = "An error has occurred";
            $_SESSION['type'] = "warning";
        }
    }
}

// Soft delete (set status=0)
if (isset($_POST['delete'])) {
    $id = $_POST['delete_id'];
    // Archive department
    $sql = "UPDATE departments SET status=0 WHERE department_id='$id'";
    // Also detach all courses from this department so they can be reused
    $sqlCourses = "UPDATE courses SET department_id = NULL WHERE acad_id='$acad' AND department_id='$id'";

    if ($conn->query($sql) && $conn->query($sqlCourses)) {
        $_SESSION['response'] = "Department successfully archived and courses detached";
        $_SESSION['type'] = "success";
    } else {
        $_SESSION['response'] = "An error has occurred";
        $_SESSION['type'] = "warning";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<?php include 'nav/header.php'; ?>

<body>
    <div class="theme-loader">
        <div class="ball-scale">
            <div class='contain'>
                <div class="ring"><div class="frame"></div></div>
                <div class="ring"><div class="frame"></div></div>
                <div class="ring"><div class="frame"></div></div>
                <div class="ring"><div class="frame"></div></div>
                <div class="ring"><div class="frame"></div></div>
                <div class="ring"><div class="frame"></div></div>
                <div class="ring"><div class="frame"></div></div>
                <div class="ring"><div class="frame"></div></div>
                <div class="ring"><div class="frame"></div></div>
                <div class="ring"><div class="frame"></div></div>
            </div>
        </div>
    </div>

    <div id="pcoded" class="pcoded">
        <div class="pcoded-overlay-box"></div>
        <div class="pcoded-container navbar-wrapper">

            <?php include 'nav/topbar.php'; ?>

            <div class="pcoded-main-container">
                <div class="pcoded-wrapper">
                    <?php include 'nav/sidebar.php'; ?>

                    <!-- Modal add/update -->
                    <div class="modal fade" id="default-Modal" tabindex="-1" role="dialog" aria-hidden="true"
                        data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-titles">Add Department</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                        onclick="location.reload();">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="" method="post">
                                    <div class="modal-body">
                                        <input type="hidden" name="department_id" id="department_id">
                                        <div class="form-group">
                                            <label class="col-form-label">Department Code</label>
                                            <input type="text" name="department_code" id="department_code"
                                                class="form-control text-uppercase" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label">Department Name</label>
                                            <input type="text" name="department_name" id="department_name"
                                                class="form-control" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label d-block">Courses under this Department</label>
                                            <div id="course-list" style="max-height: 200px; overflow-y: auto; border: 1px solid #e0e0e0; padding: 8px; border-radius: 4px;">
                                                <?php
                                                $csql = "SELECT * FROM courses WHERE acad_id='$acad' AND status=1 ORDER BY course_code ASC";
                                                $crs = $conn->query($csql);
                                                if ($crs) {
                                                    while ($crow = $crs->fetch_assoc()) {
                                                        echo '<div class="form-check">';
                                                        echo '<label class="form-check-label">';
                                                        echo '<input type="checkbox" class="form-check-input course-item" name="courses[]" value="' . $crow['course_id'] . '" data-dept="' . $crow['department_id'] . '"> ';
                                                        echo '<span class="text-uppercase">' . $crow['course_code'] . '</span> - ' . $crow['course_name'];
                                                        echo '</label>';
                                                        echo '</div>';
                                                    }
                                                }
                                                ?>
                                            </div>
                                            <small class="text-muted">Tick the courses that belong to this department.</small>
                                        </div>
                                    </div>
                                    <div class="modal-footer" id="footersa">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light"
                                            name="submit" id="submits">Save changes</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Confirm delete modal (simple POST) -->
                    <div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-hidden="true"
                        data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Archive Department</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                        onclick="location.reload();">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="" method="post">
                                    <div class="modal-body">
                                        <input type="hidden" name="delete_id" id="delete_id">
                                        <p class="mb-0">Are you sure you want to archive this department?</p>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light"
                                            name="delete">Yes, archive</button>
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
                                                                <div class="col">
                                                                    <h3 class="m-b-5"><b>MANAGE DEPARTMENTS</b></h3>
                                                                </div>
                                                                <div class="col col-auto text-right">
                                                                    <button type="button"
                                                                        class="btn btn-mat btn-warning"
                                                                        data-toggle="modal"
                                                                        data-target="#default-Modal">
                                                                        <i class="fa fa-plus text-white"></i>
                                                                    </button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>

                                                    <div class="card-block-big">
                                                        <div class="dt-responsive table-responsive">
                                                            <table id="simpletable"
                                                                class="table table-striped table-bordered nowrap w-100">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th style="display:none;">ID</th>
                                                                        <th>Department Code</th>
                                                                        <th>Department Name</th>
                                                                        <th>Courses</th>
                                                                        <th style="display:none;">Course IDs</th>
                                                                        <th>Date Created</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $sql = "SELECT * FROM departments WHERE acad_id='$acad' AND status=1 ORDER BY department_code ASC";
                                                                    $rs = $conn->query($sql);
                                                                    $i = 1;
                                                                    if ($rs) {
                                                                        while ($row = $rs->fetch_assoc()) {
                                                                            // Fetch courses belonging to this department
                                                                            $courseNames = [];
                                                                            $courseIds = [];
                                                                            $csql = "SELECT course_id, course_code FROM courses WHERE acad_id='$acad' AND status=1 AND department_id='" . $row['department_id'] . "' ORDER BY course_code ASC";
                                                                            $crs = $conn->query($csql);
                                                                            if ($crs) {
                                                                                while ($crow = $crs->fetch_assoc()) {
                                                                                    $courseNames[] = $crow['course_code'];
                                                                                    $courseIds[] = $crow['course_id'];
                                                                                }
                                                                            }
                                                                            $courseNamesStr = implode(', ', $courseNames);
                                                                            $courseIdsStr = implode(',', $courseIds);
                                                                            ?>
                                                                            <tr>
                                                                                <th scope="row"><?php echo $i++; ?></th>
                                                                                <td style="display:none;"><?php echo $row['department_id']; ?></td>
                                                                                <td class="text-uppercase"><?php echo $row['department_code']; ?></td>
                                                                                <td><?php echo $row['department_name']; ?></td>
                                                                                <td><?php echo $courseNamesStr; ?></td>
                                                                                <td style="display:none;"><?php echo $courseIdsStr; ?></td>
                                                                                <td><?php echo date('M d, Y', strtotime($row['date_created'])); ?></td>
                                                                                <td>
                                                                                    <a href="#default-Modal"
                                                                                        class="edit badge badge-warning p-2 text-white"
                                                                                        title="Edit">
                                                                                        <i class="fa fa-edit"></i>
                                                                                    </a> |
                                                                                    <a href="#"
                                                                                        class="delete badge badge-danger p-2 text-white"
                                                                                        title="Archive">
                                                                                        <i class="fa fa-archive"></i>
                                                                                    </a>
                                                                                </td>
                                                                            </tr>
                                                                            <?php
                                                                        }
                                                                    }
                                                                    ?>
                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th style="display:none;">ID</th>
                                                                        <th>Department Code</th>
                                                                        <th>Department Name</th>
                                                                        <th>Courses</th>
                                                                        <th style="display:none;">Course IDs</th>
                                                                        <th>Date Created</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </tfoot>
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
    <?php include 'modalelection.php'; ?>

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

    <script>
        $(document).ready(function () {
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }

            // When opening the modal via the Add button, show all courses and clear selection
            $('#default-Modal').on('show.bs.modal', function (e) {
                var trigger = $(e.relatedTarget);
                if (trigger && trigger.hasClass('btn-mat')) {
                    // Add mode
                    $('#department_id').val('');
                    $('#department_code').val('');
                    $('#department_name').val('');
                    $('.course-item').each(function () {
                        $(this).prop('checked', false).prop('disabled', false);
                        $(this).closest('.form-check').show();
                    });
                    $('.modal-titles').html('Add Department');
                    $('#update').remove();
                    if (!$('#submits').length) {
                        $('#footersa').append('<button type="submit" class="btn btn-primary waves-effect waves-light" name="submit" id="submits">Save changes</button>');
                    }
                }
            });

            $(document).on('click', '.edit', function () {
                $('#default-Modal').modal('show');
                var $tr = $(this).closest('tr');
                var data = $tr.children("td").map(function () {
                    return $(this).text();
                }).get();

                $('#department_id').val(data[0]);
                $('#department_code').val(data[1]);
                $('#department_name').val(data[2]);

                // Pre-select courses for this department (course IDs are in data[4], comma-separated)
                var courseIds = [];
                if (data[4]) {
                    courseIds = data[4].split(',').filter(function (v) { return v !== ''; });
                }

                // Show all courses; check those currently belonging to this department
                $('.course-item').each(function () {
                    var courseId = $(this).val();
                    $(this).prop('disabled', false);
                    $(this).closest('.form-check').show();

                    if (courseIds.indexOf(courseId) !== -1) {
                        $(this).prop('checked', true);
                    } else {
                        $(this).prop('checked', false);
                    }
                });

                $('.modal-titles').html('Update Department');
                $('#submits').remove();
                if (!$('#update').length) {
                    $('#footersa').append('<button type="submit" class="btn btn-primary waves-effect waves-light" name="update" id="update">Save changes</button>');
                }
            });

            $(document).on('click', '.delete', function (e) {
                e.preventDefault();
                var $tr = $(this).closest('tr');
                var id = $tr.find("td:eq(0)").text();
                $('#delete_id').val(id);
                $('#deleteModal').modal('show');
            });
        });
    </script>
</body>

</html>

