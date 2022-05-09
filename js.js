var map=null;
var resultadoGeneral = "https://sedeaplicaciones.minetur.gob.es/ServiciosRESTCarburantes/PreciosCarburantes/EstacionesTerrestres";
var filtroCCAA = "https://sedeaplicaciones.minetur.gob.es/ServiciosRESTCarburantes/PreciosCarburantes/EstacionesTerrestres/FiltroCCAA/";
var arrayGasolineras = [];
var markers;
var marker;

// guardar en array general el contenido de la api (endpoint con + info), que será el que se coja para cargar el mapa por primera vez.  Ir cogiendo datos de ese array para rellenar los formularios de seleccion, haciendo sub-arrays si hace falta
// por ejemplo, funcion para coger las provincias y eliminar repeticiones y poneras en el select
// el array sin filtrar es lo que coge por defecto para llenar los marcadores del mapa
// asi solo hay que llamar a la api 1 vez

//IDEAS
// llamar al metodo localizar tras cada actualizacion del dropdown, poniendo que el array del que bebe el mapa, y vaciando el mapa entre consulta y consulta
// hacer que el mapa vuele a la ccaa elegida (poner unas coords en arrCCAA?)
//posibilidad de renderizar una lista con las gasolineras dentro de los bounds del mapa (doc leaflet y openstreetmap)

// 1r mapa busca exclusivamente con formularios que se recargan con las opciones elegidas. Disponer info en los marcadores
// 2do mapa renderiza una lista con las gasolineras dentro de los bounds del mapa. Al elegir una se seleccionan sus datos


//FORM
var select = document.getElementById('selectCCAA');

// es necesario poner los nombres de las CCAA manualmente ya que en el JSON sólo está el ID
var arrCCAA = [ {'IDCCAA': 1, 'Nombre': 'ANDALUCÍA'}, {'IDCCAA':2, 'Nombre':  'ARAGÓN'}, {'IDCCAA':3, 'Nombre':  'ASTURIAS'}, {'IDCCAA':4, 'Nombre':  'BALEARES'}, {'IDCCAA':5, 'Nombre':  'CANARIAS'}, {'IDCCAA':6, 'Nombre':  'CANTABRIA'}, {'IDCCAA':7, 'Nombre':  'CASTILLA LA MANCHA'}, {'IDCCAA':8, 'Nombre':  'CASTILLA Y LEÓN'}, {'IDCCAA':9, 'Nombre':  'CATALUÑA'}, {'IDCCAA':10, 'Nombre':  'C. VALENCIANA'}, {'IDCCAA':11, 'Nombre':  'EXTREMADURA'}, {'IDCCAA':12, 'Nombre':  'GALICIA'}, {'IDCCAA':13, 'Nombre':  'MADRID'}, {'IDCCAA':14, 'Nombre':  'MURCIA'}, {'IDCCAA':15, 'Nombre':  'NAVARRA'}, {'IDCCAA':16, 'Nombre':  'P. VASCO'}, {'IDCCAA':17, 'Nombre':  'LA RIOJA'}, {'IDCCAA':18, 'Nombre':  'CEUTA'}, {'IDCCAA':19, 'Nombre':  'MELILLA'}];

// crear opciones del formulario de CCAA
for (let x in arrCCAA) {
    let opt = document.createElement('option');
    opt.value= arrCCAA[x]['IDCCAA'];
    opt.innerHTML= arrCCAA[x]['Nombre'];
    select.appendChild(opt);
}

//MAPA
// funcion onload en index.php
function locate() {
    if (navigator.geolocation) {
        navigator.geolocation.getCurrentPosition(setupMap, showError);
    }
}

