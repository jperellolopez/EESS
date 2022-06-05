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

function replaceSpecialCharacters($s) {
    $replace = array(
        'ъ'=>'-', 'Ь'=>'-', 'Ъ'=>'-', 'ь'=>'-',
        'Ă'=>'A', 'Ą'=>'A', 'À'=>'A', 'Ã'=>'A', 'Á'=>'A', 'Æ'=>'A', 'Â'=>'A', 'Å'=>'A', 'Ä'=>'Ae',
        'Þ'=>'B',
        'Ć'=>'C', 'ץ'=>'C', 'Ç'=>'C',
        'È'=>'E', 'Ę'=>'E', 'É'=>'E', 'Ë'=>'E', 'Ê'=>'E',
        'Ğ'=>'G',
        'İ'=>'I', 'Ï'=>'I', 'Î'=>'I', 'Í'=>'I', 'Ì'=>'I',
        'Ł'=>'L',
        'Ñ'=>'N', 'Ń'=>'N',
        'Ø'=>'O', 'Ó'=>'O', 'Ò'=>'O', 'Ô'=>'O', 'Õ'=>'O', 'Ö'=>'Oe',
        'Ş'=>'S', 'Ś'=>'S', 'Ș'=>'S', 'Š'=>'S',
        'Ț'=>'T',
        'Ù'=>'U', 'Û'=>'U', 'Ú'=>'U', 'Ü'=>'Ue',
        'Ý'=>'Y',
        'Ź'=>'Z', 'Ž'=>'Z', 'Ż'=>'Z',
        'â'=>'a', 'ǎ'=>'a', 'ą'=>'a', 'á'=>'a', 'ă'=>'a', 'ã'=>'a', 'Ǎ'=>'a', 'а'=>'a', 'А'=>'a', 'å'=>'a', 'à'=>'a', 'א'=>'a', 'Ǻ'=>'a', 'Ā'=>'a', 'ǻ'=>'a', 'ā'=>'a', 'ä'=>'ae', 'æ'=>'ae', 'Ǽ'=>'ae', 'ǽ'=>'ae',
        'б'=>'b', 'ב'=>'b', 'Б'=>'b', 'þ'=>'b',
        'ĉ'=>'c', 'Ĉ'=>'c', 'Ċ'=>'c', 'ć'=>'c', 'ç'=>'c', 'ц'=>'c', 'צ'=>'c', 'ċ'=>'c', 'Ц'=>'c', 'Č'=>'c', 'č'=>'c', 'Ч'=>'ch', 'ч'=>'ch',
        'ד'=>'d', 'ď'=>'d', 'Đ'=>'d', 'Ď'=>'d', 'đ'=>'d', 'д'=>'d', 'Д'=>'D', 'ð'=>'d',
        'є'=>'e', 'ע'=>'e', 'е'=>'e', 'Е'=>'e', 'Ə'=>'e', 'ę'=>'e', 'ĕ'=>'e', 'ē'=>'e', 'Ē'=>'e', 'Ė'=>'e', 'ė'=>'e', 'ě'=>'e', 'Ě'=>'e', 'Є'=>'e', 'Ĕ'=>'e', 'ê'=>'e', 'ə'=>'e', 'è'=>'e', 'ë'=>'e', 'é'=>'e',
        'ф'=>'f', 'ƒ'=>'f', 'Ф'=>'f',
        'ġ'=>'g', 'Ģ'=>'g', 'Ġ'=>'g', 'Ĝ'=>'g', 'Г'=>'g', 'г'=>'g', 'ĝ'=>'g', 'ğ'=>'g', 'ג'=>'g', 'Ґ'=>'g', 'ґ'=>'g', 'ģ'=>'g',
        'ח'=>'h', 'ħ'=>'h', 'Х'=>'h', 'Ħ'=>'h', 'Ĥ'=>'h', 'ĥ'=>'h', 'х'=>'h', 'ה'=>'h',
        'î'=>'i', 'ï'=>'i', 'í'=>'i', 'ì'=>'i', 'į'=>'i', 'ĭ'=>'i', 'ı'=>'i', 'Ĭ'=>'i', 'И'=>'i', 'ĩ'=>'i', 'ǐ'=>'i', 'Ĩ'=>'i', 'Ǐ'=>'i', 'и'=>'i', 'Į'=>'i', 'י'=>'i', 'Ї'=>'i', 'Ī'=>'i', 'І'=>'i', 'ї'=>'i', 'і'=>'i', 'ī'=>'i', 'ĳ'=>'ij', 'Ĳ'=>'ij',
        'й'=>'j', 'Й'=>'j', 'Ĵ'=>'j', 'ĵ'=>'j', 'я'=>'ja', 'Я'=>'ja', 'Э'=>'je', 'э'=>'je', 'ё'=>'jo', 'Ё'=>'jo', 'ю'=>'ju', 'Ю'=>'ju',
        'ĸ'=>'k', 'כ'=>'k', 'Ķ'=>'k', 'К'=>'k', 'к'=>'k', 'ķ'=>'k', 'ך'=>'k',
        'Ŀ'=>'l', 'ŀ'=>'l', 'Л'=>'l', 'ł'=>'l', 'ļ'=>'l', 'ĺ'=>'l', 'Ĺ'=>'l', 'Ļ'=>'l', 'л'=>'l', 'Ľ'=>'l', 'ľ'=>'l', 'ל'=>'l',
        'מ'=>'m', 'М'=>'m', 'ם'=>'m', 'м'=>'m',
        'ñ'=>'n', 'н'=>'n', 'Ņ'=>'n', 'ן'=>'n', 'ŋ'=>'n', 'נ'=>'n', 'Н'=>'n', 'ń'=>'n', 'Ŋ'=>'n', 'ņ'=>'n', 'ŉ'=>'n', 'Ň'=>'n', 'ň'=>'n',
        'о'=>'o', 'О'=>'o', 'ő'=>'o', 'õ'=>'o', 'ô'=>'o', 'Ő'=>'o', 'ŏ'=>'o', 'Ŏ'=>'o', 'Ō'=>'o', 'ō'=>'o', 'ø'=>'o', 'ǿ'=>'o', 'ǒ'=>'o', 'ò'=>'o', 'Ǿ'=>'o', 'Ǒ'=>'o', 'ơ'=>'o', 'ó'=>'o', 'Ơ'=>'o', 'œ'=>'oe', 'Œ'=>'oe', 'ö'=>'oe',
        'פ'=>'p', 'ף'=>'p', 'п'=>'p', 'П'=>'p',
        'ק'=>'q',
        'ŕ'=>'r', 'ř'=>'r', 'Ř'=>'r', 'ŗ'=>'r', 'Ŗ'=>'r', 'ר'=>'r', 'Ŕ'=>'r', 'Р'=>'r', 'р'=>'r',
        'ș'=>'s', 'с'=>'s', 'Ŝ'=>'s', 'š'=>'s', 'ś'=>'s', 'ס'=>'s', 'ş'=>'s', 'С'=>'s', 'ŝ'=>'s', 'Щ'=>'sch', 'щ'=>'sch', 'ш'=>'sh', 'Ш'=>'sh', 'ß'=>'ss',
        'т'=>'t', 'ט'=>'t', 'ŧ'=>'t', 'ת'=>'t', 'ť'=>'t', 'ţ'=>'t', 'Ţ'=>'t', 'Т'=>'t', 'ț'=>'t', 'Ŧ'=>'t', 'Ť'=>'t', '™'=>'tm',
        'ū'=>'u', 'у'=>'u', 'Ũ'=>'u', 'ũ'=>'u', 'Ư'=>'u', 'ư'=>'u', 'Ū'=>'u', 'Ǔ'=>'u', 'ų'=>'u', 'Ų'=>'u', 'ŭ'=>'u', 'Ŭ'=>'u', 'Ů'=>'u', 'ů'=>'u', 'ű'=>'u', 'Ű'=>'u', 'Ǖ'=>'u', 'ǔ'=>'u', 'Ǜ'=>'u', 'ù'=>'u', 'ú'=>'u', 'û'=>'u', 'У'=>'u', 'ǚ'=>'u', 'ǜ'=>'u', 'Ǚ'=>'u', 'Ǘ'=>'u', 'ǖ'=>'u', 'ǘ'=>'u', 'ü'=>'ue',
        'в'=>'v', 'ו'=>'v', 'В'=>'v',
        'ש'=>'w', 'ŵ'=>'w', 'Ŵ'=>'w',
        'ы'=>'y', 'ŷ'=>'y', 'ý'=>'y', 'ÿ'=>'y', 'Ÿ'=>'y', 'Ŷ'=>'y',
        'Ы'=>'y', 'ž'=>'z', 'З'=>'z', 'з'=>'z', 'ź'=>'z', 'ז'=>'z', 'ż'=>'z', 'ſ'=>'z', 'Ж'=>'zh', 'ж'=>'zh'
    );
    return strtr($s, $replace);
}

if (isset($_POST['enviar'])  || isset($_POST['enviarEmail'])) {

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
            $nombreSinEsp = str_replace(' ', '', $info[12]);
            $apellidosSinEsp = str_replace(' ', '', $info[13]);
            $_SESSION['ruta'] = replaceSpecialCharacters($nombreSinEsp).replaceSpecialCharacters($apellidosSinEsp). "-" . $_POST['invoiceid'];
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
    if (isset($_POST['enviarEmail'])) {

        $pdf->Output('F', "../invoices/" . $_SESSION['ruta'] ."A". ".pdf", true);

        $filename = null;
        $path = "../invoices/";
        $from_name="InfoGasolineras Web";
        $from_mail="admin@infogasolineras.com";
        $mailto = $_SESSION['email'];

        $rutas = scandir("../invoices");

        $pattern = "-".$_POST['invoiceid']."A";

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

}
?>