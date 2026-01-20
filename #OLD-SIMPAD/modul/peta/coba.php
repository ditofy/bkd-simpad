<!DOCTYPE html>
<html>
<head>
    <title>OpenStreetMap with PHP</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
</head>
<body>
    <div id="map" style="height: 800px;"></div>

    <script>
        var map = L.map('map').setView([-0.22201779719471829, 100.63227057435452], 16);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 30,
        }).addTo(map);

        var marker = L.marker([-0.22201779719471829, 100.63227057435452]).addTo(map);
        marker.bindPopup("<b>Hello world!</b><br>I am a popup.").openPopup();
    </script>
</body>
</html>