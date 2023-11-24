<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>WEBGIS PUSAT PERBELANJAAN</title>

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />

    <!-- Marker Cluster -->
    <link rel="stylesheet" href="assets/plugins/leaflet-markercluster/MarkerCluster.css" />
    <link rel="stylesheet" href="assets/plugins/leaflet-markercluster/MarkerCluster.Default.css" />
    <!-- Routing -->
    <link rel="stylesheet" href="assets/plugins/leaflet-routing/leaflet-routing-machine.css" />
    <!-- Search CSS Library -->
    <link rel="stylesheet" href="assets/plugins/leaflet-search/leaflet-search.css" />
    <!-- Geolocation CSS Library for Plugin -->
    <link rel="stylesheet"
        href="https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-locatecontrol/v0.43.0/L.Control.Locate.css" />
    <!-- Leaflet Mouse Position CSS Library -->
    <link rel="stylesheet" href="assets/plugins/leaflet-mouseposition/L.Control.MousePosition.css" />
    <!-- Leaflet Measure CSS Library -->
    <link rel="stylesheet" href="assets/plugins/leaflet-measure/leaflet-measure.css" />
    <!-- EasyPrint CSS Library -->
    <link rel="stylesheet" href="assets/plugins/leaflet-easyprint/easyPrint.css" />
    <style>
        #map {
            height: 97.5vh;
        }

        .info {
            padding: 6px 8px;
            font: 14px/16px Arial, Helvetica, sans-serif;
            background: white;
            background: rgba(255, 255, 255, 0.8);
            box-shadow: 0 0 15px rgba(0, 0, 0, 0.2);
            border-radius: 5px;
            text-align: center;
        }

        .info h2 {
            margin: 0 0 5px;
            color: #777;
        }
    </style>
</head>

<body>
    <div class="container mt-2"></div> <!-- Move this div to the body section -->

    <div id="map"></div>

    <!-- Include your GeoJSON data -->
    <script src="./data.js"></script>

    <!-- Leaflet and Plugins -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="assets/plugins/leaflet-markercluster/leaflet.markercluster.js"></script>
    <script src="assets/plugins/leaflet-markercluster/leaflet.markercluster-src.js"></script>
    <script src="assets/plugins/leaflet-routing/leaflet-routing-machine.js"></script>
    <script src="assets/plugins/leaflet-routing/leaflet-routing-machine.min.js"></script>
    <script src="assets/plugins/leaflet-search/leaflet-search.js"></script>
    <script
        src="https://api.tiles.mapbox.com/mapbox.js/plugins/leaflet-locatecontrol/v0.43.0/L.Control.Locate.min.js"></script>
    <script src="assets/plugins/leaflet-mouseposition/L.Control.MousePosition.js"></script>
    <script src="assets/plugins/leaflet-measure/leaflet-measure.js"></script>
    <script src="assets/plugins/leaflet-easyprint/leaflet.easyPrint.js"></script>

    <script>
        // Initialize the map
        var map = L.map("map").setView([-7.7956, 110.3695], 10);

        // Basemaps
        var basemap1 = L.tileLayer(
            "https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png",
            {
                maxZoom: 19,
                attribution:
                    'Map data © <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
            }
        );
        basemap1.addTo(map);

        // Create a GeoJSON layer for polygon data
        var Surabaya = L.geoJson(null, {
            style: function (feature) {
                // Adjust this function to define styles based on your polygon properties
                var value = feature.properties.nama; // Change this to your actual property name
                return {
                    fillColor: getColor(value),
                    weight: 2,
                    opacity: 1,
                    color: "red",
                    dashArray: "3",
                    fillOpacity: 0.5,
                };
            },
            onEachFeature: function (feature, layer) {
                // Adjust the popup content based on your polygon properties
                var content =
                    "KECAMATAN: " +
                    feature.properties.KECAMATAN +
                    "<br>";

                layer.bindPopup(content);
            },
        });

        // Fetch GeoJSON data Surabaya.php
        $.getJSON("Surabaya.php", function (data) {
            Surabaya.addData(data);
            Surabaya.addTo(map);
            map.fitBounds(Surabaya.getBounds());
        });

        // Create a marker cluster group
    var markers = L.markerClusterGroup();

