<?php
include '../../connection.php';

if (isset($_POST['myids'])) {
    $id = $_POST['myids'];
    // Detect whether archives table uses grade_level or year_level
    $gradeCol = 'grade_level';
    $tblCheck = $conn->query("SHOW TABLES LIKE 'archives'");
    if ($tblCheck && $tblCheck->num_rows > 0) {
        $hasGrade = $conn->query("SHOW COLUMNS FROM archives LIKE 'grade_level'");
        $hasYear = $conn->query("SHOW COLUMNS FROM archives LIKE 'year_level'");
        if ($hasGrade && $hasGrade->num_rows === 0 && $hasYear && $hasYear->num_rows > 0) {
            $gradeCol = 'year_level';
        }

        // voters table uses grade_level; map from whichever column archives has
        // Also restore department_id if both tables have it, and set is_verified=1 when column exists
        $hasVerified = $conn->query("SHOW COLUMNS FROM voters LIKE 'is_verified'");
        $hasDeptVoters = $conn->query("SHOW COLUMNS FROM voters LIKE 'department_id'");
        $hasDeptArch = $conn->query("SHOW COLUMNS FROM archives LIKE 'department_id'");

        $withDept = ($hasDeptVoters && $hasDeptVoters->num_rows > 0 && $hasDeptArch && $hasDeptArch->num_rows > 0);

        if ($hasVerified && $hasVerified->num_rows > 0 && $withDept) {
            $query = "INSERT INTO voters (v_id,stud_id,acad_id,department_id,fname,lname,mname,grade_level,strand,section,auth_code,date_issued,password,is_verified)
                      SELECT v_id,stud_id,acad_id,department_id,fname,lname,mname,$gradeCol,strand,section,auth_code,date_issued,password,1
                      FROM archives WHERE v_id='$id'";
        } elseif ($hasVerified && $hasVerified->num_rows > 0) {
            $query = "INSERT INTO voters (v_id,stud_id,acad_id,fname,lname,mname,grade_level,strand,section,auth_code,date_issued,password,is_verified)
                      SELECT v_id,stud_id,acad_id,fname,lname,mname,$gradeCol,strand,section,auth_code,date_issued,password,1
                      FROM archives WHERE v_id='$id'";
        } elseif ($withDept) {
            $query = "INSERT INTO voters (v_id,stud_id,acad_id,department_id,fname,lname,mname,grade_level,strand,section,auth_code,date_issued,password)
                      SELECT v_id,stud_id,acad_id,department_id,fname,lname,mname,$gradeCol,strand,section,auth_code,date_issued,password
                      FROM archives WHERE v_id='$id'";
        } else {
            $query = "INSERT INTO voters (v_id,stud_id,acad_id,fname,lname,mname,grade_level,strand,section,auth_code,date_issued,password)
                      SELECT v_id,stud_id,acad_id,fname,lname,mname,$gradeCol,strand,section,auth_code,date_issued,password
                      FROM archives WHERE v_id='$id'";
        }
        $conn->query($query);

        $sql = "DELETE FROM archives WHERE v_id='$id'";
        $conn->query($sql);
    }

    // Restore general votes only if archivesvote table exists
    $tblVotes = $conn->query("SHOW TABLES LIKE 'archivesvote'");
    if ($tblVotes && $tblVotes->num_rows > 0) {
        $query2 = "INSERT INTO vote (voter_id,candidate_id,acad_id)
                   SELECT voter_id,candidate_id,acad_id FROM archivesvote WHERE voter_id='$id'";
        $conn->query($query2);

        $sql = "DELETE FROM archivesvote WHERE voter_id='$id'";
        $conn->query($sql);
    }

    // Restore department votes only if archives_department_vote table exists
    $tblDeptVotes = $conn->query("SHOW TABLES LIKE 'archives_department_vote'");
    if ($tblDeptVotes && $tblDeptVotes->num_rows > 0) {
        $query3 = "INSERT INTO department_vote (voter_id,candidate_id,acad_id,department_id)
                   SELECT voter_id,candidate_id,acad_id,department_id FROM archives_department_vote WHERE voter_id='$id'";
        $conn->query($query3);

        $sql = "DELETE FROM archives_department_vote WHERE voter_id='$id'";
        $conn->query($sql);
    }
}
