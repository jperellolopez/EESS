<?php

/**
 * @var string $home_url
 */


include_once "../config/core.php";
$page_title = "Consulta de facturas";
$require_login = true;
include_once "../controllers/login_check.php";

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
$curDate = date('Y-m-d');
$fechaInicio = date('d-m-Y');
$fechaFin = date('d-m-Y');
$datesearch = false;
$noncompatibledate = false;
$errFecha = false;

if (!isset($_POST['submitInvoiceDate'])) {

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

} else if (isset($_POST['submitInvoiceDate']) && isset($_POST['fechaInicio']) && isset($_POST['fechaFin'])) {

    $fechaInicio = $_POST['fechaInicio'];
    $fechaFin = $_POST['fechaFin'];

    if ($fechaInicio < $fechaFin) {

    $facturasPorFecha = $invoice->searchInvoicesByDate($fechaInicio, $fechaFin, $from_record_num, $records_per_page);
    $total_rows = $invoice->countAllInvoicesByDate($fechaInicio, $fechaFin);

    if ($total_rows > 0) {
        $datos = array();
        $datesearch = true;
        $generateth = true;

        while ($data = $facturasPorFecha->fetch(PDO::FETCH_ASSOC)) {
            $tableContent = true;
            array_push($datos, $data);
        }

    } else {
        $errFecha = true;
    }
} else {
        $noncompatibledate= true;
    }
}
    require_once "../views/invoice_list.php";