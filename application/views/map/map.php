<html>

<head>
    <title>Leaflet Realtime - Earthquakes</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.1.0/dist/leaflet.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.0.6/dist/MarkerCluster.css" />
    <link rel="stylesheet" href="https://unpkg.com/leaflet.markercluster@1.0.6/dist/MarkerCluster.Default.css" />
    <style>
        #map {
            position: absolute;
            top: 25;
            left: 25;
            bottom: 25;
            right: 25;
        }
    </style>
</head>

<body>
    <div id="map"></div>

    <script src="https://unpkg.com/leaflet@1.1.0/dist/leaflet-src.js"></script>
    <script src="https://unpkg.com/leaflet.markercluster@1.0.6/dist/leaflet.markercluster-src.js"></script>
    <script src="https://unpkg.com/leaflet.featuregroup.subgroup"></script>
    <script src="<?php echo base_url('application/views/map/leaflet-realtime.js'); ?>"></script>
    <script>
        function createRealtimeLayer(url, container) {
            return L.realtime(url, {
                interval: 60 * 10000,
                getFeatureId: function(f) {
                    return f.properties.url;
                },
                cache: true,
                container: container,
                onEachFeature(f, l) {
                    //console.log(f);
                    l.bindTooltip(f.properties.place);
                    l.bindPopup(function() {
                        return (
                            "<h3>" +
                            f.properties.place +
                            "</h3>" +
                            //'<p>' + new Date(f.properties.time) +
                            "<br/>Value: <strong>" +
                            f.properties.mag +
                            "</strong></p>" +
                            '<p><a href="' +
                            f.properties.url +
                            '">More information</a></p>'
                        );
                    });
                }
            });
        }

        var map = L.map("map", {
                minZoom: 15,
                maxZoom: 50,
            }),
            clusterGroup = L.markerClusterGroup({
                'showCoverageOnHover': true,
                'zoomToBoundsOnClick': true,
                'spiderfyOnMaxZoom': false,
                /*iconCreateFunction: function(cluster) {
		return L.divIcon({ html: '<b>' + cluster.getChildCount() + '</b>' });
    },*/
                /*spiderfyShapePositions: function(count, centerPt) {
                    var distanceFromCenter = 35,
                        markerDistance = 45,
                        lineLength = markerDistance * (count - 1),
                        lineStart = centerPt.y - lineLength / 2,
                        res = [],
                        i;

                    res.length = count;

                    for (i = count - 1; i >= 0; i--) {
                        res[i] = new Point(centerPt.x + distanceFromCenter, lineStart + markerDistance * i);
                    }
                    return res;
                },*/
            }).addTo(map),
            subgroup1 = L.featureGroup.subGroup(clusterGroup),
            subgroup2 = L.featureGroup.subGroup(clusterGroup),
            realtime1 = createRealtimeLayer("<?php echo base_url("rmap/personnel");?>", subgroup1).addTo(map);
        realtime2 = createRealtimeLayer("<?php echo base_url("rmap/visitor");?>", subgroup2);

        L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", { //http://{s}.tile.osm.org/{z}/{x}/{y}.png
            attribution: '&copy; <a href="https://rtls.mobiki.in">Mobiki</a>'
        }).addTo(map);

        /*var clusterGroup = new L.CircleMarker(1, 2, 1, {
            color: 'blue',
            fillColor: '#bbf',
            fillOpacity: 0.5
        });*/
        /*L.getLayers().forEach(function(obj) {
            if (obj instanceof L.Marker) { // test if the object is a marker
                // get the position of the marker with getLatLng
                // and draw a circle at that position
                L.circle(obj.getLatLng(), 1609.34, {
                    color: 'blue',
                    fillColor: 'blue'
                }).addTo(map);
            }
        });*/
        L.control
            .layers(null, {
                Personnel: realtime1,
                Visitors: realtime2
            })
            .addTo(map);

        realtime1.once("update", function() {
            map.fitBounds(realtime1.getBounds(), {
                maxZoom: 1
            });
        });
    </script>
</body>

</html>