<?php
include '../connection.php';
$acad = $_SESSION['acad'];

echo "<h2>Debug Information</h2>";
echo "<strong>Current Academic Year ID:</strong> " . $acad . "<br><br>";

// Check if courses table exists
$table_check = "SHOW TABLES LIKE 'courses'";
$table_result = $conn->query($table_check);

if ($table_result->num_rows > 0) {
    echo "<strong>✅ Courses table exists</strong><br><br>";
    
    // Check table structure
    echo "<strong>Table Structure:</strong><br>";
    $structure = "DESCRIBE courses";
    $struct_result = $conn->query($structure);
    echo "<table border='1' cellpadding='5'>";
    echo "<tr><th>Field</th><th>Type</th><th>Null</th><th>Key</th><th>Default</th><th>Extra</th></tr>";
    while ($row = $struct_result->fetch_assoc()) {
        echo "<tr>";
        echo "<td>" . $row['Field'] . "</td>";
        echo "<td>" . $row['Type'] . "</td>";
        echo "<td>" . $row['Null'] . "</td>";
        echo "<td>" . $row['Key'] . "</td>";
        echo "<td>" . $row['Default'] . "</td>";
        echo "<td>" . $row['Extra'] . "</td>";
        echo "</tr>";
    }
    echo "</table><br>";
    
    // Check if there are any courses
    $count_sql = "SELECT COUNT(*) as total FROM courses";
    $count_result = $conn->query($count_sql);
    $count_row = $count_result->fetch_assoc();
    echo "<strong>Total courses in database:</strong> " . $count_row['total'] . "<br><br>";
    
    // Check courses for current academic year
    $acad_sql = "SELECT COUNT(*) as total FROM courses WHERE acad_id='$acad'";
    $acad_result = $conn->query($acad_sql);
    $acad_row = $acad_result->fetch_assoc();
    echo "<strong>Courses for current academic year ($acad):</strong> " . $acad_row['total'] . "<br><br>";
    
    // Show all courses
    $all_sql = "SELECT * FROM courses ORDER BY acad_id, course_code";
    $all_result = $conn->query($all_sql);
    
    if ($all_result->num_rows > 0) {
        echo "<strong>All courses in database:</strong><br>";
        echo "<table border='1' cellpadding='5'>";
        echo "<tr><th>ID</th><th>Course Code</th><th>Course Name</th><th>Academic Year</th><th>Status</th><th>Date Created</th></tr>";
        while ($row = $all_result->fetch_assoc()) {
            echo "<tr>";
            echo "<td>" . $row['course_id'] . "</td>";
            echo "<td>" . $row['course_code'] . "</td>";
            echo "<td>" . $row['course_name'] . "</td>";
            echo "<td>" . $row['acad_id'] . "</td>";
            echo "<td>" . $row['status'] . "</td>";
            echo "<td>" . $row['date_created'] . "</td>";
            echo "</tr>";
        }
        echo "</table><br>";
    } else {
        echo "<strong>❌ No courses found in database</strong><br><br>";
    }
    
} else {
    echo "<strong>❌ Courses table does not exist</strong><br><br>";
    echo "You need to run the setup script first.<br>";
    echo "<a href='setup_courses.php'>Run Setup Script</a><br><br>";
}

// Show current academic year info
$acad_info = "SELECT * FROM acad_tbl WHERE acad_id='$acad'";
$acad_info_result = $conn->query($acad_info);
if ($acad_info_result->num_rows > 0) {
    $acad_info_row = $acad_info_result->fetch_assoc();
    echo "<strong>Current Academic Year Info:</strong><br>";
    echo "ID: " . $acad_info_row['acad_id'] . "<br>";
    echo "Description: " . $acad_info_row['description'] . "<br>";
    echo "Status: " . $acad_info_row['status'] . "<br><br>";
}

echo "<a href='courses.php'>Go to Course Management</a>";
?>


