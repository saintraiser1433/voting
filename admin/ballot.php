<?php include '../connection.php' ?>
<?php
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
                    <div class="pcoded-content">
                        <div class="pcoded-inner-content">
                            <div class="main-body">
                                <div class="page-wrapper">

                                    <div class="page-body">
                                        <div id="content"></div>
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
    <script>
        $(document).ready(function() {
            
            $(document).on('click', '.moveup', function(e) {
                e.preventDefault();
                var id = $(this).data('id');
                $('#' + id).animate({
                    'marginTop': "-300px"
                });
                $.ajax({
                    type: 'POST',
                    url: 'ajax/ballot_up.php',
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(response) {



                    }
                });
                location.reload();

            });

            $(document).on('click', '.movedown', function(e) {
                e.preventDefault();

                var id = $(this).data('id');
                $('#' + id).animate({
                    'marginTop': "+300px"
                });
                $.ajax({
                    type: 'POST',
                    url: 'ajax/ballot_down.php',
                    data: {
                        id: id
                    },
                    dataType: 'json',
                    success: function(response) {

                    }
                });
                location.reload();

            });

            function fetch() {
                $.ajax({
                    type: 'POST',
                    url: 'ballotfetch.php',
                    dataType: 'json',
                    success: function(response) {
                        $('#content').html(response);
                    }
                });
            }
            fetch();
        });
    </script>


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