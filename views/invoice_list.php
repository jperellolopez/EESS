<?php
echo "<div class='col-md-12'>";

if($err == true){
    echo "<div class='alert alert-danger margin-top-40' role='alert'>Todavía no hay facturas registradas</div>";
}


if ($generateth == true) {  ?>
<table class='table table-responsive'>
<tr>
<th>Número de factura</th>
<th>Marca gasolinera</th>
<th>Dirección</th>
<th>Fecha factura</th>
<th>Consultar</th>
<th>Borrar</th>
</tr>
<?php }

if ($tableContent == true) {
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
    }




echo "</table>";

}

$page_url = "../controllers/invoice_list.php?";
include_once "../controllers/paging.php";
include_once "../views/templates/layout_foot.php";
?>