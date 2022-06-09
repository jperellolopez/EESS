<?php

/**
 * @var string $home_url
 * @var $from_record_num
 * @var $records_per_page
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
$generateTableHeader = false;
$tableContent = false;
$datos = array();
$curDate = date('Y-m-d');
$startDate = date('d-m-Y');
$endDate = date('d-m-Y');
$dateSearch = false;
$nonCompatibleDates = false;
$errDates = false;

if (!isset($_POST['submitInvoiceDate'])) {

$listaFacturas = $invoice->getUserInvoices($from_record_num, $records_per_page);
$total_rows = $invoice->countAllInvoices();

if ($listaFacturas->rowCount() > 0) {
    $generateTableHeader = true;

    while ($data = $listaFacturas->fetch(PDO::FETCH_ASSOC)) {
        $tableContent = true;
        array_push($datos, $data);
    }

    if (isset($_POST['borrar']) && $_POST['borrar']) {

        $invoice->invoice_id = $_POST['invoiceid2'];
        $invoice->deleteInvoice();

        $filename = null;
        $path = "../invoices/";
        $rutas = scandir($path);
        $pattern = "-".$_POST['invoiceid2']."A";

        foreach ($rutas as $filenames) {
            if(preg_match('/'.$pattern.'/', $filenames)) {
                $filename = $filenames;
            }
        }
        $file = $path.$filename;
        unlink($file);
        header("Location: {$home_url}controllers/invoice_list.php");
    }

} else {
   $err = true;
}

} else if (isset($_POST['submitInvoiceDate']) && isset($_POST['fechaInicio']) && isset($_POST['fechaFin'])) {

    $startDate = $_POST['fechaInicio'];
    $endDate = $_POST['fechaFin'];

    if ($startDate < $endDate) {

    $facturasPorFecha = $invoice->searchInvoicesByDate($startDate, $endDate, $from_record_num, $records_per_page);
    $total_rows = $invoice->countAllInvoicesByDate($startDate, $endDate);

    if ($total_rows > 0) {
        $datos = array();
        $dateSearch = true;
        $generateTableHeader = true;

        while ($data = $facturasPorFecha->fetch(PDO::FETCH_ASSOC)) {
            $tableContent = true;
            array_push($datos, $data);
        }

    } else {
        $errDates = true;
    }
} else {
        $nonCompatibleDates= true;
    }
}
    require_once "../views/invoice_list.php";