<?php
include 'connection.php';
$acad = $_SESSION['acad'];
$voting_mode = isset($_POST['voting_mode']) && $_POST['voting_mode'] === 'department' ? 'department' : 'general';

if (isset($_POST['submits'])) {
    $username = $conn->real_escape_string($_POST['username']);
    $password = $_POST['password'];
    $md5 = md5($password);

    if ($voting_mode === 'department') {
        if (!isset($_POST['myps1']) || $_POST['myps1'] === '') {
            $sql = "SELECT * FROM dept_voters WHERE stud_id='$username' AND CONCAT(lname,stud_id)='$password' AND acad_id='$acad' AND is_verified=1";
        } else {
            $sql = "SELECT * FROM dept_voters WHERE stud_id='$username' AND password='$md5' AND acad_id='$acad' AND is_verified=1";
        }
        $rs = $conn->query($sql);
        $row = $rs && $rs->num_rows > 0 ? $rs->fetch_assoc() : null;
        if ($row) {
            $_SESSION['voting_mode'] = 'department';
            $_SESSION['mypass'] = (isset($_POST['myps1']) && $_POST['myps1'] !== '') ? $row['password'] : 's';
            $_SESSION['v_id'] = $row['dv_id'];
            $_SESSION['face'] = $row['dv_id'];
            $_SESSION['dept_id'] = $row['department_id'];
            $m = isset($row['mname'][0]) ? $row['mname'][0] . '.' : '';
            $_SESSION['username'] = $row['lname'] . ", " . $row['fname'] . " " . $m;
            if ($_SESSION['mypass'] === 's') {
                header("Location:indexss.php");
            } else {
                header("Location:face-scan.php");
            }
        } else {
            $_SESSION['response'] = "Incorrect Credentials";
            $_SESSION['type'] = "danger";
        }
    } else {
        if (!isset($_POST['myps1']) || $_POST['myps1'] === '') {
            $sql = "SELECT * FROM voters WHERE stud_id='$username' AND CONCAT(lname,stud_id)='$password' AND acad_id='$acad' AND is_verified=1";
        } else {
            $sql = "SELECT * FROM voters WHERE stud_id='$username' AND password='$md5' AND acad_id='$acad' AND is_verified=1";
        }
        $rs = $conn->query($sql);
        $row = $rs && $rs->num_rows > 0 ? $rs->fetch_assoc() : null;
        if ($row) {
            $_SESSION['voting_mode'] = 'general';
            $_SESSION['mypass'] = (isset($_POST['myps1']) && $_POST['myps1'] !== '') ? $row['password'] : 's';
            $_SESSION['v_id'] = $row['v_id'];
            $_SESSION['face'] = $row['v_id'];
            $_SESSION['username'] = $row['lname'] . ", " . $row['fname'] . " " . (isset($row['mname'][0]) ? $row['mname'][0] : '');
            if ($_SESSION['mypass'] === 's') {
                header("Location:indexss.php");
            } else {
                header("Location:face-scan.php");
            }
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
            <form action="" method="post" id="loginForm">
                <input type="hidden" name="myps1" id="myps">
                <input type="hidden" name="voting_mode" id="voting_mode" value="general">
                <img src="libraries/lassets/img/avatar.svg">
                <?php if (isset($_SESSION['response'])) { ?>
                    <div class="alert alert-<?= $_SESSION['type']; ?> alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times </button>
                        <?= $_SESSION['response']; ?>
                    </div>
                    <?php unset($_SESSION['response']);
                } ?>
                <div class="mb-3 d-flex justify-content-around" style="gap: 8px;">
                    <button type="button" class="btn btn-outline-primary flex-fill active" id="tabGeneral" data-mode="general">General Voting</button>
                    <button type="button" class="btn btn-outline-secondary flex-fill" id="tabDepartment" data-mode="department">Department Voting</button>
                </div>
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
        $('#tabGeneral').on('click', function () {
            $('#voting_mode').val('general');
            $('#tabGeneral').addClass('active btn-primary').removeClass('btn-outline-primary');
            $('#tabDepartment').removeClass('active btn-primary').addClass('btn-outline-secondary');
        });
        $('#tabDepartment').on('click', function () {
            $('#voting_mode').val('department');
            $('#tabDepartment').addClass('active btn-primary').removeClass('btn-outline-secondary');
            $('#tabGeneral').removeClass('active btn-primary').addClass('btn-outline-primary');
        });
        $('#username').keyup(function () {
            var news = $(this).val();
            var mode = $('#voting_mode').val();
            $.ajax({
                method: "POST",
                url: "ajax/usernamepass.php",
                data: { myids: news, mode: mode },
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