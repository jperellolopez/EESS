<?php
// core configuration
include_once "config/core.php";

// set page title
$page_title="Creación de facturas";

// include login checker
$require_login=true;
include_once "login_check.php";

// include page header HTML
include_once 'layout_head.php';

echo "<div class='col-md-12'>";

// to prevent undefined index notice
$action = isset($_GET['action']) ? $_GET['action'] : "";
?>

<div class="d-flex justify-content-center">
    <div class="spinner-border mt-4"
         role="status" id="loading">
        <span class="sr-only"></span>
    </div>
</div>
<br>

<div id="mapContainer">
    <h2>Mapa de gasolineras</h2>
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
            <input  id="combustible1" class="radioOption" type="radio" name="tipoGasolina" value="Gasolina 95">
            <label class="labelCombustible" for="combustible1">Gasolina 95</label>

            <input  id="combustible2" class="radioOption" type="radio" name="tipoGasolina" value="Gasolina 98">
            <label  class="labelCombustible" for="combustible2">Gasolina 98</label>

            <input  id="combustible3" class="radioOption" type="radio" name="tipoGasolina" value="Diesel">
            <label  class="labelCombustible" for="combustible3">Diésel</label>

            <input  id="combustible4" class="radioOption" type="radio" name="tipoGasolina" value="Diesel Plus">
            <label  class="labelCombustible" for="combustible4">Diésel +</label>
        </div>
        <br>
        <div>
            <label for="cantidad">Cantidad repostada</label>
            <input id="cantidad" type="text"  name="cantidad" required>
        </div>
        <br>
        <div>
            <button id="submitBtn" type="button" onclick="enviarDatos()">Generar ticket</button>
        </div>
    </form>
</div>

<p id="message"></p>

<script type="text/javascript" src="js/invoice_map_js.js"></script>

<?php
include 'layout_foot.php';
?>