var map=null;
var provinciasPorComunidad = " https://sedeaplicaciones.minetur.gob.es/ServiciosRESTCarburantes/PreciosCarburantes/Listados/ProvinciasPorComunidad/";
var resultadoGeneral = "https://sedeaplicaciones.minetur.gob.es/ServiciosRESTCarburantes/PreciosCarburantes/EstacionesTerrestres";
var filtroCCAA = "https://sedeaplicaciones.minetur.gob.es/ServiciosRESTCarburantes/PreciosCarburantes/EstacionesTerrestres/FiltroCCAA/";
var filtroProvincia = "https://sedeaplicaciones.minetur.gob.es/ServiciosRESTCarburantes/PreciosCarburantes/EstacionesTerrestres/FiltroProvincia/";
var arrayGasolineras = [];
var arrayListaProvincias = []
var markers  = L.markerClusterGroup();
var marker;
var selectCCAA = document.getElementById("selectCCAA")
var selectProvincia = document.getElementById("selectProvincia");
var zoomGeneral = false;
var isSetFromCCAA = false;

//IDEAS
//1r mapa
// filtrar por ccaa, provincia y localidad (secuencialmente)
// Disponer info en los tooltip de los marcadores
// renderizar una tabla con los precios de los últimos 7 días para la 95 y el diesel (obtener fecha actual, sumar precios de toda la ccaa para ese tipo de gasolina para 1 semana, y renderizar una tabla por dom). Hacerlo con las provincias y las localidades elegidas.

// 2do mapa
// renderiza una lista con las gasolineras dentro de los bounds del mapa. Al elegir una se seleccionan sus datos. Datos formulario: fecha, gasolinera (se elige en el mapa), tipo combustible, cantidad de dinero

//FORM

// no se ha utilizado el endpoint "Listados/ComunidadesAutonomas" porque no proporciona coordenadas, las cuales son imprescindibles para ir moviendo el mapa cuando se selecciona una CCAA
var arrayListaCCAA = [ {'IDCCAA': 1, 'Nombre': 'ANDALUCÍA', 'Lat': '37.6000000', 'Lng': '-4.5000000'}, {'IDCCAA':2, 'Nombre':  'ARAGÓN', 'Lat': '41.5000000', 'Lng': '-0.6666700'}, {'IDCCAA':3, 'Nombre':  'ASTURIAS', 'Lat': '43.3666200', 'Lng': '-5.8611200'}, {'IDCCAA':4, 'Nombre':  'BALEARES', 'Lat': '39.6099200', 'Lng': '3.0294800'}, {'IDCCAA':5, 'Nombre':  'CANARIAS', 'Lat': '28.0000000', 'Lng': '-15.5000000'}, {'IDCCAA':6, 'Nombre':  'CANTABRIA', 'Lat': '43.2000000', 'Lng': '-4.0333300'}, {'IDCCAA':7, 'Nombre':  'CASTILLA LA MANCHA', 'Lat': '39.8581', 'Lng': '-4.02263'}, {'IDCCAA':8, 'Nombre':  'CASTILLA Y LEÓN', 'Lat': '42.60003', 'Lng': '-5.57032'}, {'IDCCAA':9, 'Nombre':  'CATALUÑA', 'Lat': '41.8204600', 'Lng': '1.8676800'}, {'IDCCAA':10, 'Nombre':  'COMUNIDAD VALENCIANA', 'Lat': '39.5000000', 'Lng': '-0.7500000'}, {'IDCCAA':11, 'Nombre':  'EXTREMADURA', 'Lat': '39.1666700', 'Lng': '-6.1666700'}, {'IDCCAA':12, 'Nombre':  'GALICIA', 'Lat': '42.7550800', 'Lng': '-7.8662100'}, {'IDCCAA':13, 'Nombre':  'MADRID', 'Lat': '40.4165000', 'Lng': '-3.7025600'}, {'IDCCAA':14, 'Nombre':  'MURCIA', 'Lat': '37.9870400', 'Lng': '-1.1300400'}, {'IDCCAA':15, 'Nombre':  'NAVARRA', 'Lat': '42.8233000', 'Lng': '-1.6513800'}, {'IDCCAA':16, 'Nombre':  'PAÍS VASCO', 'Lat': ' 43.0000000', 'Lng': '-2.7500000'}, {'IDCCAA':17, 'Nombre':  'RIOJA (LA)', 'Lat': '42.3000000', 'Lng': '-2.5000000'}, {'IDCCAA':18, 'Nombre':  'CEUTA', 'Lat': '35.8902800', 'Lng': '-5.3075000'}, {'IDCCAA':19, 'Nombre':  'MELILLA', 'Lat': '35.2936900', 'Lng': '-2.9383300'}];

// crear opciones del formulario de CCAA
for (let x in arrayListaCCAA) {
    let option = document.createElement('option');
    option.value= arrayListaCCAA[x]['IDCCAA'];
    option.innerHTML= arrayListaCCAA[x]['Nombre'];
    selectCCAA.appendChild(option);
}

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
     await updateArrayGasolineras();

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

  // cada vez que se llama la función, empieza borrando las capas existentes
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

