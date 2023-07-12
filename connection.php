<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "cbi";

// Create a connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set the time zone
$query = "SET time_zone = '+10:00'";
$result = $conn->query($query);

if (!$result) {
    die("Error setting time zone: " . $conn->error);
}

// Continue with your database operations
// ...

date_default_timezone_set('Australia/Melbourne');

// Now you can use PHP date/time functions with the specified time zone
$currentDateTime = date('Y-m-d H:i:s');
//echo "Current date and time in Melbourne: " . $currentDateTime;

?>
