var map=null;
var resultadoGeneral = "https://sedeaplicaciones.minetur.gob.es/ServiciosRESTCarburantes/PreciosCarburantes/EstacionesTerrestres";
var filtroCCAA = "https://sedeaplicaciones.minetur.gob.es/ServiciosRESTCarburantes/PreciosCarburantes/EstacionesTerrestres/FiltroCCAA/";
var arrayGasolineras = [];
var markers  = L.markerClusterGroup();
var marker;
var select = document.getElementById("selectCCAA")
var zoomGeneral = false;
var isSetFromCCAA = false;


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

// es necesario poner los nombres de las CCAA manualmente ya que en el JSON sólo está el ID
var arrCCAA = [ {'IDCCAA': 1, 'Nombre': 'ANDALUCÍA', 'Lat': '37.6000000', 'Lng': '-4.5000000'}, {'IDCCAA':2, 'Nombre':  'ARAGÓN', 'Lat': '41.5000000', 'Lng': '-0.6666700'}, {'IDCCAA':3, 'Nombre':  'ASTURIAS', 'Lat': '43.3666200', 'Lng': '-5.8611200'}, {'IDCCAA':4, 'Nombre':  'BALEARES', 'Lat': '39.6099200', 'Lng': '3.0294800'}, {'IDCCAA':5, 'Nombre':  'CANARIAS', 'Lat': '28.0000000', 'Lng': '-15.5000000'}, {'IDCCAA':6, 'Nombre':  'CANTABRIA', 'Lat': '43.2000000', 'Lng': '-4.0333300'}, {'IDCCAA':7, 'Nombre':  'CASTILLA LA MANCHA', 'Lat': '39.8581', 'Lng': '-4.02263'}, {'IDCCAA':8, 'Nombre':  'CASTILLA Y LEÓN', 'Lat': '42.60003', 'Lng': '-5.57032'}, {'IDCCAA':9, 'Nombre':  'CATALUÑA', 'Lat': '41.8204600', 'Lng': '1.8676800'}, {'IDCCAA':10, 'Nombre':  'C. VALENCIANA', 'Lat': '39.5000000', 'Lng': '-0.7500000'}, {'IDCCAA':11, 'Nombre':  'EXTREMADURA', 'Lat': '39.1666700', 'Lng': '-6.1666700'}, {'IDCCAA':12, 'Nombre':  'GALICIA', 'Lat': '42.7550800', 'Lng': '-7.8662100'}, {'IDCCAA':13, 'Nombre':  'MADRID', 'Lat': '40.4165000', 'Lng': '-3.7025600'}, {'IDCCAA':14, 'Nombre':  'MURCIA', 'Lat': '37.9870400', 'Lng': '-1.1300400'}, {'IDCCAA':15, 'Nombre':  'NAVARRA', 'Lat': '42.8233000', 'Lng': '-1.6513800'}, {'IDCCAA':16, 'Nombre':  'P. VASCO', 'Lat': ' 43.0000000', 'Lng': '-2.7500000'}, {'IDCCAA':17, 'Nombre':  'LA RIOJA', 'Lat': '42.3000000', 'Lng': '-2.5000000'}, {'IDCCAA':18, 'Nombre':  'CEUTA', 'Lat': '35.8902800', 'Lng': '-5.3075000'}, {'IDCCAA':19, 'Nombre':  'MELILLA', 'Lat': '35.2936900', 'Lng': '-2.9383300'}];

// crear opciones del formulario de CCAA
for (let x in arrCCAA) {
    let option = document.createElement('option');
    option.value= arrCCAA[x]['IDCCAA'];
    option.innerHTML= arrCCAA[x]['Nombre'];
    select.appendChild(option);
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
    //placeMarkers();
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

// if si la geolocalización no está permitida, se centra en todo el país
async function setupMapNoGeolocalizationEnabled() {
    zoomGeneral = true;
    initMap(40.463667, -3.74922);
    //fetchApiData(resultadoGeneral);
    await updateArrayGasolineras();
    placeMarkers();
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

        markers.clearLayers();

    for (let x in arrayGasolineras) {
        arrayGasolineras[x]['Latitud'] = arrayGasolineras[x]['Latitud'].replace(/,/g, '.');
        arrayGasolineras[x]['Longitud (WGS84)'] = arrayGasolineras[x]['Longitud (WGS84)'].replace(/,/g, '.');
        let lat = parseFloat(arrayGasolineras[x]['Latitud']);
        let lng = parseFloat(arrayGasolineras[x]['Longitud (WGS84)']);

        //popup con la información al hacer click en el marcador
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

     // formateo de los números de ccaa menores a 10 para incluirlos en el endpoint
     let opt = select.options[select.selectedIndex];
     if (opt.value > 0 && opt.value < 10 && opt.value.charAt(0) !== "0") {
         opt.value = "0"+opt.value;
     }
     console.log(opt.value)

     // almacena las coordenadas de la ccaa elegida en el formulario
     let ccaalatlng;
     arrCCAA.forEach(function(ccaa) {
         if (opt.value == ccaa.IDCCAA) {
             ccaalatlng = ccaa.Lat + ", " + ccaa.Lng;
             map.flyTo([ccaa.Lat, ccaa.Lng], 8);

         }
     });
     console.log(ccaalatlng)

     // fetch de la información de los endpoints elegidos, uso de booleanos para controlar en qué orden se entra y el nivel de zoom
     let response;
     // cuando se entra por primera vez (coordenadas locales, vista muy cercana)
     if (opt.value == -1 && !isSetFromCCAA) {
         response = await fetch(resultadoGeneral)

         // cuando se entra desde una CCAA (recarga marcadores y cambia a vista lejana)
     } else if (opt.value == -1 && isSetFromCCAA) {
         response = await fetch(resultadoGeneral)
         map.flyTo([40.463667, -3.74922], 5);
     }
     // cuando se elige una CCAA (carga sólo los de dicha zona y cambia a vista cercana)
     else {
         response = await fetch(filtroCCAA + opt.value);
         isSetFromCCAA = true;
     }
    let data = await response.json()

     return data.ListaEESSPrecio

    }

// se ejecuta cada vez que se elige una opción en el dropdown para llenar el array con las gasolineras de esa CCAA
async function updateArrayGasolineras(){

    arrayGasolineras = await getAPI();
    hideloader()
    placeMarkers()
    console.log('Array general', arrayGasolineras)
}