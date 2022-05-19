<!doctype html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">

    <title>Mapa de precios de combustible</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- JQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"
            integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!-- Leaflet CSS and JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css"
          integrity="sha512-hoalWLoI8r4UszCkZ5kL8vayOGVae1oxXe/2A4AO6J9+580uKHDO3JdHb7NzwwzK5xr/Fs0W40kiNHxM9vyTtQ=="
          crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js"
            integrity="sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ=="
            crossorigin=""></script>

    <!-- MarkerCluster -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css" />
    <script type="text/javascript" src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>

    <!-- Map CSS and JS -->
    <link rel="stylesheet" href="style.css">
</head>

<body onload="locate()">

<div class="d-flex justify-content-center">
    <div class="spinner-border mt-4"
         role="status" id="loading">
        <span class="sr-only"></span>
    </div>
</div>
<br>

<div id="mapContainer">
    <h2>Mapa de gasolineras</h2>
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

<script type="text/javascript" src="js.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>

</html>