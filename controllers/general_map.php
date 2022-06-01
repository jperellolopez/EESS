<?php
// core configuration
include_once "../config/core.php";

// set page title
$page_title="Mapa de gasolineras";

// include login checker
$require_login=false;
include_once "login_check.php";

include_once "../views/general_map.php";
?>
