<?php

// número de ccaa, viene desde index.php
if (isset($_GET['selectCCAA'])) {

    $endpoint = "https://sedeaplicaciones.minetur.gob.es/ServiciosRESTCarburantes/PreciosCarburantes/EstacionesTerrestres/FiltroCCAA/";

    if ($_GET['selectCCAA'] < 10) {
        $filtroccaa = 0 . $_GET['selectCCAA'];
    } else {
        $filtroccaa = $_GET['selectCCAA'];
    }

    $archivo = json_decode(file_get_contents($endpoint . $filtroccaa), true);

    echo json_encode($archivo);

    // ninguna opción del formulario seleccionada = resultados generales
} else {

    $endpoint = "https://sedeaplicaciones.minetur.gob.es/ServiciosRESTCarburantes/PreciosCarburantes/EstacionesTerrestres";

    $archivo = json_decode(file_get_contents($endpoint), true);

    echo json_encode($archivo);
}








