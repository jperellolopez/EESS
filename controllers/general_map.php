<?php

include_once "../config/core.php";
$page_title="Mapa de gasolineras";

$require_login=false;
include_once "login_check.php";

// la lógica de la clase se realiza en el fichero js index_map
include_once "../views/general_map.php";
?>
