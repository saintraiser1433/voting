<?php
include 'connection.php';
if (isset($_POST['submits'])) {
    $username = $_SESSION['s_id'];
    $password = $_POST['submits'];
    $acad = $_SESSION['acad'];
    $sql = "SELECT * FROM voters where v_id='$username' and auth_code='$password' and acad_id='$acad'";
    $rs = $conn->query($sql);
    $row = $rs->fetch_assoc();
    if ($rs->num_rows > 0) {
        $_SESSION['v_id'] = $row['v_id'];
        header("Location:home.php");
    } else {
        $_SESSION['response'] = "Incorrect Credentials";
        $_SESSION['type'] = "danger";
    }
}



?>

<!DOCTYPE html>
<html>

<head>
    <title>PPNHS VTS</title>
    <link rel="icon" href="libraries/img/logo.png" type="image/x-icon">

    <link rel="stylesheet" type="text/css" href="libraries\assets\icon\font-awesome\css\font-awesome.min.css">
    <link rel="stylesheet" type="text/css" href="libraries\bower_components\bootstrap\css\bootstrap.min.css">
    <link rel="stylesheet" type="text/css" href="libraries\assets\css\styles.css">
    <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>

    <section class="login-block">
        <!-- Container-fluid starts -->
        <div class="container">
            <div class="row">
                <div class="col-sm-12">

                    <form method="post" id="mysubmit" class="md-float-material form-material">

                        <div class="auth-box card">
                            <div class="card-block">
                                <div class="row m-b-20">
                                    <div class="col-md-12">
                                        <!-- Authentication card start -->
                                        <?php if (isset($_SESSION['response'])) { ?>
                                            <div class="alert alert-<?= $_SESSION['type']; ?> alert-dismissible">
                                                <button type="button" class="close" data-dismiss="alert">&times </button>
                                                <?= $_SESSION['response']; ?>
                                            </div>
                                        <?php unset($_SESSION['response']);
                                        } ?>
                                        <h3 class="text-center">Step 2: Scan QR CODE</h3>
                                    </div>
                                </div>
                                <div class="form-group form-primary">
                                    <video id="preview" width="100%"></video>
                                    <input type="hidden" name="submits" id="myvalue">
                                    <span class="form-bar"></span>
                                </div>
                                <hr>

                            </div>
                        </div>
                    </form>
                    <!-- end of form -->
                </div>
                <!-- end of col-sm-12 -->
            </div>
            <!-- end of row -->
        </div>
        <!-- end of container-fluid -->
    </section>

</body>

</html>
<?php include 'nav/script.php' ?>
<script type="text/javascript">
    let scanner = new Instascan.Scanner({
        video: document.getElementById('preview')
    });
    scanner.addListener('scan', function(content) {
        console.log(content);
    });
    Instascan.Camera.getCameras().then(function(cameras) {
        if (cameras.length > 0) {
            scanner.start(cameras[0]);
        } else {
            console.error('No cameras found.');
        }
    }).catch(function(e) {
        console.error(e);
    });
    scanner.addListener('scan', function(c) {
        document.getElementById('myvalue').value = c;
        $('#mysubmit').submit();

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
</style>