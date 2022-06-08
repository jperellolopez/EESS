<?php
// error reporting
error_reporting(E_ALL);

// inicia sesión
session_start();

// zona horaria
date_default_timezone_set('Europe/Madrid');

// home page url, cambiar según ruta de despliegue
$home_url="http://localhost/EESS/";

// pagina por defecto en el parámetro url
$page = isset($_GET['page']) ? $_GET['page'] : 1;

// número de facturas visibles por página en la sección "consultar facturas"
$records_per_page = 8;

// calculo para la paginación
$from_record_num = ($records_per_page * $page) - $records_per_page;
?>