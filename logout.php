<?php

session_destroy();
// include 'admin/autobackupdatabase.php';
header("Location:index.php");
$_SESSION['response'] = "Successfully Logout";
$_SESSION['type'] = "success";
