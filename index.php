<?php
include 'connection.php';
$acad = $_SESSION['acad'];
if (isset($_POST['submits'])) {
    $username = $_POST['username'];
    $password = $_POST['password'];
    $md5 = md5($_POST['password']);
    if ($_POST['myps1'] == '') {
        $sql = "SELECT * FROM voters where stud_id='$username' and CONCAT(lname,stud_id)='$password' and acad_id='$acad' and is_verified=1";
        $rs = $conn->query($sql);
        $row = $rs->fetch_assoc();
        if ($rs->num_rows > 0) {
            $_SESSION['mypass'] = "s";
            $_SESSION['v_id'] = $row['v_id'];
            $_SESSION['face'] = $row['v_id'];
            $_SESSION['username'] = $row['lname'] . ", " . $row['fname'] . " " . $row['mname'][0];
            header("Location:indexss.php");
        } else {
            $_SESSION['response'] = "Incorrect Credentials";
            $_SESSION['type'] = "danger";
        }
    } else {
        $sql = "SELECT * FROM voters where stud_id='$username' and password='$md5' and acad_id='$acad' and is_verified=1";
        $rs = $conn->query($sql);
        $row = $rs->fetch_assoc();
        if ($rs->num_rows > 0) {
            $_SESSION['mypass'] = $row['password'];
            $_SESSION['v_id'] = $row['v_id'];
            $_SESSION['face'] = $row['v_id'];
            $_SESSION['username'] = $row['lname'] . ", " . $row['fname'] . " " . $row['mname'][0];
            header("Location:face-scan.php");
        } else {
            $_SESSION['response'] = "Incorrect Credentials";
            $_SESSION['type'] = "danger";
        }
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
                <input type="hidden" name="myps1" id="myps">
                <img src="libraries/lassets/img/avatar.svg">
                <?php if (isset($_SESSION['response'])) { ?>
                    <div class="alert alert-<?= $_SESSION['type']; ?> alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times </button>
                        <?= $_SESSION['response']; ?>
                    </div>
                    <?php unset($_SESSION['response']);
                } ?>
                <h2 class="title">Welcome</h2>
                <div class="input-div one">
                    <div class="i">
                        <i class="fa fa-user"></i>
                    </div>
                    <div class="div">

                        <h5>Student ID</h5>

                        <input type="text" name="username" class="input" id="username">
                    </div>
                </div>
                <div class="input-div pass">
                    <div class="i">
                        <i class="fa fa-lock"></i>
                    </div>
                    <div class="div">
                        <h5>Password</h5>
                        <input type="password" name="password" class="input">
                    </div>
                </div>
                <div class="linking">
                    <a href="signup.php">If not register kindly click this link</a>
                </div>


                <button type="submit" name="submits" class="btn">Login </button>
            </form>
        </div>
    </div>
    <script type="text/javascript" src="libraries\bower_components\jquery\js\jquery.min.js"></script>
    <script type="text/javascript" src="libraries/lassets/js/main.js"></script>
</body>

</html>
<script>
    $(document).ready(function () {
        $('#username').keyup(function () {
            var news = $(this).val();
            $.ajax({
                method: "POST",
                url: "ajax/usernamepass.php",
                data: {
                    myids: news,
                },
                success: function (html) {
                    $('#myps').val(html);
                }

            });
        });
    });
</script>
<style>
    @font-face {
        font-family: "antipasomedium";
        src: url("libraries/font/LexendDeca-Regular.ttf") format('truetype');

    }

    body {
        font-family: "antipasomedium";
    }

    .linking {
        display: flex;
        justify-items: left;
    }
</style>