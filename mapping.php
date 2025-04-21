<?php
// Database connection parameters
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "bpis"; // Change to your actual database name

// Create a connection
$conn = new mysqli($host, $user, $pass, $dbname);

// Check the connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if there is a search term
$searchTerm = isset($_GET['search']) ? "%" . $conn->real_escape_string($_GET['search']) . "%" : "%";

// SQL query to fetch all locations with a filter for building name
$sql = "SELECT id, building_name, latitude, longitude, description FROM locations WHERE building_name LIKE ?";

// Prepare the statement to avoid SQL injection
$stmt = $conn->prepare($sql);
$stmt->bind_param('s', $searchTerm); // Bind the search term

// Execute the query
$stmt->execute();

// Get the result
$result = $stmt->get_result();

// Initialize an empty array to store location data
$locations = [];

// Fetch all rows from the query result
while ($row = $result->fetch_assoc()) {
    $locations[] = $row; // Add each location to the array
}

// Set the response header to JSON
header('Content-Type: application/json');

// Encode the $locations array as JSON and output it
echo json_encode($locations);

// Close the database connection
$conn->close();
?>
