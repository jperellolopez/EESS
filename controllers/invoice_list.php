<?php

/**
 * @var string $home_url
 */

// core configuration
include_once "../config/core.php";

// set page title
$page_title = "Consulta de facturas";

// include login checker
$require_login = true;
include_once "../controllers/login_check.php";

// include classes
include_once '../config/database.php';
include_once '../models/invoice.php';
include_once "../libs/php/utils.php";

$database = new Database();
$db = $database->getConnection();
$invoice = new Invoice($db);
$invoice->user_id = $_SESSION['user_id'];
$total_rows = 0;
$err = false;
$generateth = false;
$tableContent = false;
$datos = array();
$fechaInicio = date('d-m-Y');
$fechaFin = date('d-m-Y');

$listaFacturas = $invoice->countUserInvoices($from_record_num, $records_per_page);
$total_rows = $invoice->countAllInvoices();

if ($listaFacturas->rowCount() > 0) {
    $generateth = true;

    while ($data = $listaFacturas->fetch(PDO::FETCH_ASSOC)) {
        $tableContent = true;
        array_push($datos, $data);
    }

    if (isset($_POST['borrar']) && $_POST['borrar']) {

        $invoice->invoice_id = $_POST['invoiceid2'];
        $invoice->deleteInvoice();
        header("Location: {$home_url}controllers/invoice_list.php");
    }

} else {
   $err = true;
}

require_once "../views/invoice_list.php";