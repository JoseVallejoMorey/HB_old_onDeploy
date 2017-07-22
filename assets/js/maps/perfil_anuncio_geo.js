//sujerencia de direcciones y geolocalizacion de las mismas

var geocoder;
var map;

var placeSearch, autocomplete;
var componentForm = {
  street_number: 'short_name',
  route: 'long_name',
  locality: 'long_name',
  administrative_area_level_1: 'short_name',
  country: 'long_name',
  postal_code: 'short_name'
};


function initialize() {

//   var bounds = new google.maps.places.LatLngBounds(
//     new google.maps.LatLng(55.38942944437183, -2.7379201682812226),
//     new google.maps.LatLng(54.69726685890506, -1.2456105979687226)
// );
    var southWest = new google.maps.LatLng( 12.97232, 77.59480 );
    var northEast = new google.maps.LatLng( 12.89201, 77.58905 );
    var bangaloreBounds = new google.maps.LatLngBounds( southWest, northEast );

  //inicializo el autocomplete
  autocomplete = new google.maps.places.Autocomplete(
    (document.getElementById('direccion')),
    { bounds: bangaloreBounds,
      types: ['geocode'],
      componentRestrictions: {country: 'es'} });


  geocoder = new google.maps.Geocoder();
  var latlng = new google.maps.LatLng(-34.397, 150.644);
  var mapOptions = {
    zoom: 12,
    center: latlng
  }

  google.maps.event.addListener(autocomplete, 'place_changed', function() {
    fillInAddress();
  });


  map = new google.maps.Map(document.getElementById('anuncio-map-canvas'), mapOptions);
}

function codeAddress() {
  var address = document.getElementById('direccion').value;
  geocoder.geocode( { 'address': address}, function(results, status) {
    if (status == google.maps.GeocoderStatus.OK) {
      map.setCenter(results[0].geometry.location);
      var marker = new google.maps.Marker({
          map: map,
          position: results[0].geometry.location
      });
      alert(results[0].geometry.location);
    } else {
      alert('Geocode was not successful for the following reason: ' + status);
    }
  });
}

// Get the place details from the autocomplete object.
function fillInAddress() {
  var place = autocomplete.getPlace();
}


google.maps.event.addDomListener(window, 'load', initialize);

