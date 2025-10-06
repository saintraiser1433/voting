<?php
include '../connection.php';
$acad = 1;
if (!isset($_SESSION['at'])) {
    header("Location:logout.php");
}

if (isset($_POST['submits'])) {
    $description = $_POST['acads'];
    $status = 0;
    $sql = "INSERT INTO acad_tbl (description,status) VALUES ('$description','$status')";
    if ($conn->query($sql)) {
        $_SESSION['response'] = "Session Year successfully added";
        $_SESSION['type'] = "success";
    } else {
        $_SESSION['response'] = "An error has occurred";
        $_SESSION['type'] = "warning";
    }
}
if (isset($_POST['update'])) {
    $id = $_POST['idhidden'];
    $acads = $_POST['acads'];
    $sql = "UPDATE acad_tbl SET description='$acads' where acad_id='$id'";
    if ($conn->query($sql)) {
        $_SESSION['response'] = "Session successfully updated";
        $_SESSION['type'] = "success";
    } else {
        $_SESSION['response'] = "An error has occurred";
        $_SESSION['type'] = "warning";
    }
}

if (isset($_GET['type']) && $_GET['type'] != '') {
    $type = $_GET['type'];
    if ($type == 'status') {
        $operation = $_GET['operation'];
        $id = $_GET['id'];
        if ($operation == 'active') {
            $status = '1';
        } else {
            $status = '0';
        }
        $query = "SELECT * FROM acad_tbl where status='1'";
        $result = $conn->query($query);
        $stat = 0;
        while ($row = $result->fetch_assoc()) {
            $ids = $row['acad_id'];
            $updatesqls = "update acad_tbl set status='$stat' where acad_id='$ids'";
            $stmt = $conn->prepare($updatesqls);
            $stmt->execute();
        }
        $updatesql = "update acad_tbl set status='$status' where acad_id='$id'";
        $stmt = $conn->prepare($updatesql);
        $stmt->execute();
        $acad = $_SESSION['acad'] = $id;
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
                                    <h4 class="modal-titles">Add Position</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                                        onclick="location.reload();">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <form action="" method="post">
                                    <div class="modal-body">
                                        <div class="row">

                                            <div class="col-lg-12">
                                                <div class="form-group">
                                                    <label class="col-form-label">Session Year:</label>
                                                    <input type="hidden" name="idhidden" id="idhidden">
                                                    <select name="acads" class="form-control" id="acads" required>
                                                        <option value=""></option>
                                                        <option value="2022">2022</option>
                                                        <option value="2023">2023</option>
                                                        <option value="2024">2024</option>
                                                        <option value="2025">2025</option>
                                                        <option value="2026">2026</option>
                                                        <option value="2027">2027</option>
                                                        <option value="2028">2028</option>
                                                        <option value="2029">2029</option>
                                                        <option value="2030">2030</option>
                                                        <option value="2031">2031</option>
                                                        <option value="2032">2032</option>
                                                        <option value="2033">2033</option>
                                                        <option value="2034">2034</option>
                                                        <option value="2035">2035</option>
                                                        <option value="2036">2036</option>
                                                        <option value="2037">2037</option>
                                                        <option value="2038">2038</option>
                                                        <option value="2039">2039</option>
                                                        <option value="2040">2040</option>
                                                        <option value="2041">2041</option>
                                                        <option value="2042">2042</option>
                                                        <option value="2043">2043</option>
                                                        <option value="2044">2044</option>
                                                        <option value="2045">2045</option>

                                                    </select>
                                                    <div id="avail"></div>
                                                </div>

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
                                                                    <h3 class="m-b-5"><b>MANAGE SESSION YEAR</b></h3>
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
                                                                        <th style="display: none;">#</th>
                                                                        <th>Session Year</th>
                                                                        <th>Action</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php
                                                                    $i = 1;
                                                                    $sql = "SELECT * FROM acad_tbl order by description";
                                                                    $rs = $conn->query($sql);
                                                                    while ($row = $rs->fetch_assoc()) {

                                                                        ?>

                                                                        <tr>
                                                                            <th scope="row"><?php echo $i++; ?></th>
                                                                            <td style="display:none">
                                                                                <?php echo $row['acad_id'] ?>
                                                                            </td>
                                                                            <td class="text-uppercase">
                                                                                <?php echo $row['description'] ?>
                                                                            </td>
                                                                            <td>
                                                                                <?php if ($row['status'] == 1) {
                                                                                    echo "<a class='badge badge-success p-2' href='?type=status&operation=deactive&id=" . $row['acad_id'] . "'><i class='fa fa-eye'></i> </a>";
                                                                                } else {
                                                                                    echo "<a class='badge badge-dark p-2' href='?type=status&operation=active&id=" . $row['acad_id'] . "'><i class='fa fa-eye'></i> </a>";
                                                                                } ?>
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
                                                                        <th style="display: none;">#</th>
                                                                        <th>Position</th>

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
                console.log(data);
                $('#idhidden').val(data[0]);
                $('#acads').val(data[1]);
                $('#maxvote').val(data[2]);
                $('.modal-titles').html('Update Basic Information');
                $('#submits').remove();
                $('#footersa').append('<button type="submit" class="btn btn-primary waves-effect waves-light" name="update" id="update">Save changes</button>');

            });

            $('#acads').on('change', function () {
                var p = $(this).val();
                $.ajax({
                    url: "ajax/checkavail.php",
                    method: "POST",
                    data: {
                        id: p
                    },
                    dataType: "json",
                    success: function (html) {
                        if (html.stat == 1) {
                            $('#avail').html('<span class="text-danger font-weight-bold">This year is already existed!</span>');
                            $('#submits').attr('disabled', true);
                            $('#update').attr('disabled', true);
                        } else {
                            $('#avail').html('<span class="text-success font-weight-bold">Available</span>');
                            $('#submits').attr('disabled', false);
                            $('#update').attr('disabled', false);
                        }
                    }
                });
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
                                url: "ajax/deleteacad.php",
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