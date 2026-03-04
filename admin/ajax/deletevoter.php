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
        $hasDeptArch = $conn->query("SHOW COLUMNS FROM archives LIKE 'department_id'");
        if ($hasDeptArch && $hasDeptArch->num_rows > 0) {
            // Archive with department_id if column exists
            $query = "INSERT INTO archives (v_id,stud_id,acad_id,department_id,fname,lname,mname,$gradeCol,strand,section,auth_code,date_issued,password)
                      SELECT v_id,stud_id,acad_id,department_id,fname,lname,mname,grade_level,strand,section,auth_code,date_issued,password
                      FROM voters WHERE v_id='$id'";
        } else {
            $query = "INSERT INTO archives (v_id,stud_id,acad_id,fname,lname,mname,$gradeCol,strand,section,auth_code,date_issued,password)
                      SELECT v_id,stud_id,acad_id,fname,lname,mname,grade_level,strand,section,auth_code,date_issued,password
                      FROM voters WHERE v_id='$id'";
        }
        $conn->query($query);
    }

    // Archive general votes only if archivesvote table exists
    $tblVotes = $conn->query("SHOW TABLES LIKE 'archivesvote'");
    if ($tblVotes && $tblVotes->num_rows > 0) {
        $query2 = "INSERT INTO archivesvote (voter_id,candidate_id,acad_id)
                   SELECT voter_id,candidate_id,acad_id FROM vote WHERE voter_id='$id'";
        $conn->query($query2);
    }

    // Archive department votes if archive table exists
    $tblDeptVotes = $conn->query("SHOW TABLES LIKE 'archives_department_vote'");
    if ($tblDeptVotes && $tblDeptVotes->num_rows > 0) {
        $query3 = "INSERT INTO archives_department_vote (voter_id,candidate_id,acad_id,department_id)
                   SELECT voter_id,candidate_id,acad_id,department_id FROM department_vote WHERE voter_id='$id'";
        $conn->query($query3);
    }

    // Remove voter and all live votes (general + department) so tallies drop
    $sql = "DELETE FROM voters WHERE v_id='$id'";
    $conn->query($sql);
    $sql2 = "DELETE FROM vote WHERE voter_id='$id'";
    $conn->query($sql2);
    $sql3 = "DELETE FROM department_vote WHERE voter_id='$id'";
    $conn->query($sql3);
}
