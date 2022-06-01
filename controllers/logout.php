<?php
include_once "../config/core.php";

session_unset();
session_destroy();

header("Location: {$home_url}/controllers/login.php");
?>