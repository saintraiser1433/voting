-- Update Voter Data: Convert Grade Levels to Year Levels
-- Run this script after setting up the courses table

-- First, make sure you have courses in the database
-- If not, run the add_courses_table.sql first

-- Update voters table
-- Grade 7 → 1st Year (1)
UPDATE voters SET grade_level = 1 WHERE grade_level = 7;

-- Grade 8 → 2nd Year (2)  
UPDATE voters SET grade_level = 2 WHERE grade_level = 8;

-- Grade 9 → 3rd Year (3)
UPDATE voters SET grade_level = 3 WHERE grade_level = 9;

-- Grade 10 → 4th Year (4)
UPDATE voters SET grade_level = 4 WHERE grade_level = 10;

-- Grade 11 → 3rd Year (3)
UPDATE voters SET grade_level = 3 WHERE grade_level = 11;

-- Grade 12 → 4th Year (4)
UPDATE voters SET grade_level = 4 WHERE grade_level = 12;

-- Assign random courses to 3rd and 4th year students
-- Note: You'll need to manually assign courses or use the PHP script for random assignment
-- Example for 3rd year students:
-- UPDATE voters SET strand = 'BSIT' WHERE grade_level = 3 AND v_id IN (SELECT v_id FROM voters WHERE grade_level = 3 LIMIT 5);
-- UPDATE voters SET strand = 'BSCS' WHERE grade_level = 3 AND v_id IN (SELECT v_id FROM voters WHERE grade_level = 3 LIMIT 5);

-- Example for 4th year students:
-- UPDATE voters SET strand = 'BSIT' WHERE grade_level = 4 AND v_id IN (SELECT v_id FROM voters WHERE grade_level = 4 LIMIT 5);
-- UPDATE voters SET strand = 'BSCS' WHERE grade_level = 4 AND v_id IN (SELECT v_id FROM voters WHERE grade_level = 4 LIMIT 5);

-- Update archives table (if it exists)
UPDATE archives SET grade_level = 1 WHERE grade_level = 7;
UPDATE archives SET grade_level = 2 WHERE grade_level = 8;
UPDATE archives SET grade_level = 3 WHERE grade_level = 9;
UPDATE archives SET grade_level = 4 WHERE grade_level = 10;
UPDATE archives SET grade_level = 3 WHERE grade_level = 11;
UPDATE archives SET grade_level = 4 WHERE grade_level = 12;

-- Show results
SELECT 
    grade_level,
    COUNT(*) as count,
    CASE 
        WHEN grade_level = 1 THEN '1st Year'
        WHEN grade_level = 2 THEN '2nd Year'
        WHEN grade_level = 3 THEN '3rd Year'
        WHEN grade_level = 4 THEN '4th Year'
        ELSE 'Other'
    END as year_level
FROM voters 
GROUP BY grade_level 
ORDER BY grade_level;

