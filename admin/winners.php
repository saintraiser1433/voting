<?php include '../connection.php' ?>
<?php
if (!isset($_SESSION['at'])) {
    header("Location:logout.php");
}


$acad = $_SESSION['acad'];
$sel = "SELECT * FROM acad_tbl where acad_id = $acad";
$rs = $conn->query($sel);
$row = ($rs && $rs->num_rows > 0) ? $rs->fetch_assoc() : null;
$acads = $row ? $row['description'] : '';

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
    <script>$(function(){ $('.theme-loader').fadeOut(400, function(){ $(this).remove(); }); });</script>
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
                                                        <h4>OFFICERS / RESULTS FOR ACADEMIC YEAR : <?php echo $acads ?>
                                                        </h4>
                                                        <span>This shows officers and live results</span>
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="col-lg-4">
                                                <label>Academic Year:</label>
                                                <select name="acads" class="form-control" id="acads" required>
                                                    <?php
                                                    $sqlx = "SELECT * FROM acad_tbl ORDER BY description DESC";
                                                    $rsx = $conn->query($sqlx);
                                                    if ($rsx) {
                                                        while ($rowx = $rsx->fetch_assoc()) {
                                                            $selAttr = ((int)$rowx['acad_id'] === (int)$acad) ? 'selected' : '';
                                                            ?>
                                                            <option value="<?php echo $rowx['acad_id']; ?>" <?php echo $selAttr; ?>>
                                                                <?php echo htmlspecialchars($rowx['description']); ?>
                                                            </option>
                                                        <?php
                                                        }
                                                    }
                                                    ?>
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
    function getDeptId() {
        return new URL(window.location.href).searchParams.get('dept_id') || '';
    }
    function getData(id, deptId) {
        var payload = { acad: id };
        if (deptId) payload.dept_id = deptId;
        $.ajax({
            method: 'GET',
            data: payload,
            url: "ajax/fetchresult.php",
            success: function (datas) {
                $('#resultx').html(datas);
            }
        });
    }

    getData('<?php echo $acad ?>', getDeptId());
    $('#acads').on('change', function () {
        getData($(this).val(), getDeptId());
    });


    setInterval(() => {
        $.ajax({
            url: "ajax/checkYear.php",
            success: function (datas) {

            }
        });

    }, 1000);

</script>