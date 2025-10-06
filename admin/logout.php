<?php
session_start();
session_destroy();
// include 'autobackupdatabase.php';
header("Location:index.php");
$_SESSION['response'] = "Successfully Logout";
$_SESSION['type'] = "success";
