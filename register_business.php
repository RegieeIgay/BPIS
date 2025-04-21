<?php
$message = ''; // Initialize the message variable

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['confirm'])) {
        // Location fields
        $building    = $_POST["building"];
        $town_city   = $_POST["town_city"];
        $country     = $_POST["country"];
        $description = $_POST["description"];

        // Business permit fields
        $permit_number = $_POST["permit_number"];
        $issued_date   = $_POST["issued_date"];
        $expiry_date   = $_POST["expiry_date"];
        $status        = "valid"; // Default status is 'valid'

        // Full address for geocoding
        $address = urlencode("$building, $town_city, $country");

        // Geocode with Nominatim
        $url = "https://nominatim.openstreetmap.org/search?q=$address&format=json&limit=1&addressdetails=1";
        $options = ['http' => ['header' => "User-Agent: SMS\r\n"]];
        $context = stream_context_create($options);
        $response = file_get_contents($url, false, $context);
        $data = json_decode($response, true);

        if (!empty($data)) {
            $lat = $data[0]['lat'];
            $lon = $data[0]['lon'];

            $conn = new mysqli("localhost", "root", "", "bpis");
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            // Insert into permit table FIRST
            $stmt1 = $conn->prepare("INSERT INTO permit (permit_number, issued_date, expiry_date, status) VALUES (?, ?, ?, ?)");
            $stmt1->bind_param("ssss", $permit_number, $issued_date, $expiry_date, $status);
            $stmt1->execute();

            // Insert into locations table
            $stmt2 = $conn->prepare("INSERT INTO locations (building_name, town_city, country, description, latitude, longitude, permit_number) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt2->bind_param("sssssss", $building, $town_city, $country, $description, $lat, $lon, $permit_number);
            $stmt2->execute();

            $message = "<p class='alert alert-success'>✅ Location and Permit saved successfully!</p>";
            $message .= "<div style='text-align:center;'><a href='map.html' class='btn btn-success'>View on Map</a></div>";

            $stmt1->close();
            $stmt2->close();
            $conn->close();
        } else {
            $message = "<p class='alert alert-danger'>❌ Location not found. Please check your address.</p>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Add New Location & Permit</title>
  <!-- Bootstrap CSS -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
</head>
<body>

<!-- Include Navbar -->
<?php include 'navbar.php'; ?>

<!-- Layout: Sidebar + Main Content -->
<div class="d-flex" style="height: 100vh; overflow: hidden;">
  <?php include 'sidebar.php'; ?>

  <div class="flex-grow-1 overflow-auto">
    <div class="container py-4">
      <div class="card p-4 shadow-lg rounded" style="max-width: 600px; margin: auto;">
        <h2 class="text-center mb-4">Add a Location and Permit</h2>
        <?= $message ?>

        <form method="POST" action="">
          <h3>Location Information</h3>
          <div class="mb-3">
            <label for="building" class="form-label">Building:</label>
            <input type="text" class="form-control" id="building" name="building" required>
          </div>
          <div class="mb-3">
            <label for="town_city" class="form-label">Town/City:</label>
            <input type="text" class="form-control" id="town_city" name="town_city" required>
          </div>
          <div class="mb-3">
            <label for="country" class="form-label">Country:</label>
            <input type="text" class="form-control" id="country" name="country" required>
          </div>
          <div class="mb-3">
            <label for="description" class="form-label">Description:</label>
            <textarea class="form-control" id="description" name="description" rows="3" required></textarea>
          </div>

          <h3>Permit Information</h3>
          <div class="mb-3">
            <label for="permit_number" class="form-label">Permit Number:</label>
            <input type="text" class="form-control" id="permit_number" name="permit_number" required>
          </div>
          <div class="mb-3">
            <label for="issued_date" class="form-label">Issued Date:</label>
            <input type="date" class="form-control" id="issued_date" name="issued_date" required>
          </div>
          <div class="mb-3">
            <label for="expiry_date" class="form-label">Expiry Date:</label>
            <input type="date" class="form-control" id="expiry_date" name="expiry_date" required>
          </div>

          <button type="submit" name="confirm" class="btn btn-primary w-100">Confirm & Save</button>
        </form>
      </div>
    </div>
  </div>
</div>

<!-- Bootstrap JS, Popper.js -->
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"></script>
</body>
</html>
