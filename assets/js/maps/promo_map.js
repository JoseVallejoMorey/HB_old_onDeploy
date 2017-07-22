
// MAP EN INDEX

var map;
var z_balears = new google.maps.LatLng(39.31, 2.917);
var z_mallorca = new google.maps.LatLng(39.59, 2.917);
var z_menorca = new google.maps.LatLng(39.95, 4.10);
var z_ibiza = new google.maps.LatLng(38.91, 1.42);
var z_palma = new google.maps.LatLng(39.578675, 2.651675);


function MapSelect(controlDiv, map){
  //controlDiv va a ser el padre del select
  //var controlUI = document.getElementById('select-map');
  var controlUI = document.createElement('select');
  controlUI.id  = 'select-map';
  //AddOptions();
  //alert(typeof(controlUI));
  controlDiv.appendChild(controlUI);
  controlDiv.id = 'controls-map';

  var optiontag = document.createElement('option');
  optiontag.value = 'balears';
  optiontag.text  = 'Islas Baleares';
  controlUI.appendChild(optiontag);

  var optiontag = document.createElement('option');
  optiontag.value = 'mallorca';
  optiontag.text  = 'Mallorca';
  controlUI.appendChild(optiontag);

  var optiontag = document.createElement('option');
  optiontag.value = 'menorca';
  optiontag.text  = 'Menorca';
  controlUI.appendChild(optiontag);

  var optiontag = document.createElement('option');
  optiontag.value = 'ibiza';
  optiontag.text  = 'Ibiza';
  controlUI.appendChild(optiontag);    

  var optiontag = document.createElement('option');
  optiontag.value = 'palma';
  optiontag.text  = 'Palma';
  controlUI.appendChild(optiontag); 

  google.maps.event.addDomListener(controlUI, 'change', function() {
    var zona = $(controlUI).val(); 


    if(zona=='menorca'){
      map.setCenter(z_menorca);
      map.setZoom(11);
    }else if(zona=='ibiza'){
      map.setCenter(z_ibiza);
      map.setZoom(12);
    }else if(zona=='balears'){
      map.setCenter(z_balears);
      map.setZoom(9);
    }else if(zona=='palma'){
      map.setCenter(z_palma);
      map.setZoom(14);      
    }else{
      map.setCenter(z_mallorca);
      map.setZoom(11);
    }




    //alert(zona);
    //var x = new google.maps.LatLng(zona);
    // alert('z_'+zona);
    // map.setCenter('z_'+zona);
  });
}


function initialize() {
  var mapDiv = document.getElementById('map-canvas');
  var mapOptions = {
    center: z_balears,
    zoom: 9,
    //disableDefaultUI: true,
    scrollwheel: false,
    panControl: false,
    scaleControl: true,
    streetViewControl:false,


    //OPCIONES DE ZOOM CONTROL
    zoomControl: true,
    zoomControlOptions: {
      style: google.maps.ZoomControlStyle.SMALL,
      position: google.maps.ControlPosition.LEFT_BOTTOM
    },
    //OPCIONES DE TIPO DE MAPA CONTROL
    mapTypeControl: true,
    mapTypeControlOptions: {
      style: google.maps.MapTypeControlStyle.DEFAULT,
      position: google.maps.ControlPosition.LEFT_BOTTOM,
      mapTypeIds: [
        google.maps.MapTypeId.ROADMAP,
        google.maps.MapTypeId.TERRAIN,
        google.maps.MapTypeId.SATELLITE,
        google.maps.MapTypeId.HYBRID
      ]
    }





  };


  var map = new google.maps.Map(mapDiv,mapOptions);


  var centerControlDiv = document.createElement('div');      
  var centerControl = new MapSelect(centerControlDiv, map);

  centerControlDiv.index = 1;
  map.controls[google.maps.ControlPosition.TOP_CENTER].push(centerControlDiv);

  //LAYER DE RUTAS EN BICI
  // var bikeLayer = new google.maps.BicyclingLayer();
  // bikeLayer.setMap(map);
  //LAYER DE TRANSIT
  // var transitLayer = new google.maps.TransitLayer();
  // transitLayer.setMap(map);
  //LAYER DE TRAFICO
  // var trafficLayer = new google.maps.TrafficLayer();
  // trafficLayer.setMap(map);
  //FUSION TABLES LAYER
  layer = new google.maps.FusionTablesLayer({
    query: {
      select: 'geometry',
      from: '1ertEwm-1bMBhpEwHhtNYT47HQ9k2ki_6sRa-UQ'
    },
    styles: [{
      polygonOptions: {
        fillColor: '#00FF00',
        fillOpacity: 0.3
      }
    }, {
      where: 'birds > 300',
      polygonOptions: {
        fillColor: '#0000FF'
      }
    }, {
      where: 'population > 5',
      polygonOptions: {
        fillOpacity: 1.0
      }
    }]
  });
  layer.setMap(map);


}



      google.maps.event.addDomListener(window, 'load', initialize);









