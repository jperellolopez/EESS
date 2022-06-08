<?php
include_once '../views/templates/layout_head.php';

$action = isset($_GET['action']) ? $_GET['action'] : "";
?>
<hr class="bg-secondary border-2 border-top border-secondary">

<div class='row-md-12'>
    <div class="d-flex justify-content-center">
        <div class="spinner-border mt-4"
             role="status" id="loading">
            <span class="sr-only"></span>
        </div>
    </div>
</div>

<div class='row-12'>
    <div class="d-flex mt-4" id="mapContainer">
        <div id="map"></div>

        <div class='row d-flex justify-content-center'>
            <div class="col-12 col-md-4 mt-md-4 mb-md-4 mt-4 div-ccaa">
                <div class="row">
                    <div class="input-group">
                        <select class="form-select" name="selectCCAA" id="selectCCAA">
                            <option value="-1">GENERAL</option>
                        </select>
                        <button class="btn btn-outline-secondary" id="btnCCAA" type="button"
                                onclick="updateArrayGasolineras()">
                            Buscar CCAA
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4 mt-md-4 mb-md-4 mb-0 mt-0  div-provincias">
                <div class="row">
                    <div class="input-group">
                        <select class="form-select" name="selectProvincia" id="selectProvincia">
                            <option value="-1" disabled="disabled" selected="selected">Seleccione provincia...
                            </option>
                        </select>
                        <button class="btn btn-outline-secondary" id="btnProvincia" type="button"
                                onclick="getProvinciaValue()">
                            Buscar
                        </button>
                    </div>
                </div>
            </div>

            <div class="col-12 col-md-4 mb-0 mt-0 mb-md-4 mt-md-4  div-municipios">
                <div class="row">
                    <div class="input-group">
                        <select class="form-select" name="selectMunicipio" id="selectMunicipio"
                                onChange="getMunicipioValue();">
                            <option value="-1" disabled="disabled" selected="selected" style="display:none;">
                                Seleccione municipio...
                            </option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="d-flex justify-content-center mt-3 mb-4">
        <div class="table-responsive">
            <table class="table table-bordered text-center" id="tablaInfo"></table>
        </div>
        <div id="noFound" style="display: none">
            <div class="alert alert-secondary" role="alert">
                No hay gasolineras en el municipio seleccionado
            </div>
        </div>
    </div>
</div>


<script type="text/javascript" src="../js/index_map_js.js"></script>

<?php
include '../views/templates/layout_foot.php';
?>
