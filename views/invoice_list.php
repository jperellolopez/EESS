<?php

include_once '../views/templates/layout_head.php';
$action=isset($_GET['action']) ? $_GET['action'] : "";

echo "<div class='col-md-12'>";

if($err){
    echo "<div class='alert alert-danger margin-top-40' role='alert'>Todavía no hay facturas registradas.</div>";
} else if ($dateSearch) {
    echo "<div class='alert alert-info'>Mostrando facturas entre ". date('d-m-Y', strtotime($startDate)) . " y " . date('d-m-Y', strtotime($endDate))."</div>";
} else if ($nonCompatibleDates) {
    echo "<div class='alert alert-danger margin-top-40' role='alert'>La fecha de inicio (". date('d-m-Y', strtotime($startDate)) .") no puede ser igual o posterior a la final (" . date('d-m-Y', strtotime($endDate)) . "). <a href='../controllers/invoice_list.php'><strong>Volver</strong></a></div>";
} else if ($errDates) {
    echo "<div class='alert alert-danger margin-top-40' role='alert'>No hay facturas entre ". date('d-m-Y', strtotime($startDate)) . " y " . date('d-m-Y', strtotime($endDate)) . ". <a href='../controllers/invoice_list.php'><strong>Volver</strong></a> </div>";
}

if ($action == "sent") {
    echo '<script>alert("La factura se ha enviado a tu dirección de correo electrónico.")</script>';
} else if ($action == "cannot_be_sent") {
    echo '<script>alert("No ha sido posible enviar el email. Asegúrese de estar usando Minirelay.")</script>';
}

if ($generateTableHeader) {  ?>
<table class='table table-responsive'>
<tr>
<th>Fecha de repostaje</th>
<th>Marca gasolinera</th>
<th>Dirección</th>
<th></th>
<th></th>
</tr>

    <div class="col-sm-12">
        <form action="" method="post" autocomplete="on">
            <div class="mb-3">
                <label for="fechaInicio">Desde</label>
                <input type='date'  min="2022-01-01" max="<?php echo $curDate?>" name='fechaInicio' value="<?php echo $startDate;?>"/>
                &nbsp;
                <label for="fechaFin">Hasta</label>
                <input type='date' min="2022-01-01" max="<?php echo $curDate?>" name='fechaFin' value='<?php echo $endDate?>'/>
                <button type="submit" class="btn btn-primary" name="submitInvoiceDate">Buscar</button>
            </div>
        </form>
    </div>
    &nbsp;
<?php }

if ($tableContent) {
    foreach ($datos as $row) {
        $invoiceid = $row['invoice_id'];
        echo "<tr>";
        echo "<td class='width-30-percent'>";
        echo date('d-m-Y', strtotime($row['refuel_date']));
        echo "</td>";
        echo "<td class='width-30-percent'>";
        echo $row['brand'];
        echo "</td>";
        echo "<td class='width-30-percent'>";
        echo $row['address'] . ", " . $row['municipality'];
        echo "</td>";
         echo "<td class='width-30-percent'>";
         echo "<form method='post'  target='_blank' action='../controllers/invoice_pdf.php'>";
         echo "<input type='hidden' name='invoiceid' value='{$invoiceid}'>";
         echo "<input type='submit' name='enviar' value='Ver factura'>";
         echo '</form>';
        echo "</td>";
        echo "</td>";
        echo "<td class='width-30-percent'>";
        echo "<form id='opcionEnviar' method='post' action='../controllers/invoice_pdf.php'>";
        echo "<input type='hidden' name='invoiceid' value='{$invoiceid}'>";
        echo "<input type='submit' name='enviarEmail' value='Enviar por Email'>";
        echo '</form>';
        echo "</td>";
        echo "<td class='width-30-percent'>";
        echo "<form id='opcionBorrar' method='post' action='../controllers/invoice_list.php'>";
        echo "<input type='hidden' name='invoiceid2' value='{$invoiceid}'>";
        echo "<input type='submit' name='borrar' value='Borrar'>";
        echo '</form>';
        echo "</td>";
    }

echo "</table>";

}

$page_url = "../controllers/invoice_list.php?";
include_once "../controllers/paging.php";
include_once "../views/templates/layout_foot.php";
?>