// esconde el círculo de carga
function hideloader() {
    document.getElementById('loading').style.display = 'none';
}

 async function getAPI() {

     // formateo de los números de ccaa menores a 10 para incluirlos en el endpoint
     let opt = selectCCAA.options[selectCCAA.selectedIndex];
     if (opt.value > 0 && opt.value < 10 && opt.value.charAt(0) !== "0") {
         opt.value = "0"+opt.value;
     }
     console.log('ccaa value', opt.value)

     // mueve el mapa a las coordenadas de la ccaa elegida en el formulario
     arrayListaCCAA.forEach(function(ccaa) {
         if (opt.value == ccaa.IDCCAA) {
             map.flyTo([ccaa.Lat, ccaa.Lng], 8);
         }
     });

     // fetch de la información de los endpoints elegidos, uso de booleanos para controlar en qué orden se entra y el nivel de zoom
     let response;
     // cuando se entra por primera vez (coordenadas locales, vista muy cercana)
     if (opt.value == -1 && !isSetFromCCAA) {
         response = await fetch(resultadoGeneral)
         selectProvincia.disabled = true;

     // cuando se selecciona la opcion general desde una CCAA (recarga marcadores y cambia a vista lejana)
     } else if (opt.value == -1 && isSetFromCCAA) {
         response = await fetch(resultadoGeneral)
         map.flyTo([40.463667, -3.74922], 5);
         selectProvincia.disabled = true;
     }
     // cuando se elige una CCAA (carga sólo los de dicha zona y cambia a vista cercana). También carga la lista de provincias
     else {
         response = await fetch(filtroCCAA + opt.value);
         isSetFromCCAA = true;
         selectProvincia.disabled = false;

     }

    let data = await response.json()

     return data.ListaEESSPrecio

    }

// se ejecuta cada vez que se elige una opción en el dropdown para llenar el array con las gasolineras de esa CCAA
async function updateArrayGasolineras(){

    arrayGasolineras = await getAPI();
    hideloader()
    placeMarkers()

    await updateListaProvincias();
    console.log('Array general', arrayGasolineras)
}

async function updateListaProvincias() {
    let opt = selectCCAA.options[selectCCAA.selectedIndex];
    let response = await fetch(provinciasPorComunidad+opt.value);
    arrayListaProvincias = await response.json()
    console.log(arrayListaProvincias)

    // limpia los elementos anteriores antes de crear nuevos
    document.getElementById("selectProvincia").innerHTML = "";

    // crea las opciones
    let inicial = document.createElement("option");
    inicial.value="-1";
    inicial.innerHTML="Seleccione provincia...";
    selectProvincia.appendChild(inicial);

    for (let x in arrayListaProvincias) {
        let option = document.createElement('option');
        option.value= arrayListaProvincias[x]['IDPovincia']; // Alerta: en el json aparece como "Povincia"
        option.innerHTML= arrayListaProvincias[x]['Provincia'];
        selectProvincia.appendChild(option);
    }

}

// consulta en endpoint con el id de provincia seleccionado y renderiza los marcadores
async function getProvinciaValue() {
    let optProvincia = selectProvincia.options[selectProvincia.selectedIndex].value;

    let response = await fetch(filtroProvincia+optProvincia);

    let data = await response.json();

    arrayGasolineras = data.ListaEESSPrecio;

    console.log('eess provincia', arrayGasolineras)

    placeMarkers()

}






// Código sin usar endpoint de provincias
/*
//a partir del array general arrayGasolineras, se extraen las provincias en otro array, quitando repeticiones
async function getProvincias(arrayGasolineras) {

    arrProvincias.length = 0;

    arrProvincias = arrayGasolineras.map(function(gasolinera) {
        return {
            'IDProvincia': gasolinera['IDProvincia'], 'Provincia': gasolinera['Provincia']
        }
    })

    arrProvincias = [...new Map(arrProvincias.map(v => [v['IDProvincia'], v])).values()]
    console.log('id provincias', arrProvincias)
    return arrProvincias
}

async function generateSelectProvinciaOptions() {
    arrProvincias = await getProvincias(arrayGasolineras)

    // limpia los elementos anteriores antes de crear nuevos
    document.getElementById("selectProvincia").innerHTML = "";

    // crea las opciones
    let inicial = document.createElement("option");
    inicial.value="-1";
    inicial.innerHTML="Seleccione provincia...";
    selectProvincia.appendChild(inicial);

    for (let x in arrProvincias) {
        let option = document.createElement('option');
        option.value= arrProvincias[x]['IDProvincia'];
        option.innerHTML= arrProvincias[x]['Provincia'];
        selectProvincia.appendChild(option);
    }

}

function getProvinciaValue() {
    let optProvincia = selectProvincia.options[selectProvincia.selectedIndex];
    let optCCAA = selectCCAA.options[selectCCAA.selectedIndex];
    console.log('ccaa value2', optCCAA.value)
    console.log("provincia value", optProvincia.value);

    //con el valor de ccaa y provincia se accede al endpoint de la provincia

}

 */
