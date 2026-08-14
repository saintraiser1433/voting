<?php
include '../../connection.php';

// Always return JSON so the frontend can safely JSON.parse
header('Content-Type: application/json');

try {
    $conn->begin_transaction();

    // Get active academic year safely
    $sqlt = "SELECT acad_id FROM acad_tbl WHERE status = 1 LIMIT 1";
    $rs = $conn->query($sqlt);
    if (!$rs || $rs->num_rows === 0) {
        throw new Exception("No active academic year found.");
    }
    $row = $rs->fetch_assoc();
    $acad = (int) $row['acad_id'];

    // Get form data (defensive, avoid undefined index notices)
    $studid = isset($_POST['studid']) ? trim($_POST['studid']) : '';
    $fname = isset($_POST['fname']) ? trim($_POST['fname']) : '';
    $lname = isset($_POST['lname']) ? trim($_POST['lname']) : '';
    $mname = isset($_POST['mname']) ? trim($_POST['mname']) : '';
    $yearlevel = isset($_POST['yearlevel']) ? (int) $_POST['yearlevel'] : 0;
    $strand = isset($_POST['strand']) ? trim($_POST['strand']) : '';
    $section = isset($_POST['section']) ? trim($_POST['section']) : '';

    if ($studid === '' || $fname === '' || $lname === '' || $yearlevel === 0) {
        throw new Exception("Please fill in all required fields.");
    }
    
    // Check for duplicate registration (first name, last name, and middle name combination)
    // Remove spaces from names for comparison (e.g., "john rey" = "johnrey")
    // CONVERT avoids MySQL collation mismatch on PHP 8.2 / utf8mb4 connections
    $checkStmt = $conn->prepare(
        "SELECT COUNT(*) as count FROM voters
         WHERE REPLACE(UPPER(TRIM(CONVERT(fname USING utf8mb4))), ' ', '') = REPLACE(UPPER(TRIM(?)), ' ', '')
           AND REPLACE(UPPER(TRIM(CONVERT(lname USING utf8mb4))), ' ', '') = REPLACE(UPPER(TRIM(?)), ' ', '')
           AND REPLACE(UPPER(TRIM(CONVERT(mname USING utf8mb4))), ' ', '') = REPLACE(UPPER(TRIM(?)), ' ', '')"
    );
    $checkStmt->bind_param("sss", $fname, $lname, $mname);
    $checkStmt->execute();
    $checkResult = $checkStmt->get_result();
    $checkRow = $checkResult->fetch_assoc();
    $checkStmt->close();
    
    if ($checkRow['count'] > 0) {
        throw new Exception("Registration failed! A user with the same first name, last name, and middle name already exists.");
    }
    
    // Create folder name (avoid notice if mname empty)
    $m_initial = ($mname !== '') ? substr($mname, 0, 1) : '';
    $fullname = $lname . "," . $fname . " " . $m_initial;

    $upload_dir = "../../facephoto/" . $fullname . "/";

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
        $inserted = false;

        // Try full schema first (common in this app): include is_verified (default 0) and password (empty)
        $stmt = $conn->prepare("INSERT INTO voters (stud_id, acad_id, fname, lname, mname, grade_level, strand, section, is_verified, password) VALUES (?, ?, ?, ?, ?, ?, ?, ?, 0, '')");
        if ($stmt) {
            $stmt->bind_param(
                "sisssiss",
                $studid,
                $acad,
                $fname,
                $lname,
                $mname,
                $yearlevel,
                $strand,
                $section
            );
            if ($stmt->execute()) {
                $inserted = true;
            } else {
                // If it's not an unknown-column issue, surface the DB error
                if (strpos($stmt->error, 'Unknown column') === false) {
                    throw new Exception("Database error: " . $stmt->error);
                }
            }
        } elseif (strpos($conn->error, 'Unknown column') === false) {
            // Prepare failed for another reason
            throw new Exception("Prepare failed: " . $conn->error);
        }

        // Fallback: legacy schema without is_verified/password
        if (!$inserted) {
            $stmt2 = $conn->prepare("INSERT INTO voters (stud_id, acad_id, fname, lname, mname, grade_level, strand, section) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
            if (!$stmt2) {
                throw new Exception("Prepare failed (fallback): " . $conn->error);
            }
            $stmt2->bind_param(
                "sisssiss",
                $studid,
                $acad,
                $fname,
                $lname,
                $mname,
                $yearlevel,
                $strand,
                $section
            );
            if (!$stmt2->execute()) {
                throw new Exception("Database error (fallback): " . $stmt2->error);
            }
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