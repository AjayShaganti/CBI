<?php
// Define the database credentials
$servername = "localhost";
$username = "root";
$password = "";
$database = "cbi";

// Define the parent directory for the backup
$parentDirectory = '/opt/lampp/htdocs/CBi/Backup';

// Create a folder with a timestamp as the folder name
$timestamp = date('Ymd_His');
$backupDirectory = $parentDirectory . '/' . $timestamp;

// Ensure the parent directory exists
if (!is_dir($parentDirectory)) {
    echo "Parent directory does not exist: $parentDirectory";
    exit;
}

// Create the backup directory
if (!mkdir($backupDirectory, 0755)) {
    echo "Failed to create backup directory: $backupDirectory";
    exit;
}

// Establish a database connection
$connection = new mysqli($servername, $username, $password, $database);

// Check the connection
if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

// Get the list of tables in the database
$tablesQuery = "SHOW TABLES";
$tablesResult = $connection->query($tablesQuery);

// Loop through each table
while ($tableRow = $tablesResult->fetch_row()) {
    $tableName = $tableRow[0];

    // Generate a unique filename for the CSV file
    $filename = $backupDirectory . '/' . $tableName . '.csv';

    // Execute the SELECT ... INTO OUTFILE statement to export the table data
    $exportQuery = "SELECT * INTO OUTFILE '$filename' FIELDS TERMINATED BY ',' OPTIONALLY ENCLOSED BY '\"' LINES TERMINATED BY '\n' FROM $tableName";
    $exportResult = $connection->query($exportQuery);

    // Check if the export query executed successfully
    if (!$exportResult) {
        echo "Error exporting table: $tableName";
    } else {
        echo "Table exported successfully: $tableName";
    }
}

// Close the database connection
$connection->close();
?>
