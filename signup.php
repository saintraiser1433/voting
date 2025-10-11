<?php include 'connection.php'; ?>

<!DOCTYPE html>
<html lang="en">

<?php include 'nav/header.php'; ?>
<!-- Menu sidebar static layout -->

<body>
    <!-- Pre-loader start -->
    <div class="theme-loader">
        <div class="ball-scale">
            <div class='contain'>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
                <div class="ring">
                    <div class="frame"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- Pre-loader end -->
    <div id="pcoded" class="pcoded">
        <div class="pcoded-overlay-box"></div>
        <div class="pcoded-container navbar-wrapper">
            <div class="p-lg-5 bg-white">
                <div class="row d-flex justify-content-center">
                    <div class="col-12 col-lg-4">
                        <div class="card">
                            <div class="card bg-c-blue text-white">
                                <div class="card-block">
                                    <div class="row align-items-center">
                                        <div class="col">
                                            <h3 class="m-b-5"><b>FACE CAPTURE</b></h3>
                                        </div>

                                    </div>
                                </div>
                            </div>
                            <hr />
                            <div class="card-block-big">
                                <div class="d-flex flex-column justify-content-center align-items-center">
                                    <video id="video" class="mainImage"></video>
                                    <canvas id="canvas" style="display:none"></canvas>
                                    <button id="captureBtn">Captured</button>
                                </div>
                                <div class="row justify-content-center thumbnail-list mt-2">
                                    <div class="thumbnail mx-2"><span class="thumbnail-number">1</span>
                                    </div>
                                    <div class="thumbnail mx-2"><span class="thumbnail-number">2</span>
                                    </div>
                                    <div class="thumbnail mx-2"><span class="thumbnail-number">3</span>
                                    </div>
                                    <div class="thumbnail mx-2"><span class="thumbnail-number">4</span>
                                    </div>
                                    <div class="thumbnail mx-2"><span class="thumbnail-number">5</span>
                                    </div>
                                </div>

                            </div>

                        </div>
                    </div>
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
                                                    <input type="hidden" name="idhidden" id="idhidden"
                                                        class="form-control text-uppercase" required>
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
                                                    <label class="col-form-label">Middle
                                                        Name</label>
                                                    <input type="text" name="mname" id="mname"
                                                        class="form-control text-uppercase" required>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-12">
                                                <div class="form-group">
                                                    <label class="col-form-label">Year
                                                        Level</label>
                                                    <select name="yearlevel" class="form-control" id="yearlevel"
                                                        required>
                                                        <option value=""></option>
                                                        <option value="1">1st Year</option>
                                                        <option value="2">2nd Year</option>
                                                        <option value="3">3rd Year</option>
                                                        <option value="4">4th Year</option>
                                                    </select>
                                                </div>
                                            </div>
                                            <div class="col-lg-6 col-12">
                                                <div class="d-flex align-items-center" id="content">

                                                </div>
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
                                </form>
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


    <!-- Required Jquery -->
    <?php include 'nav/script.php'; ?>

    <?php
    if (isset($_SESSION['response']) && $_SESSION['response'] != "") {

        ?>
        <script>
            swal({
                title: "<?php echo $_SESSION['response']; ?>",
                icon: "<?php echo $_SESSION['type']; ?>",
                button: "Exit!",
            })
        </script>
        <?php unset($_SESSION['response']);
    }
    ?>
    <?php include 'modalplatform.php'; ?>

    <script>
        let capturedImages = [];

        function dataURLtoBlob(dataURL) {
            const arr = dataURL.split(',');
            const mime = arr[0].match(/:(.*?);/)[1];
            const bstr = atob(arr[1]);
            let n = bstr.length;
            const u8arr = new Uint8Array(n);

            while (n--) {
                u8arr[n] = bstr.charCodeAt(n);
            }

            return new Blob([u8arr], { type: mime });
        }

        $("#studid").keyup(function () {
            var p = $(this).val();
            $.ajax({
                url: "admin/ajax/fetchid.php",
                method: "POST",
                data: {
                    myids: p
                },
                dataType: "json",
                success: function (html) {
                    if (html.stat == 1) {
                        $('#available').html('<span class="text-danger">Not available</span>');
                        $('#submits').attr('disabled', true);
                    } else {
                        $('#available').html('<span class="text-success">Available</span>');
                        $('#submits').attr('disabled', false);
                    }

                }
            });
        });

        $('#yearlevel').on('change', function () {
            var p = $(this).val();
            $.ajax({
                url: "admin/ajax/fetchstrand.php",
                method: "POST",
                data: {
                    id: p
                },
                dataType: "text",
                success: function (html) {
                    $('#content').html(html);
                }
            });
        });
        $(document).on('click', '#back', function (e) {
            window.location.href = "index.php";
        });
        $(document).on('click', '#submit', function (e) {
            e.preventDefault();

            if (capturedImages.length < 5) {
                swal("Error!", "Please capture all 5 images first!", "error");
                return;
            }
            const formData = new FormData();
            formData.append('studid', $('#studid').val());
            formData.append('fname', $('#fname').val());
            formData.append('lname', $('#lname').val());
            formData.append('mname', $('#mname').val());
            formData.append('yearlevel', $('#yearlevel').val());
            formData.append('strand', $('#strand').val());
            formData.append('section', $('#section').val());


            // Append each captured image
            capturedImages.forEach((imageData, index) => {
                // Convert base64 to blob
                const imageBlob = dataURLtoBlob(imageData);
                formData.append(`image${index + 1}`, imageBlob, `image${index + 1}.jpg`);
            });

            swal({
                title: "Are you sure?",
                text: "Once submit, your data will submit",
                icon: "info",
                buttons: true,
                dangerMode: true,
            })
                .then((willSubmit) => {
                    if (willSubmit) {
                        $.ajax({
                            method: "POST",
                            url: "admin/ajax/submitData.php",
                            processData: false,
                            contentType: false,
                            data: formData,
                            success: function (html) {
                                const result = JSON.parse(html);

                                if (result.success) {
                                    swal("Success!", "We will validate your submition details to verify if its you thank you. Please wait for an hour thankss!", "success")
                                        .then((value) => {
                                            window.location.href = "index.php";
                                        });
                                } else {
                                    swal("Error!", result.message || "Something went wrong!", "error");
                                }
                            }

                        });

                    } else {
                        swal("Your imaginary file is safe!");
                    }
                });
        });


        const video = document.getElementById('video');
        const canvas = document.getElementById('canvas');
        const captureBtn = document.getElementById('captureBtn');
        const mainImage = document.getElementById('mainImage');
        const thumbnails = document.querySelectorAll('.thumbnail');
        let currentImageCount = 0;
        let stream = null;

        async function startCamera() {
            try {
                stream = await navigator.mediaDevices.getUserMedia({ video: true });
                video.srcObject = stream;
                video.play();
            } catch (err) {
                swal("Error!", `Error accessing camera: ${err}`, "error");
            }
        }

        function updateThumbnails() {
            thumbnails.forEach((thumbnail, index) => {
                thumbnail.innerHTML = '';
                if (capturedImages[index]) {
                    const img = document.createElement('img');
                    img.src = capturedImages[index];
                    thumbnail.appendChild(img);
                }
                const span = document.createElement('span');
                span.className = 'thumbnail-number';
                span.textContent = (index + 1).toString();
                thumbnail.appendChild(span);
            });
        }

        // Add click event listeners to thumbnails
        thumbnails.forEach((thumbnail, index) => {
            thumbnail.addEventListener('click', () => {
                if (capturedImages[index]) {
                    // Remove the image at the clicked index
                    capturedImages.splice(index, 1);
                    currentImageCount--;

                    // Enable capture button if it was disabled
                    if (captureBtn.disabled) {
                        captureBtn.disabled = false;
                    }

                    // Shift remaining images forward
                    for (let i = index; i < thumbnails.length - 1; i++) {
                        if (capturedImages[i]) {
                            const nextImage = capturedImages[i];
                            const currentThumbnail = thumbnails[i];
                            currentThumbnail.innerHTML = '';
                            const img = document.createElement('img');
                            img.src = nextImage;
                            currentThumbnail.appendChild(img);
                            const span = document.createElement('span');
                            span.className = 'thumbnail-number';
                            span.textContent = (i + 1).toString();
                            currentThumbnail.appendChild(span);
                        }
                    }

                    // Clear the last thumbnail if there was a shift
                    if (currentImageCount < thumbnails.length) {
                        const lastThumbnail = thumbnails[currentImageCount];
                        lastThumbnail.innerHTML = '';
                        const span = document.createElement('span');
                        span.className = 'thumbnail-number';
                        span.textContent = (currentImageCount + 1).toString();
                        lastThumbnail.appendChild(span);
                    }

                    updateThumbnails();
                }
            });
        });

        canvas.width = 1280;
        canvas.height = 1280;
        const ctx = canvas.getContext('2d');

        captureBtn.addEventListener('click', () => {
            if (currentImageCount >= 5) return;

            ctx.drawImage(video, 0, 0, canvas.width, canvas.height);
            const imageData = canvas.toDataURL('image/jpeg');
            capturedImages.push(imageData);
            updateThumbnails();
            currentImageCount++;

            if (currentImageCount >= 5) {
                captureBtn.disabled = true;
            }
        });

        startCamera();

        window.addEventListener('beforeunload', () => {
            if (stream) {
                stream.getTracks().forEach(track => track.stop());
            }
        });




    </script>


</body>
<style>
    .thumbnail-container {
        display: flex;
        gap: 10px;
        margin-top: 20px;
    }

    .thumbnail {
        width: 100px;
        height: 100px;
        border: 2px solid #ccc;
        display: flex;
        cursor: pointer;
        justify-content: center;
        align-items: center;
        overflow: hidden;
        position: relative;
    }

    .thumbnail:hover {
        opacity: 0.8;
    }

    .thumbnail img {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }

    .thumbnail-placeholder {
        color: #666;
    }

    .mainImage {
        max-height: 500px;
        max-width: 500px;
        min-width: 440px;
        min-height: 440px;
        border: 2px solid gray;
    }

    #captureBtn {
        margin-top: 10px;
        padding: 8px 16px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    #captureBtn:disabled {
        background-color: #ccc;
        cursor: not-allowed;
    }
</style>

</html>