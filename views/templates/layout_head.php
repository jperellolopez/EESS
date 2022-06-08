<!DOCTYPE html>
<html lang="es" class="h-100">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>InfoGasolineras</title>

    <!-- FontAwesome icons-->
    <script src="https://kit.fontawesome.com/0f18b510c3.js" crossorigin="anonymous"></script>

    <!-- Bootstrap CSS 5-->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">

    <!-- Leaflet CSS and JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.8.0/dist/leaflet.css"
          integrity="sha512-hoalWLoI8r4UszCkZ5kL8vayOGVae1oxXe/2A4AO6J9+580uKHDO3JdHb7NzwwzK5xr/Fs0W40kiNHxM9vyTtQ=="
          crossorigin=""/>
    <script src="https://unpkg.com/leaflet@1.8.0/dist/leaflet.js"
            integrity="sha512-BB3hKbKWOc9Ez/TAwyWxNXeoV9c1v6FIeYiBieIWkpLjauysF18NzgR1MBNBXf8/KABdlkX68nAhlwcDFLGPCQ=="
            crossorigin=""></script>

    <!-- MarkerCluster plugin -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.css"/>
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.4.1/dist/MarkerCluster.Default.css"/>
    <script type="text/javascript"
            src="https://unpkg.com/leaflet.markercluster@1.4.1/dist/leaflet.markercluster.js"></script>

    <!-- custom CSS -->
    <link href="<?php echo $home_url . "libs/css/style.css" ?>" rel="stylesheet"/>

</head>
<body class="d-flex flex-column h-100">

<!-- navbar -->
<?php include_once 'navbar.php'; ?>

<!-- container -->
<div class="container-fluid flex-shrink-0">

        <?php
        if ($page_title != "Login" && $page_title != "Registrar nuevo usuario" && $page_title !== "Restablecer contraseña" && $page_title !== "Resetear contraseña"){
        ?>

            <div class='row-12'>
                <div class="page-header d-flex justify-content-left mt-4 ms-5">
                    <h2><?php echo isset($page_title) ? $page_title : ""; ?></h2>
                </div>
            </div>
<?php
}
?>