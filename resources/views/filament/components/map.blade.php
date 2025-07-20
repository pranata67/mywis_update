<!-- CDN JQuery -->
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.1/jquery.min.js"
    integrity="sha512-v2CJ7UaYy4JwqLDIrZUI/4hqeoQieOmAZNXBeQyjo21dadnwR+8ZaIJVT8EE2iyI61OV8e6M8PP2/4hpQINQ/g=="
    crossorigin="anonymous" referrerpolicy="no-referrer"></script>

<div id="map" style="width: 90%; height: 300px;"></div>

<!-- Leaflet CSS -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />

<!-- Leaflet JavaScript -->
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

@php
use App\Models\Wisata;
$wisatas = Wisata::all(); // Get all tourist locations directly from model
@endphp

<script>
$(document).ready(function() {
    // Convert PHP collection to JS array
    var wisataLocations = @json($wisatas->map(function($wisata) {
        return [
            'coordinates' => $wisata->coordinates,
            'name' => $wisata->name
        ];
    }));

    // Initialize map container
    var map;
    var defaultLat = -7.4723;
    var defaultLng = 112.4333;
    
    // Function to initialize the map
    function initMap() {
        // Initialize map with default size and location
        map = L.map('map').setView([defaultLat, defaultLng], 13);

        // Add Google Maps tile layer
        L.tileLayer('http://{s}.google.com/vt/lyrs=m&x={x}&y={y}&z={z}', {
            maxZoom: 20,
            subdomains: ['mt0', 'mt1', 'mt2', 'mt3']
        }).addTo(map);

        // Add markers for each tourist location
        wisataLocations.forEach(function(location) {
            var coords = location.coordinates.split(/,\s*/);
            var lat = parseFloat(coords[0]);
            var lng = parseFloat(coords[1]);

            L.marker([lat, lng])
                .addTo(map)
                .bindPopup(location.name);
        });

        // Add draggable marker for input
        var inputMarker = L.marker([defaultLat, defaultLng], {
            draggable: true
        }).addTo(map);

        // Update input field on marker drag
        inputMarker.on('dragend', function(e) {
            var position = inputMarker.getLatLng();
            $('#coordinates_input').val(position.lat + ', ' + position.lng);
        });

        // Update marker position when input changes
        $('#coordinates_input').on('change', function() {
            var newCoords = $(this).val().split(/,\s*/);
            inputMarker.setLatLng([newCoords[0], newCoords[1]]);
            map.panTo([newCoords[0], newCoords[1]]);
        });
    }

    // Call initMap when the page is ready
    initMap();

    // Resize map when the window is resized
    $(window).on('resize', function() {
        // Refresh map view on window resize
        if (map) {
            map.invalidateSize();
        }
    });
});
</script>
