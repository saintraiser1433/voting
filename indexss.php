<?php
include 'connection.php';
$acad = $_SESSION['acad'];
$cc = $_SESSION['mypass'];
if ($_SESSION['mypass'] != 's') {
    header("Location:face-scan.php");
}


if (isset($_POST['submitsq'])) {
    $id = $_POST['idhidden'];
    $password = md5($_POST['password']);
    $sqls = "UPDATE voters SET password='$password' where v_id='$id'";
    if ($conn->query($sqls)) {
        header("Location:face-scan.php");
    }
}



?>

<!DOCTYPE html>
<html>

<head>
    <title>GIT MOBILE BASED VOTING SYSTEM</title>
    <link rel="icon" href="libraries/img/glanlogo.png" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="libraries/lassets/css/style.css">
    <link rel="stylesheet" type="text/css" href="libraries\assets\icon\font-awesome\css\font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="libraries\bower_components\bootstrap\css\bootstrap.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
    <img class="wave" src="libraries/lassets/img/wave.png">
    <div class="container">
        <div class="img">
            <img src="libraries/lassets/img/bg.svg">
        </div>
        <div class="login-content">
            <form action="" method="post">

                <img src="libraries/lassets/img/avatar.svg">
                <?php if (isset($_SESSION['response'])) { ?>
                    <div class="alert alert-<?= $_SESSION['type']; ?> alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times </button>
                        <?= $_SESSION['response']; ?>
                    </div>
                    <?php unset($_SESSION['response']);
                } ?>
                <h4 class="title">Type your new password</h2>
                    <div class="input-div one">
                        <div class="i">
                            <i class="fa fa-user"></i>
                        </div>
                        <div class="div">
                            <h5>Password</h5>
                            <input type="hidden" name="idhidden" class="input" id="password1122"
                                value="<?php echo $_SESSION['v_id'] ?>">
                            <input type="password" name="password" class="input" id="password11"
                                pattern="(?=.*[!@#$%^&*])(?=.*\d)(?=.*[a-z])(?=.*[A-Z].{8,}"
                                title="Must contain at least one number and one uppercase and lowercase letter and atleast 8 or more characters"
                                required>
                        </div>
                    </div>
                    <div class="input-div pass">
                        <div class="i">
                            <i class="fa fa-lock"></i>
                        </div>
                        <div class="div">
                            <h5>Confirm Password</h5>
                            <input type="password" name="cspassword" class="input" id="cpassword11">
                        </div>

                    </div>
                    <div id="available"></div>

                    <button type="submit" name="submitsq" class="btn" id="login">Submit </button>
            </form>
        </div>
    </div>
    <script type="text/javascript" src="libraries\bower_components\jquery\js\jquery.min.js"></script>
    <script type="text/javascript" src="libraries/lassets/js/main.js"></script>
</body>

</html>
<style>
    @font-face {
        font-family: "antipasomedium";
        src: url("libraries/font/LexendDeca-Regular.ttf") format('truetype');

    }

    body {
        font-family: "antipasomedium";
    }
</style>

<script>
    $(document).ready(function () {

        $('#password11').keyup(function () {
            var npass = $('#password11').val();
            var cpass = $('#cpassword11').val();
            if (npass == cpass) {
                $('#login').attr('disabled', false);
                $('#available').html("<span style='color:green'>Password matched</span>")
            } else {
                $('#login').attr('disabled', true);
                $('#available').html("<span style='color:red'>Password not matched</span>")
            }
        });
        $('#cpassword11').keyup(function () {
            var npass = $('#password11').val();
            var cpass = $('#cpassword11').val();
            if (npass == cpass) {
                $('#login').attr('disabled', false);
                $('#available').html("<span style='color:green'>Password matched</span>")
            } else {
                $('#login').attr('disabled', true);
                $('#available').html("<span style='color:red'>Password not matched</span>")
            }

        });
    });
</script>