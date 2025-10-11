<?php
include '../connection.php';
$acad = $_SESSION['acad'];

echo "<h2>Test Add Course</h2>";
echo "<strong>Current Academic Year ID:</strong> " . $acad . "<br><br>";

// Test adding a course
$test_course_code = "TEST";
$test_course_name = "Test Course";

// Check if test course already exists
$check_sql = "SELECT * FROM courses WHERE course_code='$test_course_code' AND acad_id='$acad'";
$check_result = $conn->query($check_sql);

if ($check_result->num_rows > 0) {
    echo "Test course already exists. Deleting it first...<br>";
    $delete_sql = "DELETE FROM courses WHERE course_code='$test_course_code' AND acad_id='$acad'";
    if ($conn->query($delete_sql)) {
        echo "✅ Test course deleted successfully<br>";
    } else {
        echo "❌ Error deleting test course: " . $conn->error . "<br>";
    }
}

// Add test course
$insert_sql = "INSERT INTO courses (course_code, course_name, acad_id) VALUES ('$test_course_code', '$test_course_name', '$acad')";

if ($conn->query($insert_sql)) {
    echo "✅ Test course added successfully<br>";
    
    // Verify it was added
    $verify_sql = "SELECT * FROM courses WHERE course_code='$test_course_code' AND acad_id='$acad'";
    $verify_result = $conn->query($verify_sql);
    
    if ($verify_result->num_rows > 0) {
        $verify_row = $verify_result->fetch_assoc();
        echo "✅ Course verified in database:<br>";
        echo "ID: " . $verify_row['course_id'] . "<br>";
        echo "Code: " . $verify_row['course_code'] . "<br>";
        echo "Name: " . $verify_row['course_name'] . "<br>";
        echo "Academic Year: " . $verify_row['acad_id'] . "<br>";
        echo "Status: " . $verify_row['status'] . "<br>";
        echo "Date Created: " . $verify_row['date_created'] . "<br>";
    } else {
        echo "❌ Course not found after insertion<br>";
    }
} else {
    echo "❌ Error adding test course: " . $conn->error . "<br>";
}

echo "<br><a href='debug_courses.php'>Check Debug Info</a><br>";
echo "<a href='courses.php'>Go to Course Management</a>";
?>


