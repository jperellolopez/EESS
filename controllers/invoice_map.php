<?php
include_once "../config/core.php";

$page_title="Creación de facturas";

$require_login=true;
include_once "login_check.php";

include_once '../config/database.php';
include_once '../models/gas_station.php';
include_once '../models/invoice.php';

// recibir datos del mapa por httprequest
if (isset($_POST) && !empty($_POST)) {

    $data = $_POST;

    $database = new Database();
    $db = $database->getConnection();

    $gas_station = new Gas_station($db);
    $invoice = new Invoice($db);

    $gas_station->gas_station_id=$data['IDEESS'];

    if (!$gas_station->checkGasStationExists()) {

        $gas_station->gas_station_id = $data['IDEESS'];
        $gas_station->address = $data['Dirección'];
        $gas_station->postal_code = $data['C_P_'];
        $gas_station->latitude = $data['Latitud'];
        $gas_station->longitude = $data['Longitud_(WGS84)'];
        $gas_station->brand = $data['Rótulo'];
        $gas_station->region_id = $data['IDCCAA'];
        $gas_station->province = $data['Provincia'];
        $gas_station->municipality = $data['Municipio'];
        $gas_station->opening_hours = $data['Horario'];

        $gas_station->insertNewGasStation();

    }

    $invoice->gas_station_id = $data['IDEESS'];
    $invoice->user_id = $_SESSION['user_id'];
    $tipoCombustible = $data['Combustible_Repostado'];
    $invoice->fuel_type = $tipoCombustible;
    $tipoCombustible = str_replace(' ', '_', $tipoCombustible);
    $precioCombustible = $data['Precio_'.$tipoCombustible];
    $precioCombustible = str_replace(',', '.', $precioCombustible);
    $invoice->fuel_price = $precioCombustible;
    $cantidadRepostada = $data['Cantidad_Repostada'];
    $cantidadRepostada = str_replace(',', '.', $cantidadRepostada);
    $invoice->money_spent = $cantidadRepostada;
    $invoice->refuel_date = $data['Fecha_Repostaje'];

    $invoice->insertNewInvoice();

}

include_once "../views/invoice_map.php";
?>
