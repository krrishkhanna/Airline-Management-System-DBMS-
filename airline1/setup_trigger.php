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
    // Create flight_logs table if it doesn't exist
    $conn->query("DROP TABLE IF EXISTS flight_logs");
    $conn->query("CREATE TABLE flight_logs (
        id INT AUTO_INCREMENT PRIMARY KEY,
        flight_id INT,
        flight_number VARCHAR(50),
        old_status VARCHAR(50),
        new_status VARCHAR(50),
        change_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP
    )");

    // Drop existing trigger if it exists
    $conn->query("DROP TRIGGER IF EXISTS after_flight_update");

    // Create trigger for tracking flight status changes
    $trigger_sql = "
    CREATE TRIGGER after_flight_update 
    AFTER UPDATE ON flights
    FOR EACH ROW 
    BEGIN
        IF OLD.Status != NEW.Status THEN
            INSERT INTO flight_logs (flight_id, flight_number, old_status, new_status)
            VALUES (NEW.id, NEW.Flight, OLD.Status, NEW.Status);
        END IF;
    END;
    ";

    if ($conn->multi_query($trigger_sql)) {
        echo json_encode([
            'success' => true,
            'message' => 'Trigger created successfully'
        ]);
    } else {
        throw new Exception("Error creating trigger: " . $conn->error);
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

$conn->close();
?> 