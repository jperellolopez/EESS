<?php
include_once '../views/templates/layout_head.php';
$action = isset($_GET['action']) ? $_GET['action'] : "";
?>
<hr class="bg-secondary border-2 border-top border-secondary">

<div class="row-md-12">
    <div class="d-flex justify-content-center">
        <div class="alert alert-success alert-dismissible " role="alert">
            <h4>Selecciona una gasolinera en el mapa para empezar a crear tus facturas. ¡Así de sencillo!</h4>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    </div>
</div>

<div class='row-md-12'>
    <div class="d-flex justify-content-center">
        <div class="spinner-border mt-4 mb-4"
             role="status" id="loading">
            <span class="sr-only"></span>
        </div>
    </div>
</div>

<div class='row-12'>
    <div class="d-flex mt-4" id="mapContainer">
        <div id="map"></div>
    </div>
</div>

<div class="row">
    <div class="d-flex justify-content-center mt-3">
        <div id="divFecha" class="d-flex align-items-center gap-1">
            <span id="infoFecha"></span>
            <label for="fecha" id="labelFecha"> Cambiar fecha: </label>
            <input type="date"
                   min="<?php echo date("Y-m-d", strtotime("-1 months", strtotime(date("Y-m-d") . "-01"))); ?>"
                   max="<?php echo date("Y-m-d"); ?>" value="<?php echo date("Y-m-d"); ?>" id="fecha">
            <input type="button" class="btn btn-success btn-sm" value="Consultar fecha" onclick="cambiarFecha()">
        </div>
    </div>
</div>

<div class="row">
    <div class="d-flex justify-content-center mt-3 mb-2">
        <div class="table-responsive">
            <table class="table table-bordered text-center" id="tablaInfo2"></table>
        </div>
    </div>
</div>

<div id="gasStationForm">
    <form class="row d-flex justify-content-center">
        <div class="col-md-7 alert alert-secondary">
            <div class="d-flex justify-content-center gap-3">

                <div class="form-outline mb-3">
                    <div class="col-lg-12 col-md-6 mb-3">
                        <input id="combustible1" class="radioOption " type="radio" name="tipoGasolina"
                               value="Gasolina 95 E5">
                        <label class="labelCombustible" for="combustible1">Gasolina 95 &nbsp;</label>

                        <input id="combustible2" class="radioOption" type="radio" name="tipoGasolina"
                               value="Gasolina 98 E5">
                        <label class="labelCombustible" for="combustible2">Gasolina 98 &nbsp;</label>

                        <input id="combustible3" class="radioOption" type="radio" name="tipoGasolina" value="Gasoleo A">
                        <label class="labelCombustible" for="combustible3">Diésel &nbsp;</label>


                        <input id="combustible4" class="radioOption" type="radio" name="tipoGasolina"
                               value="Gasoleo Premium">
                        <label class="labelCombustible" for="combustible4">Diésel + &nbsp;</label>

                    </div>
                    <div class="form-outline mb-3">
                        <label for="cantidad">Cantidad repostada:</label>
                        <input id="cantidad" type="text" name="cantidad" placeholder="entre 1 y 999 €" required>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="d-flex justify-content-center mb-2">
                    <button class="btn btn-success" id="submitBtn" type="button" onclick="enviarDatos()">Generar
                        factura
                    </button>
                </div>
            </div>
        </div>
    </form>
</div>

<div class="row-md-9">
    <div class="d-flex justify-content-center mt-2 mb-4 p-3" id="message"></div>
</div>

<script type="text/javascript" src="../js/invoice_map_js.js"></script>

<?php
include '../views/templates/layout_foot.php';
?>
