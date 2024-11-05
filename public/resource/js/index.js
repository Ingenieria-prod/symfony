var map = L.map('contenedor-del-mapa').setView([-39.81760979383754, -73.24485685048863],11)
L.tileLayer("https://tile.openstreetmap.org/{z}/{x}/{y}.png?",{}).addTo(map);

var marcador = L.marker([-39.81760979383754, -73.24485685048863]).addTo(map);

marcador.bindPopup("En Valdivia");

var circulo = L.circle([-39.81760979383754, -73.24485685048863], {radius:100}).addTo(map);


function clickSobreMapa(event){
    console.log(event);
  alert(event.latlng);
}

map.on('click', clickSobreMapa);
