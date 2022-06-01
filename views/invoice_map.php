<?php
include_once '../views/templates/layout_head.php';
$action = isset($_GET['action']) ? $_GET['action'] : "";
?>

<div class='col-md-12'>
<div class="d-flex justify-content-center">
    <div class="spinner-border mt-4"
         role="status" id="loading">
        <span class="sr-only"></span>
    </div>
</div>
<br>

<div id="mapContainer">
    <h4>Selecciona una gasolinera en el mapa para empezar a crear tus facturas. ¡Así de sencillo!</h4>
    <br>
    <div id="map"></div>
</div>
<br>
<div>
    <div id="divFecha" >
        <span id="infoFecha"></span>
        <label for="fecha" id="labelFecha"> Cambiar fecha: </label>
        <input type="date"  min="<?php  echo date("Y-m-d", strtotime("-1 months", strtotime(date("Y-m-d")."-01"))); ?>" max="<?php  echo date("Y-m-d"); ?>" value="<?php echo date("Y-m-d"); ?>" id="fecha">
        <input type="button" value="Elegir" onclick="cambiarFecha()">
        <br>
    </div>
    <table id="tablaInfo2"></table></div>

<br>

<div id="gasStationForm">
    <form>
        <div>
            <input  id="combustible1" class="radioOption" type="radio" name="tipoGasolina" value="Gasolina 95 E5">
            <label class="labelCombustible" for="combustible1">Gasolina 95</label>

            <input  id="combustible2" class="radioOption" type="radio" name="tipoGasolina" value="Gasolina 98 E5">
            <label  class="labelCombustible" for="combustible2">Gasolina 98</label>

            <input  id="combustible3" class="radioOption" type="radio" name="tipoGasolina" value="Gasoleo A">
            <label  class="labelCombustible" for="combustible3">Diésel</label>

            <input  id="combustible4" class="radioOption" type="radio" name="tipoGasolina" value="Gasoleo Premium">
            <label  class="labelCombustible" for="combustible4">Diésel +</label>
        </div>
        <br>
        <div>
            <label for="cantidad">Cantidad repostada</label>
            <input id="cantidad" type="text"  name="cantidad" placeholder="entre 1 y 999 €" required>
        </div>
        <br>
        <div>
            <button id="submitBtn" type="button" onclick="enviarDatos()">Generar factura</button>
        </div>
    </form>
</div>

<p id="message"></p>

<script type="text/javascript" src="../js/invoice_map_js.js"></script>

<?php
include '../views/templates/layout_foot.php';
?>
