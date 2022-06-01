<?php
// include page header HTML
include_once '../views/templates/layout_head.php';

// to prevent undefined index notice
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
        <div id="map"></div>
    </div>
    <br>
    <div><table id="tablaInfo"></table></div>
    <br>

    <div class="mb-3 div-ccaa">
        <label for="selectCCAA" class="form-label">CCAA: </label>
        <select name="selectCCAA" id="selectCCAA" >
            <option value="-1">GENERAL</option>
        </select>
        <input type="submit" value="Enviar" onclick="updateArrayGasolineras()">
    </div>

    <div class="mb-3 div-provincias">
        <label for="selectProvincia" class="form-label">Provincia</label>
        <select name="selectProvincia" id="selectProvincia">
            <option value="-1" disabled="disabled" selected="selected">Seleccione provincia...</option>
        </select>
        <input type="submit" value="Enviar" onclick="getProvinciaValue()">
    </div>

    <div class="mb-3 div-municipios">
        <label for="selectMunicipio" class="form-label">Municipio</label>
        <select name="selectMunicipio" id="selectMunicipio" onChange="getMunicipioValue();">
            <option value="" disabled="disabled" selected="selected" style="display:none;">Seleccione municipio...</option>
        </select>
    </div>

    <div id="noFound" style="display: none"><p>No hay gasolineras en el municipio seleccionado</p></div>

    <script type="text/javascript" src="../js/index_map_js.js"></script>

    <?php
    include '../views/templates/layout_foot.php';
    ?>
