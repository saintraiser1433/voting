<?php
include '../connection.php';

$acad = $_SESSION['acad'];
$party = 0;
if (!isset($_SESSION['at'])) {
    header("Location:logout.php");
}

if (isset($_POST['submit'])) {
    $stud = $_POST['studid'];
    $pos = $_POST['position'];
    $plat = $_POST['platform'];
    if ($_FILES['files']['name'] == '') {
        $dirs = "libraries/img/logo.png";
    } else {
        $imgfile = $_FILES["files"]["name"];
        $extension = substr($imgfile, strlen($imgfile) - 4, strlen($imgfile));
        $allowed_extensions = array(".jpg", "jpeg", ".png", ".gif");
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
    $sql = "INSERT into candidate (acad_id,p_id,pos_id,stud_id,img,platform) values ('$acad','$party','$pos','$stud','$dirs','$plat')";
    if ($conn->query($sql)) {
        $_SESSION['response'] = "Candidate successfully added";
        $_SESSION['type'] = "success";
    } else {
        $_SESSION['response'] = "An error has occurred";
        $_SESSION['type'] = "warning";
    }
}


if (isset($_POST['update'])) {
    $stud = $_POST['idhidden'];
    $pos = $_POST['position'];
    $plat = $_POST['platform'];
    if ($_FILES['files']['name'] == '') {
        $sql = "UPDATE candidate SET pos_id='$pos',platform='$plat' where c_id='$stud'";
        if ($conn->query($sql)) {
            $_SESSION['response'] = "Candidate successfully updated";
            $_SESSION['type'] = "success";
        } else {
            $_SESSION['response'] = "An error has occurred";
            $_SESSION['type'] = "warning";
        }
    } else {
        $imgfile = $_FILES["files"]["name"];
        $extension = substr($imgfile, strlen($imgfile) - 4, strlen($imgfile));
        $allowed_extensions = array(".jpg", "jpeg", ".png", ".gif");
        $tmpname = $_FILES['files']['tmp_name'];
        if (!in_array($extension, $allowed_extensions)) {
            echo "<script>alert('Invalid format. Only jpg / jpeg/ png /gif format allowed');</script>";
        } else {
            $imgnewfile = md5($imgfile) . $extension;
            $dir = "../candidatephoto/" . $imgnewfile;
            $dirs = "candidatephoto/" . $imgnewfile;
            move_uploaded_file($tmpname, $dir);
            $sql = "UPDATE candidate SET pos_id='$pos',img='$dirs',platform='$plat' where c_id='$stud'";
            if ($conn->query($sql)) {
                $_SESSION['response'] = "asds";
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



                    <!--end -- >


                    <-- Modal -->
                    <div class="modal fade" id="default-Modal" tabindex="-1" role="dialog" aria-hidden="true"
                        data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-titles">Add Candidate</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                        onclick="location.reload();">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="" method="post" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="card p-2 text-center font-weight-bold">CANDIDATE PROFILE
                                                    PICTURE</div>
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


                                                <div class="form-group">
                                                    <label class="col-form-label">Student ID</label>

                                                    <div class="row">
                                                        <div class="col-9">
                                                            <input type="text" name="studid" class="form-control"
                                                                id="studid" required>
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
                                                    <input type="hidden" name="idhidden"
                                                        class="form-control text-uppercase" id="idhidden" readonly>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-form-label">Grade Level</label>
                                                    <input type="text" name="lname" class="form-control text-uppercase"
                                                        id="gradelevel" readonly>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-form-label">Position</label>
                                                    <select name="position" class="form-control text-uppercase"
                                                        id="position" required>
                                                        <option value=""></option>
                                                        <?php
                                                        $sql = "SELECT * from position where acad_id='$acad' order by priority";
                                                        $rs = $conn->query($sql);
                                                        while ($row = $rs->fetch_assoc()) {
                                                            echo '<option value="' . $row['pos_id'] . '">' . $row['description'] . '</option>';
                                                        }
                                                        ?>
                                                    </select>
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-form-label">Platform</label>
                                                    <textarea name="platform" class="form-control"
                                                        id="platform"></textarea>
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


                    <div class="modal fade" id="platform1" tabindex="-1" role="dialog" aria-hidden="true"
                        data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-titles">Platform</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                        onclick="location.reload();">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="" method="post" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <center>
                                            <img src="../libraries/img/logo.png" id="myimg" class="rounded-circle"
                                                style="width:300px; height:300px;">
                                        </center>
                                        <br><br>
                                        <div class="form-group">
                                            <div class="card p-2">
                                                <p class="text-justify" id="see"></p>
                                            </div>
                                        </div>
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
                                                                    <h3 class="m-b-5 text-uppercase"><b>Independent
                                                                            Candidate</b></h3>
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
                                                                        <th style="display: none;"></th>
                                                                        <th>Img</th>
                                                                        <th>Candidate Name </th>
                                                                        <th>Position</th>
                                                                        <th>Platform</th>
                                                                        <th style="display: none;"></th>
                                                                        <th style="display: none;"></th>
                                                                        <th style="display: none;"></th>
                                                                        <th style="display: none;"></th>

                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $sql = "SELECT *,candidate.img as myimg,candidate.platform as myplat FROM candidate LEFT JOIN voters ON candidate.stud_id=voters.stud_id LEFT JOIN partylist ON candidate.p_id=partylist.p_id LEFT JOIN position ON candidate.pos_id=position.pos_id where candidate.p_id='0' order by position.priority";
                                                                    $rs = $conn->query($sql);
                                                                    $i = 1;
                                                                    while ($row = $rs->fetch_assoc()) {

                                                                        ?>

                                                                        <tr>
                                                                            <th scope="row"><?php echo $i++; ?></th>
                                                                            <td style="display:none">
                                                                                <?php echo $row['c_id']; ?>
                                                                            </td>
                                                                            <td><img src="../<?php echo $row['myimg']; ?>"
                                                                                    class="rounded-circle"
                                                                                    style="width:30px;height:30px;"></td>
                                                                            <td class="text-uppercase">
                                                                                <?php echo $row['lname'] . ", " . $row['fname'] . " " . $row['mname'][0]; ?>
                                                                            </td>
                                                                            <td class="text-uppercase">
                                                                                <?php echo $row['description'] ?>
                                                                            </td>
                                                                            <td> <a href="#platform1"
                                                                                    class="view badge badge-primary p-2 text-white"
                                                                                    title="Edit"><i
                                                                                        class="fa fa-eye"></i></a></td>
                                                                            <td style="display: none">
                                                                                <?php echo $row['pos_id'] ?>
                                                                            </td>
                                                                            <td style="display: none">
                                                                                <?php echo $row['stud_id'] ?>
                                                                            </td>
                                                                            <td style="display: none">
                                                                                <?php echo $row['myimg'] ?>
                                                                            </td>
                                                                            <td style="display: none">
                                                                                <?php echo $row['myplat'] ?>
                                                                            </td>
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
                                                                        <th style="display: none;"></th>
                                                                        <th>Img</th>
                                                                        <th>Candidate Name </th>
                                                                        <th>Position</th>
                                                                        <th>Platform</th>
                                                                        <th style="display: none;"></th>
                                                                        <th style="display: none;"></th>
                                                                        <th style="display: none;"></th>
                                                                        <th style="display: none;"></th>
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
        CKEDITOR.replace('platform', {
            height: 220,
            filebrowserUploadUrl: "upload.php",
        });

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    $('#ImdID').attr('src', e.target.result);


                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    <script>
        $(document).ready(function () {
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }

            $('#searchz').click(function () {
                var stud = $('#studid').val();
                $.ajax({
                    method: "POST",
                    url: "ajax/searchstudent.php",
                    data: {
                        myids: stud,
                    },
                    dataType: "json",
                    success: function (html) {
                        if (html.stat == 0) {
                            alert('No result found');
                            $('#fname').val("");
                            $('#gradelevel').val("");
                            $('#submits').attr('disabled', true);
                        } else if (html.res == 1) {
                            $('#availability').html('<span class="text-danger">Already Candidate</span>');
                            $('#submits').attr('disabled', true);
                        } else {
                            $('#fname').val(html.fname);
                            $('#availability').html('');
                            $('#gradelevel').val("Grade " + html.gr);
                            $('#submits').attr('disabled', false);
                        }


                    }

                });
            });
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
                console.log(data);
                $('#idhidden').val(data[0]);
                $('#fname').val(data[2]);
                $('#gradelevel').val(data[3]);
                $('#position').val(data[5]);
                var stud = data[6];
                $('#studid').val(data[6]);
                $('#ImdID').attr('src', '../' + data[7]);
                $.ajax({
                    method: "POST",
                    url: "ajax/searchstudent.php",
                    data: {
                        myids: stud,
                    },
                    dataType: "json",
                    success: function (html) {
                        if (html.stat == 0) {
                            $('#fname').val("");
                            $('#gradelevel').val("");
                            $('#submits').attr('disabled', true);
                        } else if (html.res == 1) {

                        } else {
                            $('#fname').val(html.fname);
                            $('#gradelevel').val("Grade " + html.gr);
                            $('#submits').attr('disabled', false);
                        }


                    }

                });
                $('#studid').attr('disabled', true);
                $('#searchz').attr('disabled', true);
                $('.modal-titles').html('Update Basic Information');

                CKEDITOR.instances["platform"].setData(data[8]);
                $('#submits').remove();
                $('#footersa').append('<button type="submit" class="btn btn-primary waves-effect waves-light" name="update" id="update">Save changes</button>');

            });

            $(document).on('click', '.view', function () {
                $('#platform1').modal('show');
                $tr = $(this).closest('tr');
                var data = $tr.children("td").map(function () {
                    return $(this).text();
                }).get();
                console.log(data);
                $('#myimg').attr('src', '../' + data[7]);
                $('#see').html(data[8]);

            });



            $(document).on('click', '.delete', function (e) {
                e.preventDefault();
                var currentRow = $(this).closest("tr");
                var col1 = currentRow.find("td:eq(0)").text();
                swal({
                    title: "Are you sure?",
                    text: "Once deleted, you will not be able to recover this imaginary file!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                    .then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                method: "POST",
                                url: "ajax/deletecandidate.php",
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


        });
    </script>
</body>

</html>