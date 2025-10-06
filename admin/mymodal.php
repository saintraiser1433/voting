<?php
if (isset($_POST['submitqwew1'])) {

    $id = $_POST['idhidden'];
    $username = $_POST['username11'];
    $pass = md5($_POST['password11']);
    $cpass = $_POST['cpassword11'];
    $old = md5($_POST['oldpass11']);
    $sqlt = "SELECT * FROM admin where password='$old'";
    $rss = $conn->query($sqlt);
    if ($rss->num_rows > 0) {
        $sql = "UPDATE admin SET username='$username', password='$pass' where id='$id'";
        $conn->query($sql);
        $_SESSION['response'] = "Admin account updated";
        $_SESSION['type'] = "success";
    } else {
        $_SESSION['response'] = "Incorrect password can't update password";
        $_SESSION['type'] = "warning";
    }
}





?>

<div class="modal fade" id="mytitleqw" tabindex="-1" role="dialog" aria-hidden="true" data-backdrop="static" data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-titles">Title</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="location.reload();">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="form-group">
                        <input type="hidden" name="idhidden" id="idhidden" value="<?php echo $_SESSION['at'] ?>">
                        <label class="col-form-label">Username</label>
                        <input type="text" class="form-control" name="username11" value="<?php echo $_SESSION['username'] ?>">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Password</label>
                        <input type="password" class="form-control" name="password11" id="password11">

                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Confirm Password</label>
                        <input type="password" class="form-control" name="cpassword11" id="cpassword11">
                        <div id="mylab"></div>
                    </div>

                    <div class="form-group">
                        <label class="col-form-label">Old Password</label>
                        <input type="password" class="form-control" name="oldpass11" id="oldpass11">
                    </div>





                </div>
                <div class="modal-footer" id="footersa">

                    <button type="submit" class="btn btn-primary waves-effect waves-light" name="submitqwew1" id="subs1">Save changes</button>
                </div>
            </form>
        </div>
    </div>
</div>


<script>
    if (window.history.replaceState) {
        window.history.replaceState(null, null, window.location.href);
    }
</script>