<?php
// Koneksi ke database dan ambil data
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "responsifarah";
$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM perbelanjaan";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while($row = $result->fetch_assoc()) {
        $Nama = $row["Nama"];
        $Jam_Buka = $row["Jam_Buka"];
        $Jam_Tutup = $row["Jam_Tutup"];
        $Latitude = $row["Latitude"];
        $Longitude = $row["Longitude"];
        // Tambahkan marker ke dalam marker cluster group
        echo "var marker = L.marker([$Latitude, $Longitude]).bindPopup(' Nama : $Nama <br> Jam Buka : $Jam_Buka <br> Jam Tutup : $Jam_Tutup');";
        echo "markers.addLayer(marker);";
    }
    // Tambahkan marker cluster group ke dalam peta
    echo "map.addLayer(markers);";
} else {
    echo "console.log('0 results');";
}
$conn->close();
?>
    
    // Title
    var title = new L.Control();
        title.onAdd = function (map) {
            this._div = L.DomUtil.create("div", "info");
            this.update();
            return this._div;
        };
        title.update = function () {
            this._div.innerHTML =
                '<h2>SHOPSPOT SURABAYA : EKSPLORASI MALL DI SURABAYA</h2>RESPONSI MATAKULIAH PEMROGRAMAN GEOSPASIAL : WEB';
        };
        title.addTo(map);

        /* Image Watermark */
        L.Control.Watermark = L.Control.extend({
            onAdd: function (map) {
                var img = L.DomUtil.create("img");
                img.src = "assets/img/logo/crown.png";
                img.style.width = "120px";
                return img;
            },
        });

        L.control.watermark = function (opts) {
            return new L.Control.Watermark(opts);
        };

        L.control.watermark({ position: "bottomleft" }).addTo(map);

        /* Image Legend */
        L.Control.Legend = L.Control.extend({
            onAdd: function (map) {
                var img = L.DomUtil.create('img');
                img.src = 'assets/img/legend/legendaresponsi.png';
                img.style.width = '280px';
                return img;
            }
        });
        L.control.Legend = function (opts) {
            return new L.Control.Legend(opts);
        }
        L.control.Legend({ position: 'bottomleft' }).addTo(map);

        /* Tile Basemap */
        var basemap1 = L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '<a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> | <a href="DIVSIGUGM" target="_blank">DIVSIG UGM</a>' //menambahkan nama//
        });

        var basemap2 = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Street_Map/MapServer/tile/ { z } / { y } / { x }', {
            attribution: 'Tiles &copy; Esri | <a href="Latihan WebGIS" target="_blank">DIVSIG UGM</a>'
        });

        var basemap3 = L.tileLayer('https://server.arcgisonline.com/ArcGIS/rest/services/World_Imagery/MapServer/tile/{z}/{y}/{ x }', {
            attribution: 'Tiles & copy; Esri | <a href="Lathan WebGIS" target="_blank">DIVSIGUGM</a>'

        });

        var basemap4 = L.tileLayer('https://tiles.stadiamaps.com/tiles/alidade_smooth_dark/{z}/{x}/{y}{r}.png', {
            attribution: '&copy; <a href="https://stadiamaps.com/">Stadia Maps</a>, &copy; <a href="https://openmaptiles.org / ">OpenMapTiles</a> &copy; <a href="http://openstreetmap.org">OpenStreetMap</a> contributors'
        });

        basemap1.addTo(map);

        var baseMaps = {
            "OpenStreetMap": basemap1,
            "Esri World Street": basemap2,
            "Esri Imagery": basemap3,
            "Stadia Dark Mode": basemap4,
        };

        L.control.layers(baseMaps).addTo(map);

        // Plugin Search
        var searchControl = new L.Control.Search({
            position: "topleft",
            layer: Surabaya, // Nama variabel layer
            propertyName: "KECAMATAN", // Field untuk pencarian
            marker: false,
            moveToLocation: function (latlng, title, map) {
                var zoom = map.getBoundsZoom(latlng.layer.getBounds());
                map.setView(latlng, zoom);
            },
        });

        searchControl
            .on("search:locationfound", function (e) {
                e.layer.setStyle({
                    fillColor: "#ffff00",
                    color: "#0000ff",
                });
            })
            .on("search:collapse", function (e) {
                Surabaya.eachLayer(function (layer) {
                    Surabaya.resetStyle(layer);
                });
            });

        map.addControl(searchControl);

        // Plugin Geolocation
        var locateControl = L.control
            .locate({
                position: "topleft",
                drawCircle: true,
                follow: true,
                setView: true,
                keepCurrentZoomLevel: false,
                markerStyle: {
                    weight: 1,
                    opacity: 0.8,
                    fillOpacity: 0.8,
                },
                circleStyle: {
                    weight: 1,
                    clickable: false,
                },
                icon: "fas fa-crosshairs",
                metric: true,
                strings: {
                    title: "Click for Your Location",
                    popup: "You're here. Accuracy {distance} {unit}",
                    outsideMapBoundsMsg: "Not available",
                },
                locateOptions: {
                    maxZoom: 16,
                    watch: true,
                    enableHighAccuracy: true,
                    maximumAge: 10000,
                    timeout: 10000,
                },
            })
            .addTo(map);

        // Plugin Measurement Tool
        var measureControl = new L.Control.Measure({
            position: "topleft",
            primaryLengthUnit: "meters",
            secondaryLengthUnit: "kilometers",
            primaryAreaUnit: "hectares",
            secondaryAreaUnit: "sqmeters",
            activeColor: "#FF0000",
            completedColor: "#00FF00",
        });

        measureControl.addTo(map);

        // Plugin EasyPrint
        L.easyPrint({
            title: "Print",
        }).addTo(map);

        // Plugin Mouse Position Coordinate
        L.control
            .mousePosition({
                position: "bottomright",
                separator: ",",
                prefix: "Point Coodinate: ",
            })
            .addTo(map);

        // Plugin Routing
        L.Routing.control({
            waypoints: [
                L.latLng(-7.2631794, 112.7362869),
                L.latLng(-7.2619632, 112.7474195),
            ],
            routeWhileDragging: true,
        }).addTo(map);

        // Function to determine the color based on the 'value' attribute
        function getColor(value) {
            return value > 75000
                ? "#67000d"
                : value > 50000
                    ? "#fb7050"
                    : value > 10
                        ? "#fff5f0"
                        : "#fff5f0";
        }
    </script>
</body>
</html>