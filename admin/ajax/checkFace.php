<?php

include '../../connection.php';
header('Content-Type: application/json');

// Get the POST data
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Function to verify face ID (you should implement your actual verification logic here)
function verifyFaceId($id, $conn)
{


    // Prevent SQL injection
    $id = mysqli_real_escape_string($conn, $id);

    // Example query - modify according to your table structure
    $query = "SELECT * FROM voters WHERE v_id = '$id'";
    $result = mysqli_query($conn, $query);

    $isValid = mysqli_num_rows($result) > 0;

    mysqli_close($conn);
    return $isValid;
}

try {
    // Validate input
    if (!isset($data['id'])) {
        throw new Exception('Face ID is required');
    }

    $id = $data['id'];

    if (verifyFaceId($id, $conn)) {
        $_SESSION['faceverified'] = true;
        unset($_SESSION['face']);
        // Send success response
        echo json_encode([
            'success' => true,
            'message' => 'Face verification successful'
        ]);
    } else {
        throw new Exception('Face verification failed');
    }

} catch (Exception $e) {
    http_response_code(400);
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
?>