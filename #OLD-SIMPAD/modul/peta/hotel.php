

<!-- Replace the value of the key parameter with your own API key. -->
   <script src="http://maps.googleapis.com/maps/api/js?key=AIzaSyDkRG9lBTDLUqmjHYS8BD-LGzstFIFPkKI"></script>



<!DOCTYPE html>
<html>
  <head>
    <meta name="viewport" content="initial-scale=1.0, user-scalable=no">
    <meta charset="utf-8">
    <title>Complex icons</title>
    <style>
      html, body {
        height: 100%;
        margin: 0;
        padding: 0;
      }
      #map {
        height: 100%;
      }
    </style>
  </head>
  <body>
    <div id="map" style="width:100%;height:750px;  border: 1px solid #d2d6d1;"></div>
    <script>

      // The following example creates complex markers to indicate beaches near
      // Sydney, NSW, Australia. Note that the anchor is set to (0,32) to correspond
      // to the base of the flagpole.

      function initMap() {
       var map = L.map('map').setView([51.505, -0.09], 13);

	L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
    maxZoom: 19,
}).addTo(map);

      }

      // Data for the markers consisting of a name, a LatLng and a zIndex for the
      // order in which these markers should display on top of each other.
      var beaches = [
                       
               		    ['Kantor Camat Harau', -0.167773, 100.659976],
                   		['Nagari Tarantang', -0.1161732,100.6494947],
                   		['Nagari SariLamak', -0.1041883,100.6279258],
                   		['Nagari Solok Bio-bio',-0.1146544,100.6015286], 
						['Nagari Harau',-0.1284253,100.652226], 
						['Nagari Gurun', -0.1643786,100.6197663], 
						['Nagari Lubuk Batingkok', -0.1505656,100.6313713], 
						['Nagari Koto Tuo Harau',-0.1753641,100.6474679],
						['Nagari Batu Balang',-0.1755106,100.6792503],
						['Nagari Bukik Limbuku',-0.190537,100.6606782],
						['Nagari Taram',-0.18929,100.7437792],
						['Nagari Pilubang',-0.1569158,100.715422],
      ];

      function setMarkers(map) {
        // Adds markers to the map.

        // Marker sizes are expressed as a Size of X,Y where the origin of the image
        // (0,0) is located in the top left of the image.

        // Origins, anchor positions and coordinates of the marker increase in the X
        // direction to the right and in the Y direction down.
        var image = {
          url: 'icon-location.png',
          // This marker is 20 pixels wide by 32 pixels high.
          size: new google.maps.Size(32, 32),
          // The origin for this image is (0, 0).
          origin: new google.maps.Point(0, 0),
          // The anchor for this image is the base of the flagpole at (0, 32).
          anchor: new google.maps.Point(0, 32)
        };
        // Shapes define the clickable region of the icon. The type defines an HTML
        // <area> element 'poly' which traces out a polygon as a series of X,Y points.
        // The final coordinate closes the poly by connecting to the first coordinate.
        var shape = {
          coords: [1, 1, 1, 20, 18, 20, 18, 1],
          type: 'poly'
        };
        for (var i = 0; i < beaches.length; i++) {
          var beach = beaches[i];
          var marker = new google.maps.Marker({
            position: {lat: beach[1], lng: beach[2]},
            map: map,
            icon: image,
            shape: shape,
            title: beach[0],
            zIndex: beach[3]
          });
        }
      }
    </script>
    <script async defer
    src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDkRG9lBTDLUqmjHYS8BD-LGzstFIFPkKI&callback=initMap">
    </script>
  </body>
</html>

