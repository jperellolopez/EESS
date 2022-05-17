var map=null;
var resultadoGeneral = "https://sedeaplicaciones.minetur.gob.es/ServiciosRESTCarburantes/PreciosCarburantes/EstacionesTerrestres";
var arrayGasolineras = [];
var gasStationsWithinBounds = [];
var markers  = L.markerClusterGroup();
var marker;
var zoomGeneral = false;



//IDEAS
//1r mapa
// filtrar por ccaa, provincia y localidad (secuencialmente)
// Disponer info básica en los tooltip de los marcadores
// disponer el marcador seleccionado en una lista bajo el mapa con info ampliada
// Opcional: renderizar una tabla con los precios de los últimos 7 días para la 95 y el diesel (obtener fecha actual, sumar precios de toda la ccaa para ese tipo de gasolina para 1 semana, y renderizar una tabla por dom). Hacerlo con las provincias y las localidades elegidas.

// 2do mapa
// igual que el anterior, pero además renderiza una lista con las gasolineras dentro de los bounds del mapa en ese momento. Al elegir una opción de la lista se seleccionan sus datos. Datos formulario: fecha, gasolinera (se elige en el mapa), tipo combustible, cantidad de dinero

//MAPA
// funcion onload en index.php
function locate() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(setupMap, showError);
    }
}

// si la geolocalización está permitida, inicia el mapa, llena el array de gasolineras y coloca los marcadores en el mapa
async function setupMap(posicion) {
    initMap(posicion.coords.latitude, posicion.coords.longitude);
     arrayGasolineras = await getAPI();
     placeMarkers();

    // obtiene un array con los marcadores en los límites del mapa al cargarlo
     map.whenReady(function() {
         getMarkersInView()
     });

    // obtiene un array con los marcadores en los límites del mapa al moverlo
    map.on('moveend', function() {
       getMarkersInView()
    });

}

// error popup alerts
function showError(error) {
    switch (error.code) {
        case error.PERMISSION_DENIED:
            alert("Se ha denegado el permiso de localización");
            setupMapNoGeolocalizationEnabled()
            break;
        case error.POSITION_UNAVAILABLE:
            alert("La información de la geolocalización no está disponible");
            break;
        case error.TIMEOUT:
            alert("La petición de geolocalización ha excedido el límite de tiempo");
            break;
        case error.UNKNOWN_ERROR:
            alert("Error desconocido. Por favor, intente recargar la página");
            break;
    }
}

// si la geolocalización no está permitida, la posicion se centra en todo el país
async function setupMapNoGeolocalizationEnabled() {
    zoomGeneral = true;
    initMap(40.463667, -3.74922);
    arrayGasolineras = await getAPI();
    placeMarkers();
    // obtiene un array con los marcadores en los límites del mapa al cargarlo
    map.whenReady(function() {
        getMarkersInView()
    });

    // obtiene un array con los marcadores en los límites del mapa al moverlo
    map.on('moveend', function() {
        getMarkersInView()
    });

}

//inicialización del mapa
function initMap(lat, lng) {
    let zoom = (!zoomGeneral ? 14 : 5);

    map = new L.map('map').setView([lat, lng], zoom, {preferCanvas: true});
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
    }).addTo(map);
}

