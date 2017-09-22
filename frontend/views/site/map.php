<div class="main_heading">
    <?= Yii::t('app', 'Карта коррупции'); ?>
</div>
<div id="map"></div>
<script>

    function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {
            zoom: 6,
            center: {lat: 41.2044, lng: 74.7661}
        });

        // Create an array of alphabetical characters used to label the markers.
        var labels = 'ABCDEFGHIJKLMNOPQRSTUVWXYZ';

        // Add some markers to the map.
        // Note: The code uses the JavaScript Array.prototype.map() method to
        // create an array of markers based on a given "locations" array.
        // The map() method here has nothing to do with the Google Maps API.
        var markers = locations.map(function (location, i) {
            return new google.maps.Marker({
                position: location,
                label: labels[i % labels.length]
            });
        });

        // Add a marker clusterer to manage the markers.
        var markerCluster = new MarkerClusterer(map, markers,
            {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});
    }

    var locations = [
        {lat: 42.8771, lng: 74.5698},
        {lat: 42.4782, lng: 78.3956},
    ]
</script>
<script src="https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js">
</script>
<script async defer
        src="https://maps.googleapis.com/maps/api/js?key=AIzaSyDDyJXbc-D_sxlQgbxS6fa-ImOsz1dyyQs&callback=initMap">
</script>