<?php include '../connection.php' ?>
<?php
if (!isset($_SESSION['at'])) {
    header("Location:logout.php");
}


$acad = $_SESSION['acad'];
$sel = "SELECT * FROM acad_tbl where acad_id = $acad";
$rs = $conn->query($sel);
$row = $rs->fetch_assoc();
$acads = $row['description'];

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
                    <div class="pcoded-content">
                        <div class="pcoded-inner-content">
                            <div class="main-body">
                                <div class="page-wrapper">
                                    <div class="page-header">
                                        <div class="row align-items-end">
                                            <div class="col-lg-8">
                                                <div class="page-header-title">
                                                    <div class="d-inline">
                                                        <h4>ELECTED OFFICERS FOR ACADEMIC YEAR : <?php echo $acads ?>
                                                        </h4>
                                                        <span>This is the list of elected officers</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <label>Academic Year:</label>
                                                <select name="acads" class="form-control" id="acads" required>

                                                    <?php
                                                    $sqlx = "SELECT * from acad_tbl";
                                                    $rsx = $conn->query($sqlx);
                                                    foreach ($rsx as $row) {
                                                        ?>
                                                        <option value="<?php echo $row['acad_id'] ?>" selected>
                                                            <?php echo $row['description'] ?>
                                                        </option>
                                                    <?php } ?>
                                                </select>
                                            </div>

                                        </div>
                                    </div>
                                    <div class="page-body">



                                        <div id="resultx"></div>
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
    </div>
    </div>


    <!-- Required Jquery -->
    <?php include 'nav/script.php'; ?>
    <?php include 'modalelection.php'; ?>



</body>

</html>
<style>
    @media screen and (max-width:600px) {
        #chck {
            margin-left: 60px;
        }

        #myh3 {
            font-size: 12px;
            margin-left: 90px;
        }
    }
</style>

<script>

    function getData(id) {
        $.ajax({
            method: 'GET',
            data: {
                acad: id
            },
            url: "ajax/fetchresult.php",
            success: function (datas) {
                $('#resultx').html(datas);
            }
        });
    }

    getData('<?php echo $acad ?>')
    $('#acads').on('change', function () {
        var data = $(this).val();
        getData(data);

    })


    setInterval(() => {
        $.ajax({
            url: "ajax/checkYear.php",
            success: function (datas) {

            }
        });

    }, 1000);

</script>