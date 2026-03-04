<?php

include '../../connection.php';
header('Content-Type: application/json');

// Get the POST data
$json = file_get_contents('php://input');
$data = json_decode($json, true);

// Function to verify face ID against voters or dept_voters based on session
function verifyFaceId($id, $conn)
{
    $id = (int) $id;
    $mode = isset($_SESSION['voting_mode']) && $_SESSION['voting_mode'] === 'department' ? 'department' : 'general';
    if ($mode === 'department') {
        $query = "SELECT 1 FROM voters WHERE v_id = '$id' AND department_id IS NOT NULL";
    } else {
        $query = "SELECT 1 FROM voters WHERE v_id = '$id'";
    }
    $result = mysqli_query($conn, $query);
    return $result && mysqli_num_rows($result) > 0;
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