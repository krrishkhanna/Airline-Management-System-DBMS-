<?php
header('Content-Type: application/json');

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

try {
    // Get the most recent status changes
    $sql = "SELECT flight_number, old_status, new_status, change_date 
            FROM flight_logs 
            ORDER BY change_date DESC 
            LIMIT 10";
            
    $result = $conn->query($sql);
    
    if ($result === false) {
        throw new Exception("Error fetching logs: " . $conn->error);
    }
    
    $logs = [];
    while ($row = $result->fetch_assoc()) {
        $logs[] = [
            'flight_number' => $row['flight_number'],
            'old_status' => $row['old_status'],
            'new_status' => $row['new_status'],
            'change_date' => $row['change_date']
        ];
    }
    
    echo json_encode([
        'success' => true,
        'data' => $logs
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

$conn->close();
?> 