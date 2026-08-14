<?php include 'connection.php'; ?>

<!DOCTYPE html>
<html lang="en">

<?php include 'nav/header.php'; ?>

<body>
    <div class="theme-loader">
        <div class="ball-scale">
            <div class='contain'>
                <div class="ring"><div class="frame"></div></div>
            </div>
        </div>
    </div>
    <div id="pcoded" class="pcoded">
        <div class="pcoded-overlay-box"></div>
        <div class="pcoded-container navbar-wrapper">
            <div class="p-lg-5 bg-white">
                <div class="row d-flex justify-content-center">
                    <div class="col-12 col-lg-8">
                        <div class="card">
                            <div class="card bg-c-blue text-white">
                                <div class="card-block">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h3 class="m-b-5"><b>SIGN UP NOW</b></h3>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-block-big">
                                <div class="row">
                                    <div class="col-12 col-lg-12">
                                        <div class="row">
                                            <div class="col-lg-6 col-12">
                                                <div class="form-group">
                                                    <label class="col-form-label">Student ID</label>
                                                    <input type="text" name="studid" id="studid"
                                                        class="form-control text-uppercase" required>
                                                    <div id="available"></div>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-12">
                                                <div class="form-group">
                                                    <label class="col-form-label">First Name</label>
                                                    <input type="text" name="fname" id="fname"
                                                        class="form-control text-uppercase" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-12">
                                                <div class="form-group">
                                                    <label class="col-form-label">Last Name</label>
                                                    <input type="text" name="lname" id="lname"
                                                        class="form-control text-uppercase" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-12">
                                                <div class="form-group">
                                                    <label class="col-form-label">Middle Name</label>
                                                    <input type="text" name="mname" id="mname"
                                                        class="form-control text-uppercase" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-12">
                                                <div class="form-group">
                                                    <label class="col-form-label">Year Level</label>
                                                    <select name="yearlevel" class="form-control" id="yearlevel" required>
                                                        <option value=""></option>
                                                        <option value="1">1st Year</option>
                                                        <option value="2">2nd Year</option>
                                                        <option value="3">3rd Year</option>
                                                        <option value="4">4th Year</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-12">
                                                <div class="d-flex align-items-center" id="content"></div>
                                            </div>
                                            <div class="col-lg-6 col-12">
                                                <button type="button"
                                                    class="btn btn-success waves-effect waves-light my-2"
                                                    id="submit">Submit</button>
                                                <button type="button"
                                                    class="btn btn-warning waves-effect waves-light my-2"
                                                    id="back">Back</button>
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
    </div>

    <?php include 'nav/script.php'; ?>

    <?php if (isset($_SESSION['response']) && $_SESSION['response'] != "") { ?>
        <script>
            swal({
                title: "<?php echo $_SESSION['response']; ?>",
                icon: "<?php echo $_SESSION['type']; ?>",
                button: "Exit!",
            })
        </script>
        <?php unset($_SESSION['response']); ?>
    <?php } ?>

    <script>
        $("#studid").keyup(function () {
            var p = $(this).val();
            $.ajax({
                url: "admin/ajax/fetchid.php",
                method: "POST",
                data: { myids: p },
                dataType: "json",
                success: function (html) {
                    if (html.stat == 1) {
                        $('#available').html('<span class="text-danger">Not available</span>');
                        $('#submit').attr('disabled', true);
                    } else {
                        $('#available').html('<span class="text-success">Available</span>');
                        $('#submit').attr('disabled', false);
                    }
                }
            });
        });

        $('#yearlevel').on('change', function () {
            $.ajax({
                url: "admin/ajax/fetchstrand.php",
                method: "POST",
                data: { id: $(this).val() },
                dataType: "text",
                success: function (html) {
                    $('#content').html(html);
                }
            });
        });

        $(document).on('click', '#back', function () {
            window.location.href = "index.php";
        });

        $(document).on('click', '#submit', function (e) {
            e.preventDefault();

            const formData = new FormData();
            formData.append('studid', $('#studid').val());
            formData.append('fname', $('#fname').val());
            formData.append('lname', $('#lname').val());
            formData.append('mname', $('#mname').val());
            formData.append('yearlevel', $('#yearlevel').val());
            formData.append('strand', $('#strand').val());
            formData.append('section', $('#section').val());

            swal({
                title: "Are you sure?",
                text: "Once submit, your data will submit",
                icon: "info",
                buttons: true,
                dangerMode: true,
            }).then((willSubmit) => {
                if (willSubmit) {
                    $.ajax({
                        method: "POST",
                        url: "admin/ajax/submitData.php",
                        processData: false,
                        contentType: false,
                        dataType: "json",
                        data: formData,
                        success: function (response) {
                            if (response && response.success) {
                                swal("Success!", "We will validate your submission details. Please wait for admin verification.", "success")
                                    .then(function () {
                                        window.location.href = "index.php";
                                    });
                            } else {
                                var msg = (response && response.message) ? response.message : "Something went wrong!";
                                swal("Error!", msg, "error");
                            }
                        },
                        error: function (xhr, status, error) {
                            swal("Error!", "Request failed: " + error, "error");
                        }
                    });
                }
            });
        });
    </script>
</body>

</html>
