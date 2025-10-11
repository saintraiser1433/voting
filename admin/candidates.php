<?php
include '../connection.php';
if (!isset($_GET['p'])) {
    header("Location:partylist.php");
} else {
    $party = $_GET['p'];
}
if (!isset($_SESSION['at'])) {
    header("Location:logout.php");
}

$acad = $_SESSION['acad'];
if (isset($_POST['submit'])) {
    $stud = $_POST['studid'];
    $pos = $_POST['position'];
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
    $sql = "INSERT into candidate (acad_id,p_id,pos_id,stud_id,img) values ('$acad','$party','$pos','$stud','$dirs')";
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
    if ($_FILES['files']['name'] == '') {
        $sql = "UPDATE candidate SET pos_id='$pos' where c_id='$stud'";
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
            $sql = "UPDATE candidate SET pos_id='$pos',img='$dirs' where c_id='$stud'";
            if ($conn->query($sql)) {
                $_SESSION['response'] = "Successfully added candidate";
                $_SESSION['type'] = "success";
            } else {
                $_SESSION['response'] = "An error has occurred";
                $_SESSION['type'] = "warning";
            }
        }
    }
}


$sql = "SELECT * FROM partylist where p_id='$party'";
$rtt = $conn->query($sql);
$roww =  $rtt->fetch_assoc();


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
                    <div class="modal fade" id="default-Modal" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-titles">Add Candidate</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="location.reload();">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="" method="post" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="card p-2 text-center font-weight-bold">CANDIDATE PROFILE PICTURE</div>
                                                <center>
                                                    <img id="ImdID" class="img-fluid rounded-circle" src="../libraries/img/glanlogo.png" alt="Image" style="width:200px; height:200px;">
                                                </center>
                                                <br><br>
                                                <div class="form-group">
                                                    <input type="file" name="files" id="filer_input_single" class="form-control" onchange="readURL(this);" />

                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Student ID</label>

                                                    <div class="row">
                                                        <div class="col-9">
                                                            <input type="text" name="studid" class="form-control" id="studid" required>
                                                            <input type="hidden" name="idhidden" class="form-control text-uppercase" id="idhidden" readonly>
                                                            <div id="availability"></div>
                                                        </div>
                                                        <div class="col">
                                                            <button type="button" class="btn btn-primary col-form-label" id="searchz"><i class="fa fa-search"></i></button>
                                                        </div>



                                                    </div>

                                                </div>
                                                <div class="form-group">
                                                    <label class="col-form-label">Full Name</label>
                                                    <input type="text" name="fname" class="form-control text-uppercase" id="fname" readonly>

                                                </div>
                                                <div class="form-group">
                                                    <label class="col-form-label">Year Level</label>
                                                    <input type="text" name="lname" class="form-control text-uppercase" id="yearlevel" readonly>
                                                </div>

                                                <div class="form-group">
                                                    <label class="col-form-label">Position</label>
                                                    <select name="position" class="form-control text-uppercase" id="position" required>
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

                                            </div>

                                        </div>
                                    </div>
                                    <div class="modal-footer" id="footersa">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light" name="submit" id="submits">Save changes</button>
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
                                                                    <h3 class="m-b-5 text-uppercase" id="psa"><b>PARTYLIST NAME: <?php echo $roww['party_name'] ?></b></h3>
                                                                </div>
                                                                <div class="col col-auto text-right">
                                                                    <button type="button" class="btn btn-mat btn-warning" data-toggle="modal" data-target="#default-Modal"><i class="fa fa-plus text-white"></i></button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <div class="card-block-big">
                                                        <div class="dt-responsive table-responsive">
                                                            <table id="simpletable" class="table table-striped table-bordered nowrap w-100">
                                                                <thead>
                                                                    <tr>
                                                                        <th>#</th>
                                                                        <th style="display: none;"></th>
                                                                        <th>Img</th>
                                                                        <th>Candidate Name </th>
                                                                        <th>Position</th>
                                                                        <th style="display: none;"></th>
                                                                        <th style="display: none;"></th>
                                                                        <th style="display: none;"></th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $sql = "SELECT *,candidate.img as myimg FROM candidate LEFT JOIN voters ON candidate.stud_id=voters.stud_id LEFT JOIN partylist ON candidate.p_id=partylist.p_id LEFT JOIN position ON candidate.pos_id=position.pos_id where candidate.p_id='$party' order by position.priority";
                                                                    $rs = $conn->query($sql);
                                                                    $i = 1;
                                                                    while ($row = $rs->fetch_assoc()) {

                                                                    ?>

                                                                        <tr>
                                                                            <th scope="row"><?php echo $i++; ?></th>
                                                                            <td style="display:none"><?php echo $row['c_id'];  ?></td>
                                                                            <td><img src="../<?php echo $row['myimg']; ?>" class="rounded-circle" style="width:30px;height:30px;"></td>
                                                                            <td class="text-uppercase"><?php echo $row['lname'] . ", " . $row['fname'] . " " . $row['mname'][0]; ?></td>
                                                                            <td class="text-uppercase"><?php echo $row['description'] ?></td>
                                                                            <td style="display: none"><?php echo $row['pos_id'] ?></td>
                                                                            <td style="display: none"><?php echo $row['stud_id'] ?></td>
                                                                            <td style="display: none"><?php echo $row['myimg'] ?></td>
                                                                            <td>

                                                                                <a href="#default-Modal" class="edit badge badge-warning p-2 text-white" title="Edit"><i class="fa fa-edit"></i></a> |
                                                                                <a href="#" class="delete badge badge-danger p-2 text-white" title="Delete"><i class="fa fa-trash"></i></a>
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
        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    $('#ImdID').attr('src', e.target.result);


                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    <script>
        $(document).ready(function() {
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }

            $('#searchz').click(function() {
                var stud = $('#studid').val();
                $.ajax({
                    method: "POST",
                    url: "ajax/searchstudent.php",
                    data: {
                        myids: stud,
                    },
                    dataType: "json",
                    success: function(html) {
                        if (html.stat == 0) {
                            alert('No result found');
                            $('#fname').val("");
                            $('#yearlevel').val("");
                            $('#submits').attr('disabled', true);
                        } else if (html.res == 1) {
                            $('#availability').html('<span class="text-danger">Already Candidate</span>');
                            $('#submits').attr('disabled', true);

                        } else {
                            $('#fname').val(html.fname);

                            $('#yearlevel').val("Year " + html.gr);
                            $('#submits').attr('disabled', false);
                        }


                    }

                });
            });


            $(document).on('click', '.edit', function() {
                $('#default-Modal').modal('show');
                $tr = $(this).closest('tr');
                var data = $tr.children("td").map(function() {
                    return $(this).text();
                }).get();
                console.log(data);
                $('#idhidden').val(data[0]);
                $('#position').val(data[4]);
                $('#studid').val(data[5]);
                $('#studid').attr('disabled', true);
                $('#searchz').attr('disabled', true);
                $('.modal-titles').html('Update Basic Information');
                $('#ImdID').attr('src', '../' + data[6]);
                var p = data[5];
                $.ajax({
                    method: "POST",
                    url: "ajax/searchstudent.php",
                    data: {
                        myids: p,
                    },
                    dataType: "json",
                    success: function(html) {
                        if (html.stat == 0) {
                            alert('No result found');
                        } else {
                            $('#fname').val(html.fname);
                            $('#yearlevel').val("Year " + html.gr);
                        }


                    }

                });
                $('#submits').remove();
                $('#footersa').append('<button type="submit" class="btn btn-primary waves-effect waves-light" name="update" id="update">Save changes</button>');

            });


            $(document).on('click', '.delete', function(e) {
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
                                success: function(html) {
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
<style>
    @media screen and (max-width:600px) {
        #psa {
            font-size: 14px;
        }

    }
</style>