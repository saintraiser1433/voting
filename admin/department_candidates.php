<?php
include '../connection.php';

$acad = $_SESSION['acad'];
if (!isset($_SESSION['at'])) {
    header("Location:logout.php");
}

// Add department candidate
if (isset($_POST['submit'])) {
    $stud = $_POST['studid'];
    $pos = $_POST['position'];

    // Resolve department from voter's course/strand
    $deptRes = $conn->query("
        SELECT d.department_id
        FROM voters v
        LEFT JOIN courses c ON v.strand = c.course_code AND c.acad_id = v.acad_id
        LEFT JOIN departments d ON c.department_id = d.department_id
        WHERE v.stud_id = '$stud' AND v.acad_id = '$acad'
        LIMIT 1
    ");
    $deptRow = $deptRes ? $deptRes->fetch_assoc() : null;
    $department_id = $deptRow ? $deptRow['department_id'] : null;

    if (empty($department_id)) {
        $_SESSION['response'] = "Student does not belong to a mapped department. Please check course/department setup.";
        $_SESSION['type'] = "warning";
    } else {
        if ($_FILES['files']['name'] == '') {
            $dirs = "libraries/img/glanlogo.png";
        } else {
            $imgfile = $_FILES["files"]["name"];
            $extension = substr($imgfile, strlen($imgfile) - 4, strlen($imgfile));
            $allowed_extensions = array(".jpg", ".jpeg", ".png", ".gif");
            $tmpname = $_FILES['files']['tmp_name'];
            if (!in_array($extension, $allowed_extensions)) {
                echo "<script>alert('Invalid format. Only jpg / jpeg/ png /gif format allowed');</script>";
            } else {
                $imgnewfile = md5($imgfile) . $extension;
                $dir = "../candidatephoto/" . $imgnewfile;
                $dirs = "candidatephoto/" . $imgnewfile;
                move_uploaded_file($tmpname, $dir);
            }
        }

        $sql = "INSERT INTO candidate (acad_id, p_id, pos_id, stud_id, img, platform, election_type, department_id)
                VALUES ('$acad', 0, '$pos', '$stud', '$dirs', NULL, 'department', '$department_id')";
        if ($conn->query($sql)) {
            $_SESSION['response'] = "Department candidate successfully added";
            $_SESSION['type'] = "success";
        } else {
            $_SESSION['response'] = "An error has occurred";
            $_SESSION['type'] = "warning";
        }
    }
}

// Update department candidate
if (isset($_POST['update'])) {
    $cid = $_POST['idhidden'];
    $pos = $_POST['position'];

    if ($_FILES['files']['name'] == '') {
        $sql = "UPDATE candidate SET pos_id='$pos' WHERE c_id='$cid'";
        if ($conn->query($sql)) {
            $_SESSION['response'] = "Department candidate successfully updated";
            $_SESSION['type'] = "success";
        } else {
            $_SESSION['response'] = "An error has occurred";
            $_SESSION['type'] = "warning";
        }
    } else {
        $imgfile = $_FILES["files"]["name"];
        $extension = substr($imgfile, strlen($imgfile) - 4, strlen($imgfile));
        $allowed_extensions = array(".jpg", ".jpeg", ".png", ".gif");
        $tmpname = $_FILES['files']['tmp_name'];
        if (!in_array($extension, $allowed_extensions)) {
            echo "<script>alert('Invalid format. Only jpg / jpeg/ png /gif format allowed');</script>";
        } else {
            $imgnewfile = md5($imgfile) . $extension;
            $dir = "../candidatephoto/" . $imgnewfile;
            $dirs = "candidatephoto/" . $imgnewfile;
            move_uploaded_file($tmpname, $dir);
            $sql = "UPDATE candidate SET pos_id='$pos', img='$dirs' WHERE c_id='$cid'";
            if ($conn->query($sql)) {
                $_SESSION['response'] = "Department candidate successfully updated";
                $_SESSION['type'] = "success";
            } else {
                $_SESSION['response'] = "An error has occurred";
                $_SESSION['type'] = "warning";
            }
        }
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
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-titles">Add Department Candidate</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                        onclick="location.reload();">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="" method="post" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="card p-2 text-center font-weight-bold">CANDIDATE PROFILE PICTURE</div>
                                                <center>
                                                    <img id="ImdID" class="img-fluid rounded-circle"
                                                         src="../libraries/img/glanlogo.png" alt="Image"
                                                         style="width:200px; height:200px;">
                                                </center>
                                                <br><br>
                                                <div class="form-group">
                                                    <input type="file" name="files" id="filer_input_single"
                                                        class="form-control" onchange="readURL(this);" />
                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Student ID</label>
                                                    <div class="row">
                                                        <div class="col-9">
                                                            <input type="text" name="studid" class="form-control"
                                                                id="studid" required>
                                                            <input type="hidden" name="idhidden" id="idhidden">
                                                            <div id="availability"></div>
                                                        </div>
                                                        <div class="col">
                                                            <button type="button" class="btn btn-primary col-form-label"
                                                                id="searchz"><i class="fa fa-search"></i></button>
                                                        </div>
                                                    </div>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-form-label">Full Name</label>
                                                    <input type="text" name="fname" class="form-control text-uppercase"
                                                        id="fname" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-form-label">Course / Strand</label>
                                                    <input type="text" name="course" class="form-control text-uppercase"
                                                        id="course" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-form-label">Department</label>
                                                    <input type="text" name="dept" class="form-control text-uppercase"
                                                        id="dept" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-form-label">Position</label>
                                                    <select name="position" class="form-control text-uppercase"
                                                        id="position" required>
                                                        <option value=""></option>
                                                        <?php
                                                        $sql = "SELECT * FROM position WHERE acad_id='$acad' AND election_type='department' ORDER BY priority";
                                                        $rs = $conn->query($sql);
                                                        while ($row = $rs->fetch_assoc()) {
                                                            echo '<option value="' . $row['pos_id'] . '">' . $row['description'] . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                            </div>
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
                                                                    <h3 class="m-b-5 text-uppercase">
                                                                        <b>DEPARTMENT CANDIDATES</b>
                                                                    </h3>
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
                                                                        <th style="display:none;"></th>
                                                                        <th>Img</th>
                                                                        <th>Candidate Name</th>
                                                                        <th>Position</th>
                                                                        <th>Department</th>
                                                                        <th style="display:none;"></th>
                                                                        <th style="display:none;"></th>
                                                                        <th style="display:none;"></th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $sql = "SELECT c.c_id, c.img as myimg, v.fname, v.lname, v.mname,
                                                                                p.description, c.pos_id, v.stud_id,
                                                                                d.department_name
                                                                            FROM candidate c
                                                                            INNER JOIN voters v ON c.stud_id = v.stud_id
                                                                            LEFT JOIN position p ON c.pos_id = p.pos_id
                                                                            LEFT JOIN departments d ON c.department_id = d.department_id
                                                                            WHERE c.acad_id='$acad' AND c.election_type='department'
                                                                            ORDER BY d.department_name, p.priority";
                                                                    $rs = $conn->query($sql);
                                                                    $i = 1;
                                                                    while ($row = $rs->fetch_assoc()) {
                                                                        ?>
                                                                        <tr>
                                                                            <th scope="row"><?php echo $i++; ?></th>
                                                                            <td style="display:none;"><?php echo $row['c_id']; ?></td>
                                                                            <td><img src="../<?php echo $row['myimg']; ?>"
                                                                                    class="rounded-circle"
                                                                                    style="width:30px;height:30px;"></td>
                                                                            <td class="text-uppercase">
                                                                                <?php echo $row['lname'] . ", " . $row['fname'] . " " . $row['mname'][0]; ?>
                                                                            </td>
                                                                            <td class="text-uppercase"><?php echo $row['description']; ?></td>
                                                                            <td><?php echo $row['department_name']; ?></td>
                                                                            <td style="display:none;"><?php echo $row['pos_id']; ?></td>
                                                                            <td style="display:none;"><?php echo $row['stud_id']; ?></td>
                                                                            <td style="display:none;"><?php echo $row['myimg']; ?></td>
                                                                            <td>
                                                                                <a href="#default-Modal"
                                                                                    class="edit badge badge-warning p-2 text-white"
                                                                                    title="Edit">
                                                                                    <i class="fa fa-edit"></i>
                                                                                </a> |
                                                                                <a href="#"
                                                                                    class="delete badge badge-danger p-2 text-white"
                                                                                    title="Delete">
                                                                                    <i class="fa fa-trash"></i>
                                                                                </a>
                                                                            </td>
                                                                        </tr>
                                                                        <?php
                                                                    }
                                                                    ?>
                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th style="display:none;"></th>
                                                                        <th>Img</th>
                                                                        <th>Candidate Name</th>
                                                                        <th>Position</th>
                                                                        <th>Department</th>
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
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $('#ImdID').attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        $(document).ready(function () {
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }

            $('#searchz').click(function () {
                var stud = $('#studid').val();
                $.ajax({
                    method: "POST",
                    url: "ajax/searchstudent.php",
                    data: { myids: stud },
                    dataType: "json",
                    success: function (html) {
                        if (html.stat == 0) {
                            alert('No result found');
                            $('#fname').val("");
                            $('#course').val("");
                            $('#dept').val("");
                            $('#submits').attr('disabled', true);
                        } else if (html.res == 1) {
                            $('#availability').html('<span class="text-danger">Already Candidate</span>');
                            $('#submits').attr('disabled', true);
                        } else {
                            $('#availability').html('');
                            $('#fname').val(html.fname);
                            $('#course').val(html.strand || '');
                            $('#dept').val(html.department || '');
                            $('#submits').attr('disabled', false);
                        }
                    }
                });
            });

            $(document).on('click', '.edit', function () {
                $('#default-Modal').modal('show');
                var $tr = $(this).closest('tr');
                var data = $tr.children("td").map(function () {
                    return $(this).text();
                }).get();

                $('#idhidden').val(data[0]);
                $('#position').val(data[4]);
                $('#studid').val(data[5]);
                $('#studid').attr('disabled', true);
                $('#searchz').attr('disabled', true);
                $('.modal-titles').html('Update Department Candidate');
                $('#ImdID').attr('src', '../' + data[6]);

                var stud = data[5];
                $.ajax({
                    method: "POST",
                    url: "ajax/searchstudent.php",
                    data: { myids: stud },
                    dataType: "json",
                    success: function (html) {
                        if (html.stat == 0) {
                            $('#fname').val("");
                            $('#course').val("");
                            $('#dept').val("");
                            $('#submits').attr('disabled', true);
                        } else {
                            $('#fname').val(html.fname);
                            $('#course').val(html.strand || '');
                            $('#dept').val(html.department || '');
                            $('#submits').attr('disabled', false);
                        }
                    }
                });

                $('#submits').remove();
                $('#footersa').append('<button type="submit" class="btn btn-primary waves-effect waves-light" name="update" id="update">Save changes</button>');
            });

            $(document).on('click', '.delete', function (e) {
                e.preventDefault();
                var currentRow = $(this).closest("tr");
                var col1 = currentRow.find("td:eq(0)").text();
                swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this candidate!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                }).then((willDelete) => {
                    if (willDelete) {
                        $.ajax({
                            method: "POST",
                            url: "ajax/deletecandidate.php",
                            data: { myids: col1 },
                            success: function (html) {
                                swal("Deleted!", { icon: "success" }).then(() => {
                                    location.reload();
                                });
                            }
                        });
                    } else {
                        swal("Candidate is safe!");
                    }
                });
            });
        });
    </script>
</body>

</html>

