<?php
// include page header HTML
include_once '../views/templates/layout_head.php';

echo "<div class='col-md-12'>";

if($err){
    echo "<div class='alert alert-danger margin-top-40' role='alert'>Todavía no hay facturas registradas</div>";
}

if ($generateth) {  ?>
<table class='table table-responsive'>
<tr>
<th>Número de factura</th>
<th>Marca gasolinera</th>
<th>Dirección</th>
<th>Fecha repostaje</th>
<th></th>
<th></th>
</tr>

    <div class="col-sm-12">
        <form action="" method="post" autocomplete="on">
            <div class="mb-3">
                <label for="fechaInicio">Desde</label>
                <input type='date' name='fechaInicio' value="<?php echo $fechaInicio;?>"/>
                &nbsp;
                <label for="fechaFin">Hasta</label>
                <input type='date' name='fechaFin' value='<?php echo $fechaFin?>'/>
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
        echo $invoiceid;
        echo "</td>";
        echo "<td class='width-30-percent'>";
        echo $row['brand'];
        echo "</td>";
        echo "<td class='width-30-percent'>";
        echo $row['address'] . ", " . $row['municipality'];
        echo "</td>";
        echo "<td class='width-30-percent'>";
        echo date('d-m-Y', strtotime($row['refuel_date']));
        echo "</td>";
         echo "<td class='width-30-percent'>";
         echo "<form method='post'  target='_blank' action='../controllers/invoice_pdf.php'>";
         echo "<input type='hidden' name='invoiceid' value='{$invoiceid}'>";
         echo "<input type='submit' name='enviar' value='Ver factura'>";
         echo '</form>';
        echo "</td>";
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