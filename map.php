<!DOCTYPE html>
<html>
<head>
  <title>Location Map - BPIS</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
  <style>
    #map { height: 500px; width: 100%; }
    #search-bar { margin-bottom: 10px; }
    #search-container {
      margin-bottom: 10px;
    }
    #search-btn {
      padding: 6px 12px;
      margin-left: 10px;
      cursor: pointer;
    }
  </style>
</head>
<body>

<h3>📍 Location Map</h3>

<!-- Search Bar and Search Button -->
<div id="search-container">
  <input type="text" id="search-bar" placeholder="Search locations...">
  <button id="search-btn" onclick="searchLocations()">Search</button>
</div>

<div id="map"></div>

<script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
<script>
// Initialize the map
const map = L.map('map').setView([10.6500, 122.9500], 13);

// Load OpenStreetMap tiles
L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
  maxZoom: 18,
}).addTo(map);

// Create a simple blue marker icon for default markers
const defaultIcon = L.icon({
  iconUrl: 'https://maps.google.com/mapfiles/ms/icons/blue-dot.png',
  iconSize: [32, 32],
  iconAnchor: [16, 32],
  popupAnchor: [0, -32]
});

// Store markers globally to use in search function
let markers = [];

// Fetch markers from PHP
function loadMarkers(searchTerm = '') {
  fetch('mapping.php?search=' + encodeURIComponent(searchTerm))
    .then(response => response.json())
    .then(data => {
      // Clear existing markers
      markers.forEach(marker => map.removeLayer(marker.marker));
      markers = [];

      data.forEach(loc => {
        const marker = L.marker([loc.latitude, loc.longitude], {
          icon: defaultIcon
        }).addTo(map);

        let popupContent = ` 
          <strong>${loc.building_name}</strong><br>
          ${loc.description}
        `;
        marker.bindPopup(popupContent);
        markers.push({ marker, latitude: loc.latitude, longitude: loc.longitude, building_name: loc.building_name.toLowerCase() });
      });
    })
    .catch(error => console.error('Error loading location data:', error));
}

// Initial load of markers
loadMarkers();

// Function to search locations by name
function searchLocations() {
  const searchTerm = document.getElementById('search-bar').value.toLowerCase();
  const matchingLocation = markers.find(marker => marker.building_name.includes(searchTerm));

  if (matchingLocation) {
    // Zoom and center the map on the searched location
    map.setView([matchingLocation.latitude, matchingLocation.longitude], 15); // Zoom to level 15
    matchingLocation.marker.openPopup();  // Optionally, open the popup of the marker
  } else {
    alert('No matching location found!');
  }
}
</script>

</body>
</html>