// coloca los marcadores en el mapa
function placeMarkers() {
  // cada vez que se llama la función, empieza borrando las capas existentes
  markers.clearLayers();

    for (let x in arrayGasolineras) {
        arrayGasolineras[x]['Latitud'] = arrayGasolineras[x]['Latitud'].replace(/,/g, '.');
        arrayGasolineras[x]['Longitud (WGS84)'] = arrayGasolineras[x]['Longitud (WGS84)'].replace(/,/g, '.');
        let lat = parseFloat(arrayGasolineras[x]['Latitud']);
        let lng = parseFloat(arrayGasolineras[x]['Longitud (WGS84)']);
        let rotulo = arrayGasolineras[x]['Rótulo'];
        let direccion = arrayGasolineras[x]['Dirección'] + ", " + arrayGasolineras[x]['C.P.'] + ", " + arrayGasolineras[x]['Municipio'];
        let horario = arrayGasolineras[x]['Horario'];
        let precio95 = arrayGasolineras[x]['Precio Gasolina 95 E5'];
        let precioDiesel = arrayGasolineras[x]['Precio Gasoleo A'];
        let precio98 = arrayGasolineras[x]['Precio Gasolina 98 E5'];
        let precioDieselPlus = arrayGasolineras[x]['Precio Gasoleo Premium'];

        //popup con la información al hacer click en el marcador
        var popup = L.tooltip()
            .setLatLng(lat, lng)
            .setContent( rotulo + " - " + arrayGasolineras[x]['Dirección']);

        marker = new L.Marker(
            [lat, lng],
            {
                rotulo: rotulo,
                direccion: direccion,
                precioGasolina95: precio95,
                precioGasolina98: precio98,
                precioGasoil: precioDiesel,
                precioDieselPlus: precioDieselPlus,
                horario: horario
            }
            ).bindTooltip(popup).openTooltip();


        markers.addLayer(marker);

    }

    map.addLayer(markers);

}

// almacena en un array los marcadores que están dentro de los límites actuales del mapa. No incluye los que están dentro de un cluster para no generar listas enormes
function getMarkersInView() {

    gasStationsWithinBounds.length=0;

    map.eachLayer( function(layer) {
        if(layer instanceof L.Marker) {
            if(map.getBounds().contains(layer.getLatLng())) {

                // con este condicional sólo se guardarán marcadores, no clusters
                if (Object.keys(layer.options).length > 3) {
                    gasStationsWithinBounds.push(layer.options)
                }

            }
        }
    });
    console.log(gasStationsWithinBounds)

    tablaGasolineras()
}

function tablaGasolineras() {

    let table = document.getElementById('tablaInfo')

    table.innerHTML=""

    let encabezados = ['Rótulo', 'Dirección', 'Precio Gasolina 95', 'Precio Gasolina 98', 'Precio Gasoil', 'Precio Diésel +', 'Horario'];


    let headerRow = document.createElement('tr')

    encabezados.forEach(texto => {
        if (gasStationsWithinBounds.length !== 0) {
            let header = document.createElement('th');
            let textNode = document.createTextNode(texto);
            header.appendChild(textNode);
            headerRow.appendChild(header);
        }

    })

    table.appendChild(headerRow);

    gasStationsWithinBounds.forEach(gasolinera => {
        let row = document.createElement('tr');

        Object.values(gasolinera).forEach(texto => {
            if (texto !== 1) {
                let cell = document.createElement('td');

                if (texto === "") {
                let textNode = document.createTextNode("-");
                cell.appendChild(textNode);
                row.appendChild(cell);
                }

                let textNode = document.createTextNode(texto);
                cell.appendChild(textNode);
                row.appendChild(cell);
            }

        })
        table.appendChild(row);
    })

}

// hacer tabla de js1 al mismo estilo que esta
// hacer que arrayGasolineras se llene consultando el endpoint de fecha ( por defecto la actual, pero cambiará al enviar form para enviar la fecha al endpoint)
// añadir la propiedad IDEESS a los marcadores como compo oculto (visibility)
// al hacer clic en un boton enviar el IDEESS a arrayGasolineras, obtener los datos de ese objeto
// seguir pidiendo datos (tipo de combustible y dinero)
// enviar lo anterior a php para guardar en bd



// esconde el círculo de carga una vez han cargado los marcadores
function hideloader() {
    document.getElementById('loading').style.display = 'none';
}

 async function getAPI() {

    let response = await fetch(resultadoGeneral)

    let data = await response.json()
     hideloader()

     return data.ListaEESSPrecio

    }

