<?php
include 'mymodal.php';
?>

<script type="text/javascript" src="..\libraries\bower_components\jquery\js\jquery.min.js"></script>
<script type="text/javascript" src="..\libraries\bower_components\jquery-ui\js\jquery-ui.min.js"></script>
<script type="text/javascript" src="..\libraries\bower_components\popper.js\js\popper.min.js"></script>
<script type="text/javascript" src="..\libraries\bower_components\bootstrap\js\bootstrap.min.js"></script>
<!-- jquery slimscroll js -->
<script type="text/javascript" src="..\libraries\bower_components\jquery-slimscroll\js\jquery.slimscroll.js"></script>
<!-- modernizr js -->

<!--datatable -->

<script src="..\libraries\bower_components\datatables.net\js\jquery.dataTables.min.js"></script>
<script src="..\libraries\bower_components\datatables.net-bs4\js\dataTables.bootstrap4.min.js"></script>
<script src="..\libraries\bower_components\datatables.net-responsive\js\dataTables.responsive.min.js"></script>
<script src="..\libraries\bower_components\datatables.net-responsive-bs4\js\responsive.bootstrap4.min.js"></script>

<!--ckeditor -->
<script src="..\libraries\bower_components\ckeditorf\ckeditor.js"></script>
<link rel="stylesheet" type="text/css" href="..\libraries\bower_components\switchery\js\switchery.min.js">
<script type="text/javascript" src="..\libraries\assets\js\modalEffects.js"></script>
<script type="text/javascript" src="..\libraries\assets\js\classie.js"></script>
<!-- Custom js -->
<script src="..\libraries\assets\pages\data-table\js\data-table-custom.js"></script>
<script src="..\libraries\assets\js\pcoded.min.js"></script>
<script src="..\libraries\assets\js\vartical-layout.min.js"></script>
<script src="..\libraries\assets\js\jquery.mCustomScrollbar.concat.min.js"></script>
<script type="text/javascript" src="../libraries/assets/js/sweetalert.js"></script>

<script type="text/javascript" src="..\libraries\assets\js\script.js"></script>
<script src="..\libraries\bower_components\raphael\js\raphael.min.js"></script>
<script src="..\libraries\bower_components\morris.js\js\morris.js"></script>

<script>
    $(document).ready(function () {

        $('#password11').keyup(function () {
            var npass = $('#password11').val();
            var cpass = $('#cpassword11').val();
            if (npass == cpass) {
                $('#subs1').attr('disabled', false);
                $('#mylab').html("<span class='text-success'>Password matched</span>")
            } else {
                $('#subs1').attr('disabled', true);
                $('#mylab').html("<span class='text-danger'>Password not matched</span>")
            }
        });
        $('#cpassword11').keyup(function () {
            var npass = $('#password11').val();
            var cpass = $('#cpassword11').val();
            if (npass == cpass) {
                $('#subs1').attr('disabled', false);
                $('#mylab').html("<span class='text-success'>Password matched</span>")
            } else {
                $('#subs1').attr('disabled', true);
                $('#mylab').html("<span class='text-danger'>Password not matched</span>")
            }
        });
    });
</script>