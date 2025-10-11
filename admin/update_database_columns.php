<?php
include '../connection.php';

echo "<h2>Update Database Column Names</h2>";

// Check if we need to rename the grade_level column to year_level
$check_column = "SHOW COLUMNS FROM voters LIKE 'grade_level'";
$result = $conn->query($check_column);

if ($result->num_rows > 0) {
    echo "<strong>Found 'grade_level' column in voters table</strong><br>";
    
    // Check if year_level column already exists
    $check_year_column = "SHOW COLUMNS FROM voters LIKE 'year_level'";
    $year_result = $conn->query($check_year_column);
    
    if ($year_result->num_rows == 0) {
        echo "<strong>Renaming 'grade_level' to 'year_level'...</strong><br>";
        
        $rename_sql = "ALTER TABLE voters CHANGE grade_level year_level INT NOT NULL";
        if ($conn->query($rename_sql)) {
            echo "✅ Successfully renamed 'grade_level' to 'year_level' in voters table<br>";
        } else {
            echo "❌ Error renaming column: " . $conn->error . "<br>";
        }
    } else {
        echo "✅ 'year_level' column already exists<br>";
    }
} else {
    echo "✅ No 'grade_level' column found (already renamed or doesn't exist)<br>";
}

// Check archives table
$check_archives = "SHOW TABLES LIKE 'archives'";
$archives_result = $conn->query($check_archives);

if ($archives_result->num_rows > 0) {
    echo "<br><strong>Checking archives table...</strong><br>";
    
    $check_archives_column = "SHOW COLUMNS FROM archives LIKE 'grade_level'";
    $archives_column_result = $conn->query($check_archives_column);
    
    if ($archives_column_result->num_rows > 0) {
        echo "<strong>Found 'grade_level' column in archives table</strong><br>";
        
        $check_archives_year = "SHOW COLUMNS FROM archives LIKE 'year_level'";
        $archives_year_result = $conn->query($check_archives_year);
        
        if ($archives_year_result->num_rows == 0) {
            echo "<strong>Renaming 'grade_level' to 'year_level' in archives table...</strong><br>";
            
            $rename_archives_sql = "ALTER TABLE archives CHANGE grade_level year_level INT NOT NULL";
            if ($conn->query($rename_archives_sql)) {
                echo "✅ Successfully renamed 'grade_level' to 'year_level' in archives table<br>";
            } else {
                echo "❌ Error renaming archives column: " . $conn->error . "<br>";
            }
        } else {
            echo "✅ 'year_level' column already exists in archives table<br>";
        }
    } else {
        echo "✅ No 'grade_level' column found in archives table<br>";
    }
}

echo "<br><strong>Database column update completed!</strong><br>";
echo "<a href='update_grade_to_year.php'>Update PHP Files</a><br>";
echo "<a href='voters.php'>View Voters</a>";
?>
