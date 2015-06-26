/**
 * Created by Admin on 23/06/2015.
 */

var geocoder;
var map;
var infowindow = new google.maps.InfoWindow();
var marker;
function initialize() {
    geocoder = new google.maps.Geocoder();
    var latlng = new google.maps.LatLng(40.730885,-73.997383);
    var mapOptions = {
        zoom: 8,
        center: latlng,
        mapTypeId: 'roadmap'
    }
    map = new google.maps.Map(document.getElementById('map-canvas'), mapOptions);
    google.maps.event.addDomListener(window, 'tilesloaded', codeLatLng());
}

function codeLatLng() {
   console.log('codeLatLng');

    var input = $('#latlng').val();
    var latlngStr = input.split(',', 2);
    var lat = parseFloat(latlngStr[0]);
    var lng = parseFloat(latlngStr[1]);
    var latlng = new google.maps.LatLng(lat, lng);
    geocoder.geocode({'latLng': latlng}, function(results, status) {
        if (status == google.maps.GeocoderStatus.OK) {
            if (results[1]) {
                map.setZoom(11);

                marker = new google.maps.Marker({
                    position: latlng,
                    map: map
                });
                map.setCenter(marker.getPosition());
                infowindow.setContent(results[1].formatted_address);
                infowindow.open(map, marker);
               $('#full_address').val(results[1].formatted_address);
            } else {
                alert('No results found');
            }
        } else {
            alert('Geocoder failed due to: ' + status);
        }
    });
}

google.maps.event.addDomListener(window, 'load', initialize);




