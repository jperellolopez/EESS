var map=null;
var provinciasPorComunidad = "https://sedeaplicaciones.minetur.gob.es/ServiciosRESTCarburantes/PreciosCarburantes/Listados/ProvinciasPorComunidad/";
var municipiosPorProvincia = "https://sedeaplicaciones.minetur.gob.es/ServiciosRESTCarburantes/PreciosCarburantes/Listados/MunicipiosPorProvincia/"
var resultadoGeneral = "https://sedeaplicaciones.minetur.gob.es/ServiciosRESTCarburantes/PreciosCarburantes/EstacionesTerrestres";
var filtroCCAA = "https://sedeaplicaciones.minetur.gob.es/ServiciosRESTCarburantes/PreciosCarburantes/EstacionesTerrestres/FiltroCCAA/";
var filtroProvincia = "https://sedeaplicaciones.minetur.gob.es/ServiciosRESTCarburantes/PreciosCarburantes/EstacionesTerrestres/FiltroProvincia/";
var filtroMunicipio = "https://sedeaplicaciones.minetur.gob.es/ServiciosRESTCarburantes/PreciosCarburantes/EstacionesTerrestres/FiltroMunicipio/";
var arrayGasolineras = [];
var arrayListaProvincias = [];
var arrayListaMunicipios = [];
var markers  = L.markerClusterGroup();
var marker;
var selectCCAA = document.getElementById("selectCCAA");
var selectProvincia = document.getElementById("selectProvincia");
var selectMunicipio = document.getElementById("selectMunicipio");
var zoomGeneral = false;
var isSetFromCCAA = false;

// no se ha utilizado el endpoint "Listados/ComunidadesAutonomas" porque no proporciona coordenadas, las cuales son imprescindibles para ir moviendo el mapa cuando se selecciona una CCAA
var arrayListaCCAA = [ {'IDCCAA': 1, 'Nombre': 'ANDALUCÍA', 'Lat': '37.6000000', 'Lng': '-4.5000000'}, {'IDCCAA':2, 'Nombre':  'ARAGÓN', 'Lat': '41.5000000', 'Lng': '-0.6666700'}, {'IDCCAA':3, 'Nombre':  'ASTURIAS', 'Lat': '43.3666200', 'Lng': '-5.8611200'}, {'IDCCAA':4, 'Nombre':  'BALEARES', 'Lat': '39.6099200', 'Lng': '3.0294800'}, {'IDCCAA':5, 'Nombre':  'CANARIAS', 'Lat': '28.0000000', 'Lng': '-15.5000000'}, {'IDCCAA':6, 'Nombre':  'CANTABRIA', 'Lat': '43.2000000', 'Lng': '-4.0333300'}, {'IDCCAA':7, 'Nombre':  'CASTILLA LA MANCHA', 'Lat': '39.8581', 'Lng': '-4.02263'}, {'IDCCAA':8, 'Nombre':  'CASTILLA Y LEÓN', 'Lat': '42.60003', 'Lng': '-5.57032'}, {'IDCCAA':9, 'Nombre':  'CATALUÑA', 'Lat': '41.8204600', 'Lng': '1.8676800'}, {'IDCCAA':10, 'Nombre':  'COMUNIDAD VALENCIANA', 'Lat': '39.5000000', 'Lng': '-0.7500000'}, {'IDCCAA':11, 'Nombre':  'EXTREMADURA', 'Lat': '39.1666700', 'Lng': '-6.1666700'}, {'IDCCAA':12, 'Nombre':  'GALICIA', 'Lat': '42.7550800', 'Lng': '-7.8662100'}, {'IDCCAA':13, 'Nombre':  'MADRID', 'Lat': '40.4165000', 'Lng': '-3.7025600'}, {'IDCCAA':14, 'Nombre':  'MURCIA', 'Lat': '37.9870400', 'Lng': '-1.1300400'}, {'IDCCAA':15, 'Nombre':  'NAVARRA', 'Lat': '42.8233000', 'Lng': '-1.6513800'}, {'IDCCAA':16, 'Nombre':  'PAÍS VASCO', 'Lat': ' 43.0000000', 'Lng': '-2.7500000'}, {'IDCCAA':17, 'Nombre':  'RIOJA (LA)', 'Lat': '42.3000000', 'Lng': '-2.5000000'}, {'IDCCAA':18, 'Nombre':  'CEUTA', 'Lat': '35.8902800', 'Lng': '-5.3075000'}, {'IDCCAA':19, 'Nombre':  'MELILLA', 'Lat': '35.2936900', 'Lng': '-2.9383300'}];

document.addEventListener('DOMContentLoaded', function() {
    locate()
}, false);


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

// si la geolocalización no está permitida, el zoom es general
async function setupMapNoGeolocalizationEnabled() {
    zoomGeneral = true;
    initMap(40.463667, -3.74922);
    await updateArrayGasolineras();
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

        //tooltip con la información al hacer hover en el marcador
        var tooltip = L.tooltip()
            .setLatLng(lat, lng)
            .setContent( rotulo + " - " + direccion);

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
            ).bindTooltip(tooltip).openTooltip().on('click', clickMarker)

        markers.addLayer(marker);

    }

    map.addLayer(markers);

}

