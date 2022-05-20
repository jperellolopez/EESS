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
            <input id="cantidad" type="text"  name="cantidad" required>
            <label for="cantidad">Cantidad repostada</label>
        </div>
        <br>
        <div>
            <button id="submitBtn" type="button" onclick="enviarDatos()">Generar ticket</button>
        </div>
    </form>
</div>

<p id="message"></p>

<script type="text/javascript" src="js2.js"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous"></script>

</body>

</html>