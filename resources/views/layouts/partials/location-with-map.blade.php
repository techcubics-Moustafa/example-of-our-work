<button id="current_location" style="bottom: 185px;position: absolute ;">
    <img src="{{ asset('google-map/current-location.png') }}" style="width:25px;height:35px;" alt="">
</button>
@push($styles)
    <style>
        #geomap {
            width: 100%;
            height: 400px;
        }

        #current_location {
            right: 10px !important;
        }
    </style>

@endpush
@push($scripts)
    <script src="https://maps.googleapis.com/maps/api/js?key={{ Utility::getValByName('google_maps_api') }}&libraries=places&callback=initMap&v=weekly&language={{ locale() }}" async></script>
    <script>
        $(document).ready(function () {
            window.initMap = initMap;
        });

        let map, infoWindow;
        let marker;
        let geocoder;

        function initMap() {
            let initialLat = $('.search_latitude').val();
            let initialLong = $('.search_longitude').val();
            initialLat = initialLat ? initialLat : 31.224092;
            initialLong = initialLong ? initialLong : 30.0247538;
            let latlng = new google.maps.LatLng(initialLat, initialLong);

            const imageMarker = {
                url: "{{ asset('google-map/address.png') }}", // url
                scaledSize: new google.maps.Size(35, 40), // scaled size
                origin: new google.maps.Point(0, 0), // origin
                anchor: new google.maps.Point(0, 0) // anchor
            };
            const currentLocale = "{{ asset('google-map/current-location.png') }}";
            let options = {
                zoom: 12,
                center: latlng,
                mapTypeId: google.maps.MapTypeId.ROADMAP,
                mapTypeControl: false,
                streetViewControl: true,
                zoomControlOptions: {
                    style: google.maps.ZoomControlStyle.SMALL
                }
            };

            map = new google.maps.Map(document.getElementById('geomap'), options);

            geocoder = new google.maps.Geocoder();
            infoWindow = new google.maps.InfoWindow();

            /* current location */
            const locationButton = document.getElementById("current_location");
            map.controls[google.maps.ControlPosition.RIGHT_BOTTOM].push(locationButton);
            locationButton.addEventListener("click", (e) => {
                e.preventDefault();
                // Try HTML5 geolocation.
                if (navigator.geolocation) {
                    navigator.geolocation.getCurrentPosition(
                        (position) => {
                            let address = '';
                            const pos = {
                                lat: position.coords.latitude,
                                lng: position.coords.longitude,
                            };
                            latlng = new google.maps.LatLng(position.coords.latitude, position.coords.longitude);
                            $('.search_latitude').val(position.coords.latitude);
                            $('.search_longitude').val(position.coords.longitude);
                            map.setCenter(pos)
                            map.setZoom(12)
                            marker.setPosition(pos);
                            const infoWindow = new google.maps.InfoWindow();
                            getAddByLatLng(position.coords.latitude, position.coords.longitude, infoWindow, map, marker)
                        },
                        () => {
                            handleLocationError(true, infoWindow, map.getCenter());
                        }
                    );
                } else {
                    // Browser doesn't support Geolocation
                    handleLocationError(false, infoWindow, map.getCenter());
                }
            });

            var input = document.getElementById('search_location');
            map.controls[google.maps.ControlPosition.TOP_LEFT].push(input);

            var autocomplete = new google.maps.places.Autocomplete(input);
            autocomplete.bindTo('bounds', map);


            marker = new google.maps.Marker({
                map: map,
                draggable: true,
                position: latlng,
                anchorPoint: new google.maps.Point(0, -29),
                icon: imageMarker,
            });

            /* search in input map */
            autocomplete.addListener('place_changed', function () {
                infoWindow.close();
                marker.setVisible(false);
                var place = autocomplete.getPlace();
                if (!place.geometry) {
                    window.alert("Autocomplete's returned place contains no geometry");
                    return;
                }

                // If the place has a geometry, then present it on a map.
                if (place.geometry.viewport) {
                    map.fitBounds(place.geometry.viewport);
                } else {
                    map.setCenter(place.geometry.location);
                    map.setZoom(12);
                }
                /*marker.setIcon(({
                    url: place.icon,
                    size: new google.maps.Size(71, 71),
                    origin: new google.maps.Point(0, 0),
                    anchor: new google.maps.Point(17, 34),
                    scaledSize: new google.maps.Size(35, 35)
                }));*/
                //marker.setPosition(place.geometry.location);
                map.setCenter(place.geometry.location);
                map.setZoom(12)
                marker.setPosition(place.geometry.location);
                marker.setVisible(true);
                let address = '';
                if (place.address_components) {
                    address = [
                        (place.address_components[0] && place.address_components[0].short_name || ''),
                        (place.address_components[1] && place.address_components[1].short_name || ''),
                        (place.address_components[2] && place.address_components[2].short_name || '')
                    ].join(' ');
                }
                $('#address').val(address);
                $('#address_{{ locale() }}').val(address);
                infoWindow.setContent('<div><strong>' + place.name + '</strong><br>' + address);
                infoWindow.open(map, marker);

                // Location details
                /*for (var i = 0; i < place.address_components.length; i++) {
                    if(place.address_components[i].types[0] == 'postal_code'){
                        document.getElementById('postal_code').innerHTML = place.address_components[i].long_name;
                    }
                    if(place.address_components[i].types[0] == 'country'){
                        document.getElementById('country').innerHTML = place.address_components[i].long_name;
                    }
                }*/
                $('.search_latitude').val(place.geometry.location.lat());
                $('.search_longitude').val(place.geometry.location.lng());
                $('.place_id').val(place.place_id);
            });

            /* drop and down mark */
            marker.addListener('dragend', function () {
                let latlng;
                geocoder.geocode({'latLng': marker.getPosition()}, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[0]) {
                            let address = '';
                            latlng = new google.maps.LatLng(marker.getPosition().lat(), marker.getPosition().lng());
                            $('.search_latitude').val(marker.getPosition().lat());
                            $('.search_longitude').val(marker.getPosition().lng());
                            $('.place_id').val(results[0].place_id);
                            map.setCenter(marker.getPosition())
                            map.setZoom(12)
                            marker.setPosition(marker.getPosition());
                            if (results.length > 0) {
                                address = [
                                    (results[0].address_components[0] && results[0].address_components[0].short_name || ''),
                                    (results[0].address_components[1] && results[0].address_components[1].short_name || ''),
                                    (results[0].address_components[2] && results[0].address_components[2].short_name || '')
                                ].join(' ');
                            }
                            $('#address').val(address);
                            $('#address_{{ locale() }}').val(address);
                            infoWindow.setContent('<div><strong>' + address + '</strong>');
                            infoWindow.open(map, marker);
                        }
                    }
                });
            });

            /* click in map */
            map.addListener('click', function (event) {
                geocoder.geocode({'latLng': event.latLng}, function (results, status) {
                    if (status == google.maps.GeocoderStatus.OK) {
                        if (results[0]) {
                            let address = '';
                            $('.search_latitude').val(event.latLng.lat());
                            $('.search_longitude').val(event.latLng.lng());
                            $('.place_id').val(results[0].place_id);
                            map.setCenter(event.latLng)
                            map.setZoom(12)
                            marker.setPosition(event.latLng);
                            if (results.length > 0) {
                                address = [
                                    (results[0].address_components[0] && results[0].address_components[0].short_name || ''),
                                    (results[0].address_components[1] && results[0].address_components[1].short_name || ''),
                                    (results[0].address_components[2] && results[0].address_components[2].short_name || '')
                                ].join(' ');
                            }
                            $('#address').val(address);
                            $('#address_{{ locale() }}').val(address);
                            infoWindow.setContent('<div><strong>' + address + '</strong>');
                            infoWindow.open(map, marker);
                        }
                    }
                });
            });

        }

        /* current location in map */
        function handleLocationError(browserHasGeolocation, infoWindow, pos) {
            infoWindow.setPosition(pos);
            infoWindow.open(map);
            infoWindow.setContent(
                browserHasGeolocation
                    ? "Error: The Geolocation service failed."
                    : "Error: Your browser doesn't support geolocation."
            );

        }

        function getAddByLatLng(lat, lng, infoWindow, map, marker) {
            $.ajax({
                url: 'https://maps.googleapis.com/maps/api/geocode/json?latlng=' + lat + ',' + lng + '&key=AIzaSyBGNB1noDBHyu5VfUKONwJATgyFA-8Mkv4',
                method: 'GET',
                success: function (data, textStatus, jqXHR) {
                    let results = data.results;
                    let address = '';
                    if (results.length > 0) {
                        address = [
                            (results[0].address_components[0] && results[0].address_components[0].short_name || ''),
                            (results[0].address_components[1] && results[0].address_components[1].short_name || ''),
                            (results[0].address_components[2] && results[0].address_components[2].short_name || '')
                        ].join(' ');
                    }
                    $('#address').val(address);
                    $('#address_{{ locale() }}').val(address);
                    infoWindow.setContent('<div><strong>' + address + '</strong>');
                    infoWindow.open(map, marker);
                },
                error: function (jqXHR, textStatus, errorThrown) {
                    console.log(jqXHR)
                }
            })
            return false;
        }
    </script>
@endpush
