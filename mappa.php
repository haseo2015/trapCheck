<!doctype html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Simple markers</title>
    <style>
      html, body, #map-canvas {
        height: 100%;
        margin: 0px;
        padding: 0px
      }
    </style>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.4/jquery.min.js"></script>
    <script src="https://maps.googleapis.com/maps/api/js?v=3.exp&signed_in=true"></script>
    <script src="geoUtilities.js"></script>
  </head>
  <body>
  <div id="panel">
    <input id="latlng" type="text" value="<?=$_GET['coords']?>"> <input id="full_address" type="text" value="" size="50">
    <!-- <input type="button" value="Reverse Geocode" onclick="codeLatLng()"> -->
  </div>
    <div id="map-canvas"></div>
  </body>
</html>
