<?php
require_once '../config/core.php';

if ($_POST['enviarEmail']) {

    require_once '../fpdf/fpdf.php';
    include_once "../config/database.php";
    include_once '../models/invoice.php';

    session_start();
    $database = new Database();
    $db = $database->getConnection();
    define('EURO', chr(128));

    class myPDF extends FPDF {

        function getInvoiceData($db) {
            $invoiceData = new Invoice($db);
            $invoiceData->invoice_id = $_POST['invoiceid1'];
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
            $_SESSION['ruta'] = $info[12].$info[13]. "-" . $_POST['invoiceid1'];
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
    $pdf->Output('F', "../invoices/" . $_SESSION['ruta'] ."A". ".pdf", true);


$filename = null;
$path = "../invoices/";
$from_name="InfoGasolineras Web";
$from_mail="admin@infogasolineras.com";
$mailto = $_SESSION['email'];

$rutas = scandir("../invoices");
//print_r($rutas);
$pattern = "-".$_POST['invoiceid1']."A";
//echo ($pattern);

    foreach ($rutas as $filenames) {
        if(preg_match('/'.$pattern.'/', $filenames)) {
            $filename = $filenames;
        }
    }

    $file = $path.$filename;
    $content = file_get_contents( $file);
    $content = chunk_split(base64_encode($content));
    $uid = md5(uniqid(time()));
    $file_name = basename($file);
    $subject= $_SESSION['firstname'] .", esta es tu factura de infoGasolineras";
    $message="Hola.\r\n\r\n";
    $message.="Te hemos enviado como archivo adjunto en este correo la factura que has solicitado en infoGasolineras.\r\n\r\n";
    $message.="Gracias por utilizar nuestro servicio.";

    $header = "From: ".$from_name." <".$from_mail.">\r\n";
    //$header .= "Reply-To: ".$replyto."\r\n";
    $header .= "MIME-Version: 1.0\r\n";
    $header .= "Content-Type: multipart/mixed; boundary=\"".$uid."\"\r\n\r\n";

    $nmessage = "--".$uid."\r\n";
    $nmessage .= "Content-type:text/plain; charset=iso-8859-1\r\n";
    $nmessage .= "Content-Transfer-Encoding: 7bit\r\n\r\n";
    $nmessage .= $message."\r\n\r\n";
    $nmessage .= "--".$uid."\r\n";
    $nmessage .= "Content-Type: application/octet-stream; name=\"".$filename."\"\r\n";
    $nmessage .= "Content-Transfer-Encoding: base64\r\n";
    $nmessage .= "Content-Disposition: attachment; filename=\"".$file_name."\"\r\n\r\n";
    $nmessage .= $content."\r\n\r\n";
    $nmessage .= "--".$uid."--";

    if (mail($mailto, $subject, $nmessage, $header)) {
        header("Location: {$home_url}controllers/invoice_list.php?action=sent");
    } else {
        header("Location: {$home_url}controllers/invoice_list.php?action=cannot_be_sent");
    }

}
?>