<?php

session_start();
$conn = new mysqli("localhost", "root", "", "votes");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$conn->set_charset('utf8mb4');

if (!function_exists('middle_initial')) {
    function middle_initial($mname, $suffix = '.') {
        $mname = trim((string) $mname);
        return ($mname !== '') ? substr($mname, 0, 1) . $suffix : '';
    }
}

$query = "SELECT * from acad_tbl where status = 1";
$result = $conn->query($query);
$row = ($result && $result->num_rows > 0) ? $result->fetch_assoc() : null;
if (!isset($_SESSION['acad']) && $row) {
	$_SESSION['acad'] = $row['acad_id'];
}
if (!isset($_SESSION['voting_mode'])) {
	$_SESSION['voting_mode'] = 'general';
}
if (!isset($_SESSION['admin_mode'])) {
	$_SESSION['admin_mode'] = 'general';
}
