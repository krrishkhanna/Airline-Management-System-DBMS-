<?php
// Set headers for JSON response
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "airline";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    http_response_code(500);
    die(json_encode([
        'error' => true,
        'message' => 'Connection failed: ' . $conn->connect_error
    ]));
}

// If accessed directly with no query params, show a test message
if (empty($_GET) && php_sapi_name() !== 'cli') {
    echo json_encode([
        'error' => false,
        'message' => 'fetch_data.php is reachable and database connection succeeded.'
    ]);
    $conn->close();
    exit;
}

// Get filter parameters
$search = isset($_GET['search']) ? $_GET['search'] : '';
$status = isset($_GET['status']) ? $_GET['status'] : '';

// Build query with filters
$sql = "SELECT * FROM flights";
$whereConditions = [];

if (!empty($search)) {
    $search = $conn->real_escape_string($search);
    $whereConditions[] = "(Name LIKE '%$search%' OR Flight LIKE '%$search%' OR FromLocation LIKE '%$search%' OR ToLocation LIKE '%$search%')";
}

if (!empty($status) && $status !== 'all') {
    $status = $conn->real_escape_string($status);
    $whereConditions[] = "Status = '$status'";
}

if (!empty($whereConditions)) {
    $sql .= " WHERE " . implode(" AND ", $whereConditions);
}

// Add sorting
$sql .= " ORDER BY DepartureDate, ArrivalTime";

// Fetch all flights
$result = $conn->query($sql);

if ($result === false) {
    echo json_encode([
        'error' => true,
        'message' => 'Error executing query: ' . $conn->error
    ]);
} else {
    $flights = [];
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $flights[] = $row;
        }
    }
    
    echo json_encode([
        'error' => false,
        'count' => count($flights),
        'data' => $flights
    ]);
}

$conn->close();
?> 