<?php
include '../connection.php';
$acad = $_SESSION['acad'];
if (!isset($_SESSION['at'])) {
    header("Location:logout.php");
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
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-titles">Add Partylist</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                        onclick="location.reload();">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="" method="post" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="col-lg-6">
                                                <div class="card p-2 text-center font-weight-bold">PARTYLIST PROFILE
                                                    PICTURE</div>
                                                <center>
                                                    <img id="ImdID" class="img-fluid rounded-circle"
                                                        src="../libraries/img/logo.png" alt="Image"
                                                        style="width:310px; height:310px;">
                                                </center>
                                                <br><br>
                                                <div class="form-group">
                                                    <input type="file" name="files[]" id="filer_input_single"
                                                        class="form-control" onchange="readURL(this);" />

                                                </div>
                                            </div>
                                            <div class="col-lg-6">
                                                <div class="form-group">
                                                    <label class="col-form-label">Partylist Name</label>
                                                    <input type="text" name="pname" id="pname"
                                                        class="form-control text-uppercase">
                                                    <input type="hidden" name="ids" id="ids"
                                                        class="form-control text-uppercase">
                                                </div>
                                                <div class="form-group">
                                                    <label class="col-form-label">Platform</label>
                                                    <textarea class="form-control" name="platform"
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


                    <div class="modal fade" id="viewModal" tabindex="-1" role="dialog" aria-hidden="true"
                        data-backdrop="static" data-keyboard="false">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-titles">View Platform</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                        onclick="location.reload();">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="" method="post" enctype="multipart/form-data">
                                    <div class="modal-body">

                                        <center>
                                            <img id="ImdIDs" class="img-fluid rounded-circle"
                                                src="../libraries/img/logo.png" alt="Image"
                                                style="width:310px; height:310px;">
                                            <br><br>
                                            <u>
                                                <h5 id="blank" class="text-uppercase">BLANK</h5>
                                            </u>
                                        </center>

                                        <div class="row p-5">
                                            <div class="card">
                                                <p class="text-justify" id="mycon"></p>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="modal-footer" id="footersa">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light"
                                            name="submits" id="submits">Save changes</button>
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
                                                                    <h3 class="m-b-5"><b>ARCHIVES</b></h3>
                                                                </div>
                                                                <div class="col col-auto text-right">

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
                                                                        <th style="display:none"></th>
                                                                        <th>Voter Name</th>
                                                                        <th>Grade Level</th>
                                                                        <th>Strand</th>
                                                                        <th>Section</th>
                                                                        <th>Academic Year</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $sql = "SELECT * FROM archives LEFT JOIN acad_tbl ON archives.acad_id=acad_tbl.acad_id";
                                                                    $rs = $conn->query($sql);
                                                                    $i = 1;
                                                                    while ($row = $rs->fetch_assoc()) {
                                                                        ?>

                                                                        <tr>
                                                                            <th scope="row"><?php echo $i++; ?></th>
                                                                            <td style="display:none">
                                                                                <?php echo $row['v_id']; ?>
                                                                            </td>

                                                                            <td class="text-uppercase">
                                                                                <?php echo $row['lname'] . ", " . $row['fname'] . " " . $row['mname'][0]; ?>
                                                                            </td>
                                                                            <td class="text-uppercase">
                                                                                <?php echo $row['grade_level']; ?>
                                                                            </td>
                                                                            <td class="text-uppercase">
                                                                                <?php
                                                                                if ($row['strand'] == '') {
                                                                                    echo '<span class="text-danger">X</span>';
                                                                                } else {
                                                                                    echo $row['strand'];
                                                                                }


                                                                                ?>
                                                                            </td>
                                                                            <td class="text-uppercase">
                                                                                <?php echo $row['section']; ?>
                                                                            </td>
                                                                            <td class="text-uppercase">
                                                                                <?php echo $row['description']; ?>
                                                                            </td>

                                                                            <td>
                                                                                <a href=""
                                                                                    class="retrieve badge badge-success p-2 text-white"
                                                                                    title="Edit"><i
                                                                                        class="fa fa-undo"></i></a> |
                                                                                <a href=""
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
                                                                        <th style="display: none;">#</th>
                                                                        <th>Img</th>
                                                                        <th>Partylist Name</th>
                                                                        <th>Platform</th>
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

                reader.onload = function (e) {
                    $('#ImdID').attr('src', e.target.result);


                };

                reader.readAsDataURL(input.files[0]);
            }
        }
    </script>
    <script>
        CKEDITOR.replace('platform', {
            height: 220,
            filebrowserUploadUrl: "upload.php",
        });
        $(document).ready(function () {
            if (window.history.replaceState) {
                window.history.replaceState(null, null, window.location.href);
            }




            $(document).on('click', '.view', function () {
                $('#viewModal').modal('show');
                $tr = $(this).closest('tr');
                var data = $tr.children("td").map(function () {
                    return $(this).text();
                }).get();
                console.log(data);
                $('#blank').html(data[2]);
                $('#mycon').html(data[3]);
                $('#ImdIDs').attr('src', '../' + data[4]);

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
                                url: "ajax/deletearchive.php",
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


            setInterval(() => {
                $.ajax({
                    url: "ajax/checkYear.php",
                    success: function (datas) {

                    }
                });

            }, 1000);

            $(document).on('click', '.retrieve', function (e) {
                e.preventDefault();
                var currentRow = $(this).closest("tr");
                var col1 = currentRow.find("td:eq(0)").text();
                swal({
                    title: "Are you sure?",
                    text: "Once retrieve, it will recover this imaginary file!",
                    icon: "warning",
                    buttons: true,
                    dangerMode: true,
                })
                    .then((willDelete) => {
                        if (willDelete) {
                            $.ajax({
                                method: "POST",
                                url: "ajax/retrieve.php",
                                data: {
                                    myids: col1,
                                },
                                success: function (html) {
                                    swal("Poof! Your imaginary file has been retrieved!", {
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