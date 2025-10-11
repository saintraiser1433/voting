<?php
include '../connection.php';
$acad = $_SESSION['acad'];
if (!isset($_SESSION['at'])) {
    header("Location:logout.php");
}

if (isset($_POST['submit'])) {
    $course_code = $_POST['course_code'];
    $course_name = $_POST['course_name'];
    
    // Check if course code already exists
    $check_sql = "SELECT * FROM courses WHERE course_code='$course_code' AND acad_id='$acad'";
    $check_result = $conn->query($check_sql);
    
    if ($check_result->num_rows > 0) {
        $_SESSION['response'] = "Course code already exists";
        $_SESSION['type'] = "warning";
    } else {
        $sql = "INSERT INTO courses (course_code, course_name, acad_id) VALUES ('$course_code', '$course_name', '$acad')";
        if ($conn->query($sql)) {
            $_SESSION['response'] = "Course successfully added";
            $_SESSION['type'] = "success";
        } else {
            $_SESSION['response'] = "An error has occurred";
            $_SESSION['type'] = "warning";
        }
    }
}

if (isset($_POST['update'])) {
    $course_id = $_POST['course_id'];
    $course_code = $_POST['course_code'];
    $course_name = $_POST['course_name'];
    
    // Check if course code already exists (excluding current record)
    $check_sql = "SELECT * FROM courses WHERE course_code='$course_code' AND acad_id='$acad' AND course_id != '$course_id'";
    $check_result = $conn->query($check_sql);
    
    if ($check_result->num_rows > 0) {
        $_SESSION['response'] = "Course code already exists";
        $_SESSION['type'] = "warning";
    } else {
        $sql = "UPDATE courses SET course_code='$course_code', course_name='$course_name' WHERE course_id='$course_id'";
        if ($conn->query($sql)) {
            $_SESSION['response'] = "Course successfully updated";
            $_SESSION['type'] = "success";
        } else {
            $_SESSION['response'] = "An error has occurred";
            $_SESSION['type'] = "warning";
        }
    }
}

if (isset($_POST['delete'])) {
    $course_id = $_POST['delete_id'];
    $sql = "DELETE FROM courses WHERE course_id='$course_id'";
    if ($conn->query($sql)) {
        $_SESSION['response'] = "Course successfully deleted";
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
    <!-- Pre-loader start -->
    <div class="theme-loader">
        <div class="ball-scale">
            <div class='contain'>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Pre-loader end -->
    <div id="pcoded" class="pcoded">
        <div class="pcoded-overlay-box"></div>
        <div class="pcoded-container navbar-wrapper">

            <?php include 'nav/topbar.php'; ?>

            <div class="pcoded-main-container">
                <div class="pcoded-wrapper">
                    <?php include 'nav/sidebar.php'; ?>

                    <!-- Modal -->
                    <div class="modal fade" id="default-Modal" tabindex="-1" role="dialog" aria-hidden="true"
                        data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-titles">Add Course</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                        onclick="location.reload();">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="" method="post">
                                    <div class="modal-body">
                                        <input type="hidden" name="course_id" id="course_id">
                                        <div class="form-group">
                                            <label class="col-form-label">Course Code</label>
                                            <input type="text" name="course_code" id="course_code"
                                                class="form-control text-uppercase" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label">Course Name</label>
                                            <input type="text" name="course_name" id="course_name"
                                                class="form-control" required>
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
                                                                    <h3 class="m-b-5"><b>MANAGE COURSES</b></h3>
                                                                </div>
                                                                <div class="col col-auto text-right">
                                                                    <button type="button"
                                                                        class="btn btn-mat btn-warning"
                                                                        data-toggle="modal"
                                                                        data-target="#default-Modal"><i
                                                                            class="fa fa-plus text-white"></i></button>
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
                                                                        <th style="display: none;">ID</th>
                                                                        <th>Course Code</th>
                                                                        <th>Course Name</th>
                                                                        <th>Date Created</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $sql = "SELECT * FROM courses WHERE acad_id='$acad' AND status=1 ORDER BY course_code ASC";
                                                                    $rs = $conn->query($sql);
                                                                    $i = 1;
                                                                    
                                                                    // Debug: Show query and result count
                                                                    if (!$rs) {
                                                                        echo "<tr><td colspan='6' class='text-danger'>Error: " . $conn->error . "</td></tr>";
                                                                    } else if ($rs->num_rows == 0) {
                                                                        echo "<tr><td colspan='6' class='text-warning'>No courses found for academic year: $acad</td></tr>";
                                                                    }
                                                                    
                                                                    while ($row = $rs->fetch_assoc()) {
                                                                        ?>
                                                                        <tr>
                                                                            <th scope="row"><?php echo $i++; ?></th>
                                                                            <td style="display:none"><?php echo $row['course_id']; ?></td>
                                                                            <td class="text-uppercase"><?php echo $row['course_code']; ?></td>
                                                                            <td><?php echo $row['course_name']; ?></td>
                                                                            <td><?php echo date('M d, Y', strtotime($row['date_created'])); ?></td>
                                                                            <td>
                                                                                <a href="#default-Modal"
                                                                                    class="edit badge badge-warning p-2 text-white"
                                                                                    title="Edit"><i
                                                                                        class="fa fa-edit"></i></a> |
                                                                                <a href="#"
                                                                                    class="delete badge badge-danger p-2 text-white"
                                                                                    title="Delete"><i
                                                                                        class="fa fa-trash"></i></a>
                                                                            </td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th style="display: none;">ID</th>
                                                                        <th>Course Code</th>
                                                                        <th>Course Name</th>
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

    <!-- Required Jquery -->
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

            setInterval(() => {
                $.ajax({
                    url: "ajax/checkYear.php",
                    success: function (datas) {
                    }
                });
            }, 1000);

            $(document).on('click', '.edit', function () {
                $('#default-Modal').modal('show');
                $tr = $(this).closest('tr');
                var data = $tr.children("td").map(function () {
                    return $(this).text();
                }).get();
                
                $('#course_id').val(data[1]);
                $('#course_code').val(data[2]);
                $('#course_name').val(data[3]);
                $('.modal-titles').html('Update Course');
                $('#submits').remove();
                $('#footersa').append('<button type="submit" class="btn btn-primary waves-effect waves-light" name="update" id="update">Save changes</button>');
            });

            $(document).on('click', '.delete', function (e) {
                e.preventDefault();
                var currentRow = $(this).closest("tr");
                var course_id = currentRow.find("td:eq(1)").text();
                var course_code = currentRow.find("td:eq(2)").text();
                
                swal({
                    title: "Are you sure?",
                    text: "You want to delete course: " + course_code + "?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                .then((willDelete) => {
                    if (willDelete) {
                        $('<form>', {
                            'method': 'POST',
                            'action': '',
                            'html': '<input type="hidden" name="delete_id" value="' + course_id + '">'
                        }).appendTo('body').submit();
                    }
                });
            });
        });
    </script>
</body>

</html>
