<?php
include '../connection.php';
$acad = $_SESSION['acad'];
if (!isset($_SESSION['at'])) {
    header("Location:logout.php");
}


if (isset($_POST['update'])) {
    $vid = $_POST['idhidden'];
    $fname = $_POST['fname'];
    $mname = $_POST['mname'];
    $lname = $_POST['lname'];
    $gradelevel = $_POST['gradelevel'];
    $strand = $_POST['strand'];
    $section = $_POST['section'];
    $sql = "UPDATE voters SET fname='$fname',lname='$lname',mname='$mname',grade_level='$gradelevel',strand='$strand',section='$section' where v_id='$vid'";
    if ($conn->query($sql)) {
        $_SESSION['response'] = "Voters successfully updated";
        $_SESSION['type'] = "success";
    } else {
        $_SESSION['response'] = "An error has occurred";
        $_SESSION['type'] = "warning";
    }
}
if (isset($_GET['re'])) {
    $re = $_GET['re'];
    $pass = "";
    $sql = "UPDATE voters SET password='$pass' where v_id='$re'";
    if ($conn->query($sql)) {
        $_SESSION['response'] = "Voters password successfully reset";
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
<!-- Menu sidebar static layout -->

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
                    <div class="modal fade" id="delete" tabindex="-1" role="dialog" aria-hidden="true"
                        data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Ohhh Nooo!</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                        onclick="location.reload();">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="" method="post">
                                    <div class="modal-body">
                                        <input type="hidden" name="deleteid" id="deleteid">
                                        <h5 class="text-center text-primary">You want to delete this data?</h5>
                                    </div>
                                    <div class="modal-footer">

                                        <button type="submit" class="btn btn-primary waves-effect waves-light"
                                            name="delete" id="delete">Delete</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!--end -- >


                    <-- Modal -->
                    <div class="modal fade" id="default-Modal" tabindex="-1" role="dialog" aria-hidden="true"
                        data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-titles">Basic Information</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                        onclick="location.reload();">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="" method="post">
                                    <div class="modal-body">
                                        <div class="form-group">
                                            <input type="hidden" name="idhidden" id="idhidden"
                                                class="form-control text-uppercase" required>
                                            <label class="col-form-label">Student ID</label>
                                            <input type="text" name="studid" id="studid"
                                                class="form-control text-uppercase" required>
                                            <div id="available"></div>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label">First Name</label>
                                            <input type="text" name="fname" id="fname"
                                                class="form-control text-uppercase" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label">Last Name</label>
                                            <input type="text" name="lname" id="lname"
                                                class="form-control text-uppercase" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label">Middle Name</label>
                                            <input type="text" name="mname" id="mname"
                                                class="form-control text-uppercase" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="col-form-label">Grade Level</label>
                                            <select name="gradelevel" class="form-control" id="gradelevel" required>
                                                <option value=""></option>
                                                <option value="7">Grade 7</option>
                                                <option value="8">Grade 8</option>
                                                <option value="9">Grade 9</option>
                                                <option value="10">Grade 10</option>
                                                <option value="11">Grade 11</option>
                                                <option value="12">Grade 12</option>
                                            </select>
                                        </div>
                                        <div id="content"></div>
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
                                            <div class="col-12 col-lg-6">

                                                <div class="card">
                                                    <div class="card bg-c-green text-white">
                                                        <div class="card-block">
                                                            <div class="row align-items-center">
                                                                <div class="col">
                                                                    <h3 class="m-b-5"><b>UNVERIFIED VOTERS</b></h3>
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
                                                                        <th>Student ID</th>
                                                                        <th>Full Name</th>
                                                                        <th>Grade Level</th>
                                                                        <th>Strand</th>
                                                                        <th>Section</th>
                                                                        <th style="display:none"></th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $sql = "SELECT * FROM voters where acad_id='$acad' and is_verified = 0 order by date_issued DESC ";
                                                                    $res = $conn->query($sql);
                                                                    $i = 1;
                                                                    while ($row = $res->fetch_assoc()) {
                                                                        ?>
                                                                        <tr>
                                                                            <th scope="row"><?php echo $i++ ?></th>

                                                                            <td><?php echo $row['stud_id']; ?></td>
                                                                            <td class="text-uppercase">
                                                                                <?php echo $row['lname'] . ", " . $row['fname'] . " " . $row['mname'][0] ?>
                                                                            </td>
                                                                            <td><?php echo $row['grade_level']; ?></td>
                                                                            <td><?php
                                                                            if ($row['strand'] === "undefined") {

                                                                                echo "<span class='text-danger'>X</span>";
                                                                            } else {
                                                                                echo $row['strand'];
                                                                            }

                                                                            ?></td>
                                                                            <td><?php echo $row['section'] ?></td>
                                                                            <td style="display:none">
                                                                                <?php echo $row['v_id'] ?>
                                                                            </td>
                                                                            <td>
                                                                                <a href="#"
                                                                                    class="approve badge badge-success p-2 text-white"
                                                                                    title="Approve"><i
                                                                                        class="fa fa-thumbs-up"></i></a> |
                                                                                <a href="#"
                                                                                    class="disapprove badge badge-danger p-2 text-white"
                                                                                    title="Disapprove"><i
                                                                                        class="fa fa-thumbs-down"></i></a>
                                                                            </td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Student ID</th>
                                                                        <th>Full Name</th>
                                                                        <th>Grade Level</th>
                                                                        <th>Strand</th>
                                                                        <th>Section</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </tfoot>
                                                            </table>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-12 col-lg-6">

                                                <div class="card">
                                                    <div class="card bg-c-green text-white">
                                                        <div class="card-block">
                                                            <div class="row align-items-center">
                                                                <div class="col">
                                                                    <h3 class="m-b-5"><b>VERIFIED VOTERS</b></h3>
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
                                                                        <th>Student ID</th>
                                                                        <th>Full Name</th>
                                                                        <th>Grade Level</th>
                                                                        <th>Strand</th>
                                                                        <th>Section</th>
                                                                        <th style="display: none;"></th>
                                                                        <th style="display: none;"></th>
                                                                        <th style="display: none;"></th>
                                                                        <th style="display: none;"></th>
                                                                        <th style="display: none;"></th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $sql = "SELECT * FROM voters where acad_id='$acad'  and is_verified = 1 order by date_issued DESC";
                                                                    $res = $conn->query($sql);
                                                                    $i = 1;
                                                                    while ($row = $res->fetch_assoc()) {
                                                                        ?>
                                                                        <tr>
                                                                            <th scope="row"><?php echo $i++ ?></th>

                                                                            <td><?php echo $row['stud_id']; ?></td>
                                                                            <td class="text-uppercase">
                                                                                <?php echo $row['lname'] . ", " . $row['fname'] . " " . $row['mname'][0] ?>
                                                                            </td>
                                                                            <td><?php echo $row['grade_level']; ?></td>
                                                                            <td><?php
                                                                            if ($row['strand'] === "undefined") {

                                                                                echo "<span class='text-danger'>X</span>";
                                                                            } else {
                                                                                echo $row['strand'];
                                                                            }

                                                                            ?></td>
                                                                            <td style="display: none;">
                                                                                <?php echo $row['strand']; ?>
                                                                            </td>
                                                                            <td><?php echo $row['section'] ?></td>
                                                                            <td style="display: none;">
                                                                                <?php echo $row['fname']; ?>
                                                                            </td>
                                                                            <td style="display: none;">
                                                                                <?php echo $row['lname']; ?>
                                                                            </td>
                                                                            <td style="display: none;">
                                                                                <?php echo $row['mname']; ?>
                                                                            </td>
                                                                            <td style="display:none">
                                                                                <?php echo $row['v_id']; ?>
                                                                            </td>
                                                                            <td>
                                                                                <a href="#default-Modal"
                                                                                    class="edit badge badge-warning p-2 text-white"
                                                                                    title="Edit"><i
                                                                                        class="fa fa-edit"></i></a> |
                                                                                <a href="#"
                                                                                    class="delete badge badge-danger p-2 text-white"
                                                                                    title="Delete"><i
                                                                                        class="fa fa-trash"></i></a> |
                                                                                <a href="?re=<?php echo $row['v_id'] ?>"
                                                                                    class="myd badge badge-info p-2 text-white"
                                                                                    title="Reset"><i
                                                                                        class="fa fa-recycle"></i></a>
                                                                            </td>
                                                                        </tr>
                                                                    <?php } ?>
                                                                </tbody>
                                                                <tfoot>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th>Student ID</th>
                                                                        <th>Full Name</th>
                                                                        <th>Grade Level</th>
                                                                        <th>Strand</th>
                                                                        <th>Section</th>
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

                            <div id="styleSelector">

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
            $("#studid").keyup(function () {
                var p = $(this).val();
                $.ajax({
                    url: "ajax/fetchid.php",
                    method: "POST",
                    data: {
                        myids: p
                    },
                    dataType: "json",
                    success: function (html) {
                        if (html.stat == 1) {
                            $('#available').html('<span class="text-danger">Not available</span>');
                            $('#submits').attr('disabled', true);
                        } else {
                            $('#available').html('<span class="text-success">Available</span>');
                            $('#submits').attr('disabled', false);
                        }

                    }
                });
            });

            $('#gradelevel').on('change', function () {
                var p = $(this).val();
                $.ajax({
                    url: "ajax/fetchstrand.php",
                    method: "POST",
                    data: {
                        id: p
                    },
                    dataType: "text",
                    success: function (html) {
                        $('#content').html(html);
                    }
                });
            });

            $(document).on('click', '.edit', function () {
                $('#default-Modal').modal('show');
                $tr = $(this).closest('tr');
                var data = $tr.children("td").map(function () {
                    return $(this).text();
                }).get();
                console.log(data);
                var p = data[2];
                $.ajax({
                    url: "ajax/fetchstrand.php",
                    method: "POST",
                    data: {
                        id: p
                    },
                    dataType: "text",
                    success: function (html) {
                        $('#content').html(html);
                        $('#section').val(data[5]);
                        $('#strand').val(data[4]);
                    }
                });
                $('#id').val(data[0]);
                $('#fname').val(data[6]);
                $('#mname').val(data[8]);
                $('#lname').val(data[7]);
                $('#gradelevel').val(data[2]);
                $('#studid').val(data[0]);
                $('#idhidden').val(data[9]);
                $('#studid').attr('readonly', 'readonly');
                $('.modal-titles').html('Update Basic Information');
                $('#submits').remove();
                $('#footersa').append('<button type="submit" class="btn btn-primary waves-effect waves-light" name="update" id="update">Save changes</button>');

            });

            $(document).on('click', '.delete', function (e) {
                e.preventDefault();
                var currentRow = $(this).closest("tr");
                var col1 = currentRow.find("td:eq(9)").text();
                swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will able to recover this imaginary file in archives!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                    .then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                method: "POST",
                                url: "ajax/deletevoter.php",
                                data: {
                                    myids: col1,
                                },
                                success: function (html) {
                                    swal("Poof! Your imaginary file has been deleted!", {
                                        icon: "success",
                                    }).then((value) => {
                                        location.reload();
                                    });
                                }

                            });

                        } else {
                            swal("Your imaginary file is safe!");
                        }
                    });
            });


            $(document).on('click', '.approve', function (e) {
                e.preventDefault();
                var currentRow = $(this).closest("tr");
                var col1 = currentRow.find("td:eq(5)").text();
                swal({
                    title: "Are you sure?",
                    text: "Once approve, it will tag it as verified voters",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                    .then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                method: "POST",
                                url: "ajax/checkVerified.php",
                                data: {
                                    status: 1,
                                    myids: col1,
                                },
                                success: function (html) {
                                    swal("Success", {
                                        icon: "success",
                                    }).then((value) => {
                                        location.reload();
                                    });
                                }

                            });

                        } else {
                            swal("Your imaginary file is safe!");
                        }
                    });
            });

            $(document).on('click', '.disapprove', function (e) {
                e.preventDefault();
                var currentRow = $(this).closest("tr");
                var col1 = currentRow.find("td:eq(5)").text();
                swal({
                    title: "Are you sure?",
                    text: "You want to disapprove this user?",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                    .then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                method: "POST",
                                url: "ajax/checkVerified.php",
                                data: {
                                    status: 3,
                                    myids: col1,
                                },
                                success: function (html) {
                                    swal("Success", {
                                        icon: "success",
                                    }).then((value) => {
                                        location.reload();
                                    });
                                }

                            });

                        } else {
                            swal("Your imaginary file is safe!");
                        }
                    });
            });




        });
    </script>
</body>

</html>