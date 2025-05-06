<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "airline";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Additional flight records to add
$additionalFlights = [
    ['Air India', 'AI101', '2023-08-20', 'On Time'],
    ['IndiGo', '6E789', '2023-08-20', 'On Time'],
    ['Vistara', 'UK234', '2023-08-21', 'Delayed'],
    ['SpiceJet', 'SG567', '2023-08-21', 'On Time'],
    ['Air India Express', 'IX890', '2023-08-22', 'On Time'],
    ['GoAir', 'G8123', '2023-08-22', 'Cancelled'],
    ['IndiGo', '6E456', '2023-08-23', 'On Time'],
    ['Vistara', 'UK789', '2023-08-23', 'On Time'],
    ['Air India', 'AI202', '2023-08-24', 'Delayed'],
    ['SpiceJet', 'SG123', '2023-08-24', 'On Time']
];

// Insert additional flights
$successCount = 0;
$errorCount = 0;

foreach ($additionalFlights as $flight) {
    $sql = "INSERT INTO flights (Name, Flight, Date, Status) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $flight[0], $flight[1], $flight[2], $flight[3]);
    
    if ($stmt->execute()) {
        $successCount++;
    } else {
        $errorCount++;
    }
    
    $stmt->close();
}

echo "<h2>Flight Records Added</h2>";
echo "<p>Successfully added $successCount flight records.</p>";
if ($errorCount > 0) {
    echo "<p>Failed to add $errorCount flight records.</p>";
}

echo "<p><a href='dashboard.html'>Go to Dashboard</a></p>";

$conn->close();
?> 