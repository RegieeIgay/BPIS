<!DOCTYPE html>
<html>
<head>
  <title>Location Map - BPIS</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
  <style>
    html, body {
      height: 100%;
      margin: 0;
    }

    .full-height {
      height: 100vh;
    }

    #map {
      height: calc(100vh - 120px); /* Adjust if your navbar is taller or shorter */
      width: 100%;
    }

    #search-container {
      margin: 10px 0;
    }

    #search-bar {
      width: 300px;
    }

    .no-padding {
      padding: 0 !important;
      margin: 0 !important;
    }
  </style>
</head>
<body>
  <!-- Include Navbar -->
  <?php include 'navbar.php'; ?>

  <div class="container-fluid no-padding">
    <div class="row no-padding">
      <!-- Sidebar -->
      <div class="col-md-3 col-lg-2 bg-light full-height no-padding">
        <?php include 'sidebar.php'; ?>
      </div>

      <!-- Main Content -->
      <div class="col-md-9 col-lg-10 full-height no-padding d-flex flex-column">
        <div class="p-3">
          <h3>📍 Location Map</h3>

          <!-- Search Bar and Button -->
          <div id="search-container" class="d-flex align-items-center">
            <input type="text" id="search-bar" class="form-control" placeholder="Search locations...">
            <button id="search-btn" class="btn btn-primary ms-2" onclick="searchLocations()">Search</button>
          </div>
        </div>

        <!-- Map -->
        <div id="map"></div>
      </div>
    </div>
  </div>

  <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
  <script>
    const map = L.map('map').setView([10.6500, 122.9500], 13);

    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
      maxZoom: 18,
    }).addTo(map);

    const defaultIcon = L.icon({
      iconUrl: 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png',
      iconSize: [32, 32],
      iconAnchor: [16, 32],
      popupAnchor: [0, -32]
    });

    let markers = [];

    function loadMarkers(searchTerm = '') {
      fetch('mapping.php?search=' + encodeURIComponent(searchTerm))
        .then(response => response.json())
        .then(data => {
          markers.forEach(marker => map.removeLayer(marker.marker));
          markers = [];

          data.forEach(loc => {
            const marker = L.marker([loc.latitude, loc.longitude], {
              icon: defaultIcon
            }).addTo(map);

            marker.bindPopup(`<strong>${loc.building_name}</strong><br>${loc.description}`);
            markers.push({ marker, latitude: loc.latitude, longitude: loc.longitude, building_name: loc.building_name.toLowerCase() });
          });
        })
        .catch(error => console.error('Error loading location data:', error));
    }

    loadMarkers();

    function searchLocations() {
      const searchTerm = document.getElementById('search-bar').value.toLowerCase();
      const matchingLocation = markers.find(marker => marker.building_name.includes(searchTerm));

      if (matchingLocation) {
        map.setView([matchingLocation.latitude, matchingLocation.longitude], 15);
        matchingLocation.marker.openPopup();
      } else {
        alert('No matching location found!');
      }
    }
  </script>
</body>
</html>
