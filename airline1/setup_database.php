<?php
// Database connection parameters
$servername = "localhost";
$username = "root";
$password = "";

// Create connection
$conn = new mysqli($servername, $username, $password);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Create database
$sql = "CREATE DATABASE IF NOT EXISTS airline";
if ($conn->query($sql) === TRUE) {
    echo "Database created successfully<br>";
} else {
    echo "Error creating database: " . $conn->error . "<br>";
}

// Select the airline database
$conn->select_db("airline");

// Drop existing flights table if it exists
$sql = "DROP TABLE IF EXISTS flights";
$conn->query($sql);

// Create flights table with more detailed information
$sql = "CREATE TABLE flights (
    id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    Name VARCHAR(100) NOT NULL,
    Flight VARCHAR(30) NOT NULL,
    AirplaneModel VARCHAR(100) NOT NULL,
    Manufacturer VARCHAR(100) NOT NULL,
    DepartureDate DATE NOT NULL,
    ArrivalTime TIME NOT NULL,
    FromLocation VARCHAR(50) NOT NULL,
    ToLocation VARCHAR(50) NOT NULL,
    PilotName VARCHAR(100) NOT NULL,
    CabinCrew INT(3) NOT NULL,
    Passengers INT(4) NOT NULL,
    Status VARCHAR(30) NOT NULL
)";

if ($conn->query($sql) === TRUE) {
    echo "Table flights created successfully<br>";
} else {
    echo "Error creating table: " . $conn->error . "<br>";
}

// Insert sample data
$sql = "INSERT INTO flights (Name, Flight, AirplaneModel, Manufacturer, DepartureDate, ArrivalTime, FromLocation, ToLocation, PilotName, CabinCrew, Passengers, Status) VALUES
    ('Air India', 'AI302', 'Boeing 787-8', 'Boeing', '2023-08-15', '14:30:00', 'DEL', 'BOM', 'Rajesh Kumar', 8, 250, 'On Time'),
    ('IndiGo', '6E123', 'Airbus A320neo', 'Airbus', '2023-08-15', '16:45:00', 'BOM', 'MAA', 'Priya Sharma', 6, 180, 'Delayed'),
    ('Vistara', 'UK789', 'Boeing 737-800', 'Boeing', '2023-08-16', '09:15:00', 'DEL', 'BLR', 'Amit Patel', 5, 150, 'On Time'),
    ('SpiceJet', 'SG456', 'Bombardier Q400', 'Bombardier', '2023-08-16', '11:20:00', 'MAA', 'DEL', 'Suresh Verma', 4, 80, 'Cancelled'),
    ('Air India Express', 'IX512', 'Boeing 737-800', 'Boeing', '2023-08-17', '13:10:00', 'BOM', 'COK', 'Neha Gupta', 5, 160, 'On Time'),
    ('GoAir', 'G8347', 'Airbus A320', 'Airbus', '2023-08-17', '15:30:00', 'DEL', 'CCU', 'Vikram Singh', 6, 170, 'On Time'),
    ('IndiGo', '6E456', 'Airbus A321neo', 'Airbus', '2023-08-18', '10:00:00', 'BLR', 'DEL', 'Anita Desai', 7, 220, 'Delayed'),
    ('Vistara', 'UK567', 'Airbus A320neo', 'Airbus', '2023-08-18', '17:45:00', 'BOM', 'DEL', 'Rahul Mehta', 6, 180, 'On Time')";
    
if ($conn->query($sql) === TRUE) {
    echo "Sample data inserted successfully<br>";
} else {
    echo "Error inserting sample data: " . $conn->error . "<br>";
}

echo "Setup completed successfully!";
$conn->close();
?> 