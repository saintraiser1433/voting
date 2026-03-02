<?php
include '../connection.php';
if (!isset($_SESSION['at'])) {
    header("Location: logout.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'nav/header.php'; ?>
<body>
    <div class="theme-loader"><div class="ball-scale"><div class="contain"><div class="ring"><div class="frame"></div></div></div></div></div>
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
                                        <h4 class="mb-3">Department ballot positioning</h4>
                                        <div id="content"></div>
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
    <script>
    $(document).ready(function () {
        $('.theme-loader').fadeOut(400, function () { $(this).remove(); });
        $(document).on('click', '.moveup', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            $('#' + id).animate({ 'marginTop': '-300px' });
            $.ajax({ type: 'POST', url: 'ajax/ballot_up_dept.php', data: { id: id }, dataType: 'json', success: function () { location.reload(); } });
        });
        $(document).on('click', '.movedown', function (e) {
            e.preventDefault();
            var id = $(this).data('id');
            $('#' + id).animate({ 'marginTop': '+300px' });
            $.ajax({ type: 'POST', url: 'ajax/ballot_down_dept.php', data: { id: id }, dataType: 'json', success: function () { location.reload(); } });
        });
        function fetch() {
            $.ajax({ type: 'POST', url: 'ballotfetch_dept.php', dataType: 'json', success: function (response) { $('#content').html(response); } });
        }
        fetch();
    });
    </script>
</body>
</html>
