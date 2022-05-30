
<?php
/*
// enviar estos datos al controlador, es decir a controllers/invoice_map, configurar en js
// los datos que llegan desde el formulario al insertar un ticket

if ($_POST) {
    $data = $_POST;
// sanitize first
    echo "\n\n:: Data received via POST ::\n\n";
    print_r($data);


    //ejemplo: almacenar sacar tipo y precio en variables
    $tipo = $data["Combustible_Repostado"];
    // en este punto $tipo se mete en la bd
    $tipo = str_replace(' ', '_', $tipo);
    echo $tipo;
    $precio = $data["Precio_".$tipo];
    echo $precio;


}
 */

//Ejemplo de envío de datos
/*
Array
(
    [C_P_] => 07009
    [Dirección] => CARRETERA VIEJA DE BUÑOLA KM. 15
    [Horario] => L-S: 06:00-22:00; D: 08:00-16:00
    [Latitud] => 39.593333
    [Localidad] => PALMA
    [Longitud_(WGS84)] => 2.668944
    [Margen] => D
    [Municipio] => Palma de Mallorca
    [Precio_Biodiesel] =>
    [Precio_Bioetanol] =>
    [Precio_Gas_Natural_Comprimido] =>
    [Precio_Gas_Natural_Licuado] =>
    [Precio_Gases_licuados_del_petróleo] =>
    [Precio_Gasoleo_A] => 1,945
    [Precio_Gasoleo_B] =>
    [Precio_Gasoleo_Premium] => 2,025
    [Precio_Gasolina_95_E10] =>
    [Precio_Gasolina_95_E5] => 1,889
    [Precio_Gasolina_95_E5_Premium] => 1,969
    [Precio_Gasolina_98_E10] =>
    [Precio_Gasolina_98_E5] =>
    [Precio_Hidrogeno] =>
    [Provincia] => BALEARS (ILLES)
    [Remisión] => OM
    [Rótulo] => CEPSA
    [Tipo_Venta] => P
    [%_BioEtanol] => 0,0
    [%_Éster_metílico] => 0,0
    [IDEESS] => 2613
    [IDMunicipio] => 836
    [IDProvincia] => 07
    [IDCCAA] => 04
    [Combustible_Repostado] => Diesel
    [Cantidad_Repostada] => 12
    [Fecha_Repostaje] => 2022-04-28
)
*/


 ?>









