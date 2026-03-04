<?php

session_start();
$conn = new mysqli("localhost", "root", "", "votes");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "SELECT * from acad_tbl where status = 1";
$result = $conn->query($query);
$row = $result->fetch_assoc();
if (!isset($_SESSION['acad'])) {
	$_SESSION['acad'] = $row['acad_id'];
}
if (!isset($_SESSION['voting_mode'])) {
	$_SESSION['voting_mode'] = 'general';
}
if (!isset($_SESSION['admin_mode'])) {
	$_SESSION['admin_mode'] = 'general';
}
