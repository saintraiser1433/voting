<?php
include '../../connection.php';

if (isset($_POST['myids'])) {
    $id = (int) $_POST['myids'];

    // Archive voter + votes so department deletion behaves like general deletion
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
            $query = "INSERT INTO archives (v_id,stud_id,acad_id,department_id,fname,lname,mname,$gradeCol,strand,section,auth_code,date_issued,password)
                      SELECT v_id,stud_id,acad_id,department_id,fname,lname,mname,grade_level,strand,section,auth_code,date_issued,password
                      FROM voters
                      WHERE v_id='$id'";
        } else {
            $query = "INSERT INTO archives (v_id,stud_id,acad_id,fname,lname,mname,$gradeCol,strand,section,auth_code,date_issued,password)
                      SELECT v_id,stud_id,acad_id,fname,lname,mname,grade_level,strand,section,auth_code,date_issued,password
                      FROM voters
                      WHERE v_id='$id'";
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

    // Archive department votes only if archives_department_vote table exists
    $tblDeptVotes = $conn->query("SHOW TABLES LIKE 'archives_department_vote'");
    if ($tblDeptVotes && $tblDeptVotes->num_rows > 0) {
        $query3 = "INSERT INTO archives_department_vote (voter_id,candidate_id,acad_id,department_id)
                   SELECT voter_id,candidate_id,acad_id,department_id FROM department_vote WHERE voter_id='$id'";
        $conn->query($query3);
    }

    // Finally remove from live tables so tallies drop in both modes
    $conn->query("DELETE FROM department_vote WHERE voter_id='$id'");
    $conn->query("DELETE FROM vote WHERE voter_id='$id'");
    $conn->query("DELETE FROM voters WHERE v_id='$id'");
}
?>