// elimina el color de los marcadores para poder cambiarlo luego
function eliminaColorMarcadores() {
    let seleccionados = document.querySelectorAll(".colorChange")
    for (let x = 0; x < seleccionados.length; x++) {
        seleccionados[x].classList.remove("colorChange")
    }
}

// cuando se hace click en un marcador, se despliega la información
function clickMarker() {

    eliminaColorMarcadores()
    this._icon.classList.add("colorChange")
    let tablaInfo = document.getElementById("tablaInfo");
    tablaInfo.innerHTML = "";

    let encabezados = ['Rótulo', 'Dirección', 'Precio Gasolina 95', 'Precio Gasolina 98', 'Precio Gasoil', 'Precio Diésel +', 'Horario'];

    let headerRow = document.createElement('tr');
    let row = document.createElement('tr');

    encabezados.forEach(texto => {
            let header = document.createElement('th');
            let textNode = document.createTextNode(texto);
            header.appendChild(textNode);
            headerRow.appendChild(header);
    });

    tablaInfo.appendChild(headerRow);

    for (let i = 0; i < Object.keys(this.options).length; i++) {

        //propiedades/opciones que no va a tener en cuenta porque no queremos ver en la lista: opacity e icon
        if (Object.keys(this.options)[i] !== 'opacity' && Object.keys(this.options)[i] !== 'icon') {
            let cell = document.createElement('td');

            if (Object.values(this.options)[i] === "") {
                let textNode = document.createTextNode("-");
                cell.appendChild(textNode);
                row.appendChild(cell);
            }

            let textNode = document.createTextNode(Object.values(this.options)[i]);
            cell.appendChild(textNode);
            row.appendChild(cell);
        }

    }

    tablaInfo.appendChild(row)

}

// esconde el círculo de carga
function hideloader() {
    document.getElementById('loading').style.display = 'none';
}

// consulta a la API para obtener un array de objetos gasolinera
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

    let tablaInfo = document.getElementById("tablaInfo");
    tablaInfo.innerHTML = "";

    arrayGasolineras = await getAPI();
    hideloader()
    placeMarkers()

    await updateListaProvincias();

    selectMunicipio.disabled = true;
    let noFound = document.getElementById('noFound');
    noFound.style.display = "none";

    console.log('Array general', arrayGasolineras)
}

async function updateListaProvincias() {
    let opt = selectCCAA.options[selectCCAA.selectedIndex];
    let response = await fetch(provinciasPorComunidad+opt.value);
    arrayListaProvincias = await response.json()
    console.log(arrayListaProvincias)

    // limpia los elementos anteriores antes de crear nuevos
    document.getElementById("selectProvincia").innerHTML = "";

    for (let x in arrayListaProvincias) {
        let option = document.createElement('option');
        option.value= arrayListaProvincias[x]['IDPovincia']; // Alerta: en el json aparece como "Povincia"
        option.innerHTML= arrayListaProvincias[x]['Provincia'];
        selectProvincia.appendChild(option);
    }

}

// consulta en endpoint con el id de provincia seleccionado y renderiza los marcadores
async function getProvinciaValue() {
    let tablaInfo = document.getElementById("tablaInfo");
    tablaInfo.innerHTML = "";
    let optProvincia = selectProvincia.options[selectProvincia.selectedIndex];
    console.log('valor prov', optProvincia.value)

    if (optProvincia.value == -1) {
        selectMunicipio.disabled = true;
    } else {
        selectMunicipio.disabled = false;
    }

    let response = await fetch(filtroProvincia+optProvincia.value);

    let data = await response.json();

    arrayGasolineras = data.ListaEESSPrecio;

    console.log('eess provincia', arrayGasolineras)

    placeMarkers()

    let noFound = document.getElementById('noFound');
    noFound.style.display = "none";

    await updateListaMunicipios();

}

async function updateListaMunicipios() {
    let opt = selectProvincia.options[selectProvincia.selectedIndex].value;
    console.log('cod prov', opt)
    let response = await fetch(municipiosPorProvincia+opt);
    arrayListaMunicipios = await response.json()
    console.log('lista munic', arrayListaMunicipios)

    // limpia los elementos anteriores antes de crear nuevos
    document.getElementById("selectMunicipio").innerHTML = "";

    for (let x in arrayListaMunicipios) {
        let option = document.createElement('option');
        option.value= arrayListaMunicipios[x]['IDMunicipio'];
        option.innerHTML= arrayListaMunicipios[x]['Municipio'];
        selectMunicipio.appendChild(option);
    }

}

async function getMunicipioValue() {
    let tablaInfo = document.getElementById("tablaInfo");
    tablaInfo.innerHTML = "";
    let optMunicipio = selectMunicipio.options[selectMunicipio.selectedIndex];
    console.log('valor municipio', optMunicipio.value)

   let response = await fetch(filtroMunicipio+optMunicipio.value);

    let data = await response.json();

    arrayGasolineras = data.ListaEESSPrecio;

    let noFound = document.getElementById('noFound');

    if (!arrayGasolineras.length) {
        noFound.style.display = "block";
    } else {
        noFound.style.display = "none";
    }

    console.log('eess municipio', arrayGasolineras)

    placeMarkers()
}
