<?php
include '../../connection.php';

if (isset($_POST['myids'])) {
    $id = $_POST['myids'];
    $query = "INSERT into archives (v_id,stud_id,acad_id,fname,lname,mname,grade_level,strand,section,auth_code,date_issued,password) SELECT v_id,stud_id,acad_id,fname,lname,mname,grade_level,strand,section,auth_code,date_issued,password from voters where v_id='$id'";
    $conn->query($query);
    $query2 = "INSERT into archivesvote (voter_id,candidate_id,acad_id) SELECT voter_id,candidate_id,acad_id from vote where voter_id='$id'";
    $conn->query($query2);
    $sql = "DELETE FROM voters where v_id='$id'";
    $conn->query($sql);
    $sql2 = "DELETE FROM vote where voter_id='$id'";
    $conn->query($sql2);
}
