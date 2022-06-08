<?php

include_once '../views/templates/layout_head.php';
$action = isset($_GET['action']) ? $_GET['action'] : "";
?>

    <hr class="bg-secondary border-2 border-top border-secondary">
    <div class="container-fluid">
        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-12 col-md-8 col-lg-6">
                <?php
                $msgSent = "<div class='alert alert-success mt-3' role='alert'>La factura se ha enviado a tu dirección de correo electrónico.</div>";
                $msgNotSent = "<div class='alert alert-danger mt-3' role='alert'>El email no ha podido ser enviado.</div>";

                if ($err) {
                    echo "<div class='alert alert-danger mt-3' role='alert'>Todavía no hay facturas registradas.</div>";
                } else if ($dateSearch) {
                    $msgSent = "";
                    $msgNotSent = "";
                    echo "<div class='alert alert-info mt-3'>Mostrando facturas entre " . date('d-m-Y', strtotime($startDate)) . " y " . date('d-m-Y', strtotime($endDate)) . "</div>";
                } else if ($nonCompatibleDates) {
                    $msgSent = "";
                    $msgNotSent = "";
                    echo "<div class='alert alert-danger mt-3' role='alert'>La fecha de inicio (" . date('d-m-Y', strtotime($startDate)) . ") no puede ser igual o posterior a la final (" . date('d-m-Y', strtotime($endDate)) . "). <a class='alert-link' href='../controllers/invoice_list.php'>Volver</a></div>";
                } else if ($errDates) {
                    $msgSent = "";
                    $msgNotSent = "";
                    echo "<div class='alert alert-danger mt-3' role='alert'>No hay facturas entre " . date('d-m-Y', strtotime($startDate)) . " y " . date('d-m-Y', strtotime($endDate)) . ". <a class='alert-link' href='../controllers/invoice_list.php'>Volver</a> </div>";
                } ?>

                <?php if ($action == "sent") {
                    echo $msgSent;
                     } else if ($action == "cannot_be_sent") {
                   echo $msgNotSent;
                 } ?>
            </div>
        </div>

        <div class="row d-flex justify-content-center align-items-center">
            <div class="col-md-12  col-lg-9 col-xl-6">

                <?php if ($generateTableHeader) { ?>

                <div class="row-md-12 d-flex justify-content-end mb-3">
                    <form action="" method="post" autocomplete="on" class="d-flex align-items-center gap-2">
                        <label for="fechaInicio" class="fw-bold">Desde</label>
                        <input type='date' min="2022-01-01" max="<?php echo $curDate ?>" name='fechaInicio'
                               value="<?php echo $startDate; ?>"/>
                        &nbsp;
                        <label for="fechaFin" class="fw-bold">Hasta</label>
                        <input type='date' min="2022-01-01" max="<?php echo $curDate ?>" name='fechaFin'
                               value='<?php echo $endDate ?>'/>
                        <button type="submit" class="btn btn-secondary btn-sm" name="submitInvoiceDate">Buscar</button>
                    </form>
                </div>

                <div class="table-responsive">
                    <table class='table table-hover'>
                        <thead>
                        <tr>
                            <th>Fecha de repostaje</th>
                            <th>Marca gasolinera</th>
                            <th>Dirección</th>
                            <th></th>
                            <th></th>
                            <th></th>
                        </tr>
                        </thead>
                        <?php }

                        if ($tableContent) { ?>
                        <tbody>
                        <?php foreach ($datos as $row) {
                            $invoiceid = $row['invoice_id']; ?>
                            <tr>
                                <td>
                                    <?php echo date('d-m-Y', strtotime($row['refuel_date'])); ?>
                                </td>
                                <td>
                                    <?php echo $row['brand']; ?>
                                </td>
                                <td>
                                    <?php echo $row['address'] . ", " . $row['municipality']; ?>
                                </td>
                                <td>
                                    <form method='post' target='_blank' action='../controllers/invoice_pdf.php'>
                                        <input type='hidden' name='invoiceid' value='<?php echo "{$invoiceid}" ?>'>
                                        <input class="btn btn-success" type='submit' name='enviar'
                                               value='Ver factura'>
                                    </form>
                                </td>
                                <td>
                                    <form id='opcionEnviar' method='post' action='../controllers/invoice_pdf.php'>
                                        <input type='hidden' name='invoiceid' value='<?php echo "{$invoiceid}" ?>'>
                                        <input class="btn btn-success" type='submit' name='enviarEmail'
                                               value='Enviar por Email'>
                                    </form>
                                </td>
                                <td>
                                    <form id='opcionBorrar' method='post' action='../controllers/invoice_list.php'>
                                        <input type='hidden' name='invoiceid2' value='<?php echo "{$invoiceid}" ?>'>
                                        <input class="btn btn-danger" type='submit' name='borrar' value='Borrar'>
                                    </form>
                                </td>
                            </tr>
                        <?php } ?>
                        </tbody>
                    </table>
                </div>

            <?php } ?>

                <?php
                $page_url = "../controllers/invoice_list.php?";
                include_once "../controllers/paging.php";
                ?>

            </div>
        </div>

    </div>

<?php
include_once "../views/templates/layout_foot.php";
?>