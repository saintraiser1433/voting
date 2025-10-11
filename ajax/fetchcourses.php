<?php
include '../connection.php';

if (isset($_POST['acad_id'])) {
    $acad_id = $_POST['acad_id'];
    
    // Fetch courses from database
    $sql = "SELECT * FROM courses WHERE acad_id='$acad_id' AND status=1 ORDER BY course_code ASC";
    $result = $conn->query($sql);
    
    $courses = array();
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $courses[] = array(
                'course_code' => $row['course_code'],
                'course_name' => $row['course_name']
            );
        }
    }
    
    echo json_encode($courses);
}
?>


