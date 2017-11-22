$(window).load(function(){
    //report create anonymous check
    $('.input_checker').change(function() {
        var input=$("#user-contact input");
        if($(this).is(":checked")) {
            input.val('');
            input.prop('disabled', true);
            $(this).val(1);
            $('.field-report-author').removeClass('has-error').find('.help-block').hide();
        }
        else
        {
            input.prop('disabled', false);
            $(this).val(0);
        }
    });

    //tooltip, popover
    $('[data-toggle="tooltip"]').tooltip();
    $('[data-toggle="popover"]').popover();

    $('.warning-link').click(function (e) {
        e.preventDefault();
        $('#update-modal')
            .modal('show')
            .find('#updateModalContent')
            .load($(this).attr('value'));
    });
});


//google map
function loadScript() {
    var script = document.createElement("script");
    script.type = "text/javascript";
    script.src = "https://maps.googleapis.com/maps/api/js?key=AIzaSyDDyJXbc-D_sxlQgbxS6fa-ImOsz1dyyQs&callback=initMap";
    document.body.appendChild(script);
}

var map;
function initMap() {
    var uluru = {lat: 41.2044, lng: 74.7661};

    var defaultLat = parseFloat($('.report_lat').val());
    var defaultLon = parseFloat($('.report_lon').val());


    map = new google.maps.Map(document.getElementById('map'), {
        zoom: 6,
        center: uluru
    });
    var marker = new google.maps.Marker({
        map: map,
        draggable: true,
        //position:{lat: 41.2044, lng: 74.7661}

    });

    if(defaultLat!=0 && defaultLon!=0) {
        placeMarker({lat: defaultLat, lng: defaultLon});
    }

    google.maps.event.addListener(marker, 'dragend', function (a) {
        $('.report_lat').val(a.latLng.lat().toFixed(4));
        $('.report_lon').val(a.latLng.lng().toFixed(4));
    });
    google.maps.event.addListener(map, 'click', function (event) {
        placeMarker(event.latLng);
        $('.report_lat').val(event.latLng.lat().toFixed(4));
        $('.report_lon').val(event.latLng.lng().toFixed(4));
    });

    function placeMarker(location) {
        if (marker == undefined) {
            marker = new google.maps.Marker({
                position: location,
                map: map,
                animation: google.maps.Animation.DROP,
            });
        }
        else {
            marker.setPosition(location);
        }
        //map.setCenter(location);
    }
}

function newLocation(newLat,newLng)
{
    map.setCenter({
        lat : newLat,
        lng : newLng
    });
}
function newZoom(level)
{
    map.setZoom(level);
}

$(window).load(function(){
    if($('#map').length){
        loadScript();
        var imported = document.createElement('script');
        imported.src = '/js/cities.js';
        document.body.appendChild(imported);
    }
    else if($('#map_index').length){
        var cluster = document.createElement('script');
        cluster.src = 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js';
        document.body.appendChild(cluster);
        $.ajax({
            type: 'GET',
            url: '/report/get-locations',
            //beforeSend: function () {},
            success:function(data){
                $.each(data, function() {
                    locations.push({
                        lat: parseFloat(this.lat),
                        lng: parseFloat(this.lon),
                        info:"<a href='/report/"+this.id+"'>"+this.title+"</a>"
                    });
                });
                //console.log(locations);
                loadScriptIndex();
            }
        });
    }

    else if($('#map_long').length){
        var cluster = document.createElement('script');
        cluster.src = 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/markerclusterer.js';
        document.body.appendChild(cluster);
        $.ajax({
            type: 'GET',
            url: '/report/get-locations',
            //beforeSend: function () {},
            success:function(data){
                $.each(data, function() {
                    locations.push({
                        lat: parseFloat(this.lat),
                        lng: parseFloat(this.lon),
                        info:"<a href='/report/"+this.id+"'>"+this.title+"</a>"
                    });
                });
                //console.log(locations);
                loadScriptLong();
            }
        });
    }

    $('#report-city_id').change(function () {
        console.log("changed");
        var city_id=$(this).val();
        var coord=getCityCoord(city_id);

        console.log(coord);
        newLocation(coord[0],coord[1]);
        newZoom(13);
    });
});

//site/index map
var locations=[];

function loadScriptIndex() {
    var script = document.createElement("script");
    script.type = "text/javascript";
    script.src = "https://maps.googleapis.com/maps/api/js?key=AIzaSyDDyJXbc-D_sxlQgbxS6fa-ImOsz1dyyQs&callback=initMapIndex";
    document.body.appendChild(script);
}

function loadScriptLong() {
    var script = document.createElement("script");
    script.type = "text/javascript";
    script.src = "https://maps.googleapis.com/maps/api/js?key=AIzaSyDDyJXbc-D_sxlQgbxS6fa-ImOsz1dyyQs&callback=initMapLong";
    document.body.appendChild(script);
}

function initMapIndex() {
    var map = new google.maps.Map(document.getElementById('map_index'), {
        zoom: 6,
        center: {lat: 41.2044, lng: 74.7661}
    });

    var infoWin = new google.maps.InfoWindow();

    // Add some markers to the map.
    // Note: The code uses the JavaScript Array.prototype.map() method to
    // create an array of markers based on a given "locations" array.
    // The map() method here has nothing to do with the Google Maps API.
    var markers = locations.map(function (location, i) {
        var marker= new google.maps.Marker({
            position: location
        });
        google.maps.event.addListener(marker, 'click', function(evt) {
            infoWin.setContent(location.info);
            infoWin.open(map, marker);
        });
        return marker;
    });

    // Add a marker clusterer to manage the markers.
    var markerCluster = new MarkerClusterer(map, markers,
        {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});

    //infowindow
    /*var infowindow = new google.maps.InfoWindow({
        content: "asdf"
    });
    marker.addListener('click', function() {
        infowindow.open(map, marker);
    });*/
}

function initMapLong() {
    var map = new google.maps.Map(document.getElementById('map_long'), {
        zoom: 7,
        center: {lat: 41.2044, lng: 74.7661}
    });

    var infoWin = new google.maps.InfoWindow();

    // Add some markers to the map.
    // Note: The code uses the JavaScript Array.prototype.map() method to
    // create an array of markers based on a given "locations" array.
    // The map() method here has nothing to do with the Google Maps API.
    var markers = locations.map(function (location, i) {
        var marker= new google.maps.Marker({
            position: location
        });
        google.maps.event.addListener(marker, 'click', function(evt) {
            infoWin.setContent(location.info);
            infoWin.open(map, marker);
        });
        return marker;
    });

    // Add a marker clusterer to manage the markers.
    var markerCluster = new MarkerClusterer(map, markers,
        {imagePath: 'https://developers.google.com/maps/documentation/javascript/examples/markerclusterer/m'});

    //infowindow
    /*var infowindow = new google.maps.InfoWindow({
        content: "asdf"
    });
    marker.addListener('click', function() {
        infowindow.open(map, marker);
    });*/
}
