<?php
require_once "../config/core.php";
require_once '../fpdf/fpdf.php';
include_once "../config/database.php";
include_once '../models/invoice.php';
$require_login = true;
include_once "../controllers/login_check.php";

//session_start();
$database = new Database();
$db = $database->getConnection();
define('EURO', chr(128));

if (isset($_POST['enviar'])) {

    class myPDF extends FPDF {

        function getInvoiceData($db) {
            $invoiceData = new Invoice($db);
            $invoiceData->invoice_id = $_POST['invoiceid'];
            $stmt = $invoiceData->getPdfInvoiceData();

            if ($stmt->rowCount() > 0) {
                $invoiceInfo = array();

                while ($data = $stmt->fetch(PDO::FETCH_OBJ)) {
                    array_push($invoiceInfo, $data->fuel_type, $data->fuel_price, $data->money_spent, $data->refuel_date, $data->created, $data->gas_address, $data->postal_code, $data->brand, $data->province, $data->municipality, $data->region, $data->opening_hours, $data->firstname, $data->lastname, $data->address, $data->postal_code);

                }
                return $invoiceInfo;
            } else {
                return false;
            }
        }

        function header()
        {
            $this->SetFont("Arial", "B", 14);
            $this->Cell(0, 5, "FACTURA", 0, 0, "C");
            $this->Ln(20);
        }

        function userAndGasStationData($db)
        {
            $info = $this->getInvoiceData($db);
            $_SESSION['ruta'] = $info[12].$info[13]. "-" . $_POST['invoiceid'];
            $this->SetFont("Arial", "B", 12);
            $this->setY(30);
            $this->setX(15);
            $this->Cell(5, 10, "Usuario");
            $this->SetFont("Arial", "", 10);
            $this->setY(40);
            $this->setX(15);
            $this->Cell(5,5, utf8_decode($info[12]) . " " . utf8_decode($info[13]));
            $this->setY(45);
            $this->setX(15);
            $this->Cell(5,5, utf8_decode($info[14]));
            $this->setY(50);
            $this->setX(15);
            $this->Cell(5,5, $info[15]);

            $this->setY(30);
            $this->setX(90);
            $this->SetFont("Arial", "B", 12);
            $this->Cell(5, 10, utf8_decode("Estación de servicio"));
            $this->SetFont("Arial", "", 10);
            $this->setY(40);
            $this->setX(90);
            $this->Cell(5, 5, utf8_decode($info[7]));
            $this->setY(45);
            $this->setX(90);
            $this->Cell(5, 5, utf8_decode($info[5]));
            $this->setY(50);
            $this->setX(90);
            $this->Cell(5,5,utf8_decode($info[9]) . ", " . utf8_decode($info[6]));
            $this->setY(55);
            $this->setX(90);
            $this->Cell(5,5,utf8_decode($info[8]) . ", " . utf8_decode($info[10]));

            $this->setY(75);
            $this->setX(10);
            $this->cell(0, 5, "Factura creada en: " . $info[4], 0, 0 ,"R");

        }

        function headerTable() {
            $this->SetFont("Times", "B", 12);
            $this->Ln();
            $this->setX(15);
            $this->setY(85);
            $this->Cell(40, 10, "Fecha repostaje", 1,0,"C");
            $this->Cell(60, 10, "Producto", 1,0,"C");
            $this->Cell(30, 10, "Precio/litro", 1,0,"C");
            $this->Cell(30, 10, "Cant. litros", 1,0,"C");
            $this->Cell(30, 10, "Dinero gastado", 1,0,"C");
            $this->Ln();
        }

        function showTable($db) {
            $info = $this->getInvoiceData($db);
            $litros = $info[2] / $info[1];
            $fecha = date("d-m-Y", strtotime($info[3]));
            $this->headerTable();
            $this->SetFont("Times", "", 12);
            $this->setX(15);
            $this->setY(95);
            $this->Cell(40, 10, $fecha, 1,0,"C");
            $this->Cell(60, 10, $info[0], 1,0,"C");
            $this->Cell(30, 10, $info[1]." ".EURO, 1,0,"C");
            $this->Cell(30, 10, round($litros, 2), 1,0,"C");
            $this->Cell(30, 10, $info[2]." ".EURO , 1,0,"C");

        }

        function footer() {
            $this->SetY(-15);
            $this->SetFont("Arial", "", 8);
            $this->Cell(10,10,"Generado en infoGasolineras Web",0,0,'L');
            $this->Cell(0,10,utf8_decode('Página ').$this->PageNo().'/{nb}',0,0,'C');
        }


    }

    $pdf = new myPDF($orientation='P',$unit='mm');
    $pdf->AliasNbPages();
    $pdf->AddPage();
    $pdf->userAndGasStationData($db);
    $pdf->showTable($db);
    $pdf->Output();
    //$pdf->Output('F', "../invoices/" . $_SESSION['ruta'] ."A". ".pdf", true);

}
?>