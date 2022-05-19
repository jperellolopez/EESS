var map=null;
var resultadoGeneral = "https://sedeaplicaciones.minetur.gob.es/ServiciosRESTCarburantes/PreciosCarburantes/EstacionesTerrestres";
var arrayGasolineras = [];
var gasStationsWithinBounds = [];
var markers  = L.markerClusterGroup();
var marker;
var zoomGeneral = false;

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
        let IDEESS = arrayGasolineras[x]['IDEESS'];

        var popup = L.popup({
            closeButton:false,
            autoPan:false
        })
            .setLatLng(lat, lng)
            .setContent('<button type="button" class="btn btn-success btn-sm" onclick="mostrar(' + IDEESS + ')" ' + '>Seleccionar<br>gasolinera</button>');

        //tooltip al poner el cursor sobre un marcador
        var tooltip = L.tooltip()
            .setLatLng(lat, lng)
            .setContent( rotulo + " - " + arrayGasolineras[x]['Dirección']);

        // se construyen los marcadores con sus coordenadas y sus opciones
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
            ).bindPopup(popup).bindTooltip(tooltip).openTooltip().on('click', colorChange)

        // se añade el ID de gasolinera
        marker.IDgasolinera = IDEESS

        markers.addLayer(marker);

    }

    map.addLayer(markers);

}

function mostrar(ideess) {

    let objGasolineraSeleccionado;

    for (let i in arrayGasolineras) {
        if (Number(arrayGasolineras[i]['IDEESS']) === ideess) {
            objGasolineraSeleccionado = arrayGasolineras[i];
            console.log(objGasolineraSeleccionado)
        }
    }

    // hacer que al ejecutar esta función se habiliten los inputs de precio y tipo de gasolina
// coger los datos que interesa guardar del objeto y meterlos en un array con return



}

function colorChange() {

    let seleccionados = document.querySelectorAll(".colorChange")
    for (let x = 0; x < seleccionados.length; x++) {
        seleccionados[x].classList.remove("colorChange")
    }
    this._icon.classList.add("colorChange")

    let colorFondoFila = "green";
    var direccionCompleta;


    gasStationsWithinBounds.forEach(gasolinera => {
        if (gasolinera.IDgasolinera === this.IDgasolinera) {
            direccionCompleta = gasolinera.options['direccion']
            //console.log(gasolinera)
        }
    })

    let table = document.getElementById('tablaInfo')

    let tr = table.getElementsByTagName('tr');

    for (let i = 0; i<tr.length; i++) {
        let celdas = tr[i].getElementsByTagName('td');

        tr[i].removeAttribute("style");

        for (let celda = 0; celda < celdas.length; celda++) {

            if (celdas[celda].innerHTML === direccionCompleta) {
                celdas[celda].parentNode.style.backgroundColor = colorFondoFila;
            }
        }
    }

}

// almacena en un array los marcadores que están dentro de los límites actuales del mapa. No incluye los que están dentro de un cluster para no generar listas enormes
function getMarkersInView() {

    gasStationsWithinBounds.length=0;

    map.eachLayer( function(layer) {
        if(layer instanceof L.Marker) {
            if(map.getBounds().contains(layer.getLatLng())) {

                // con este condicional sólo se guardarán marcadores, no clusters
                if (Object.keys(layer.options).length > 3) {
                    gasStationsWithinBounds.push(layer)
                }

            }
        }
    });

    tablaGasolineras()
}

function tablaGasolineras() {

    let table = document.getElementById('tablaInfo')

    table.innerHTML="";

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

        Object.values(gasolinera.options).forEach(texto => {

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

// hacer que arrayGasolineras se llene consultando el endpoint de fecha ( por defecto la actual, pero cambiará al enviar form para enviar la fecha al endpoint)
// al hacer clic en un boton enviar el IDEESS a arrayGasolineras, obtener los datos de ese objeto (o al hacer click en un marcador, abrir botón en el popup)
// seguir pidiendo datos (tipo de combustible y dinero)
// enviar lo anterior a php para guardar en bd



// esconde el círculo de carga una vez han cargado los marcadores
function hideloader() {
    document.getElementById('loading').style.display = 'none';
}

// devuelve un array de objetos gasolinera procedentes de la API
 async function getAPI() {

    let response = await fetch(resultadoGeneral)

    let data = await response.json()
     hideloader()

     return data.ListaEESSPrecio

    }

