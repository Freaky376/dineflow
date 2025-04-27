@extends('tenantviews.tenantlayout.tenantlayout')

@section('content')
<!-- Main Content -->
<div class="col-md-9">
    <div class="card mb-4">
        <div class="card-header bg-primary text-white">
            Dashboard
        </div>
        <div class="card-body">
            <h5 class="card-title">Welcome!</h5>
            <p class="card-text">This is your dashboard, {{ $tenantName }}.</p>
            <p class="card-text">Easily manage users, your café's menu, and more.</p>
        </div>
    </div>

    <!-- 🌍 OpenStreetMap Location Widget -->
    <div class="card mb-4">
        <div class="card-header bg-success text-white">
            Your Location (Map)
        </div>
        <div class="card-body p-0">
            <div id="map" style="height: 400px;"></div>
        </div>
    </div>
</div>

<!-- Load jQuery FIRST -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<!-- Leaflet.js -->
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>


<!-- Live Map Script -->
<script>
    document.addEventListener('DOMContentLoaded', () => {
        const map = L.map('map').setView([14.5995, 120.9842], 13); // Default to Manila

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors'
        }).addTo(map);

        L.marker([14.5995, 120.9842]).addTo(map)
            .bindPopup('Default Marker: Manila')
            .openPopup();

        // Optionally get user’s location
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(position => {
                const lat = position.coords.latitude;
                const lon = position.coords.longitude;
                map.setView([lat, lon], 15);

                L.marker([lat, lon]).addTo(map)
                    .bindPopup("You're here!")
                    .openPopup();
            });
        }
    });
</script>
@endsection
