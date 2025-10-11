<?php
include '../../connection.php';

try {
    // Start transaction
    $conn->begin_transaction();

    // Get academic year
    $sqlt = "SELECT acad_id from acad_tbl where status = 1";
    $rs = $conn->query($sqlt);
    $row = $rs->fetch_assoc();
    $acad = $row['acad_id'];

    // Get form data
    $studid = $_POST['studid'];
    $fname = $_POST['fname'];
    $lname = $_POST['lname'];
    $mname = $_POST['mname'];
    $yearlevel = $_POST['yearlevel'];
    $strand = $_POST['strand'];
    $section = $_POST['section'];
    // Create folder name
    $fullname = $lname . "," . $fname . " " . $mname[0];

    // Define upload directory path (using string concatenation, not backticks)
    $upload_dir = "../../facephoto/" . $fullname . "/";

    // Generate auth code
    $mypass = "";
    $auth = substr(str_shuffle("0123456789abcdefghijklmnopqrstuvwxyz-:,"), 0, 8);
    $authcode = md5($auth);

    // Create directory if it doesn't exist
    if (!file_exists($upload_dir)) {
        if (!mkdir($upload_dir, 0777, true)) {
            throw new Exception("Failed to create directory: " . $upload_dir);
        }
    }

    // Process images first to ensure we have them before database insert
    $uploadSuccess = true;
    for ($i = 1; $i <= 5; $i++) {
        if (isset($_FILES["image$i"]) && $_FILES["image$i"]['error'] === UPLOAD_ERR_OK) {
            $file = $_FILES["image$i"];
            $filename = $i . '.jpg';
            $filepath = $upload_dir . $filename;

            if (!move_uploaded_file($file['tmp_name'], $filepath)) {
                $uploadSuccess = false;
                throw new Exception("Failed to move uploaded file $i");
            }
        } else {
            $uploadSuccess = false;
            throw new Exception("Image $i is missing or invalid");
        }
    }

    // Only proceed with database insert if all images were uploaded successfully
    if ($uploadSuccess) {
        // Insert into database with the correct path
        $stmt = $conn->prepare("INSERT INTO voters (stud_id, acad_id, fname, lname, mname, grade_level, strand, section, auth_code, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param(
            "sisssissss",
            $studid,
            $acad,
            $fname,
            $lname,
            $mname,
            $yearlevel,
            $strand,
            $section,
            $authcode,
            $mypass
        );

        if (!$stmt->execute()) {
            throw new Exception("Database error: " . $stmt->error);
        }
    }

    // If we got here, commit the transaction
    $conn->commit();
    echo json_encode([
        'success' => true,
        'message' => 'Data saved successfully',
    ]);

} catch (Exception $e) {
    // Rollback transaction on error
    $conn->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$conn->close();
?>