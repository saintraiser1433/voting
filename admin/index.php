<?php
include '../connection.php';
if (isset($_POST['submits'])) {
    $username = $_POST['username'];
    $password = md5($_POST['password']);
    $sql = "SELECT * FROM admin where username='$username' and password='$password'";
    $rs = $conn->query($sql);
    $row = $rs->fetch_assoc();
    if ($rs->num_rows > 0) {
        $_SESSION['at'] = $row['id'];
        $_SESSION['username'] = $row['username'];
        header("Location:dashboard.php");
    } else {
        $_SESSION['response'] = "Incorrect Credentials";
        $_SESSION['type'] = "danger";
    }
}



?>

<!DOCTYPE html>
<html>

<head>
    <title>GIT MOBILE BASED VOTING SYSTEM</title>
    <link rel="icon" href="../libraries/img/glanlogo.png" type="image/x-icon">
    <link rel="stylesheet" type="text/css" href="../libraries/lassets/css/style.css">
    <link rel="stylesheet" type="text/css" href="../libraries\assets\icon\font-awesome\css\font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="../libraries\bower_components\bootstrap\css\bootstrap.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>
    <img class="wave" src="../libraries/lassets/img/wave.png">
    <div class="container">
        <div class="img">
            <img src="../libraries/lassets/img/bg.svg">
        </div>
        <div class="login-content">
            <form action="" method="post">

                <img src="../libraries/lassets/img/avatar.svg">
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
                        <h5>Username</h5>
                        <input type="text" name="username" class="input">
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

                <button type="submit" name="submits" class="btn">Login </button>
            </form>
        </div>
    </div>
    <script type="text/javascript" src="../libraries/lassets/js/main.js"></script>
</body>

</html>
<style>
    @font-face {
        font-family: "antipasomedium";
        src: url("../libraries/font/LexendDeca-Regular.ttf") format('truetype');

    }

    body {
        font-family: "antipasomedium";
    }
</style>