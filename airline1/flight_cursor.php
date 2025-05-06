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
    // Create a temporary table to store processed results
    $conn->query("DROP TABLE IF EXISTS flight_summary");
    $conn->query("CREATE TEMPORARY TABLE flight_summary (
        airline_name VARCHAR(255),
        total_flights INT,
        total_passengers INT,
        avg_passengers FLOAT,
        on_time_percentage FLOAT
    )");

    // Declare and initialize variables for cursor
    $result = $conn->query("SELECT Name, Status, Passengers FROM flights");
    
    // Variables to store aggregated data
    $airlines = [];
    
    // Process each row (simulating cursor behavior since MySQL PHP doesn't directly support cursors)
    while ($row = $result->fetch_assoc()) {
        $airline = $row['Name'];
        
        if (!isset($airlines[$airline])) {
            $airlines[$airline] = [
                'total_flights' => 0,
                'total_passengers' => 0,
                'on_time_flights' => 0
            ];
        }
        
        $airlines[$airline]['total_flights']++;
        $airlines[$airline]['total_passengers'] += intval($row['Passengers']);
        if ($row['Status'] === 'On Time') {
            $airlines[$airline]['on_time_flights']++;
        }
    }
    
    // Insert processed data into temporary table
    foreach ($airlines as $airline => $stats) {
        $avg_passengers = $stats['total_passengers'] / $stats['total_flights'];
        $on_time_percentage = ($stats['on_time_flights'] / $stats['total_flights']) * 100;
        
        $stmt = $conn->prepare("INSERT INTO flight_summary VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("siidd", 
            $airline,
            $stats['total_flights'],
            $stats['total_passengers'],
            $avg_passengers,
            $on_time_percentage
        );
        $stmt->execute();
    }
    
    // Fetch final results
    $final_result = $conn->query("SELECT * FROM flight_summary ORDER BY total_flights DESC");
    $summary = [];
    
    while ($row = $final_result->fetch_assoc()) {
        $summary[] = [
            'airline_name' => $row['airline_name'],
            'total_flights' => $row['total_flights'],
            'total_passengers' => $row['total_passengers'],
            'avg_passengers' => round($row['avg_passengers'], 2),
            'on_time_percentage' => round($row['on_time_percentage'], 1) . '%'
        ];
    }
    
    echo json_encode([
        'success' => true,
        'data' => $summary
    ]);

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

$conn->close();
?> 