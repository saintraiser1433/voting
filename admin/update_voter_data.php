<?php
include '../connection.php';

echo "<h2>Update Voter Data - Grade Levels to Year Levels</h2>";

// First, let's make sure we have courses in the database
$acad = $_SESSION['acad'];
echo "<strong>Current Academic Year ID:</strong> $acad<br><br>";

// Check if courses table exists and has data
$courses_check = "SELECT COUNT(*) as count FROM courses WHERE acad_id='$acad' AND status=1";
$courses_result = $conn->query($courses_check);
$courses_count = $courses_result->fetch_assoc()['count'];

if ($courses_count == 0) {
    echo "<strong>❌ No courses found in database. Please run setup_courses.php first.</strong><br>";
    echo "<a href='setup_courses.php'>Run Course Setup</a><br><br>";
    exit;
}

echo "<strong>✅ Found $courses_count courses in database</strong><br><br>";

// Get all courses for random assignment
$courses_sql = "SELECT course_code FROM courses WHERE acad_id='$acad' AND status=1";
$courses_result = $conn->query($courses_sql);
$courses = array();
while ($row = $courses_result->fetch_assoc()) {
    $courses[] = $row['course_code'];
}

echo "<strong>Available courses:</strong> " . implode(', ', $courses) . "<br><br>";

// Function to convert grade level to year level
function convertGradeToYear($grade_level) {
    switch($grade_level) {
        case 7: return 1;  // Grade 7 → 1st Year
        case 8: return 2;  // Grade 8 → 2nd Year
        case 9: return 3;  // Grade 9 → 3rd Year
        case 10: return 4; // Grade 10 → 4th Year
        case 11: return 3; // Grade 11 → 3rd Year
        case 12: return 4; // Grade 12 → 4th Year
        default: return $grade_level; // Keep as is if already converted
    }
}

// Function to get random course
function getRandomCourse($courses) {
    return $courses[array_rand($courses)];
}

// Get all voters that need updating
$voters_sql = "SELECT v_id, stud_id, fname, lname, grade_level, strand FROM voters WHERE grade_level BETWEEN 7 AND 12";
$voters_result = $conn->query($voters_sql);

if ($voters_result->num_rows == 0) {
    echo "<strong>✅ No voters found with old grade levels (7-12)</strong><br>";
    echo "All voters already have year levels (1-4).<br><br>";
} else {
    echo "<strong>Found " . $voters_result->num_rows . " voters to update</strong><br><br>";
    
    $updated_count = 0;
    $error_count = 0;
    
    echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
    echo "<tr><th>Student ID</th><th>Name</th><th>Old Grade</th><th>New Year</th><th>Old Strand</th><th>New Course</th><th>Status</th></tr>";
    
    while ($voter = $voters_result->fetch_assoc()) {
        $v_id = $voter['v_id'];
        $stud_id = $voter['stud_id'];
        $fname = $voter['fname'];
        $lname = $voter['lname'];
        $old_grade = $voter['grade_level'];
        $old_strand = $voter['strand'];
        
        // Convert grade to year
        $new_year = convertGradeToYear($old_grade);
        
        // Determine new course
        $new_course = '';
        if ($new_year == 3 || $new_year == 4) {
            // 3rd and 4th year students get random courses
            $new_course = getRandomCourse($courses);
        } else {
            // 1st and 2nd year students don't have courses
            $new_course = '';
        }
        
        // Update the voter record
        $update_sql = "UPDATE voters SET grade_level='$new_year', strand='$new_course' WHERE v_id='$v_id'";
        
        if ($conn->query($update_sql)) {
            $updated_count++;
            $status = "✅ Updated";
        } else {
            $error_count++;
            $status = "❌ Error: " . $conn->error;
        }
        
        echo "<tr>";
        echo "<td>$stud_id</td>";
        echo "<td>$fname $lname</td>";
        echo "<td>Grade $old_grade</td>";
        echo "<td>$new_year" . ($new_year == 1 ? 'st' : ($new_year == 2 ? 'nd' : ($new_year == 3 ? 'rd' : 'th'))) . " Year</td>";
        echo "<td>$old_strand</td>";
        echo "<td>$new_course</td>";
        echo "<td>$status</td>";
        echo "</tr>";
    }
    
    echo "</table><br>";
    
    echo "<strong>Update Summary:</strong><br>";
    echo "✅ Successfully updated: $updated_count voters<br>";
    if ($error_count > 0) {
        echo "❌ Errors: $error_count voters<br>";
    }
}

// Also update archives table if it exists
$archives_check = "SHOW TABLES LIKE 'archives'";
$archives_result = $conn->query($archives_check);

if ($archives_result->num_rows > 0) {
    echo "<br><strong>Updating Archives Table...</strong><br>";
    
    $archives_sql = "SELECT id, stud_id, fname, lname, grade_level, strand FROM archives WHERE grade_level BETWEEN 7 AND 12";
    $archives_result = $conn->query($archives_sql);
    
    if ($archives_result->num_rows > 0) {
        $archives_updated = 0;
        
        while ($archive = $archives_result->fetch_assoc()) {
            $id = $archive['id'];
            $old_grade = $archive['grade_level'];
            $new_year = convertGradeToYear($old_grade);
            
            $new_course = '';
            if ($new_year == 3 || $new_year == 4) {
                $new_course = getRandomCourse($courses);
            }
            
            $update_archive_sql = "UPDATE archives SET grade_level='$new_year', strand='$new_course' WHERE id='$id'";
            
            if ($conn->query($update_archive_sql)) {
                $archives_updated++;
            }
        }
        
        echo "✅ Updated $archives_updated archive records<br>";
    } else {
        echo "✅ No archive records to update<br>";
    }
}

echo "<br><strong>🎉 Update completed!</strong><br>";
echo "<a href='voters.php'>View Updated Voters</a><br>";
echo "<a href='courses.php'>Manage Courses</a>";
?>

