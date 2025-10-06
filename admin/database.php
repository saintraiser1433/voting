<?php
include '../connection.php';
if (!isset($_SESSION['at'])) {
    header("Location:logout.php");
}

if (isset($_POST["import"])) {
    if ($_FILES["db"]["name"] != '') {
        $array = explode(".", $_FILES["db"]["name"]);
        $extension = end($array);
        if ($extension == 'sql') {
            $query_disable_checks = 'SET foreign_key_checks = 0';
            $connect = mysqli_connect("localhost", "root", "", "myvote");
            $connect->query('SET foreign_key_checks = 0');
            $qry_drop = "DROP TABLE IF EXISTS acad_tbl,admin,candidate,election_title,partylist,position,vote,voters,archives,archivesvote";
            $connect->query($qry_drop);
            $connect->query('SET foreign_key_checks = 1');


            $output = '';
            $count = 0;
            $file_data = file($_FILES["db"]["tmp_name"]);
            foreach ($file_data as $row) {
                $start_character = substr(trim($row), 0, 2);
                if ($start_character != '--' || $start_character != '/*' || $start_character != '//' || $row != '') {
                    $output = $output . $row;
                    $end_character = substr(trim($row), -1, 1);
                    if ($end_character == ';') {
                        if (!mysqli_query($connect, $output)) {
                            $count++;
                        }
                        $output = '';
                    }
                }
            }
            if ($count > 0) {
                $_SESSION['response'] = "There is an error in Database Import";
                $_SESSION['type'] = "warning";
            } else {

                $_SESSION['response'] = "Database Successfully Imported";
                $_SESSION['type'] = "success";
            }
        } else {

            $_SESSION['response'] = "Invalid File";
            $_SESSION['type'] = "warning";
        }
    } else {

        $_SESSION['response'] = "Please Select Sql File";
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


                    <!--end -- >


                    <-- Modal -->



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
                                                                    <h3 class="m-b-5"><b>DATABASE BACKUP</b></h3>
                                                                </div>

                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="card-block">
                                                        <div class="row">
                                                            <div class="col-lg-6">
                                                                <center>
                                                                    <h3>Export Database</h3><br>
                                                                    <span class="text-muted">Click "Export" Button to
                                                                        backup your database!</span><br><br>
                                                                    <a href="getdatabase.php"
                                                                        class="btn btn-success">Export</a>
                                                                </center>
                                                            </div>
                                                            <div class="col-lg-6">
                                                                <center>
                                                                    <h3>Import Database</h3><br>
                                                                    <span class="text-muted">Click "Export" Button to
                                                                        backup your database!</span><br><br>
                                                                    <form action="" method="post"
                                                                        enctype="multipart/form-data">
                                                                        <input type="file" class="form-control w-50"
                                                                            name="db" id="db" required><br>
                                                                        <button type="submit" class="btn btn-success"
                                                                            name="import">Import</button>
                                                                    </form>


                                                                </center>
                                                            </div>
                                                        </div>
                                                    </div>


                                                    <br><br><br><br><br><br><br><br><br><br>
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





        });
    </script>
</body>

</html>