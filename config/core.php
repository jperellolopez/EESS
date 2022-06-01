<?php
// error reporting
error_reporting(E_ALL);

// inicia sesión
session_start();

// zona horaria
date_default_timezone_set('Europe/Madrid');

// home page url
$home_url="http://localhost/EESS/";

// pagina por defecto en el parámetro url
$page = isset($_GET['page']) ? $_GET['page'] : 1;

// número de facturas por página
$records_per_page = 5;

// calculo para la paginación
$from_record_num = ($records_per_page * $page) - $records_per_page;
?>