<!DOCTYPE html>
<html>
  <head>
    <title>Simple Map</title>
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
    <style>
      /* Always set the map height explicitly to define the size of the div
       * element that contains the map. */
      #columnchart_value5 {
        height: 100%;
      }
      /* Optional: Makes the sample page fill the window. */
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
    </style>
  </head>
  <body>
    <div id="columnchart_value5"></div>
    <script>
      var map;
      function initMap() {
        map = new google.maps.Map(document.getElementById('columnchart_value5'), {
          center: {lat: -34.397, lng: 150.644},
          zoom: 8
        });
      }
    </script>
    
<script async defer src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCQs_RyLtC82fNUP08MJO90ykPZJMaK_Wk&callback=initMap"></script>

  </body>
</html>