// si la geolocalización está permitida, inicia el mapa, llena el array de gasolinas y coloca los marcadores en el mapa
async function setupMap(posicion) {
    initMap(posicion.coords.latitude, posicion.coords.longitude);
    //fetchApiData(resultadoGeneral);
     await updateArrayGasolineras();
    placeMarkers();
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

// if geolocalization is not allowed, center in Madrid, then generate markers
async function setupMapNoGeolocalizationEnabled() {
    initMap(40.4165, -3.70256);
    //fetchApiData(resultadoGeneral);
    await updateArrayGasolineras();
    placeMarkers();
}

//initizalizes the map
function initMap(lat, lng) {
        map = new L.map('map').setView([lat, lng], 14, {preferCanvas: true});
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

}

// coloca los marcadores en el mapa
function placeMarkers() {

    markers = L.markerClusterGroup();
    map.removeLayer(markers);

    for (let x in arrayGasolineras) {
        arrayGasolineras[x]['Latitud'] = arrayGasolineras[x]['Latitud'].replace(/,/g, '.');
        arrayGasolineras[x]['Longitud (WGS84)'] = arrayGasolineras[x]['Longitud (WGS84)'].replace(/,/g, '.');
        let lat = parseFloat(arrayGasolineras[x]['Latitud']);
        let lng = parseFloat(arrayGasolineras[x]['Longitud (WGS84)']);

        var popup = L.popup()
            .setLatLng(lat, lng)
            .setContent(arrayGasolineras[x]['Dirección']);

        marker = L.marker([lat, lng]).bindPopup(popup).openPopup();

        markers.addLayer(marker);
    }
    map.addLayer(markers);

}

function hideloader() {
    document.getElementById('loading').style.display = 'none';
}

 async function getAPI() {
     var sel = document.getElementById("selectCCAA")
     var opt = sel.options[sel.selectedIndex]

     if (opt.value > 0 && opt.value < 10) {
         opt.value = "0"+opt.value;
     }

     console.log(opt.value)

     var response;

     if (opt.value == -1 || opt.value == null) {
         response = await fetch(resultadoGeneral)
     } else {
         response = await fetch(filtroCCAA + opt.value);
     }

    var data = await response.json()
     return data.ListaEESSPrecio

    }

// se ejecuta cada vez que se elige una opción en el dropdown para llenar el array con las gasolineras de esa CCAA
async function updateArrayGasolineras(){

    arrayGasolineras = await getAPI();
    hideloader();
    console.log('Array general', arrayGasolineras)
}

//console.log('arrayGasolineras', arrayGasolineras)

// función para recoger la info principal: lee el json con los datos generales, los introduce en un array para usarlos en los formularios, genera los marcadores
/*
function fetchApiData(url) {

fetch(url)
    .then(response => response.json())
    .then(function (result) {
        hideloader();
        console.log("Result", result) // resultado de la lectura
        var markers = L.markerClusterGroup();

        for (var x in result.ListaEESSPrecio) { // se selecciona solo el array de objetos gasolinera

            //var gasolinera = result.ListaEESSPrecio[x]
            //arrayGasolineras.push(result.ListaEESSPrecio[x]);
            result.ListaEESSPrecio[x]['Latitud'] = result.ListaEESSPrecio[x]['Latitud'].replace(/,/g, '.');
            result.ListaEESSPrecio[x]['Longitud (WGS84)'] = result.ListaEESSPrecio[x]['Longitud (WGS84)'].replace(/,/g, '.');
            let lat = parseFloat(result.ListaEESSPrecio[x]['Latitud']);
            let lng = parseFloat(result.ListaEESSPrecio[x]['Longitud (WGS84)']);

            // info displayed when clicking in a marker
            var popup = L.popup()
                .setLatLng(lat, lng)
                .setContent(result.ListaEESSPrecio[x]['Dirección']);

            var marker = L.marker([lat, lng]).bindPopup(popup).openPopup();

            markers.addLayer(marker);

        }

        map.addLayer(markers);

    })
    .catch(error =>console.log("error", error));
}
*/

// reads the API json in order to generate markers
/*
function generateMarkers(apiData) {

    var markers = L.markerClusterGroup();


        for (var x in apiData.ListaEESSPrecio) {



            var gasolinera = apiData.ListaEESSPrecio[x];

            gasolinera['Latitud'] = gasolinera['Latitud'].replace(/,/g, '.');
            gasolinera['Longitud (WGS84)'] = gasolinera['Longitud (WGS84)'].replace(/,/g, '.');
            let lat = parseFloat(gasolinera['Latitud']);
            let lng = parseFloat(gasolinera['Longitud (WGS84)']);

            // info displayed when clicking in a marker
            var popup = L.popup()
                .setLatLng(lat, lng)
                .setContent(gasolinera['Dirección']);

            var marker = L.marker([lat, lng]).bindPopup(popup).openPopup();

            markers.addLayer(marker);

        }

        map.addLayer(markers);

}

 */

/*
var select = document.getElementById('selectCCAA');
$.getJSON(apiFile, function (data) {
 for (var x in data.ListaEESSPrecio) {
  let gasolinera = data.ListaEESSPrecio[x];
  var opt = document.createElement('option');
  opt.value= gasolinera['IDProvincia'];
  opt.innerHTML= gasolinera['Provincia'];
  select.appendChild(opt);
 }
});

 */

/*
async function getApiData(url) {

    const response = await fetch(url);

    var apiData = await response.json();

    if(response) {
        hideloader();
    }

    generateMarkers(apiData);
}
 */