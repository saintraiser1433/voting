<?php
$acad = $_SESSION['acad'];
if (isset($_POST['submitqwe'])) {
    $idhidden = $_POST['idhidden'];
    $title = $_POST['titles'];
    $start = $_POST['start'];
    $end = $_POST['end'];
    if ($idhidden == "") {
        $sql = "INSERT INTO election_title (title,acad_id,date_start,date_end) values ('$title','$acad','$start','$end')";
        if ($conn->query($sql)) {
            $_SESSION['response'] = "Success";
            $_SESSION['type'] = "success";
        } else {
            $_SESSION['response'] = "An error has occurred";
            $_SESSION['type'] = "warning";
        }
    } else {
        $sql = "UPDATE election_title SET title='$title',date_start='$start',date_end='$end' where id='$idhidden'";
        if ($conn->query($sql)) {
            $_SESSION['response'] = "Updated";
            $_SESSION['type'] = "success";
        } else {
            $_SESSION['response'] = "An error has occurred";
            $_SESSION['type'] = "warning";
        }
    }
}


?>

<div class="modal fade z-index-1" id="mytitleq" role="dialog" aria-hidden="true" data-backdrop="static"
    data-keyboard="false">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-titles">Election Settings</h4>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"
                    onclick="location.reload();">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <form action="" method="post" enctype="multipart/form-data">
                <div class="modal-body">
                    <?php
                    $sql = "SELECT * FROM election_title where acad_id='$acad'";
                    $rs1233 = $conn->query($sql);
                    $rowww = $rs1233->fetch_assoc();

                    ?>
                    <div class="form-group">
                        <label class="col-form-label">Enter title:</label>
                        <input type="hidden" class="form-control" name="idhidden" value="<?php echo @$rowww['id']; ?>">
                        <input type="text" class="form-control" name="titles" value="<?php echo @$rowww['title']; ?>">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Date Start:</label>
                        <input type="date" class="form-control" name="start"
                            value="<?php echo @$rowww['date_start']; ?>">
                    </div>
                    <div class="form-group">
                        <label class="col-form-label">Date End:</label>
                        <input type="date" class="form-control" name="end" value="<?php echo @$rowww['date_end']; ?>">
                    </div>

                </div>
                <div class="modal-footer" id="footersa">

                    <button type="submit" class="btn btn-primary waves-effect waves-light" name="submitqwe"
                        id="submits">Save changes</button>
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