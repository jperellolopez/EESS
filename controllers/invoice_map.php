<?php
// core configuration
include_once "../config/core.php";

// set page title
$page_title="Creación de facturas";

// include login checker
$require_login=true;
include_once "login_check.php";

// include page header HTML
include_once '../views/layout_head.php';
echo "<div class='col-md-12'>";

// to prevent undefined index notice
$action = isset($_GET['action']) ? $_GET['action'] : "";

// recibir datos del mapa


include_once "../views/invoice_map.php";
?>
