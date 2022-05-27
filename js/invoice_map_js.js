var map=null;
var resultadoGeneral = "https://sedeaplicaciones.minetur.gob.es/ServiciosRESTCarburantes/PreciosCarburantes/EstacionesTerrestres";
var resultadoAnterior = "https://sedeaplicaciones.minetur.gob.es/ServiciosRESTCarburantes/PreciosCarburantes/EstacionesTerrestresHist/";
var inputFecha;
var arrayGasolineras = [];
var gasStationsWithinBounds = [];
var markers  = L.markerClusterGroup();
var marker;
var zoomGeneral = false;
var objGasolineraSeleccionado;
var fechaCambiada = false;
var url = 'http://localhost/EESS/data.php';


document.addEventListener('DOMContentLoaded', function() {
    locate()
}, false);

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
            .setContent('<button type="button" class="btn btn-success btn-sm" onclick="getGasStationData(' + IDEESS + ')" ' + '>Seleccionar<br>gasolinera</button>');

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

function getGasStationData(ideess) {
    // muestra el resto de opciones y las limpia
    document.getElementById('gasStationForm').style.display="inline";
    document.getElementById('cantidad').value = "";
    let radioElements = document.getElementsByClassName('radioOption');
    for (let x = 0; x < radioElements.length; x++) {
        radioElements[x].checked = false;
    }

    // se obtiene el objeto correspondiente a la gasolinera seleccionada en el mapa
    for (let i in arrayGasolineras) {
        if (Number(arrayGasolineras[i]['IDEESS']) === ideess) {
            objGasolineraSeleccionado = arrayGasolineras[i];
        }
    }

    // se comprueban los valores de los 4 tipos de combustible, si alguno de ellos es "" se oculta el input
    let labelRadioElements = document.getElementsByClassName('labelCombustible');
    let tiposCombustible = ['Precio Gasolina 95 E5', 'Precio Gasolina 98 E5', 'Precio Gasoleo A', 'Precio Gasoleo Premium']

    for (let j = 0; j < tiposCombustible.length; j++) {

            if (objGasolineraSeleccionado[tiposCombustible[j]] !== "") {
                radioElements[j].style.display="inline";
                labelRadioElements[j].style.display="inline";
            } else {
                radioElements[j].style.display="none";
                labelRadioElements[j].style.display="none";
            }
    }

}

function enviarDatos() {
    //oculta el formulario cuando se clica en el botón de enviar
    let formID = document.getElementById('gasStationForm');


    let inputFecha = document.getElementById('fecha')

    //obtener los datos puestos y añadirlos al objeto, despues enviarlo
    let radioSelected;
    let radioOptions = document.getElementsByClassName('radioOption');

    for (let i = 0; i < radioOptions.length; i++) {
        if (radioOptions[i].checked) {
            radioSelected = radioOptions[i].value;
        }
    }

    let amountSelected = document.getElementById('cantidad').value;

    // validar campos
    if (radioSelected !== undefined && amountSelected !== "" && !isNaN(amountSelected) && amountSelected > 0) {

        // incorpora al objeto gasolinera los datos del formulario
        let c = {
            Combustible_Repostado: radioSelected,
            Cantidad_Repostada: amountSelected,
            Fecha_Repostaje: inputFecha.value
        }

        let datos = Object.assign(objGasolineraSeleccionado, c);

        sendToServer(datos);
        alert("Factura creada con éxito")
        formID.style.display="none";

    } else {
        alert("Faltan campos por completar o los datos no son correctos")

    }
}

function sendToServer(data) {
    let XHR = new XMLHttpRequest();
    let FD  = new FormData();

    for(let key in data) {
        FD.append(key, data[key]);
    }
    XHR.open('POST', url);
    XHR.send(FD);
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
        }
    })

    let table = document.getElementById('tablaInfo2')

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

    let table = document.getElementById('tablaInfo2')

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

// al enviar la fecha, la verifica, la envía a un u otro endpoint y la convierte en un formato admitido por la API
  function cambiarFecha() {

inputFecha =  document.getElementById('fecha').value
     // convierte las fechas mínima, máxima y enviada a ms y las compara, por si se introducen las fechas con el teclado en lugar de usar el datepicker
     let submittedTime = new Date(inputFecha).getTime();
     let minAttr = document.getElementById('fecha').min;
     let minDate = new Date(minAttr).getTime()
     let maxAttr = document.getElementById('fecha').max;
     let maxDate = new Date(maxAttr).getTime()

     if (submittedTime < minDate || submittedTime > maxDate) {
         alert('fecha no válida')
         document.getElementById('fecha').valueAsDate = new Date()
         return
     }

     if (convertDate(inputFecha) !== convertDate(new Date())) {
         fechaCambiada = true;
     } else {
         fechaCambiada = false;
     }

inputFecha = (convertDate(inputFecha));
map.off();
map.remove();
locate()
}

// convierte la fecha en un formato admitido en el endpoint (mm/dd/aaaa -> dd-mm-aaaa)
function convertDate(inputFormat) {
    function pad(s) { return (s < 10) ? '0' + s : s; }
    var d = new Date(inputFormat)
    return [pad(d.getDate()), pad(d.getMonth()+1), d.getFullYear()].join('-')
}

// esconde el círculo de carga una vez han cargado los marcadores
function hideloader() {
    document.getElementById('loading').style.display = 'block';
    document.getElementById('loading').style.display = 'none';
}

// devuelve un array de objetos gasolinera procedentes de la API
 async function getAPI() {

    let response;

    if (!fechaCambiada) {
        response =  await fetch(resultadoGeneral)
    } else {
        response = await  fetch(resultadoAnterior + inputFecha)
    }

    let data = await response.json()
     hideloader()

    let fechaCompleta = data.Fecha;
    let fecha = fechaCompleta.split(' ')[0];

     let infoFecha = document.getElementById('infoFecha');
     infoFecha.innerHTML="Mostrando datos de " + fecha + " - ";

     return data.ListaEESSPrecio

    }

// en la tabla facturas quitar fecha edicion (los tickets no se editarán) y poner fecha de repostaje

