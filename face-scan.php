<?php include 'connection.php';

if (!isset($_SESSION['face'])) {
    header("Location:index.php");
}
$id = $_SESSION['v_id'];


$sql = "SELECT v_id,lname,fname,mname FROM voters where is_verified = 1 and v_id ='$id'";
$result = mysqli_query($conn, $sql);
$srcs = array();
$faceIdMap = array();

if (mysqli_num_rows($result) > 0) {
    while ($row = mysqli_fetch_assoc($result)) {

        $name = $row['lname'] . "," . $row['fname'] . " " . $row['mname'][0];


        $srcs[] = $name;


        $faceIdMap[$name] = $row['v_id'];
    }
}


$faceIdMapJS = "const faceIdMap = new Map(" . json_encode(array_map(
    function ($key, $value) {
        return [$key, $value];
    },
    array_keys($faceIdMap),
    array_values($faceIdMap)
), JSON_PRETTY_PRINT) . ");";







?>

<!DOCTYPE html>
<html lang="en">

<?php include 'nav/header.php'; ?>
<!-- Menu sidebar static layout -->

<body class="bg-white">
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

            <div class="p-lg-5 bg-white">
                   <div class="row d-flex justify-content-center align-items-center">
                                    <div class="col-12 col-lg-4">
                                        <div class="card">
                                            <div class="card m-0 bg-c-blue text-white">
                                                <div class="card-block">
                                                    <div class="row align-items-center">
                                                        <div class="col">
                                                            <h3><b>FACE VERIFICATION</b></h3>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                            <div class="card-body p-0">
                                                <div
                                                    class="d-flex flex-column justify-content-center align-items-center position-relative">
                                                    <div class="detection-container">
                                                        <video id="video" autoplay muted></video>
                                                        <div id="overlays"></div>
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
    <script type="text/javascript" src="./libraries/assets/js/face-api.min.js"></script>
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
    <script>
        const video = document.getElementById('video');
        const labelsData = <?php echo json_encode($srcs); ?>;
        <?php echo $faceIdMapJS; ?>
        let canvas, displaySize;

        // Load models with higher precision weights
        Promise.all([
            faceapi.nets.tinyFaceDetector.loadFromUri('./libraries/assets/models'), // Faster and more accurate than ssdMobilenetv1
            faceapi.nets.faceLandmark68Net.loadFromUri('./libraries/assets/models'),
            faceapi.nets.faceRecognitionNet.loadFromUri('./libraries/assets/models'),
            faceapi.nets.faceExpressionNet.loadFromUri('./libraries/assets/models') // Added for expression detection
        ]).then(startVideo);

        function startVideo() {
            // Request higher resolution video stream
            navigator.mediaDevices.getUserMedia({
                video: {
                    width: { ideal: 1920 },
                    height: { ideal: 1080 },
                    facingMode: "user"
                }
            })
                .then(stream => {
                    video.srcObject = stream;
                })
                .catch(err => console.error('Error accessing camera:', err));

            video.addEventListener('play', () => {
                canvas = faceapi.createCanvasFromMedia(video);
                document.getElementById('overlays').append(canvas);

                displaySize = { width: video.clientWidth, height: video.clientHeight };
                faceapi.matchDimensions(canvas, displaySize);

                detectFace();
            });
        }

        function drawIris(ctx, center, radius, color) {
            // Enhanced iris drawing with more realistic details
            // Outer iris gradient
            const gradient = ctx.createRadialGradient(
                center.x, center.y, radius * 0.2,
                center.x, center.y, radius
            );
            gradient.addColorStop(0, color);
            gradient.addColorStop(1, `${color}99`); // Semi-transparent at edges

            ctx.beginPath();
            ctx.arc(center.x, center.y, radius, 0, 2 * Math.PI);
            ctx.fillStyle = gradient;
            ctx.fill();

            // Pupil with dynamic size based on face distance
            const pupilRadius = radius * 0.45;
            ctx.beginPath();
            ctx.arc(center.x, center.y, pupilRadius, 0, 2 * Math.PI);
            ctx.fillStyle = '#000000';
            ctx.fill();

            // Multiple highlights for more realism
            const highlights = [
                { x: -0.2, y: -0.2, size: 0.15, opacity: 0.8 },
                { x: 0.2, y: 0.2, size: 0.1, opacity: 0.6 }
            ];

            highlights.forEach(h => {
                ctx.beginPath();
                ctx.arc(
                    center.x + radius * h.x,
                    center.y + radius * h.y,
                    radius * h.size,
                    0,
                    2 * Math.PI
                );
                ctx.fillStyle = `rgba(255, 255, 255, ${h.opacity})`;
                ctx.fill();
            });
        }

        function getEyeCenter(eyePoints) {
            // Weighted center calculation focusing on inner eye points
            const innerPoints = eyePoints.slice(2, 4); // Inner eye corners
            const allPoints = eyePoints;

            const centerX = (
                innerPoints.reduce((sum, point) => sum + point.x, 0) * 0.6 +
                allPoints.reduce((sum, point) => sum + point.x, 0) * 0.4
            ) / (innerPoints.length * 0.6 + allPoints.length * 0.4);

            const centerY = (
                innerPoints.reduce((sum, point) => sum + point.y, 0) * 0.6 +
                allPoints.reduce((sum, point) => sum + point.y, 0) * 0.4
            ) / (innerPoints.length * 0.6 + allPoints.length * 0.4);

            return { x: centerX, y: centerY };
        }

        function getEyeRadius(eyePoints) {
            const center = getEyeCenter(eyePoints);
            // Calculate weighted average of distances
            const distances = eyePoints.map(point => {
                const dist = Math.sqrt(Math.pow(point.x - center.x, 2) + Math.pow(point.y - center.y, 2));
                // Give more weight to points on the eye's horizontal axis
                const weight = (point === eyePoints[0] || point === eyePoints[3]) ? 1.2 : 1;
                return dist * weight;
            });
            return (Math.max(...distances) * 0.4 + distances.reduce((a, b) => a + b) / distances.length * 0.6) * 0.5;
        }

        const lastDetectionTimes = new Map();

        async function detectFace() {
            const labeledFaceDescriptors = await loadLabeledImages();
            const faceMatcher = new faceapi.FaceMatcher(labeledFaceDescriptors, 0.4);
            const COOLDOWN_PERIOD = 10000; // 10 seconds in milliseconds

            function canProcessPerson(personLabel) {
                const currentTime = Date.now();
                const lastDetectionTime = lastDetectionTimes.get(personLabel);

                if (!lastDetectionTime || (currentTime - lastDetectionTime) >= COOLDOWN_PERIOD) {
                    lastDetectionTimes.set(personLabel, currentTime);
                    return true;
                }
                return false;
            }

            function detect() {
                requestAnimationFrame(async () => {
                    const options = new faceapi.TinyFaceDetectorOptions({
                        inputSize: 512,
                        scoreThreshold: 0.7
                    });

                    const detection = await faceapi.detectSingleFace(video, options)
                        .withFaceLandmarks()
                        .withFaceDescriptor()
                        .withFaceExpressions();

                    if (detection) {
                        const resizedDetection = faceapi.resizeResults(detection, displaySize);
                        const context = canvas.getContext('2d');
                        context.clearRect(0, 0, canvas.width, canvas.height);

                        // Draw face box with dynamic color based on confidence
                        const confidence = detection.detection.score;
                        const boxColor = `rgba(0, ${Math.floor(confidence * 255)}, 0, 0.5)`;
                        const drawBox = new faceapi.draw.DrawBox(resizedDetection.detection.box, {
                            label: `Confidence: ${(confidence * 100).toFixed(1)}%`,
                            lineWidth: 2,
                            boxColor
                        });
                        drawBox.draw(canvas);

                        // Enhanced landmarks drawing
                        const landmarks = resizedDetection.landmarks;
                        const drawLandmarks = new faceapi.draw.DrawFaceLandmarks(landmarks, {
                            lineWidth: 1,
                            color: '#FF0000',
                            pointSize: 1
                        });
                        drawLandmarks.draw(canvas);

                        // Enhanced iris drawing
                        const leftEyePoints = landmarks.getLeftEye();
                        const rightEyePoints = landmarks.getRightEye();

                        const leftEyeCenter = getEyeCenter(leftEyePoints);
                        const rightEyeCenter = getEyeCenter(rightEyePoints);

                        const leftEyeRadius = getEyeRadius(leftEyePoints);
                        const rightEyeRadius = getEyeRadius(rightEyePoints);

                        const irisColor = '#0066CC';
                        drawIris(context, leftEyeCenter, leftEyeRadius, irisColor);
                        drawIris(context, rightEyeCenter, rightEyeRadius, irisColor);

                        // Face recognition with confidence display
                        if (resizedDetection.descriptor) {
                            const bestMatch = faceMatcher.findBestMatch(resizedDetection.descriptor);
                            const id = faceIdMap.get(bestMatch.label);

                            if (bestMatch.label !== 'unknown' && id && canProcessPerson(id)) {
                                await sendFaceDetectionData(bestMatch);
                            }


                            // Calculate remaining cooldown time
                            const lastDetection = lastDetectionTimes.get(id);
                            const remainingCooldown = lastDetection ?
                                Math.max(0, COOLDOWN_PERIOD - (Date.now() - lastDetection)) : 0;

                            // Display match text with cooldown information if applicable
                            const matchText = `${bestMatch.label} (${(1 - bestMatch.distance).toFixed(2)}% match)`;

                            const drawText = new faceapi.draw.DrawTextField([matchText],
                                resizedDetection.detection.box.bottomLeft, {
                                fontColor: 'pink',
                                backgroundColor: `rgba(0, 0, 0, ${confidence})`
                            });
                            drawText.draw(canvas);

                            // Display dominant expression
                            if (detection.expressions) {
                                const expressions = detection.expressions;
                                const dominantExpression = Object.keys(expressions).reduce((a, b) =>
                                    expressions[a] > expressions[b] ? a : b
                                );
                                const expressionText = `Expression: ${dominantExpression} (${(expressions[dominantExpression] * 100).toFixed(1)}%)`;
                                const drawExpression = new faceapi.draw.DrawTextField([expressionText],
                                    {
                                        x: resizedDetection.detection.box.bottomLeft.x,
                                        y: resizedDetection.detection.box.bottomLeft.y + 20
                                    }, {
                                    fontColor: 'white',
                                    backgroundColor: 'rgba(0, 0, 0, 0.5)'
                                });
                                drawExpression.draw(canvas);
                            }
                        }
                    } else {
                        const context = canvas.getContext('2d');
                        context.clearRect(0, 0, canvas.width, canvas.height);
                    }

                    detect();
                });
            }

            detect();
        }

        async function loadLabeledImages() {
            try {
                // Load multiple images per person for better recognition
                return Promise.all(
                    labelsData.map(async label => {
                        console.log(`Loading images for ${label}`);
                        const descriptions = [];
                        const imageCount = 5; // Try to load multiple images per person

                        for (let i = 1; i <= imageCount; i++) {
                            try {
                                const img = await faceapi.fetchImage(`./facephoto/${label}/${i}.jpg`);

                                const detection = await faceapi
                                    .detectSingleFace(img, new faceapi.TinyFaceDetectorOptions())
                                    .withFaceLandmarks()
                                    .withFaceDescriptor();

                                if (detection) {
                                    descriptions.push(detection.descriptor);
                                    console.log(`Successfully processed face ${i} for ${label}`);
                                } else {
                                    console.warn(`No face detected in image ${i} for ${label}`);
                                }
                            } catch (error) {
                                console.warn(`Error processing image ${i} for ${label}:`, error);
                                continue;
                            }
                        }

                        return new faceapi.LabeledFaceDescriptors(label, descriptions);
                    })
                );
            } catch (error) {
                console.error('Error in loadLabeledImages:', error);
                return [];
            }
        }

        async function sendFaceDetectionData(detectionData) {
            const id = faceIdMap.get(detectionData.label);
            try {
                const response = await fetch('admin/ajax/checkFace.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json'
                    },
                    body: JSON.stringify({
                        id: id
                    })
                });

                if (response.ok) {
                    window.location.href = "home.php";
                }

            } catch (error) {
                console.error('Error sending face detection data:', error);
            }
        }

    </script>
</body>

<style>
    .detection-container {
        position: relative;
        width: 100%;
        max-width: 1000px;
        margin: 0 auto;

    }

    video {
        width: 100%;
        height: auto;
        display: block;
    }

    #overlays {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        pointer-events: none;
    }

    canvas {
        position: absolute;
        top: 0;
        left: 0;
        width: 100% !important;
        height: 100% !important;
    }
</style>

</html>