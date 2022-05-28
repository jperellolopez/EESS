<?php
// core configuration
include_once "../config/core.php";

// set page title
$page_title="Mapa de gasolineras";

// include login checker
$require_login=false;
include_once "login_check.php";

// include page header HTML
include_once '../views/templates/layout_head.php';

//echo "<div class='col-lg-12'>";

// to prevent undefined index notice
$action = isset($_GET['action']) ? $_GET['action'] : "";

include_once "../views/general_map.php";
?>
