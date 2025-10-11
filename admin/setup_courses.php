<?php
include '../connection.php';

// Create courses table
$create_table_sql = "
CREATE TABLE IF NOT EXISTS `courses` (
  `course_id` int NOT NULL AUTO_INCREMENT,
  `course_code` varchar(20) NOT NULL,
  `course_name` varchar(100) NOT NULL,
  `acad_id` int NOT NULL,
  `status` int NOT NULL DEFAULT '1' COMMENT '1-active, 0-inactive',
  `date_created` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`course_id`)
) ENGINE=MyISAM DEFAULT CHARSET=latin1;
";

if ($conn->query($create_table_sql)) {
    echo "Courses table created successfully.<br>";
} else {
    echo "Error creating courses table: " . $conn->error . "<br>";
}

// Get current academic year
$acad_query = "SELECT * from acad_tbl where status = 1";
$acad_result = $conn->query($acad_query);
$acad_row = $acad_result->fetch_assoc();
$current_acad = $acad_row['acad_id'];

// Insert default courses
$default_courses = array(
    array('BSIT', 'Bachelor of Science in Information Technology'),
    array('BSCS', 'Bachelor of Science in Computer Science'),
    array('BSIS', 'Bachelor of Science in Information Systems'),
    array('BSCE', 'Bachelor of Science in Computer Engineering'),
    array('BSEE', 'Bachelor of Science in Electrical Engineering'),
    array('BSME', 'Bachelor of Science in Mechanical Engineering'),
    array('BSA', 'Bachelor of Science in Accountancy'),
    array('BSBA', 'Bachelor of Science in Business Administration'),
    array('BSE', 'Bachelor of Science in Education'),
    array('BSED', 'Bachelor of Science in Elementary Education')
);

foreach ($default_courses as $course) {
    $course_code = $course[0];
    $course_name = $course[1];
    
    // Check if course already exists
    $check_sql = "SELECT * FROM courses WHERE course_code='$course_code' AND acad_id='$current_acad'";
    $check_result = $conn->query($check_sql);
    
    if ($check_result->num_rows == 0) {
        $insert_sql = "INSERT INTO courses (course_code, course_name, acad_id) VALUES ('$course_code', '$course_name', '$current_acad')";
        if ($conn->query($insert_sql)) {
            echo "Course $course_code added successfully.<br>";
        } else {
            echo "Error adding course $course_code: " . $conn->error . "<br>";
        }
    } else {
        echo "Course $course_code already exists.<br>";
    }
}

echo "<br><strong>Setup completed!</strong><br>";
echo "<a href='courses.php'>Go to Course Management</a>";
?>


