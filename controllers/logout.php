<?php
// core configuration
include_once "../config/core.php";

// destroy session, it will remove ALL session settings
session_unset();
session_destroy();

//redirect to login page
header("Location: {$home_url}/controllers/login.php");
?>