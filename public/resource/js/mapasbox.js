mapboxgl.accessToken = 'pk.eyJ1IjoianF1aW50YW5hdyIsImEiOiJjbTMwcDU4N2QwbzhzMm5vcXdkZTk0aHo3In0.dnPy0ePJOJQKcDe0ii-E3w';

const mapa = new mapboxgl.Map({
    container: "mapabox",
	 style: "mapbox://styles/mapbox/streets-v12", 
	 center: [-73.25, -39.82], 
	 zoom: 11, 
});

const pin = new mapboxgl.Marker({
	color: "green",
	rotation: 5
}).setLngLat([-73.25, -39.82]).addTo(mapa)


const popup = new mapboxgl.Popup({
	offset: 25,
}).setHTML("<h2>Hola</h2>")	
// }).setText("Hola")

const pin2 = new mapboxgl.Marker({}).setLngLat([-73.2, -39.8]).setPopup(popup).addTo(mapa)

function clickMapaBox(evento){
   alert(evento.lngLat)
}

mapa.on("click", clickMapaBox)


