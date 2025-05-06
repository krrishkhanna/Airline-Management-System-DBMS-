<?php
// Set headers for JSON response
header('Content-Type: application/json');

// Enable error reporting for debugging
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "airline";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die(json_encode([
        'success' => false,
        'message' => 'Connection failed: ' . $conn->connect_error
    ]));
}

// Check if it's a POST request
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request method'
    ]);
    exit;
}

try {
    // Prepare SQL statement
    $sql = "INSERT INTO flights (Name, Flight, AirplaneModel, Manufacturer, DepartureDate, 
            ArrivalTime, FromLocation, ToLocation, PilotName, CabinCrew, Passengers, Status) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
    
    $stmt = $conn->prepare($sql);
    
    if ($stmt === false) {
        throw new Exception("Error preparing statement: " . $conn->error);
    }
    
    // Bind parameters
    $stmt->bind_param("ssssssssssss", 
        $_POST['Name'],
        $_POST['Flight'],
        $_POST['AirplaneModel'],
        $_POST['Manufacturer'],
        $_POST['DepartureDate'],
        $_POST['ArrivalTime'],
        $_POST['FromLocation'],
        $_POST['ToLocation'],
        $_POST['PilotName'],
        $_POST['CabinCrew'],
        $_POST['Passengers'],
        $_POST['Status']
    );
    
    // Execute the statement
    if ($stmt->execute()) {
        echo json_encode([
            'success' => true,
            'message' => 'Flight added successfully',
            'id' => $conn->insert_id
        ]);
    } else {
        throw new Exception("Error executing statement: " . $stmt->error);
    }
    
    $stmt->close();
    
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

$conn->close();
?> 