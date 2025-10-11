<?php
echo "<h2>Update All 'Grade' References to 'Year'</h2>";

// List of main PHP files to update
$files_to_update = [
    'admin/dashboard.php',
    'admin/myvote.php', 
    'admin/candidates.php',
    'admin/partylist.php',
    'admin/archives.php',
    'admin/independent.php',
    'admin/ajax/fetchstrand.php',
    'admin/ajax/searchstudent.php',
    'admin/ajax/retrieve.php',
    'admin/ajax/deletevoter.php'
];

$replacements = [
    // Variable names
    'gradelevel' => 'yearlevel',
    '$gradelevel' => '$yearlevel',
    'grade_level' => 'year_level',
    '$grade_level' => '$year_level',
    
    // Form field names
    'name="gradelevel"' => 'name="yearlevel"',
    'id="gradelevel"' => 'id="yearlevel"',
    'name=\'gradelevel\'' => 'name=\'yearlevel\'',
    'id=\'gradelevel\'' => 'id=\'yearlevel\'',
    
    // JavaScript selectors
    '#gradelevel' => '#yearlevel',
    '$(\'#gradelevel\')' => '$(\'#yearlevel\')',
    '$("#gradelevel")' => '$("#yearlevel")',
    
    // Labels and text
    'Grade Level' => 'Year Level',
    'GRADE LEVEL' => 'YEAR LEVEL',
    'grade level' => 'year level',
    
    // Tab labels
    'Grade ' => 'Year ',
    'Grade' => 'Year',
    
    // Comments
    '// Grade' => '// Year',
    '/* Grade' => '/* Year',
];

$total_files = 0;
$total_replacements = 0;

foreach ($files_to_update as $file) {
    if (file_exists($file)) {
        $content = file_get_contents($file);
        $original_content = $content;
        $file_replacements = 0;
        
        foreach ($replacements as $search => $replace) {
            $count = 0;
            $content = str_replace($search, $replace, $content, $count);
            $file_replacements += $count;
        }
        
        if ($content !== $original_content) {
            file_put_contents($file, $content);
            echo "✅ Updated $file ($file_replacements replacements)<br>";
            $total_replacements += $file_replacements;
        } else {
            echo "⚪ No changes needed in $file<br>";
        }
        $total_files++;
    } else {
        echo "❌ File not found: $file<br>";
    }
}

echo "<br><strong>Summary:</strong><br>";
echo "Files processed: $total_files<br>";
echo "Total replacements: $total_replacements<br>";

echo "<br><strong>✅ Update completed!</strong><br>";
echo "<a href='voters.php'>View Voters</a><br>";
echo "<a href='dashboard.php'>View Dashboard</a>";
?>
